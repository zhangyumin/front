##########################################################################################
#2015/8/1
#��Rʵ�� PA2PAC �� PAs2PAC ����perl��ͬ���ǣ��Ƕ�chr����group���ٱȶԵ�gff�У�
#�ȶ�ȡPA���PAT�ļ�����merge��һ������ٱ���chr����group
#parameters & usage
#rscript "E:/sys/code/R/PAT_PA2PAC.r" path="f:/" bigmem=T mtbls="t_pa_rice_part1;t_pa_rice_part2;t_pa_rice_part3;t_pa_rice_part4;t_pa_rice_part5" smps="dry_seed1:dry_seed2:dry_seed3:embryo1:embryo2:endosperm1:endosperm2:endosperm3:imbibed_seed1:imbibed_seed2:imbibed_seed3;shoot1:shoot2:shoot3:leaf_20days1:leaf_20days2:leaf_20days3:leaf_60days1:leaf_60days2:leaf_60days3;stem_60days1:stem_60days2:stem_60days3:root_5days1:root_5days2:root_5days3:root_60days1:root_60days2:root_60days3;husk1:husk2:husk3:anther1:anther2:anther3:mature_pollen1:mature_pollen2:mature_pollen3;pistil1:pistil2:pistil3" osmps=NULL d=24 anti=T gfftbl="rice_gff7.t_gff7L_ae" conf="dbconf_ricepa.xml" otbl="rice_pac.test_pac" filedesc=NULL

# path: folder for filedesc or masterTable_Output
# bigmem:T/F; T for large PA file (��chr����merge����������С�Ļ�������merge�����)
# *mtbls/smps/osmps: master tables sep by ;/:/, and smps like A1:A2:B1:B2;C1:C2 [smps��;������]
# *filedesc: PA file description (filename samplename); PA file has only 4 columns but no header(chr,strand,coord,tag)
# d: distance for PAC
# anti=T(deafult)/F
# gfftbl=gff table
# noGFF=F(default)/T; if T then Not relate GFF (���bigmem=T noGFF������T����F������Ҫ�ṩgfftbl�Եõ�chrs)
# conf=XML
# otbl=output PAC table#
# depend: GenomicRanges

# ע�⣺
#�������󣬹��̻ᱣ�����ɸ�.rda�ļ�������������⣬���м��ļ���ʼ��R������
#ͬһ��Rscript������ܵ�1�����г�����2�������ֿ����ˡ���ԭ������

#ex1# ��master���� (ֻ��Ҫָ���������У���һ��Ҫȫ����)
#rscript "E:/sys/code/R/PAT_PA2PAC.r" path="f:/" bigmem=T mtbls="t_pa_rice_part1;t_pa_rice_part2" smps="dry_seed1:dry_seed2;pistil1:pistil2:pistil3" osmps=NULL d=24 anti=T gfftbl="rice_gff7.t_gff7L_ae" conf="dbconf_ricepa.xml" otbl="rice_pac.test_pac" filedesc=NULL

#ex2# ���ļ�����PA���γ�PAC
#rscript "E:/sys/code/R/PAT_PA2PAC.r" path="F:/sys_data/LiLab/FHH_rice_indica/indica2japonicaPAT/noXS" bigmem=T filedesc="PAfile.desc" d=24 anti=T gfftbl="rice_gff7.t_gff7L_ae" conf="dbconf_ricepa.xml" otbl="rice_pac.t_i2j_pac"

#ex3# ��master���� ������GFF�������bigmem=T�Ļ�������Ҫ�ṩgfftbl��
#rscript "E:/sys/code/R/PAT_PA2PAC.r" path="f:/" bigmem=F mtbls="t_pa_rice_part1;t_pa_rice_part2" smps="dry_seed1:dry_seed2;pistil1:pistil2:pistil3" osmps=NULL d=24 noGFF=T  conf="dbconf_ricepa.xml" otbl="rice_pac.test_pac" filedesc=NULL


