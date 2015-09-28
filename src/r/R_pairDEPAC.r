##########################################################################################
#2015/4/15
#DEXSeq ���� DE PAC (�����ٶȱȽ�������DE gene���ܶ�)
#�Ը���intbl����groups �ֱ���������DE ���㣨�����б�׼��������intbl���Ǳ�׼����ı���
#parameters & usage
#rscript "E:/sys/code/R/R_pairDEPAC.r" path="f:/" intbl=t_ae_pacsd24n_flt cols=A1:A2;B1:B2:b3;c1:cc2:c3 minrep=0 minpat=0 donorm=0  groups=NULL conf=dbconf_ricepac.xml
# intbl: normalized table with (gene, ftr)+<cols>
# cols: cols (rep1:rep2;r1:r2...) ͬһ�����Ĳ�ͬrep��,��:��������ͬ�����÷ֺŸ���
# groups=NULL���ṩ,���Զ�����cols�ж�group���� groups=A:B:C
# conf: xml conf
#depend on: XML, DESeq, DESeq2, mysql-connector-java-5.1.13-bin.jar

#ex.
#rscript "E:/sys/code/R/R_pairDEPAC.r" path="f:/" intbl=t_ae_pacsd24n_flt cols="dry_seed1:dry_seed2:dry_seed3;embryo1:embryo2;endosperm1:endosperm2:endosperm3;imbibed_seed1:imbibed_seed2:imbibed_seed3;shoot1:shoot2:shoot3;leaf_20days1:leaf_20days2:leaf_20days3;leaf_60days1:leaf_60days2:leaf_60days3;stem_60days1:stem_60days2:stem_60days3;root_5days1:root_5days2:root_5days3;root_60days1:root_60days2:root_60days3;husk1:husk2:husk3;anther1:anther2:anther3;mature_pollen1:mature_pollen2:mature_pollen3;pistil1:pistil2:pistil3" groups=NULL conf=dbconf_ricepac.xml

#�����
#t_ae_pacsd24n_flt.APAgene.pac -- ��APAgene�µ�PAC�ļ�
#t_ae_pacsd24n_flt.APAgene.pac.LeafXseed24H.minpat0_minrep0_norm0 -- ����������DE���

#ע�⣺��rice��42tissues����91��pairwise��Ҫ��Լ5Сʱ��ÿ����3�������ҡ�������DEX��estimateDisper�ر�����

#2015/6/3
#����minpat, minrep��donorm��Ĭ��ȫΪ0
#����JBrowser�е���

##########################################################################################

###############################################
# invoke R
###############################################
source('/var/www/front/src/r/R_funclib.r')

args=commandArgs()		
opts=getOptions(args,c('intbl','cols','conf','path','groups','minpat','minrep','donorm'));
stopifnot(!is.null(opts[['intbl']]),!is.null(opts[['cols']]),!is.null(opts[['conf']]))
intbl=opts[['intbl']]
cols=opts[['cols']]
conf=opts[['conf']]
path=setPath(opts[['path']])

minpat=0; 
minrep=0;
donorm=0;

if (!is.null(opts[['minpat']])) {
  minpat=as.numeric(opts[['minpat']]);
}
if (!is.null(opts[['minrep']])) {
  minrep=as.numeric(opts[['minrep']]);
}
if (!is.null(opts[['donorm']])) {
  donorm=as.numeric(opts[['donorm']]);
}



#path='f:/';
#intbl='t_ae_pacsd24n_flt'
#conf='dbconf_ricepac.xml'
#cols="dry_seed1:dry_seed2:dry_seed3;embryo1:embryo2;endosperm1:endosperm2:endosperm3;imbibed_seed1:imbibed_seed2:imbibed_seed3;shoot1:shoot2:shoot3;leaf_20days1:leaf_20days2:leaf_20days3;leaf_60days1:leaf_60days2:leaf_60days3;stem_60days1:stem_60days2:stem_60days3;root_5days1:root_5days2:root_5days3;root_60days1:root_60days2:root_60days3;husk1:husk2:husk3;anther1:anther2:anther3;mature_pollen1:mature_pollen2:mature_pollen3;pistil1:pistil2:pistil3"

