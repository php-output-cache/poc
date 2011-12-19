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
namespace POC;


class Poc
{
  private $outputHandler;
  private $output;
  private $buffering;
  static private $debug;
  static private $start;
  static private $caches;
  static private $level;

  public function setDebug($debug) {
    self::$debug = $debug;
  }

  public static function pocCallbackShowOutput($buffer) {
    $dbgMsg = '';
    if (self::$debug) {
       $dbgMsg = '<br>This page has not been cached because one  Evaluatebale is
                                                                   Blacklisted.'
       .' <b> Was Generated within </b>'
       .'<b>'.((microtime() - self::$start) * 1000).'</b> milliseconds.';
    }
    return $buffer.$dbgMsg;
  }

  public static function pocCallbackGenerate($buffer) {
    if (self::$level == \ob_get_level() - 1) {
      $res = '';
      for ( $i=0; $i<sizeof(self::$caches); $i++ ) {
        $cache = self::$caches[$i]->getSpecificCache();
        $eval = $cache->getEvaluateable();
        if ($eval->evaluate()) {
          $dbgMsg = '';

          if (self::$debug) {
            $dbgMsg = '<br>This page has been '
            .'<b> generated within </b> in '
            .'<b>'.((microtime() - self::$start) * 1000).
                                                           '</b> milliseconds.';

          }
          $res = $buffer.$dbgMsg;
          $arr = \headers_list();
          self::$caches[$i]->storeHeadersForPreservation($arr);
          self::$caches[$i]->removeHeaders($arr);
          self::$caches[$i]->storeCache($res);
          $eval->cacheAddTags();
        }
      }
     return ($res);
    }
  }

  public static function pocCallbackCache($buffer) {
    if (self::$debug) {
     $dbgMsg = '<br>This page has been '
     .' <b> Fetched from the cache within </b>'
     .'<b>'.((microtime() - self::$start) * 1000).'</b> milliseconds.';
      return ($buffer.$dbgMsg);
    } else {
      return ($buffer);
    }
  }

  /**
  @param PocCacheInterface $cache this placeholder class contains the various
  caches.
  @param bool $debug If true debug messages are provided in the output, only
  for develompment purposes.
  */
  function __construct(\POC\cache\PocCacheInterface $cache = null,
                        \POC\handlers\OutputInterface $output, $debug = false) {
    $this->outputHandler = $output;
    $this->setDebug($debug);
    if ($cache != null) {
      $this->addCache($cache);
      $this->start();
    }
    $this->outputHandler = $output;
  }

  private function fetchCache() {
   $started = 0;
    for ( $i=0; $i<sizeof(self::$caches); $i++ ) {
      self::$caches[$i]->cacheTagsInvalidation();
      if (self::$caches[$i]->getSpecificCache()->
                                                getEvaluateable()->evaluate()) {
        $this->output = self::$caches[$i]->fetchCache();
        if ($this->output) {
          $this->outputHandler->startBuffer('pocCallbackCache');
          $arr = headers_list();
          if (self::$caches[$i]->headersToSend) {
            foreach (self::$caches[$i]->headersToSend as $header) {
              $this->outputHandler->header($header);
            }
            self::$caches[$i]->removeHeaders($arr);
          }
          $started = 1;
          echo($this->output);
          $this->outputHandler->stopBuffer();
        }
      }
    }
    return $started;
  }

  public function start() {

    self::$start = microtime();

    if (!$this->fetchCache()) {
      $startCache = true;
      for ( $i=0; $i<sizeof(self::$caches); $i++ ) {
        if (self::$caches[$i]->getSpecificCache()->getEvaluateable()->
                                                              isBlacklisted()) {

          $startCache = false;
          $break;
        }
      }
      if ($startCache) {
        $this->buffering = true;
        self::$level = \ob_get_level();
        $this->outputHandler->startBuffer('pocCallbackGenerate');
      } else {
        $this->outputHandler->startBuffer('pocCallbackShowOutput');
      }
    }
  }

  public function addCache(\POC\cache\PocCacheInterface $cache) {
    self::$caches[] = $cache;
  }

  function __destruct() {
    if (isset(self::$level)) {
       if (self::$level) {
         \ob_end_flush();
       }
    }
  }

  public function destruct() {
    $this->__destruct();
  }
}
