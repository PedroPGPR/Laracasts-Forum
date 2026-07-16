<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Dislike;
use App\Models\Like;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class DislikeController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'model' => ['required', 'string'],
            'id' => ['required', 'integer'],
        ]);

        $likeable = Relation::getMorphedModel($validatedData['model'])::findOrFail($validatedData['id']);
        $this->authorize('create', [Dislike::class, $likeable]);

        $likeable->dislikes()->create(['user_id' => $request->user()->id]);
        $likeable->increment('dislikes_count');

        return back()->with('success', 'Disliked successfully.');
    }

    public function destroy(Request $request)
    {
        $validatedData = $request->validate([
            'model' => ['required', 'string'],
            'id' => ['required', 'integer'],
        ]);

        $likeable = Relation::getMorphedModel($validatedData['model'])::findOrFail($validatedData['id']);
        $this->authorize('delete', [Dislike::class, $likeable]);

        $likeable->dislikes()->where('user_id', $request->user()->id)->delete();
        $likeable->decrement('dislikes_count');

        return back()->with('success', 'Dislike removed successfully.');
    }
}
