##########################################################################################
#2015/4/17
#���ṩ��cols����������������switch����FU�ķ�ʽ (FDR=0.1)
#����������ṩ��cols�����޹أ���Ϊÿ��ֻȡ���������������ݣ������������޹�
#parameters & usage
#rscript "E:/sys/code/R/R_switchFU.r" path="f:/" intbl=t_ae_pacsd24n_flt ogene=T cols=A1:A2;B1:B2:b3;c1:cc2:c3 avgPAT=10 suffix=leafs groups=NULL conf=dbconf_ricepac.xml
# intbl: normalized table with (gene, ftr)+<cols>
# ogene=T/F(default); if T, ���ÿ�������µ�.long/short�Ļ����б�
# avgPAT=1(default);  ����������ƽ��PAT���ܺ�>=avgPAT ������������ƽ���ĺͣ����ǵ�����������UTRlen��һ������
# cols: cols (rep1:rep2;r1:r2...) ͬһ�����Ĳ�ͬrep��,��:��������ͬ�����÷ֺŸ���
# groups=NULL���ṩ,���Զ�����cols�ж�group���� groups=A:B:C
# conf: xml conf
#depend on: XML, conn

#ex.
#rscript "E:/sys/code/R/R_switchFU.r" ogene=T avgPAT=5 suffix=root path="f:/global/" intbl=t_ae_pacsd24n_flt cols="root_5days1:root_5days2:root_5days3;root_60days1:root_60days2:root_60days3" groups=NULL conf=dbconf_ricepac.xml
#�����
#<intbl>.<suffix>.avgPAT5.FU.pdf                              
#<intbl>.<suffix>.avgPAT5.FU.stat -- <lbls, used gene, switch gene, switch percent, A-long, B-long>
#<intbl>.<suffix>.avgPAT5.root_5daysXroot_60days.FU -- <gene, cor, padj, logRatio, change>        
#<intbl>.<suffix>.avgPAT5.root_5daysXroot_60days.FU.long.gene -- �����б�
#<intbl>.<suffix>.avgPAT5.root_5daysXroot_60days.FU.short.gene
###########################################################################################

###############################################
# invoke R
###############################################
source('/var/www/front/src/r/R_funclib.r')

args=commandArgs()		
opts=getOptions(args,c('intbl','cols','suffix','conf','path','groups','ogene','avgPAT'));
if (is.null(opts[['suffix']])) {
  suffix=''
} else {
  suffix=opts[['suffix']]
}
stopifnot(!is.null(opts[['intbl']]),!is.null(opts[['cols']]),!is.null(opts[['conf']]))
intbl=opts[['intbl']]
cols=opts[['cols']]
conf=opts[['conf']]
path=setPath(opts[['path']])


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
ogene=F
if (!is.null(opts[['ogene']])) {
  if (opts[['ogene']] == 'T') {
    ogene=T
  }
}

#path='f:/';
#intbl='t_ae_pacsd24n_flt'
#conf='dbconf_ricepac.xml'
#cols="root_5days1:root_5days2:root_5days3;root_60days1:root_60days2:root_60days3"
#suffix='root'
#avgPAT=10; ogene=T

cat('path=',path,'\n','intbl=',intbl,'\n','conf=',conf,'\n','cols=',cols,'\n','groups=',toString(groups),'\n','suffix=',suffix,'\n')
cat('avgPAT=',avgPAT,'\n','ogene=',ogene,'\n')

if (suffix!='') {
  ofile=addStr(intbl,'.',suffix,'.avgPAT',avgPAT)
} else {
  ofile=addStr(intbl,'.avgPAT',avgPAT)
}
pdffile=addStr(ofile,'.FU.pdf')
statfile=addStr(ofile,'.FU.stat');

###############################################
# FU switch
###############################################
#�Ը�������������intbl��������������
#�Ƚ�repƽ��������ftr=3UTR �� avg(smp1)+avg(smp2)>=avgPAT
#�ҵ���3UTR�е�APA gene�����㵽ftr_start�ľ���

