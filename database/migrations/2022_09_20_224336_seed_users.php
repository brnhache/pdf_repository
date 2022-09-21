<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Insert seed data into users table
        $user_1 = new User();
        $user_1->name = 'User A';
        $user_1->email = 'user_a@email.com';
        $user_1->password = Hash::make('thisisapassword');
        $user_1->save();

        $user_2 = new User();
        $user_2->name = 'User B';
        $user_2->email = 'user_b@email.com';
        $user_2->password = Hash::make('thisisalsoapassword');;
        $user_2->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Remove the seeded users
        if (Schema::hasTable('users')) {
            User::where('email', 'user_a@email.com')->delete();
            User::where('email', 'user_b@email.com')->delete();
        }
    }
};
