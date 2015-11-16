##########################################################################################
#2015/8/1
#用R实现 PA2PAC 或 PAs2PAC （与perl不同的是，是对chr进行group，再比对到gff中）
#先读取PA表或PAT文件，用merge成一个大表，再遍历chr进行group
#parameters & usage
#rscript "E:/sys/code/R/PAT_PA2PAC.r" path="f:/" bigmem=T mtbls="t_pa_rice_part1;t_pa_rice_part2;t_pa_rice_part3;t_pa_rice_part4;t_pa_rice_part5" smps="dry_seed1:dry_seed2:dry_seed3:embryo1:embryo2:endosperm1:endosperm2:endosperm3:imbibed_seed1:imbibed_seed2:imbibed_seed3;shoot1:shoot2:shoot3:leaf_20days1:leaf_20days2:leaf_20days3:leaf_60days1:leaf_60days2:leaf_60days3;stem_60days1:stem_60days2:stem_60days3:root_5days1:root_5days2:root_5days3:root_60days1:root_60days2:root_60days3;husk1:husk2:husk3:anther1:anther2:anther3:mature_pollen1:mature_pollen2:mature_pollen3;pistil1:pistil2:pistil3" osmps=NULL d=24 anti=T gfftbl="rice_gff7.t_gff7L_ae" conf="dbconf_ricepa.xml" otbl="rice_pac.test_pac" filedesc=NULL

# path: folder for filedesc or masterTable_Output
# bigmem:T/F; T for large PA file (分chr进行merge，否则数据小的话整个表merge会更快)
# *mtbls/smps/osmps: master tables sep by ;/:/, and smps like A1:A2:B1:B2;C1:C2 [smps用;隔开表]
# *filedesc: PA file description (filename samplename); PA file has only 4 columns but no header(chr,strand,coord,tag)
# d: distance for PAC
# anti=T(deafult)/F
# gfftbl=gff table
# noGFF=F(default)/T; if T then Not relate GFF (如果bigmem=T noGFF不管是T还是F，都需要提供gfftbl以得到chrs)
# conf=XML
# otbl=output PAC table#
# depend: GenomicRanges

# 注意：
#数据量大，过程会保存若干个.rda文件，如果出现问题，从中间文件开始在R中运行
#同一个Rscript命令，可能第1次运行出错，第2次运行又可以了。。原因不明！

#ex1# 从master表导入 (只需要指定的样本列，不一定要全部列)
#rscript "E:/sys/code/R/PAT_PA2PAC.r" path="f:/" bigmem=T mtbls="t_pa_rice_part1;t_pa_rice_part2" smps="dry_seed1:dry_seed2;pistil1:pistil2:pistil3" osmps=NULL d=24 anti=T gfftbl="rice_gff7.t_gff7L_ae" conf="dbconf_ricepa.xml" otbl="rice_pac.test_pac" filedesc=NULL

#ex2# 从文件导入PA并形成PAC
#rscript "E:/sys/code/R/PAT_PA2PAC.r" path="F:/sys_data/LiLab/FHH_rice_indica/indica2japonicaPAT/noXS" bigmem=T filedesc="PAfile.desc" d=24 anti=T gfftbl="rice_gff7.t_gff7L_ae" conf="dbconf_ricepa.xml" otbl="rice_pac.t_i2j_pac"

#ex3# 从master表导入 不关联GFF（但如果bigmem=T的话，还是要提供gfftbl）
#rscript "E:/sys/code/R/PAT_PA2PAC.r" path="f:/" bigmem=F mtbls="t_pa_rice_part1;t_pa_rice_part2" smps="dry_seed1:dry_seed2;pistil1:pistil2:pistil3" osmps=NULL d=24 noGFF=T  conf="dbconf_ricepa.xml" otbl="rice_pac.test_pac" filedesc=NULL


#输出 -- 最后导入的表otb，以及下面的临时文件(bigmem=T时保存.rda临时文件)
#  masterX -- filedesc情况下，合并几个文件生成的master表（6个样本一个master）                                                                                                 
#  allpa.rda --                                                                                               
#  groups.rda --                                                                                             
#  sensepac.rda -- 最后的pac（包括sense和anti（tot=1的也包括），但导入的mysql的表(如果数据量超过1million)则只包含tot_tagnum>1的行）  

#2015/9/3
#增加noGFF选项，允许不关联GFF，只进行聚类后输出（主要是防止有些GFF漏掉一些结果）
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
#osmps=smps #PA以表的形式

#path="F:/sys_data/LiLab/FHH_rice_indica/indica2japonicaPAT/noXS"
#filedesc='PAfile.desc' #PA以文件的形式：PAfile smp

