<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagRequest;
use App\Models\Tag;
use App\Helpers\ValidationHelpers\ValidationHelpers as helper;
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
        [$isValid, $message, $statusCode] = helper::Validate_id($id, Tag::class);
        if($isValid)
        {
            return TagController::JsonResponse(Tag::find( $id ),200);
        }
        else{
            return TagController::JsonResponse(['error' => $message],$statusCode);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TagRequest $request, $id)
    {
        [$isValid, $message, $statusCode] = helper::Validate_id($id, Tag::class);
        if($isValid)
        {
            $tag = Tag::find($id);
            $tag->update($request->only(['name']));
            return TagController::JsonResponse($tag,200);
        }
        else{
            return TagController::JsonResponse(['error' => $message],$statusCode);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        [$isValid, $message, $statusCode] = helper::Validate_id($id, Tag::class);
        if($isValid)
        {
            $tag = Tag::find($id);
            $tag->delete();
            return TagController::JsonResponse(null,204);
        }
        else{
            return TagController::JsonResponse(['error' => $message],$statusCode);
        }
    }

    /**
     * A helper funtion to Format a json reponse with the JSON_PRETTY_PRINT
     */
    private static function JsonResponse($data, $status)
    {
        return response()->json($data, $status,[],JSON_PRETTY_PRINT);
    }
}
