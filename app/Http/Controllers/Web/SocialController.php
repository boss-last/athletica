<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;
use Illuminate\Http\Request;
class SocialController extends Controller
{
    // Liker une activité
    public function toggleLike($id)
    {
        $activity = Activity::findOrFail($id);
        $existingLike = $activity->likes()->where('user_id', auth()->id())->first();

        if ($existingLike) {
            $existingLike->delete();
            return back()->with('success', 'Like retiré');
        }

        $activity->likes()->create(['user_id' => auth()->id()]);
        return back()->with('success', 'Like ajouté');
    }

    // Ajouter un commentaire
    public function addComment(Request $request, $id)
    {
        $request->validate(['content' => 'required|string|max:500']);

        $activity = Activity::findOrFail($id);
        $activity->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return back()->with('success', 'Commentaire ajouté');
    }
    public function network()
{
    $user = auth()->user()->load('followers', 'following');
    return view('profile.followers', compact('user'));
}
}