#gfftbl='rice_gff7.t_gff7L_ae'
#conf='dbconf_ricepa.xml'
#d=24
#anti=T
#otbl='rice_pac.t_i2j_pac'
#bigmem=T

if (is.null(filedesc)) { #PA表来自表
	mtbls=unlist(strsplit(mtbls,'[;,:]'))
	##将smps和osmps，全读成list[[mtbl]]=c()的形式
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

#处理longchr
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

##读取master表到df (慢，耗内存)
palist=list()
if (!is.null(filedesc)) { #PA表来自文件，每6个文件一个master表
  x=read.table(filedesc,header=F)
  pafiles=x$V1
  pasmps=x$V2
  insmpls=list()
  osmpls=list()
  mtbls=c()
  ng=floor(nrow(x)/6)+ ifelse(nrow(x) %% 6==0,0,1)  
  cat('read PA files (6 files each time)',ng,'groups\n')
  for (i in 1:ng) { #将6个PA文件读到一个master阵中
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
} else {#从master表
	for (m in mtbls) {
	  cat('read PA table',m,'\n') #取 smp>0 的全部行
	  sql=sprintf("SELECT chr,strand,coord,%s FROM %s where %s",paste(insmpls[[m]],collapse=','),m,addStr(paste(insmpls[[m]],collapse='+'),'>0'));
	  palist[[m]]=sql2df(dbh,sql,header=T) 
	  colnames(palist[[m]])[-(1:3)]=osmpls[[m]]; #输出样本名
	  x=gc(verbose =F)
	}
}

##以上不论是来自文件或来自master表，都将以 mtbls和insmpls的形式存储
##对每个master表，进行merge  (慢，耗内存)
if (bigmem) { #分chr
  allpa=matrix(nrow=0,ncol=0)
  for (chr in chrs) {
   for (strand in c('+','-')) {
	cat('bigmem merging',chr,strand,'\n')
	if (chr=='ISSHORT') {
      idx=!(palist[[mtbls[1]]]$chr %in% chrs) & palist[[mtbls[1]]]$strand==strand
	  m=palist[[mtbls[1]]][idx,]
	} else {
	  idx=(palist[[mtbls[1]]]$chr==chr & palist[[mtbls[1]]]$strand==strand)
	  m=palist[[mtbls[1]]][idx,c('coord',osmpls[[mtbls[1]]])] #去掉chr,strand列
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
} else { #直接merge
	allpa=palist[[mtbls[1]]]
	for (mtbl in mtbls[-1]) {
	  cat('merging',mtbl,'\n')
	  pa2=palist[[mtbl]]
      allpa=merge(allpa,pa2,all=T,by.x=c('chr','strand','coord'),by.y=c('chr','strand','coord'))
      x=gc(verbose =F)
	}
  allpa[is.na(allpa)]=0
}

#至此，allpa存放的是所有的PA，可以删除palist
rm(list=c('m','palist','pa2'))
x=gc(verbose =F)
if (bigmem) {
  save(allpa,file='allpa.rda')
}

##对allpa进行group (很快)
cat('grouping PA by dist\n')
allpa=allpa[order(allpa$chr,allpa$strand,allpa$coord),]
x=gc(verbose =F)
diffcrd=diff(allpa$coord) #前后的距离
same=(allpa$chr[1:(nrow(allpa)-1)]==allpa$chr[2:nrow(allpa)]) & (allpa$strand[1:(nrow(allpa)-1)]==allpa$strand[2:nrow(allpa)])
#allpa[!same,c('chr','strand')]
indist=(diffcrd<=d & same) #前后距离<X且同chr-strand
x=gc(verbose =F)
indist=as.character(indist)
indist[indist=='FALSE']=paste('F',1:length(indist[indist=='FALSE']),sep='')
rle=Rle(indist)
cum=cumsum(runLength(rle))
Tto=cum[runValue(rle)=='TRUE']+1 ##先确定TRUE情况下的组别，这里要在纸上确定区域
Tfrom=cum[which(runValue(rle)=='TRUE')-1]+1
if (length(Tfrom)==length(Tto)-1) {
  Tfrom=c(1,Tfrom) #如果第1个就是TRUE，则需要补上，因为cum[0]为空，+1后还是空，元素会少一个
}
if (length(Tfrom)!=length(Tto)) {
  stop("error: length(Tfrom)!=length(Tto)\n")
}
#write.table(cbind(Tfrom,Tto,Tto-Tfrom),file='tfrom_tTo.txt')
ir=IRanges(start=Tfrom,end=Tto)
gap=gaps(ir,start=1,end=nrow(allpa)) ##除去TRUE，剩余的为FALSE组别
gap=as.data.frame(gap)
Ffrom=unlist(apply(gap,1,function(par) return(seq(par[1],par[2])))) #F: 每行为一组
Fto=Ffrom
groups=rbind(cbind(from=Tfrom,to=Tto),cbind(from=Ffrom,to=Fto)) ##最终得到的组别的起止下标
groups=groups[order(groups[,'from']),]
gnames=paste('g',1:nrow(groups),sep='')
pacChrStrand=allpa[groups[,'from'],c('chr','strand')]
groups=cbind(groups,pacChrStrand,gnames)
gnames=rep(gnames,times=groups[,'to']-groups[,'from']+1) ##组号，用于aggregate
rm(rle,indist,gap,ir,cum,Tto,Tfrom,Fto,Ffrom,pacChrStrand)
x=gc(verbose=F)
##groups=from to  chr strand gnames

##对每组计算PA数，及tot
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

##划分 single/multiple，只对multiple特别计算
sgroups=groups[nPA==1,]
mgroups=groups[nPA>1,]
sallpa=allpa[allpa$gnames %in% sgroups$gnames,]
mallpa=allpa[allpa$gnames %in% mgroups$gnames,]
rm(groups,allpa)

smpcols=c(smpcols,'tottag')
x=gc(verbose =F)
mpactag=aggregate(mallpa[,smpcols], list(groups=mallpa$gnames), sum) #各样本PAC的tag数 (慢)
x=gc(verbose =F)

##计算每个PAC的坐标（最大的tag位置）
maxtag=aggregate(mallpa[,'tottag'], list(groups=mallpa$gnames),max) #各样本PAC的tag数
x=gc(verbose =F)
m=merge(maxtag,mallpa[,c('gnames','coord','tottag')],by.x=c('groups','x'),by.y=c('gnames','tottag'))
x=gc(verbose =F)
m=m[order(m$groups),] #如果有maxtag相同，则取rle的最后1个（并不一定是chr的前1个）
rle=Rle(m$groups)
cum=cumsum(runLength(rle))
m=m[cum,]
colnames(m)=c('groups','maxtag','coord')
mgroups=merge(mgroups,m,by.x='gnames',by.y='groups')
mgroups=merge(mgroups,mpactag,by.x='gnames',by.y='groups')
#single PA-PAC的处理
sallpa$maxtag=sallpa$tottag
sgroups=sgroups[,!(colnames(sgroups) %in% c('chr','strand'))]
sgroups=merge(sgroups,sallpa,by.x='gnames',by.y='gnames')
sgroups=sgroups[,colnames(mgroups)]
groups=rbind(sgroups,mgroups)

if (bigmem) {
save(groups,smpcols,anti,otbl,conf,file='groups.rda')
}

if (noGFF) { #不关联gff，直接输出以chr组合的结果
  cat('Not relate GFF, output to table\n')
  smpcols=smpcols[smpcols!='tottag']
  cols=c('chr','strand','coord','tottag','UPA_start','UPA_end','nPA','maxtag',smpcols)
  groups=groups[,cols]
  if (nrow(groups)>1e6) {  ##如果超过1M，则只导入tot_tagnum>1的行
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

##关联gff表
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
if (sum(gff$ftr_end-gff$ftr_start<0)>0) { #2015/9/2 BUG修正：可能是parseGFF的BUG导致有10来条GFF的区间有误！
  cat('Warning: remove length<=0 in GFF:',sum(gff$ftr_end-gff$ftr_start<0),'rows','\n')
  gff=gff[gff$ftr_end-gff$ftr_start>0,]
}
grgff=  GRanges(seqnames =gff$chr,
          ranges =IRanges(start=gff$ftr_start, end =gff$ftr_end),
          strand =gff$strand, id = gff$id)
grpac=GRanges(seqnames =groups$chr,
          ranges =IRanges(start=groups$coord, end =groups$coord),
          strand =groups$strand, gnames = groups$gnames)

ovp=findOverlaps(query=grpac, subject=grgff, select="first")  #ovp为有query的sbj的下标
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
  ovp=findOverlaps(query=pacminus, subject=gffplus, select="first",ignore.strand =T)  #ovp为有query的sbj的下标
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
  ovp=findOverlaps(query=pacminus, subject=gffplus, select="first",ignore.strand =T)  #ovp为有query的sbj的下标
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

##确定输出表的列名
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
if (nrow(sensepac)>1e6) {  ##如果超过1M，则只导入tot_tagnum>1的行
  nr=nrow(sensepac)-sum(sensepac$tottag>1)
  sensepac=sensepac[sensepac$tottag>1,]
  cat('nPAC>1million, so remove',nr,'tottag=1 rows\n') #1213535-695570=remove517965
}
write.table(sensepac,file=tmpfile,col.names=F,row.names=F,quote=F,sep="\t")

##导入到otbl中
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
