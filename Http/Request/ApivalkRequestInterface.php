<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Http\Request;

use apivalk\ApivalkPHP\Documentation\ApivalkRequestDocumentation;
use apivalk\ApivalkPHP\Http\Method\MethodInterface;
use apivalk\ApivalkPHP\Http\Request\File\FileBag;
use apivalk\ApivalkPHP\Http\Request\Parameter\ParameterBag;
use apivalk\ApivalkPHP\Security\AbstractAuthIdentity;
use apivalk\ApivalkPHP\Router\Route;

interface ApivalkRequestInterface
{
    public static function getDocumentation(): ApivalkRequestDocumentation;

    public function populate(Route $route): void;

    public function getMethod(): MethodInterface;

    public function header(): ParameterBag;

    public function query(): ParameterBag;

    public function body(): ParameterBag;

    public function path(): ParameterBag;

    public function file(): FileBag;

    public function getAuthIdentity(): ?AbstractAuthIdentity;

    public function setAuthIdentity(?AbstractAuthIdentity $authIdentity): void;
}
