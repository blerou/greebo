<?php

namespace greebo\headache;

require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__) . '/../../../../../src/greebo/headache/ExceptionHandler.php';
require_once dirname(__FILE__) . '/../../../../../src/greebo/headache/ExceptionExceptionHandler.php';

/**
 * Test class for ExceptionExceptionHandler.
 * Generated by PHPUnit on 2010-04-11 at 13:10:52.
 */
class ExceptionExceptionHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Exception
     */
    public function testHandle()
    {
        $handler = new ExceptionExceptionHandler;
        $handler->handle(new \Exception);
    }
}
