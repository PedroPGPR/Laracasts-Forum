<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Like::class);

        return Like::all();
    }

    public function store(Request $request)
    {
        $this->authorize('create', Like::class);

        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
        ]);

        return Like::create($data);
    }

    public function show(Like $like)
    {
        $this->authorize('view', $like);

        return $like;
    }

    public function update(Request $request, Like $like)
    {
        $this->authorize('update', $like);

        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
        ]);

        $like->update($data);

        return $like;
    }

    public function destroy(Like $like)
    {
        $this->authorize('delete', $like);

        $like->delete();

        return response()->json();
    }
}
