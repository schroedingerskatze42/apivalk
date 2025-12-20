<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Documentation\Property\Validator;

use apivalk\ApivalkPHP\Documentation\Property\AbstractProperty;
use apivalk\ApivalkPHP\Documentation\Property\ArrayProperty;
use apivalk\ApivalkPHP\Documentation\Property\BooleanProperty;
use apivalk\ApivalkPHP\Documentation\Property\NumberProperty;
use apivalk\ApivalkPHP\Documentation\Property\AbstractObjectProperty;
use apivalk\ApivalkPHP\Documentation\Property\StringProperty;

final class ValidatorFactory
{
    public static function create(AbstractProperty $property): AbstractValidator
    {
        if ($property instanceof StringProperty) {
            return new StringValidator($property);
        }

        if ($property instanceof ArrayProperty) {
            return new ArrayValidator($property);
        }

        if ($property instanceof AbstractObjectProperty) {
            return new ObjectValidator($property);
        }

        if ($property instanceof BooleanProperty) {
            return new BooleanValidator($property);
        }

        if ($property instanceof NumberProperty) {
            return new NumberValidator($property);
        }

        // Default validator for unknown properties (e.g. anonymous classes in tests)
        return new class($property) extends AbstractValidator {
            public function validate($value): ValidatorResult
            {
                return new ValidatorResult(true);
            }
        };
    }
}
