<?php

namespace Database\Seeders;

use App\Models\Reader;
use Illuminate\Database\Seeder;

class ReaderSeeder extends Seeder
{
    public function run(): void
    {
        $readers = [
            ['name' => 'Jānis Bērziņš', 'email' => 'janis.berzins@example.com'],
            ['name' => 'Ilze Kalniņa', 'email' => 'ilze.kalnina@example.com'],
            ['name' => 'Pēteris Ozols', 'email' => 'peteris.ozols@example.com'],
            ['name' => 'Anna Liepa', 'email' => 'anna.liepa@example.com'],
            ['name' => 'Mārtiņš Vītols', 'email' => 'martins.vitols@example.com'],
            ['name' => 'Zane Siliņa', 'email' => 'zane.silina@example.com'],
            ['name' => 'Kārlis Eglītis', 'email' => 'karlis.eglitis@example.com'],
            ['name' => 'Līga Bērziņa', 'email' => 'liga.berzina@example.com'],
            ['name' => 'Andris Kļaviņš', 'email' => 'andris.klavins@example.com'],
            ['name' => 'Dace Priede', 'email' => 'dace.priede@example.com'],
        ];

        foreach ($readers as $reader) {
            Reader::create($reader);
        }
    }
}
