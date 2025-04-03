<?php

namespace Rangkotodotcom\Pushnotif\Validators;

interface Validation
{
    /**
     * @param array $data
     * @return array
     */
    public static function validate(array $data): array;
}
