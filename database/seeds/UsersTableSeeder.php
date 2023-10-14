<?php

use Illuminate\Database\Seeder;

use App\User;

// php artisan db:seed --class=UsersTableSeeder
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a user using the factory
        factory(User::class)->create();
    }
}
