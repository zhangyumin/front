#2011/7/1 R11的函数库
#source('E:\\sys\\code\\R\\R_funclib.r')

#2011/7/1 标准化，结果统计相关的函数
# getSizeFactor(matrix,'EDGER'/'DESEQ')
# normBySizeFactor(matrix,sizeFactor)
# doDE(dat,method,doNorm=T,grpInd1,grpInd2,grpLbl1,grpLbl2,sizeFactor=NULL,verbose=T)
# ovpDERet(ret,what=pval/padj,thds,tops)
# topRet(ret,cols,thds,conds,groupby)

#2011/7/6 对profile的chisq.test函数
#mat=chisqATCG(path,files,names,,default,totSeq,ofile,toPlot=F)

#2011/7/8 增加函数 mat=shuffleNames<-function(group1,group2,r)
#用于DH说的shuffle DE的计算

#2011/11/13 增加DExPAC的检测函数 
#AinB<-function(A,B,all=T) 判断是否AinB，来自 SBS_funclib.r
#filterByCpmQnt(dat,filterType='cpm_quant',CPM=2,QNT=0.2):mtx 根据TPM和quantile过滤数据
#DE1PAC(pac,g1Cols,g2Cols,g1Lbl='WT',g2Lbl='Mutant',filterType='cpm_quant',CPM=2,QNT=0.2,filterIGT=T,nSum=10,nDiff=5,nFold=2,pvalue=0.01):mtx
#DE2PAC(pac,smpCols,smpLbls,filterType='cpm_quant',CPM=2,QNT=0.2,filterIGT=T,dist=50,nOne=10,nSum=50,nDiff=5,nFold=2,pvalue=0.01,top2=T,SL=T)
#DE3PAC(pac,g1Cols,g2Cols,g1Lbl='WT',g2Lbl='Mutant',filterType='cpm_quant',CPM=2,QNT=0.2,filterUTR=T,pvalue=0.01)

#2011/11/15 增加patrick的比较两样本的方式
#.plotCum<-function(array,intervals,...) {
#patrick<-function(pac,g1Cols,g2Cols,g1Lbl='WT',g2Lbl='Mutant',filterType='cpm_quant',CPM=2,QNT=0.2,TOT=10,toPlot=T){

#2011/11/16 增加multi2two，将几列合并成1列，并修改相应的DExPAC系列函数
#multi2two(dat,smpCols,smpLbls,mergeType='AVG',normFirst=T,normAfter=F) 
#改进了filterByCpmQnt，增加了type种类
#增加函数  LOG('txt',file,append=T)

#2011/11/19 修改标准化函数，只用DESEQ的
#getSizeFactor

#2012-07-20 增加DoDEs等函数，用于输入文件，输出DE文件，并对单个或2个DE文件做统计
#oret:DoDEs(path,group1,group2,lbl1,lbl2,doNorm=1,file,filecols,minpat,minrep,osurfix,ocols)
#statDEs(path,retfile,thds,tops)
#stat2DE(path,retfile1,retfile2,thd,ofile)

#2012-08-23 增加FU函数，计算PAC的差异
#增加FUgene函数，计算一堆gene的cor

options(stringsAsFactors=F)
#library('IRanges')
#library(gtools)

XMLDIR='/var/www/front/src/r/';     
TMPDIR='/var/www/front/searched';  
RDIR='/var/www/front/src/r/'
PERLEXE='perl'
#PERLINFO='E:/sys/code/PAT/PAT_geneInfo.pl'
#PERLUTR='E:/sys/code/PAT/PAT_avgUtrLen.pl'
#PERLPACINFO='E:/sys/code/PAT/PAT_PACInfo.pl'
#PERLPAGFF='E:/sys/code/PAT/CMP_PA2Gff.pl'
#RPAGFF="E:/sys/code/R/UTIL_plotPAGff.r"
PERLSWITCH='/var/www/front/src/perl/CMP_switchGene.pl'
#PERLSCOREPROF="E:/sys/code/ATLAS/UTIL_scoreProfile.pl"
#PERLRANDPAC="E:/sys/code/UTIL/UTIL_rndPAC.pl"


RICEJPCOLS="dry_seed1:dry_seed2:dry_seed3;embryo1:embryo2;endosperm1:endosperm2:endosperm3;imbibed_seed1:imbibed_seed2:imbibed_seed3;shoot1:shoot2:shoot3;leaf_20days1:leaf_20days2:leaf_20days3;leaf_60days1:leaf_60days2:leaf_60days3;stem_60days1:stem_60days2:stem_60days3;root_5days1:root_5days2:root_5days3;root_60days1:root_60days2:root_60days3;husk1:husk2:husk3;anther1:anther2:anther3;mature_pollen1:mature_pollen2:mature_pollen3;pistil1:pistil2:pistil3"
INDICACOLS="dry_seed1:dry_seed2:dry_seed3;embryo1:embryo2:embryo3;endosperm1:endosperm2:endosperm3;imbibed_seed1:imbibed_seed2:imbibed_seed3;shoot1:shoot2:shoot3;leaf_20days1:leaf_20days2:leaf_20days3;leaf_60days1:leaf_60days2:leaf_60days3;stem_60days1:stem_60days2:stem_60days3;root_5days1:root_5days2:root_5days3;root_60days1:root_60days2:root_60days3;husk1:husk2:husk3;anther1:anther2:anther3;mature_pollen1:mature_pollen2:mature_pollen3;pistil1:pistil2:pistil3"
RICETWOCOLS="i_dry_seed1:i_dry_seed2:i_dry_seed3;i_embryo1:i_embryo2:i_embryo3;i_endosperm1:i_endosperm2:i_endosperm3;i_imbibed_seed1:i_imbibed_seed2:i_imbibed_seed3;i_shoot1:i_shoot2:i_shoot3;i_leaf_20days1:i_leaf_20days2:i_leaf_20days3;i_leaf_60days1:i_leaf_60days2:i_leaf_60days3;i_stem_60days1:i_stem_60days2:i_stem_60days3;i_root_5days1:i_root_5days2:i_root_5days3;i_root_60days1:i_root_60days2:i_root_60days3;i_husk1:i_husk2:i_husk3;i_anther1:i_anther2:i_anther3;i_mature_pollen1:i_mature_pollen2:i_mature_pollen3;i_pistil1:i_pistil2:i_pistil3;j_dry_seed1:j_dry_seed2:j_dry_seed3;j_embryo1:j_embryo2;j_endosperm1:j_endosperm2:j_endosperm3;j_imbibed_seed1:j_imbibed_seed2:j_imbibed_seed3;j_shoot1:j_shoot2:j_shoot3;j_leaf_20days1:j_leaf_20days2:j_leaf_20days3;j_leaf_60days1:j_leaf_60days2:j_leaf_60days3;j_stem_60days1:j_stem_60days2:j_stem_60days3;j_root_5days1:j_root_5days2:j_root_5days3;j_root_60days1:j_root_60days2:j_root_60days3;j_husk1:j_husk2:j_husk3;j_anther1:j_anther2:j_anther3;j_mature_pollen1:j_mature_pollen2:j_mature_pollen3;j_pistil1:j_pistil2:j_pistil3"

#if (!file.exists(PERLINFO)) {
#  stop(PERLINFO,' PERLINFO not exists!')
#}
#if (!file.exists(PERLUTR)) {
#  stop(PERLUTR,' PERLUTR not exists!')
#}

#-------------------------------------------------
# common
#-------------------------------------------------
## getOptions(args,opts)
## 由args串，以及给定的选项，取得 xx=AA的list
## usage 2015/4/10:
## args=commandArgs()
## args=c("path=f:/","cols=INDICACOLS")
## opts=getOptions(args,c('intbl','cols','suffix','conf','path'));
## 对于不存在值的opt，置为NULL
getOptions<-function(args,opts) {
  lst=list()
  for (arg in args) {
	for (opt in opts) {
	  idx=as.integer(gregexpr(paste('^',opt,'=',sep=''),arg))  
	  if (idx!=-1) {
		lst[[opt]]=substr(arg,nchar(paste(opt,'=',sep=''))+1,nchar(arg));
	  }	  
	}
  }  
  for (opt in opts) {
	if (!is.null(lst[[opt]])) { #R里面很奇怪需要分两个if写，否则可能出错。因为 & 它也不是判断一个条件，而是2个条件要一起判断
	  if (toupper(lst[[opt]])=='NULL') {
	    lst[[opt]]=NULL;
      } else if (lst[[opt]]=='') {
	    lst[[opt]]=NULL;
      }else if (toupper(lst[[opt]])=='RICEJPCOLS') {
	    lst[[opt]]=RICEJPCOLS;
      } else if (toupper(lst[[opt]])=='INDICACOLS') {  #简写cols列
		lst[[opt]]=INDICACOLS
	  }else if (toupper(lst[[opt]])=='RICETWOCOLS') {  #简写cols列
		lst[[opt]]=RICETWOCOLS
	  }
	}
  }
  return(lst)
}



## 把所给的字符串连接起来
addStr<-function(...,sep='') {
  return(paste(...,sep=sep)) 
}

## 给文件添加行，如果不提供txt则添加空行
addTxt2File<-function(file,txt=NULL) {
  if (is.null(txt)) {
	txt="\n";
  }
  x=write.table(txt,file=file,sep='',quote=F,row.names=F,col.names=F,append=T);
}

##trim 去除头尾空格
trim<-function(x) {
  return(gsub("(^ +)|( +$)", "", x))
}

#############################################################################
#wilcoxTest(x,y,random=100)
#wilcox检验，满足50个才检验
#random>0，则自动随机筛选X次少的向量，取pvalue的平均值
#############################################################################
wilcoxTest<-function(v1,v2,random=0) {
  if (length(v1)<=30 | length(v2)<=30) {
	return(-1);
  }
 if (random==0 | length(v1)==length(v2)) {
    return(wilcox.test(v1,v2)$p.value);
 }
 if (length(v1)<length(v2)) {
   big=v2
   small=v1
 } else {
   big=v1
   small=v2
 } 
 sumpv=0 
 for (i in 1:random) {
   x=sample(big,size=length(small),replace=F)
   sumpv=sumpv+wilcox.test(x,small)$p.value
 }
 pv=sumpv/random
 return(pv)
}


#############################################################################
#   getTmpPath($bar):str
#  useage: $str=getTmpPath(1)       
#  说明: 仅限于本机的临时路径. bar=1末端有/,0无/
#############################################################################
getTmpPath <-function(bar=FALSE) {
  mine=TMPDIR;
  if (file.exists(mine)) {
    if (bar) return(addStr(mine,'/'));
	return(mine);
  } else {
	others='f:/tmpdir';
	if (!file.exists(others)) dir.create(others) ;
	if (bar) return(addStr(others,'/')) ;
	return(others);
  }
}

#path=setPath(path)
#根据给定path，设置当前路径，且返回新的path
#如果path不存在，则取得tmpPath
setPath<-function(path=NULL) {
	if (is.null(path)) {
	  path=getTmpPath(1);
	} else if  (path=='' | !file.exists(file.path(path))) {
	  path=getTmpPath(1);
	}
	setwd(path)
	return(path)
}

#############################################################################
#  y=removeOutliers(x)
#  作用: 去除x中的异常值，x为向量，异常值会置为NA
#  IQR=1.5 SD=3 如果有提供SD按标准差方式去除，否则有提供IQR，则按照IQR去除；
#  fill=T 则用上限和下限去填充
#############################################################################
removeOutliers <- function(x,IQR=1.5,SD=NULL,fill=F) {
  if (!is.null(SD)) {
	datsd=sd(x)
	H=mean(x)+datsd*SD
	L=mean(x)-datsd*SD
	y=x
	if (fill) {
	  y[x<L]=L
      y[x>H]=H
	} else {
	  y[x<L]=NA
      y[x>H]=NA
	}

	return(y)
  }  
  if (IQR>0) {
	  qnt <- quantile(x, probs=c(.25, .75))
	  H <- IQR * IQR(x)
	  y <- x
	if (fill) {
	  y[x < (qnt[1] - H)] <- qnt[1] - H
	  y[x > (qnt[2] + H)] <- qnt[2] + H
	} else {
	  y[x < (qnt[1] - H)] <- NA
	  y[x > (qnt[2] + H)] <- NA
	}
	  return(y)
  } 
}

#############################################################################
#  给定一个向量，以及一个区间，取得在区间内的累积频率，用于作累积图
#  y=getCumFreq(sample(1:100000,500),intervals=seq(0,10,1))
#  返回y：向量，names(y)为intervals，值为累积频率
#############################################################################
getCumFreq<-function(array,intervals) {
  idx=findInterval(array,intervals)
  cnt=tapply(idx,as.factor(idx),length)
  x=intervals; y=rep(0,length(x)); 
  y[as.integer(names(cnt))]=cnt
  y=cumsum(y)/sum(y)
  names(y)=x
  return(y)
}


#############################################################################
#  connectDB($conf):db
#  作用: 连接数据库
#  若当前文件夹下不存在conf,则去 XMLDIR 下搜
#  Usage: dbh=connectDB('dbconf_ricepac.xml')
#  增加items选项，允许输出额外的如chr之类的信息，输出为list，用list[['conn']]=xx 
#############################################################################
connectDB<-function(conf,items=NULL) {
	require("XML",quietly =T)
	require("RJDBC",quietly =T)
	drv <- JDBC("com.mysql.jdbc.Driver",
			   paste(RDIR,"mysql-connector-java-5.1.13-bin.jar",sep=''),
			   identifier.quote="`")

	if (!file.exists(conf)) {
	  conf=addStr(XMLDIR,conf);
	  if (!file.exists(conf)) {
		 stop(addStr(conf,' not exists!'))
	  }
	}
	#读取conf 
	conf=xmlRoot(xmlTreeParse(conf))
	db=xmlValue(conf[['dbname']])
	user=xmlValue(conf[['user']])
	pwd=xmlValue(conf[['password']])
	host=xmlValue(conf[['dbhost']])

	#连接DB  
	sqldb=paste("jdbc:mysql://",host,"/",db,sep='')
	conn <- dbConnect(drv, sqldb, user, pwd) 
    if (is.null(items)) {
	  return(conn)
    }
	  olist=list()
	  olist[['conn']]=conn;
	  for (i in items) {
		olist[[i]]=xmlValue(conf[[i]])
	  }
	  return(olist)
}

#输出表到文件 或 dataframe，如果file为空的话，则返回df
#df=tbl2file(dbh,'atbl','',header=T)
#tbl2file(dbh,'atbl','xx.txt',header=T)
tbl2file<-function(conn,tbl,file,header=F) {
	toDF=F
	if (file=='' | is.null(file)) {
      file=addStr(getTmpPath(1),'tbl2df.tmp')
	  toDF=T
	}
	if (file.exists(file)) x=file.remove(file);
	a=dbSendQuery(conn,paste("select * from ",tbl," into outfile ",'\'',file,'\'',sep=''))
	if (!header & !toDF) {
	  return('');
	} 
	#print(header)
	a=read.table(file,header=F,sep="\t",quote="",comment.char="")
	if (!header & toDF) {
      x=file.remove(file);
	  return(a);
	} 
	colnames(a)=dbListFields(conn,tbl);
	if (header & toDF) {
      x=file.remove(file);
	  return(a);
	} 
    write.table(a,file=file,sep="\t",col.names=T,row.names=F,quote=F)	
	return('')
}


#输出SQL到文件 
#如果从sql得到的标题数与file不同，则不添加标题
#usage 2015/4/16
#sql2file(conn,sql,pacfile)
sql2file<-function(conn,sql,file,header=F) {
	if (file.exists(file)) x=file.remove(file);
	sql=paste(sql," into outfile ",'\'',file,'\'',sep='')
	a=dbSendQuery(conn,sql)
	if (!header) return();
	#取得 select A,B from ... 的A和B作为标题行
	seltxt=unlist(strsplit(sql,split=" FROM|from ",perl=T))[1]
    seltxt=gsub("\\s+","",seltxt) #去掉空格 
	seltxt=gsub("^select","",seltxt,ignore.case =T)
	if (seltxt=='*') { #如果是* select * from xxtbl
	  fromtxt=unlist(strsplit(sql,split=" FROM|from ",perl=T))[2]
	  fromtxt=unlist(strsplit(fromtxt,split="\\s+"))
	  tbl=fromtxt[!nchar(fromtxt)==0][1]	  
	  cols=dbListFields(conn,tbl); #如果是xx.tbl的形式，则不会输出结果？？
	} else {
	  cols=unlist(strsplit(seltxt,split=","))
	}
	a=read.table(file,header=F,sep="\t",quote="")
	if (ncol(a)==length(cols)) {
	  colnames(a)=cols
	  write.table(a,file=file,sep="\t",col.names=T,row.names=F,quote=F)	
	} else {
	  cat("sql2file() warning: header=T but cannot get right headers from sql","\n")
	}
	return('')
}