#��� -- �����ı�otb���Լ��������ʱ�ļ�(bigmem=Tʱ����.rda��ʱ�ļ�)
#  masterX -- filedesc����£��ϲ������ļ����ɵ�master��6������һ��master��                                                                                                 
#  allpa.rda --                                                                                               
#  groups.rda --                                                                                             
#  sensepac.rda -- ����pac������sense��anti��tot=1��Ҳ���������������mysql�ı�(�������������1million)��ֻ����tot_tagnum>1���У�  

#2015/9/3
#����noGFFѡ���������GFF��ֻ���о�����������Ҫ�Ƿ�ֹ��ЩGFF©��һЩ�����
##########################################################################################

###############################################
# invoke R
###############################################
source('/var/www/front/src/r/R_funclib.r');

suppressPackageStartupMessages(library("GenomicRanges"))

args=commandArgs()		
opts=getOptions(args,c('path','mtbls','smps','osmps','d','anti','gfftbl','conf','filedesc','otbl','bigmem','noGFF'));
stopifnot(!is.null(opts[['path']]),!is.null(opts[['d']]),!is.null(opts[['conf']]),!is.null(opts[['otbl']]))
anti=T
if (!is.null(opts[['anti']])) {
  if (opts[['anti']] == 'F') {
	 anti=F
  }
} 

noGFF=F
if (!is.null(opts[['noGFF']])) {
  if (opts[['noGFF']] == 'T') {
	 noGFF=T
  }
} 

bigmem=T
if (!is.null(opts[['bigmem']])) {
  if (opts[['bigmem']] == 'F') {
	 bigmem=F
  }
} 

if (is.null(opts[['mtbls']]) & is.null(opts[['filedesc']])) {
  stop("At least mtbls (from table) or filedesc (from file)!")
}

if (!is.null(opts[['mtbls']]) & !is.null(opts[['filedesc']])) {
  stop("Only mtbls (from table) or filedesc (from file)!")
}

if (!is.null(opts[['mtbls']]) & is.null(opts[['smps']])) {
  stop("mtbls but no smps!")
}


path=setPath(opts[['path']])
mtbls=opts[['mtbls']]
smps=opts[['smps']]
osmps=opts[['osmps']]
gfftbl=opts[['gfftbl']]
conf=opts[['conf']]
filedesc=opts[['filedesc']]
otbl=opts[['otbl']]
d=as.numeric(opts[['d']])

if (d<1) {
  stop("d<1")
}

if (bigmem & is.null(gfftbl)) {
  stop("bigmem=T but NO gfftbl!")
}

if (!noGFF & is.null(gfftbl)) {
  stop("noGFF=F but NO gfftbl!")
}


cat('path=',path,'\n','mtbls=',mtbls,'\n','smps=',smps,'\n','osmps=',osmps,'\n','filedesc=',filedesc,'\n')
cat('gfftbl=',gfftbl,'\n','conf=',conf,'\n','otbl=',otbl,'\n','d=',d,'\n','anti=',anti,'\n','bigmem=',bigmem,'\n','*noGFF=',noGFF,'\n')


cat('START:',date(),'\n')

#setwd('f:/')
#mtbls=paste('t_pa_rice_part',c(1:5),sep='')
#smps="dry_seed1:dry_seed2:dry_seed3:embryo1:embryo2:endosperm1:endosperm2:endosperm3:imbibed_seed1:imbibed_seed2:imbibed_seed3;shoot1:shoot2:shoot3:leaf_20days1:leaf_20days2:leaf_20days3:leaf_60days1:leaf_60days2:leaf_60days3;stem_60days1:stem_60days2:stem_60days3:root_5days1:root_5days2:root_5days3:root_60days1:root_60days2:root_60days3;husk1:husk2:husk3:anther1:anther2:anther3:mature_pollen1:mature_pollen2:mature_pollen3;pistil1:pistil2:pistil3"
##smps="dry_seed1:dry_seed2:dry_seed3:embryo1:embryo2:endosperm1:endosperm2:endosperm3:imbibed_seed1:imbibed_seed2:imbibed_seed3;pistil1:pistil2:pistil3"
#osmps=smps #PA�Ա����ʽ

#path="F:/sys_data/LiLab/FHH_rice_indica/indica2japonicaPAT/noXS"
#filedesc='PAfile.desc' #PA���ļ�����ʽ��PAfile smp

