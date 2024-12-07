<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagRequest;
use App\Models\Tag;
use Illuminate\Support\Facades\Log;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TagController::JsonResponse(Tag::all(),200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TagRequest $request)
    {
        $name = $request->only(['name']);
        $tag = Tag::create($name);
        return TagController::JsonResponse($tag,201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $validationResult = TagController::validateId($id);
        if($validationResult != $id)
        {
            return TagController::JsonResponse(['error' => $validationResult],404);
        }

        return TagController::JsonResponse(Tag::find( $id ),200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TagRequest $request, $id)
    {
        $validationResult = TagController::validateId($id);
        if($validationResult != $id)
        {
            return TagController::JsonResponse(['error' => $validationResult],404);
        }

        $tag = Tag::find($id);
        $tag->update($request->only(['name']));
        return TagController::JsonResponse($tag,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $validationResult = TagController::validateId($id);
        if($validationResult != $id)
        {
            return TagController::JsonResponse(['error' => $validationResult],404);
        }
        $tag = Tag::find($id);
        $tag->delete();
        return TagController::JsonResponse(null,204);
    }

    /**
     * A helper method to see if the id is valid and if it is
     * exist in the database
     */
    private static function validateId($id){
        $model = Tag::class;
        if (!is_numeric($id)) {
            return "Tag is not valid";
        }

        if (!$model::where('id', $id)->exists()) {
            return 'Tag not found';
        }

        return $id;
    }

    /**
     * A helper funtion to Format a json reponse with the JSON_PRETTY_PRINT
     */
    private static function JsonResponse($data, $status)
    {
        return response()->json($data, $status,[],JSON_PRETTY_PRINT);
    }
}
