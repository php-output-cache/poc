<?php
// @codingStandardsIgnoreFile
// @codeCoverageIgnoreStart 
// this is an autogenerated file - do not edit
spl_autoload_register(
    function($class) {
        static $classes = null;
        if ($classes === null) {
            $classes = array(
                'abstractdb' => '/src/cache/tagging/AbstractDb.php',
                'abstractpoccachespecific' => '/src/cache/cacheImplementation/AbstractPocCacheSpecific.php',
                'apccache' => '/src/cache/cacheImplementation/ApcCache.php',
                'filecache' => '/src/cache/cacheImplementation/FileCache.php',
                'logger' => '/src/utility/Logger.php',
                'memcachedcache' => '/src/cache/cacheImplementation/MemcachedCache.php',
                'poc\\cache\\filtering\\evaluateable' => '/src/cache/filtering/Evaluateable.php',
                'poc\\cache\\filtering\\hasvalue' => '/src/cache/filtering/HasValue.php',
                'poc\\cache\\filtering\\tohash' => '/src/cache/filtering/ToHash.php',
                'poc\\cache\\filtering\\tostring' => '/src/cache/filtering/ToString.php',
                'poc\\cache\\poccache' => '/src/cache/PocCache.php',
                'poc\\cache\\poccacheinterface' => '/src/cache/PocCacheInterface.php',
                'poc\\handlers\\outputinterface' => '/src/handlers/OutputInterface.php',
                'poc\\handlers\\serveroutput' => '/src/handlers/ServerOutput.php',
                'poc\\poc' => '/src/Poc.php',
                'poccachespecificinterface' => '/src/cache/cacheImplementation/PocCacheSpecificInterface.php',
                'sqlitetagging' => '/src/cache/tagging/SqliteTagging.php',
                'tagger' => '/src/cache/tagging/Tagger.php',
                'unittest\\apccache' => '/tests/cache/cacheImplmentation/ApcCache.php',
                'unittest\\apccachetest' => '/tests/cache/cacheImplmentation/ApcCacheTest.php',
                'unittest\\cachetest' => '/tests/cache/cacheImplmentation/CacheTest.php',
                'unittest\\filecachetest' => '/tests/cache/cacheImplmentation/FileCacheTest.php',
                'unittest\\handler\\testoutput' => '/tests/handlers/TestOutput.php',
                'unittest\\memcachedcachetest' => '/tests/cache/cacheImplmentation/MemcachedCacheTest.php',
                'unittest\\testclasstest' => '/tests/PocTest.php'
            );
        }
        $cn = strtolower($class);
        if (isset($classes[$cn])) {
            require __DIR__ . $classes[$cn];
        }
    }
);
// @codeCoverageIgnoreEnd