#gfftbl='rice_gff7.t_gff7L_ae'
#conf='dbconf_ricepa.xml'
#d=24
#anti=T
#otbl='rice_pac.t_i2j_pac'
#bigmem=T

if (is.null(filedesc)) { #PA�����Ա�
	mtbls=unlist(strsplit(mtbls,'[;,:]'))
	##��smps��osmps��ȫ����list[[mtbl]]=c()����ʽ
	insmpls=list()
	osmpls=list()
	xx<- unlist(strsplit(smps,'[;]'))
	for (i in 1:length(xx)) {
	  insmpls[[mtbls[i]]]=unlist(strsplit(xx[i],'[:,]'))
	}
	if (!is.null(osmps)) {
		xx<- unlist(strsplit(osmps,'[;]'))
		for (i in 1:length(xx)) {
		  osmpls[[mtbls[i]]]=unlist(strsplit(xx[i],'[:,]'))
		}
	} else {
		osmpls=insmpls
	}
}

#����longchr
alist=connectDB(conf,c('longchr'));
dbh=alist[[1]]
longchr=alist[['longchr']]

chrs=c()

if (!is.na(longchr)) {
    chrs=unlist(strsplit(longchr,split=","))
	#txt=addStr('\'',paste(chrs,collapse="\',\'"),'\'')
	#sql=sprintf("SELECT distinct(chr) FROM %s where chr not in (%s) order by chr",gfftbl,txt);
    #chrs=c(chrs,getFldValues(dbh,sql,0))
	chrs=c(chrs,'ISSHORT')
} else if (bigmem) {
	sql=sprintf("SELECT distinct(chr) FROM %s order by chr",gfftbl);
    chrs=getFldValues(dbh,sql,0);
}

chrs=unique(chrs)

##��ȡmaster��df (�������ڴ�)
palist=list()
if (!is.null(filedesc)) { #PA�������ļ���ÿ6���ļ�һ��master��
  x=read.table(filedesc,header=F)
  pafiles=x$V1
  pasmps=x$V2
  insmpls=list()
  osmpls=list()
  mtbls=c()
  ng=floor(nrow(x)/6)+ ifelse(nrow(x) %% 6==0,0,1)  
  cat('read PA files (6 files each time)',ng,'groups\n')
  for (i in 1:ng) { #��6��PA�ļ�����һ��master����
	cat('master',i,'\n')
	mtbls=c(mtbls,paste('master',i,sep=''))
	idx= ((i-1)*6+1):(ifelse(i*6>length(pafiles),length(pafiles),i*6))
	insmpls[[mtbls[i]]]=pasmps[idx]
	osmpls[[mtbls[i]]]=pasmps[idx]
	
	j=idx[1]
	palist[[mtbls[i]]]=read.table(pafiles[j],header=F)
	colnames(palist[[mtbls[i]]])=c('chr','strand','coord',pasmps[j])
    for (j in idx[-1]) {
	  pa2=read.table(pafiles[j],header=F)
	  colnames(pa2)=c('chr','strand','coord',pasmps[j])
      palist[[mtbls[i]]]=merge(palist[[mtbls[i]]],pa2,all=T,by.x=c('chr','strand','coord'),by.y=c('chr','strand','coord'))
    }
	palist[[mtbls[i]]][is.na(palist[[mtbls[i]]])]=0 
	write.table(palist[[mtbls[i]]],file=mtbls[i],sep="\t",quote=F,col.names=T,row.names=F)
	x=gc(verbose=F)
 }
} else {#��master��
	for (m in mtbls) {
	  cat('read PA table',m,'\n') #ȡ smp>0 ��ȫ����
	  sql=sprintf("SELECT chr,strand,coord,%s FROM %s where %s",paste(insmpls[[m]],collapse=','),m,addStr(paste(insmpls[[m]],collapse='+'),'>0'));
	  palist[[m]]=sql2df(dbh,sql,header=T) 
	  colnames(palist[[m]])[-(1:3)]=osmpls[[m]]; #���������
	  x=gc(verbose =F)
	}
}

