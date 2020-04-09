<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Str;

class ImportDistricts extends Command
{

    protected const UF_MAP = [
        12 => "AC",
        27 => "AL",
        16 => "AM",
        13 => "AP",
        29 => "BA",
        23 => "CE",
        53 => "DF",
        32 => "ES",
        52 => "GO",
        21 => "MA",
        51 => "MT",
        50 => "MS",
        31 => "MG",
        15 => "PA",
        25 => "PB",
        41 => "PR",
        26 => "PE",
        22 => "PI",
        24 => "RJ",
        43 => "RN",
        33 => "RO",
        11 => "RS",
        14 => "RR",
        42 => "SC",
        35 => "SE",
        28 => "SP",
        17 => "TO"
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:importDistricts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the states and districts from CSV file.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $csvPath = database_path('districts.csv');

        if (!file_exists($csvPath)) {
            $this->error("File `$csvPath` not found!");
            return 1;
        }
        $this->info("Importing districts from `$csvPath` ...");

        // remove all records from the tables
        DB::table('states')->truncate();
        DB::table('districts')->truncate();

        // initialize the progress bar
        $bar = $this->output->createProgressBar(filesize($csvPath));
        $bar->start();

        // read the file, line by line
        $linesRead = 0;
        $lastUfId = null;
        $fh = fopen($csvPath,"r");
        while ($line = fgets($fh)) {
            $bar->advance(strlen($line));
            $row = Str::of($line)
                ->trim()
                ->trim(';')
                ->explode(';');

            $linesRead++;

            // skip first line
            if ($linesRead === 1) {
                continue;
            }

            $currentUfId = (int) trim($row[0]);
            if ($lastUfId === null || $currentUfId !== $lastUfId) {
                DB::table('states')->insert([
                    'id' => $currentUfId,
                    'uf' => static::UF_MAP[$currentUfId],
                    'name' => trim($row[1]),
                ]);
                $lastUfId = $currentUfId;
            }

            DB::table('districts')->insert([
                'id' => (int) trim($row[10]),
                'municipio' => trim($row[8]),
                'distrito' => trim($row[9]) == '05' ? null : trim($row[11]),
                'state_id' => $currentUfId
            ]);
        }

        echo PHP_EOL;

        $this->info("Imported $linesRead lines ...");

        return 0;
    }
}
