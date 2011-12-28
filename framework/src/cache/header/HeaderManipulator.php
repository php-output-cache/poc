<?php
namespace POC\cache\header;

class HeaderManipulator
{
  var $headersToPreserve;
  var $headersToStore;
  var $headersToSend;
  var $headersToRemove;
  var $eTag;
  var $outputHeader;

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
  
}