##���ϲ����������ļ�������master�������� mtbls��insmpls����ʽ�洢
##��ÿ��master������merge  (�������ڴ�)
if (bigmem) { #��chr
  allpa=matrix(nrow=0,ncol=0)
  for (chr in chrs) {
   for (strand in c('+','-')) {
	cat('bigmem merging',chr,strand,'\n')
	if (chr=='ISSHORT') {
      idx=!(palist[[mtbls[1]]]$chr %in% chrs) & palist[[mtbls[1]]]$strand==strand
	  m=palist[[mtbls[1]]][idx,]
	} else {
	  idx=(palist[[mtbls[1]]]$chr==chr & palist[[mtbls[1]]]$strand==strand)
	  m=palist[[mtbls[1]]][idx,c('coord',osmpls[[mtbls[1]]])] #ȥ��chr,strand��
	}
	for (mtbl in mtbls[-1]) {
	  if (chr=='ISSHORT') {
        idx=!(palist[[mtbl]]$chr %in% chrs) & palist[[mtbl]]$strand==strand
	    pa2=palist[[mtbl]][idx,]
        m=merge(m,pa2,all=T)
	  } else {
	    idx=(palist[[mtbl]]$chr==chr) & palist[[mtbl]]$strand==strand
	    pa2=palist[[mtbl]][idx,c('coord',osmpls[[mtbl]])]
		m=merge(m,pa2,all=T)
	  }
      x=gc(verbose=F)
	} #mtbl
	if (chr!='ISSHORT') {
	  m=cbind(chr=rep(chr,nrow(m)),strand=rep(strand,nrow(m)),m)
	}
	if (nrow(allpa)==0) {
	  allpa=m[,c('chr','strand','coord',unlist(osmpls))]
	} else {
	  allpa=rbind(m[,c('chr','strand','coord',unlist(osmpls))],allpa)
	}
	rm(m)
    x=gc(verbose=F)
	write.table(allpa,file='allpa.tmp',sep="\t",quote=F,col.names=T,row.names=F)
  }#for S
 } #forC
  allpa[is.na(allpa)]=0
} else { #ֱ��merge
	allpa=palist[[mtbls[1]]]
	for (mtbl in mtbls[-1]) {
	  cat('merging',mtbl,'\n')
	  pa2=palist[[mtbl]]
      allpa=merge(allpa,pa2,all=T,by.x=c('chr','strand','coord'),by.y=c('chr','strand','coord'))
      x=gc(verbose =F)
	}
  allpa[is.na(allpa)]=0
}

#���ˣ�allpa��ŵ������е�PA������ɾ��palist
rm(list=c('m','palist','pa2'))
x=gc(verbose =F)
if (bigmem) {
  save(allpa,file='allpa.rda')
}

##��allpa����group (�ܿ�)
cat('grouping PA by dist\n')
allpa=allpa[order(allpa$chr,allpa$strand,allpa$coord),]
x=gc(verbose =F)
diffcrd=diff(allpa$coord) #ǰ��ľ���
same=(allpa$chr[1:(nrow(allpa)-1)]==allpa$chr[2:nrow(allpa)]) & (allpa$strand[1:(nrow(allpa)-1)]==allpa$strand[2:nrow(allpa)])
#allpa[!same,c('chr','strand')]
indist=(diffcrd<=d & same) #ǰ�����<X��ͬchr-strand
x=gc(verbose =F)
indist=as.character(indist)
indist[indist=='FALSE']=paste('F',1:length(indist[indist=='FALSE']),sep='')
rle=Rle(indist)
cum=cumsum(runLength(rle))
Tto=cum[runValue(rle)=='TRUE']+1 ##��ȷ��TRUE����µ��������Ҫ��ֽ��ȷ������
Tfrom=cum[which(runValue(rle)=='TRUE')-1]+1
if (length(Tfrom)==length(Tto)-1) {
  Tfrom=c(1,Tfrom) #�����1������TRUE������Ҫ���ϣ���Ϊcum[0]Ϊ�գ�+1���ǿգ�Ԫ�ػ���һ��
}
if (length(Tfrom)!=length(Tto)) {
  stop("error: length(Tfrom)!=length(Tto)\n")
}
#write.table(cbind(Tfrom,Tto,Tto-Tfrom),file='tfrom_tTo.txt')
ir=IRanges(start=Tfrom,end=Tto)
gap=gaps(ir,start=1,end=nrow(allpa)) ##��ȥTRUE��ʣ���ΪFALSE���
gap=as.data.frame(gap)
Ffrom=unlist(apply(gap,1,function(par) return(seq(par[1],par[2])))) #F: ÿ��Ϊһ��
Fto=Ffrom
groups=rbind(cbind(from=Tfrom,to=Tto),cbind(from=Ffrom,to=Fto)) ##���յõ���������ֹ�±�
groups=groups[order(groups[,'from']),]
gnames=paste('g',1:nrow(groups),sep='')
pacChrStrand=allpa[groups[,'from'],c('chr','strand')]
groups=cbind(groups,pacChrStrand,gnames)
gnames=rep(gnames,times=groups[,'to']-groups[,'from']+1) ##��ţ�����aggregate
rm(rle,indist,gap,ir,cum,Tto,Tfrom,Fto,Ffrom,pacChrStrand)
x=gc(verbose=F)
##groups=from to  chr strand gnames

