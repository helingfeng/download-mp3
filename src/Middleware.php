<?php

namespace DownloadMp3;

use Psr\Http\Message\RequestInterface;

class Middleware
{
    public static function addHeader($header, $value): \Closure
    {
        return function (callable $handler) use ($header, $value) {
            return function (
                RequestInterface $request,
                array            $options
            ) use ($handler, $header, $value) {
                $request = $request->withHeader($header, $value);
                return $handler($request, $options);
            };
        };
    }
}