colid=cols2list(cols,groups);
conds=unlist(strsplit(cols,'[;:]')) 
conn=connectDB(conf)
#pdf(file=pdffile)
if (file.exists(statfile)) x=file.remove(statfile)
ng=length(colid)
tmpfile=addStr(path,ofile,'.tostop.tmp');
omtx=matrix(nrow=0,ncol=5)
colnames(omtx)=c('used gene','switch gene','switch percent','A-long','B-long')
alls=choose(ng,2)
gi=0
lbls=c()
for (i in 1:(ng-1)) {
  for (j in (i+1):ng) { 
	gi=gi+1
	
	#��SQL������
	group1=colid[[i]]; 	group2=colid[[j]]
	lbl1=names(colid)[i]; lbl2=names(colid)[j];
	lbls=c(lbls,addStr(lbl1,'-',lbl2))
	smp1txt=addStr('(',paste(group1,collapse='+'),')/',length(group1))
	smp2txt=addStr('(',paste(group2,collapse='+'),')/',length(group2))
	condtxt=addStr(smp1txt,'+',smp2txt,'>=',avgPAT)
	sql=sprintf("select gene,strand,coord,ftr_start,ftr_end,%s,%s from %s where ftr=\'3UTR\' and %s",smp1txt,smp2txt,intbl,condtxt)
	x=sql2file(conn,sql,tmpfile)

	#ȡAPA gene������UTRlen
	#������� gene,avgSmp1,avgSmp2,UTRlen
	pac=read.table(tmpfile,header=F,quote="",sep="\t")
	colnames(pac)=c('gene','strand','coord','ftr_start','ftr_end',lbl1,lbl2)
	APAgene=pac$gene[duplicated(pac$gene)]
	pac=pac[pac$gene %in% APAgene,]
	id=grep('\\+',pac$strand)
	pac[id,'coord']=pac[id,'coord']-pac[id,'ftr_start']+1
	id=grep('\\-',pac$strand)
	pac[id,'coord']=pac[id,'ftr_end']-pac[id,'coord']+1
	pac=pac[,c('gene',lbl1,lbl2,'coord')]

    cat(addStr(alls,'.',gi),lbl1,lbl2,'APAgene',nrow(pac),'\n')

    #FUgene(dat,smp1,smp2,lblScore,geneFilter=1,toPlot=T,pdf,FDR=0.1)
	fu=FUgene(pac,lbl1,lbl2,'coord',geneFilter=1,toPlot=F,pdf='')
	opairfile=addStr(ofile,'.',lbl1,'X',lbl2,'.FU')
	write.table(fu,file=opairfile,col.names=T,row.names=F,quote=F,sep="\t");

	#������.fu�ļ�����һ��ͳ�ƣ� ��used gene#,switch gene#,switch gene%,A-Long,B-Long��
	  ngene=nrow(fu)
	  tolong=nrow(fu[grep('longer',fu$change),])
	  toshort=nrow(fu[grep('Shorter',fu$change),])
	  omtx=rbind(omtx,c(ngene,tolong+toshort,(tolong+toshort)/ngene,toshort,tolong))
	  if (ogene) {
		  gLong=fu$gene[grep('longer',fu$change)]
		  gShort=fu$gene[grep('Shorter',fu$change)]
		  write.table(gLong,file=paste(opairfile,'.long.gene',sep=''),sep="\t",col.names=F,row.names=F,quote=F);
		  write.table(gShort,file=paste(opairfile,'.short.gene',sep=''),sep="\t",col.names=F,row.names=F,quote=F);
	  }
  }
}
#x=dev.off()
x=dbDisconnect(conn)
if (file.exists(tmpfile))  x=file.remove(tmpfile);
omtx=cbind(lbls,omtx);
write.table(omtx,file=statfile,sep="\t",col.names=T,row.names=F,quote=F);