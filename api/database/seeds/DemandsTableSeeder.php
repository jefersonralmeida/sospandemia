<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DemandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        DB::table('demands')->insert([
            [
                // 1
                'title' => 'Demanda 1',
                'text' => 'Descrição da demanda 1 (Castleblack)',
                'entity_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                // 2
                'title' => 'Demanda 2',
                'text' => 'Descrição da demanda 2 (Castleblack)',
                'entity_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