##��ÿ�����PA������tot
cat('Calculating nPA, PACtag (slow) ...\n')
smpcols=colnames(allpa)[!(colnames(allpa) %in% c('chr','strand','coord'))]
if (length(smpcols)>1) {
  tottag=rowSums(allpa[,smpcols])
} else {
  tottag=allpa[,smpcols]
}
allpa=cbind(allpa,tottag)
allpa=cbind(allpa,gnames)
UPA_start=allpa$coord[groups[,'from']]
UPA_end=allpa$coord[groups[,'to']]
nPA=groups[,'to']-groups[,'from']+1
groups=cbind(groups,UPA_start,UPA_end,nPA)

##���� single/multiple��ֻ��multiple�ر����
sgroups=groups[nPA==1,]
mgroups=groups[nPA>1,]
sallpa=allpa[allpa$gnames %in% sgroups$gnames,]
mallpa=allpa[allpa$gnames %in% mgroups$gnames,]
rm(groups,allpa)

smpcols=c(smpcols,'tottag')
x=gc(verbose =F)
mpactag=aggregate(mallpa[,smpcols], list(groups=mallpa$gnames), sum) #������PAC��tag�� (��)
x=gc(verbose =F)

##����ÿ��PAC�����꣨����tagλ�ã�
maxtag=aggregate(mallpa[,'tottag'], list(groups=mallpa$gnames),max) #������PAC��tag��
x=gc(verbose =F)
m=merge(maxtag,mallpa[,c('gnames','coord','tottag')],by.x=c('groups','x'),by.y=c('gnames','tottag'))
x=gc(verbose =F)
m=m[order(m$groups),] #�����maxtag��ͬ����ȡrle�����1��������һ����chr��ǰ1����
rle=Rle(m$groups)
cum=cumsum(runLength(rle))
m=m[cum,]
colnames(m)=c('groups','maxtag','coord')
mgroups=merge(mgroups,m,by.x='gnames',by.y='groups')
mgroups=merge(mgroups,mpactag,by.x='gnames',by.y='groups')
#single PA-PAC�Ĵ���
sallpa$maxtag=sallpa$tottag
sgroups=sgroups[,!(colnames(sgroups) %in% c('chr','strand'))]
sgroups=merge(sgroups,sallpa,by.x='gnames',by.y='gnames')
sgroups=sgroups[,colnames(mgroups)]
groups=rbind(sgroups,mgroups)

if (bigmem) {
save(groups,smpcols,anti,otbl,conf,file='groups.rda')
}

