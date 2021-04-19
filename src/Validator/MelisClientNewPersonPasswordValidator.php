<?php

namespace MelisCommerce\Validator;

use Laminas\Validator\AbstractValidator;

class MelisClientNewPersonPasswordValidator extends AbstractValidator
{
    const NO_PASSWORD = 'noPassword';

    protected $messageTemplates = [
        self::NO_PASSWORD => "Password is required for new Contact",
    ];

    public function __construct($options = [])
    {
        
    }

    public function isValid($value)
    {
        // $this->setValue($value);

        // if (! is_float($value)) {
        //     $this->error(self::FLOAT);
        //     return false;
        // }

        if (!empty($value)) {

            $this->get('cper_password')->setRequired(true);
            dump($value);
        }

        return true;
    }
}