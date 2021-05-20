<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SpoonApi {

    protected $searchUrl = 'https://api.spoonacular.com/recipes/complexSearch';
    
    protected $detailsUrl = 'https://api.spoonacular.com/recipes/informationBulk';

    protected $key = 'add0b1b4e4e546b2ba3d13d014dfd478';

    protected $diet;

    protected $intollerances;

    protected $type;

    protected $fillIngredients = true;

    protected $addRecipeNutrition = true;

    protected $titleMatch;

    protected $minCals = 50;

    protected $maxCals = 800;

    protected $minCarbs  = 10;

    protected $maxCarbs = 100;

    protected $minPro = 10;

    protected $maxPro = 100;

    protected $minFat = 1;

    protected $maxFat = 100;

    protected $minFiber = 0;

    protected $maxFiber = 100;

    protected $minSugar = 0;

    protected $maxSugar = 100;

    protected $totalReturn = 10;

    public function __construct($diet, $intollerances, $type, $titleMatch = null)
    {
        $this->diet = $diet;
        $this->intollerances = $intollerances;
        $this->type = $type;
        $this->titleMatch = $titleMatch;
    }

    public function setTotalReturn($totalReturn){
        $this->totalReturn = $totalReturn;
    }

    public function getTotalReturn(){
        return $this->totalReturn;
    }

    public function setDiet($diet){
        $this->diet = $diet;
    }

    public function getDiet(){
        return $this->diet;
    }

    public function setCarbs($min, $max){
        $this->minCarbs = $min;
        $this->maxCarbs = $max;
    }

    public function setCals($min, $max){
        $this->minCals = $min;
        $this->maxCals = $max;
    }

    public function setPro($min, $max){
        $this->minPro = $min;
        $this->maxPro = $max;
    }

    public function setFiber($min, $max){
        $this->minFiber = $min;
        $this->maxFiber = $max;
    }

    public function setFat($min, $max){
        $this->minFat = $min;
        $this->maxFat = $max;
    }

    public function setSugar($min, $max){
        $this->minSugar = $min;
        $this->maxSugar = $max;
    }

    public function search($query, $includeIngreds, $readyTime, $offset){
        try {
            $result = Http::get($this->searchUrl, [
                'apiKey' => $this->key,
                'diet' => $this->diet,
                'query' => $query,
                'intolerances' => $this->intollerances,
                'type' => $this->type,
                'includeIngredients' => $includeIngreds,
                'addRecipeInformation' => true,
                'fillIngredients' => false,
                'maxReadyTime' => $readyTime,
                'number' => $this->totalReturn,
                'offset' => $offset,
            ]);
    
            if($result->ok()){
                return $result->json();
            } else {
                Log::debug($result->status());
                Log::debug(var_dump($result->body()));
                return $result->body();
            }
        } catch (Exception $e){
            Log::debug($e->getMessage());
        }
    }

    public function getInfo($ids){
            try{
                $result = Http::get($this->detailsUrl, [
                    'apiKey' => $this->key,
                    'ids' => $ids
                ]);
        
                if($result->ok()){
                    return $result->json();
                }
            } catch (Exception $e){
                Log::debug($e->getMessage());
            }
    }
}
