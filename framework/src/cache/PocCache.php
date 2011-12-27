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

namespace POC\cache;

use POC\cache\cacheimplementation\AbstractPocCacheSpecific;
use POC\cache\filtering\Filter;
class PocCache {

  var $specificCache;
  var $headersToPreserve;
  var $headersToStore;
  var $headersToSend;
  var $headersToRemove;
  var $outputBlacklist;
  var $eTag;
  private $isEtagGeneration = 1;
  private $filter;

  function __construct (AbstractPocCacheSpecific $specificCache, Filter $filter) {
    $this->specificCache = $specificCache;
    $this->filter = $filter;
  }

  public function getFilter(){
    return $this->filter;
  }
  public function storeCache($output) {
    if ($this->filter->evaluate()) {
       $this->specificCache->cacheSpecificStore(
                   $this->specificCache->getHasher()->getKey(), $output);
       //TODO: still not working.
       if($this->isEtagGeneration){
         $this->specificCache->cacheSpecificStore(
           $this->specificCache->getHasher()->getKey().'e',
                                                $this->etagGeneration($output));
       }

       if($this->headersToStore){
         $this->specificCache->cacheSpecificStore(
           $this->specificCache->getHasher()->getKey().'h',
                                              serialize($this->headersToStore));
      }
    }
  }

  public function fetchCache() {
    if($this->filter->evaluate()){
      $this->headersToSend = unserialize($this->specificCache->cacheSpecificFetch(
                        $this->specificCache->getHasher()->getKey().'h'));
      $this->eTag = ($this->specificCache->cacheSpecificFetch(
                        $this->specificCache->getHasher()->getKey().'e'));
      return $this->specificCache->cacheSpecificFetch(
                             $this->specificCache->getHasher()->getKey());
    }
  }

  public function getSpecificCache() {
    return $this->specificCache;
  }


  public function storeHeaderVariable($headerVariable){
//TODO: check for all possible valid header variables.
    $this->headersToPreserve[] = $headerVariable;
  }

  public function storeHeaderToRemove($headerVariable){
    $this->headersToRemove[] = $headerVariable;
  }

  public function removeHeaders($reponseHeaders){
    if($this->headersToRemove){
      foreach($this->headersToRemove as $removeThisHeader){
        header_remove($removeThisHeader);
      }
    }
  }

  public function storeHeadersForPreservation($responseHeaders){
    if($this->headersToPreserve){
      foreach ($responseHeaders as $header){
        $headerTmp[] = explode(':', $header);
      }

      foreach($this->headersToPreserve as $findThisHeader){
        foreach ($headerTmp as $preserveThisHeader){
          if($preserveThisHeader[0] == $findThisHeader){
            $this->headersToStore[] = $findThisHeader.': '.$preserveThisHeader[1];
          }
        }
      }
    }
  }

//TODO: still not works
  public function etagGeneration($output){
    if($this->isEtagGeneration){
      $etag = md5($output);
      $this->headersToStore[] = 'Etag : '.$etag;
      return $etag;
    }
  }

  public function storeOutputBlacklistCondition($condition){
    $this->outputBlacklist[] = $condition;
  }

  //TODO:implement this functionality
  //still not properly implemented feature..
  public function isOutputBlacklisted ($output){
    if( $this->outputBlacklist ){
      foreach( $this->outputBlacklist as $condititon ){
        //$result = preg_match($condition, $output);
        //if($result){
          return false;
        //}
      }
    }
  }

  public function setEtagGeneration($boolean = true){
   $this->isEtagGeneration = $boolean;
  }

}
