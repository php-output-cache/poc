<?php
namespace Poc\Plugins;

use Poc\PocEvents\PocEvent;

use Poc\PocEvents\PocEventNames;

use Poc\Core\OptionAble\OptionAble;

use Poc\Core\OptionAble\OptionAbleInterface;

use Monolog\Handler\StreamHandler;

use Monolog\Logger;

use Poc\Core\Event\PocDispatcher;

use Symfony\Component\EventDispatcher\EventDispatcher;

use Poc\Poc;

class PocLogs implements OptionAbleInterface, PocLogsParams{
  
  private $logFolder;
  private $logPrefix;
  
  private $loggers;

  /**
   *
   * @var OptionAble
   *
   */
  private $optionAble;
  
  public function fillDefaults(){
    $this->optionAble[self::PARAM_TMP_FOLDER] = function (){
      return "/tmp/";
    };
    $this->optionAble[self::PARAM_LOG_PREFIX] = function (){
      return "POC_LOG";
    };
    
  }

  function __construct($options = array()){
    $this->optionAble = new OptionAble($options, $this);
    $this->optionAble->start();
    
    $this->logFolder = $this->optionAble[self::PARAM_TMP_FOLDER];
    $this->logPrefix = $this->optionAble[self::PARAM_LOG_PREFIX];
    
    $this->dispatcher = PocDispatcher::getIstance();
    $this->dispatcher->addListener(PocEventNames::BEFORE_OUTPUT_SENT_TO_CLIENT, array($this, 'beforeOutputSent'));

  }
  
  function beforeOutputSent(PocEvent $event){
      $log = $this->getLogger(PocEventNames::BEFORE_OUTPUT_SENT_TO_CLIENT);
      $log->addInfo("Output Sent!");
  } 
  
  private function gelog($eventName){
    $log = $this->getLogger($eventName);
    $log->pushHandler(new StreamHandler($this->logFolder.$this->logPrefix.PocEventNames::BEFORE_OUTPUT_SENT_TO_CLIENT, Logger::INFO));
    
  }
  
  /**
   * 
   * @param string $eventName
   * @return Logger
   */
  private function getLogger($eventName){
    if (!isset($this->loggers[$eventName])){
      $this->loggers[$eventName] = new Logger($eventName);
      $this->loggers[$eventName]->pushHandler(new StreamHandler($this->logFolder.$this->logPrefix.PocEventNames::BEFORE_OUTPUT_SENT_TO_CLIENT, Logger::INFO));
    }
    return $this->loggers[$eventName];
  }
    
}

