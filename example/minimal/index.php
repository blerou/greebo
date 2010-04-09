<?php

namespace greebo\essence;

error_reporting(E_ALL);
ini_set('display_errors', true);

require __DIR__.'/../../src/greebo/essence/Greebo.php';

Greebo::register();


class FirstController extends HttpController
{
  public function foo()
  {
    $response = new HttpResponse();
    $response->setContent('foo action content');
    return $response;
  }
}




$request = new HttpRequest($_GET, $_POST, $_FILES, $_COOKIE, $_SERVER);
$request->action = 'foo';

try {
    $controller = new FirstController();
    $response = $controller->handle($request);
    if (!$response instanceof HttpResponse) {
        $message = sprintf('Action has to return a HttpResponse instance: %s', $request->action);
        throw new HttpException($message);
    }
} catch (HttpException $e) {
    $exception_handler = new HttpExceptionHandler();
    $response = $exception_handler->handle($e);
} catch (\Exception $e) {
    // TODO render 404 page
    throw $e;
}

$responder = new HttpResponder();
$responder->send($response);