<?php

use App\Enums\VisibilityStatus;
use App\Models\BackpackUser;
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
        $user= BackpackUser::create(
            [
                'name' => 'Mr. admin',
                'email' => 'admin@mail.com',
                'password' => bcrypt('123456'),
                'status' => VisibilityStatus::ACTIVE
            ]
        );
    }
}
