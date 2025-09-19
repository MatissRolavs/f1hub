<?php

namespace App\Http\Controllers;

use App\Models\Race;
use App\Models\ForumPost;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function index(Request $request)
{
    $sort = $request->get('sort', 'latest'); // default sort
    $search = $request->get('search');

    $races = \App\Models\Race::withCount('forumPosts') // assuming relation in Race model
        ->when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
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

    $races = $races->get();

    return view('forums.index', compact('races', 'sort', 'search'));
}

public function show($raceId, Request $request)
{
    $race = Race::findOrFail($raceId);

    $sort = $request->get('sort', 'latest');
    $search = $request->get('search');

    $posts = ForumPost::withCount('comments')
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
            'title' => 'required|string|max:255',
            'body'  => 'required|string',
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
    $post = ForumPost::with(['user', 'comments.user', 'likes'])->findOrFail($postId);
    return view('forums.post-show', compact('post'));
}

public function storeComment(Request $request, $postId)
{
    $request->validate([
        'body' => 'required|string'
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
