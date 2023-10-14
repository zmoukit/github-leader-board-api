<?php

namespace App\Http\Controllers;

use App\Http\Resources\Repository;
use App\Services\GitHubService;
use App\Http\Resources\RepositoryCollection;
use DateTime;

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

    /**
     * Fetch the details of the selected GitHub repository
     *
     * @param  string  $owner — The repository owner
     * @param  string  $repo — The repository name
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function selectRepository($owner, $repo)
    {
        $response = $this->gitHubService->fetchRepository($owner, $repo);

        if ($response->successful()) {
            $aRepository = new Repository($response->json());
            return $this->apiResponse(self::SUCCESS, "Repository fetched successfully", 200, [$aRepository]);
        }

        $aResponseAsArray = $response->json();
        $errorMessage = $this->gitHubService->getMessageFromGitHubApiResponse($aResponseAsArray);

        return $this->apiResponse(self::ERROR, $errorMessage, $response->status(), $aResponseAsArray);
    }

    /**
     * Count pull request and pull request reviews
     *
     * @param  string  $owner — The repository owner
     * @param  string  $repo — The repository name
     * @param null|string $from — The date we use in the filter (format: yyyymmmdd)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function countReviewsAndPullRequests($owner, $repo, $from = null)
    {
        $response = $this->gitHubService->countReviewsAndPullRequests($owner, $repo, $from);

        if ($response->successful()) {
            $pullRequests = $response->json();

            // Process the data and filter by last month
            if (is_null($from)) {
                $lastMonth = now()->subMonth();
                $timestamp = $lastMonth->timestamp;
            } else {
                if (!$this->gitHubService->validateDateFormat($from)) {
                    return $this->apiResponse(self::ERROR, "Please enter the date in the format: yyyymmdd (e.g., 20231013 for October 13, 2023).", 400, [$from]);
                }

                $datetime = DateTime::createFromFormat('Ymd', $from);
                $timestamp = $datetime->getTimestamp();
            }

            $filteredData = collect($pullRequests)->filter(function ($pullRequest) use ($timestamp) {
                return strtotime($pullRequest['created_at']) >= $timestamp;
            });

            // Count PRs reviewed and PRs created
            $contributors = $filteredData->groupBy('user.login')->map(function ($groupedData) {
                $prsCount = $groupedData->count();
                $prsReviewsCount = $groupedData->count(); //$groupedData->where('state', 'opened')->count();

                return [
                    'prs_count' => $prsCount,
                    'prs_reviews_count' => $prsReviewsCount,
                ];
            });

            $aSortedContributors = $contributors->sortByDesc('prs_reviews_count');

            return $this->apiResponse(self::SUCCESS, "Repository fetched successfully", 200, $aSortedContributors);

            return $aSortedContributors;
        }


        $aResponseAsArray = $response->json();
        $errorMessage = $this->gitHubService->getMessageFromGitHubApiResponse($aResponseAsArray);

        return $this->apiResponse(self::ERROR, $errorMessage, $response->status(), $aResponseAsArray);
    }
}
