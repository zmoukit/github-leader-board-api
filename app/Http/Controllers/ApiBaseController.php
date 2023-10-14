<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class ApiBaseController extends Controller
{
    const SUCCESS = "success";
    const ERROR = "error";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * return a new JSON response instance.
     *
     * @param  string  $status
     * @param  string  $message
     * @param  int  $statusCode
     * @param  array  $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function apiResponse($status, $message, $statusCode = 200, $data = [])
    {
        $response = [
            'status' => $status,
            'code' => $statusCode,
            'message' => $message,
        ];

        if (!empty($data)) {
            if (strtolower($status) == "error")
                $response['errors'] = $data;
            else
                $response['data'] = $data;
        }

        return new JsonResponse($response, $statusCode);
    }
}
