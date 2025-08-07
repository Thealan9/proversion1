<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('posts/index', [
            'posts' => Post::with('user')->latest()->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dataValidates = $request->validate([
            'message' => ['required', 'min:8', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->extension();
            $file->move(public_path('images'), $filename);
            $dataValidates['image'] = $filename;
        }

        $request->user()->posts()->create($dataValidates);

        return to_route('posts.index')->with('status', __('Post Created Successfully!'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        // Verificar que el usuario sea el dueño del post
        if (auth()->id() !== $post->user_id) {
            abort(403);
        }

        return response()->json([
            'message' => $post->message,
            'image' => $post->image
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        if (auth()->id() !== $post->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => ['required', 'min:8', 'max:255'],
            'image' => ['sometimes', 'image', 'max:255'],
        ]);

        if ($request->hasFile('image')) {
            // Eliminar imagen anterior
            if ($post->image && file_exists(public_path('images/' . $post->image))) {
                unlink(public_path('images/' . $post->image));
            }

            $filename = time() . '.' . $request->file('image')->extension();
            $request->file('image')->move(public_path('images'), $filename);
            $validated['image'] = $filename;
        }

        $post->update($validated);

        return redirect()->route('posts.index')->with('status', __('Post Updated Successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Verificar autorización
        if (auth()->id() !== $post->user_id) {
            abort(403);
        }

        // Eliminar imagen
        if ($post->image && file_exists(public_path('images/' . $post->image))) {
            unlink(public_path('images/' . $post->image));
        }

        $post->delete();

        return redirect()->route('posts.index')->with('status', __('Post Deleted Successfully!'));
    }
}
