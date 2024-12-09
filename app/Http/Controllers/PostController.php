<?php

namespace App\Http\Controllers;
use App\Helpers\ValidationHelpers\ValidationHelpers as helper;
use Illuminate\Http\Request;
use App\models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Support\Facades\Log;

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
        $post = $request->all();
        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imageName = str_replace(' ','_',$imageName);
            $image->move(public_path('uploaded_images'), $imageName);
            $post['cover_image'] = 'uploaded_images/' . $imageName;
            $post['cover_image'] = asset($post['cover_image']);
        }
        return Post::create($post);
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
        if(!$isValid)
        {
            return response()->json(['error' => $message],$statusCode);
        }

        $post = Post::find($id);
        if ($request->hasFile('cover_image')) {
            $oldImagePath = public_path('uploaded_images/'.basename($post->cover_image));
            unlink($oldImagePath);
            $image = $request->file('cover_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imageName = str_replace(' ','_',$imageName);
            $image->move(public_path('uploaded_images'), $imageName);
            $post['cover_image'] = 'uploaded_images/' . $imageName;
            $post['cover_image'] = asset($post['cover_image']);
        }

        $post->update($request->except('cover_image'));
        return $post;
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

    /**
     * Restore soft deleted post
     */
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
