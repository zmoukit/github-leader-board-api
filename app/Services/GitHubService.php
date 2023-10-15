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
     * Count pull request and pull request reviews
     *
     * @param  string  $owner — The repository owner
     * @param  string  $repo — The repository name
     * @param null|string $from — The date we use in the filter (format: yyyymmmdd)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function countReviewsAndPullRequests(string $owner, string $repo, string $from = null)
    {
        // Fetch data from the GitHub API
        return $this->sendGetRequestToGitHubApi("repos/{$owner}/{$repo}/pulls");
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

    /**
     * Send Get request to Github api using curl
     */
    private function sendCurlGetRequest(string $endpoint)
    {
        $gitHubToken = $this->getGitHubToken();
        $baseUrl = $this->getGitHubApiBaseUrl();

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $baseUrl . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $gitHubToken
        ));

        $response = curl_exec($ch);

        if ($response === false) {
            echo 'cURL Error: ' . curl_error($ch);
        } else {
            $data = json_decode($response, true);
        }

        curl_close($ch);

        return $data;
    }
}
