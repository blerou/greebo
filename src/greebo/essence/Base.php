<?php

namespace greebo\essence;

class Base
{
  private $_container;

  function __construct(Container $container) {
    $this->_container = $container;
  }

  function container() {
    return $this->_container;
  }
}