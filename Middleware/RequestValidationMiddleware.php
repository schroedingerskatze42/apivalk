<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Middleware;

use apivalk\ApivalkPHP\Documentation\Property\Validator\ValidatorResult;
use apivalk\ApivalkPHP\Http\Request\Parameter\ParameterBag;
use apivalk\ApivalkPHP\Http\Request\ApivalkRequestInterface;
use apivalk\ApivalkPHP\Http\Response\AbstractApivalkResponse;
use apivalk\ApivalkPHP\Http\Response\BadValidationApivalkResponse;
use apivalk\ApivalkPHP\Http\Response\ErrorObject;

class RequestValidationMiddleware implements MiddlewareInterface
{
    /** @var ErrorObject[] */
    private $errors = [];

    public function process(ApivalkRequestInterface $request, string $controllerClass, callable $next): AbstractApivalkResponse
    {
        $this->errors = [];

        $documentation = $request::getDocumentation();

        $this->validateProperties(
            $documentation->getBodyProperties(),
            $request->body()
        );

        $this->validateProperties(
            $documentation->getQueryProperties(),
            $request->query()
        );

        $this->validateProperties(
            $documentation->getPathProperties(),
            $request->path()
        );

        if (\count($this->errors) > 0) {
            return new BadValidationApivalkResponse($this->errors);
        }

        return $next($request);
    }

    private function validateProperties(
        array $properties,
        ParameterBag $parameterBag
    ): void {
        foreach ($properties as $property) {
            $parameter = $parameterBag->get($property->getPropertyName());

            if ($parameter === null && !$property->isRequired()) {
                continue;
            }

            if ($parameter === null && $property->isRequired()) {
                $this->errors[] = new ErrorObject($property->getPropertyName(), ValidatorResult::FIELD_IS_REQUIRED);
                continue;
            }

            foreach ($property->getValidators() as $validator) {
                $validationResult = $validator->validate($parameter->getValue());

                if (!$validationResult->isSuccess()) {
                    $this->errors[] = new ErrorObject(
                        $property->getPropertyName(), $validationResult->getMessage()
                    );
                }
            }
        }
    }
}