#输出SQL到data.frame
#df=sql2df(conn,sql,header=T) 
sql2df<-function(conn,sql,header=T) {
  tmpfile=addStr(getTmpPath(1),'sql2df.tmp')
  x=sql2file(conn,sql,tmpfile,header=header)
  df=read.table(tmpfile,header=header,sep="\t",quote="")
  file.remove(tmpfile)
  return(df)
}

#############################################################################
#  loadFile2Tbl(dbh,tbl,file,$ignoreLine):int;
#  useage: $rv=loadFile2Tbl($dbh,$tbl,$file,0)
#  说明:文件导入数据表
#############################################################################
loadFile2Tbl<-function(dbh,tbl,file,ignoreLine=0){
  rn='\n'; #win
  t='\t';
  if (ignoreLine<=0) { 
    #sql=sprintf("load data infile \'%s\' into table %s",file,tbl)
    sql=sprintf("load data infile \'%s\' into table %s fields terminated by \'%s\' enclosed by \'\' Lines Terminated By \'%s\'",file,tbl,t,rn)
  } else {
    sql=sprintf("load data infile \'%s\' into table %s fields terminated by \'%s\' enclosed by \'\' Lines Terminated By \'%s\' ignore %d lines",file,tbl,t,rn,ignoreLine)
    #sql=sprintf("load data infile \'%s\' into table %s ignore %d lines",file,tbl,ignoreLine)
  }
 #cat(sql,'\n')
  x=dbSendUpdate(dbh,sql)   
  return(x)
}

#############################################################################
#  df2Tbl(dbh,tbl,df,$ignoreLine):int;
#  useage: $rv=df2Tbl($dbh,$tbl,$df,0)
#  说明:data.frame导入数据表
#############################################################################
df2Tbl<-function(dbh,tbl,df,ignoreLine=0){
  tmpfile=addStr(getTmpPath(1),'df2Tbl.tmp')
  write.table(df,file=tmpfile,row.names=F,col.names=F,sep="\t",quote=F)
  x=loadFile2Tbl(dbh,tbl,tmpfile,ignoreLine)
  file.remove(tmpfile)
  return(x)
}


#############################################################################
#  getFldValues($dbh,$sql,$ncol首0):@fldValue
#  useage: fldValue=getFldValues($dbh,'select distinct(chr) from xx order by chr',0);
#  说明:取得某列的值,返回向量
#############################################################################
getFldValues <-function (dbh,sql,ncol) {
  res <- dbSendQuery(dbh, sql)
  df=dbFetch(res)
  x=dbClearResult(res)
  return(df[,ncol+1]);
}


#############################################################################
#  AinB(c('start','end'),c('start','x','y'),all=T)
#  useage: AinB(c('start','end'),c('start','x','y'),all=T)
#  说明:
#  1) #判断A向量是否在B向量中, all=T全,F部分; 返回T/F
#############################################################################
AinB<-function(A,B,all=T){
  if (!(is.vector(A) & is.vector(B))) {
    return(F)
  }
  x=sum(A %in% B)
  if ((all & x==length(A)) | (!all & x>0)) {
    return(T)
  } else {
    return(F)
  }  
}

#############################################################################
#  LOG(txt,file,append=T) 记录并显示日志
#  useage: LOG('txt',file,append=T)
#  说明:
#  1) 输出的txt会自动换行
#############################################################################
LOG<-function(txt,file,append=T){
  write(txt,file=file,append=append)
  cat(txt,'\n')
}


#-------------------------------------------------
# polyA
#-------------------------------------------------
#aList=cols2list(cols,groups=NULL)
#分析opt的cols，得到list[[group]]=A1,A2,A3，如 [[1]]=dry1 dry2 dry3
#程序中会判断 groups是否与cols相同组数
#usage 2015/4/16
#cols=A1:A2;B1:B2;C1,C3
#aList=cols2list(cols='A1:A2;B1,B2;C1:C2',groups=NULL or c(A,B,C)
#返回的aList的names为groups，如果没有提供groups，则先会智能提取groups，如果个数不对，则就直接编号，不提供groups
cols2list<-function(cols,groups=NULL) {
	colid=list()
	xx<- unlist(strsplit(cols,'[;]'))
	if (is.null(groups)) {
	  autoGrp=cols2group(cols,T)
	  if (length(autoGrp)==length(xx)) {
		groups=autoGrp
	  } else {
	    groups=1:length(xx)
	  }
	}
	if (length(groups)!=length(xx)) {
	  stop('error cols2list: not same ngroup in groups/cols!')
	}
	for (i in 1:length(groups)) {
		colid[[groups[i]]]=unlist(strsplit(xx[i],'[:,]'))
	}
	names(colid)=groups
	return(colid);
}

#智能判断输入的样本列的组别，去掉末尾的1个数字，再取其它的unique
#groups=dryseed leaf root
#usage 2015/4/16
#cols=A1:A2;B1:B2;C1,C3
#groups=cols2group(cols='A1:A2;B1,B2;C1:C2')
#如果auto=F，则将 :,; 作为分隔符，groups=A1,A2,B1,B2...
#如果auto=T，则将 ; 作为样本分隔符，再去掉rep末位的1个数字，groups=A,B,C...
# cols2group(cols='A;B',auto=T) --> "A" "B" #如果末尾不是数字结尾，不管auto=T或F，都是直接返回auto=F的情况
# cols2group(cols='A;B',auto=F) --> "A" "B"
# cols2group(cols='A1;B1',auto=F)  --> "A1" "B1"
# cols2group(cols='A1;B1',auto=T) --> "A1" "B1"
# cols2group(cols='A1;A2',auto=T) --> "A1" "A2"
cols2group<-function(cols,auto=T) {
	if (!auto) {
	  groups=unlist(strsplit(cols,'[,;:]')) 
	} else {
		if (length( unlist(strsplit(cols,'[,;:]')))==length( unlist(strsplit(cols,'[;]')))) { #如果类似 A1;A2;B1;B2，则直接返回
		  groups=unlist(strsplit(cols,'[,;:]')) 
		  return(groups)
		} else if (length(grep(';',cols))==1) {
		  conds<- unlist(strsplit(cols,'[,;:]'))
		  if (length(grep('[0-9]$',conds))!=length(conds)) { #如果末尾不是数字结尾，则说明没有rep的情况
			groups=conds
		    return(groups)
		  }
		  groups=unique(substr(conds,1,nchar(conds)-1)) #如果输入含；，则表示有reps，自动将最后1位数字去掉当DESeq2的condition
		} 
	}
	return(groups);
}

#reps=cols2reps(cols='A1:A2;B1,B2;C1:C2',groups=NULL,auto=T)
#取得cols的重复rep字段，可用于DE分析
#若不提供groups，则自动根据规则先得到groups，再进行rep获取
#usage 2015/4/16
#cols=A1:A2;B1:B2;C1,C3
#如果auto=F，则将 用提供的groups
#如果auto=T，则未提供groups时，用cols取得groups
#如果提供groups，则auto不起作用
# cols2reps(cols='A1;B1') --> "A" "B"
# cols2reps(cols='A1:A2;B1') --> "A" "A" "B"
cols2reps<-function(cols,groups=NULL,auto=T) {
	reps=c()
	if (is.null(groups) & auto) {
       groups=cols2group(cols,auto=T)
	} else if (is.null(groups) & !auto) {
	   stop("cols2reps: no group and auto=F")
	}
	xx<- unlist(strsplit(cols,'[;]'))
	if (length(xx)!=length(groups)) {
	  stop("cols2reps: not same group number in cols and groups!")
	}
	i=1
	for (x in xx) {
		reps=c(reps,rep(groups[i],length(unlist(strsplit(x,'[:,]')))) )
		i=i+1
	}
    return(reps);
}

#取得给定文件标题行中的样本列
#usage 2015/4/16
#getHeaderSmps(file,c('A1','A2','B1','B2'))
getHeaderSmps<-function(file,conds) {
  colnames=scan(file,what='character',nlines=1,sep="\t",quote="",quiet=T)
  smps=intersect(colnames,conds)
  if (length(smps)==0) {
	stop('no sample cols in ',file,'\n')
  }
  return(smps)
}

#给定colid列表，以及smps，返回一个列表，把smps分成不同的组别
#usage 2015/4/16
#lst=smps2list(c('A1','A2','B1','B2'),colid)
  smps2list<-function(smps,colid) {
  alist=list()
  k=1
  for (j in 1:length(colid)) {
	if (length(smps)==0) {
	  break;
	}
	gi=colid[[j]] #第i组的所有样本
	gcol=smps[!is.na(match(smps,gi))]
	if (length(gcol)>0) {
	  alist[[k]]=gcol
	  if (!is.null(names(colid))) {
	    names(alist)[k]=names(colid)[j]
	  }
	  k=k+1
	  smps=smps[is.na(match(smps,gi))]
	}
  }
  if (length(smps)>0) {
	stop('smps2list:',smps,'not in colid','\n');
  }
  return(alist)
}

#输出table中gene的相应样本的表达量到文件中
#如果extra中含tot_tagnum列，则实际用的是sum(smp)的值，而非原表的tot_tagnum
#tot_tagnum放的位置与extra中的位置一致
#要注意MySQL不会判断group的列是否是可以group的，而只会返回第一行，所以要注意！！！！
#输出：<extra>+sum(A),sum(B)...
#条件：noIGT+smps>0
#usage 2015/4/16
#geneSql2file(conn,intbl,smps,file,extra=c('gene','gene_type','chr','strand','tot_tagnum'))
geneSql2file<-function(conn,intbl,smps,file,extra=c('gene','gene_type','chr','strand','tot_tagnum')) {
	if (file.exists(file)) x=file.remove(file);
	sumtxt=paste(paste('sum(',smps,sep=''),')',sep='')
	tottxt=paste(smps,collapse='+')
	extraTxt=''
	if (length(extra)>0) {
	  for (x in extra) {
		if (x=='tot_tagnum') {
		  extraTxt=addStr(extraTxt,'sum(',tottxt,'),')
		} else {
		  extraTxt=addStr(extraTxt,x,',')
		}
	  }
	}
	sql=paste('select',extraTxt,toString(sumtxt),' from ',intbl,' where ',tottxt,'>0 and ftr not like \'%inter%\' group by gene')
	sql2file(conn,sql,file)
	return(T)
}

## plotHeatmap(dat,sizefactor=NULL,title='')
## 对一个矩阵类型的dat(列名为样本名)，作DESeq的样本间的heatmap图
## usage 2015/4/10
## pdf(file=addStr(intbl,'.sampleCor.pdf'))
## plotHeatmap(PAC,sf,'PAC')
## plotHeatmap(gene,gsf,'gene')
## dev.off()
plotHeatmap<-function(dat,sizefactor=NULL,title='') {
	#require("DESeq",quietly =T)
	suppressPackageStartupMessages( library( "DESeq" ) )

	require("RColorBrewer",quietly =T)
	cdsFull <- newCountDataSet(dat,colnames(dat))
	if (!is.null(sizefactor)) {
	  sizeFactors(cdsFull) <- sizefactor
	} else {
	  sizeFactors(cdsFull)=rep(1,ncol(dat))
	}

	#if (!is.null(sizefactor)) {
	#  sizeFactors(cdsFull) <- sizefactor
	#} else {
	#  cdsFull <- estimateSizeFactors( cdsFull )
	#}
	cdsFullBlind <- estimateDispersions( cdsFull, method="blind",fitType='local' )
	vsdFull <- DESeq::getVarianceStabilizedData( cdsFullBlind )
	dists <- dist( t( vsdFull ) )
	heatmap( as.matrix( dists ),
	   symm=TRUE, scale="none", margins=c(10,10),
	   col = colorRampPalette(c("darkblue","white"))(100),
	   labRow = paste( pData(cdsFullBlind)$condition, pData(cdsFullBlind)$type ),main=title )
}

## rld1=plotPCA(dat,reps,sizefactor=NULL)
## DESeq2画PCA图
## reps是同样本的列，比如 AA,AA,BB,BB,BB表示有两个样本
## usage 2015/4/10:
## rld1=plotPCA(PAC,reps,sf) 
## rld转换这一步很慢 样本多时很难有结果！！
plotPCA<-function(dat,reps,sizefactor=NULL) {
	suppressPackageStartupMessages( library( "DESeq2" ) )
	#require("DESeq",quietly =T)
	#detach("package:DESeq")
	sampleData <- data.frame(condition=as.factor(reps))
	design <- formula( ~ condition )
	dds <- DESeqDataSetFromMatrix(dat, sampleData, design)
	if (!is.null(sizefactor)) {
	  sizeFactors(dds) <- sizefactor
	} else {
	  sizeFactors(dds)=rep(1,ncol(dat))
	}
	dds$condition <- relevel(dds$condition, reps[1]) #设置基准tissue，以在计算log2时有一个base
	cat("plotPCA: rlog(fast)...",'\n')
	rld <- rlog(dds,fast=T) #数据形式转换
	DESeq2::plotPCA(rld, intgroup=c("condition"),ntop=20000)
	return(rld)
}

#############################################################################
#  mat=chisqATCG(path,files,names,default,totSeq,ofile,toPlot=F)
#  作用: 根据.cnt的profie的ATCG频率，计算chisq
#  Usage: 
#  mat=chisqATCG(path='c:/',files=c('3utr.cnt','cds.cnt','intron.cnt'),names=c('3UTR','CDS','intron'),totSeq=c(1000,500,300),default='3UTR',ofile='chi',toPlot=F)
#############################################################################
.chi<-function(par) {
 return(chisq.test(rbind(par[1:4],par[5:8]))$statistic);
}

chisqATCG<-function(path,files,names,totSeq,default,ofile,toPlot=F) {
   require('RColorBrewer',quietly =T)
  setwd(path)
  idx=which(names==default)
  stopifnot(length(idx)==1)
  defP=read.table(files[idx],header=T,sep="\t",quote="")
  defP=defP[,-1]
  idxs=c(1:length(files))[-idx]
  for (i in idxs) {
    dat=read.table(files[i],header=T,sep="\t",quote="")
    dat=dat[,-1]
    dat=cbind(dat*totSeq[i],defP*totSeq[idx])
    chiDat=apply(dat,1,.chi)
    if (i==idxs[1]) {
      chis=chiDat
    } else {
      chis=cbind(chis,chiDat)
    }
  }
  if (!is.vector(chis)) {
    colnames(chis)=names[-idx]
  }
  write.table(chis,file=ofile,col.names=T,row.names=F,quote=F,sep='\t');
  if (toPlot) {
    ncolor=length(files)
    if (ncolor<3) {
      ncolor=3
    }
    cols=brewer.pal(ncolor, "Set1")
    if (is.vector(chis)) {
      plot(chis,type='l',col=cols[1])
    } else {
      plot(chis[,1],type='l',col=cols[1])
      for (i in 2:ncol(chis)) {
        lines(chis[,i],col=cols[i])
      }
    }
  }
}

#############################################################################
#  mat=shuffleNames<-function(group1,group2,r)
#  作用: 排列组合,group1/2要一样的列数,r可以不选一半,如6选2,4选2
#  返回: 矩阵,每行如:"X1" "X2" "Y1" "Y2" "X3" "X4" "Y3" "Y4",前一半是shuffle后的半边,后一半是另半另
#  Usage: 
#  a=shuffleNames(paste('X',1:6,sep=''),paste('Y',1:6,sep=''),2) #选取的个数可以不一
#  a=shuffleNames(paste('s1r',1:4,sep=''),paste('s2r',1:4,sep=''),2)
#############################################################################
shuffleNames<-function(group1,group2,r) {
  stopifnot(length(group1)==length(group2))
  n=length(group1)
  shf1=combinations(v=group1,r=r,n=n)
  shf2=combinations(v=group2,r=r,n=n)
  shf11=matrix(rep(shf1,each=nrow(shf2)),ncol=ncol(shf1),byrow=F) 
  shf22=shf2
  for (i in 2:nrow(shf2)) {
    shf22=rbind(shf22,shf2)
  }
  shf12=cbind(shf11,shf22) #搞定一边
  #另一边是group1+group2-shf12
  shf1234=matrix(rep(c(group1,group2),nrow(shf12)),nrow=nrow(shf12),byrow=T)
  shf34=shf1234[1,][!(shf1234[1,] %in% shf12[1,])]
  for (i in 2:nrow(shf12)) {
    shf34=rbind(shf34, shf1234[i,][!(shf1234[i,] %in% shf12[i,])] )
  }
  #shf1234中会有重复的,比如x1x2y1y2是左边,另一行在右边,这是不同行,要去除
  shf1234=cbind(shf12,shf34)
  marks=rep(1,nrow(shf1234))
  for (i in 1:nrow(shf1234)) {
    for (j in 2:nrow(shf1234)) {
      if ( marks[i]==1 & marks[j]==1 & sum(shf1234[i,1:n] %in% shf1234[j,(n+1):(2*n)])==n ) {
        marks[j]=0
      }
    }
  }
  #print(marks)
  return(shf1234[marks==1,])
}


