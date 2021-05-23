<?php

namespace App\Services;

/* 

 This is Essentially a Facade for the PHPUnitConversion Library with added functions
 to abstract mass to volume conversions away from the model.

 the main handle function should simply take 
 
    1. An amount and Unit in any form as $currentAmount
    2. An amount and unit in and form as $changeAmount
    3. A function type (add or subtract)
    4. An Ingredient Model


    The class will then take care of handling conversions from vol
    to mass if necessary, and return the result of the transaction 
    in the type and units of the $currentAmount that was passed in.


    I will also include a public getVol() and getMass() 
    for any situation that might require the basic use of those functions
    these functions will require the use of the constructor whereas the handle 
    function is designed for mass use.


*/

use App\Models\Ingredient;
use Illuminate\Support\Facades\Log;
use PhpUnitConversion\System;
use PhpUnitConversion\Unit as Unit;
use PhpUnitConversion\Unit\Mass;
use PhpUnitConversion\Unit\Mass\Gram;
use PhpUnitConversion\Unit\Volume;
use PhpUnitConversion\Unit\Volume\MilliLiter;
use PhpUnitConversion\UnitType;
use Spatie\Regex\Regex;
use PhpUnitConversion\Map as UnitMap;


class UnitConverter
{
    const MASS = 1;
    const LENGTH = 2;
    const AREA = 3;
    const VOLUME = 4;
    const TIME = 5;
    const TEMPERATURE = 6;
    const AMOUNT = 7;
    const VELOCITY = 8;

    protected $massUnit;


    protected $volUnit;

    protected $ingredient;

    protected $unitsOfVolume = ['cup', 'ml', 'tsp', 'tbsp', 'gal', 'pt', 'pint', 'l', 'qt', 'quart', 'fl oz', 'm3'];

    public function __construct($massUnit = null, $volUnit = null, $ingredient = null)
    {
        if ($massUnit != null) {
            $this->massUnit = Mass::from($massUnit);
        }
        if ($volUnit != null) {
            $this->volUnit = Volume::from($volUnit);
        }
        if ($ingredient != null) {
            $this->ingredient = $ingredient;
        }
    }


    public function handle($currentAmount, $changeAmount, String $type, Ingredient $ingredient, String $state = null)
    {
        // TODO add a check to ensure incoming amounts are valid

        $current = Unit::from($currentAmount); // always get metric

        // return $current;

        $change = Unit::from($changeAmount);  // always get metric

        // return $change;
        // return json_decode($ingredient->conversions);
        if ($current::TYPE === $change::TYPE) { // simple case
            if ($type == 'add') {
                $final = $this->add($current, $change);
            } elseif ($type == 'subtract') {
                $final = $this->subtract($current, $change);
            }
        } else if ($current::TYPE == 1 && $change::TYPE == 4) {

            $conMass = $this->getVolToMassGramsConversion($ingredient->conversions()->get(), $state, $change);

            if ($type == 'add') {
                $final = $this->add($current, $conMass);
            } elseif ($type == 'subtract') {
                $final = $this->subtract($current, $conMass);
            }
        } else if ($current::TYPE == 4 && $change::TYPE == 1) {

            $conVol = $this->getMassGramToVolConversion($ingredient->conversions()->get(), $state, $change);
            return $conVol;
            if ($type == 'add') {
                $final = $this->add($current, $conVol);
            } elseif ($type == 'subtract') {
                $final = $this->subtract($current, $conVol);
            }
        }



        return $final;
    }

    public function getVolFromMass()
    {
    }

    public function getMassFromVol()
    {
    }

    public function getValue(String $type)
    {
    }

    protected function getVolToMassGramsConversion($conversions, String $state = null, Unit $change)
    {

        $final = null;

        foreach ($conversions as $conversion) {

            foreach ($this->unitsOfVolume as $uVol) {
                if (Regex::match('/' . $uVol . '/', $conversion->measure)->hasMatch()) {
                    $unitString = Regex::match('/' . $uVol . '/', $conversion->measure)->result();
                    try {
                        $unit = Unit::from(strval($conversion->quantity) . ' ' . $unitString);
                        if ($unit::TYPE == 4) {
                            // do some stuff
                            $base = Unit::from($unit());

                            $factor = $change->getValue() / $base->getValue();

                            $final = new Mass\Gram($conversion->serving_weight * $factor);
                            return $final;
                        }
                    } catch (\Exception $e) {
                        $msg = $e->getMessage();
                        Log::debug($msg);
                    }
                }
            }
        }

        if (!$final) {
            $mils = $change->to(MilliLiter::class);

            return new Mass\Gram($mils->getValue()); // if all else fails treat the food as if it has the density of water

            //TODO we need to change this to something more like 65% - 75% of the density of water. this is probably still high, but more likely an aporx. avg of most food densities
        } else {
            return $final;
        }
    }

    protected function getMassGramToVolConversion($conversions, String $state = null, Unit $change)
    {
        $msg = UnitMap::add('./Units/', 'App\Exceptions\Units');


        foreach ($conversions as $conversion) {
            foreach ($this->unitsOfVolume as $uVol) {
                if (Regex::match('/[[:<:]]' . $uVol . '[[:>:]]/', $conversion->measure)->hasMatch()) {
                    $unitString = Regex::match('/' . $uVol . '/', $conversion->measure)->result();
                    try {
                        $unit = Unit::from(strval($conversion->quantity) . ' ' . $unitString);
                        if ($unit::TYPE == 4) {
                            // do some stuff
                            // $base = Unit::from($unit());

                            $factor = $change->getValue() / $conversion->serving_weight;

                            $unit->setValue($factor);
                            // $base->setValue($base->getValue() * $factor);

                            return $unit;
                        }
                    } catch (\Exception $e) {
                        $msg = $e->getMessage();
                        Log::debug($msg);
                    }
                }
            }
        }
    }

    protected function add($amountOne, $amountTwo)
    {
        return $amountOne->add($amountTwo);
    }

    protected function subtract($amountOne, $amountTwo)
    {
        return $amountOne->substract($amountTwo);
    }

    public static function handleSmallMedLarge(Ingredient $ingredient, String $unit, $amount)
    {
        $conversions = json_decode($ingredient->conversions);


        foreach ($conversions as $conversion) {
            $conversionMeasureSML = Regex::match('/(' . $unit . ')/', $conversion->measure)->result();

            $conversionMeasureName = Regex::match('/(' . $ingredient->name . ')/', $conversion->measure)->result();
            if ($conversionMeasureSML != null && $conversionMeasureSML == $unit) {
                return new Mass\Gram($amount * $conversion->serving_weight);
            } else if ($conversionMeasureName != null) {
                return new Mass\Gram($amount * $conversion->serving_weight);
            }
        }
    }
}
