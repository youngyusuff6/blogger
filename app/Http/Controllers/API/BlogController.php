<?php

namespace App\Http\Controllers\API;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{

    /**
     * Display a listing of all blog posts.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {

        if (!$this->checkToken()) {
            return $this->respondUnauthorized();
        }
        // Retrieve all blogs with their owners and paginate the results
        $blogs = Blog::with('owner')->latest()->paginate(10);

        // Check if any blogs are found
        if ($blogs->isEmpty()) {
            return response()->json(['message' => 'No blogs found.']);
        }

        // Format the retrieved blogs
        $formattedBlogs = $blogs->map(function ($blog) {
            return $this->formatBlog($blog);
        });

        // Return the formatted blogs as JSON response
        return response()->json(['blogs' => $formattedBlogs]);
    }

    /**
     * Display a listing of the user's blog posts.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userBlogs()
    {
        if (!$this->checkToken()) {
            return $this->respondUnauthorized();
        }

        $userBlogs = Blog::where('owner_id', Auth::id())
            ->latest()
            ->paginate(10);


        if ($userBlogs->isEmpty()) {
            return response()->json(['message' => 'You have no blogs.']);
        }

        $formattedUserBlogs = $userBlogs->map(function ($blog) {
            return $this->formatBlog($blog);
        });

        return response()->json(['blogs' => $formattedUserBlogs]);
    }


    /**
     * Store a newly created blog post in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        if (!$this->checkToken()) {
            return $this->respondUnauthorized();
        }
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }

        // Create a new blog post
        $blog = Blog::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'owner_id' => Auth::user()->id,
        ]);

        // Return success message and the formatted blog post as JSON response
        return response()->json(['message' => 'Blog post created successfully', 'blog' => $this->formatBlog($blog)]);
    }

    /**
     * Update the specified blog post in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        if (!$this->checkToken()) {
            return $this->respondUnauthorized();
        }

        // Find the blog post by ID
        $blog = Blog::find($id);

        // Check if the blog post exists
        if (!$blog) {
            return response()->json(['error' => 'Blog not found', 'message' => 'The specified blog post does not exist.'], 404);
        }

        // Check if the authenticated user is the owner of the blog post
        if (Auth::user()->id !== $blog->owner_id) {
            return response()->json(['error' => 'Unauthorized', 'message' => 'You are not authorized to update this blog post.'], 401);
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'title' => 'string|max:255',
            'content' => 'string',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }

        // Update the blog post with provided values or keep the existing ones
        $blog->update([
            'title' => $request->input('title', $blog->title),
            'content' => $request->input('content', $blog->content),
        ]);

        // Return success message and the formatted blog post as JSON response
        return response()->json(['message' => 'Blog post updated successfully', 'blog' => $this->formatBlog($blog)]);
    }


    /**
     * Remove the specified blog post from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if (!$this->checkToken()) {
            return $this->respondUnauthorized();
        }
        // Find the blog post by ID
        $blog = Blog::find($id);

        // Check if the blog post exists
        if (!$blog) {
            return response()->json(['error' => 'Blog not found', 'message' => 'The specified blog post does not exist.'], 404);
        }

        // Check if the authenticated user is the owner of the blog post
        if (Auth::user()->id !== $blog->owner_id) {
            return response()->json(['error' => 'Unauthorized', 'message' => 'You are not authorized to delete this blog post.'], 401);
        }

        // Delete the blog post
        $blog->delete();

        // Return success message as JSON response
        return response()->json(['message' => 'Blog post deleted successfully']);
    }

    /**
     * Format a blog post for consistent response format.
     *
     * @param  \App\Models\Blog  $blog
     * @return array
     */
    private function formatBlog(Blog $blog)
    {
        // Find the author of the blog post
        $author = \App\Models\User::find($blog->owner_id);

        // Format the blog post
        return [
            'id' => $blog->id,
            'title' => $blog->title,
            'content' => $blog->content,
            'author' => $author ? $author->name : null,
            'created_at' => $blog->created_at,
            'updated_at' => $blog->updated_at
        ];
    }
    private function respondUnauthorized()
    {
        return response()->json(['error' => 'Unauthenticated, kindly try again after authentication'], Response::HTTP_UNAUTHORIZED);
    }

    private function checkToken()
    {
        return Auth::check();
    }

}