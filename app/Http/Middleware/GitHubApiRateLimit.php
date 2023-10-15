<?php

namespace App\Http\Middleware;

use Closure;

class GitHubApiRateLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = Http::get('https://api.github.com/rate_limit');
        $remainingRequests = $response->header('X-RateLimit-Remaining');
        $resetTimestamp = $response->header('X-RateLimit-Reset');

        if ($remainingRequests <= 1) {
            $timeToWait = $resetTimestamp - now()->timestamp + 5;
            sleep($timeToWait);
        }

        return $next($request);
    }
