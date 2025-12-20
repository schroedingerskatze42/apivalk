<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Documentation\Property\Validator;

use apivalk\ApivalkPHP\Documentation\Property\AbstractProperty;

abstract class AbstractValidator
{
    /** @var AbstractProperty */
    private $property;

    /**
     * @param int|bool|float|string $value
     *
     * @return ValidatorResult
     */
    abstract public function validate($value): ValidatorResult;

    public function __construct(AbstractProperty $property)
    {
        $this->property = $property;
    }

    public function getProperty(): AbstractProperty
    {
        return $this->property;
    }
}
