<?php

namespace App\Console\Commands;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Services\UnitConverter;
use Illuminate\Console\Command;

class BuildCart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart:build';

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
        $recipes = Recipe::all();

        $counter = 0;

        $converter = new UnitConverter();

        foreach($recipes as $recipe){
            $ingredients = $recipe->ingredients()->get();
            foreach($ingredients as $ingredient){
                // $converter->handle();
            }
        }
    }
}
