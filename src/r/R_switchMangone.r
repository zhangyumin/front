##########################################################################################
#2015/5/5
#���ṩ��cols����������������switch����Mangone�ķ�ʽ
#����������ṩ��cols�����޹أ���Ϊÿ��ֻȡ���������������ݣ������������޹�
#parameters & usage
#rscript "E:/sys/code/R/R_switchMangone.r" path="f:/" intbl=t_ae_pacsd24n_flt switch=T:50:10:5:10:2 cond="" cols=A1:A2;B1:B2:b3;c1:cc2:c3 suffix=leafs groups=NULL conf=dbconf_ricepac.xml
# intbl: normalized table with (gene, ftr)+<cols>
# switch=T:50:10:5:10:2 -top2 T -dist 50 -N1 10 -N2 5 -N3 10 -NF 2
# ע�⣺����������õ����ṩ��sample��rep��ƽ�����������ܺ�(��Ϊ��ͬ�������ظ��������ܲ�ͬ)
	#-N1=at least one PA>=N1; 
	#-N2=At least PA1 or PA2 in all stage >=N2;
	#-N3=In the two stages, |PA1-PA2|>=N3; 
	#-NF=At least one stage PA1/PA2>=NF, and one other stage switch; #C4
# cond=sqlcond for intbl (for switchPERL)
# cols: cols (rep1:rep2;r1:r2...) ͬһ�����Ĳ�ͬrep��,��:��������ͬ�����÷ֺŸ���
# groups=NULL���ṩ,���Զ�����cols�ж�group���� groups=A:B:C
# conf: xml conf
#depend on: PERLSWITCH

#ex.
#rscript "E:/sys/code/R/R_switchMangone.r" path="f:/" intbl=t_ae_pacsd24n_flt switch=F:50:10:5:10:2 cond="" cols="dry_seed1:dry_seed2:dry_seed3;embryo1:embryo2;endosperm1:endosperm2:endosperm3;imbibed_seed1:imbibed_seed2:imbibed_seed3;shoot1:shoot2:shoot3;leaf_20days1:leaf_20days2:leaf_20days3;leaf_60days1:leaf_60days2:leaf_60days3;stem_60days1:stem_60days2:stem_60days3;root_5days1:root_5days2:root_5days3;root_60days1:root_60days2:root_60days3;husk1:husk2:husk3;anther1:anther2:anther3;mature_pollen1:mature_pollen2:mature_pollen3;pistil1:pistil2:pistil3" suffix=F_50_10_5_10_2 groups=NULL  conf=dbconf_ricepac.xml
#�����
#t_ae_pacsd24n_flt.F_50_10_5_10_2.dry_seedXanther.switch                             
#t_ae_pacsd24n_flt.F_50_10_5_10_2.switch.stat <sample,switch_gene,switch_pair,3UTR_pair,non3UTR_pair,3UTR_PAC,5UTR_PAC,intron_PAC,CDS_PAC>
###########################################################################################

###############################################
# invoke R
###############################################
source('/var/www/front/src/r/R_funclib.r')

args=commandArgs()		
opts=getOptions(args,c('intbl','cols','suffix','conf','path','groups','ogene','switch'));
if (is.null(opts[['suffix']])) {
  suffix=''
} else {
  suffix=opts[['suffix']]
}
stopifnot(!is.null(opts[['intbl']]),!is.null(opts[['cols']]),!is.null(opts[['conf']]),!is.null(opts[['switch']]))
intbl=opts[['intbl']]
cols=opts[['cols']]
conf=opts[['conf']]
path=setPath(opts[['path']])
switch=opts[['switch']]
sqlcond=opts[['cond']]
if (is.null(sqlcond)) {
  sqlcond=''
}

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


#path='f:/';
#intbl='t_ae_pacsd24n_flt'
#conf='dbconf_ricepac.xml'
#cols="root_5days1:root_5days2:root_5days3;root_60days1:root_60days2:root_60days3"
#suffix='root'
#avgPAT=10; ogene=T

cat('path=',path,'\n','intbl=',intbl,'\n','conf=',conf,'\n','cols=',cols,'\n','groups=',toString(groups),'\n','suffix=',suffix,'\n')
cat('cond=',sqlcond,'\n','switch(top2:dist:N1:N2:N3:NF)=',switch,'\n')

