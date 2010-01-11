<?php

namespace greebo\test;

class Response extends \greebo\essence\Response
{
  function get_status() {
    return $this->_status;
  }
  function get_header() {
    return $this->_header;
  }
  function get_cookie() {
    return $this->_cookie;
  }
  function get_content() {
    return $this->_content;
  }
}
