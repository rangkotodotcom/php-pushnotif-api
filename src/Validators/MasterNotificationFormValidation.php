<?php

namespace Rangkotodotcom\Pushnotif\Validators;

use Illuminate\Support\Facades\Validator;
use Rangkotodotcom\Pushnotif\Exceptions\PushnotifValidationException;

class MasterNotificationFormValidation implements Validation
{
    /**
     * @param array $data
     * @return array
     * @throws PushnotifValidationException
     */
    public static function validate(array $data): array
    {
        $rules = [
            'client_id'             => 'bail|required|uuid',
            'name'                  => 'bail|required|string|max:255',
            'total_params'          => 'bail|required|array:title,body',
            'total_params.title'    => 'bail|required|integer',
            'total_params.body'     => 'bail|required|integer',
            'template'              => 'bail|required|array:title,body',
            'template.title'        => 'bail|required|string',
            'template.body'         => 'bail|required|string',
            'example'               => 'bail|required|array:title,body',
            'example.title'         => 'bail|required|string',
            'example.body'          => 'bail|required|string',
            'status'                => 'bail|required|boolean'
        ];


        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new PushnotifValidationException($validator);
        }

        return  $validator->validate();
    }
}
