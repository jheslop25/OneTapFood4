<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CropAcademicData;
use App\Models\CropGrowingData;
use App\Models\CropUses;

class CropSearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:crops';

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
        $crops = CropGrowingData::where('use_main', 'food & beverage')->where('life_span', 'annual')->get();

        $this->info(count($crops));
        $this->info(count($crops->where('use_part', 'leaves')) . ' leaves');
        $this->info(count($crops->where('use_part', 'fruits')) . ' fruits');
        $this->info(count($crops->where('use_part', 'roots')) . ' roots');

        $this->info(count($crops->where('use_part', 'seeds')) . ' seeds');
        $this->info(count($crops->where('use_part', 'stems')) . ' stems');
        $this->info(count($crops->where('use_part', 'seedlings')) . ' seedlings');
        $this->info(count($crops->where('use_part', 'bulbs')) . ' bulbs');
        // $this->info(count($crops->unique('use_part')));



        foreach ($crops->where('use_part', 'fruits') as $crop){
            $this->info($crop->crop_code . ' ' . $crop->species . ' ' . $crop->cycle_min . ' ' . $crop->cycle_max);
            // $ad = CropAcademicData::where('crop_code', $crop->crop_code)->first();
            // $this->info($ad->common_names);
        }
    }
}
