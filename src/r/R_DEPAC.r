##########################################################################################
#2015/9/30
#DEXSeq 分析 DE PAC 

#rscript "E:/sys/code/R/R_DEPAC.r" path="f:/"  ofile=NULL  intbl=t_ae_pacsd24n_flt cols=A1:A2;B1:B2:b3;c1:cc2:c3 method=DEXSeq minpat=0 donorm=NULL adj=1 sig=0.05 groups=NULL conf=dbconf_ricepac.xml
# intbl: normalized table with (gene, ftr)+<cols>
# cols: cols (rep1:rep2;r1:r2...) 同一样本的不同rep用,或:隔开，不同样本用分号隔开
# groups=NULL或不提供,则自动根据cols判断group，或 groups=A:B:C
# conf: xml conf
# minpat=1(default)
# adj=1/0(default)
# sig=0.05(default)~0.1
# method=DEXSEQ (程序中固定，不论method是何参数)
# donorm=null/edgeR/deseq/TPM
# ofile=output file name; if NULL, then like <t_arab_pac.gene.grp1Xgrp2.DESEQ2.PAT5.normDESEQ.adj1.sig0.1.DE>
#depend on: XML, DEXSeq,DESEQ,DESeq2, EdgeR mysql-connector-java-5.1.13-bin.jar

#ex
#rscript "E:/sys/project/browser/app/R_DEPAC.r" path="f:/" donorm=DESEQ method=DEXSEQ minpat=5 adj=0 sig=0.1 intbl=t_arab_pac cols="wt_leaf_1:wt_leaf_2:wt_leaf_3;wt_seed_1:wt_seed_2" groups=grp1:grp2 conf=dbconf_dbserver.xml
#rscript "E:/sys/project/browser/app/R_DEPAC.r" path="f:/" donorm=DESEQ method=DEXSEQ minpat=5 adj=1 sig=0.1 intbl=t_arab_pac cols="wt_leaf_1:wt_leaf_2:wt_leaf_3;wt_seed_1:wt_seed_2" groups=grp1:grp2 conf=dbconf_dbserver.xml

##########################################################################################

###############################################
# invoke R
###############################################
source('/var/www/front/src/r/R_funclib.r')

args=commandArgs()	
opts=getOptions(args,c('intbl','cols','conf','path','groups','adj','sig','minpat','donorm','method','ofile'));
stopifnot(!is.null(opts[['intbl']]),!is.null(opts[['cols']]),!is.null(opts[['conf']]))
intbl=opts[['intbl']]
cols=opts[['cols']]
conf=opts[['conf']]
path=setPath(opts[['path']])
method='DEXSEQ'
ofile=opts[['ofile']]

if (!is.null(opts[['method']])) {
  method=toupper(opts[['method']]);
}
#if (!(method %in% c('DEXSEQ'))) {
#  stop('error method: DEXSEQ\n')
#}

minpat=1; 
donorm=NULL;
adj=0;
sig=0.05

if (!is.null(opts[['minpat']])) {
  minpat=as.numeric(opts[['minpat']]);
  if (minpat==0) {
	 minpat=1;
  }
}
if (!is.null(opts[['donorm']])) {
  donorm=toupper(opts[['donorm']]);
	if (!(donorm %in% c('DESEQ','EDGER','TPM'))) {
	  stop('error normalization method: DESEQ  or EDGER or TPM\n')
	}
}

if (!is.null(opts[['adj']])) {
  adj=as.numeric(opts[['adj']]);
  if (adj>0) {
	adj=1
  }
}

if (!is.null(opts[['sig']])) {
  sig=as.numeric(opts[['sig']]);
  if (sig>0.1) {
	sig=0.1;
  }
}

#智能判断输入的样本列的组别，去掉末尾的1个数字，再取其它的unique
#groups=dryseed leaf root
if (is.null(opts[['groups']])) {
  groups=cols2group(cols,auto=T)
} else {
  groups=cols2group(opts[['groups']],auto=F)
}

if (length(groups)<=1) {
  stop("error groups");
}

#还需要一个colid，保存groups对应的colnames，如 dry1 dry2 dry3; ... 相当于将cols的分号变成行号
colid=cols2list(cols,groups);

conn <- connectDB(conf) 

#cat(paste("read table",intbl,"..."),'\n')
conds<- unlist(strsplit(cols,'[;:]')) #单样本列（含rep）

adjtxt='NONE';
if (adj) {
  adjtxt='Bonferroni';
}
donormtxt='NONE';
if (!is.null(donorm)) {
  donormtxt=donorm
}


###############################################
#取得APA gene的PAC
###############################################
#建APA gene表
tmptbl='t_tmpgene'
dbSendUpdate(conn,addStr('drop table if exists ',tmptbl));
tottxt=paste(conds,collapse='+')
dbSendUpdate(conn,paste('create table',tmptbl,'select gene from',intbl,'where', tottxt,'>0 and ftr not like \'inter%\' group by gene having count(*)>=2'))
dbSendUpdate(conn,paste('create index idx_gene on',tmptbl,'(gene)'));

