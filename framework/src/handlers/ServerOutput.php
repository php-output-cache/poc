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
/**
 * This class is an implementation of the Outoput Interface. This is used in a 
 * server Environment to handle the output.
 * 
 * @author Imre Toth
 *
 */
namespace POC\handlers;
class ServerOutput implements OutputInterface {

  function getLevel(){
    return ob_get_level();
  }

  function startBuffer($callbackFunctname){
    ob_start(array('\POC\Poc', $callbackFunctname));
  }

  function StopBuffer(){
    die();
  }

  function header($header){
    \header($header);
  }

  function obEnd(){
    \ob_end_flush();
  }

  function ObPrintCallback($output){
    echo $output;
  }

  function headersList(){
    return \headers_list();
  }
  function printOutputCallback($output){
    echo $output;
  }
}
