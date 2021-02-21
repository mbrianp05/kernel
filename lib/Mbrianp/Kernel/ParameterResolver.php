<?php

namespace Mbrianp\FuncCollection\Kernel;

use Mbrianp\FuncCollection\DIC\DIC;
use ReflectionParameter;

interface ParameterResolver
{
    public function __construct(DIC $dependenciesContainer);

    public function supports(ReflectionParameter $parameter): bool;

    public function resolve(): mixed;
}