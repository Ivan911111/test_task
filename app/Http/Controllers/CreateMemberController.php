<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CreateMemberService;

class CreateMemberController extends Controller
{
    public function create(Request $request)
    {
        CreateMemberService::create($request);
    }
}
