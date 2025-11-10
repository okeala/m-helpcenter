<?php

namespace Modules\Helpcenter\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Modules\Helpcenter\Models\Tag;

class TagController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Tag::class);

        return TagResource::collection(Tag::all());
    }

    public function store(Request $request)
    {
        $this->authorize('create', Tag::class);

        $data = $request->validate([
            'name' => ['nullable'],
            'user_id' => ['nullable', 'integer'],
        ]);

        return new TagResource(Tag::create($data));
    }

    public function show(Tag $tag)
    {
        return new TagResource($tag);
    }

    public function update(Request $request, Tag $tag)
    {
        $this->authorize('update', $tag);

        $data = $request->validate([
            'name' => ['nullable'],
            'user_id' => ['nullable', 'integer'],
        ]);

        $tag->update($data);

        return new TagResource($tag);
    }

    public function destroy(Tag $tag)
    {
        $this->authorize('delete', $tag);

        $tag->delete();

        return response()->json();
    }
}
