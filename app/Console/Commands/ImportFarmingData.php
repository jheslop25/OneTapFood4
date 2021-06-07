<?php

namespace App\Console\Commands;

use App\Models\CropAcademicData;
use App\Models\CropGrowingData;
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

        $this->info(var_dump($csvGrowingData->data[0]));

        foreach($csvGrowingData->data as $item){
            $growData = new CropGrowingData();
            $growData->crop_code = $item['crop_code'];
            $growData->species = $item['species'];
            $growData->life_form = $item['Life.form'];
            $growData->life_span = $item['Life.span'];
            $growData->habit = $item['Habit'];
            $growData->physiology = $item['Physiology'];
            $growData->category = $item['Category'];
            $growData->attributes = $item['Plant.attributes'];
            $growData->temp_opt_max = $item['Temp_Opt_Max'];
            $growData->temp_opt_min = $item['temp_opt_min'];
            $growData->temp_abs_max = $item['Temp_Abs_Max'];
            $growData->temp_abs_min = $item['Temp_Abs_Min'];
            $growData->rain_abs_max = $item['Rain_Abs_Max'];
            $growData->rain_abs_min = $item['Rain_Abs_Min'];
            $growData->rain_opt_max = $item['Rain_Opt_Max'];
            $growData->rain_opt_min = $item['Rain_Opt_Min'];
            $growData->lat_opt_max = $item['Lat_Opt_Max'];
            $growData->lat_opt_min = $item['Lat_Opt_Min'];
            $growData->lat_abs_max = $item['Lat_Abs_Max'];
            $growData->lat_abs_min = $item['Lat_Abs_Min'];
            $growData->alt_opt_max = $item['Alt_Opt_Max'];
            $growData->alt_opt_min = $item['Alt_Opt_Min'];
            $growData->alt_abs_max = $item['Alt_Abs_Max'];
            $growData->alt_abs_min = $item['Alt_Abs_Min'];
            $growData->ph_opt_max = $item['pH_Opt_Max'];
            $growData->ph_opt_min = $item['pH_Opt_Min'];
            $growData->ph_abs_max = $item['pH_Abs_Max'];
            $growData->ph_abs_min = $item['pH_Abs_Min'];
            $growData->light_opt_max = $item['Light_Opt_Max'];
            $growData->light_opt_min = $item['Light_Opt_Min'];
            $growData->light_abs_max = $item['Light_Abs_Max'];
            $growData->light_abs_min = $item['Light_Abs_Min'];
            $growData->depth_opt = $item['Depth_Opt'];
            $growData->depth_abs = $item['Depth_Abs'];
            $growData->soil_texture_opt = $item['Texture_Ops'];
            $growData->soil_texture_abs = $item['Texture_Abs'];
            $growData->fertility_opt = $item['Fertility_Ops'];
            $growData->fertility_abs = $item['Fertility_Abs'];
            $growData->al_toxicity_opt = $item['Al_Toxicity_Opt'];
            $growData->al_toxicity_abs = $item['Al_Toxicity_Abs'];
            $growData->salinity_opt = $item['Salinity_Ops'];
            $growData->salinity_abs = $item['Salinity_Abs'];
            $growData->drainage_opt = $item['drainage_opt'];
            $growData->drainage_abs = $item['drainage_abs'];
            $growData->climate_zone = $item['Climate.Zone'];
            $growData->photo_period = $item['photoperiod'];
            $growData->killing_temp_during_rest = $item['Killing.temp..during.rest'];
            $growData->killing_temp_early_growth = $item['Killing.temp..early.growth'];
            $growData->abiotic_tolerance = $item['Abiotic.toler.'];
            $growData->abiotic_suscept = $item['Abiotic.suscept.'];
            $growData->introduction_risks = $item['Introduction.risks.'];
            $growData->product_system = $item['Product..system'];
            $growData->cropping_system = $item['Cropping.system'];
            $growData->subsystem = $item['Subsystem'];
            $growData->companion_species = $item['Companion.species'];
            $growData->level_of_mechanization = $item['Level.of.mechanization'];
            $growData->labour_intensity = $item['Labour.intensity'];
            $growData->cycle_min = $item['cycle_min'];
            $growData->cycle_max = $item['cycle_max'];
            $growData->use_main = $item['use.main'];
            $growData->use_detailed = $item['use.detailed'];
            $growData->use_part = $item['use.part'];
            $growData->datasheet_url = $item['datasheet_url'];

            $growData->save();
        }

        $csvAcademicData = new Csv();

        $csvAcademicData->auto('crop_view_data.csv');

        $this->info(var_dump($csvAcademicData->data[0]));

        foreach($csvAcademicData->data as $item){
            $AcademicData = new CropAcademicData();
            $AcademicData->crop_code = $item['Ecocrop_code'];
            $AcademicData->authority = $item['Authority'];
            $AcademicData->family = $item['Family'];
            $AcademicData->synonyms = $item['Synonyms'];
            $AcademicData->common_names =  $item['Common_names'];
            $AcademicData->editor = $item['Editor'];
            $AcademicData->notes = $item['Notes'];
            $AcademicData->sources = $item['Sources'];

            $AcademicData->save();
        }

        $csvUsesData = new Csv();

        $csvUsesData->auto('crop_uses_data.csv');

        $this->info(var_dump($csvUsesData->data[0]));

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
