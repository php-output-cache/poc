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

use Poc\Cache\CacheImplementation\CacheParams;
use Poc\Cache\CacheImplementation\FileCache;
use Poc\Cache\Filtering\Hasher;
use Poc\Poc;
use Poc\PocParams;
use Poc\PocPlugins\Output\MinifyHtmlOutput;
use Poc\Toolsets\NativeOutputHandlers\Handlers\Output\TestOutput;
use Poc\Toolsets\NativeOutputHandlers\HttpCapture;
use Poc\Tests\Toolsets\NativeOutputHandlers\NativeOutputHandlersTestCore;

class MinifyHtmlOutputTest extends NativeOutputHandlersTestCore
{
    const TEST_STRING_MINIFY_EXTRA_SPACE = "A    a      A";

    public function dataProviderForTests ()
    {
        return array(
                    array("A    a      A", "A a A"),
                    array("A
                        a      A", "A a A"),
                    array("A
a
                A", "A a A"),
                    array("A
        a
                A", "A a A"),

                array("A    a  <!-- html comment -->    A", "A a A"),

                );

    }

    /**
     * @dataProvider dataProviderForTests
     */
    public function testminifyHtmlWithPoc ($input, $expectedOutput)
    {
        $hasher = new Hasher();
        $hasher->addDistinguishVariable("TestMinify".  rand());

        $cache = new FileCache(
                                array(CacheParams::PARAM_TTL => self::BIGTTL,
                                    ));

        $outputHandler = new TestOutput();

        $poc  = new Poc(array(PocParams::PARAM_CACHE => new FileCache(),
                              PocParams::PARAM_OUTPUTHANDLER=> $outputHandler,
                              PocParams::PARAM_CACHE=>$cache,
                              PocParams::PARAM_HASHER=>$hasher,
                              Poc::PARAM_TOOLSET => new HttpCapture(new TestOutput())
                        ));

        $poc->addPlugin(new MinifyHtmlOutput);

        $this->pocBurner($poc, $input);
        $output = $this->getOutput();

        $this->assertEquals($expectedOutput, $output);
    }
}
