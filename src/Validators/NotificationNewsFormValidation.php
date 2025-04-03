<?php

namespace Rangkotodotcom\Pushnotif\Validators;

use Illuminate\Support\Facades\Validator;
use Rangkotodotcom\Pushnotif\Exceptions\PushnotifValidationException;

class NotificationNewsFormValidation implements Validation
{
    /**
     * @param array $data
     * @return array
     * @throws PushnotifValidationException
     */
    public static function validate(array $data): array
    {
        $rules = [
            'client_id'     => 'bail|required|uuid',
            'mode'          => 'bail|required|string|in:free',
            'params'        => 'bail|required|array:title,body',
            'params.title'  => 'bail|required|string',
            'params.body'   => 'bail|required|string',
            'data'          => 'bail|required|array',
            'type'          => 'bail|required|integer|in:2',
            'news_id'       => 'bail|required|string',
        ];


        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new PushnotifValidationException($validator);
        }

        return  $validator->validate();
    }
}
