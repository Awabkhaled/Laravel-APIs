<?php

namespace App\Http\Requests;
use App\Http\Requests\BasePostRequest;

class UpdatePostRequest extends BasePostRequest
{
    function __construct()
    {
        parent::__construct();
        $this->returnedRules = [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'body' => ['sometimes', 'required', 'string'],
            'cover_image' => ['sometimes', 'required', 'image'],
            'pinned' => ['sometimes', 'required', 'boolean'],
            'tags' => ['sometimes', 'required', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
            'replace_whole_tags' => ['required_with:tags', 'boolean'],
        ];
    }
}