#-------------------------------------------------
# About DE
#-------------------------------------------------
##子函数：从DE文件名中取出两个样本名
##要求有minpat字段来定位
##如果无法解析，则返回文件名
##DEfileName2SampleName('t_ae_pacsd24n_flt.APAgene.pac.antherXmature_pollen.minpat1_minrep1_norm0.padj0.1.list',groups)
DEfileName2SampleName<-function(filename,groups=NULL){ 
  filename=basename(filename)
  #根据提供的样本名，确定样本
  if (!is.null(groups)) {
	combine=expand.grid(groups,groups)
    combine=apply(combine,2,as.character)
	pats=paste(combine[,1],'.*',combine[,2],sep='')
    for (i in 1:length(pats)) {
	  if (length(grep(pats[i],filename))==1) {
		smps=c(combine[i,1],combine[i,2])
	    return(smps)
	  }
    }
	return(filename)
  }

  #如果不提供，则根据文件名
  #t_tm_pac_fnl_repsN_flt.gene.seed36HXseed48H_minpat0_minrep0_norm0
  if (length(grep('\\.minpat',filename))==0) {
	  dotpos=max(as.integer(unlist(gregexpr('\\.',filename))))
	  patpos=min(as.integer(unlist(gregexpr('minpat',filename))))
	  tmp=substr(filename,dotpos+1,patpos-2)
	  smps=unlist(strsplit(tmp,split='X'))
  #或者  t_tm_pac_fnl_repsN_flt.gene.seed36HXseed48H.minpat0_minrep0_norm0
  } else if (length(grep('_minpat',filename))==0) {
	  x=unlist(strsplit(filename,split='\\.'))
	  tmp=x[length(x)-1] #倒数第2个
	  smps=unlist(strsplit(tmp,split='X'))
  } else {
	  smps=filename
  }
  return(smps);
}


#根据名字，取得类型字段（适用于globle类型的）
#直接按.分割后，取第2个部分
#如 t_ae_pacsd24n_flt.anther_pollen.APAgene.pac.all.DEXSeq 中的anther_pollen
.getTypeChar<-function(afilename) {
   x=basename(afilename)
   x=unlist(strsplit(afilename,split='\\.'))
   if (length(x)==1) {
	 return(NA)
   }
   return(x[2]);
}

#文件名智能配对，返回 list(gene,pac,header)表示相应的文件名及标题（如pairwise:dry-leaf 或 global:flower）
#若配不上号，则stop
#global:比如这里的 anther_pollen t_ae_pacsd24n_flt.anther_pollen.APAgene.pac.all.DEXSeq 和 t_ae_pacsd24n_flt.anther_pollen.gene.all.DESeq2
#pairwise：t_ae_pacsd24n_flt.APAgene.pac.antherXmature_pollen_minpat1_minrep1_norm0 和 t_ae_pacsd24n_flt.gene.antherXmature_pollen_minpat0_minrep0_norm0
.matchDEfiles<-function(genefiles,pacfiles) {
	headers=c()
	pacfiles2=c()
	for (genefile in genefiles) {
	  smps=DEfileName2SampleName(genefile) #先查看pairwise的情况
	  if (length(smps)==2) {
	    i=intersect(grep(smps[1],pacfiles),grep(smps[2],pacfiles)) #查找对应样本的pacFile
		if (length(i)!=1) {
		  stop('more than 1 PAC file for ',genefile,'(',smps,')','\n')
		}
	    pacfiles2=c(pacfiles2,pacfiles[i])
	    headers=c(headers,paste(smps[1],smps[2],sep='-'))
	  } else { #查看global的情况
        mark=.getTypeChar(genefile)
		if (is.na(mark)) {
		  stop('no PAC file for ',genefile,'\n');
		}
        have=0;
		for (pacfile in pacfiles) {
		  mark2=.getTypeChar(pacfile)
		  if (mark==mark2) {
			 have=1
			 break;
		  }
		}
		if (!have) {
		  stop('no PAC file for ',genefile,'\n');
		}
	    pacfiles2=c(pacfiles2,pacfile)
		headers=c(headers,mark);
	  }
	}
   return(list(gene=genefiles,pac=pacfiles2,header=headers));
}

#############################################################################
#  getSizeFactor(matrix,'EDGER'/'DESEQ'/'TPM')
#  useage: 计算标准化因子，默认用DESEQ的 (注意最后标准化方法是将 PAC/factor，不是相乘)
#  1) 取得标准化因子，允许使用edgeR或DEseq的方法
#     sf=getSizeFactor(matrix,'EDGER');
#  2) 或输出是libSize
#     libSize=getSizeFactor(matrix,'EDGER',oLibsize=T);
#  2015/8/24 增加 x%的TPM标准化，method=TPMx，thd=0.95
#     libSize=getSizeFactor(matrix,'TPMx',oLibsize=T,thd=0.95);
#############################################################################
getSizeFactor<-function(dat,method='DESEQ',oLibsize=FALSE,thd=0.95){
  method=toupper(method)
  if (is.data.frame(dat)) dat=as.matrix(dat)
  if (method!='EDGER' && method!='DESEQ' && method!='TPM' && method!='TPMX') {
    method='DESEQ'
  }
  stopifnot (method=='EDGER' || method=='DESEQ' || method=='TPM' || method=='TPMX')
  if (method=='EDGER') {
	suppressPackageStartupMessages( library( "edgeR" ) )
    f <- calcNormFactors(dat)
    #f <- f/exp(mean(log(f)))
  } else if (method=='DESEQ') {
	suppressPackageStartupMessages( library( "DESeq" ) )
    f=sizeFactors(estimateSizeFactors(newCountDataSet(dat,1:ncol(dat)))) #group参数是随便给的,只要列数相同
  } else if (method=='TPM') {
    f=colSums(dat)/1000000
  }else if (method=='TPMX') {
	rowsums=rowSums(dat);
	uprow=quantile(rowsums,probs=thd)
	rest=dat[rowsums<uprow,]
	libs=colSums(rest);
	minlib=min(libs)
	f=libs/minlib
  }
  if (oLibsize){
    f=as.integer(colSums(dat)/f) #这个好像是/f而不是*f
  }
  return(f)
}

#Mark:
#First, the standard use of calcNormFactors() is described in the user guide in the "Normalization" section.  Briefly it goes something like this ...
#f <- calcNormFactors(D)  # D is table of counts
#d <- DGEList(counts = D, group = g, lib.size = colSums(D) * f)

#############################################################################
#  normBySizeFactor(matrix,sizeFactor)
#  useage: 
#  1) 根据标准化因子，对矩阵数据标准化
#     sf=getSizeFactor(matrix,'EDGER'); dat.norm=normBySizeFactor(dat,sf)
#############################################################################
normBySizeFactor<-function(dat,sizeFactor) {
  tmp=matrix(rep(sizeFactor,nrow(dat)),nrow=nrow(dat),byrow=T)
  return(round(dat/tmp))
}


## 供doDE内部调用
.ttest<-function(par,grp1,grp2) { 
  if (length(grp1)<2 | length(grp2)<2) { #只有1列,则输出1,1
    return (c(1,1))
  }
  if (sd(par[grp1])==0 & sd(par[grp2])==0) { #防止t.test出错
    return (c(1,1))
  }
  t=t.test(par[grp1],par[grp2]);
  return (c(t$statistic,t$p.value));
}

#############################################################################
#  doDE(dat,method,doNorm=T,grpInd1,grpInd2,grpLbl1,grpLbl2,sizeFactor=NULL,verbose=T)
#  作用: 用不同方法进行DE检测，输出矩阵的最后3列为pval,padj,logFC或tstat
#  dat: 输出数据，只要包含需要的列即可
#  method: edgeR, DESeq, TTest
#  grpInd1,grpInd2: 在dat中的样本下标,如(1,3),(2,4)
#  grpLbl1,grpLbl2: 用于标识样本1和样本2,如s1,s2
#  doNorm,sizeFactor: 若doNorm,则进行标准化,若有提供sizeFactor,则直接用sizeFactor,否则根据方法估算sizeFactor
#  verbose: 是否输出信息
#  输出: dat+dat.norm(如果需要标准化)+pval,padj,logFC(对于egdeR或DEseq)/tstat(对于TTest)
#  说明: 
#  1) 如果只含有1列,则不能进行ttest
#  2) 输出行的顺序与原输入dat一致
#  Usage:
#  1) dat.DE=doDE(dat,'edgeR',doNorm=T,grpInd1=1:4,grpInd2=5:8,grpLbl1='wt',grpLbl2='oxt6')
#  2) dat.DE=doDE(dat,'edgeR',doNorm=T,sizeFactor=c(1,0.9..),grpInd1=1:4,grpInd2=5:8,grpLbl1='wt',grpLbl2='oxt6')
#  如果针对同一标准化数据,计算多种情况下的DE值,可以
#  sf=getSizeFactor(dat[,c(grpInd1,grpInd2)],'DESeq');  dat.norm=normBySizeFactor(dat[,c(grpInd1,grpInd2)],sf)
#  dat.norm.DE=doDE(dat.norm,'edgeR',doNorm=F,grpInd1=1:4,grpInd2=5:8,grpLbl1='wt',grpLbl2='oxt6'); 再根据id行merge还原原数据

#  2015/3/15 增加DESeq2
#############################################################################
doDE<-function(dat,method,grpInd1,grpInd2,grpLbl1,grpLbl2,doNorm=T,sizeFactor=NULL,verbose=T) {
  	suppressPackageStartupMessages( library( "edgeR" ) )
	suppressPackageStartupMessages( library( "DESeq" ) )
	suppressPackageStartupMessages( library( "DESeq2" ) )

  method=toupper(method)
  stopifnot (method=='EDGER' | method=='DESEQ' | method=='TTEST' | method=='DESEQ2')
 # if (method=='TTEST' & (length(grpInd1)<2 | length(grpInd2)<2)) {
 #   stop ("method=TTEST, but grpInd1 or grpInd2 <=1 columns")
 # }
  ret=cbind(tmpID=1:nrow(dat),dat)  #返回结果包含输入的全部
  dat=dat[,c(grpInd1,grpInd2)] #只出需要的列
  grpNames=colnames(dat)
  group=c(grpInd1,grpInd2) #比如 (s1,s1,s2,s2)
  group[grpInd1]=grpLbl1
  group[grpInd2]=grpLbl2

  if (doNorm) { #标准化
    if (verbose) {
      cat('normBySizeFactor()','\n')
    }
    if (is.null(sizeFactor)) {
      sizeFactor=getSizeFactor(dat,method)
      if (verbose) {
        cat('getSizeFactor(): sizeFactor=',sizeFactor,'\n')
      }
    }
    dat=normBySizeFactor(dat,sizeFactor)
    colnames(dat)=paste(grpNames,'_norm',sep='')
    ret=cbind(ret,dat) #也包含标准化后的数值
  } 
  rownames(dat)=1:nrow(dat)

  if (method=='DESEQ') { # (pval,padj,logFC)
     if (verbose) {
       cat('method=DESeq','\n')
     }
     dat.ds <- newCountDataSet( dat, group)
     sizeFactors(dat.ds)=rep(1,ncol(dat))
     if (length(grpInd1)==1 | length(grpInd2)==1) {
       #dat.ds <- estimateDispersions( dat.ds,blind=T)
	dat.ds <- estimateDispersions( dat.ds, method = 'blind', fitType='local')
     } else {
       dat.ds <- estimateDispersions( dat.ds,fitType='local') #2012-07-20 要加上local，否则可能有错
     }
     res <- nbinomTest( dat.ds,grpLbl1,grpLbl2)
     res=res[order(res$padj,res$pval),c('id','pval','padj','log2FoldChange')]
     colnames(res)=c('id','pval','padj','logFC')

  } else if (method=='EDGER') { # (pval,padj,logFC)
     edger.d <- DGEList(counts =dat, group=group,lib.size=colSums(dat))
     edger.d <- estimateCommonDisp(edger.d) 
     if (verbose) {
       cat ('method=EdegR. common.dispersion:',edger.d$common.dispersion,'\n')
     }
     edger.com <- exactTest(edger.d)
     if ('p.value' %in% colnames(edger.com$table)) {
       pval=as.numeric(edger.com$table$p.value)
     } else if ('PValue' %in% colnames(edger.com$table)) {
       pval=as.numeric(edger.com$table$PValue)
     } else {
       stop("no p.value or PValue in Edger.com table")
     }
     padj=p.adjust(pval, method = "BH")
     logFC=as.numeric(edger.com$table$logFC)
     res=cbind('id'=as.integer(rownames(edger.com$table)),pval,padj,logFC)
     #print(head(edger.com$table))
     #print(head(res))

  } else if (method=='TTEST') { # (pval,padj,tstat)
     if (verbose) {
       cat('method=TTEST','\n')
     }
    i1=1:length(grpInd1)
    i2=(length(grpInd1)+1):(length(grpInd1)+length(grpInd2))
    t=apply(dat,1,.ttest,i1,i2)
    res=t(t)
    pval=res[,2]
    tstat=res[,1]
    padj=p.adjust(pval, method = "BH")
    res=cbind(id=as.integer(rownames(dat)),pval,padj,tstat)
  } else if (method=='DESEQ2') { # 2015/3/15 (pval,padj,logFC)
     if (verbose) {
       cat('method=DESeq2','\n')
     }
	 sampleData <- data.frame(condition=group)
	 design <- formula( ~ condition )
     dat.ds <- DESeqDataSetFromMatrix( dat, sampleData, design)
     sizeFactors(dat.ds)=rep(1,ncol(dat))
     dat.ds=DESeq(dat.ds)
	 res=as.matrix(results(dat.ds))
     res <- cbind('id'=1:nrow(res),res)
	 res=apply(res[,c('id','pvalue','padj','log2FoldChange')],2,as.numeric)
	 res=as.data.frame(res);
     #res=res[order(res$padj,res$pval),]
     colnames(res)=c('id','pval','padj','logFC')
  } 
  ret=merge(ret,res,by.x='tmpID',by.y='id')
  ret=ret[order(ret$tmpID),]
  ret=ret[,-which(colnames(ret)=='tmpID')]
  return(ret)
}


#############################################################################
#  oret:DoDEs(path,group1,group2,lbl1,lbl2,doNorm=1,file,filecols,minpat,minrep,osurfix,ocols)
#  时间：2012-07-20
#  作用: 输入PAC/Gene文件，输出用不同方法的DE结果。调用DoDE函数。返回oret，与输出的ofile一样。
#  Usage: 
#  path='c:/' #文件所在路径 
#  lbl1='wt'; lbl2='oxt'; #要比对的2个样本标签（用于输出的列标题）
#  group1=paste('wt',1:3,sep='') #样本的列名
#  group2=paste('oxt',1:3,sep='')
#  file='t_4_pac' #输入文件及文件的列名,原文件无标题
#  filecols=c('gff_id','chr','strand','ftr','ftr_start','ftr_end','transcript','gene','gene_type','ftrs')
#  filecols=c(filecols,'trspt_cnt','coord','tot_tagnum','wt1','wt2','wt3','oxt1','oxt2','oxt3','g1','g2')
#  filecols=c(filecols,'g3','gm1','gm2','gm3','UPA_start','UPA_end','tot_PAnum','tot_ftrs','ref_coord')
#  filecols=c('ref_tagnum','anti_gff_id','anti_strand','anti_ftr','anti_ftr_start','anti_ftr_end','anti_transcript','anti_gene','anti_gene_type')
#  ocols=c('transcript','gene_type','coord','gff_id','ftr','anti_ftr','UPA_start','UPA_end','ref_tagnum') #需要的输出列
#  minpat=2 #用于过滤数据，groupx所在的列要>=minpat
#  minrep=2 #用于过滤数据，满足>=minpat的列数要>=minrep
#  doNorm=1 #是否标准化
#  sizefactor=NULL
#  #输出
#  osurfix=paste('minpat',minpat,'_minrep',minrep,'_norm',doNorm,sep='') #输出文件后缀 .osurfix
#  DoDEs(path,group1,group2,lbl1,lbl2,doNorm,sizefactor,file,filecols,minpat,minrep,osurfix,ocols);
#############################################################################
#对于已经标准化后的常规矩阵数据的处理！
##输出标准化结果：无header，至少有1列是不在group中的（比如这里的row.names=T作为一列）
#write.table(dn,file="pnas_expression.norm",row.names=T,col.names=F,quote=F,sep="\t")
#path='F:/script_out_2/diffexpr/' #文件所在路径 
#lbl1='Ctrl'; lbl2='Treat'; #要比对的2个样本标签（用于输出的列标题）
#group1=paste('Ctrl',1:4,sep='') #样本的列名
#group2=paste('Treat',1:3,sep='')
#ocols=c('gene')
#file='pnas_expression.norm' #输入文件及文件的列名
#filecols=c('gene',group1,group2)
#minpat=0 #用于过滤数据，groupx所在的列要>=minpat (仅对grp1和grp2取后的数据有效)
#minrep=0 #用于过滤数据，满足>=minpat的列数要>=minrep
#doNorm=0 #是否标准化
#sizefactor=NULL
#osurfix='DE'
#skip=0 skip N lines of file
#DoDEs(path,group1,group2,lbl1,lbl2,doNorm,sizefactor,file,filecols,minpat,minrep,osurfix,ocols);

