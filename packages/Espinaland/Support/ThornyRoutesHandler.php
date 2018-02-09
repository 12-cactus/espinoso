<?php

namespace Espinaland\Support;

use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\Response;
use Espinaland\Support\Objects\RequestMessageInterface;

/**
 * Class ManagerRoutesHandle
 * @package Espinaland\Support
 */
class ThornyRoutesHandler
{
    /**
     * @param string $route
     * @param RequestMessageInterface $message
     * @param string $sender
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(string $route,
                           RequestMessageInterface $message,
                           string $sender = 'telegram'): Response
    {
        $data = [
            'delivery' => $sender,
            'message'  => $message->raw(),
            '_token'   => csrf_token()
        ];

        $request = Request::create("/espi{$route}", 'PUT', $data, [], [], [
            'HTTP_Accept' => 'application/json',
        ]);

        $response = app()->handle($request);

        try {
            $response->original->apply();
            return response('OK');
        } catch (\Exception $e) {
            logger('****************** ERROR *****************');
            logger($e->getMessage());
            logger($e->getTrace());
            return response('ERROR', 512);
        }
    }
}