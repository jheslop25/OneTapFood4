<?php

namespace App\Console\Commands;

use App\Models\Recipe;
use Illuminate\Console\Command;

class CleanRecipeData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recipe:clean';

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
        $allRecipies = Recipe::all();

        $unique = $allRecipies->unique('name');

        $extra = $allRecipies->except($unique->modelKeys());

        $this->info(count($allRecipies));
        $this->info(count($unique));
        $this->info(count($extra));

        foreach($extra as $item){
            $item->ingredients()->sync([]);
            $item->delete();
        }
    }
}
