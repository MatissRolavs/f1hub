<?php

namespace App\Http\Controllers;

use App\Models\Race;
use App\Models\ForumPost;
use App\Models\Comment;
use App\Models\Like;
use App\Services\JolpicaF1Service;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    protected $f1Service;

    public function __construct(JolpicaF1Service $f1Service)
    {
        $this->f1Service = $f1Service;
    }

    public function index(Request $request)
    {
        $sort = $request->get('sort', 'latest');
        $search = $request->get('search');
        $season = $request->get('season');

        $races = \App\Models\Race::withCount('forumPosts')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($season, function ($query, $season) {
                $query->where('season', $season);
            });

        switch ($sort) {
            case 'oldest':
                $races->orderBy('date', 'asc');
                break;
            case 'most_posts':
                $races->orderBy('forum_posts_count', 'desc');
                break;
            case 'least_posts':
                $races->orderBy('forum_posts_count', 'asc');
                break;
            case 'latest':
            default:
                $races->orderBy('date', 'desc');
                break;
        }

        $races = $races->paginate(10)->withQueryString();

        // Full season list from Jolpica (cached). Seasons without races in the DB
        // will simply show an empty result until /races loads them on demand.
        $seasons = $this->f1Service->getSeasons();

        // === Forum Stats ===
        $totalPosts = \App\Models\ForumPost::count();
        $thanksLeft = \App\Models\Like::count();
        $totalRaces = \App\Models\Race::count();

        return view('forums.index', compact(
            'races',
            'sort',
            'search',
            'season',
            'seasons',
            'totalPosts',
            'thanksLeft',
            'totalRaces',
        ));
    }
    

public function show($raceId, Request $request)
{
    $race = Race::findOrFail($raceId);

    $sort = $request->get('sort', 'latest');
    $search = $request->get('search');

    $posts = ForumPost::withCount(['comments', 'likes'])
        ->with(['user.favoriteConstructor', 'user.favoriteDriver'])
        ->where('race_id', $race->id)
        ->when($search, function ($query, $search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
        });

    switch ($sort) {
        case 'oldest':
            $posts->orderBy('created_at', 'asc');
            break;
        case 'most_comments':
            $posts->orderBy('comments_count', 'desc');
            break;
        case 'least_comments':
            $posts->orderBy('comments_count', 'asc');
            break;
        case 'latest':
        default:
            $posts->orderBy('created_at', 'desc');
            break;
    }

    $posts = $posts->get();

    return view('forums.show', compact('race', 'posts', 'sort', 'search'));
}

    public function store(Request $request, $raceId)
    {
        $race = Race::findOrFail($raceId);

        $validated = $request->validate([
            'title' => 'required|string|min:5|max:150',
            'body'  => 'required|string|min:20|max:5000',
        ], [
            'title.min' => 'Title must be at least 5 characters.',
            'title.max' => 'Title cannot exceed 150 characters.',
            'body.min'  => 'Post body must be at least 20 characters.',
            'body.max'  => 'Post body cannot exceed 5000 characters.',
        ]);

        ForumPost::create([
            'race_id' => $race->id,
            'user_id' => auth()->id(),
            'title'   => $validated['title'],
            'body'    => $validated['body'],
        ]);

        return redirect()->route('forums.show', $race->id)
            ->with('success', 'Post created successfully!');
    }
    public function showPost($postId)
{
    $post = ForumPost::with([
        'race',
        'user.favoriteConstructor', 'user.favoriteDriver',
        'comments.user.favoriteConstructor', 'comments.user.favoriteDriver',
        'likes',
    ])->findOrFail($postId);
    return view('forums.post-show', compact('post'));
}

public function storeComment(Request $request, $postId)
{
    $request->validate([
        'body' => 'required|string|min:3|max:1000',
    ], [
        'body.min' => 'Comment must be at least 3 characters.',
        'body.max' => 'Comment cannot exceed 1000 characters.',
    ]);

    Comment::create([
        'forum_post_id' => $postId,
        'user_id' => auth()->id(),
        'body' => $request->body
    ]);

    return back()->with('success', 'Comment added!');
}

public function toggleLike($postId)
{
    $like = Like::where('forum_post_id', $postId)
                ->where('user_id', auth()->id())
                ->first();

    if ($like) {
        $like->delete();
    } else {
        Like::create([
            'forum_post_id' => $postId,
            'user_id' => auth()->id()
        ]);
    }

    return back();
}
}
