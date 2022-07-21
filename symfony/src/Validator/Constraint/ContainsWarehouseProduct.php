<?php

namespace App\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

#[\Attribute] class ContainsWarehouseProduct extends Constraint
{
    public string $message = 'Колличество товара на складе не хватает.';

    public function validatedBy(): string
    {
        return static::class . 'Validator';
    }
}