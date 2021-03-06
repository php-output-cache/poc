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

namespace Poc\PocPlugins\Output;

//use Poc\Core\PocEvents\PocEventNames;
use FlyingWhale\Plugin\PluginInterface;
use Poc\Poc;
use Poc\Core\Events\BaseEvent;
use Poc\Toolsets\NativeOutputHandlers\Handlers\Callback\CallbackHandlerEventNames;

class OutputFilter implements PluginInterface
{

    private $outputBlacklist = null;

    /**
     *
     * @var Poc
     */
    private $poc;

    public function init($poc)
    {
        $poc->getPocDispatcher()->addListener(CallbackHandlerEventNames::BEFORE_THE_DECISION_IF_WE_CAN_STORE_THE_GENERATED_CONTENT,
                                           array($this, 'isOutputBlacklisted'));
    }

    public function addBlacklistCondition ($condition)
    {
        $this->outputBlacklist[] = $condition;
    }

    public function isOutputBlacklisted (BaseEvent $event)
    {
        if ($this->outputBlacklist) {
            foreach ($this->outputBlacklist as $condititon) {
                $result = preg_match($condititon, $event->getPoc()->getOutput());
                if ($result) {
                  $event->getPoc()->setCanICacheThisGeneratedContent(false);

                  return;
                }
            }
        }
    }

    public function getName()
    {
        return "OutputFilter";
    }

    public function isMultipleInstanced()
    {
        return false;
    }

}
