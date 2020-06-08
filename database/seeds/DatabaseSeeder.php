<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        //factory(App\Comment::class, 20)->create();

        $this->call(UserSeeder::class);
        $this->call(KeywordSeeder::class);
        $this->call(PhotoSeeder::class);
        $this->call(PhotoKeywordsSeeder::class);
    }
}
