<?php

namespace Tests\Feature\Controllers;

use Tests\Feature\Controllers\BaseTestController;

class GitHubControllerTest extends BaseTestController
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test list repositories success flow.
     *
     * @return void
     */
    public function test_list_repositories_success()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->json("get", "/api/v1/github/repos/");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Repositories fetched successfully',
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'data',
            ]);
    }

    /**
     * Test list repositories success flow.
     *
     * @return void
     */
    public function test_list_repositories_failed()
    {
        $response = $this->withHeaders([
            'Authorization' => $this->getToken()
        ])->json("get", "/api/v1/github/repos/");

        $response->assertStatus(400)
            ->assertJsonStructure([
                'status',
                'code',
                'message',
                'errors',
            ])
            ->assertJson([
                'status' => 'error',
                'code' => 400,
                'message' => 'Bad Request',
                'errors' => ["Bad Request"]
            ]);
    }
}
