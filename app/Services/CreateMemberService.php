<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Result;

class CreateMemberService
{
    public static function create(Request $request)
    {

        $validated = $request->validate([
            'email' => 'nullable | string',
            'milliseconds' => 'required'
        ]);

        // Email request check
        if ($request->has('email')){
            Member::firstOrCreate([
                'email' => $request->input('email')
            ]);
            $member_id = Member::where('email', $request->input('email'))->value('id');
        }
        else {
            $member_id = null;
        }


        Result::create([
            'member_id' => $member_id,
            'milliseconds' => $request->input('milliseconds')
        ]);

        return response()->json([
            'success' => true
        ]);    }
    
}