#2015/3/15 只保留DESeq和DESeq2的结果！
#############################################################################
DoDEs<-function(path,group1,group2,lbl1,lbl2,doNorm=1,sizefactor=NULL,file,filecols,minpat=0,minrep=0,osurfix,ocols,skip=0){

  setwd(path)
  ##输入及参数设置
  grpInd1=1:length(group1)
  grpInd2=(length(group1)+1):(length(group1)+length(group2))
    
  #原始数据
  pac=read.table(file,skip=skip,sep="\t",quote="")
  colnames(pac)=filecols
  pac=cbind(id=seq(1,nrow(pac)),pac)
  pac.dat=as.matrix(pac[,c(group1,group2)])
  rownames(pac.dat)=pac$id
  
  ofile=paste(file,osurfix,sep='.')
  cat('ofile:',ofile,'\n')
  cat('raw row number:',nrow(pac.dat),'\n')
  
  #过滤
  pac.filter=pac.dat
  if (minpat==0) {
	minpat=1;
  }
  if (minrep==0) {
	minrep=1;
  }
  if (minpat>0 && minrep>0) {
    pac.filter=pac.dat[rowSums(pac.dat>=minpat)>=minrep,] 
  }
  cat('filtered row number:',nrow(pac.filter),'\n')

  #-------------------------------------------------
  # 标准化
  #-------------------------------------------------
  pac.filter.norm=pac.filter
  colnames(pac.filter.norm)=paste(colnames(pac.filter),'_norm',sep='')
  if (doNorm) {
  if (is.null(sizefactor)) { #如果有提供标准化因子
  sf=getSizeFactor(pac.filter,'DESEQ');
  } else {
  sf=sizefactor
  }
  pac.filter.norm=normBySizeFactor(pac.filter,sf)
  colnames(pac.filter.norm)=paste(colnames(pac.filter.norm),'_norm',sep='')
  cat ('size factor:',sf,'\n')
  }
  
  #结果数据
  ret=cbind(id=as.integer(rownames(pac.filter)),pac.filter,pac.filter.norm)
  
  #-------------------------------------------------
  # DESeq (DESeq_pval,DESeq_padj)
  #-------------------------------------------------
  ret.deseq=doDE(pac.filter.norm,'DESEQ',doNorm=F,grpInd1=grpInd1,grpInd2=grpInd2,grpLbl1=lbl1,grpLbl2=lbl2)
  ret.deseq=ret.deseq[,c('pval','padj','logFC')]
  colnames(ret.deseq)=c('DESeq_pval','DESeq_padj','DESeq_logFC')
  ret=cbind(ret,ret.deseq)

  #-------------------------------------------------
  # T-Test (t_stat, t_pval, t_padj)
  #-------------------------------------------------
  if (0) { #不要t-test
  ret.t=doDE(pac.filter.norm,'TTEST',doNorm=F,grpInd1=grpInd1,grpInd2=grpInd2,grpLbl1=lbl1,grpLbl2=lbl2)
  ret.t=ret.t[,c('pval','padj','tstat')]
  colnames(ret.t)=c('t_pval','t_padj','t_stat')
  ret=cbind(ret,ret.t)
  }

  #-------------------------------------------------
  # EdgeR common (EdgeR_com_pval, EdgeR_com_padj)
  #-------------------------------------------------
  if (0) {
  ret.edger=doDE(pac.filter.norm,'EDGER',doNorm=F,grpInd1=grpInd1,grpInd2=grpInd2,grpLbl1=lbl1,grpLbl2=lbl2)
  ret.edger=ret.edger[,c('pval','padj','logFC')]
  colnames(ret.edger)=c('edger_com_pval','edger_com_padj','edger_com_logFC')
  ret=cbind(ret,ret.edger)
 }

  #-------------------------------------------------
  # DESeq2  (DESeq2_pval, DESeq2_padj)
  #-------------------------------------------------
  ret.deseq2=doDE(pac.filter.norm,'DESEQ2',doNorm=F,grpInd1=grpInd1,grpInd2=grpInd2,grpLbl1=lbl1,grpLbl2=lbl2)
  ret.deseq2=ret.deseq2[,c('pval','padj','logFC')]
  colnames(ret.deseq2)=c('DESeq2_pval','DESeq2_padj','DESeq2_logFC')
  ret=cbind(ret,ret.deseq2)
 
  #-------------------------------------------------
  #输出
  #-------------------------------------------------
  ocols=c('id',ocols)
  oret=merge(pac[,ocols],ret,by.x='id',by.y='id')
  oret=oret[order(oret[,'DESeq_padj'],oret[,'DESeq_pval']),]
  if (doNorm==0) { #非标准化的不需要_norm列
    oret=oret[,-grep('_norm',colnames(oret))]
  }
  write.table(oret,file=ofile,col.names=T,row.names=F,quote=F,sep='\t')
  return(oret)
}

#############################################################################
#  statDEs(path,retfile,thds,tops)
#  作用: 统计DoDEs的结果,计数pval和padj在一定threshold下的个数; 输出不同情况重合数；输出不同条件组合下的DE行
#  Usage: 函数内部调用 countDERet()，ovpDERet()，topRet()
#  path='c:/' #文件路径 
#  retfile='t_4_pac.minpat2_minrep2_norm0' #DoDEs的输出文件
#  thds=c(0.1,0.05,0.01)  #用于统计的thd
#  tops=seq(100,1000,100) #用于计算从100-1000,每隔100的top个数下的不同条件的重合情况
#  statDEs(path,retfile,thds,tops) #调用
#  输出如下文件：               
#  t_4_pac.minpat2_minrep2_norm0.thd个数                                   
#  t_4_pac.padj重合                                    
#  t_4_pac.pval重合          
#  t_4_pac.0.edger_com_padj0.1.TOP                     
#  t_4_pac.0.edger_com_padj0.1_AND_DESeq_padj0.1.TOP   
#  t_4_pac.1174.DESeq_pval0.1.TOP                      
#  t_4_pac.42.DESeq_padj0.1.TOP                        
#  t_4_pac.42.edger_com_padj0.1_OR_DESeq_padj0.1.TOP   
#  t_4_pac.477.DESeq_pval0.1_AND_edger_com_pval0.1.TOP 
#  t_4_pac.803.edger_com_pval0.1.TOP   
#############################################################################
statDEs<-function(path,retfile,thds=c(0.1,0.05,0.01),tops=seq(100,1000,100)){
# 每种pval,padj <0.1/0.05,0.01的个数
#type	pval<0.1	padj<0.1	pval<0.05	padj<0.05	pval<0.01	padj<0.01	min_padj
#DESeq	2428	201	1385	151	438	107	7.91633945320618e-44
#t	1141	0	495	0	135	0	0.629272515868868
ostat=paste(retfile,'.thd个数',sep='')
ret=read.table(retfile,header=T,sep="\t")
stats=countDERet(ret,pvalTxt='pval',padjTxt='padj',thds=thds)
write.table(stats,file=ostat,sep="\t",col.names=T,row.names=F,quote=F,append=T)

# 每种pval的重合数
# 输出如:  
#A-B  A(pval<0.1)  B(pval<0.1) A_in_B(pval<0.1)  A(pval<0.01)..  A_in_B(pval<0.01)  A_in_B(top100) ...  A_in_B(top1000)
#edger_com_pval-t_pval 10 1000 7 
ofile=paste(retfile,'.pval重合',sep='')
ovps=ovpDERet(ret,what='pval',thds=thds,tops=tops)
write.table(ovps,file=ofile,sep="\t",col.names=T,row.names=F,quote=F)
ofile=paste(retfile,'.padj重合',sep='')
ovps=ovpDERet(ret,what='padj',thds=thds,tops=tops)
write.table(ovps,file=ofile,sep="\t",col.names=T,row.names=F,quote=F)

# 输出top
if ('anti_ftr' %in% colnames(ret)) {
  toFilePre=retfile
  top=topRet(ret,cols=c('DESeq_pval'),thds=0.1,toFilePre=toFilePre,groupby=c('ftr','anti_ftr'),oRet=T)
  top=topRet(ret,cols=c('edger_com_pval'),thds=0.1,toFilePre=toFilePre,groupby=c('ftr','anti_ftr'),oRet=T)
  top=topRet(ret,cols=c('DESeq_pval','edger_com_pval'),thds=c(0.1,0.1),conds='AND',toFilePre=toFilePre,groupby=c('ftr','anti_ftr'),oRet=T)
  top=topRet(ret,cols=c('DESeq_padj'),thds=0.1,toFilePre=toFilePre,groupby=c('ftr','anti_ftr'),oRet=T)
  top=topRet(ret,cols=c('edger_com_padj'),thds=0.1,toFilePre=toFilePre,groupby=c('ftr','anti_ftr'),oRet=T)
  top=topRet(ret,cols=c('edger_com_padj','DESeq_padj'),thds=c(0.1,0.1),conds='AND',toFilePre=toFilePre,groupby=c('ftr','anti_ftr'),oRet=T)
  top=topRet(ret,cols=c('edger_com_padj','DESeq_padj'),thds=c(0.1,0.1),conds='OR',toFilePre=toFilePre,groupby=c('ftr','anti_ftr'),oRet=T)
  #top=topRet(ret,cols=c('t_pval'),thds=0.05,toFilePre=toFilePre,groupby=c('ftr','anti_ftr'))
  #top=topRet(ret,cols=c('edger_com_padj','DESeq_padj','t_pval'),thds=c(0.1,0.1,0.01),conds=c('OR','AND'),toFilePre=toFilePre,groupby=c('ftr','anti_ftr'))
  #top=topRet(ret,cols=c('edger_com_pval','DESeq_pval','t_pval'),thds=c(0.1,0.1,0.1),conds=c('OR','OR'),toFilePre=toFilePre,groupby=c('ftr','anti_ftr'))
  #top=topRet(ret,cols=c('edger_com_pval','DESeq_pval','t_pval'),thds=c(0.01,0.01,0.01),conds=c('OR','OR'),toFilePre=toFilePre,groupby=c('ftr','anti_ftr'))
}
}


#############################################################################
#  stat2DE(path,retfile1,retfile2,thd,ofile) 
#  作用: 比较两个DE文件,统计在thd过滤后的top重合情况，输出统计结果及重合的行
#  Usage: #
#  retfile1='t_4_pac.minpat2_minrep2_norm0'; #输入的2个文件，来自DoDEs的结果
#  retfile2='t_4_pac.minpat2_minrep2_norm1';
#  thd=0.01 #用于按条件过滤，只有2种条件：edger_com_pval<thd OR DESeq_pval<thd 和 edger_com_pval<thd OR DESeq_pval<thd
#  ofile=paste(retfile1,'__',retfile2,'.重合比较',sep='') #输出文件
#  stat2DE(path,retfile1,retfile2,thd,ofile)
#############################################################################
stat2DE<-function(path,retfile1,retfile2,thd,ofile) {

ret1=read.table(retfile1,header=T,sep="\t",quote="")
ret2=read.table(retfile2,header=T,sep="\t",quote="")

oo=c('retfile1:',retfile1,'retfile2:',retfile2,'thd:',thd,'ofile:',ofile);

#计算overlap，相同transcript，且位置相差<24
ret11=ret1[ret1$edger_com_pval<thd | ret1$DESeq_pval<thd,]
ret11=ret11[order(ret11$edger_com_pval,ret11$edger_com_pval),]
ret22=ret2[ret2$edger_com_pval<thd | ret2$DESeq_pval<thd,]
ret22=ret22[order(ret22$edger_com_pval,ret22$edger_com_pval),]
oo=c(oo,'\n*************','Condition: edger_com_pval<thd OR DESeq_pval<thd#:')
oo=c(oo,'File1:',nrow(ret11),'File2:',nrow(ret22))
mm1=merge(ret11,ret22,by.x='transcript',by.y='transcript') 
idx1=mm1$coord.x<=mm1$coord.y+24 | mm1$coord.x>=mm1$coord.y-24
ovp1=sum(idx1) 
oo=c(oo,'Overlap (same transcript & 24nt around)#:',ovp1)

ret11=ret1[ret1$edger_com_pval<thd & ret1$DESeq_pval<thd,]
ret11=ret11[order(ret11$edger_com_pval,ret11$edger_com_pval),]
ret22=ret2[ret2$edger_com_pval<thd & ret2$DESeq_pval<thd,]
ret22=ret22[order(ret22$edger_com_pval,ret22$edger_com_pval),]
oo=c(oo,'\n*************','Condition: edger_com_pval<thd AND DESeq_pval<thd#:')
oo=c(oo,'File1:',nrow(ret11),'File2:',nrow(ret22))
mm2=merge(ret11,ret22,by.x='transcript',by.y='transcript')
idx2=mm2$coord.x<=mm2$coord.y+24 | mm2$coord.x>=mm2$coord.y-24
ovp2=sum(idx2) 
oo=c(oo,'Overlap (same transcript & 24nt around)#:',ovp2)

thd=0.1
ret11=ret1[ret1$edger_com_padj<thd | ret1$DESeq_padj<thd,]
ret11=ret11[order(ret11$edger_com_padj,ret11$edger_com_padj),]
ret22=ret2[ret2$edger_com_padj<thd | ret2$DESeq_padj<thd,]
ret22=ret22[order(ret22$edger_com_padj,ret22$edger_com_padj),]
oo=c(oo,'\n*************','Condition: edger_com_padj<0.1 OR DESeq_padj<0.1#:')
oo=c(oo,'File1:',nrow(ret11),'File2:',nrow(ret22))
mm3=merge(ret11,ret22,by.x='transcript',by.y='transcript') 
idx3=mm3$coord.x<=mm3$coord.y+24 | mm3$coord.x>=mm3$coord.y-24
ovp1=sum(idx3) 
oo=c(oo,'Overlap (same transcript & 24nt around)#:',ovp1)

omtx=matrix(oo,ncol=2,byrow=T)

write.table(omtx,file=ofile,col.names=F,row.names=F,quote=F,sep="\t")
write.table('\nCondition: edger_com_pval<thd OR DESeq_pval<thd',row.names=F,col.names=F,quote=F,file=ofile,append=T)
write.table(mm1[idx1,],file=ofile,col.names=T,row.names=F,quote=F,sep="\t",append=T)
write.table('\nCondition: edger_com_pval<thd AND DESeq_pval<thd',row.names=F,col.names=F,quote=F,file=ofile,append=T)
write.table(mm2[idx2,],file=ofile,col.names=T,row.names=F,quote=F,sep="\t",append=T)
write.table('\nCondition: edger_com_padj<thd OR DESeq_padj<thd',row.names=F,col.names=F,quote=F,file=ofile,append=T)
write.table(mm3[idx3,],file=ofile,col.names=T,row.names=F,quote=F,sep="\t",append=T)
}

#############################################################################
#  relateDEPACgene(pretfile,gretfile,ofile)
#  作用: 关联 DEgene.ret和DEPAC.ret,得到每个PAC对应的gene的DE情况
#  Usage: 
#  pretfile='t_4_pac.minpat2_minrep2_norm0';
#  gretfile='t_4_pac.minpat2_minrep2_norm1';
#  ofile=paste(pretfile,'.','linkGENE',sep='')
#  relateDEPACgene(pretfile,gretfile,ofile);
#############################################################################
relateDEPACgene<-function(pretfile,gretfile,ofile){

gret=read.table(gretfile,header=T,sep="\t",quote="")
pret=read.table(pretfile,header=T,sep="\t",quote="")

stopifnot ('gene' %in% colnames(gretfile));
stopifnot ('transcript' %in% colnames(pretfile));

colnames(gret)=paste(colnames(gret),'g',sep='_')
#添加gene列,将pret中的非.x或.0-9的transcript
gene=pret$transcript
gene=substr(gene,1,as.integer(gregexpr('\\.',gene))-1)
igtidx=which(as.integer(gregexpr('\\.x',pret$transcript))==-1 & as.integer(gregexpr('\\.[0-9]',pret$transcript))==-1)
gene[igtidx]=paste(gene[igtidx],'.igt',sep='')
pret=cbind(gene,pret)

#合并gene和PAC
m=merge(x=pret,y=gret,by.x='gene',by.y='gene_g',all.x=T)

if ('edger_com_padj' %in% colnames(m)){
 m=m[order(m$edger_com_padj),]
}

#输出全部PAC及linkGene
write.table(m,file=ofile,sep="\t",col.names=T,row.names=F,quote=F)
}

