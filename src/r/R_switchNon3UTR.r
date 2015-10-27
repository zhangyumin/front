##########################################################################################
#2015/5/5
#对提供的cols，计算两两样本的switch，用Mangone的方式
#两两结果与提供的cols多少无关，因为每次只取那两个样本的数据，与其它样本无关
#parameters & usage
#rscript "E:/sys/code/R/R_switchNon3UTR.r" path="f:/" intbl=t_ae_pacsd24n_flt switch=T:50:10:5:10:2 cond="" cols=A1:A2;B1:B2:b3;c1:cc2:c3 ofile=xx.txt groups=NULL conf=dbconf_ricepac.xml
# intbl: normalized table with (gene, ftr)+<cols>
# switch=T:50:10:5:10:2 -top2 T -dist 50 -N1 10 -N2 5 -N3 10 -NF 2
# 注意：这里的条件用的是提供的sample的rep的平均，而不是总和(因为不同样本的重复个数可能不同)
	#-N1=at least one PA>=N1; 
	#-N2=At least PA1 or PA2 in all stage >=N2;
	#-N3=In the two stages, |PA1-PA2|>=N3; 
	#-NF=At least one stage PA1/PA2>=NF, and one other stage switch; #C4
# cond=sqlcond for intbl (for switchPERL)
# cols: cols (rep1:rep2;r1:r2...) 同一样本的不同rep用,或:隔开，不同样本用分号隔开
# groups=NULL或不提供,则自动根据cols判断group，或 groups=A:B:C
# conf: xml conf
#depend on: PERLSWITCH

#ex.
#rscript "E:/sys/project/browser/app/R_switchNon3UTR.r" path="f:/" ofile="not3UTR.switch" switch=T:50:10:5:10:2 cond="" intbl=t_arab_pac cols="wt_leaf_1:wt_leaf_2:wt_leaf_3;wt_seed_1:wt_seed_2" groups=grp1:grp2 conf=dbconf_dbserver.xml
###########################################################################################

###############################################
# invoke R
###############################################
source('/var/www/front/src/r/R_funclib.r');

args=commandArgs()		
opts=getOptions(args,c('intbl','cols','ofile','conf','path','groups','ogene','switch'));
ofile=opts[['ofile']]

stopifnot(!is.null(opts[['intbl']]),!is.null(opts[['cols']]),!is.null(opts[['conf']]),!is.null(opts[['switch']]),!is.null(opts[['ofile']]))
intbl=opts[['intbl']]
cols=opts[['cols']]
conf=opts[['conf']]
path=setPath(opts[['path']])
switch=opts[['switch']]
sqlcond=opts[['cond']]
if (is.null(sqlcond)) {
  sqlcond=''
}

#智能判断输入的样本列的组别，去掉末尾的1个数字，再取其它的ogene
#groups=dryseed leaf root
if (is.null(opts[['groups']])) {
  groups=cols2group(cols,auto=T)
} else {
  groups=cols2group(opts[['groups']],auto=F)
}

if (length(groups)<=1) {
  stop("error groups");
}

cat('path=',path,'\n','intbl=',intbl,'\n','conf=',conf,'\n','cols=',cols,'\n','groups=',toString(groups),'\n')
cat('cond=',sqlcond,'\n','switch(top2:dist:N1:N2:N3:NF)=',switch,'\n')
switchtxt=switch

switch=unlist(strsplit(switch,'[;:,]'))
if (length(switch)!=6) {
  stop('error switch(top2:dist:N1:N2:N3:NF)',switch,'\n')
}
top2=switch[1]; dist=switch[2]; N1=switch[3]; N2=switch[4]; N3=switch[5]; NF=switch[6];

statfile=addStr(ofile,'.stat');
if (file.exists(statfile)) x=file.remove(statfile);

