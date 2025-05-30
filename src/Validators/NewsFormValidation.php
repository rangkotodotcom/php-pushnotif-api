<?php

namespace Rangkotodotcom\Pushnotif\Validators;

use Illuminate\Support\Facades\Validator;
use Rangkotodotcom\Pushnotif\Exceptions\PushnotifValidationException;

class NewsFormValidation implements Validation
{
    /**
     * @param array $data
     * @return array
     * @throws PushnotifValidationException
     */
    public static function validate(array $data, string $action = 'created'): array
    {
        $rules = [
            'client_id'         => 'bail|required|uuid',
            'title'             => 'bail|required|string',
            'sub_title'         => 'bail|required|string',
            'is_redirect'       => 'bail|required|boolean',
            'url' => [
                'bail',
                'string',
                function ($attribute, $value, $fail) use ($data) {
                    if ($data['is_redirect'] && empty($value)) {
                        $fail("The $attribute field is required when is_redirect is true.");
                    }
                }
            ],
            "{$action}_by"      => 'bail|required|string|max:255',
            'content'           => 'bail|nullable|string',
            'content_images'    => 'bail|nullable|array',
            'content_images.*'  => 'bail|nullable|string',
            'image'             => 'bail|nullable|array:thumbnail,detail,photoname',
            'image.thumbnail'   => 'bail|nullable|string',
            'image.detail'      => 'bail|nullable|string',
            'image.photoname'   => 'bail|nullable|string',
            'status'            => 'bail|nullable|boolean',
        ];

        if ($action === 'updated') {
            $rules['slug'] = 'bail|required|string';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new PushnotifValidationException($validator);
        }

        return $validator->validated();
    }
}
