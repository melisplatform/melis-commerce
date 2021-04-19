<?php

namespace MelisCommerce\Validator;

use Laminas\Validator;
use Laminas\Validator\AbstractValidator;
class MelisPasswordValidator extends AbstractValidator
{
    
    const TOO_SHORT = 'length';
    const NO_LOWER  = 'lower';
    const NO_DIGIT  = 'digit';
    const NO_PASSWORD  = 'noPassword';
    
    protected $messageTemplates = array(
        self::TOO_SHORT => "'%value%' must be at least %min% characters in length",
        self::NO_LOWER  => "'%value%' must contain at least one lowercase letter",
        self::NO_DIGIT  => "'%value%' must contain at least one digit character",
        self::NO_PASSWORD  => 'tr_meliscommerce_common_input_required',
    );
    
    /**
     * @var array
     */
    protected $messageVariables = array(
        'min' => array('options' => 'min'),
    );
    
    protected $options = array(
        'min'      => 8,       // Default/Minimum length
    );

    private $token;
    

    public function __construct($options = array())
    {
        if (!is_array($options)) {
            $options     = func_get_args();
            $temp['min'] = array_shift($options);
            $options = $temp;
        }

        if (empty($options['token']))
            $this->token = $options['token'];
        
        parent::__construct($options);
    }
    
    /**
     * Returns the min option
     *
     * @return int
     */
    public function getMin()
    {
        return $this->options['min'];
    }
    
    /**
     * Sets the min option
     *
     * @param  int $min
     * @throws Exception\InvalidArgumentException
     * @return StringLength Provides a fluent interface
     */
    public function setMin($min)
    {
        $this->options['min'] = max(0, (int) $min);
        return $this;
    }
    

    
    public function isValid($value, $context = null)
    {
        $this->setValue($value);
        
        $isValid = true;
        
        if (strlen($value) < 8) {
            $this->error(self::TOO_SHORT);
            $isValid = false;
        }
        
        if (!preg_match('/[a-z]/', $value)) {
            $this->error(self::NO_LOWER);
            $isValid = false;
        }
        
        if (!preg_match('/\d/', $value)) {
            $this->error(self::NO_DIGIT);
            $isValid = false;
        }
        

        if (!empty($this->token) && isset($context[$this->token])) {
            if (!empty($context[$this->token]) && empty($value)) {
                $this->error(self::NO_PASSWORD);
                $isValid = false;
            }
        }
            
        return $isValid;
    }
}