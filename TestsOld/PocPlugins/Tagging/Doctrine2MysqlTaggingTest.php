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

namespace Poc\Tests\PocPlugins\Tagging;

class Doctrine2MysqlTaggingTest extends Doctrine2TaggingTest
{

    public static function getDoctrineOptionableOptions()
    {
        $options = $GLOBALS['DOCTRINE_OPTIONABLE'];
        $options['orm.entity_managers.default.connection'] = 'mysql';

        return $options;
    }

    public static function cleanDatabase($em)
    {
        parent::cleanDatabase($em);
        $conn = $em->getConnection();

        $sm = $conn->getSchemaManager();
        $fks = $sm->listTableForeignKeys('tags_has_caches');
        foreach ($fks as $fk) {
            $sql = "ALTER TABLE `tags_has_caches` DROP FOREIGN KEY `".$fk->getName()."`";
            $stmt = $conn->query($sql);

        }

    }
}
