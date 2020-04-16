<?php

namespace MelisCommerce\Validator;

use Laminas\I18n\Validator\IsFloat;
use Laminas\Session\Container;
class MelisPriceValidator extends IsFloat
{

    const INVALID_PRICE = 'notFloat';
    
    
    protected $messageTemplates = array(
        self::NOT_FLOAT => "'%value%' is an invalid price value",
    );
    
    protected $messageVariables = array(
        
    );
    
    protected $options = array(
        'locale' => '' // default locale value
    );
    
    public function __construct($options = array())
    {

        if (!is_array($options)) {
            $options     = func_get_args();
            $options = $temp;
        }
        
        parent::__construct();
        
        // get the locale of the curren session
        $sessionLocale = 'en_US';
        $container = new Container('meliscore');
        if (!empty($container['melis-lang-locale']))
            //$sessionLocale = $container['melis-lang-locale'];
        
        if($sessionLocale == 'en_EN') {
            $sessionLocale = 'en_US';
        }
        
        // if locale options is set then use the option, if not then use the locale that is set in the session
        $locale = isset($options['locale']) && !empty($options['locale']) ? $options['locale'] : $sessionLocale;
        parent::setLocale($locale);
        foreach($options['messages'] as $messageKey => $messageContent) {
            parent::setMessage($options['messages'][$messageKey]);
        }
        
    }

    
    public function isValid($value)
    {
        $value = str_replace(' ', '', $value);
        parent::setValue($value);
        
        $isValid = true;
        
        if(!parent::isValid($value)) {
            
            parent::error(self::INVALID_PRICE);
            $isValid = false;
            
        }
        
        if((parent::getLocale() == 'en_US' || parent::getLocale() == 'en_EN') && (strpos($value, ',') !== false)) {
            parent::error(self::INVALID_PRICE);
            $isValid = false;
        }
        
        return $isValid;
    }
    

}