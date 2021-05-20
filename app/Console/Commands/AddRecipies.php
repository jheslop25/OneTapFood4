<?php

namespace App\Console\Commands;

use App\Models\Ingredient;
use App\Models\IngredientConversion;
use App\Models\IngredientNutrient;
use App\Models\Recipe;
use App\Services\CookBookApi;
use App\Services\NutriSApi;
use App\Services\ParseApi;
use App\Services\SpoonApi;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpUnitConversion\Unit;
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
        $spoon = new SpoonApi('', null, 'main course');
        $cookbook = new CookBookApi();
        $parser = new ParseApi();
        $NutriS = new NutriSApi();

        // set save data variable. we'll use this to indicate that we want persist the results
        $save = true;

        $spoon->setTotalReturn(8);

        $basicSearch = $spoon->search('salmon', 'rice', 60, 8);
        $this->info(var_dump($basicSearch));
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

                    $this->info($recipeJson[0]['prep-time']);
                    //regex the data to get a useable time formate
                    $prepRegRes = Regex::match('/(\d*)H(\d*)M/', $recipeJson[0]['prep-time']); // regex this

                    try {
                        $hours = $prepRegRes->group(1);
                    } catch (Exception $e) {
                        Log::debug($e->getMessage());
                        $hours = 0;
                    }

                    try {
                        $mins = $prepRegRes->group(2);
                    } catch (Exception $e) {
                        $mins = 0;
                        Log::debug($e->getMessage());
                    }
                    $prep = intval($hours) * 60 + floatval($mins);
                } else {
                    $prep = 0;
                }

                // set prep time
                $recipe->prep_time = $prep;

                // regex cook time param
                $cookRegRes = Regex::match('/(\d*)H(\d*)M/', $recipeJson[0]['cook-time']); // regex this

                if ($recipeJson[0]['cook-time'] != null) {
                    // set the regex results to a variable we can use

                    try {
                        $cookH = $cookRegRes->group(1);
                    } catch (Exception $e) {
                        Log::debug($e->getMessage());
                        $cookH = 0;
                    }

                    try {
                        $cookM = $cookRegRes->group(2);
                    } catch (Exception $e) {
                        Log::debug($e->getMessage());
                        $cookM = 0;
                    }

                    $cook = intval($cookH) * 60 + floatval($cookM);
                } else {
                    $cook = 0;
                }



                // set cook time
                $recipe->cook_time = $cook;


                //set total time
                $recipe->total_time = $cook + $prep;

                // regex portions field from data
                $portions = intval(Regex::match('/\d/', $recipeJson[0]['yield'])->result()); // MOAR regex

                // set yield
                $recipe->yield = $portions;

                // $this->info($portions);

                // set dish types
                $recipe->meal_types = json_encode($detail['dishTypes']);


                // set diets 
                $recipe->diets = json_encode($detail['diets']);

                // set cuisines
                $recipe->cuisines = json_encode($detail['cuisines']);

                // set spoon id for future reference i.e wine recommendation.
                $recipe->spoon_id = $detail['id'];

                //set vegetarian bool
                $recipe->vegetarian = $detail['vegetarian'];

                //set vegan bool
                $recipe->vegan = $detail['vegan'];

                // set gluten free bool
                $recipe->gluten_free = $detail['glutenFree'];

                //set dairy free bool
                $recipe->dairy_free = $detail['dairyFree'];

                // save the recipe
                if (isset($recipeJson[0]['ingredients'])) {
                    if ($save) {
                        $recipe->save();
                    }
                } else {
                    continue;
                }



                //parse ingredients from their strings this was always the cunty bit
                $parsed = $parser->parse($recipeJson[0]['ingredients']);

                // loop through parsed results and pull full data from NutriS Api
                foreach ($parsed['results'] as $item) {


                    $this->info($item['ingredientRaw']);
                    $query = $item['ingredientParsed']['quantity'] . ' ' . $item['ingredientParsed']['unit'] . ' ' . Regex::replace('/(\s)/', '-', $item['ingredientParsed']['product'])->result();
                    $this->info($query);
                    $ingredData = $NutriS->search($query);

                    // check for result from query
                    if (isset($ingredData['foods'][0])) {
                        $this->info($ingredData['foods'][0]['food_name']);

                        if (!Ingredient::where('name', $ingredData['foods'][0]['food_name'])->exists()) {
                            // run 100 gram standard query 
                            $standardQuery = '100 grams of ' . Regex::replace('/(\s)/', '-', $item['ingredientParsed']['product'])->result();
                            $ingredientStandardData = $NutriS->search($standardQuery);

                            // instantiate new ingredient class;
                            $ingredient = new Ingredient();

                            // set ingredient name
                            $ingredient->name = $ingredData['foods'][0]['food_name'];

                            // set ingredient food group
                            $ingredient->food_group = $ingredData['foods'][0]['food_group'] ?? null;

                            // set ingredient photo 
                            $ingredient->photo_url = $ingredData['foods'][0]['photo']['high_res'] ?? null;

                            // set ingredient thumbnail
                            $ingredient->thumb_url = $ingredData['foods'][0]['photo']['thumbnail'] ?? null;

                            // save the ingredient
                            if ($save) {
                                $ingredient->save();
                            }

                            // loop through other measures array in json and create ingredient conversions
                            foreach ($ingredData['foods'][0]['other_measures'] as $measure) {
                                // instantiate new IngredientConversion class 
                                $conversion = new IngredientConversion();

                                // set measure
                                $conversion->measure = $measure['measure'];

                                // set serving weight in grams
                                $conversion->serving_weight = floatval($measure['serving_weight']);

                                // set quantity
                                $conversion->quantity = floatval($measure['qty']);

                                if ($save) {
                                    // set ingredient id via eloquent relationship method
                                    $conversion->ingredient()->associate($ingredient);

                                    // save conversion
                                    $conversion->save();
                                }
                            }

                            // loop through standardized nutrition data for the ingredient
                            foreach ($ingredientStandardData['foods'][0]['full_nutrient_breakdown'] as $nutrientData) {
                                // instantiate new IngredientNutrient class
                                $nutrient = new IngredientNutrient();

                                // set nutrient name
                                $nutrient->name = $nutrientData['name'];

                                // set nutrient unit
                                $nutrient->unit = $nutrientData['unit'];

                                // set nutrient value
                                $nutrient->value = floatval($nutrientData['value']);

                                if ($save) {
                                    // set ingredient id via eloquent
                                    $nutrient->ingredient()->associate($ingredient);

                                    // save nutrient
                                    $nutrient->save();
                                }
                            }
                        } else {
                            $ingredient = Ingredient::where('name', $ingredData['foods'][0]['food_name'])->first();
                        }

                        if (isset($ingredData['foods'][0]['calories'])) {
                            // add ingredient calories to recipe total
                            $recipe->total_cals += floatval($ingredData['foods'][0]['calories']);
                        }

                        if (isset($ingredData['foods'][0]['protein'])) {
                            // add ingredient protein to total
                            $recipe->protein += floatval($ingredData['foods'][0]['protein']);
                        }

                        if (isset($ingredData['foods'][0]['total_fat'])) {
                            // add ingredient total fat to recipe total
                            $recipe->fat += floatval($ingredData['foods'][0]['total_fat']);
                        }

                        if (isset($ingredData['foods'][0]['total_carbohydrate'])) {
                            // add carbs to recipe
                            $recipe->carbs += floatval($ingredData['foods'][0]['total_carbohydrate']);
                        }

                        if (isset($ingredData['foods'][0]['saturated_fat'])) {
                            // add sat fat
                            $recipe->sat_fat += floatval($ingredData['foods'][0]['saturated_fat']);
                        }

                        if (isset($ingredData['foods'][0]['cholesterol'])) {
                            // add cholesterol
                            $recipe->cholesterol += floatval($ingredData['foods'][0]['cholesterol']);
                        }

                        if (isset($ingredData['foods'][0]['dietary_fiber'])) {
                            // add fibre
                            $recipe->dietary_fibre += floatval($ingredData['foods'][0]['dietary_fiber']);
                        }

                        if (isset($ingredData['foods'][0]['sodium'])) {
                            // add ingredient sodium
                            $recipe->sodium += floatval($ingredData['foods'][0]['sodium']);
                        }

                        if (isset($ingredData['foods'][0]['sugars'])) {
                            // add sugars
                            $recipe->sugars += floatval($ingredData['foods'][0]['sugars']);
                        }

                        if (isset($ingredData['foods'][0]['potassium'])) {
                            // add potassium
                            $recipe->potassium += floatval($ingredData['foods'][0]['potassium']);
                        }

                        if (isset($ingredData['foods'][0]['serving_weight_in_grams'])) {
                            // add ingredient serving weight in grams
                            $recipe->serving_weight_grams += floatval($ingredData['foods'][0]['serving_weight_in_grams']);
                        }

                        if ($save) {
                            // try {
                            // create a php unit mass/gram class for full ingredient amount
                            $fullAmount = Unit::from(strval($ingredData['foods'][0]['serving_weight_in_grams']) . ' g');
                            $this->info($fullAmount());
                            // } catch (Exception $e){
                            //     Log::debug($e->getMessage());
                            // }

                            // try {
                            //     // create a php unit mass/gram class for per serving ingredient amount
                            //     $this->info($ingredData['foods'][0]['serving_weight_in_grams'] / $recipe->yield);
                            if ($recipe->yield > 0) {
                                $perServing = Unit::from(strval($ingredData['foods'][0]['serving_weight_in_grams'] / $recipe->yield) . ' g');
                            } else {
                                $perServing = Unit::from(strval($ingredData['foods'][0]['serving_weight_in_grams']) . ' g');
                            }
                            $this->info($perServing());
                            // } catch (Exception $e){
                            //     Log::debug($e->getMessage());
                            // }

                            // attach ingredient to recipe
                            $recipe->ingredients()->attach($ingredient->id, ['full_servings_amount' => $fullAmount(), 'per_serving_amount' => $perServing(), 'display_text' => $item['ingredientRaw']]);
                        }
                    } else {
                        Log::debug("Ingredient not found for query" . $query);
                    }
                }

                // save the recipe
                if ($save) {
                    $recipe->save();
                }
            }
        }
    }
}
