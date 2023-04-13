<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Service;

/**
 *
 * This service handles the checkout system of MelisCommerce.
 * When calling services, if providing both clientId and anonymous hash key, it will
 * always lead to the automatic transfer of an anonymous basket into a persistent one linked to the clientId
 * 
 * Main services are:
 * - getBasket
 * - addVariantToBasket
 * - removeVariantFromBasket
 * - emptyBasket
 * - cleanAnonymousBaskets >> might be associated with a cron to clean the DB
 *
 */
class MelisComBasketService extends MelisComGeneralService
{
    /**
     * This service get the Item from basket
     */
    public function getBasketItemById($basketId, $type) 
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
    
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_basket_item_start', $arrayParameters);
    
        if ($type == 'persistent') 
            $melisEcomBasketTable = $this->getServiceManager()->get('MelisEcomBasketPersistentTable');
        else
            $melisEcomBasketTable = $this->getServiceManager()->get('MelisEcomBasketAnonymousTable');

        $basketItem = $melisEcomBasketTable->getEntryById($basketId)->current();

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $basketItem;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_basket_item_start', $arrayParameters);

        return $arrayParameters['results'];
    }

    public function getBasketItemByFields($clientId, $clientKey = null, $variantId, $fields = [], $langId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
            
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_basket_item_by_fields_start', $arrayParameters);

        $this->getBasket($arrayParameters['clientId'], $arrayParameters['clientKey']);

        $fields = $arrayParameters['fields'];
        
        if (!is_array($fields))
            $fields = [];

        if ($arrayParameters['clientId'] != null) {
            $type = 'persistent';
            $colPreFix = 'bper';
            $basketTbl = $this->getServiceManager()->get('MelisEcomBasketPersistentTable');
            $fields['bper_client_id'] = $arrayParameters['clientId'];
            $fields['bper_variant_id'] = $arrayParameters['variantId'];
        } else {
            $type = 'anonymous';
            $colPreFix = 'bano';
            $basketTbl = $this->getServiceManager()->get('MelisEcomBasketAnonymousTable');
            $fields['bano_key'] = $arrayParameters['clientKey'];
            $fields['bano_variant_id'] = $arrayParameters['variantId'];
        }

        $basketItem = $basketTbl->getEntryByFields($fields)->current();

        $melisBasket = null;
        if (!empty($basketItem)) {
            $melisBasket = new \MelisCommerce\Entity\MelisBasket();
            
            $melisBasket->setId($basketItem->{$colPreFix.'_id'});
            $melisBasket->setType($type);
            $melisBasket->setVariantId($basketItem->{$colPreFix.'_variant_id'});
            // Getting Variant Object from Variant Service
            $melisBasket->setVariant($this->getServiceManager()->get('MelisComVariantService')
                ->getVariantById($basketItem->{$colPreFix.'_variant_id'}, $arrayParameters['langId']));
            $melisBasket->setQuantity($basketItem->{$colPreFix.'_quantity'});
            $melisBasket->setDateAdded($basketItem->{$colPreFix.'_date_added'});
        }

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $melisBasket;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_basket_item_by_fields_end', $arrayParameters);

        return $arrayParameters['results'];
    }
    /**
     * This service gets the basket of a user
     *
     * @param int|null $langId
     * @param int|null $clientId The clientId to get the basket from
     * @param string|null $clientKey If clientId is null, then this key will make the relation until the client has an account
     * 
     * @return MelisBasket[]
     */
    public function getBasket($clientId, $clientKey = null, $langId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = null;
    
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_basket_get_start', $arrayParameters);
    
        // Transfer to persistent basket if possible
        if ($arrayParameters['clientId'] != null && $arrayParameters['clientKey'] != null)
            $this->transferAnonymousBasketToPersistentBasket($arrayParameters['clientKey'], $arrayParameters['clientId']);
            
        if ($arrayParameters['clientId'] != null)
            $results = $this->getPersistentBasket($arrayParameters['clientId'], $arrayParameters['langId']);
        else
            $results = $this->getAnonymousBasket($arrayParameters['clientKey'], $arrayParameters['langId']);

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_basket_get_end', $arrayParameters);

        return $arrayParameters['results'];
    }
    
    /**
     * This service gets the persistent basket of a user
     *
     * @param int|null $langId
     * @param string|null $clientId If clientId is null, then this key will make the relation until the client has an account
     *
     * @return MelisBasket[]
     */
    public function getPersistentBasket($clientId, $langId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = null;
    
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_basket_persistent_get_start', $arrayParameters);
        
        // Get persistent basket
        $melisEcomBasketPersistentTable = $this->getServiceManager()->get('MelisEcomBasketPersistentTable');
        $basketPersistent = $melisEcomBasketPersistentTable->getEntryByField('bper_client_id', $arrayParameters['clientId']);
        
        $melisComVariantService = $this->getServiceManager()->get('MelisComVariantService');
        
        foreach ($basketPersistent As $val) {
            // Melis Basket Object
            $melisBasket = new \MelisCommerce\Entity\MelisBasket();
            $melisBasket->setId($val->bper_id);
            $melisBasket->setType('persistent');
            $melisBasket->setVariantId($val->bper_variant_id);
            // Getting Variant Object from Variant Service
            $melisBasket->setVariant($melisComVariantService->getVariantById($val->bper_variant_id, $arrayParameters['langId']));
            $melisBasket->setQuantity($val->bper_quantity);
            $melisBasket->setDateAdded($val->bper_date_added);
            // Basket Object added to result
            $results[] = $melisBasket;
        }
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_basket_persistent_get_end', $arrayParameters);
    
        return $arrayParameters['results'];
    }
    
    /**
     * This service gets the anonymous basket of a user
     *
     * @param int $langId
     * @param string|null $clientKey If clientId is null, then this key will make the relation until the client has an account
     *
     * @return MelisBasket[]
     */
    public function getAnonymousBasket($clientKey, $langId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = null;
    
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_basket_anonymous_get_start', $arrayParameters);
    
        // Get anonymous basket
        $melisEcomBasketAnonymousTable = $this->getServiceManager()->get('MelisEcomBasketAnonymousTable');
        $basketAnonymous = $melisEcomBasketAnonymousTable->getEntryByField('bano_key', $arrayParameters['clientKey']);
        
        $melisComVariantService = $this->getServiceManager()->get('MelisComVariantService');
        
        foreach ($basketAnonymous As $val)
        {
            // Getting Melis Basket Object
            $melisBasket = new \MelisCommerce\Entity\MelisBasket();
            $melisBasket->setId($val->bano_id);
            $melisBasket->setType('anonymous');
            $melisBasket->setVariantId($val->bano_variant_id);
            // Getting Variant Object from Variant Service
            $melisBasket->setVariant($melisComVariantService->getVariantById($val->bano_variant_id, $arrayParameters['langId']));
            $melisBasket->setQuantity($val->bano_quantity);
            $melisBasket->setDateAdded($val->bano_date_added);
            // Basket Object added to result
            $results[] = $melisBasket;
        }
    
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_basket_anonymous_get_end', $arrayParameters);

        return $arrayParameters['results'];
    }
    
    /**
     * This service adds a variant to the basket (anonymous or persistent) of the user
     * 
     * @param int $variantId The variantId to add in the basket
     * @param int $quantity Quantity of the product in the basket
     * @param int|null $clientId The clientId to add to persistent basket
     * @param string|null $clientKey If clientId is null, then this key will make the relation until the client has an account
     */
    public function addVariantToBasket($variantId, $quantity, $clientId, $clientKey = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = null;
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_basket_add_variant_start', $arrayParameters);
        
        // Transfer to persistent basket if possible
        if ($arrayParameters['clientId'] != null && $arrayParameters['clientKey'] != null)
            $this->transferAnonymousBasketToPersistentBasket($arrayParameters['clientKey'], $arrayParameters['clientId']);
        
        if ($arrayParameters['clientId'] != null)
            $results = $this->addVariantToPersistentBasket($arrayParameters['variantId'], $arrayParameters['quantity'], $arrayParameters['clientId']);
        else 
            $results = $this->addVariantToAnonymousBasket($arrayParameters['variantId'], $arrayParameters['quantity'], $arrayParameters['clientKey']);
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_basket_add_variant_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
    /**
     * This service adds a variant to the persistent basket of the user
     *
     * @param int $variantId The variantId to add in the basket
     * @param int $quantity Quantity of the product in the basket
     * @param int $clientId The clientId to add to persistent basket
     */
    public function addVariantToPersistentBasket($variantId, $quantity, $clientId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = null;
    
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_basket_persistent_add_variant_start', $arrayParameters);
    
    
        $melisEcomBasketPersistentTable = $this->getServiceManager()->get('MelisEcomBasketPersistentTable');
        // First check if the variantId already exists in the basket
        $persistentData = $this->getBasketItemByFields($arrayParameters['clientId'], null, $arrayParameters['variantId']);
        
        if (!empty($persistentData))
        {
            // If exists, then modify quantity of the existing entry
            $data = array(
                'bper_quantity' => $arrayParameters['quantity'],
            );
            $basketId = $melisEcomBasketPersistentTable->save($data, $persistentData->getId());
        }
        else 
        {
            // If doesn't exist, then add the entry in anonymous table
            $data = array(
                'bper_client_id' => $arrayParameters['clientId'],
                'bper_variant_id' => $arrayParameters['variantId'],
                'bper_quantity' => $arrayParameters['quantity'],
                'bper_date_added' => date('Y-m-d H:i:s')
            );
            $basketId = $melisEcomBasketPersistentTable->save($data);
        }
        
        $results = $basketId;
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_basket_persistent_add_variant_end', $arrayParameters);
    
        return $arrayParameters['results'];
    }
    
    /**
     * This service adds a variant to the anonymous basket of the user
     *
     * @param int $variantId The variantId to add in the basket
     * @param int $quantity Quantity of the product in the basket
     * @param string $clientKey This key will make the relation until the client has an account
     */
    public function addVariantToAnonymousBasket($variantId, $quantity, $clientKey)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = null;
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_basket_anonymous_add_variant_start', $arrayParameters);

        $melisEcomBasketAnonymousTable = $this->getServiceManager()->get('MelisEcomBasketAnonymousTable');
        $anonymousData = $this->getBasketItemByFields(null, $arrayParameters['clientKey'], $arrayParameters['variantId']);

        // First check if the variantId already exists in the basket
        if (!empty($anonymousData))
        {
            // If exists, then modify quantity of the existing entry
            $data = array(
                'bano_quantity' => $arrayParameters['quantity'],
            );
            $basketId = $melisEcomBasketAnonymousTable->save($data, $anonymousData->getId());
        }
        else 
        {
            // If doesn't exist, then add the entry in anonymous table
            $data = array(
                'bano_key' => $arrayParameters['clientKey'],
                'bano_variant_id' => $arrayParameters['variantId'],
                'bano_quantity' => $arrayParameters['quantity'],
                'bano_date_added' => date('Y-m-d H:i:s')
            );
            $basketId = $melisEcomBasketAnonymousTable->save($data);
        }
        
        $results = $basketId;
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_basket_anonymous_add_variant_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
    /**
     * This service removes a variant from the basket (anonymous or persistent) of the user
     *
     * @param int $variantId The variantId to add in the basket
     * @param int $quantity Quantity of the product in the basket to remove
     * @param int|null $clientId The clientId to remove from persistent basket
     * @param string|null $clientKey If clientId is null, then this key will make the relation until the client has an account
     */
    public function removeVariantFromBasket($variantId, $quantity, $clientId, $clientKey = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = null;
    
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_basket_remove_variant_start', $arrayParameters);
    
        // Transfer to persistent basket if possible
        if ($arrayParameters['clientId'] != null && $arrayParameters['clientKey'] != null)
            $this->transferAnonymousBasketToPersistentBasket($arrayParameters['clientKey'], $arrayParameters['clientId']);
        
        if ($arrayParameters['clientId'] != null)
            $results = $this->removeVariantToPersistentBasket($arrayParameters['variantId'], $arrayParameters['quantity'], $arrayParameters['clientId']);
        else
            $results = $this->removeVariantToAnonymousBasket($arrayParameters['variantId'], $arrayParameters['quantity'], $arrayParameters['clientKey']);
    
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_basket_remove_variant_end', $arrayParameters);

        return $arrayParameters['results'];
    }
    
    /**
     * This service removes a variant from the anonymous basket of the user
     *
     * @param int $variantId The variantId to remove in the basket
     * @param int $quantity Quantity of the product in the basket
     * @param string $clientKey This key will make the relation until the client has an account
     */
    public function removeVariantToAnonymousBasket($variantId, $quantity, $clientKey)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = null;
    
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_basket_anonymous_remove_variant_start', $arrayParameters);
    
        $melisEcomBasketAnonymousTable = $this->getServiceManager()->get('MelisEcomBasketAnonymousTable');
        $anonymousData = $this->getBasketItemByFields(null, $arrayParameters['clientKey'], $arrayParameters['variantId']);
        
        // First check if the variantId already exists in the basket
        if (!empty($anonymousData))
        {
            // If exists, then modify quantity of the existing entry or delete if <= 0
            $totalQuantity = $arrayParameters['quantity'];
            if ($totalQuantity <= 0)
            {
                $basketId = $melisEcomBasketAnonymousTable->deleteById($anonymousData->getId());
            }
            else 
            {
                $data = array(
                    'bano_quantity' => $totalQuantity,
                );
                $basketId = $melisEcomBasketAnonymousTable->save($data, $anonymousData->getId());
            }
            
            $results = $basketId;
        }
        // If doesn't exist, then do nothing
    
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_basket_anonymous_remove_variant_end', $arrayParameters);
    
        return $arrayParameters['results'];
    }
    
    /**
     * This service removes a variant from the persistent basket of the user
     *
     * @param int $variantId The variantId to remove in the basket
     * @param int $quantity Quantity of the product in the basket
     * @param int $clientId The clientId for the product to remove from persistent basket
     */
    public function removeVariantToPersistentBasket($variantId, $quantity, $clientId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = null;
    
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_basket_persistent_remove_variant_start', $arrayParameters);
    
        $melisEcomBasketPersistentTable = $this->getServiceManager()->get('MelisEcomBasketPersistentTable');
        $persistentData = $this->getBasketItemByFields($arrayParameters['clientId'], null, $arrayParameters['variantId']);
        // First check if the variantId already exists in the basket
        if (!empty($persistentData))
        {
            // If exists, then modify quantity of the existing entry or delete if <= 0
            $totalQuantity = $arrayParameters['quantity'];
            if ($totalQuantity <= 0)
            {
                $basketId = $melisEcomBasketPersistentTable->deleteById($persistentData->getId());
            }
            else 
            {
                $data = array(
                    'bper_quantity' => $totalQuantity,
                );
                $basketId = $melisEcomBasketPersistentTable->save($data, $persistentData->getId());
            }
            
            $results = $basketId;
        }
        // If doesn't exist, then do nothing
    
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_basket_persistent_remove_variant_end', $arrayParameters);
    
        return $arrayParameters['results'];
    }
    

    /**
     * This service empties a basket for a client
     *
     * @param int|null $clientId The clientId to remove from persistent basket
     * @param string|null $clientKey If clientId is null, then this key will make the relation until the client has an account
     */
    public function emptyBasket($clientId, $clientKey = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = null;
    
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_basket_empty_start', $arrayParameters);    
    
        if ($arrayParameters['clientId'] != null)
            $results = $this->emptyPersistentBasket($arrayParameters['clientId']);
        else
            $results = $this->emptyAnonymousBasket($arrayParameters['clientKey']);
    
    
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_basket_empty_end', $arrayParameters);
    
        return $arrayParameters['results'];
    }
    
    /**
     * This service empties a basket for a specific anonymous client (from its hash key)
     *
     * @param string $clientKey This key will make the relation until the client has an account
     */
    public function emptyAnonymousBasket($clientKey)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = null;
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_basket_anonymous_empty_start', $arrayParameters);
        
        $melisEcomBasketAnonymousTable = $this->getServiceManager()->get('MelisEcomBasketAnonymousTable');
        $anonymous = $melisEcomBasketAnonymousTable->getEntryByField('bano_key', $arrayParameters['clientKey']);
        $anonymousData = $anonymous->current();
        // First check if the clientKey already exists in the basket
        if (!empty($anonymousData))
        {
            $melisEcomBasketAnonymousTable->deleteByField('bano_key', $arrayParameters['clientKey']);
            $results = $arrayParameters['clientKey'];
        }
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_basket_anonymous_empty_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
    
    /**
     * This service empties a basket for a specific client (from its clientId)
     *
     * @param string $clientId The clientId of the client
     */
    public function emptyPersistentBasket($clientId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = null;
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_basket_persistent_empty_start', $arrayParameters);
        
        $melisEcomBasketPersistentTable = $this->getServiceManager()->get('MelisEcomBasketPersistentTable');
        $persistent = $melisEcomBasketPersistentTable->getEntryByField('bper_client_id', $arrayParameters['clientId']);
        $persistentData = $persistent->current();
        // First check if the variantId already exists in the basket
        if (!empty($persistentData))
        {
            $melisEcomBasketPersistentTable->deleteByField('bper_client_id', $arrayParameters['clientId']);
            $results = $arrayParameters['clientId'];
        }
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_basket_persistent_empty_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
    /**
     * This service removes all baskets that are older than $daysToKeep
     * This service might be associated to a cron so that the db is cleaned of baskets
     * on a regular basis
     *
     * @param int $daysToKeep Baskets older than this will be removed
     */
    public function cleanAnonymousBaskets($daysToKeep)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = null;
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_basket_anonymous_clean_start', $arrayParameters);
        
        
        // Remove all anonymous baskets which are older than $daysToKeep
        $melisEcomBasketAnonymousTable = $this->getServiceManager()->get('MelisEcomBasketAnonymousTable');
        $melisEcomBasketAnonymousTable->cleanAnonymousBaskets($daysToKeep);
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_basket_anonymous_clean_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
    /**
     * This service transfers an anonymous basket into a persistent one.
     * This will happen if both $clientKey and $clientId are provided
     * 
     * @param string $clientKey The client anonymous hash key
     * @param int $clientId The client Id
     * @return boolean Result of the transfer operation
     */
    public function transferAnonymousBasketToPersistentBasket($clientKey, $clientId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = false;
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_basket_transfer_start', $arrayParameters);
        
        // Find anonymous basket
        $melisEcomBasketAnonymousTable = $this->getServiceManager()->get('MelisEcomBasketAnonymousTable');
        $basketAnonymous = $melisEcomBasketAnonymousTable->getEntryByField('bano_key', $arrayParameters['clientKey']);
        $basketAnonymousData = $basketAnonymous->toArray();
        
        // Find client account
        $melisEcomClientTable = $this->getServiceManager()->get('MelisEcomClientTable');
        $client = $melisEcomClientTable->getEntryById($arrayParameters['clientId']);
        $clientData = $client->current();
        
        // If anonymous basket AND client account found, then move all anonymous entries of client into persistent table
        if (!empty($basketAnonymousData) && !empty($clientData))
        {
            
            $melisEcomBasketPersistentTable = $this->getServiceManager()->get('MelisEcomBasketPersistentTable');
            foreach ($basketAnonymousData As $val)
            {
                // Creating Data array and save to Basket Persistent
                $data = array(
                    'bper_client_id' => $clientData->cli_id,
                    'bper_variant_id' => $val['bano_variant_id'],
                    'bper_date_added' => $val['bano_date_added']
                );
                
                // checking if the variantId already exists in the basket
                $persistentData = $this->getBasketItemByFields($arrayParameters['clientId'], null, $val['bano_variant_id']);

                $basketId = null;
                if (!empty($persistentData))
                {
                    // Add Quantity both baskets and Update Persistent
                    $data['bper_quantity'] = $val['bano_quantity'] + $persistentData->getQuantity();
                    $basketId = $persistentData->getId();
                }
                else 
                {
                    // Create new entry for Persistent
                    $data['bper_quantity'] = $val['bano_quantity'];
                }

                $data = $this->sendEvent('meliscommerce_service_basket_transfer_anonymous_to_persistent', 
                    ['persistent' => $data, 'anonymous' => $val])['persistent'];

                $melisEcomBasketPersistentTable->save($data, $basketId);
                
                // Deleting Anonymous entry after saving to Persistent table
                $melisEcomBasketAnonymousTable->deleteById($val['bano_id']);
            }
            $results = true;
        }
        else 
        {
            // If anonymous basket not found or client account not found
            $results = false;
        }
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_basket_transfer_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }

    public function deleteVariantFromBasketByVariantId($variantId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = false;
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_basket_transfer_start', $arrayParameters);

        // Removing variants from Anonymous Basket 
        $basketAnonymousTable = $this->getServiceManager()->get('MelisEcomBasketAnonymousTable');
        $basketAnonymousTable->deleteByField('bano_variant_id', $variantId);

        // Removing variants from Persistent Basket 
        $basketPersistentTable = $this->getServiceManager()->get('MelisEcomBasketPersistentTable');
        $basketPersistentTable->deleteByField('bper_variant_id', $variantId);

        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_delete_variant_from_basket_by_variant_id_end', $arrayParameters);

        return $arrayParameters;
    }
}