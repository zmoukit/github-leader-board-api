<?php

namespace App\Http\Controllers;

use App\Http\Resources\Repository;
use App\Services\GitHubService;
use App\Http\Resources\RepositoryCollection;

class GitHubController extends ApiBaseController
{
    private $gitHubService;

    public function __construct(
        GitHubService $gitHubService
    ) {
        parent::__construct();
        $this->gitHubService = $gitHubService;
    }

    /**
     * List user's repositories.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listRepositories()
    {
        $response = $this->gitHubService->getRepositories();

        if ($response->successful()) {
            $aRepositories = new RepositoryCollection($response->json());
            return $this->apiResponse(self::SUCCESS, "Repositories fetched successfully", 200, $aRepositories);
        }

        $aResponseAsArray = $response->json();
        $errorMessage = $this->gitHubService->getMessageFromGitHubApiResponse($aResponseAsArray);

        return $this->apiResponse(self::ERROR, $errorMessage, $response->status(), $aResponseAsArray);
    }
}
