<?php

namespace App\Http\Controllers;
use App\Helpers\ValidationHelpers\ValidationHelpers as helper;
use App\Http\Resources;
use App\Http\Resources\PostResource;
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
        $posts = Post::with('tags')->get()->sortBy('pinned', SORT_REGULAR, true)->values();
        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $post_data = $request->validated();
        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imageName = str_replace(' ','_',$imageName);
            $image->move(public_path('uploaded_images'), $imageName);
            $post_data['cover_image'] = 'uploaded_images/' . $imageName;
            $post_data['cover_image'] = asset($post_data['cover_image']);
        }
        $createdPost = Post::create($post_data);
        $tags = array_unique($request->tags);
        $createdPost->tags()->sync($tags);
        return new PostResource($createdPost);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        [$isValid, $message, $statusCode] = helper::Validate_id($id, Post::class);
        if($isValid)
        {
            return new PostResource(Post::find( $id ));
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

        // if the id is not valid
        if(!$isValid)
        {
            return response()->json(['error' => $message],$statusCode);
        }

        $post = Post::find($id);

        // image update
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

        // Update Tags
        if ($request->has('tags')) {
            $newTags = array_unique($request->tags);
            $existingTags = $post->tags->pluck('id')->toArray();
            $post->tags()->detach();
            if ($request->replace_whole_tags) {
                $post->tags()->sync($newTags);
            } else {
                $allTags = array_unique(array_merge($existingTags, $newTags));
                $post->tags()->attach($allTags);
            }
        }

        $post->update($request->except('cover_image'));
        $post->refresh();

        return new PostResource($post);
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
        return PostResource::collection($softDeletedPosts);
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
                return ["message"=>"Post restored successfully","post"=>new PostResource($post)];
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
