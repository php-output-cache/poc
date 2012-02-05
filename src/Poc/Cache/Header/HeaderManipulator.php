<?php
/*Copyright 2012 Imre Toth <tothimre at gmail>

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
/**
 * This class contains some header related functionality. By utilizing its 
 * capabilities you will be able to manipulate and store the the header.
 *  
 * @author Imre Toth
 *
 */
namespace POC\cache\header;

use POC\Poc;

use POC\cache\cacheimplementation\Cache;

class HeaderManipulator
{
  var $headersToPreserve;
  
  var $headersToStore;
  
  var $headersToSend;
  
  var $headersToRemove;
  
  var $eTag;
  
  var $outputHeader;

  /**
   * 
   * @var Cache
   */
  private  $cache;
  
  /**
   * Poc
   */
  private $poc;
  
  var $isEtagGeneration;

/*  public function __construct(Poc $poc)
  {
    $this->poc = $poc;
  }*/
  
  public function setCache($cache){
    $this->cache = $cache;
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

  public function storeHeaderVariable($headerVariable){
    //TODO: check for all possible valid header variables.
    $this->headersToPreserve[] = $headerVariable;
  }

  public function storeHeadersForPreservation($responseHeaders){
    if($this->headersToPreserve){
      $headerTmp = array();

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

  public function setEtagGeneration($boolean = true){
    $this->isEtagGeneration = $boolean;
  }
  
  public function setOutputHandler($outputHeader){
    $this->outputHeader = $outputHeader;
  }

  public function storeHeades($output){

    //TODO: still not working.
    if($this->isEtagGeneration){
      $this->cache->cacheSpecificStore(
          $this->cache->getHasher()->getKey().'e',
          $this->etagGeneration($output));
    }
    
    if($this->headersToStore){
      $this->cache->cacheSpecificStore(
          $this->cache->getHasher()->getKey().'h',
          serialize($this->headersToStore));
    }
  } 
  
  public function fetchHeaders(){
    $this->headersToSend = unserialize($this->cache->fetch(
        $this->cache->getHasher()->getKey().'h'));
    $this->eTag = ($this->cache->fetch(
        $this->cache->getHasher()->getKey().'e'));
  }
  
}