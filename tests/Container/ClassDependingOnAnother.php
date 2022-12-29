<?php

namespace App\courseProject\tests\Container;
use App\courseProject\tests\Container\SomeClassWithoutDependencies;
use App\courseProject\tests\Container\SomeClassWithParameter;

class ClassDependingOnAnother
{
// Класс с двумя зависимостями
    public function __construct(
        private SomeClassWithoutDependencies $one,
        private SomeClassWithParameter $two,
    ) {
}
}