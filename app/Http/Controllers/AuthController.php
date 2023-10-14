<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;

class AuthController extends ApiBaseController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        try {
            $credentials = request(['email', 'password']);

            //******************************* Validation **********************/
            $validator = $this->validator($credentials);
            if ($validator->fails()) {
                return $this->apiResponse('error', 'Invalid Data.', 400, $validator->errors());
            }
            //******************************* Validation **********************/

            if (!$token = auth()->guard('api')->attempt($credentials)) {
                return $this->apiResponse('error', 'The provided credential is invalid.', 401);
            }

            $aData = [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ];

            return $this->apiResponse(
                "success",
                "",
                200,
                $aData
            );
        } catch (\Throwable $th) {
            return $this->apiResponse('error', 'Internal Server Error.', 500);
        }
    }

    private function validator($aData)
    {
        return Validator::make(
            $aData,
            [
                'email' => 'required|email|string',
                'password' => 'required|string',
            ],
            [],
            [
                'email' => 'Email',
                'password' => 'Password',
            ]
        );
    }
}
