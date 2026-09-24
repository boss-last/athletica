<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Badge;


class BadgeController extends Controller
{
    public function index()
    {
         $allBadges = Badge::all();
        $userBadges = auth()->user()->badges->pluck('id')->toArray();

        return view('badges.index', compact('allBadges', 'userBadges'));
    }
}
