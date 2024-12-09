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
        ];
    }
}
