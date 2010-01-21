Greebo is an object-oriented framework essence written in PHP 5.3.

It was inspired by Camping microframework - http://en.wikipedia.org/wiki/Camping_(microframework). I have also borrowed concepts from Symfony 2 - http://www.symfony-project.org - and Zend Framework 2 - http://framework.zend.com/wiki/display/ZFDEV2/Zend+Framework+2.0+Roadmap.

# Goals:
 * an experienced developer should understand the internals in 1 hour
 * introduce no new API (use PHP's API everywhere)
 * keep code at bare minimum

# Concept:

Every request is wrapped by a Request object. The Request is a simple wrapper around PHP's superglobals; it only adds ability to return default values by getter methods. 

...

At the end of the process it sends back a Response.
