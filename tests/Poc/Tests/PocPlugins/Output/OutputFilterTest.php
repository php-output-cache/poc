<?php
/*
 * Copyright 2013 Imre Toth <tothimre at gmail> Licensed under the Apache
 * License, Version 2.0 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law
 * or agreed to in writing, software distributed under the License is
 * distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied. See the License for the specific language
 * governing permissions and limitations under the License.
 */

namespace Poc\Tests\PocPlugins\Output;

use Poc\Tests\Toolsets\NativeOutputHandlers\NativeOutputHandlersTestCore;

use Poc\Cache\CacheImplementation\CacheParams;
use Poc\Cache\CacheImplementation\FileCache;
use Poc\Cache\Filtering\Hasher;
use Poc\Poc;
use Poc\PocParams;
use Poc\PocPlugins\Output\OutputFilter;
use Poc\Toolsets\NativeOutputHandlers\Handlers\Output\TestOutput;
use Poc\Toolsets\NativeOutputHandlers\HttpCapture;

class OutputFilterTest extends \PHPUnit_Framework_TestCase
{
    //todo: test has to be implemented!!
    public function testOutputFilter ()
    {
        $noh = new NativeOutputHandlersTestCore();
        $hasher = new Hasher();
        $hasher->addDistinguishVariable("testOutputFilter".  rand());

        $cache = new FileCache(
                array(CacheParams::PARAM_TTL => 1000,
                      ));
        $outputFilter = new OutputFilter();

        $outputFilter->addBlacklistCondition("/Needle/");

        $poc = new Poc(
                        array(PocParams::PARAM_CACHE => $cache,
                              Poc::PARAM_TOOLSET => new HttpCapture(new TestOutput()),
                              PocParams::PARAM_HASHER => $hasher,
                              ));

        $poc->addPlugin($outputFilter);

        $noh->pocBurner($poc, rand() . "Needle" . rand());
        //$this->assertContains('because', $noh->getOutput());
    }
}
