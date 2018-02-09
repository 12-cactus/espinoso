<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Application;
use Espinaland\Responses\ReplyResponses;

class ResolveDelivery
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->app->bind('reply', function () use ($request) {
            $deliveryName = "{$request->input('delivery')}-delivery";

            return new ReplyResponses(resolve($deliveryName));
        });

        return $next($request);
    }
}
