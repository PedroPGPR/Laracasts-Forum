<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'model' => ['required', 'string'],
            'id' => ['required', 'integer'],
        ]);

        $likeable = Relation::getMorphedModel($validatedData['model'])::findOrFail($validatedData['id']);
        $this->authorize('create', [Like::class, $likeable]);

        $likeable->likes()->create(['user_id' => $request->user()->id]);
        $likeable->increment('likes_count');

        return back()->with('success', 'Liked successfully.');
    }

    public function destroy(Request $request)
    {
        $validatedData = $request->validate([
            'model' => ['required', 'string'],
            'id' => ['required', 'integer'],
        ]);

        $likeable = Relation::getMorphedModel($validatedData['model'])::findOrFail($validatedData['id']);
        $this->authorize('delete', [Like::class, $likeable]);

        $likeable->likes()->where('user_id', $request->user()->id)->delete();
        $likeable->decrement('likes_count');

        return back()->with('success', 'Like removed successfully.');
    }
}
