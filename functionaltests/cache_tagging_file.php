<?php
/*Copyright 2011 Imre Toth <tothimre at gmail>

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/
  ini_set('display_errors', 1);
  error_reporting(E_ALL);

  use POC\Poc;
  use POC\handlers\ServerOutput;
  use POC\cache\PocCache;
  use POC\cache\cacheimplementation\FileCache;

  use POC\cache\tagging\driver\mySQL\CacheTable;
  use POC\cache\tagging\MysqlTagging;

  use POC\cache\filtering\Hasher;
  use POC\cache\filtering\filter;

  include ("../framework/autoload.php");

/*
  $mt = new MysqlTagging();
  $mt->addCacheToTags('user,customer', '31291a18c630c9b65a7792d9f247903a');
  exit;
*/

/*
  //$eval->addCacheTags(true,'invetntory,article');
  if(isset($_GET)){
    if(isset($_GET['delcache'])){
      if($_GET['delcache']){
          $eval->addCacheInvalidationTags(true,'user,customer');
  //      $eval->addCacheInvalidationTags(true,'invetntory,article');
      }
    }
  }
  */

  $hasher = new Hasher();
  $filter = new Filter();
  $hasher->addDistinguishVariable($_GET);

  $cache = new FileCache($hasher, 5, new MysqlTagging);

  //$apcCache->addCacheAddTags(true,"Karacsonyfa,Mezesmadzag,csicsa");

  $pocCache = new PocCache($cache,$filter);

  //$cache->addCacheAddTags(true,"Karacsonyfa,Mezesmadzag,csicsa");

  $pob  = new Poc($pocCache, new ServerOutput(),  true);

  //$pob->addCacheInvalidationTags($_GET,"Mezesmadzag,csicsa");
  //print_r($sqlite3Tagging->addCacheToTags('zizi,yuyu,aa,bb,ggg,fufu,fufufu,dict,sztaki,hu,dsaj,adsf,sdaf,adsf,asdf,sadf,dafgfdsg,ghrt,qw,we,er,rt,ty,yu,uii,io,as,sd,df,fg,gh,hj,jk,kl,zx,xc,v,cb,vn,bm,fh,df,sd,ad,qe,wr,e,t,ry,ru,,ueu,i,dj,sd,ssdf,sdf,sd,fsd,f,sdf,sd,f,sdf,sd,f,dfg,rewt,yu,ghj,sdfg,bv,gfh,rew,tq,etr,hdsg,hjsj,wu,djdj,sh,wy,ry,hfh,fh,d,gd,g,dgssdfg,sdf,g,ty,t,yhf,ghb,cvhgf,hg,fh,gfj,gfh,sdfg,dfhb,gfn,v,bnb,n,sfh,y,hh,oyoy,pdpdp,zlzl,al,bbbb,wweewe,rtrtrt,tytyty,yuyu,zxzxzx,xcxcxc,cvcvcv,vbvbvb,bnbn,ghghgh,fgfgfg,dfdfsfd,1,2,3,4,5,6,7,8,9,01'));

  include('lib/text_generator.php');
