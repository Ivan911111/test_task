<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Result;

class RatingService
{
    public static function getByEmail(Request $request)
    {
        // Top 10 results without filter
        $topResults = DB::table('results')
            ->join('members', 'results.member_id', '=', 'members.id')
            ->orderBy('milliseconds', 'asc')
            ->select('members.email', 'results.milliseconds')
            ->take(10)
            ->get();

        // Add place and apply key filter
        foreach ($topResults as $key => $result) {
            $result->place = $key + 1;

            $email = $result->email;
            $length = strlen($email);
            $midpoint = $length / 2;
        
            $maskedEmail = substr($email, 0, $midpoint - 1) . str_repeat("*", $midpoint) . substr($email, $midpoint + 1);
            $result->email = $maskedEmail;
        }

        // Output result by email
        $result = DB::table('members') // 
            ->join('results', 'members.id', '=', 'results.member_id')
            ->select('members.email', DB::raw('MIN(results.milliseconds) AS min_milliseconds'))
            ->where('members.email', $request->input('email'))
            ->groupBy('members.email')
            ->first();

        // Output place by email
        $memberId = Member::where('email', $request->input('email'))->value('id');
        $bestResult = Result::where('member_id', $memberId)->orderBy('milliseconds')->first();
        $place = Result::where('milliseconds', '<', $bestResult->milliseconds)->count() + 1;
        $result->place = $place;

        $data = [
            'data' => [
                'top' => $topResults,
                'self' => $result,
            ]
        ];
    
        return response()->json($data);
    }

}