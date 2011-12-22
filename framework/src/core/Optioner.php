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

namespace POC\core;

class Optioner {

  public function isInterfaceImoplemented(){

  }

  public function __construct(OptionAble $oa){
    $implementedinterfaces = (class_implements(get_class($oa)));

    if(isset($implementedinterfaces['POC\core\OptionAbleInterface'])){
      $oa->setOptions($this->optionsMerge($oa->getOptions(),
                                                     $oa->getDefaultOptions()));
     } else {
       throw new \Exception("Please Pass to the Optioner an instance of the
       OptionAbleInterface");
     }

   }

  public function optionsMerge($srcArray, $defaultValues){
    foreach($defaultValues as $key => $value){
      if(!isset($srcArray[$key])) {
        $srcArray[$key] = $value;
      }
    }
    return $srcArray;
  }
}