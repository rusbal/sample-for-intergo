<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        factory(App\User::class, 3)->create()->each(function ($u) {
//            $u->amazonMws()->save(factory(App\AmazonMws::class)->make());
//        });
        factory(App\User::class, 3)->create();
    }
}
