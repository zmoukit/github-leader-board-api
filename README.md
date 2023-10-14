## GitHub Leaderboard API For Laravel 7

GitHub Leaderboard API is a project built with Laravel and serves as a solution for the [YouCan Shop coding challenge](<https://github.com/youcan-shop/coding-challenges/tree/master/Senior%20Software%20Engineer%20-%20Backend%20(PHP)>), utilizes the GitHub API to create a leaderboard for contributors to a repository. such as:

## Installation

To get started with GitHub Leaderboard API, follow these steps:

1 - Clone the repository to your local machine:

```bash
git clone https://github.com/zmoukit/github-leader-board-api.git
```

2 - Install the project dependencies:

```bash
composer install
```

3 - Change database configuration in you `.env` file

```bash
DB_DATABASE=your-database-name
DB_USERNAME=your-database-username
DB_PASSWORD=your-database-password
```

4 - Configure your GitHub API token by adding it to the .env file:

```bash
GITHUB_TOKEN=your-github-personal-access-token-here
```

5 - Run migration

```
php artisan migrate
```

6 - Start the Laravel development server:

```
php artisan serve
```

## Main Features

-   `GET api/v1/github/repos`, List Repositories: Users can list their repositories.

-   `GET api/v1/github/repos/{owner}/{repo}`, Select Repository: Users can choose a repository from the list.