#############################################################################
#  countDERet(ret,pvalTxt,padjTxt,thds)
#  作用: 统计ret结果,计数pval和padj在一定threshold下的个数
#  Usage: 
#  stats=countDERet(ret,pvalTxt='pval',padjTxt='padj',thds=c(0.1,0.05,0.01))
#############################################################################
countDERet<-function(ret,pvalTxt='pval',padjTxt='padj',thds=c(0.1,0.05,0.01)) {
  cols=colnames(ret)
  pvcols=grep(paste(pvalTxt,'$',sep=''),cols) #以pval结尾
  padjcols=grep(paste(padjTxt,'$',sep=''),cols)
  
  # 每种pval,padj <0.1/0.05,0.01的个数
  #type	pval<0.1	padj<0.1	pval<0.05	padj<0.05	pval<0.01	padj<0.01	min_padj
  #DESeq	2428	201	1385	151	438	107	7.91633945320618e-44
  #t	1141	0	495	0	135	0	0.629272515868868
  stats=matrix(gsub(paste('_',pvalTxt,sep=''),'',cols[pvcols]),ncol=1)
  colnames(stats)='type'
  for (thd in thds) {
    stats=cbind(stats,pv=as.integer(colSums((!is.na(ret[,pvcols]) & ret[,pvcols]<thd))))
    colnames(stats)[colnames(stats)=='pv']=paste('pval<',thd,sep='')
  }
  for (thd in thds) {
    stats=cbind(stats,padj=as.integer(colSums((!is.na(ret[,padjcols]) & ret[,padjcols]<thd))))
    colnames(stats)[colnames(stats)=='padj']=paste('padj<',thd,sep='')
  }
  
  #加上min(padj)
  mins=c()
  for (p in padjcols) {
    mins=c(mins,min(ret[!is.na(ret[,p]),p]))
  }
  stats=cbind(stats,'min_padj'=mins)
  return(stats)
}


#############################################################################
#  ovpDERet(ret,what=pval/padj,thds,tops)
#  作用: 统计ret结果,计算每个pval或padj<x下的各种方法的重合数
#  Usage: 
#  ovpStats=ovpDERet(ret,what='pval',thds=c(0.1,0.05,0.01),tops=seq(100,1000,100))
#############################################################################
ovpDERet<-function(ret,what='pval',thds=c(0.1,0.05,0.01),tops=seq(100,1000,100)) {
# 每种pval的重合数
# 输出如:  
#A-B  A(pval<0.1)  B(pval<0.1) A_in_B(pval<0.1)  A(pval<0.01)..  A_in_B(pval<0.01)  A_in_B(top100) ...  A_in_B(top1000)
#edger_com_pval-t_pval 10 1000 7 
#..-.. 

#输出矩阵列
AB=rep(c('A','B','A_in_B'),length(thds))
PV=rep(paste('(',what,'<',thds,')',sep=''),each=3)
TP=paste('A_in_B(top',tops,')',sep='')
oheader=c('A-B',paste(AB,PV,sep=''),TP)
#print(oheader)
cols=colnames(ret)
pcols=grep(paste(what,'$',sep=''),cols) #以pval结尾


  ovps=matrix(nrow=0,ncol=length(oheader)) 
  colnames(ovps)=oheader
  for (pv1 in pcols) {
    for (pv2 in pcols[!(pcols==pv1)]) {
      ovp=paste(cols[pv1],'-',cols[pv2])      
      if (what=='pval') { #如果是pval,用pval,pval排序
        pv11=pv1
	    pv22=pv2
        ovp=gsub("_pval","",ovp)
      } else {#如果是padj,要用padj,pval字段排序
        pv11=which(cols==gsub("_padj","_pval",cols[pv1]))
        pv22=which(cols==gsub("_padj","_pval",cols[pv2]))
        ovp=gsub("_padj","",ovp)
      }      
      for (thd in thds) {
        id1=which(!is.na(ret[,pv1]) & ret[,pv1]<thd)
        id2=which(!is.na(ret[,pv2]) & ret[,pv2]<thd)
        ovp=c(ovp,length(id1),length(id2),sum(id1 %in% id2))
      } #thd
      for (top in tops) {
        order1=ret[,'id'][order(ret[,c(pv1,pv11)])]
        id1=order1[1:top]
        order2=ret[,'id'][order(ret[,c(pv2,pv22)])]
        id2=order2[1:top]
        ovp=c(ovp,sum(id1 %in% id2))
      } #top
      ovps=rbind(ovps,ovp)
    }#pv2
  }#pv1
  colnames(ovps)=oheader
  return(ovps)
} 

#############################################################################
#  topRet(ret,cols,thds,conds,groupby,toFilePre,oRet=F)
#  作用: 按条件过滤ret中的top,且统计groupby
#  Usage: 
#  1) 输出top且统计,到文件
#  top=topRet(ret,cols=c('edger_com_padj','DESeq_padj','t_pval'),thds=c(0.1,0.1,0.01),conds=c('OR','AND'),groupby=c('ftr','anti_ftr'),toFilePre='xxfile')
#  2) 只输出top到R中
#  top=topRet(ret,cols=c('edger_com_padj','DESeq_padj','t_pval'),thds=c(0.1,0.1,0.01),conds=c('OR','AND'))
#  3) 输出,但不统计
#  top=topRet(ret,cols=c('edger_com_padj','DESeq_padj','t_pval'),thds=c(0.1,0.1,0.01),conds=c('OR','AND'),toFilePre='xxfile')
#  4) 输出统计,但不输出内容
#  top=topRet(ret,cols=c('edger_com_padj','DESeq_padj','t_pval'),thds=c(0.1,0.1,0.01),conds=c('OR','AND'),toFilePre='xxfile',oRet=F)
#############################################################################
topRet<-function(ret,cols,thds,conds=NULL,groupby=NULL,toFilePre=NULL,oRet=F) {

  stopifnot(length(cols)==length(thds))
  stopifnot(length(conds)==length(cols)-1)

  if (!is.null(conds)) {
   conds=toupper(conds)
  }  
  if (!is.null(groupby)) {
    if (sum(groupby %in% colnames(ret))!=length(groupby)) {
      stop('groupby not in colnames(ret)')
    }
  }
  if (sum(cols %in% colnames(ret))!=length(cols)) {
    stop('cols not in colnames(ret)')
  }
  #根据条件,计算合集的下标
  #相当于:idx=which(ret$edger_com_padj<thd | ret$DESeq_padj<thd & ret$t_pval<tThd)
  idx=which(ret[,cols[1]]<thds[1])
  txt=paste(cols[1],thds[1],sep='')
  if (length(cols)>1) {
    for (i in 2:length(cols)) {
      idx2=which(ret[,cols[i]]<thds[i])
      txt=paste(txt,conds[i-1],paste(cols[i],thds[i],sep=''),sep='_')
      if(conds[i-1]=='AND') {
        idx=intersect(idx,idx2)
      } else {
        idx=union(idx,idx2)
      }
    }
  }

n=length(idx)
ret1=ret[idx,]
 if (!is.null(toFilePre)) {
  ofile=paste(toFilePre,'.',n,'.',txt,'.TOP',sep='')
  write('',file=ofile,append=F)
 }
if (n==0) {
  return(ret1)
}

#统计groupby的分组情况
if (!is.null(groupby)) {
  ret1=cbind(aggreID=1:nrow(ret1),ret1)
  l=list()
  for (grpi in groupby) { #相当于形成 l=list(sense=ret1$ftr,anti=ret1$anti_ftr)
    l[[grpi]]=ret1[,grpi]
    if (!is.null(toFilePre)) {
      write.table(paste('*** group by',grpi),file=ofile,append=T,quote=F,row.names=F,col.names=F,sep='\t')
      write.table(aggregate(ret1[,c('aggreID')],list(ret1[,grpi]),length),quote=F,row.names=F,col.names=F,file=ofile,append=T,sep="\t")
      write('\n',file=ofile,append=T)
    }
  }
  if (!is.null(toFilePre)) {
    write.table(paste('*** group by',toString(groupby,sep=',')),file=ofile,append=T,quote=F,row.names=F,col.names=F,sep='\t')
    write.table(aggregate(ret1[,c('aggreID')], l,length),file=ofile,append=T,quote=F,row.names=F,col.names=F,sep='\t')
    write('\n',file=ofile,append=T)
  }
  ret1=ret1[,-1]
}
#排序: 不知道怎么在列名未知的情况下按矩阵的多个列排序,只能选第1列了.
 if (!is.null(groupby)) {
   ret1=ret1[order(ret1[,groupby[1]],ret1[,cols[1]]),]
 } else {
   ret1=ret1[order(ret1[,cols[1]]),]
 }
 if (!is.null(toFilePre) & oRet) {
  write('\n',file=ofile,append=T)
  write.table(ret1,file=ofile,sep="\t",col.names=T,row.names=F,quote=F,append=T)
 #print(nrow(ret1))
 #print(ofile)
 }

 return(ret1)
}

#############################################################################
#  doDEX(path,group1,group2,lbl1,lbl2,geneIDCol,exonIDCol,doNorm=1,sizefactor=NULL,file,filecols,minpat=0,minrep=0,osurfix,ocols,skip=0)
#  2015/3/13
#  作用: 用DEXSEQ进行DE检测，输出矩阵的最后2列为pval,padj
#  path='F:/script_out_2/diffexpr/' #文件所在路径 
#  group1=paste('Ctrl',1:4,sep='') #样本的列名
#  group2=paste('Treat',1:3,sep='')
#  lbl1='Ctrl'; lbl2='Treat'; #要比对的2个样本标签（DXD的condition列标题）
#  geneIDCol,exonIDCol: 用于标记gene列和exonID列
#  doNorm,sizeFactor: 若doNorm,则进行标准化,若有提供sizeFactor,则直接用sizeFactor,否则根据方法估算sizeFactor
#  file='pnas_expression.norm' #输入文件及文件的列名
#  filecols=c('gene',geneIDCol,exonIDCol,group1,group2) 包含需要的列即可(geneIDCol,exonIDCol,grpInd1/2)
#  minpat=0 #用于过滤数据，groupx所在的列要>=minpat (仅对grp1和grp2取后的数据有效)
#  minrep=0 #用于过滤数据，满足>=minpat的列数要>=minrep
#  osurfix='DE'
#  ocols=c('chr','strand','ftr') 额外输出的列
#  skip=0 skip N lines of file
#  输出: file=dat+dat.norm(如果需要标准化)+pval,padj
#  说明: 
#  1) 如果只含有1列,可能会disper时出错
#  2) 对于不包含多个exon的gene，结果是NA
#  Usage:
#  dxRet=DoDEX(path=path,group1=group1,group2=group2,lbl1=lbl1,lbl2=lbl2,geneIDCol=geneIDCol,exonIDCol=exonIDCol,doNorm=doNorm,sizefactor=NULL,file=file,filecols=filecols,minpat=minpat,minrep=minrep,osurfix=osurfix,ocols=ocols);
#  输出文件：
#       gene coord  chr strand    ftr dryseed1 dryseed2 seed24H1 seed24H2      pval padj
#1 AT1G01020  6858 Chr1      -   3UTR        0        2       10       28 0.6198408    1
#2 AT1G01020  7077 Chr1      - intron        0        0       14        0 0.6208440    1
#############################################################################
DoDEX<-function(path,group1,group2,lbl1,lbl2,geneIDCol,exonIDCol,doNorm=1,sizefactor=NULL,file,filecols,minpat=0,minrep=0,osurfix,ocols,skip=0,verbose=T){
  suppressPackageStartupMessages( library( "DEXSeq" ) )

  if (is.null(geneIDCol) | is.null(exonIDCol)) {
	stop("doDEX: no geneIDCol or exonIDCol")
  }

 if (verbose) {
   cat('method=DEXSEQ','\n')
 }

  setwd(path)
    
  #原始数据
  pac=read.table(file,skip=skip,sep="\t",quote="")
  colnames(pac)=filecols
  pac.dat=pac[,c(geneIDCol,exonIDCol,group1,group2)]
  
  grpInd1=match(group1,colnames(pac.dat))
  grpInd2=match(group2,colnames(pac.dat))

  ofile=paste(file,osurfix,sep='.')
  if (verbose) {
    cat('ofile:',ofile,'\n')
    cat('raw row number:',nrow(pac.dat),'\n')
  }
  
  #过滤
  if (minpat==0) {
	minpat=1;
  }
  if (minrep==0) {
	minrep=1;
  }
  if (minpat>0 && minrep>0) {
    pac.dat=pac.dat[rowSums(pac.dat[,c(group1,group2)]>=minpat)>=minrep,] 
    if (verbose) {
	  cat('filtered row number:',nrow(pac.dat),'(minpat=',minpat,', minrep=',minrep,')\n')
    }
  }

  cntdat=pac.dat[,c(grpInd1,grpInd2)] #只出需要的列
  grpNames=colnames(cntdat)
  group=c(grpInd1,grpInd2) #比如 (s1,s1,s2,s2)
  group[1:length(grpInd1)]=lbl1
  group[(length(grpInd1)+1):ncol(cntdat)]=lbl2

  if (doNorm) { #标准化
    if (verbose) {
      cat('normBySizeFactor()','\n')
    }
    if (is.null(sizeFactor)) {
      sizeFactor=getSizeFactor(cntdat,'DESEQ')
      if (verbose) {
        cat('getSizeFactor(): sizeFactor=',sizeFactor,'\n')
      }
    }
    cntdat=normBySizeFactor(cntdat,sizeFactor)
    colnames(cntdat)=paste(grpNames,'_norm',sep='')
    pac.dat=cbind(pac.dat,cntdat) #pac.dat 也包含标准化后的数值
  } 
  rownames(cntdat)=1:nrow(cntdat)

    sampleData <- data.frame(condition=group )
	design <- formula( ~ sample + exon + condition:exon )
	groupID <- pac.dat[,geneIDCol]
    featureID=as.character(pac.dat[,exonIDCol]);
	#print(head(cbind(groupID,featureID)));
	dxd= DEXSeqDataSet( cntdat, sampleData, design,featureID, groupID )
	sizeFactors(dxd)=rep(1,ncol(cntdat))
    #print(colData(dxd))
	dxd = estimateDispersions( dxd )
	#plotDispEsts( dxd )
	dxd = testForDEU( dxd )
	dxr1 = DEXSeqResults( dxd )
	#plotMA( dxr1, cex=0.8 )

	## DataFrame with 498 rows and 13 columns
	## groupID featureID exonBaseMean dispersion stat
	## <character> <character> <numeric> <numeric> <numeric>
	## FBgn0000256:E001 FBgn0000256 E001 58 0.0172 9.2e-06
	## FBgn0000256:E002 FBgn0000256 E002 103 0.0074 1.6e+00
	## pvalue padj control knockdown
	## <numeric> <numeric> <numeric> <numeric>
	## FBgn0000256:E001 1.00 1.00 11 11
	## FBgn0000256:E002 0.21 0.99 13 14

	#ret=as.data.frame(dxr1[,c('groupID','featureID','exonBaseMean(在所有样本的平均)','dispersion','stat','pvalue','padj','dryseed(某系数)','seed24H(某系数)','log2fold_seed24H_dryseed')])
	dxRet=as.data.frame(dxr1[,1:7])
    dxRet=dxRet[order(dxRet[,'padj']),c('groupID','featureID','pvalue','padj')]
    colnames(dxRet)=c(geneIDCol,exonIDCol,'pval','padj')
    ret=merge(pac.dat,dxRet,by.x=c(geneIDCol,exonIDCol),by.y=c(geneIDCol,exonIDCol))
	#加上ocols
	ret=merge(pac[,unique(c(geneIDCol,exonIDCol,ocols))],ret,by.x=c(geneIDCol,exonIDCol),by.y=c(geneIDCol,exonIDCol))
	ret=ret[order(ret[,c('padj')]),]
    write.table(ret,file=ofile,col.names=T,row.names=F,quote=F,sep='\t')
    return(ret)
}

