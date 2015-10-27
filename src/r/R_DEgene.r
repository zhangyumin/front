##########################################################################################
#2015/9/30 
#browser程序: DESEQ / EdgeR 分析 DE gene 
#对给定intbl表的groups 分别两两进行DE 计算（不进行标准化，所以intbl表是标准化后的表）
#parameters & usage
#rscript "E:/sys/code/R/R_DEgene.r" path="f:/" intbl=t_ae_pacsd24n_flt cols=A1:A2;B1:B2:b3;c1:cc2:c3 method=DESeq minpat=0 donorm=NULL adj=1 sig=0.05 groups=NULL ofile=NULL conf=dbconf_ricepac.xml
# intbl: normalized table with (gene, ftr)+<cols>
# cols: cols (rep1:rep2;r1:r2...) 同一样本的不同rep用,或:隔开，不同样本用分号隔开
# groups=NULL或不提供,则自动根据cols判断group，或 groups=A:B:C
# conf: xml conf
# minpat=1(default)
# adj=1/0(default)
# sig=0.05(default)~0.1
# method=deseq/edgeR/deseq2
# donorm=null/edgeR/deseq/TPM
# ofile=output file name; if NULL, then like <t_arab_pac.gene.grp1Xgrp2.DESEQ2.PAT5.normDESEQ.adj1.sig0.1.DE>
#depend on: XML, DESeq, DESeq2, EdgeR mysql-connector-java-5.1.13-bin.jar

#ex.
#rscript "E:/sys/project/browser/app/R_DEgene.r" path="f:/" donorm=NULL method=DESEQ minpat=5 adj=1 sig=0.05 intbl=t_arab_pac cols="wt_leaf_1:wt_leaf_2:wt_leaf_3;wt_seed_1:wt_seed_2" groups=grp1:grp2 conf=dbconf_dbserver.xml
#rscript "E:/sys/project/browser/app/R_DEgene.r" path="f:/" donorm=DESEQ method=EDGER minpat=5 adj=0 sig=0.1 intbl=t_arab_pac cols="wt_leaf_1:wt_leaf_2:wt_leaf_3;wt_seed_1:wt_seed_2" groups=grp1:grp2 conf=dbconf_dbserver.xml
#rscript "E:/sys/project/browser/app/R_DEgene.r" path="f:/" ofile="DE.txt" donorm=DESEQ method=EDGER minpat=5 adj=0 sig=0.1 intbl=t_arab_pac cols="wt_leaf_1:wt_leaf_2:wt_leaf_3;wt_seed_1:wt_seed_2" groups=grp1:grp2 conf=dbconf_dbserver.xml

##########################################################################################

###############################################
# invoke R
###############################################
source('/var/www/front/src/r/R_funclib.r');

args=commandArgs()	
opts=getOptions(args,c('intbl','cols','conf','path','groups','adj','sig','minpat','donorm','method','ofile'));
stopifnot(!is.null(opts[['intbl']]),!is.null(opts[['cols']]),!is.null(opts[['conf']]))
intbl=opts[['intbl']]
cols=opts[['cols']]
conf=opts[['conf']]
path=setPath(opts[['path']])
method='DESEQ'
ofile=opts[['ofile']]

if (!is.null(opts[['method']])) {
  method=toupper(opts[['method']]);
}
if (!(method %in% c('DESEQ','DESEQ2','EDGER'))) {
  stop('error method: DESEQ or DESEQ2 or EDGER\n')
}

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

#读取 gene nPAT，不要igt的
genefile=addStr(path,intbl,'.gene')
x=geneSql2file(conn,intbl,conds,genefile,extra=c('gene','gene_type','chr','strand','tot_tagnum'))
x=dbDisconnect(conn)

###############################################
#  DESEQ 分析 DE gene （两两样本，或一轮流对其它）
###############################################
filecols=c('gene','gene_type','chr','strand','genePAT',conds)
ocols=c('gene','gene_type')

## 对gene文件作初步统计
## 计算仅在一个样本表达的基因个数，不在该样本表达的基因个数
dat=read.table(genefile,sep="\t",quote="");
colnames(dat)=filecols;
x=file.remove(genefile);

lbl1=groups[1];
lbl2=groups[2];
group1=colid[[1]]
group2=colid[[2]]

#过滤原始数据
sizefactor=NULL
grpInd1=1:length(group1)
grpInd2=(length(group1)+1):(length(group1)+length(group2))

#原始数据
pac=dat
dat=c()
colnames(pac)=filecols
pac=cbind(id=seq(1,nrow(pac)),pac)
pac.dat=as.matrix(pac[,c(group1,group2)])
rownames(pac.dat)=pac$id