switch=unlist(strsplit(switch,'[;:,]'))
if (length(switch)!=6) {
  stop('error switch(top2:dist:N1:N2:N3:NF)',switch,'\n')
}
top2=switch[1]; dist=switch[2]; N1=switch[3]; N2=switch[4]; N3=switch[5]; NF=switch[6];

if (suffix!='') {
  filepre=addStr(path,intbl,'.',suffix,'.')
} else {
  filepre=addStr(path,intbl,'.')
}
statfile=addStr(filepre,'switch.stat');

###############################################
# Mangone switch
###############################################
#�Ը�������������intbl��������������
#�Ƚ�repƽ��������ftr=3UTR �� avg(smp1)+avg(smp2)>=avgPAT
#�ҵ���3UTR�е�APA gene�����㵽ftr_start�ľ���

colid=cols2list(cols,groups);
conds=unlist(strsplit(cols,'[;:]')) 
if (file.exists(statfile)) x=file.remove(statfile)
ng=length(colid)
omtx=matrix(nrow=0,ncol=8)
alls=choose(ng,2)
gi=0
lbls=c()
for (i in 1:(ng-1)) {
  for (j in (i+1):ng) { 
	gi=gi+1
	
	group1=colid[[i]]; 	group2=colid[[j]]
	lbl1=names(colid)[i]; lbl2=names(colid)[j];
	lbls=c(lbls,addStr(lbl1,'-',lbl2))
	smp1txt=addStr('(',paste(group1,collapse='+'),')/',length(group1))
	smp2txt=addStr('(',paste(group2,collapse='+'),')/',length(group2))
    
    cat(addStr(alls,'.',gi),lbl1,lbl2,'\n')

	ofile=addStr(filepre,lbl1,'X',lbl2,'.','switch');
	#CMP_switchGene.pl -intbl t_ae_pacsd24n_flt -top2 F -dist 50 -N1 5 -N2 10 -N3 10 -NF "2" -lbls dryseed:embryo -smpcols "(dry_seed1+dry_seed2+dry_seed3)/3:(embryo1+embryo2)/2"  -ofile f:/t_ae_pacsd24n_flt_dryembry -conf dbconf_ricepac.xml
    cmd=sprintf("%s -intbl %s -sqlcond \"%s\" -top2 %s -dist %s -N1 %s -N2 %s -N3 %s -NF \"%s\" -lbls %s -smpcols \"%s:%s\"  -ofile \"%s\" -conf %s",paste(PERLEXE,PERLSWITCH),intbl,sqlcond,top2,dist,N1,N2,N3,NF,addStr(lbl1,':',lbl2),smp1txt,smp2txt,ofile,conf)
    errcode=system(cmd,intern=T,wait=TRUE);

	#������.switch�ļ�����һ��ͳ�ƣ� ��switch gene,switch pair,3UTR_pair,non3UTR_pair,3UTR,5UTR,intron,CDS��
        if (file.size(ofile)>0) {
	a=read.table(ofile,header=T,sep="\t",quote="")
       } else {
        a=matrix(ncol=0,nrow=0)
       }
	ngene=0;npair=0;utr3=0;utr5=0;intron=0;cds=0;utr3pair=0;nonpair=0;
	if (nrow(a)>0) {
		ngene=length(unique(a$gene))
		npair=as.integer(nrow(a)/2)
		utr3pair=as.integer(sum(a$gene_class=='3UTR-3UTR')/2)
	        nonpair=npair-utr3pair
		b=aggregate(a$coord, list(ftr=a$ftr),length)
		colnames(b)=c('ftr','nPAC')
		utr3=ifelse(sum(b$ftr=='3UTR')==1,b$nPAC[b$ftr=='3UTR'],0)
		utr5=ifelse(sum(b$ftr=='5UTR')==1,b$nPAC[b$ftr=='5UTR'],0)
		intron=ifelse(sum(b$ftr=='intron')==1,b$nPAC[b$ftr=='intron'],0)
		cds=ifelse(sum(b$ftr=='CDS')==1,b$nPAC[b$ftr=='CDS'],0)
	}
	omtx=rbind(omtx,c(ngene,npair,utr3pair,nonpair,utr3,utr5,intron,cds))
  }
}
omtx=cbind(lbls,omtx);
colnames(omtx)=c('sample','switch_gene','switch_pair','3UTR_pair','non3UTR_pair','3UTR_PAC','5UTR_PAC','intron_PAC','CDS_PAC')
write.table(omtx,file=statfile,sep="\t",col.names=T,row.names=F,quote=F);