#############################################################################
#  filterByCpmQnt(dat,filterType='cpm_quant',CPM=2,QNT=0.2,TOT=10):mtx
#  useage: dat.filter＝filterByCpmQnt(dat,'CPM_Quant',CPM=2,QNT=0.2)
#  说明:
#  1) 根据TPM(TPM>=1的列数超过CPM值)和/或Quantile过滤矩阵数据
#  filterType: cpm_quant,cpm,quant,quant_cpm,total,cpm_total,cpm_cpmtotal
#  cpm: 计算CPM列>=1的列数>=CPM值
#  quant: quantile(rowSums)>=QNT
#  total：原始的rowSums>=total
#  cpm_total: 用CPM的TOT>TOT
#  cpm_cpm: 先用cpm过滤replicate>CPM的，再用TOT截取cpmtot>TOT的
#############################################################################
filterByCpmQnt<-function(dat,filterType='cpm_quant',CPM=2,QNT=0.2,TOT=10){
  filterType=tolower(filterType)
  if (!AinB(filterType,c('cpm_quant','cpm','quant','quant_cpm','total','cpm_total','cpm_cpmtotal'))) return(dat)
  if (filterType=='cpm_quant') {
     dat.cpm <- round(cpm(as.matrix(dat)))
     use=(rowSums(dat.cpm >= 1)>=CPM)
     dat=dat[use,]
     cat('CPM#:',nrow(dat),'  filter#:',length(use)-nrow(dat),'\n')     
     rs=rowSums(dat)
     use=(rs>quantile(rs,QNT))
     dat=dat[use,]
     cat('QNT#:',nrow(dat),'  filter#:',length(rs)-sum(use),'\n')
    } else if (filterType=='quant_cpm') {
     rs=rowSums(dat)
     use=(rs>quantile(rs,QNT))
     dat=dat[use,]
     cat('QNT#:',nrow(dat),'  filter#:',length(rs)-sum(use),'\n')
     dat.cpm <- round(cpm(as.matrix(dat)))
     use=(rowSums(dat.cpm >= 1)>=CPM)
     dat=dat[use,]
     cat('CPM#:',nrow(dat),'  filter#:',length(use)-nrow(dat),'\n')
    } else if (filterType=='quant') {
     rs=rowSums(dat)
     use=(rs>quantile(rs,QNT))
     dat=dat[use,]
     cat('QNT#:',nrow(dat),'  filter#:',length(rs)-sum(use),'\n')
    } else if (filterType=='cpm') {
     dat.cpm <- round(cpm(as.matrix(dat)))
     use=(rowSums(dat.cpm >= 1)>=CPM)
     dat=dat[use,]
     cat('CPM#:',nrow(dat),'  filter#:',length(use)-nrow(dat),'\n')     
    } else if (filterType=='total') {
     use=(rowSums(dat)>=TOT)
     dat <- dat[use, ]
     cat('Total#:',nrow(dat),'  filter#:',length(use)-nrow(dat),'\n')
    }else if (filterType=='cpm_total') {
     dat.cpm <- round(cpm(as.matrix(dat)))
     use=(rowSums(dat.cpm)>=TOT)
     dat <- dat[use, ]
     cat('CPM_Total#:',nrow(dat),'  filter#:',length(use)-nrow(dat),'\n')
    }else if (filterType=='cpm_cpmtotal') {
     dat.cpm <- round(cpm(as.matrix(dat)))
     use=(rowSums(dat.cpm >= 1)>=CPM)
     dat=dat[use,]
     dat.cpm=dat.cpm[use,]
     cat('CPM_CPMTOTAL: CPM#:',nrow(dat),'  filter#:',length(use)-nrow(dat),'\n')     
     use=(rowSums(dat.cpm)>=TOT)
     dat <- dat[use, ]
     cat('CPM_CPMTOTAL:CPM_Total#:',nrow(dat),'  filter#:',length(use)-nrow(dat),'\n')
    }
  return(dat)
}

#############################################################################
# multi2two(dat,smpCols,smpLbls,mergeType='AVG',normFirst=T,normAfter=F) 
# 说明：合并每组样本到1列中，比如wt1,wt2,wt3到wt中
# 参数：
# dat: 矩阵，只包含g1Cols和g2Cols
# smpCols,smpLbls：组名及组别
# mergeType=AVG（平均）, SUM（求和），MEDIAN（中位数）
# normFirst：在进行merge之前先标准化
# normAfter：在进行merge之后再标准化
# 输出：2列，标题为g1Lbl和g2Lbl
# removeZero=T/F 若F，则不去除0行，主要用于需要保留输入输出行数一样的情况！
#############################################################################
multi2two<-function(dat,smpCols,smpLbls,mergeType='AVG',normFirst=T,normAfter=F,removeZero=T) {
  mergeType=toupper(mergeType)
  if (!AinB(mergeType,c('AVG','SUM','MEDIAN'))) stop ('mergeType not in AVG SUM MEDIAN!')
  if (!AinB(smpCols,colnames(dat))) stop ('multi2two: smpCols not in dat!')
  if (length(smpCols)!=length(smpLbls)) stop ('multi2two: smpCols not same length as smpLbls!')
  cat('multi2two: mergeType=',mergeType,'\n',sep='')
  if (normFirst) {
  f=getSizeFactor(as.matrix(dat),'DESEQ',oLibsize=F);
  cat('sizeFactor:',f,'\n')
  dat=normBySizeFactor(dat,f)
   if (removeZero) {
    dat=dat[rowSums(dat)>0,] #标准化以后可能还会出现全0行，要去除
    cat('normBefore# >0:',nrow(dat),'\n')
   }
  } 
  rns=rownames(dat) #记住行名
  smps=unique(smpLbls)
  dat.g=matrix(nrow=nrow(dat),ncol=length(smps))  
  for (i in 1:length(smps)) {
   tmp=which(smpLbls==smps[i])
   if (mergeType=='AVG') {
    if (length(tmp)>1) {
     dat.tmp=round(rowSums(dat[,smpCols[tmp]])/length(tmp))
    } else {
     dat.tmp=dat[,smpCols[tmp]]
    }
   } else if (mergeType=='SUM') {
    if (length(tmp)>1) {
     dat.tmp=rowSums(dat[,smpCols[tmp]])
    } else {
     dat.tmp=dat[,smpCols[tmp]]
    }
   } else if (mergeType=='MEDIAN') {
    if (length(tmp)==1) {
     dat.tmp=dat[,smpCols[tmp]]
    } else if (length(tmp)==2) {
     dat.tmp=round(rowSums(dat[,smpCols[tmp]])/length(tmp))
    } else {      
      dat.tmp=rowMedians(as.matrix(dat[,smpCols[tmp]]))
    }
   }
   dat.g[,i]=dat.tmp
  }
  colnames(dat.g)=smps
  rownames(dat.g)=rns
  dat=dat.g
  #再标准化
  if (normAfter) {
  f=getSizeFactor(dat,'EDGER',oLibsize=F);
  cat('sizeFactor:',f,'\n')
  dat=normBySizeFactor(dat,f)
  if (removeZero) {
    dat=dat[rowSums(dat)>0,] 
    cat('normAfter# >0:',nrow(dat),'\n')
  }
  } 
   if (removeZero) {
    dat=dat[rowSums(dat)>0,] #标准化以后可能还会出现全0行，要去除
    cat('Multi2two Final>0#:',nrow(dat),'\n')
   }
  return(dat)
}


#############################################################################
#  DE1PAC
#  useage: pac.de=DE1PAC(...)
#  说明:
#  0) 类似于 CMP_statMerge_PA.pl 但将原来的NR条件改为nDiff条件
#  1) 计算pac矩阵中的某两组的1PAC差异
#  2) 输出（仅输出DE的行）：PAC[gene,g1Cols,g2Cols,++ g1Norms,g2Norms,g1Avg,g2Avg,g1Rest,g2Rest,pvalue,padj] #rownames=PAC的行号
#  参数说明：
#  pac: matrix，含标题行([g1Cols],[g2Cols],gene,<ftr>)
#  g1Cols=c('wl_s1','wl_s2','wl_s5'); g1Lbl='WT_leaf'
#  mergeType='AVG',normFirst=T,normAfter=F: 将多列合并成1列的方式
#  filterType; CPM; QNT: CPM_Quant,CPM,Quant,Quant_CPM
#  filterIGT=T/F #是否过滤ftr=intergenic/.pm/.igt的行，若为T则要求输入的pac有ftr列
#  DE判断条件：
#  nSum=10 #PA.g1+PA.g2>=nSum
#  nDiff=5 #|PA.g1-PA.g2|>=5
#  nFold=2 #(PA.g1+1)/(PA.g2+1)>=nFold or g1/g2<=1/nFold; 为防止除0,PA会+1后再进行测试
#  pvalue=0.01 #fisher.pvalue<pvalue
#  输出：<gene,g1Cols,g2Cols,g1_norm,g2_norm,g1_merge,g2_merge,g1_rest,g2_rest,pvalue,padj>
#  使用：
#  1) 标准化以后于平均: mergeType='AVG',normFirst=T,normAfter=F
#    pac.de=DE1PAC(pac,g1Cols,g2Cols,g1Lbl=g1Lbl,g2Lbl=g2Lbl,mergeType='AVG',normFirst=T,normAfter=F,filterType=filterType,CPM=CPM,QNT=QNT,filterIGT=T,nSum=nSum,nDiff=nDiff,nFold=nFold,pvalue=pvalue)
#  2) 先将同样本的列混合为2列，再标准化: mergeType='SUM',normFirst=F,normAfter=T
#############################################################################
DE1PAC<-function(pac,g1Cols,g2Cols,g1Lbl='g1',g2Lbl='g2',mergeType='AVG',normFirst=T,normAfter=F,filterType='cpm_quant',CPM=2,QNT=0.2,TOT=10,filterIGT=T,nSum=10,nDiff=5,nFold=2,pvalue=0.01){
  
    #require("edgeR",quietly =T)
	#require("DESeq",quietly =T)

	suppressPackageStartupMessages( library( "edgeR" ) )
	suppressPackageStartupMessages( library( "DESeq" ) )

  #条件判断
  if (!AinB(g1Cols,colnames(pac))) stop (paste(g1Cols,'not all in colnames!'))
  if (!AinB(g2Cols,colnames(pac))) stop (paste(g2Cols,'not all in colnames!'))
  #if (filterType!='' & !AinB(tolower(filterType),c('cpm_quant','cpm','quant','quant_cpm','total','cpm_total'))) stop ('Error filterType!')
  if (nSum<=0 | nDiff<=0 | nFold<=0 | pvalue<0) stop("Error options: nSum nDiff nFold pvalue!")
  if (filterIGT & !AinB(c('gene','ftr'),colnames(pac))) stop ('gene,ftr not all in colnames!')
  if (!filterIGT & !AinB(c('gene'),colnames(pac))) stop ('gene not all in colnames!')
  
  if (is.null(rownames(pac))) rownames(pac)=seq(1,nrow(pac))
  cat('raw#:',nrow(pac),'\n')
  pac=pac[rowSums(pac[,c(g1Cols,g2Cols)])>0,]
  dat=pac[,c(g1Cols,g2Cols)]
  dat=dat[rowSums(dat)>0,]
  cat('raw>0#:',nrow(dat),'\n')

  #过滤
  if (filterType != '') {
    cat('filtering by',filterType,'...\n')
    dat=filterByCpmQnt(dat,filterType,CPM=CPM,QNT=QNT,TOT=TOT)
  }

  #因为最后要输出dat.norm数据，所以这里不管是否normFirst，都需要生成一份数据！
  f=getSizeFactor(as.matrix(dat),'DESEQ',oLibsize=F);
  dat.norm=normBySizeFactor(dat,f)
  
  dat.g=multi2two(dat,c(g1Cols,g2Cols),c(rep(g1Lbl,length(g1Cols)),rep(g2Lbl,length(g2Cols))),mergeType=mergeType,normFirst=normFirst,normAfter=normAfter) 

  #判断DE
  if (filterIGT) {
    gene=pac[rownames(pac) %in% rownames(dat.g),c('gene','ftr')]
    dat.g=as.data.frame(cbind(dat.g,gene))
    igtIdx=c(grep('igt',dat.g$ftr),grep('intergenic',dat.g$ftr),grep('pm',dat.g$ftr))
    if (length(igtIdx)>0) dat.g=dat.g[-igtIdx,]
    cat('nonIGT#',nrow(dat.g),'\n')
  } else {
    gene=pac[rownames(pac) %in% rownames(dat.g),c('gene')]
    dat.g=as.data.frame(cbind(dat.g,gene))
   }
  dat.g[,g1Lbl]=as.integer(dat.g[,g1Lbl])
  dat.g[,g2Lbl]=as.integer(dat.g[,g2Lbl])
  #判断DE: 只有1个gene有多个PA时，才可以判断
  #输出为：odat[g1,g2,gene,<ftr>,pvalue,padj]
  gene=unique(dat.g$gene)
  odat=matrix(nrow=0,ncol=ncol(dat.g)+3) 
  colnames(odat)=c(colnames(dat.g),paste(c(g1Lbl,g2Lbl),'_rest',sep=''),'pvalue')
  cat('Gene#',length(gene),'\n')
  cnt2pa=0; cntsum=0; cntdiff=0; cntfold=0;
  for (i in 1:length(gene)) {
    agene=gene[i]
    idx=which(dat.g$gene==agene)
    if (length(idx)<=1) next
    cnt2pa=cnt2pa+1
    #对该gene内的每个PA
    dg=dat.g[idx,]
    tot1=sum(dg[,g1Lbl])
    tot2=sum(dg[,g2Lbl])
    for (j in 1:nrow(dg)) {
      pa1=dg[j,g1Lbl]
      pa2=dg[j,g2Lbl]
      pa.sum=pa1+pa2
      if (pa.sum<nSum) next
      cntsum=cntsum+1
      if (abs(pa1-pa2)<nDiff) next
      cntdiff=cntdiff+1
      if (pa1==0) pa1=pa1+1
      if (pa2==0) pa2=pa2+1
      if (pa1/pa2<nFold & pa1/pa2>1/nFold) next
      cntfold=cntfold+1
      pa1=dg[j,g1Lbl]
      pa2=dg[j,g2Lbl]
      m<-matrix(c(pa1,pa2,tot1-pa1,tot2-pa2),2,2)
      pv=fisher.test(m)$p.value
      #if (pv>pvalue) next
      odat=rbind(odat,unlist(c(dg[j,],tot1-pa1,tot2-pa2,pv)))
      rownames(odat)[nrow(odat)]=rownames(dg[j,])
    }  
  }
  cat('Pass >=2PA#',cnt2pa,'\n')
  cat('Pass nSum PA#',cntsum,'\n')
  cat('Pass nDiff PA#',cntdiff,'\n')
  cat('Pass nFold PA#',cntfold,'\n')
  cat('PAB4Pvalue#',nrow(odat),'\n') 
  odat=odat[odat[,'pvalue']<pvalue,]
  padj=p.adjust(odat[,'pvalue'])
  odat=as.data.frame(cbind(odat,padj))
  odat=odat[order(odat$pvalue,odat$padj),]
  cat('pvalue#',nrow(odat),'\n')
  cat('padj0.05#',sum(odat[,'padj']<0.05),'\n')
  cat('padj0.1#',sum(odat[,'padj']<0.1),'\n')
  
  #输出（仅输出DE的行）：
  #PAC[gene,g1Cols,g2Cols,++ g1Norms,g2Norms,g1Avg,g2Avg,g1Rest,g2Rest,pvalue,padj]
  #rownames=PAC的行号
  opart1=pac[rownames(pac) %in% rownames(odat),c('gene',g1Cols,g2Cols)]
  opart2=dat.norm[rownames(dat.norm) %in% rownames(odat),c(g1Cols,g2Cols)]
  colnames(opart2)=paste(colnames(opart2),'_norm',sep='')
  opart3=odat[,c(g1Lbl,g2Lbl,paste(c(g1Lbl,g2Lbl),'_rest',sep=''),'pvalue','padj')]
  colnames(opart3)=c(paste(c(g1Lbl,g2Lbl),'_merge',sep=''),paste(c(g1Lbl,g2Lbl),'_rest',sep=''),'pvalue','padj')
  opart1=opart1[order(rownames(opart1)),]
  opart2=opart2[order(rownames(opart2)),]
  opart3=opart3[order(rownames(opart3)),]
  pac.de=cbind(opart1,opart2)
  pac.de=cbind(pac.de,opart3)
  pac.de$pvalue=as.numeric(pac.de$pvalue)
  pac.de$padj=as.numeric(pac.de$padj)
  pac.de=pac.de[order(pac.de$pvalue),]
  return(pac.de)
}

