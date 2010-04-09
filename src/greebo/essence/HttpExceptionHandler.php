<?php

namespace greebo\essence;

class HttpExceptionHandler
{
    public function handle(HttpException $exception)
    {
        $r = new HttpResponse();
        $r->setStatus(404);
        $r->setContent($exception->getMessage());
        return $r;
    }
}