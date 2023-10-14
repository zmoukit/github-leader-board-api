<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\User;

class BaseTestController extends TestCase
{
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $credentials = ["email" => "john@example.com", "password" => "password"];

        $this->token = auth()->guard('api')->attempt($credentials);
    }

    protected function getToken()
    {
        return $this->token;
    }
}
