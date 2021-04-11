<?php

namespace App\Console\Commands;

use App\Services\CookBookApi;
use App\Services\NutriSApi;
use App\Services\ParseApi;
use App\Services\SpoonApi;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Spatie\Regex\Regex;

class AddRecipies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recipe:add';

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
        $spoon = new SpoonApi('whole30', null, 'main course');

        $spoon->setTotalReturn(1);
    
        $basic = $spoon->search('chicken', 'onions', 40, 0);
        $this->info('hello??');
        // $this->info(var_dump($basic));
        $detailed = $spoon->getInfo($basic['results'][0]['id']);
        
        // $this->info(var_dump($detailed[0]['dishTypes']));
        // $this->info(var_dump($detailed[0]['extendedIngredients'][0]));

        $cookbook = new CookBookApi();

        $recipe = $cookbook->getRecipe($detailed[0]['sourceUrl']);

        $this->info(var_dump($recipe[0]['ingredients']));

        $parser = new ParseApi();

        $parsed = $parser->parse($recipe[0]['ingredients']);

        // $this->info(var_dump($parsed['results'][0]));

        $NutriS = new NutriSApi();

        foreach ($parsed['results'] as $item){
            $this->info($item['ingredientRaw']);
            $query = $item['ingredientParsed']['quantity'] . ' ' . $item['ingredientParsed']['unit'] . ' ' . Regex::replace('/(\s)/', '-', $item['ingredientParsed']['product'])->result();
            $this->info($query);
            $ingredData = $NutriS->search($query);
            if(isset($ingredData['foods'][0])){
                $this->info($ingredData['foods'][0]['food_name']);
                $this->info(var_dump($ingredData['foods'][0]['other_measures']));
            }
            // break;
        }
    }
}
