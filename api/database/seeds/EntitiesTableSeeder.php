<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EntitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        DB::table('entities')->insert([
            [
                // 1
                'cnpj' => '61005051000109',
                'name' => 'Castleblack',
                'legal_name' => 'Castle of the night watch Ltda',
                'description' => 'The main castle of the night watch',
                'street_address' => 'Middle of the wall',
                'district_id' => 411990505, // ponta grossa
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                // 2
                'cnpj' => '74824798000122',
                'name' => 'Winterfel',
                'legal_name' => 'Winterfel Inc.',
                'description' => 'Castle of Winterfel, ancestral seat of house Stark',
                'street_address' => 'Kings Road, sn',
                'district_id' => 410690205, // curitiba
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                // 3
                'cnpj' => '98715141000195',
                'name' => 'Storm End',
                'legal_name' => 'Storm End Inc.',
                'description' => "Storm's End castle, ancestral seat of house Baratheon",
                'street_address' => 'Shipbreaker Bay, sn',
                'district_id' => 411200905, // jaguariaíva
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                // 4
                'cnpj' => '35577951000102',
                'name' => "Citadel",
                'legal_name' => 'Order of maesters',
                'description' => 'The maesters Citadel',
                'street_address' => 'The citadel Av. 560',
                'district_id' => 411370005, // londrina
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                // 5
                'cnpj' => '33866542000109',
                'name' => "Eyrie",
                'legal_name' => 'The Eyrie Andals Inc.',
                'description' => 'The Eyrie Castle, ancestral seat of house Arryn',
                'street_address' => 'Mountains of the Moon, sn',
                'district_id' => 230350105, // cascavel
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
