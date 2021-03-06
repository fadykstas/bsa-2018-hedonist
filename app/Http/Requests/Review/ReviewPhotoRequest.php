<?php

namespace Hedonist\Http\Requests\Review;

use Hedonist\Http\Requests\JsonRequest;

class ReviewPhotoRequest extends JsonRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'review_id' => 'required|integer',
            'description' => 'nullable|string|max:255',
            'img_url' => 'required|image|max:5120'
        ];
    }
}