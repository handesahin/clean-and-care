<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    const CARE = "Bakım";
    const CLEAN = "Temizlik";
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services = [
            [
                'name' => 'Yıllık Bakım',
                'type' => self::CARE,
                'price' => 3000.0,
                'published' => 1
            ],
            [
                'name' => 'Lastik Bakımı',
                'type' => self::CARE,
                'price' => 800.0,
                'published' => 1
            ],
            [
                'name' => 'Akü Bakımı',
                'type' => self::CARE,
                'price' => 1200.0,
                'published' => 1
            ],
            [
                'name' => 'Dış Yıkama',
                'type' => self::CLEAN,
                'price' => 100.0,
                'published' => 1
            ],
            [
                'name' => 'İç-Dış Yıkama',
                'type' => self::CLEAN,
                'price' => 200.0,
                'published' => 1
            ],
            [
                'name' => 'Dezenfeksiyon',
                'type' => self::CLEAN,
                'price' => 400.0,
                'published' => 0
            ],
            [
                'name' => 'Detaylı İç Yıkama',
                'type' => self::CLEAN,
                'price' => 250.0,
                'published' => 1
            ],

        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
