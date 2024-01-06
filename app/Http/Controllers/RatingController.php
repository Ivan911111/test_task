<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RatingService;

class RatingController extends Controller
{
    public function getByEmail(Request $request)
    {
        return RatingService::getByEmail($request);
    }
}
