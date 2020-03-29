<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EntityUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        DB::table('entity_user')->insert([
            [
                'entity_id' => 1, // Castleblack
                'user_id' => 1, // Jon
            ],
            [
                'entity_id' => 2, // Winterfel
                'user_id' => 1, // Jon
            ],
            [
                'entity_id' => 3, // Storms End
                'user_id' => 2, // Renly
            ],
            [
                'entity_id' => 4, // Citadel
                'user_id' => 2, // Renly
            ],
            [
                'entity_id' => 5, // Eyrie
                'user_id' => 1, // Jon
            ],
            [
                'entity_id' => 5, // Eyrie
                'user_id' => 2, // Renly
            ],
        ]);
    }
}
