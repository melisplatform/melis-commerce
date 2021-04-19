<?php

namespace MelisCommerce\Entity\Form;

class Client
{
    protected $cli_id;
    protected $cli_status;
    protected $cli_group_id;
    protected $cli_country_id;

    /**
     * Get the value of cli_id
     */ 
    public function getCli_id()
    {
        return $this->cli_id;
    }

    /**
     * Set the value of cli_id
     *
     * @return  self
     */ 
    public function setCli_id($cli_id)
    {
        $this->cli_id = $cli_id;

        return $this;
    }

    /**
     * Get the value of cli_status
     */ 
    public function getCli_status()
    {
        return $this->cli_status;
    }

    /**
     * Set the value of cli_status
     *
     * @return  self
     */ 
    public function setCli_status($cli_status)
    {
        $this->cli_status = $cli_status;

        return $this;
    }

    /**
     * Get the value of cli_group_id
     */ 
    public function getCli_group_id()
    {
        return $this->cli_group_id;
    }

    /**
     * Set the value of cli_group_id
     *
     * @return  self
     */ 
    public function setCli_group_id($cli_group_id)
    {
        $this->cli_group_id = $cli_group_id;

        return $this;
    }

    /**
     * Get the value of cli_country_id
     */ 
    public function getCli_country_id()
    {
        return $this->cli_country_id;
    }

    /**
     * Set the value of cli_country_id
     *
     * @return  self
     */ 
    public function setCli_country_id($cli_country_id)
    {
        $this->cli_country_id = $cli_country_id;

        return $this;
    }
}

class Person
{
    protected $cper_id;
    protected $cper_lang_id;
    protected $cper_status;
    protected $cper_is_main_person;
    protected $cper_email;
    protected $cper_password;
    protected $cper_civility;
    protected $cper_name;
    protected $cper_middle_name;
    protected $cper_firstname;
    protected $cper_job_title;
    protected $cper_job_service;
    protected $cper_tel_mobile;
    protected $cper_tel_landline;

    

    /**
     * Get the value of cper_id
     */ 
    public function getCper_id()
    {
        return $this->cper_id;
    }

    /**
     * Set the value of cper_id
     *
     * @return  self
     */ 
    public function setCper_id($cper_id)
    {
        $this->cper_id = $cper_id;

        return $this;
    }

    /**
     * Get the value of cper_lang_id
     */ 
    public function getCper_lang_id()
    {
        return $this->cper_lang_id;
    }

    /**
     * Set the value of cper_lang_id
     *
     * @return  self
     */ 
    public function setCper_lang_id($cper_lang_id)
    {
        $this->cper_lang_id = $cper_lang_id;

        return $this;
    }

    /**
     * Get the value of cper_status
     */ 
    public function getCper_status()
    {
        return $this->cper_status;
    }

    /**
     * Set the value of cper_status
     *
     * @return  self
     */ 
    public function setCper_status($cper_status)
    {
        $this->cper_status = $cper_status;

        return $this;
    }

    /**
     * Get the value of cper_is_main_person
     */ 
    public function getCper_is_main_person()
    {
        return $this->cper_is_main_person;
    }

    /**
     * Set the value of cper_is_main_person
     *
     * @return  self
     */ 
    public function setCper_is_main_person($cper_is_main_person)
    {
        $this->cper_is_main_person = $cper_is_main_person;

        return $this;
    }

    /**
     * Get the value of cper_email
     */ 
    public function getCper_email()
    {
        return $this->cper_email;
    }

    /**
     * Set the value of cper_email
     *
     * @return  self
     */ 
    public function setCper_email($cper_email)
    {
        $this->cper_email = $cper_email;

        return $this;
    }

    /**
     * Get the value of cper_password
     */ 
    public function getCper_password()
    {
        return $this->cper_password;
    }

    /**
     * Set the value of cper_password
     *
     * @return  self
     */ 
    public function setCper_password($cper_password)
    {
        $this->cper_password = $cper_password;

        return $this;
    }

    /**
     * Get the value of cper_civility
     */ 
    public function getCper_civility()
    {
        return $this->cper_civility;
    }

    /**
     * Set the value of cper_civility
     *
     * @return  self
     */ 
    public function setCper_civility($cper_civility)
    {
        $this->cper_civility = $cper_civility;

        return $this;
    }

    /**
     * Get the value of cper_name
     */ 
    public function getCper_name()
    {
        return $this->cper_name;
    }

    /**
     * Set the value of cper_name
     *
     * @return  self
     */ 
    public function setCper_name($cper_name)
    {
        $this->cper_name = $cper_name;

        return $this;
    }

    /**
     * Get the value of cper_middle_name
     */ 
    public function getCper_middle_name()
    {
        return $this->cper_middle_name;
    }

    /**
     * Set the value of cper_middle_name
     *
     * @return  self
     */ 
    public function setCper_middle_name($cper_middle_name)
    {
        $this->cper_middle_name = $cper_middle_name;

        return $this;
    }

    /**
     * Get the value of cper_firstname
     */ 
    public function getCper_firstname()
    {
        return $this->cper_firstname;
    }

    /**
     * Set the value of cper_firstname
     *
     * @return  self
     */ 
    public function setCper_firstname($cper_firstname)
    {
        $this->cper_firstname = $cper_firstname;

        return $this;
    }

    /**
     * Get the value of cper_job_title
     */ 
    public function getCper_job_title()
    {
        return $this->cper_job_title;
    }

    /**
     * Set the value of cper_job_title
     *
     * @return  self
     */ 
    public function setCper_job_title($cper_job_title)
    {
        $this->cper_job_title = $cper_job_title;

        return $this;
    }

    /**
     * Get the value of cper_job_service
     */ 
    public function getCper_job_service()
    {
        return $this->cper_job_service;
    }

    /**
     * Set the value of cper_job_service
     *
     * @return  self
     */ 
    public function setCper_job_service($cper_job_service)
    {
        $this->cper_job_service = $cper_job_service;

        return $this;
    }

    /**
     * Get the value of cper_tel_mobile
     */ 
    public function getCper_tel_mobile()
    {
        return $this->cper_tel_mobile;
    }

    /**
     * Set the value of cper_tel_mobile
     *
     * @return  self
     */ 
    public function setCper_tel_mobile($cper_tel_mobile)
    {
        $this->cper_tel_mobile = $cper_tel_mobile;

        return $this;
    }

    /**
     * Get the value of cper_tel_landline
     */ 
    public function getCper_tel_landline()
    {
        return $this->cper_tel_landline;
    }

    /**
     * Set the value of cper_tel_landline
     *
     * @return  self
     */ 
    public function setCper_tel_landline($cper_tel_landline)
    {
        $this->cper_tel_landline = $cper_tel_landline;

        return $this;
    }

}