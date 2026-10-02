<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@poltekkes-denpasar.ac.id'],
            [
                'name' => 'Administrator Poltekkes Denpasar',
                'password' => Hash::make('admin123'), // Change as appropriate
            ]
        );
    }
}
