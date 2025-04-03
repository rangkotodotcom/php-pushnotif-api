<?php

namespace Rangkotodotcom\Pushnotif\Validators;

use Illuminate\Support\Facades\Validator;
use Rangkotodotcom\Pushnotif\Exceptions\PushnotifValidationException;

class InformationFormValidation implements Validation
{
    /**
     * @param array $data
     * @return array
     * @throws PushnotifValidationException
     */
    public static function validate(array $data, string $action = 'created'): array
    {
        $rules = [
            'client_id'     => 'bail|required|uuid',
            'title'         => 'bail|required|string',
            'sub_title'     => 'bail|required|string',
            'target'        => 'bail|required|in:all,pd,ptk',
            "{$action}_by"  => 'bail|required|string|max:255',
        ];

        if ($action == 'updated') {
            $rules['slug'] = 'bail|required|string';
        }

        $optionalRules = [
            'content'           => 'bail|string',
            'content_images'    => 'bail|array',
            'content_images.*'  => 'bail|string',
            'image'             => 'bail|array:thumbnail,detail',
            'image.thumbnail'   => 'bail|string',
            'image.detail'      => 'bail|string',
            'status'            => 'bail|boolean',
        ];

        foreach ($optionalRules as $key => $rule) {
            if (!empty($data[$key])) {
                $rules[$key] = $rule;
            }
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new PushnotifValidationException($validator);
        }

        return $validator->validate();
    }
}
