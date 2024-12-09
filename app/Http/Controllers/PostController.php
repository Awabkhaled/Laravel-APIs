<?php

namespace App\Http\Controllers;
use App\Helpers\ValidationHelpers\ValidationHelpers as helper;
use Illuminate\Http\Request;
use App\models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Post::all()->sortBy('pinned', SORT_REGULAR, true)->values();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        return Post::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        [$isValid, $message, $statusCode] = helper::Validate_id($id, Post::class);
        if($isValid)
        {
            return Post::find( $id );
        }
        else{
            return response()->json(['error' => $message],$statusCode);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, string $id)
    {
        [$isValid, $message, $statusCode] = helper::Validate_id($id, Post::class);
        if($isValid)
        {
            $post = Post::find($id);
            $post->update($request->all());
            return $post;
        }
        else{
            return response()->json(['error' => $message],$statusCode);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        [$isValid, $message, $statusCode] = helper::Validate_id($id, Post::class);
        if($isValid)
        {
            $post = Post::find($id);
            $post->delete();
            return response()->json(null,204);
        }
        else{
            return response()->json(['error' => $message],$statusCode);
        }
    }

    /**
     * Retrieve all soft deleted posts
     */
    public function trached()
    {
        $softDeletedPosts = Post::onlyTrashed()->get();
        return $softDeletedPosts;
    }

    public function restore($id)
    {
        if(is_numeric( $id )){
            $post = Post::onlyTrashed()->find($id);
            if($post){
                $post->restore();
                return ["message"=>"Post restored successfully","post"=>$post];
            }
            else{
                $message = 'Id Does Not Exist In Softly Deleted Posts';
                $statusCode = 404;
            }
        }
        else{
            $message = 'Invalid id Format';
            $statusCode = 400;
        }
        return response()->json(['error'=> $message],$statusCode);
    }
}