#############################################################################
#  DE2PAC
#  说明:
#  0) 类似于 CMP_statMerge_switchGene.pl 
#  1) 计算pac矩阵中的某一对PAC在>2组的smp中是否存在switch
#  2) 输出（仅输出switch的行,rownames列=PAC的行号）：
#  <rownames,pairnum>,++<smpCols_norm,smpCols_merge,pairnum(PAC对的序号),switchsmps (如WT|Oxt6),pvalue,padj,[SL if SL=T]>,++<gene,smpCols>
#  参数说明：
#  pac: matrix，含标题行(gene,[smpCols],gene,<coord,strand>,<ftr>)
#  smpCols=c('wl_s1','wl_s2','wl_s5','wr_s2g','wf_s81','wf_s82','wf_s83','wf_s84')
#  smpLbls=c(rep('WT_leaf',3),rep('WT_root',1),rep('WT_flower',4))
#  mergeType='AVG',normFirst=T,normAfter=F: 将多列合并成1列的方式
#  #过滤条件
#  CPM=2
#  QNT=0.2
#  filterType='CPM_Quant' #CPM,Quant,Quant_CPM,total
#  #DE条件
#  filterIGT=T #是否过滤ftr=intergenic/.pm/.igt的行
#  nOne=12 #at least one PA>=N1; 
#  nSum=48 #At least PA1 or PA2 in all stage >=N2;
#  nDiff=12 #In the two stages, |PA1-PA2|>=N3; 
#  nFold=2 #At least one stage PA1/PA2>=NF, and one other stage switch;
#  pvalue=0.01 #fisher.pvalue<pvalue
#  dist=50 #min distance between two switch PA. (PA1-PA2>=dist) if -1, then discard this option.
#  top2=T/F #与.pl中不同，如果top2=T，则一个基因只输出pvalue最小的一对，如果top2=F，则输出所以满足的对
#  SL=T/F #输出每行PAC是long/short，如L/S 数字表示哪两行是一对PAC
#  #############################################################################
DE2PAC<-function(pac,smpCols,smpLbls,filterType='cpm_quant',CPM=2,QNT=0.2,TOT=10,mergeType='AVG',normFirst=T,normAfter=F,filterIGT=T,dist=50,nOne=10,nSum=50,nDiff=5,nFold=2,pvalue=0.01,top2=T,SL=T){
  #条件判断
  allCols=colnames(pac)
  if (!AinB(smpCols,allCols)) stop (paste( toString(smpCols,sep=','),'not all in colnames!'))
  if(length(smpCols) != length(smpLbls)) stop ('smpCols not same length as smpLbls!')
 # if (filterType!='' & !AinB(tolower(filterType),c('cpm_quant','cpm','quant','quant_cpm','total','cpm_total'))) stop ('Error filterType!')
  if (nOne<=0 | nSum<=0 | nDiff<=0 | nFold<=0 | pvalue<0) stop("Error options: nOne nSum nDiff nFold pvalue!")
  if (filterIGT & !AinB(c('gene','ftr'),allCols)) stop ('gene,ftr not all in colnames!')
  if (!filterIGT & !AinB(c('gene'),allCols)) stop ('gene not all in colnames!')
  if (dist>0 & !AinB(c('coord'),allCols)) stop ('dist>0 but coord not all in colnames!')
  if (SL & !AinB(c('coord','strand'),allCols)) stop ('SL=T but coord, strand not all in colnames!')

  if (is.null(rownames(pac))) rownames(pac)=seq(1,nrow(pac))
  pac=pac[rowSums(pac[,c(smpCols)])>0,]
  dat=pac[,smpCols]
  cat('raw#:',nrow(dat),'\n')
  dat=dat[rowSums(dat)>0,]
  cat('raw>0#:',nrow(dat),'\n')

  #过滤
  if (filterType != '') {
    cat('filtering by',filterType,'...\n')
    dat=filterByCpmQnt(dat,filterType,CPM=CPM,QNT=QNT,TOT=TOT)
  }  

  #标准化得到dat.norm
  f=getSizeFactor(as.matrix(dat),'DESEQ',oLibsize=F);
  dat.norm=normBySizeFactor(dat,f)
  
  dat.g=multi2two(dat,smpCols,smpLbls,mergeType=mergeType,normFirst=normFirst,normAfter=normAfter) 
  smps=unique(smpLbls)

  #dat.g=multi2two(dat,smpCols,smpLbls,mergeType='SUM',normFirst=F,normAfter=F) 

  if (filterIGT) {
    gene=pac[rownames(pac) %in% rownames(dat.g),c('gene','ftr')]
    dat.g=as.data.frame(cbind(dat.g,gene))
    igtIdx=c(grep('igt',dat.g$ftr),grep('intergenic',dat.g$ftr),grep('pm',dat.g$ftr))
    if (length(igtIdx)>0) dat.g=dat.g[-igtIdx,]
    cat('nonIGT#',nrow(dat.g),'\n')
  } else {
    gene=pac[rownames(pac) %in% rownames(dat.g),c('gene')]
    dat.g=as.data.frame(cbind(dat.g,gene))
  }
   if (dist>0) { #coord列
    coord=pac[rownames(pac) %in% rownames(dat.g),c('coord')]
    dat.g=as.data.frame(cbind(dat.g,coord))
   }

   for (i in 1:length(smps)) {
    dat.g[,i]=as.integer(dat.g[,i])
   }

smpIdxs=1:length(smps)
names(smpIdxs)=smps
#判断DE: 只有1个gene有多个PA时，才可以判断
#输出为：odat[gene,<ftr>,<coord>,pair#,switchSmps,pvalue,rownames]
  cntOne=0; cntSum=0; cntDist=0; cntFold=0;cntGene=0;
  gene=unique(dat.g$gene)
  odat=matrix(nrow=0,ncol=ncol(dat.g)+4)  #2011/11/18 
  pairnum=1
  cat('Gene#',length(gene),'\n')
  cntapa=0;
  for (i in 1:length(gene)) {
    agene=gene[i]
   # agene='AT1G01290'
   # print(agene)
    idx=which(dat.g$gene==agene)
    if (length(idx)<=1) next #<2PA    
    cntapa=cntapa+1
    datg=dat.g[idx,]

    odat1=matrix(nrow=0,ncol=ncol(dat.g)+4)
    for (ii in 1:(length(idx)-1)) {#要比较的一对PA
      dg=datg[c(ii,ii+1),] 
      i1=1;i2=2
      #对每个smp计算PAT#
      totals=colSums(dg[,smpIdxs])
      #dist
      if (dist>0) {
        padist=abs(dg[i1,'coord']-dg[i2,'coord'])
        if (padist<dist) next
        cntDist=cntDist+1
      }
      #nOne: At least one PAi in one Smp >=nOne
      if (sum(dg[,smpIdxs]>nOne)==0) next
      cntOne=cntOne+1
      #At least PA1 or PA2 in all stage >=nSum
      if (sum(totals>nSum)==0) next
      cntSum=cntSum+1
      #1) At least one stage PA1/PA2>=nFold, and one other stage PA2/PA1>=nFold
      #2) In the two stages of 1), |PA1-PA2|>=nDiff； #要求在满足nFold的smp中，**至少**有1对PA是满足nDiff条件的
      #记录差距最大的两个smp (fold最大)
      diffs=abs(dg[i1,smpIdxs]-dg[i2,smpIdxs])
      fold1s=unlist(ifelse(dg[i1,smpIdxs]==0,dg[i1,smpIdxs]+0.1,dg[i1,smpIdxs]))/unlist(ifelse(dg[i2,smpIdxs]==0,dg[i2,smpIdxs]+0.1,dg[i2,smpIdxs]))
      fold2s=1/fold1s
      diffIdx1=smpIdxs[fold1s>=nFold & diffs>=nDiff] #diffIdx是满足fold和diff的样本列ID
      diffIdx2=smpIdxs[fold2s>=nFold & diffs>=nDiff]
      if (length(diffIdx1)==0 | length(diffIdx2)==0) next 
      cntFold=cntFold+1
      #fisher.test
      #有可能在多个smp中满足nFold和nDiff条件，则对每1种情况都作test，取pvalue最小的
      #(PA1_in_smpX,PA2_in_smpX,PA1_in_smpY,PA2_in_smpY) #只比较发生switch的这对smp
      pv=10; min1=diffIdx1[1]; min2=diffIdx2[1]
      for (di1 in diffIdx1) {
        for (di2 in diffIdx2) {
          fm=matrix(c(dg[i1,di1],dg[i2,di1],dg[i1,di2],dg[i2,di2]),nrow=2)
  	fpv=fisher.test(fm)$p.value
  	if (pv>fpv) {
  	  min1=di1; min2=di2;
  	  pv=fpv
  	}
        }
      }
     # if (pv>pvalue) next
      #保存这个gene的所有可能的PA对
      switchsmps=paste(names(smpIdxs[min(min1,min2)]),names(smpIdxs[max(min1,min2)]),sep='|')
      dg=cbind(dg,matrix(c(pairnum,pairnum,switchsmps,switchsmps,pv,pv,rownames(dg)),nrow=2)) #因为是一对，这边两行pvalue和smp是一样的！
      pairnum=pairnum+1
      odat1=rbind(odat1,dg)
      #cat(agene,rownames(dg))
      #rownames(odat1)[(nrow(odat1)-1):nrow(odat1)]=rownames(dg)
     } #~ii
    if (nrow(odat1)==0) next
    cntGene=cntGene+1
    colnames(odat1)=c(colnames(dat.g),'pairnum','switchsmps','pvalue','rownames')
    odat1[,'pvalue']=as.numeric(odat1[,'pvalue'])
    #根据是否top2，输出所以对或pvalue最小的一对
    if (!top2 | nrow(odat1)==2) {
      odat=rbind(odat,odat1)
    } else {
      mpv=1; mj=1;
      for (j in seq(1:nrow(odat1))) {
        if (j %% 2==1 & mpv>odat1[j,'pvalue']) {
	  mpv=odat1[j,'pvalue']; mj=j;
	}
      }
      odat=rbind(odat,odat1[c(mj,mj+1),])
    }
  } #~i
  cat('APA gene#',cntapa,'\n')  
  cat('passDist pair#',cntDist,'\n')
  cat('passOne pair#',cntOne,'\n')
  cat('passSum pair#',cntSum,'\n')
  cat('passFoldDiff pair#',cntFold,'\n')
  cat('beforePvalue pair#(top2=',top2,') ',nrow(odat)/2,'\n',sep='')
  cat('beforePvalue gene#(top2=',top2,') ',cntGene,'\n',sep='')
  colnames(odat)=c(colnames(dat.g),'pairnum','switchsmps','pvalue','rownames')
  odat=odat[odat[,'pvalue']<pvalue,] #应该是放这里过滤
  pvs=odat[,'pvalue'][seq(1,nrow(odat)) %% 2==0]
  padj=p.adjust(pvs)
  padj=rep(padj,each=2)
  odat=as.data.frame(cbind(odat,padj))
  odat=odat[order(odat$pvalue,odat$padj),]
  cat('pvalue pair#',nrow(odat)/2,'\n')
  cat('padj0.05 pair#',sum(odat[,'padj']<0.05)/2,'\n')
  cat('padj0.1 pair#',sum(odat[,'padj']<0.1)/2,'\n')
  
  #输出（仅输出DE的行）：
  #PAC[gene,smpCols,++ smpCols_norm,smpCols_merge,pairnum,switchsmps,pvalue,padj,<strand,coord,SL>]
  #rownames=PAC的行号
  ##... 应该增加1列ID列，标识输入的PAC的每行，这样后面的输出全部有这个ID列作关联... 因为rownames不能重复，这里的top2=F时会出现dupliate rownames的情况
  opart1=pac[rownames(pac) %in% odat$rownames,c('gene',smpCols)]
  opart2=dat.norm[rownames(dat.norm) %in% odat$rownames,c(smpCols)]
  colnames(opart2)=paste(colnames(opart2),'_norm',sep='')
  opart3=odat[,c(names(smpIdxs),'pairnum','switchsmps','pvalue','padj','rownames')]
  colnames(opart3)[1:length(smpIdxs)]=paste(names(smpIdxs),'_merge',sep='')
  opart1=opart1[order(rownames(opart1)),]
  opart2=opart2[order(rownames(opart2)),]
  opart12=cbind(opart1,opart2)
  ID=rownames(opart12)
  opart12=cbind(opart12,rownames=ID)
  pac.de=merge(opart3,opart12,by='rownames',all.x=T)
  pac.de[,'pairnum']=as.integer( pac.de[,'pairnum'])
  pac.de=pac.de[order(pac.de[,'pairnum']),]
  pac.de[,'pairnum']=rep(1:(nrow(pac.de)/2),each=2) #使pairnum是连续的
  cat('gene#',length(unique(pac.de[,'gene'])),'\n')

  if (SL) {
   opart4=pac[rownames(pac) %in% pac.de[,'rownames'],c('strand','coord','strand')]
   opart4=cbind(opart4,rownames=rownames(opart4))
   rn=pac.de[,'rownames'] #保证opart4也是按pac.de的顺序排序
   opart4=merge(pac.de[,c('pairnum','rownames')],opart4,by='rownames',all.x=T)
   #opart4=opart4[match(rn,rownames(opart4)),] ##用match！！
   colnames(opart4)[which(colnames(opart4)=='strand.1')]='SL'
   opart4=opart4[order(opart4[,'pairnum']),]
   #strand=+, coord大，则为long
   pa1=opart4[seq(1,nrow(opart4))%%2==1,]
   pa2=opart4[seq(1,nrow(opart4))%%2==0,]
   Lidx=which( (pa1[,'strand']=='+' & pa1[,'coord']>pa2[,'coord']) | (pa1[,'strand']=='-' & pa1[,'coord']<pa2[,'coord']) )
   pa1[Lidx,'SL']='L'; pa1[-Lidx,'SL']='S'
   pa2[Lidx,'SL']='S'; pa2[-Lidx,'SL']='L'
   opart4[seq(1,nrow(opart4))%%2==1,'SL']=pa1[,'SL']
   opart4[seq(1,nrow(opart4))%%2==0,'SL']=pa2[,'SL']
   #pac.de=cbind(pac.de,opart4)
   pac.de=merge(pac.de,opart4[,c('rownames','pairnum','SL')],by=c('rownames','pairnum'))
   pac.de=pac.de[order(pac.de[,'pvalue'],pac.de[,'pairnum']),]
  }
  return(pac.de)
}


#############################################################################
#  DE3PAC
#  说明:
#  0) 参考Fu et al
#  1) 计算pac矩阵中的每个gene内的PAC是否存在lengthening现象
#  2) 输出（仅输出DE的行,rownames=PAC的行号）：#PAC[gene,g1Cols,g2Cols,++ g1Norms,g2Norms,g1Avg,g2Avg,len,pvalue,padj]
#  参数说明：
#  pac: matrix，含标题行(gene,[g1Cols],[g2Cols],ftr_start,strand,ftr_end,coord,<ftr>)
#  g1Cols=c('wl_s1','wl_s2','wl_s5'); g1Lbl='WT_leaf'
#  mergeType='AVG',normFirst=T,normAfter=F: 将多列合并成1列的方式
#  filterType; CPM; QNT: CPM_Quant,CPM,Quant,Quant_CPM
#  filterUTR=T/F #是否过滤ftr=3UTR的行,即只判断3UTR的长度
#  DE判断条件：
#  pvalue=0.01 #prop.pvalue<pvalue
#  #############################################################################
DE3PAC<-function(pac,g1Cols,g2Cols,g1Lbl='WT',g2Lbl='Mutant',mergeType='AVG',normFirst=T,normAfter=F,filterType='cpm_quant',CPM=2,QNT=0.2,TOT=10,filterUTR=T,pvalue=0.01){
  #条件判断
  if (!AinB(g1Cols,colnames(pac))) stop (paste(g1Cols,'not all in colnames!'))
  if (!AinB(g2Cols,colnames(pac))) stop (paste(g2Cols,'not all in colnames!'))
  #if (filterType!='' & !AinB(tolower(filterType),c('cpm_quant','cpm','quant','quant_cpm','total','cpm_total'))) stop ('Error filterType!')
  if (pvalue<0) stop("Error options: pvalue!")
  if (filterUTR & !AinB(c('ftr'),colnames(pac))) stop ('ftr not all in colnames!')
  #用于取得3UTR长度的
  if (!AinB(c('gene','ftr_start','strand','ftr_end','coord'),colnames(pac))) stop ('gene,ftr_start,ftr_end,strand,coord not all in colnames!')
  
  if (is.null(rownames(pac))) rownames(pac)=seq(1,nrow(pac))
  cat('raw#:',nrow(pac),'\n')
  pac=pac[rowSums(pac[,c(g1Cols,g2Cols)])>0,]
  #过滤3UTR的数据
  if (filterUTR) {
    dat=pac[pac$ftr=='3UTR',c(g1Cols,g2Cols)]
    dat=dat[rowSums(dat)>0,]
    cat('3UTR >0:',nrow(dat),'\n')
  } else {
    dat=pac[,c(g1Cols,g2Cols)]
    dat=dat[rowSums(dat)>0,]
    cat('raw>0#:',nrow(dat),'\n')
  }  

  #过滤得到dat.norm 
  if (filterType != '') {
    cat('filtering by',filterType,'...\n')
    dat.norm=filterByCpmQnt(dat,filterType,CPM=CPM,QNT=QNT,TOT=TOT)
  }

  #标准化得到dat.norm
  f=getSizeFactor(as.matrix(dat),'DESEQ',oLibsize=F);
  dat.norm=normBySizeFactor(dat,f)
  
  dat.g=multi2two(dat,c(g1Cols,g2Cols),c(rep(g1Lbl,length(g1Cols)),rep(g2Lbl,length(g2Cols))),mergeType=mergeType,normFirst=normFirst,normAfter=normAfter) 

  #判断Lengthening
  #求3UTR len：strand+ coord-ftr_start；strand- ftr_end-coord
  info=pac[rownames(pac) %in% rownames(dat.g),c('gene','strand','ftr_start','ftr_end','coord')]
  dat.g=as.data.frame(cbind(dat.g,info))
  len=ifelse(dat.g$strand=='+',dat.g$coord-dat.g$ftr_start+1,dat.g$ftr_end-dat.g$coord+1)
  dat.g=cbind(dat.g,len)
  dat.g=dat.g[order(dat.g$gene,dat.g$len),] #按gene和len排列

  #判断: 只有1个gene有>=2PA时，才可以判断
  #输出为：odat[dat.g+pvalue]
  gene=unique(dat.g$gene)
  odat=matrix(nrow=0,ncol=ncol(dat.g)+1) 
  colnames(odat)=c(colnames(dat.g),'pvalue')
  for (i in 1:length(gene)) {
    agene=gene[i]
    idx=which(dat.g$gene==agene)
    if (length(idx)<=1) next
    #print(agene)
    #对该gene内的所有PA
    dg=dat.g[idx,]
    s1=dg[,g1Lbl]; s2=dg[,g2Lbl]; tots=s1+s2; 
    #if(sum(s1)==0 | sum(s2)==0) tots[1]=tots[1]+1 #防止tot=s时，test出错
    pv=try(prop.trend.test(s1,tots,score=dg[,'len']))
    if (class(pv)=='try-error') {
      pv=1
    } else {
      pv=pv$p.value
    }
   # print(pv)
    pv=rep(pv,nrow(dg))
    odat=rbind(odat,cbind(dg,pv))
    rownames(odat)[(nrow(odat)-nrow(dg)+1):nrow(odat)]=rownames(dg)
  } #~i
  #可能存在pvalue=NA的行
  odat[is.na(odat[,'pv']),'pv']=1
  colnames(odat)=c(colnames(dat.g),'pvalue')
  cat('PA# B4Pvalue',nrow(odat),'\n') 
  cat('gene# B4Pvalue',length(unique(odat$gene)),'\n') 
  odat=odat[odat[,'pvalue']<pvalue,]  #先过滤pvalue再padj
  padj=p.adjust(odat[,'pvalue'])
  odat=as.data.frame(cbind(odat,padj))
  odat=odat[order(odat$gene,odat$pvalue,odat$padj),]
  cat('gene# pvalue',length(unique(odat$gene)),'\n')
  cat('gene# padj0.05',length(unique(odat$gene[odat[,'padj']<0.05])),'\n')
  cat('gene# padj0.1',length(unique(odat$gene[odat[,'padj']<0.1])),'\n')
  
  #输出（仅输出pv<pvalue的行）：
  #PAC[gene,g1Cols,g2Cols,++ g1Norms,g2Norms,g1Avg,g2Avg,len,pvalue,padj]
  #rownames=PAC的行号
  opart1=pac[rownames(pac) %in% rownames(odat),c('gene',g1Cols,g2Cols)]
  opart2=dat.norm[rownames(dat.norm) %in% rownames(odat),c(g1Cols,g2Cols)]
  colnames(opart2)=paste(colnames(opart2),'_norm',sep='')
  opart3=odat[,c(g1Lbl,g2Lbl,'len','pvalue','padj')]
  colnames(opart3)=c(paste(c(g1Lbl,g2Lbl),'_merge',sep=''),'len','pvalue','padj')
  opart1=opart1[order(rownames(opart1)),]
  opart2=opart2[order(rownames(opart2)),]
  opart3=opart3[order(rownames(opart3)),]
  pac.de=cbind(opart1,opart2)
  pac.de=cbind(pac.de,opart3)
  pac.de=pac.de[order(pac.de$gene,pac.de$len,pac.de$pvalue),]
  return(pac.de)
}


