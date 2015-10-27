##########################################################################################
#2015/4/17
#���ṩ��cols����������������switch����FU�ķ�ʽ (FDR=0.1)
#����������ṩ��cols�����޹أ���Ϊÿ��ֻȡ���������������ݣ������������޹�
#parameters & usage
#rscript "E:/sys/code/R/R_switchFU.r" path="f:/"  ofile=NULL intbl=t_ae_pacsd24n_flt adj=1 sig=0.05 cols=A1:A2;B1:B2:b3;c1:cc2:c3 avgPAT=10 groups=NULL conf=dbconf_ricepac.xml
# intbl: normalized table with (gene, ftr)+<cols>
# avgPAT=1(default);  ����������ƽ��PAT���ܺ�>=avgPAT ������������ƽ���ĺͣ����ǵ�����������UTRlen��һ������
# adj=1/0(default)
# sig=0.05(default)~0.1
# cols: cols (rep1:rep2;r1:r2...) ͬһ�����Ĳ�ͬrep��,��:��������ͬ�����÷ֺŸ���
# groups=NULL���ṩ,���Զ�����cols�ж�group���� groups=A:B:C
# conf: xml conf
# ofile=output file name; if NULL, then like <...>
#depend on: XML, conn

#ex.
#rscript "E:/sys/project/browser/app/R_switch3UTR.r" path="f:/" avgPAT=5 adj=0 sig=0.1 intbl=t_arab_pac cols="wt_leaf_1:wt_leaf_2:wt_leaf_3;wt_seed_1:wt_seed_2" groups=grp1:grp2 conf=dbconf_dbserver.xml
#���
#grp1Xgrp2..PAT5.adj0.sig0.1.UTRswitch.stat
#grp1Xgrp2..PAT5.adj0.sig0.1.UTRswitch

###########################################################################################

###############################################
# invoke R
###############################################
source('/var/www/front/src/r/R_funclib.r');

args=commandArgs()		
opts=getOptions(args,c('intbl','cols','sig','adj','conf','path','groups','avgPAT','ofile'));

stopifnot(!is.null(opts[['intbl']]),!is.null(opts[['cols']]),!is.null(opts[['conf']]))
intbl=opts[['intbl']]
cols=opts[['cols']]
conf=opts[['conf']]
path=setPath(opts[['path']])
ofile=opts[['ofile']]


#�����ж�����������е����ȥ��ĩβ��1�����֣���ȡ������ogene
#groups=dryseed leaf root
if (is.null(opts[['groups']])) {
  groups=cols2group(cols,auto=T)
} else {
  groups=cols2group(opts[['groups']],auto=F)
}

if (length(groups)<=1) {
  stop("error groups");
}

avgPAT=1
if (!is.null(opts[['avgPAT']])) {
  avgPAT=as.numeric(opts[['avgPAT']])
}

adj=0;
sig=0.05
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

adjtxt='NONE';
if (adj) {
  adjtxt='Bonferroni';
}

###############################################
# FU switch
###############################################
#�Ը�������������intbl��������������
#�Ƚ�repƽ��������ftr=3UTR �� avg(smp1)+avg(smp2)>=avgPAT
#�ҵ���3UTR�е�APA gene�����㵽ftr_start�ľ���

colid=cols2list(cols,groups);
conds=unlist(strsplit(cols,'[;:]')) 
conn=connectDB(conf)

omtx=matrix(nrow=0,ncol=5)
colnames(omtx)=c('used gene','switch gene','switch percent','A-long','B-long')

lbls=c()

#��SQL������
group1=colid[[1]]; 	group2=colid[[2]]
lbl1=names(colid)[1]; lbl2=names(colid)[2];

if (is.null(ofile)) {
  osurfix=paste(lbl1,'X',lbl2,'.','.PAT',avgPAT,'.adj',adj,'.sig',sig,'.UTRswitch',sep='')
} else {
  osurfix=ofile
}
ostatfile=addStr(osurfix,'.stat');
tmpfile=addStr(path,osurfix,'.tostop.tmp');
if (file.exists(ostatfile)) x=file.remove(ostatfile);
if (file.exists(tmpfile)) x=file.remove(tmpfile);
addTxt2File(ostatfile,paste('Samples: ',cols,'\n','Average PAT: ',avgPAT,'\n','Adj. method: ',adjtxt,'\n','Significance level: ',sig,sep=''))

