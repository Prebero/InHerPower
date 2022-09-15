<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Rule;



class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get( 
     *  path="/api/posts",
     *    summary="Get all posts",
     *    description="All posts",
     *    operationId="postsDetails",
     *    tags={"Posts"},
     *    security={{ "bearer":{}}},
     *    @OA\Response(
     *      response=200,
     *        description="Fetched successfully",
     *      @OA\MediaType(
     *        mediaType="application/json",
     *      )  
     *    ) 
     * )
     */
    public function index()
    {
        $posts =  Post::all();

        return response()->json([
            'message' => "All saved posts",
            'posts' => $posts
        ]);
    }

   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /** 
     * @OA\Post(
     * path="/api/posts",
     *    tags={"Posts"},
     *    summary="Create a Posts",
     *    operationId="CreatePosts",
     *    description="Create a Posts",
     *    @OA\RequestBody(
     *          @OA\JsonContent(),
     *          @OA\MediaType(
     *          mediaType="multipart/form-data",
     *          @OA\Schema(
     *              type="object",
     *              required={"title","description","category_id","file"},
     *              @OA\Property(property="title", type="text"),
     *              @OA\Property(property="description", type="text"),
     *              @OA\Property(property="category_id", type="text"),
     *              @OA\Property(property="file", type="file"),
     *           )
     *         ),
     *       ),
     *       @OA\Response(
     *        response=422,
     *        description="Successfully Created",
     *        @OA\JsonContent(),
     *      )
     * )
    */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:posts|max:255',
            'description' => 'required|string',
            'category_id' => 'required|string|exists:categories,id',
            'file' => 'required|image'
        ]);
        
        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'picture' => $request->file('file')->store('images', ['disk' => 'public']),
        ]);

        return response()->json([
            'message' => "post created successfully",
            'post' => $post
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Post  $post
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get( 
     * path="/api/posts/{post}",
     *    summary="Show posts by Id",
     *    description="Show posts Details",
     *    operationId="ShowpostsDetails",
     *    tags={"Posts"},
     *    @OA\Parameter(
     *      name="Posts",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )  
     *   ),
     *    @OA\Response(
     *      response=200,
     *        description="Fetched successfully",
     *      @OA\MediaType(
     *        mediaType="application/json",
     *      )  
     *    ) 
     * )
     */
    public function show(Post $post)
    {
        return response()->json([
            'post' => $post
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Post  $post
     * @return \Illuminate\Http\Response
     */
    /** 
     * @OA\Put(
     * path="/api/posts",
     *    tags={"Posts"},
     *    summary="Update a Posts",
     *    operationId="UpdatePosts",
     *    description="Update a Posts",
     *    @OA\RequestBody(
     *          @OA\JsonContent(),
     *          @OA\MediaType(
     *          mediaType="multipart/form-data",
     *          @OA\Schema(
     *              type="object",
     *              required={"title","description","category_id","file"},
     *              @OA\Property(property="title", type="text"),
     *              @OA\Property(property="description", type="text"),
     *              @OA\Property(property="category_id", type="text"),
     *              @OA\Property(property="file", type="file"),
     *           )
     *         ),
     *       ),
     *       @OA\Response(
     *        response=422,
     *        description="Successfully Update",
     *        @OA\JsonContent(),
     *      )
     * )
    */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'unique:posts|max:255',
            'description' => 'string',
            'category_id' => 'required|string|exists:categories,id',
            'file' => 'image'
        ]);

        $post->update([
            'title' => $request->title ?? $post->title,
            'description' => $request->description ?? $post->description,
            'category_id' => $request->category_id ?? $post->category_id,
            'picture' => $request->file ? $request->file('file')->store('images', ['disk' => 'public']) : $post->picture,
        ]);

        return response()->json([
            'message' => "post updated successfully",
            'post' => $post->refresh()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Post  $post
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Delete( 
     * path="/api/posts/{post}",
     *    summary="Delete posts by Id",
     *    description="Delete posts Details",
     *    operationId="DeletepostsDetails",
     *    tags={"Posts"},
     *    @OA\Parameter(
     *      name="Posts",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )  
     *   ),
     *    @OA\Response(
     *      response=200,
     *        description="Fetched successfully",
     *      @OA\MediaType(
     *        mediaType="application/json",
     *      )  
     *    ) 
     * )
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json([
            'message' => "post deleted successfully"
        ]);
    }
}
