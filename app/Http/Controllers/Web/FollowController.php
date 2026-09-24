<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Follow;

class FollowController extends Controller
{
    // Suivre / Ne plus suivre
    public function toggle($id)
    {
        $userToFollow = User::findOrFail($id);

        if (auth()->id() === $userToFollow->id) {
            return back()->with('error', 'Vous ne pouvez pas vous suivre vous-même.');
        }

        $existingFollow = Follow::where('follower_id', auth()->id())
            ->where('following_id', $userToFollow->id)
            ->first();

        if ($existingFollow) {
            $existingFollow->delete();
            return back()->with('success', 'Vous ne suivez plus ' . $userToFollow->full_name);
        } else {
            Follow::create([
                'follower_id' => auth()->id(),
                'following_id' => $userToFollow->id,
            ]);
            return back()->with('success', 'Vous suivez maintenant ' . $userToFollow->full_name);
        }
    }
}