###############################################
# Mangone switch
###############################################
#对给定的样本，从intbl中两个样本挑出
#先将rep平均，满足ftr=3UTR 且 avg(smp1)+avg(smp2)>=avgPAT
#找到在3UTR中的APA gene，计算到ftr_start的距离

colid=cols2list(cols,groups);
conds=unlist(strsplit(cols,'[;:]')) 
if (file.exists(statfile)) x=file.remove(statfile)

lbls=c()
group1=colid[[1]]; 	group2=colid[[2]]
lbl1=names(colid)[1]; lbl2=names(colid)[2];
lbls=c(lbls,addStr(lbl1,'-',lbl2))
smp1txt=addStr('(',paste(group1,collapse='+'),')/',length(group1))
smp2txt=addStr('(',paste(group2,collapse='+'),')/',length(group2))

addTxt2File(statfile,paste('Samples: ',cols,'\n','Swiching conditions: ',switchtxt,sep=''))

ofile=addStr(path,ofile);
#CMP_switchGene.pl -intbl t_ae_pacsd24n_flt -top2 F -dist 50 -N1 5 -N2 10 -N3 10 -NF "2" -lbls dryseed:embryo -smpcols "(dry_seed1+dry_seed2+dry_seed3)/3:(embryo1+embryo2)/2"  -ofile f:/t_ae_pacsd24n_flt_dryembry -conf dbconf_ricepac.xml
cmd=sprintf("%s -intbl %s -sqlcond \"%s\" -top2 %s -dist %s -N1 %s -N2 %s -N3 %s -NF \"%s\" -lbls %s -smpcols \"%s:%s\"  -ofile \"%s\" -conf %s",paste(PERLEXE,PERLSWITCH),intbl,sqlcond,top2,dist,N1,N2,N3,NF,addStr(lbl1,':',lbl2),smp1txt,smp2txt,ofile,conf)
errcode=system(cmd,intern=T,wait=TRUE);

a=read.table(ofile,header=T,sep="\t",quote="")
if (nrow(a)==0) exit;
a=a[!(a$gene_class %in% c('3UTR-3UTR','3UTR-AMB','AMB-3UTR')),] #只取不全是3UTR的配对
ng=length(unique(a$gene))
addTxt2File(statfile,paste('Switching genes: ',ng,sep=''))

a=a[,c('gene','gene_type','chr','strand','coord','ftr',group1,group2,lbl1,lbl2,'gene_class')]
a[,lbl1]=round(a[,lbl1])
a[,lbl2]=round(a[,lbl2])
colnames(a)=c('gene','gene_type','chr','strand','coord','ftr',group1,group2,addStr(lbl1,'_average'),addStr(lbl2,'_average'),'switching type')
write.table(a,file=ofile,col.names=T,row.names=F,quote=F,sep="\t");

#统计
ngene=0;npair=0;utr3=0;utr5=0;intron=0;cds=0;utr3pair=0;nonpair=0;
ngene=length(unique(a$gene))
npair=as.integer(nrow(a)/2)
addTxt2File(statfile,paste('Switching pairs: ',npair,sep=''))
b=aggregate(a$coord, list(ftr=a$ftr),length)
colnames(b)=c('ftr','nPAC')
utr3=ifelse(sum(b$ftr=='3UTR')==1,b$nPAC[b$ftr=='3UTR'],0)
utr5=ifelse(sum(b$ftr=='5UTR')==1,b$nPAC[b$ftr=='5UTR'],0)
intron=ifelse(sum(b$ftr=='intron')==1,b$nPAC[b$ftr=='intron'],0)
cds=ifelse(sum(b$ftr=='CDS')==1,b$nPAC[b$ftr=='CDS'],0)
addTxt2File(statfile,paste('3UTR PACs: ',utr3,sep=''))
addTxt2File(statfile,paste('5UTR PACs: ',utr5,sep=''))
addTxt2File(statfile,paste('CDS PACs: ',cds,sep=''))
addTxt2File(statfile,paste('intron PACs: ',intron,sep=''))

