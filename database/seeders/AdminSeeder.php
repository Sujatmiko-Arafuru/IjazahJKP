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
        $admins = [
            [
                'username' => 'AdminD3JKP',
                'name' => 'Admin D3 Keperawatan',
                'password' => Hash::make('D3JKP#123'),
                'email' => 'admind3jkp@sijitu.local',
            ],
            [
                'username' => 'AdminStrJKP',
                'name' => 'Admin Sarjana Terapan Keperawatan',
                'password' => Hash::make('StrJKP#123'),
                'email' => 'adminstrjkp@sijitu.local',
            ],
            [
                'username' => 'AdminNersJKP',
                'name' => 'Admin Profesi Ners Keperawatan',
                'password' => Hash::make('NersJKP#123'),
                'email' => 'adminnersjkp@sijitu.local',
            ],
        ];

        foreach ($admins as $adminData) {
            Admin::updateOrCreate(
                ['username' => $adminData['username']],
                $adminData
            );
        }
    }
}
