<?php

namespace App\Console\Commands;

use App\Models\Recipe;
use App\Services\CookBookApi;
use App\Services\NutriSApi;
use App\Services\ParseApi;
use App\Services\SpoonApi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
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
        $cookbook = new CookBookApi();
        $parser = new ParseApi();
        $NutriS = new NutriSApi();

        $spoon->setTotalReturn(1);

        $basicSearch = $spoon->search('chicken', 'onions', 40, 0);

        foreach ($basicSearch['results'] as $basic) {

            $detailed = $spoon->getInfo($basic['id']);

            foreach ($detailed as $detail) {
                $recipeJson = $cookbook->getRecipe($detail['sourceUrl']);

                $recipe = new Recipe();

                // Set recipe name
                $recipe->name = $recipeJson[0]['name']; 

                // set description
                $recipe->description = $recipeJson[0]['description']; 
                
                // set source url
                $recipe->source_url = $detail['sourceUrl']; 

                // check if data contains prep time
                if ($recipeJson[0]['prep-time'] != null) { 


                    //regex the data to get a useable time formate
                    $prepRegRes = Regex::match('/(\d*)H(\d*)M/', $recipeJson[0]['prep-time']); // regex this

                    $prep = intval($prepRegRes->group(1)) * 60 + floatval($prepRegRes->group(2));
                } else {
                    $prep = 0;
                }

                // set prep time
                $recipe->prep_time = $prep; 

                // regex cook time param
                $cookRegRes = Regex::match('/(\d*)H(\d*)M/', $recipeJson[0]['cook-time']); // regex this

                // set the regex results to a variable we can use
                $cook = intval($cookRegRes->group(1)) * 60 + floatval($cookRegRes->group(2));


                // set cook time
                $recipe->cook_time = $cook;


                //set total time
                $recipe->total_time = $cook + $prep;

                // regex portions field from data
                $portions = intval(Regex::match('/(\d*).*/', $recipeJson[0]['yield'])->group(1)); // MOAR regex

                // set yield
                $recipe->yield = $portions;

                // set dish types
                $recipe->meal_types = $recipeJson[0]['dishTypes'];


                // set diets 
                $recipe->diets = $recipeJson[0]['diets'];

                // set cuisines
                $recipe->cuisines = $recipeJson[0]['cuisines'];

                // set spoon id for future reference i.e wine recommendation.
                $recipe->spoon_id = $recipeJson[0]['id'];

                //set vegetarian bool
                $recipe->vegetarian = $recipeJson[0]['vegetarian'];

                //set vegan bool
                $recipe->vegan = $recipeJson[0]['vegan'];

                // set gluten free bool
                $recipe->gluten_free = $recipeJson[0]['glutenFree'];

                //set dairy free bool
                $recipe->dairy_free = $recipeJson[0]['dairyFree'];

                //parse ingredients from their strings this was always the cunty bit
                $parsed = $parser->parse($recipeJson[0]['ingredients']);

                foreach ($parsed['results'] as $item) {
                    $this->info($item['ingredientRaw']);
                    $query = $item['ingredientParsed']['quantity'] . ' ' . $item['ingredientParsed']['unit'] . ' ' . Regex::replace('/(\s)/', '-', $item['ingredientParsed']['product'])->result();
                    $this->info($query);
                    $ingredData = $NutriS->search($query);
                    if (isset($ingredData['foods'][0])) {
                        $this->info($ingredData['foods'][0]['food_name']);
                    } else {
                        Log::debug("Ingredient not for for query" . $query);
                    }
                }
            }
        }
    }
}
