<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NutriSApi {

    protected $url = "https://nutri-s1.p.rapidapi.com/nutrients";

    public function __construct()
    {
        
    }

    public function search($query){
        try {
            $results = Http::withHeaders([
                'content-type' => 'application/json',
                'x-rapidapi-key' => '84963ea5f7mshdff757e386ee4bap1be221jsn90125f74a6e6',
                'x-rapidapi-host' => 'nutri-s1.p.rapidapi.com'
            ])->withBody(json_encode(['query' => $query]), 'application/json')->post($this->url);

            return $results->json();
        } catch (Exception $e){
            Log::debug($e->getMessage());
        }
    }

}

?>