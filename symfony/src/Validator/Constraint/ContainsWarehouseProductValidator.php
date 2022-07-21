<?php

namespace App\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ContainsWarehouseProductValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ContainsWarehouseProduct) {
            throw new UnexpectedTypeException($constraint, ContainsWarehouseProduct::class);
        }
        foreach ($value as $data) {
            $cartQuantity = $data->getQuantity();
            $productInWarehouse = $data->getProduct()->getWarehouse();
            if ($productInWarehouse < $cartQuantity) {

                throw new UnexpectedValueException($value, 'warehouse');
            }
        }
    }
}