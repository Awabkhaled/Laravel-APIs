<?php

namespace App\Http\Requests;
use App\Http\Requests\BasePostRequest;

class StorePostRequest extends BasePostRequest
{
    function __construct()
    {
        parent::__construct();
        $this->returnedRules = [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'cover_image' => ['required', 'image'],
            'pinned' => ['required', 'boolean'],
        ];
    }
}
