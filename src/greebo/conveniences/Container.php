<?php

namespace greebo\conveniences;

class Container extends \greebo\essence\Container
{
  function init() {
    parent::init();

    $this->vendor = 'Greebo';
    $this->app = 'default';
    $this->loader = $this->shared(function($c) { return new ClassLoader; });
    $this->request = $this->shared(function($c) { return new Request; });
    $this->response = $this->shared(function($c) { return new Response($c); });
    $this->action_name = $this->request->param('action', 'index');
    $this->action = $this->shared(function($c) {
      $class = sprintf('%s\\%s\\ActionSet', $c->vendor, $c->app);
      return new $class($c);
    });
    $this->template = $this->shared(function($c) {
      if (!class_exists($class = sprintf('%s\\%s\\Template\\%s', $c->vendor, $c->app, $c->action_name)))
        if (!class_exists($class = sprintf('greebo\\conveniences\\%s', $c->action_name)))
          $class = 'greebo\\conveniences\\error404';
      $template = new $class($c);
      $template->escaper(function($val) { return htmlentities($val, ENT_QUOTES, 'utf-8'); });
      return $template;
    });
  }
}