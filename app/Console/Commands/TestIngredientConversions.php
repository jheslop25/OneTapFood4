<?php

namespace App\Console\Commands;

use App\Models\Ingredient;
use App\Services\UnitConverter;
use Illuminate\Console\Command;
use PhpUnitConversion\Unit\Mass;

class TestIngredientConversions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'conversion:test';

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
        $ingredients = Ingredient::all();

        $converter = new UnitConverter();

        $this->info(count($ingredients));
        foreach($ingredients as $ingredient){
            // $current = Mass::from('1 lb');
            // $change = Mass::from('0.5 lb');
            // $this->info(var_dump($ingredient->conversions()->get()));
            $result = $converter->handle('1 lb', '0.5 cup', 'subtract', $ingredient);

            $this->info(var_dump($result) . $ingredient->name);
        }
    }
}
