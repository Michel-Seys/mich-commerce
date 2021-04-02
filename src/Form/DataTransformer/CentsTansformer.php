<?php


namespace App\Form\DataTransformer;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CentsTansformer implements DataTransformerInterface
{

    public function transform($value)
    {
        if ($value === null) {
            return;
        }
        return $value / 100;
    }

    public function reverseTransform($value)
    {
        if ($value === null) {
            return;
        }
        return $value * 100;
    }
}