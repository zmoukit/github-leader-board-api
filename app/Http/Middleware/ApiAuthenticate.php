<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class ApiAuthenticate
{
    const INVALID_TOKEN = "A valid user token must be provided";
    const WRONG_SEGMENTS_NUMBER = "Wrong number of segments";
    const MALFORMED_TOKEN = "Malformed token";
    const METHOD_NOT_ALLOWED = "Method Not Allowed";
    const BAD_REQUEST = "Bad Request";
    const RESTRICTED = "You are restricted to access the site.";

    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        //check if authorization exists in request header
        if (!$request->hasHeader('Authorization')) {
            return response()->json(
                array(
                    'status' => "error",
                    'code' => 400,
                    'message' => self::BAD_REQUEST,
                    'errors' => array(
                        self::BAD_REQUEST
                    )
                ),
                400
            );
        }


        $authorization = $request->header('Authorization');
        $header = explode(" ", $authorization);
        if (!isset($header[1]) || trim($header[1]) == "") {
            return response()->json(
                array(
                    'status' => "error",
                    'code' => 400,
                    'message' => self::BAD_REQUEST,
                    'errors' => array(
                        self::BAD_REQUEST
                    )
                ),
                400
            );
        }

        //validate token structure
        $token = $header[1];
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return response()->json(
                array(
                    'status' => "error",
                    'code' => 400,
                    'message' => self::WRONG_SEGMENTS_NUMBER,
                    'errors' => array(
                        self::WRONG_SEGMENTS_NUMBER
                    )
                ),
                400
            );
        }

        $parts = array_filter(array_map('trim', $parts));
        if (count($parts) !== 3 || implode('.', $parts) !== $token) {
            return response()->json(
                array(
                    'status' => "error",
                    'code' => 401,
                    'message' => self::MALFORMED_TOKEN,
                    'errors' => array(
                        self::MALFORMED_TOKEN
                    )
                ),
                401
            );
        }

        if ($this->auth->guard('api')->guest()) {
            return response()->json(
                array(
                    'status' => "error",
                    'code' => 401,
                    'message' => "Unauthorized",
                    'errors' => array(
                        "Unauthorized"
                    )
                ),
                401
            );
        }

        return $next($request);
    }
}
