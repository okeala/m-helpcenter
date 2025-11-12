<?php

namespace Modules\Helpcenter\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Modules\Helpcenter\Http\Resources\TagResource;
use Modules\Helpcenter\Models\Tag;

class APITagController extends Controller
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
        $this->authorize('view', $tag);

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


    /**
     * GET /api/v1/tags/{tag}/items?type=person&q=&sort=&per_page=
     * - type: morph alias ou FQCN. Si absent => 422 (on exige un type côté API).
     */
    public function items(Request $request, Tag $tag)
    {
        $type = $request->query('type');
        if (!$type) {
            return response()->json([
                'message' => 'Missing "type" query parameter (morph alias or FQCN).',
            ], 422);
        }

        $perPage = min((int) $request->query('per_page', 24), 100);
        $q       = trim((string) $request->query('q', ''));
        $sort    = $request->query('sort', 'recent'); // recent|name

        $fqcn = Relation::getMorphedModel($type) ?? $type;
        $model = new $fqcn();
        $table = $model->getTable();

        $query = $tag->morphedByMany($fqcn, 'taggable')
            ->with(['tags:id,name,slug,color'])
            ->select("{$table}.*");

        if ($q !== '') {
            $query->where(function ($w) use ($table, $q) {
                if (Schema::hasColumn($table, 'name')) {
                    $w->orWhere("{$table}.name", 'like', "%{$q}%");
                }
                if (Schema::hasColumn($table, 'title')) {
                    $w->orWhere("{$table}.title", 'like', "%{$q}%");
                }
            });
        }

        if ($sort === 'name' && Schema::hasColumn($table, 'name')) {
            $query->orderBy("{$table}.name");
        } elseif ($sort === 'name' && Schema::hasColumn($table, 'title')) {
            $query->orderBy("{$table}.title");
        } else {
            $query->latest("{$table}.created_at");
        }

        $p = $query->paginate($perPage)->appends($request->query());

        return response()->json([
            'data' => $p->getCollection()->map(function ($m) {
                $display = $m->name ?? $m->title ?? ('#'.$m->getKey());
                return [
                    'id'    => $m->getKey(),
                    'display' => $display,
                    'type'  => class_basename($m),
                    'tags'  => $m->whenLoaded('tags', fn() => $m->tags->map(fn($t) => [
                        'id' => $t->id, 'name' => $t->name, 'slug' => $t->slug, 'color' => $t->color,
                    ])),
                    'created_at' => optional($m->created_at)->toIso8601String(),
                ];
            }),
            'meta' => [
                'current_page' => $p->currentPage(),
                'per_page'     => $p->perPage(),
                'total'        => $p->total(),
                'last_page'    => $p->lastPage(),
                'tag'          => ['id' => $tag->id, 'name' => $tag->name, 'slug' => $tag->slug],
                'filters'      => ['q' => $q, 'sort' => $sort, 'type' => $type],
            ],
        ]);
    }
}
