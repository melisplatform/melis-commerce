<?php

namespace MelisCommerce\Validator;

use Laminas\Session\Container;
use Laminas\Validator\AbstractValidator;

class MelisClientMainPersonValidator extends AbstractValidator
{

    const NO_MAIN_PERSON = 'noMainPerson';
    const HAS_MORE_MAIN_PERSON = 'hasMoreMainPerson';
    
    
    protected $messageTemplates = [
        self::NO_MAIN_PERSON => "Client has no selected as Main Person",
        self::HAS_MORE_MAIN_PERSON => "Select ONLY ONE Person as Main Person",
    ];

    private $serviceManager;

    private $data;
    
    private $targetIndex;

    public function __construct($options = [])
    {

        if (!isset($options['serviceManager']) || 
            !$options['serviceManager'] instanceof \Laminas\ServiceManager\ServiceManager) {
            throw new \ErrorException('ServiceManager not found on option');
        }

        $this->serviceManager = $options['serviceManager'];
        unset($options['serviceManager']);

        if (empty($options['dataIndex'])) {
            throw new \ErrorException('Data index not found in the option');
        }

        if ($this->serviceManager->get('request')->isPost()) {

            $this->dataIndex = $options['dataIndex'];

            if (empty($this->serviceManager->get('request')->getPost($this->dataIndex))) {
                throw new \ErrorException('No Data to validate');
            }
    
            $this->data = $this->serviceManager->get('request')->getPost($this->dataIndex);
    
            if (!isset($options['dataIndexTarget'])) {
                throw new \ErrorException('Post index not found on option');
            }
    
            $this->targetIndex = $options['dataIndexTarget'];
    
            $hasTarget = true;
    
            foreach ($this->data As $val) {
                if (!isset($val[$this->targetIndex])) {
                    $hasTarget = false;
                }
            }
    
            if (!$hasTarget) {
                throw new \ErrorException('Post Target index not found on option');
            }
        }

        if (!is_array($options)) {
            $options     = func_get_args();
            $options = $temp;
        }
        
        parent::__construct();
    }

    
    public function isValid($value, $context = null)
    {
        $value = str_replace(' ', '', $value);
        parent::setValue($value);

        $isValid = true;
        $hasMainPerson = false;
        $hasMorePerson = false;

        foreach ($this->data As $val) {

            if ($val[$this->targetIndex] && !$hasMainPerson) {
                $hasMainPerson = true;
            } else {
                if ($val[$this->targetIndex] && $hasMainPerson) {
                    $hasMorePerson = true;
                }
            }
        }

        if (!$hasMainPerson) {
            $this->error(self::NO_MAIN_PERSON);
            $isValid = false;
        } else {
            if ($hasMorePerson) {
                $this->error(self::HAS_MORE_MAIN_PERSON);
                $isValid = false;
            }
        }

        return $isValid;
    }
}