<?php

namespace Rangkotodotcom\Pushnotif\Validators;

use Illuminate\Support\Facades\Validator;
use Rangkotodotcom\Pushnotif\Exceptions\PushnotifValidationException;

class NotificationTransactionFormValidation implements Validation
{
    /**
     * @param array $data
     * @return array
     * @throws PushnotifValidationException
     */
    public static function validate(array $data): array
    {
        $rules = [
            'user_id'           => 'bail|required',
            'client_id'         => 'bail|required|uuid',
            'mode'              => 'bail|required|string|in:template',
            'template_id'       => 'bail|required|string',
            'params'            => 'bail|required|array:title,body',
            'params.title'      => 'bail|required|array',
            'params.body'       => 'bail|required|array',
            'data'              => 'bail|required|array',
            'type'              => 'bail|required|integer|in:1',
            'transaction_id'    => 'bail|required|string',
            'transaction_type'  => 'bail|required|string',
            'delay'             => 'bail|required|integer'
        ];


        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new PushnotifValidationException($validator);
        }

        return  $validator->validate();
    }
}
