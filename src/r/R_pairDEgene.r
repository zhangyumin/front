##########################################################################################
#2015/4/10
#DESEQ / DESeq2 ���� DE gene 
#�Ը���intbl���groups �ֱ���������DE ���㣨�����б�׼��������intbl���Ǳ�׼����ı�
#parameters & usage
#rscript "E:/sys/code/R/R_pairDEgene.r" path="f:/" intbl=t_ae_pacsd24n_flt cols=A1:A2;B1:B2:b3;c1:cc2:c3 minrep=0 minpat=0 donorm=0 groups=NULL conf=dbconf_ricepac.xml
# intbl: normalized table with (gene, ftr)+<cols>
# cols: cols (rep1:rep2;r1:r2...) ͬһ�����Ĳ�ͬrep��,��:��������ͬ�����÷ֺŸ���
# groups=NULL���ṩ,���Զ�����cols�ж�group���� groups=A:B:C
# conf: xml conf
# minpat,minrep,donorm=DE option
#depend on: XML, DESeq, DESeq2, mysql-connector-java-5.1.13-bin.jar

#ex.
#rscript "E:/sys/code/R/R_pairDEgene.r" path="f:/" intbl=t_ae_pacsd24n_flt cols="dry_seed1:dry_seed2:dry_seed3;embryo1:embryo2;endosperm1:endosperm2:endosperm3;imbibed_seed1:imbibed_seed2:imbibed_seed3;shoot1:shoot2:shoot3;leaf_20days1:leaf_20days2:leaf_20days3;leaf_60days1:leaf_60days2:leaf_60days3;stem_60days1:stem_60days2:stem_60days3;root_5days1:root_5days2:root_5days3;root_60days1:root_60days2:root_60days3;husk1:husk2:husk3;anther1:anther2:anther3;mature_pollen1:mature_pollen2:mature_pollen3;pistil1:pistil2:pistil3" groups=NULL conf=dbconf_ricepac.xml
#�����
#t_ae_pacsd24n_flt.gene -- gene�Լ�PAT�����ļ�
#t_ae_pacsd24n_flt.gene.stat -- ͳ��ÿ���������ܻ������Լ�unique/notExpressed�Ļ�����
#t_ae_pacsd24n_flt.gene.LeafXseed24H.minpat0_minrep0_norm0 -- ����������DE���(DESeq+DESeq2)

#2015/6/3
#����minpat, minrep��donorm��Ĭ��ȫΪ0
#����JBrowser�е���
##########################################################################################

###############################################
# invoke R
###############################################
source('/var/www/front/src/r/R_funclib.r');

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

#path='f:/';
#intbl='t_ae_pacsd24n_flt'
#conf='dbconf_ricepac.xml'
#cols="dry_seed1:dry_seed2:dry_seed3;embryo1:embryo2;endosperm1:endosperm2:endosperm3;imbibed_seed1:imbibed_seed2:imbibed_seed3;shoot1:shoot2:shoot3;leaf_20days1:leaf_20days2:leaf_20days3;leaf_60days1:leaf_60days2:leaf_60days3;stem_60days1:stem_60days2:stem_60days3;root_5days1:root_5days2:root_5days3;root_60days1:root_60days2:root_60days3;husk1:husk2:husk3;anther1:anther2:anther3;mature_pollen1:mature_pollen2:mature_pollen3;pistil1:pistil2:pistil3"

conn <- connectDB(conf) 

cat(paste("read table",intbl,"..."),'\n')
conds<- unlist(strsplit(cols,'[;:]')) #�������У���rep��

cat('path=',path,'\n','intbl=',intbl,'\n','conf=',conf,'\n','cols=',cols,'\n','groups=',toString(groups),'\n')
cat('minpat=',minpat,'\n','minrep=',minrep,'\n','donorm=',donorm,'\n')

#��ȡ gene nPAT����Ҫigt��
genefile=addStr(path,intbl,'.gene')
x=geneSql2file(conn,intbl,conds,genefile,extra=c('gene','gene_type','chr','strand','tot_tagnum'))
x=dbDisconnect(conn)


###############################################
#  DESEQ ���� DE gene ��������������һ������������
###############################################
filecols=c('gene','gene_type','chr','strand','genePAT',conds)
ocols=c('gene','gene_type','chr','strand','genePAT')

## ��gene�ļ�������ͳ��
## �������һ���������Ļ�����������ڸ��������Ļ������
ostatFile=addStr(genefile,'.stat')
dat=read.table(genefile,sep="\t",quote="");
colnames(dat)=filecols;
ostat=matrix(nrow=0,ncol=4) #group ngene nUniqueGene  nNotGene
for (i in 1:(length(groups))) {
  smpcols=colid[[i]]
  thisPAT=rowSums(dat[,smpcols])
  nNotGene=sum(thisPAT<=0);
  ngene=length(unique(dat$gene[thisPAT>0]))
  nUniqueGene=sum(thisPAT==dat$genePAT);
  ostat=rbind(ostat,c(groups[i],ngene,nUniqueGene,nNotGene))
}
colnames(ostat)=c('sample','ngene','nUniqueGene','nNotExpressedGene');
write.table(ostat,file=ostatFile,col.names=T,row.names=F,quote=F,sep="\t")


## ����DE ���� 
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
	#minpat=0
	#minrep=0
	doNorm=0
	sizefactor=NULL
	#���
	osurfix=paste(lbl1,'X',lbl2,'.minpat',minpat,'_minrep',minrep,'_norm',donorm,sep='')

	#����DE
	cat(addStr(ng,'.',p),lbl1,lbl2,'\n')
	ret=DoDEs(path,group1,group2,lbl1,lbl2,donorm,sizefactor,genefile,filecols,minpat,minrep,osurfix,ocols,skip=1);
  }
}

#��DESeq��DE2���غ϶� [��ѡ����һ�ַ����ȽϺ���]
#DE�ļ���<genefile>.LeafXseed24H_minpat0_minrep0_norm0

#��DESeq��DE2���غ϶� (�����DESeq2�Ľ��)
#path='F:/'
#setwd(path)
#a=read.table('t_ae_pacsd24n_flt.gene.dry_seedXembryo_minpat0_minrep0_norm0',header=T,sep="\t")
#top1=a$gene[!is.na(a$DESeq_padj) & a$DESeq_padj<0.001]
#top2=a$gene[!is.na(a$DESeq2_padj) & a$DESeq2_padj<0.001]
#length(top1); length(top2); length(intersect(top1,top2))
#head(a[a$DESeq2_padj<0.001 & a$DESeq_padj>0.001,])
#head(a[a$DESeq2_padj>0.001 & a$DESeq_padj<0.001,]) 
#head(a[a$DESeq2_padj<0.001 & a$DESeq_padj<0.001,])
#
#path='F:/'
##retfile='t_ae_pacsd24n_flt.gene.dry_seedXembryo_minpat0_minrep0_norm0'
#retfile='t_ae_pacsd24n_flt.gene.embryoXendosperm_minpat0_minrep0_norm0'
#thds=c(0.01,0.001)  
#tops=seq(100,1000,100)
#statDEs(path,retfile,thds,tops)
