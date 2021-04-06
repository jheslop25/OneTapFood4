<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CookBookApi {
    protected $url = 'https://mycookbook-io1.p.rapidapi.com/recipes/rapidapi';

    public function __construct()
    {
        
    }

    public function getRecipe($url){
        try {
            $result = Http::withHeaders([
                'content-type' => 'text/plain',
                'x-rapidapi-key' => '84963ea5f7mshdff757e386ee4bap1be221jsn90125f74a6e6',
                'x-rapidapi-host' => 'mycookbook-io1.p.rapidapi.com'
            ])->withBody($url, 'text/plain')->post($this->url);

            return $result->json();
        } catch (Exception $e){
            Log::debug($e->getMessage());
        }
    }
}

?>