if (noGFF) { #������gff��ֱ�������chr��ϵĽ��
  cat('Not relate GFF, output to table\n')
  smpcols=smpcols[smpcols!='tottag']
  cols=c('chr','strand','coord','tottag','UPA_start','UPA_end','nPA','maxtag',smpcols)
  groups=groups[,cols]
  if (nrow(groups)>1e6) {  ##�������1M����ֻ����tot_tagnum>1����
    nr=nrow(groups)-sum(groups$tottag>1)
    groups=groups[groups$tottag>1,]
    cat('nPAC>1million, so remove',nr,'tottag=1 rows\n')
  }
  tmpfile=addStr(getTmpPath(1),'grpac.tmp')
  write.table(groups,file=tmpfile,col.names=F,row.names=F,quote=F,sep="\t")

  x=dbSendUpdate(dbh,sprintf("drop table if exists %s",otbl))
  sqlsmp=paste(smpcols,collapse=' int,')
  sqlsmp=paste(sqlsmp,' int',sep='')
  sql=sprintf("create table %s (chr varchar(20),strand char(1),coord int,tot_tagnum int,UPA_start int,UPA_end int,tot_PAnum int,ref_tagnum int,%s)",otbl,sqlsmp)
  x=dbSendUpdate(dbh,sql)
  x=loadFile2Tbl(dbh,otbl,tmpfile,0);
  x=file.remove(tmpfile)
  cat('END:',date(),'\n')
  quit();
}

##����gff��
rm(allpa,m,mpactag)
x=gc(verbose =F)
cat('Relating PAC with gff\n')
flds=dbListFields(dbh,gfftbl);
if ('ftrs' %in% flds & 'trspt_cnt' %in% flds) {
  gffcols=c('chr','strand','ftr','ftr_start','ftr_end','transcript','gene','gene_type','ftrs','trspt_cnt')
} else {
  gffcols=c('chr','strand','ftr','ftr_start','ftr_end','transcript','gene','gene_type')
}
gffcolstxt=paste(gffcols,collapse=',')
sql=sprintf("select %s from %s",gffcolstxt,gfftbl)
gff=sql2df(dbh,sql,header=T) 
gff=cbind(id=1:nrow(gff),gff)
if (sum(gff$ftr_end-gff$ftr_start<0)>0) { #2015/9/2 BUG������������parseGFF��BUG������10����GFF����������
  cat('Warning: remove length<=0 in GFF:',sum(gff$ftr_end-gff$ftr_start<0),'rows','\n')
  gff=gff[gff$ftr_end-gff$ftr_start>0,]
}
grgff=  GRanges(seqnames =gff$chr,
          ranges =IRanges(start=gff$ftr_start, end =gff$ftr_end),
          strand =gff$strand, id = gff$id)
grpac=GRanges(seqnames =groups$chr,
          ranges =IRanges(start=groups$coord, end =groups$coord),
          strand =groups$strand, gnames = groups$gnames)

ovp=findOverlaps(query=grpac, subject=grgff, select="first")  #ovpΪ��query��sbj���±�
dfgff=grgff[ovp[!is.na(ovp)]]
sensepac=grpac[!is.na(ovp)]
sensepac=as.data.frame(sensepac)
dfgff=as.data.frame(dfgff)
sensepac=cbind(gnames=sensepac$gnames,gffid=dfgff$id)
sensepac=merge(groups,sensepac,by.x='gnames',by.y='gnames')
rm(dfgff,ovp)
x=gc(verbose =F)
sensepac=merge(sensepac,gff[,!(colnames(gff) %in% c('chr','strand'))],by.x='gffid',by.y='id')
x=gc(verbose =F)

