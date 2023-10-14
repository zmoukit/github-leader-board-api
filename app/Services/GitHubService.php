<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GitHubService extends BaseService
{
    /**
     * Listing user GitHub repositories for api
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRepositories()
    {
        return $this->sendGetRequestToGitHubApi('user/repos');
    }

    /**
     * Fetch the details of the selected GitHub repository
     *
     * @param  string  $owner — The repository owner
     * @param  string  $repo — The repository name
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchRepository(string $owner, string  $repo)
    {
        return $this->sendGetRequestToGitHubApi("repos/{$owner}/{$repo}");
    }

    /**
     * Get reponse message from github
     */
    public function getMessageFromGitHubApiResponse($aResponseAsArray)
    {
        return (isset($aResponseAsArray['message']))
            ? $aResponseAsArray['message']
            : "Failed to fetch data from GitHub API";
    }

    /**
     * Get GitHub API base url
     */
    public function getGitHubApiBaseUrl()
    {
        $config = config('github');

        return (isset($config['base_url']))
            ? $config['base_url']
            : 'https://api.github.com/';
    }

    /**
     * Get GitHub personal token
     */
    private function getGitHubToken()
    {
        return env('GITHUB_TOKEN');
    }

    /**
     * Send Get Request to GitHub Api
     */
    private function sendGetRequestToGitHubApi(string $endpoint)
    {
        $gitHubToken = $this->getGitHubToken();
        $baseUrl = $this->getGitHubApiBaseUrl();

        return Http::withToken($gitHubToken)
            ->get($baseUrl . $endpoint);
    }
}
