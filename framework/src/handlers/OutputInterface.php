<?php

namespace POC\Handlers;

interface OutputInterface
{
  function getLevel();
  function startBuffer($callbackFunctname);
  function stopBuffer();
  function header($header);
  function obEnd();
  function cacheCallback($output);
}
