<?php

namespace greebo\headache;

use greebo\essence\HttpNotFound;

require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__) . '/../../../../../src/greebo/essence/HttpResponse.php';
require_once dirname(__FILE__) . '/../../../../../src/greebo/essence/HttpNotFound.php';
require_once dirname(__FILE__) . '/../../../../../src/greebo/headache/ExceptionHandler.php';
require_once dirname(__FILE__) . '/../../../../../src/greebo/headache/HttpNotFoundExceptionHandler.php';

/**
 * Test class for HttpNotFoundExceptionHandler.
 * Generated by PHPUnit on 2010-04-11 at 13:10:52.
 */
class HttpNotFoundExceptionHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandle()
    {
        $handler = new HttpNotFoundExceptionHandler;
        $result = $handler->handle(new HttpNotFound);
        $this->assertTrue($result instanceof HttpResponse);
    }
}
