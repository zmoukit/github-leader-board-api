<?php

namespace Tests\Feature\Controllers;

use Tests\Feature\Controllers\BaseTestController;

class AuthControllerTest extends BaseTestController
{
    const LOGIN_ENDPOINT = "/api/v1/auth/login";

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test login success flow.
     *
     * @return void
     */
    public function test_login_success()
    {
        $response = $this->json(
            "post",
            self::LOGIN_ENDPOINT,
            [
                "email" => "john@example.com",
                "password" => "password"
            ]
        );

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'code' => 200,
                'message' => '',
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'data',
            ])
            ->assertJsonPath('data.token_type', 'bearer');
    }

    /**
     * Test login email invalid.
     *
     * @return void
     */
    public function test_login_missing_email()
    {
        $response = $this->json(
            "post",
            self::LOGIN_ENDPOINT,
            [
                "password" => "password"
            ]
        );

        $response->assertStatus(400)
            ->assertJson([
                'status' => 'error',
                'code' => 400,
                'message' => 'Invalid Data.',
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'errors',
            ])
            ->assertJsonPath('errors.email.0', 'The Email field is required.');
    }

    /**
     * Test login email invalid.
     *
     * @return void
     */
    public function test_login_invalid_email()
    {
        $response = $this->json(
            "post",
            self::LOGIN_ENDPOINT,
            [
                "email" => "john",
                "password" => "password"
            ]
        );

        $response->assertStatus(400)
            ->assertJson([
                'status' => 'error',
                'code' => 400,
                'message' => 'Invalid Data.',
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'errors',
            ])
            ->assertJsonPath('errors.email.0', 'The Email must be a valid email address.');
    }

    /**
     * Test login password invalid.
     *
     * @return void
     */
    public function test_login_missing_password()
    {
        $response = $this->json(
            "post",
            self::LOGIN_ENDPOINT,
            [
                "email" => "john@example.com"
            ]
        );

        $response->assertStatus(400)
            ->assertJson([
                'status' => 'error',
                'code' => 400,
                'message' => 'Invalid Data.',
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'errors',
            ])
            ->assertJsonPath('errors.password.0', 'The Password field is required.');
    }

    /**
     * Test login failed wrong password.
     *
     * @return void
     */
    public function test_login_failed_wrong_password()
    {
        $response = $this->json(
            "post",
            self::LOGIN_ENDPOINT,
            [
                "email" => "john@example.com",
                "password" => "wrongpassword"
            ]
        );

        $response->assertStatus(401)
            ->assertJsonStructure([
                'status',
                'message',
                'code',
            ])
            ->assertJson([
                'status' => 'error',
                'code' => 401,
                'message' => 'The provided credential is invalid.',
            ]);
    }

    /**
     * Test login failed wrong email.
     *
     * @return void
     */
    public function test_login_failed_wrong_email()
    {
        $response = $this->json(
            "post",
            self::LOGIN_ENDPOINT,
            [
                "email" => "wrong@example.org",
                "password" => "password"
            ]
        );

        $response->assertStatus(401)
            ->assertJsonStructure([
                'status',
                'message',
                'code',
            ])
            ->assertJson([
                'status' => 'error',
                'code' => 401,
                'message' => 'The provided credential is invalid.',
            ]);
    }
}