#-------------------------------------------------
# global diff
#-------------------------------------------------

#############################################################################
#  FUgene
#  计算一组gene的相关系数和pvalue
#  参数说明：
#  dat: data.frame，包含[smp1,smp2,lblScore]
#  smp1,smp2,lblScore: 列名，仅1个值
#  geneFilter: 用smp1+smp2>=geneFilter来过滤有效的gene
#  toPlot：是否画散点图
#  输出：(gene,cor,padj,logRatio)用于画Fu的散点图
#dat=read.table('t_4_pac_nf_2rep_wtoxt.tostop')
#colnames(dat)=c('gene','wt','oxt','len')
#fu=FUgene(dat,'wt','oxt','len',30,pdf='xx.pdf',FDR=0.1)

#输出类似 -- change列根据参数FDR决定
#gene	cor	padj	logRatio	change
#AT2G22780	0.593288741702413	7.11906779704433e-37	2.00979331632478	leaf_to_seed_longer
#AT2G39570	0.443945662208361	6.26213337507655e-24	0.230655258701802	leaf_to_seed_longer
#AT4G17620	-0.322168219365689	0.0951651004044109	-2.27244656375834	leaf_to_seed_Shorter
#AT4G36750	-0.319804377234013	0.107428877107239	2.20671703281195	X
#AT3G14130	-0.570259235330364	0.174025608968678	-0.853958464793667	X
##############################################################################
FUgene<-function(dat,smp1,smp2,lblScore,geneFilter=1,toPlot=T,pdf,FDR=0.1) {
genes=unique(dat$gene)
gs=c()
p=c()
cr=c()
ratio=c()

for (g in genes) {
  ig=which(dat$gene==g)
  if (length(ig)<2 || sum(dat[ig,c(smp1,smp2)])<geneFilter) {
    next
  }
  s1=dat[ig,smp1]
  s2=dat[ig,smp2]
  score=dat[ig,lblScore]
  if (sum(s1)==0 | sum(s2)==0) next
  p=c(p,prop.trend.test(s1, s1+s2,score=score)$p.value)
  cr=c(cr,FU(s1,s2,score))
  ratio=c(ratio,log2((sum(s1)+0.1)/(sum(s2)+0.1)))
  gs=c(gs,g)
}

if (toPlot || pdf!='') {
	if (pdf!='') pdf(file=pdf)
}
  pv=p.adjust(p)
  for (pthd in unique(FDR,c(0.01,0.05,0.1))) {
	x=which(pv<pthd & cr>0)
	y=which(pv<pthd & cr<=0)
	if (toPlot || pdf!='') {
		main=paste(smp1,'&',smp2,' ',length(gs),' gene(>=',geneFilter,') Blue(S)=',length(y),' Red(L)=',length(x),' FDR=',pthd,sep='')
		plot(cr,ratio,main=main,ylab=paste('log2(',smp1,'/',smp2,')',sep=''),type='p',col='grey')
		lines(x=cr[x],y=ratio[x],type='p',col='red',pch=20)  ##ratio=XX/YY; if X-axis>0, from XX to YY, switch to longer. 
		lines(x=cr[y],y=ratio[y],type='p',col='blue',pch=20) 
		if(pdf!='') dev.off()
	}
	if (pthd==FDR) {
	  XYLong=paste(smp1,'to',smp2,'longer',sep='_');
	  XYShort=paste(smp1,'to',smp2,'Shorter',sep='_');
	  notChange='X';
	  change=rep(notChange,length(pv))
	  change[x]=XYLong; change[y]=XYShort;
	}
  }#for

rt=as.data.frame(cbind(gene=gs,cor=cr,padj=pv,logRatio=ratio,change=change))
rt$cor=as.numeric(rt$cor); rt$logRatio=as.numeric(rt$logRatio);rt$padj=as.numeric(rt$padj);
rt=rt[order(rt$change,rt$padj),]
return(rt)
}


#############################################################################
#  FU
#  说明:
#  0) 参考FU et al
#  1) 计算两组数据的相关系数
#  2) 输出cor
#  参数说明：
#  s1: 样本1/2；score分值
# s1=c(17066,14464,788  ,126  ,37)
# s2=c(48,38,5,1,1)
# score=c(0,0.5,1.5,4.0,7.0)
# r=FU(s1,s2,score)

#r=FU(s1,s2,score)
#M=(sum(s1+s2)-1)*(r^2)
#1-pchisq(M,1) === 用卡方检验
#prop.trend.test(s1, s1+s2,score=score)$p.value === 用R函数得到的pvalue差不多

#> s1
#[1] 50 30 20
#> s2
#[1]  30  35 200
#> score
#[1] 100 150 300
#> r
#0.5179539 
#>  log2(sum(s1)/sum(s2))
#[1] -1.405992
##############################################################################
#FU(c(50,30,20),c(30,35,200),c(100,150,300))
FU<-function(s1,s2,score) {

p1=s1/sum(s1+s2)
p2=s2/sum(s1+s2)
p=rbind(p1,p2)
u=c(1,2)
v=score
ubar=sum(p1)*u[1]+sum(p2)*u[2]
vbar=sum((p1+p2)*v)

fz=0
for (i in 1:2) {
  for (j in 1:length(v)) {
   fz=fz+(u[i]-ubar)*(v[j]-vbar)*p[i,j]   
  }
}

fm1=0
for (i in 1:2) {
  fm1=fm1+(u[i]-ubar)^2*sum(p[i,])
}
  
fm2=0
for (j in 1:length(v)) {
  fm2=fm2+(v[j]-vbar)^2*sum(p[,j])
}
  
r=fz/sqrt(fm1*fm2)
return(r)
}



#############################################################################
#  patrick
#  说明:
#  0) 参考Patrick et al
#  1) 计算两样本下gene的global similarity，这里用fisher.test (pvalue)，以及Patrick的方式 (diff)
#  2) 输出（仅输出>=2PA的gene）：仅输出(gene,panum,g1pa,g2pa,pvalue,diff,wghdiff(带权重的)) 不输出 PAC[gene,g1Cols,g2Cols,++ g1Norms,g2Norms,g1Avg,g2Avg,pvalue]
#  参数说明：
#  pac: matrix，含标题行(gene,[g1Cols],[g2Cols])
#  g1Cols=c('wl_s1','wl_s2','wl_s5'); g1Lbl='WT_leaf'
#  mergeType='AVG',normFirst=T,normAfter=F: 将多列合并成1列的方式
#  filterType; CPM; QNT: CPM_Quant,CPM,Quant,Quant_CPM
#  geneFilter= 用于过滤满足total_PAT#>=geneFilter的基因
#  toPlot=T/F 是否作accumulate图
#  pdf=file 如果toPlot或pdf<>''，则都画图
#  #############################################################################
#画累积的区间图,及返回Y值，以便后续画图
#toNorm=F则不将Y标准化到0-1间
#toPlot=F 则不作图
.plotCum<-function(array,intervals,toPlot=T,toNorm=T,...) {
  idx=findInterval(array,intervals)
  cnt=tapply(idx,as.factor(idx),length)
  x=intervals; y=rep(0,length(x)); 
  y[as.integer(names(cnt))]=cnt
  if (toNorm) {
    y=cumsum(y)/sum(y)
  } else {
    y=cumsum(y)
  }
  if(toPlot) plot(x,y,...)
  return(y)
}

patrick<-function(pac,g1Cols,g2Cols,g1Lbl='WT',g2Lbl='Mutant',mergeType='AVG',normFirst=T,normAfter=F,filterType='cpm_quant',CPM=2,QNT=0.2,TOT=10,geneFilter=0,toPlot=T,pdf=''){
  #条件判断
  if (!AinB(g1Cols,colnames(pac))) stop (paste(g1Cols,'not all in colnames!'))
  if (!AinB(g2Cols,colnames(pac))) stop (paste(g2Cols,'not all in colnames!'))
 # if (filterType!='' & !AinB(tolower(filterType),c('cpm_quant','cpm','quant','quant_cpm','total','cpm_total'))) stop ('Error filterType!')
  if (!AinB(c('gene'),colnames(pac))) stop ('gene  not all in colnames!')
  
  if (is.null(rownames(pac))) rownames(pac)=seq(1,nrow(pac))
  dat=pac[,c(g1Cols,g2Cols)]
  cat('raw#:',nrow(dat),'\n')
  dat=dat[rowSums(dat)>0,]
  cat('raw>0#:',nrow(dat),'\n')
  
  #过滤得到dat.norm 
  if (filterType != '') {
    cat('filtering by',filterType,'...\n')
    dat=filterByCpmQnt(dat,filterType,CPM=CPM,QNT=QNT,TOT=TOT)
  }
  
  #标准化得到dat.norm
  f=getSizeFactor(as.matrix(dat),'DESEQ',oLibsize=F);
  dat.norm=normBySizeFactor(dat,f)
  
  dat.g=multi2two(dat,c(g1Cols,g2Cols),c(rep(g1Lbl,length(g1Cols)),rep(g2Lbl,length(g2Cols))),mergeType=mergeType,normFirst=normFirst,normAfter=normAfter) 
  
  #每gene计算pvalue
  gene=pac[rownames(pac) %in% rownames(dat.g),c('gene')]
  dat.g=as.data.frame(cbind(dat.g,gene))
  dat.g[,g1Lbl]=as.numeric( dat.g[,g1Lbl])
  dat.g[,g2Lbl]=as.numeric( dat.g[,g2Lbl])

  #判断: 只有1个gene有>=2PA时，才可以判断
  #临时输出为：odat[dat.g+pvalue]
  #真实输出为：ogene(gene,panum,pvalue)
  gene=unique(dat.g$gene)
  odat=matrix(nrow=0,ncol=ncol(dat.g)+2) 
  colnames(odat)=c(colnames(dat.g),'pvalue','diff')
  ogene=matrix(nrow=0,ncol=6) 
  colnames(ogene)=c('gene','panum',paste(g1Lbl,'pa',sep='_'),paste(g2Lbl,'pa',sep='_'),'pvalue','diff')
  cat('gene#',length(gene),'\n')
  print(paste('geneFilter=',geneFilter,sep=''))
  cntapa=0;
  gidx=which(colnames(dat.g)=='gene')
 for (i in 1:length(gene)) {
    agene=gene[i]
    idx=which(dat.g$gene==agene)
    if (length(idx)<=1) next
    cntapa=cntapa+1
    #对该gene内的所有PA
    dg=dat.g[idx,]
    if (sum(dg[,-gidx])<geneFilter) next
    s1=dg[,g1Lbl]; s2=dg[,g2Lbl];

    #计算pvalue
    pv=try( fisher.test(cbind(s1,s2)),silent=T) #防止fisher.test在表过大时出错！
    if (class(pv)=='try-error') {
      pv=try(fisher.test(cbind(s1,s2),workspace=2e+07,hybrid=TRUE),silent=T)
      if (class(pv)=='try-error') {
        pv=try(chisq.test(cbind(s1,s2)),silent=T)
	#if (class(pv)=='try-error') {
	#  cat(agene); print(cbind(s1,s2));
	#  stop('stop')
	#}
      }
    }

    pv=pv$p.value 
    pv=rep(pv,nrow(dg))

    #计算patrick的值
    sum1=ifelse(sum(s1)==0,1,sum(s1));  sum2=ifelse(sum(s2)==0,1,sum(s2))
    diff=sum(abs(s1/sum1-s2/sum2)/2)
    diff=rep(diff,nrow(dg))
    odat=rbind(odat,cbind(dg,pv,diff))
    rownames(odat)[(nrow(odat)-nrow(dg)+1):nrow(odat)]=rownames(dg)
    g1pa=toString(dg[,g1Lbl],sep=',');  g2pa=toString(dg[,g2Lbl],sep=',');
    ogene=rbind(ogene,c(agene,length(idx),g1pa,g2pa,pv[1],diff[1]))
  } #~i
  cat('APAgene#',cntapa,'\n')
  ogene=as.data.frame(ogene)
  ogene[,'pvalue']=as.numeric(ogene[,'pvalue'])
  ogene[,'panum']=as.numeric(ogene[,'panum'])
  ogene[,'diff']=as.numeric(ogene[,'diff'])
  wghdiff=ogene[,'diff']*(1-ogene[,'pvalue'])
  ogene=cbind(ogene,wghdiff)
  if (toPlot || pdf!='') {
    if (pdf!='') pdf(file=pdf)
    par(mfrow=c(1,3)) 
    intervals=seq(0,1,0.05)
    y=.plotCum(ogene$pvalue,intervals,main=paste(nrow(ogene),' genes','@pvalue',sep=''),type='b')
    y=.plotCum(ogene$diff,intervals,main='diff',type='b')
    y=.plotCum(ogene$wghdiff,intervals,main='weighted-diff',type='b')
    if (pdf!='') dev.off()
  }
  return(ogene)

 if (0) { #备用
  colnames(odat)=c(colnames(dat.g),'pvalue') 
  #输出（仅输出pv<pvalue的行）：
  #PAC[gene,g1Cols,g2Cols,++ g1Norms,g2Norms,g1Avg,g2Avg,len,pvalue,padj]
  #rownames=PAC的行号
  opart1=pac[rownames(pac) %in% rownames(odat),c('gene',g1Cols,g2Cols)]
  opart2=dat.norm[rownames(dat.norm) %in% rownames(odat),c(g1Cols,g2Cols)]
  colnames(opart2)=paste(colnames(opart2),'_norm',sep='')
  opart3=odat[,c(g1Lbl,g2Lbl,'len','pvalue','padj')]
  colnames(opart3)=c(paste(c(g1Lbl,g2Lbl),'_merge',sep=''),'len','pvalue','padj')
  opart1=opart1[order(rownames(opart1)),]
  opart2=opart2[order(rownames(opart2)),]
  opart3=opart3[order(rownames(opart3)),]
  pac.de=cbind(opart1,opart2)
  pac.de=cbind(pac.de,opart3)
  pac.de=pac.de[order(pac.de$gene,pac.de$len,pac.de$pvalue),]
  return(pac.de)
} #~0
}