##antisense
if (anti) {
  cat('Relating PAC with anti_gff\n')
  gffplus=grgff[strand(grgff)=='+',]
  pacminus=grpac[strand(grpac)=='-',]
  ovp=findOverlaps(query=pacminus, subject=gffplus, select="first",ignore.strand =T)  #ovpΪ��query��sbj���±�
  gffplus=gffplus[ovp[!is.na(ovp)]]
  pacminus=pacminus[!is.na(ovp)]
  pacminus=as.data.frame(pacminus)
  gffplus=as.data.frame(gffplus)
  colnames(gffplus)=paste('anti_',colnames(gffplus),sep='')
  gffplus=cbind(gnames=pacminus$gnames,gffplus)
  rm(pacminus,ovp)
  x=gc(verbose =F)
  sensepacMinus=merge(sensepac[sensepac$strand=='-',],gffplus[,!(colnames(gffplus) %in% c('anti_chr','anti_strand'))],by.x='gnames',by.y='gnames')
  x=gc(verbose =F)

  gffplus=grgff[strand(grgff)=='-',]
  pacminus=grpac[strand(grpac)=='+',]
  ovp=findOverlaps(query=pacminus, subject=gffplus, select="first",ignore.strand =T)  #ovpΪ��query��sbj���±�
  gffplus=gffplus[ovp[!is.na(ovp)]]
  pacminus=pacminus[!is.na(ovp)]
  pacminus=as.data.frame(pacminus)
  gffplus=as.data.frame(gffplus)
  colnames(gffplus)=paste('anti_',colnames(gffplus),sep='')
  gffplus=cbind(gnames=pacminus$gnames,gffplus)
  rm(pacminus,ovp)
  x=gc(verbose =F)
  sensepacPlus=merge(sensepac[sensepac$strand=='+',],gffplus[,!(colnames(gffplus) %in% c('anti_chr','anti_strand'))],by.x='gnames',by.y='gnames')
  x=gc(verbose =F)
  sensepac=rbind(sensepacMinus,sensepacPlus)

  colnames(gff)=paste('anti_',colnames(gff),sep='')
  sensepac=merge(sensepac,gff,by.x='anti_id',by.y='anti_id')
  x=gc(verbose =F)
}

##ȷ������������
smpcols=smpcols[smpcols!='tottag']
if ('ftrs' %in% flds & 'trspt_cnt' %in% flds) {
  cols=c('chr','strand','coord','tottag','ftr','ftr_start','ftr_end','transcript','gene','gene_type','ftrs','trspt_cnt','UPA_start','UPA_end','nPA','maxtag',smpcols)
} else {
  cols=c('chr','strand','coord','tottag','ftr','ftr_start','ftr_end','transcript','gene','gene_type','UPA_start','UPA_end','nPA','maxtag',smpcols)
  }
if (anti) {
  cols=c(cols,paste('anti_',c('ftr','ftr_start','ftr_end','transcript','gene','gene_type'),sep=''))
}

cat('Loading pac to table',otbl,'\n')
sensepac=sensepac[,cols]
if (bigmem) {
  save(sensepac,file='sensepac.rda')
}

tmpfile=addStr(getTmpPath(1),'grpac.tmp')
if (nrow(sensepac)>1e6) {  ##�������1M����ֻ����tot_tagnum>1����
  nr=nrow(sensepac)-sum(sensepac$tottag>1)
  sensepac=sensepac[sensepac$tottag>1,]
  cat('nPAC>1million, so remove',nr,'tottag=1 rows\n') #1213535-695570=remove517965
}
write.table(sensepac,file=tmpfile,col.names=F,row.names=F,quote=F,sep="\t")

##���뵽otbl��
x=dbSendUpdate(dbh,sprintf("drop table if exists %s",otbl))
sqlsmp=paste(smpcols,collapse=' int,')
#cat(sqlsmp,'\n')
sqlsmp=paste(sqlsmp,' int',sep='')
#cat(sqlsmp,'\n')
antisql=''
if (anti) {
  antisql=",anti_ftr varchar(50),anti_ftr_start int,anti_ftr_end int,anti_transcript varchar(50),anti_gene varchar(50),anti_gene_type varchar(100)"
}
if ('ftrs' %in% flds & 'trspt_cnt' %in% flds) {
  sql=sprintf("create table %s (chr varchar(20),strand char(1),coord int,tot_tagnum int,ftr varchar(50),ftr_start int,ftr_end int,transcript varchar(50),gene varchar(50),gene_type varchar(100),ftrs varchar(50),trspt_cnt int,UPA_start int,UPA_end int,tot_PAnum int,ref_tagnum int,%s %s)",otbl,sqlsmp,antisql)
} else {
  sql=sprintf("create table %s (chr varchar(20),strand char(1),coord int,tot_tagnum int,ftr varchar(50),ftr_start int,ftr_end int,transcript varchar(50),gene varchar(50),gene_type varchar(100),UPA_start int,UPA_end int,tot_PAnum int,ref_tagnum int,%s %s)",otbl,sqlsmp,antisql)
}	  
x=dbSendUpdate(dbh,sql)
x=loadFile2Tbl(dbh,otbl,tmpfile,0);
x=file.remove(tmpfile)

cat('END:',date(),'\n')
