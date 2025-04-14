<?php

namespace Rangkotodotcom\Pushnotif\Validators;

use Illuminate\Support\Facades\Validator;
use Rangkotodotcom\Pushnotif\Exceptions\PushnotifValidationException;

class NotificationJobDeleteFormValidation implements Validation
{
    /**
     * @param array $data
     * @return array
     * @throws PushnotifValidationException
     */
    public static function validate(array $data, string $action = 'created'): array
    {
        $rules = [
            'client_id' => 'bail|required|uuid',
            'job_id'    => 'bail|required|array',
            'job_id.*'  => 'bail|required',
        ];


        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new PushnotifValidationException($validator);
        }

        return $validator->validated();
    }
}
