<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ParseApi {
    
    protected $url = "https://zestful.p.rapidapi.com/parseIngredients";

    public function __construct()
    {
        
    }

    public function parse($ingredients){

        try {
            $results = Http::withHeaders([
                'content-type' => 'application/json',
                'x-rapidapi-key' => '84963ea5f7mshdff757e386ee4bap1be221jsn90125f74a6e6',
                'x-rapidapi-host' => 'zestful.p.rapidapi.com'
            ])->post($this->url, [
                'ingredients' => $ingredients
            ]);

            return $results->json();

            
        } catch (Exception $e){
            Log::debug($e->getMessage());
        }

    }

}

?>