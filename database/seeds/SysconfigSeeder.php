<?php

use Illuminate\Database\Seeder;
use App\Sysconfig;

class SysconfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Sysconfig::truncate();

        Sysconfig::create([
            'configKey' => 'FEE_PASIEN',
            'configValue' => '5000'
        ]);
        
        Sysconfig::create([
            'configKey' => 'ENDPOINT_URL',
            'configValue' => 'https://testing.masjidjabirsorowajan.com/'
        ]);
    }
}