#输出
if (is.null(ofile)) {
	osurfix=paste(lbl1,'X',lbl2,'.',method,'.PAT',minpat,'.norm',donorm,'.adj',adj,'.sig',sig,'.DE',sep='')
	ofile=paste(genefile,osurfix,sep='.')
}
ostatfile=paste(ofile,'stat',sep='.')
if (file.exists(ostatfile)) x=file.remove(ostatfile);

#cat('ofile:',ofile,'\n')
#cat('raw row number:',nrow(pac.dat),'\n')
addTxt2File(ostatfile,paste('Samples: ',cols,'\n','Normalization method: ',donormtxt,'\n','DE method: ',method,'\n','Min PAT: ',minpat,'\n','Adj. method: ',adjtxt,'\n','Significance level: ',sig,sep=''))

#过滤
addTxt2File(ostatfile,paste('Genes: ',nrow(pac.dat),sep=''));
pac.filter=pac.dat
if (minpat>1) {
  pac.filter=pac.dat[rowSums(pac.dat)>=minpat,] 
  cat('Genes filtered: ',nrow(pac.filter),'\n')
  addTxt2File(ostatfile,paste('Genes filtered: ',nrow(pac.filter),sep=''));
}

#标准化
pac.filter.norm=pac.filter
ret=cbind(id=as.integer(rownames(pac.filter)),pac.filter)
if (!is.null(donorm)) {
  sf=getSizeFactor(pac.filter,donorm,oLibsize=F);
  pac.filter.norm=normBySizeFactor(pac.filter,sf)
  #cat ('size factor:',sf,'\n')
  colnames(pac.filter.norm)=paste(colnames(pac.filter.norm),'_norm',sep='')
  ret=cbind(id=as.integer(rownames(pac.filter)),pac.filter,pac.filter.norm)
}

#-------------------------------------------------
# DE
#-------------------------------------------------
if (method=='DESEQ') {
  ret.deseq=doDE(pac.filter.norm,'DESEQ',doNorm=F,grpInd1=grpInd1,grpInd2=grpInd2,grpLbl1=lbl1,grpLbl2=lbl2)
  ret.deseq=ret.deseq[,c('pval','padj','logFC')]
  colnames(ret.deseq)=c('DESeq_pval','DESeq_padj','DESeq_logFC')
  ret=cbind(ret,ret.deseq)
  pvcol='DESeq_pval'
  adjcol='DESeq_padj'
} else if (method=='EDGER') {
  ret.edger=doDE(pac.filter.norm,'EDGER',doNorm=F,grpInd1=grpInd1,grpInd2=grpInd2,grpLbl1=lbl1,grpLbl2=lbl2)
  ret.edger=ret.edger[,c('pval','padj','logFC')]
  colnames(ret.edger)=c('edger_com_pval','edger_com_padj','edger_com_logFC')
  ret=cbind(ret,ret.edger)
  pvcol='edger_com_pval'
  adjcol='edger_com_padj'
} else if (method=='DESEQ2') {
  ret.deseq2=doDE(pac.filter.norm,'DESEQ2',doNorm=F,grpInd1=grpInd1,grpInd2=grpInd2,grpLbl1=lbl1,grpLbl2=lbl2)
  ret.deseq2=ret.deseq2[,c('pval','padj','logFC')]
  colnames(ret.deseq2)=c('DESeq2_pval','DESeq2_padj','DESeq2_logFC')
  ret=cbind(ret,ret.deseq2)
  pvcol='DESeq2_pval'
  adjcol='DESeq2_padj'
}

ocols=c('id',ocols)
oret=merge(pac[,ocols],ret,by.x='id',by.y='id')
oret=oret[,-which(colnames(oret)=='id')]
oret=oret[,-grep('logFC',colnames(oret))]
if (adj) {
	oret=oret[,-which(colnames(oret)==pvcol)]
	pvcol=adjcol
	oret=oret[!is.na(oret[,pvcol]),]
	oret=oret[oret[,pvcol]<=sig,]
	oret=oret[order(oret[,pvcol]),]
	colnames(oret)[which(colnames(oret)==pvcol)]='padj'
} else {
	oret=oret[!is.na(oret[,pvcol]),]
	oret=oret[,-which(colnames(oret)==adjcol)]
	oret=oret[oret[,pvcol]<=sig,]
	oret=oret[order(oret[,pvcol]),]
	colnames(oret)[which(colnames(oret)==pvcol)]='pval'
}

write.table(oret,file=ofile,col.names=T,row.names=F,quote=F,sep='\t')
addTxt2File(ostatfile,paste('DE genes: ',nrow(oret),sep=''));
