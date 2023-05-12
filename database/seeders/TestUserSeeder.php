<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, Permission, DegreeProgram, Faculty};
use App\Classes\{Permission as PermissionClass};

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create super admin
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'email' => 'mumerfarooqlablnet01@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'user_role' => 'admin'
        ]);        
    }
}
