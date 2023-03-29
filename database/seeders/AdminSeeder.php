<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Constant\Constants;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "first_name" =>  "Car Auction",
            "last_name" => "Admin",
            "email" => "admin@mailinator.com",
            "phone" =>   "9856321470",
            "phone_ext" =>   "9856321470",
            "mobile" =>   "9856321470",
            "contact_preference" => "first",
            "password" => Hash::make('Mind@123'),
            "role_id" => Constants::TYPE_ADMIN,
            "status" => Constants::STATE_ACTIVE,
            "type_id" => Constants::STATE_ACTIVE 
        ]);
    }
}
