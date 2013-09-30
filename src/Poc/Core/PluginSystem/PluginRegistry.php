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

namespace Poc\Core\PluginSystem;

class PluginRegistry
{
    private $singleInstancedPlugins = array();

    /**
     *
     * @param  PluginInterface $plugin
     * @throws MultipleSameTypePluginRegistrationException
     */
    public function addPlugin($plugin)
    {
        //if ($plugin->isPluginMultipleInstanced()) {
            if (isset($this->singleInstancedPlugins[$plugin->getPluginName()])) {
               throw (new \Poc\Core\PluginSystem\Exceptions\MultipleSameTypePluginRegistrationException("Please Don't register this plugin twice!"));
            } else {
                if (!$plugin->isPluginMultipleInstanced()) {
                    $this->singleInstancedPlugins[$plugin->getPluginName()] = $plugin;
                }
            }
        //}
    }

    public function getPlugin($name)
    {
        if ($this->hasPlugin($name)) {
           return $this->singleInstancedPlugins[$name];
        } else {
           throw (new \Poc\Core\PluginSystem\Exceptions\PluginIsNotRegisteredException("Plugin is not registered! "));
        }
    }

    public function hasPlugin($name)
    {
        if (isset($this->singleInstancedPlugins[$name])) {
            return true;
        }

        return false;
    }
}
