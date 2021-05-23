<?php

namespace App\Console\Commands;

use App\Models\CropAcademicData;
use App\Models\CropUses;
use Illuminate\Console\Command;
use ParseCsv\Csv;

class ImportFarmingData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'farm:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $csvGrowingData = new Csv();

        $csvGrowingData->auto('fao_cropdata.csv');

        $this->info(count($csvGrowingData->data));

        foreach($csvGrowingData->data as $item){

        }

        $csvAcademicData = new Csv();

        $csvAcademicData->auto('crop_view_data.csv');

        $this->info(count($csvAcademicData->data));

        foreach($csvAcademicData->data as $item){
            $AcademicData = new CropAcademicData();
        }

        $csvUsesData = new Csv();

        $csvUsesData->auto('crop_uses_data.csv');

        $this->info(count($csvUsesData->data));

        foreach($csvUsesData->data as $item){
            $CropUse = new CropUses();

            $CropUse->crop_code = intval($item['crop_code']);
            $CropUse->main_use = $item['Main use'];
            $CropUse->detailed_use = $item['Detailed use'];
            $CropUse->used_part = $item['Used part'];

            $CropUse->save();
        }
    }
}