lbls=c(lbls,addStr(lbl1,'-',lbl2))
smp1txt=addStr('(',paste(group1,collapse='+'),')/',length(group1))
smp2txt=addStr('(',paste(group2,collapse='+'),')/',length(group2))
condtxt=addStr(smp1txt,'+',smp2txt,'>=',avgPAT)
sql=sprintf("select gene,strand,coord,ftr_start,ftr_end,%s,%s from %s where ftr=\'3UTR\' and %s",smp1txt,smp2txt,intbl,condtxt)
x=sql2file(conn,sql,tmpfile)
x=dbDisconnect(conn)

#ȡAPA gene������UTRlen
#������� gene,avgSmp1,avgSmp2,UTRlen
pac=read.table(tmpfile,header=F,quote="",sep="\t")
colnames(pac)=c('gene','strand','coord','ftr_start','ftr_end',lbl1,lbl2)
if (file.exists(tmpfile))  x=file.remove(tmpfile);
APAgene=pac$gene[duplicated(pac$gene)]
pac=pac[pac$gene %in% APAgene,]
id=grep('\\+',pac$strand)
pac[id,'coord']=pac[id,'coord']-pac[id,'ftr_start']+1
id=grep('\\-',pac$strand)
pac[id,'coord']=pac[id,'ftr_end']-pac[id,'coord']+1
pac=pac[,c('gene',lbl1,lbl2,'coord')]
pac[,lbl1]=ceiling(pac[,lbl1])
pac[,lbl2]=ceiling(pac[,lbl2])

addTxt2File(ostatfile,paste('APA genes: ',nrow(pac),sep=''))

#FUgene(dat,smp1,smp2,'coord',geneFilter=1,toPlot=T,pdf,FDR=0.1)
#fu=FUgene(pac,lbl1,lbl2,'coord',geneFilter=1,toPlot=F,pdf='')
genes=unique(pac$gene)
gs=c()
p=c()
cr=c()
ratio=c()
pattxt=c()
utrlentxt=c()
for (g in genes) {
  ig=which(pac$gene==g)
  if (length(ig)<2) {
    next
  }
  s1=pac[ig,lbl1]
  s2=pac[ig,lbl2]
  score=pac[ig,'coord']
  if (sum(s1)==0 | sum(s2)==0) next
  p=c(p,prop.trend.test(s1, s1+s2,score=score)$p.value)
  cr=c(cr,FU(s1,s2,score))
  #ratio=c(ratio,log2((sum(s1)+0.1)/(sum(s2)+0.1)))
  gs=c(gs,g)
  pattxt=c(pattxt,paste(paste('PA',1:length(ig),'=',sep=''),apply(t(pac[ig,c(lbl1,lbl2)]),2,paste,collapse=','),collapse=';',sep=''))
  utrlentxt=c(utrlentxt,paste('PA',1:length(ig),'=',pac[ig,'coord'],sep='',collapse=';'))
}

#      gene     grp1  grp2 coord
# AT1G50380   1   5   157
# AT1G50380   10 36   303

if (adj) {
  pv=p.adjust(p)
  ptxt='padj'
} else {
  pv=p
  ptxt='pval'
}

pthd=sig
x=which(pv<pthd & cr>0)
y=which(pv<pthd & cr<=0)
XYLong=paste(lbl2,' longer',sep='');
XYShort=paste(lbl1,' longer',sep='');
notChange='X';
change=rep(notChange,length(pv))
change[x]=XYLong; change[y]=XYShort;
rt=as.data.frame(cbind(gene=gs,avgPAT=pattxt,UTRlen=utrlentxt,cor=cr,padj=pv,change=change))
rt$cor=as.numeric(rt$cor);rt$padj=as.numeric(rt$padj);
ngene=nrow(rt)
rt=rt[rt$change!='X',]
rt=rt[order(rt$change,rt$padj),]
colnames(rt)=c('gene','average PAT','3UTR length','correlation',ptxt,'switching')
write.table(rt,file=osurfix,col.names=T,row.names=F,quote=F,sep="\t");

#��һ��ͳ�ƣ� ��used gene#,switch gene#,switch gene%,A-Long,B-Long��
toshort=length(x)
tolong=length(y)
addTxt2File(ostatfile,paste('Used genes: ',ngene,sep=''))
addTxt2File(ostatfile,paste('Switching genes: ',tolong+toshort,sep=''))
addTxt2File(ostatfile,paste(lbl1,' longer: ',tolong,sep=''))
addTxt2File(ostatfile,paste(lbl2,' longer: ',toshort,sep=''))



