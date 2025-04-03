<?php

namespace Rangkotodotcom\Pushnotif\Validators;

use Illuminate\Support\Facades\Validator;
use Rangkotodotcom\Pushnotif\Exceptions\PushnotifValidationException;

class RegisterTokenFormValidation implements Validation
{
    /**
     * @param array $data
     * @return array
     * @throws PushnotifValidationException
     */
    public static function validate(array $data): array
    {
        $rules = [
            'client_type'   => 'bail|required|in:web,mobile',
            'client_id'     => 'bail|required|uuid',
            'user_id'       => 'bail|nullable',
            'user_group'    => 'bail|nullable|string|max:255',
            'token'         => 'bail|required|string',
        ];


        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new PushnotifValidationException($validator);
        }

        return  $validator->validate();
    }
}
