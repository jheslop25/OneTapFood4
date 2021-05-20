<?php

namespace App\Http\Controllers\Api\V4\External;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use Illuminate\Http\Request;

class DemoController extends Controller
{
    public function index(Request $request){
        return response()->json(Recipe::with('ingredients')->get());
    }
}
