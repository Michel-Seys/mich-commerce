<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

/**
 * Class AppType
 * @package App\Form
 */
class AppType extends AbstractType
{
    /**
     *Configuration basique d'un champs
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array
     */
    protected function getConfig(string $label = "", string $placeholder, $options = []): array
    {
        return array_merge_recursive([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ], $options);
    }
}