#�����ж�����������е����ȥ��ĩβ��1�����֣���ȡ������unique
#groups=dryseed leaf root
if (is.null(opts[['groups']])) {
  groups=cols2group(cols,auto=T)
} else {
  groups=cols2group(opts[['groups']],auto=F)
}

if (length(groups)<=1) {
  stop("error groups");
}

#����Ҫһ��colid������groups��Ӧ��colnames���� dry1 dry2 dry3; ... �൱�ڽ�cols�ķֺű���к�
colid=cols2list(cols,groups);

conn <- connectDB(conf) 

cat(paste("read table",intbl,"..."),'\n')
conds<- unlist(strsplit(cols,'[;:]')) #�������У���rep��

cat('path=',path,'\n','intbl=',intbl,'\n','conf=',conf,'\n','cols=',cols,'\n','groups=',toString(groups),'\n')
cat('minpat=',minpat,'\n','minrep=',minrep,'\n','donorm=',donorm,'\n')


###############################################
#ȡ��APA gene��PAC
###############################################
#��APA gene��
tmptbl='t_tmpgene'
dbSendUpdate(conn,addStr('drop table if exists ',tmptbl));
tottxt=paste(conds,collapse='+')
dbSendUpdate(conn,paste('create table',tmptbl,'select gene from',intbl,'where', tottxt,'>0 and ftr not like \'inter%\' group by gene having count(*)>=2'))
dbSendUpdate(conn,paste('create index idx_gene on',tmptbl,'(gene)'));

#��ȡAPA PAC
pacfile=addStr(path,intbl,'.APAgene.pac')
if (file.exists(pacfile)) x=file.remove(pacfile);
coltxt=paste(conds,collapse=',')
sql=addStr('select gene,chr,strand,coord,ftr,',coltxt,' from ',intbl,' where gene in (select gene from ',tmptbl,') order by gene,coord')
sql2file(conn,sql,pacfile)

dbSendUpdate(conn,addStr('drop table if exists ',tmptbl));
x=dbDisconnect(conn)


###############################################
#  DESEQ ���� DE PAC ���� 
###############################################
filecols=c('gene','chr','strand','coord','ftr',conds)
ocols=c('gene','chr','strand','coord','ftr');

#��pacfile���ӱ����У�����鿴
a=read.table(pacfile,header=F,sep="\t",quote="")
colnames(a)=filecols
write.table(a,file=pacfile,sep="\t",col.names=T,row.names=F,quote=F)
a=c()

ng=choose(length(groups),2)
cat("********* DE ",ng," pairs ********* ",'\n')
p=0
for (i in 1:(length(groups)-1)) {
  for (j in (i+1):length(groups) ) {
	p=p+1

	lbl1=groups[i];
	lbl2=groups[j];
    group1=colid[[i]]
    group2=colid[[j]]

	#����ԭʼ����
	#minpat=1
	#minrep=1
	#doNorm=0
	sizefactor=NULL

	geneIDCol='gene'
	exonIDCol='coord'
	skip=1

	#���
	osurfix=paste(lbl1,'X',lbl2,'.minpat',minpat,'_minrep',minrep,'_norm',donorm,sep='')

	#����DE
	cat(addStr(ng,'.',p),lbl1,lbl2,'\n')
    dxRet=DoDEX(skip=skip,path=path,group1=group1,group2=group2,lbl1=lbl1,lbl2=lbl2,geneIDCol=geneIDCol,exonIDCol=exonIDCol,doNorm=donorm,sizefactor=sizefactor,file=pacfile,filecols=filecols,minpat=minpat,minrep=minrep,osurfix=osurfix,ocols=ocols);
  }
}