#读取APA PAC
pacfile=addStr(path,intbl,'.APAgene.pac')
coltxt=paste(conds,collapse=',')
sql=addStr('select gene,chr,strand,coord,ftr,',coltxt,' from ',intbl,' where gene in (select gene from ',tmptbl,') order by gene,coord')
sql2file(conn,sql,pacfile)

dbSendUpdate(conn,addStr('drop table if exists ',tmptbl));
x=dbDisconnect(conn)

###############################################
#  DESEQ 分析 DE PAC 两两 
###############################################
filecols=c('gene','chr','strand','coord','ftr',conds)
ocols=c('gene','chr','strand','coord','ftr');

#给pacfile添加标题行，方便查看
pac=read.table(pacfile,header=F,sep="\t",quote="")
colnames(pac)=filecols
if (file.exists(pacfile)) x=file.remove(pacfile);

lbl1=groups[1];
lbl2=groups[2];
group1=colid[[1]]
group2=colid[[2]]

sizefactor=NULL

geneIDCol='gene'
exonIDCol='coord'
skip=1

#输出
if (is.null(ofile)) {
	osurfix=paste(lbl1,'X',lbl2,'.',method,'.PAT',minpat,'.norm',donorm,'.adj',adj,'.sig',sig,'.DE',sep='')
	ofile=paste(pacfile,osurfix,sep='.')
}
ostatfile=paste(ofile,'stat',sep='.')
if (file.exists(ostatfile)) x=file.remove(ostatfile);
addTxt2File(ostatfile,paste('Samples: ',cols,'\n','Normalization method: ',donormtxt,'\n','DE method: ',method,'\n','Min PAT: ',minpat,'\n','Adj. method: ',adjtxt,'\n','Significance level: ',sig,sep=''))

suppressPackageStartupMessages( library( "DEXSeq" ) )

pac.dat=pac[,c(geneIDCol,exonIDCol,group1,group2)]

grpInd1=match(group1,colnames(pac.dat))
grpInd2=match(group2,colnames(pac.dat))

addTxt2File(ostatfile,paste('PACs: ',nrow(pac.dat),sep=''));

#过滤
if (minpat>1) {
  pac.dat=pac.dat[rowSums(pac.dat[,c(group1,group2)])>=minpat,] 
  cat('PACs filtered: ',nrow(pac.dat),'\n')
  addTxt2File(ostatfile,paste('PACs filtered: ',nrow(pac.dat),sep=''));
}

cntdat=pac.dat[,c(grpInd1,grpInd2)] #只出需要的列
grpNames=colnames(cntdat)
group=c(grpInd1,grpInd2) #比如 (s1,s1,s2,s2)
group[1:length(grpInd1)]=lbl1
group[(length(grpInd1)+1):ncol(cntdat)]=lbl2

#标准化
if (!is.null(donorm)) {
  sf=getSizeFactor(cntdat,oLibsize=F);
  cntdat=normBySizeFactor(cntdat,sf)
  colnames(cntdat)=paste(grpNames,'_norm',sep='')
  pac.dat=cbind(pac.dat,cntdat) #pac.dat 也包含标准化后的数值
}
rownames(cntdat)=1:nrow(cntdat)

sampleData <- data.frame(condition=group )
design <- formula( ~ sample + exon + condition:exon )
groupID <- pac.dat[,geneIDCol]
featureID=as.character(pac.dat[,exonIDCol]);
dxd= DEXSeqDataSet( cntdat, sampleData, design,featureID, groupID )
sizeFactors(dxd)=rep(1,ncol(cntdat))
dxd = estimateDispersions( dxd )
dxd = testForDEU( dxd )
dxr1 = DEXSeqResults( dxd )
dxRet=as.data.frame(dxr1[,1:7])
dxRet=dxRet[order(dxRet[,'padj']),c('groupID','featureID','pvalue','padj')]
colnames(dxRet)=c(geneIDCol,exonIDCol,'pval','padj')
ret=merge(pac.dat,dxRet,by.x=c(geneIDCol,exonIDCol),by.y=c(geneIDCol,exonIDCol))
#加上ocols
ret=merge(pac[,unique(c(geneIDCol,exonIDCol,ocols))],ret,by.x=c(geneIDCol,exonIDCol),by.y=c(geneIDCol,exonIDCol))
ret=ret[order(ret[,c('padj')]),]

if (adj) {
	ret=ret[,-which(colnames(ret)=='pval')]
	pvcol='padj'
	ret=ret[!is.na(ret[,pvcol]),]
	ret=ret[ret[,pvcol]<=sig,]
	ret=ret[order(ret[,pvcol]),]
} else {
	pvcol='pval'
	ret=ret[!is.na(ret[,pvcol]),]
	ret=ret[,-which(colnames(ret)=='padj')]
	ret=ret[ret[,pvcol]<=sig,]
	ret=ret[order(ret[,pvcol]),]
}

write.table(ret,file=ofile,col.names=T,row.names=F,quote=F,sep='\t')
addTxt2File(ostatfile,paste('DE PACs: ',nrow(ret),sep=''));


