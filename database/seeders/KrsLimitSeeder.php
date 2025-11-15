<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class KrsLimitSeeder extends Seeder
{
    public function run()
    {
        Setting::setMaxSks(24);
        Setting::setMaxMatakuliah(8);
        
        // Optional: Set other default settings
        Setting::set('krs_status', 'closed', 'Status KRS');
    }
}