<?php

namespace Rangkotodotcom\Pushnotif\Exceptions;

use Illuminate\Validation\ValidationException;

class PushnotifValidationException extends ValidationException
{

    public function getErrorBags(): ?array
    {
        return $this->errors();
    }
}
