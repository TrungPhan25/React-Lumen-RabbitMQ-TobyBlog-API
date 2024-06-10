<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $search_keyword  = $request->input('type');

        if($search_keyword == 'month'){
            $views = DB::table('views')
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('views');
            
            $comments = DB::table('comments')
                ->whereMonth('created_at', Carbon::now()->month)
                ->count();
        } else if($search_keyword == 'year'){
            $views = DB::table('views')
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('views');
            
            $comments = DB::table('comments')
                ->whereYear('created_at', Carbon::now()->year)
                ->count();
        } else {
            $views = DB::table('views')
                ->whereDate('created_at', Carbon::today())
                ->sum('views');
            
            $comments = DB::table('comments')
                ->whereDate('created_at', Carbon::today())
                ->count();
        }

        return response()->json([
            'views' => $views,
            'comments' => $comments,
        ]);
    }
}
