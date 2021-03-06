<?php

use App\User;
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
        DB::table('users')->truncate();
        DB::table('amazon_mws')->truncate();
        DB::table('amazon_request_queues')->truncate();
        DB::table('amazon_request_histories')->truncate();
        DB::table('amazon_merchant_listings')->truncate();

        $this->call(UsersTableSeeder::class);
        $this->setFirstUserToRaymond();
    }

    private function setFirstUserToRaymond()
    {
        $user = User::first();
        $user->name = "Raymond Usbal";
        $user->email = "raymond@philippinedev.com";
        $user->password = bcrypt('default');
        $user->save();
        echo "First User: 'Raymond Usbal' set\n";
    }
}
