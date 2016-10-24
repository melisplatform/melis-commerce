<?php

namespace MelisCommerce\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use MelisEngine\Model\MelisPage;
use Zend\Filter\HtmlEntities;

class MelisComHeadService implements ServiceLocatorAwareInterface
{
	protected $serviceLocator;
	
	public function setServiceLocator(ServiceLocatorInterface $sl)
	{
		$this->serviceLocator = $sl;
		return $this;
	}
	
	public function getServiceLocator()
	{
		return $this->serviceLocator;
	}	
	
	public function updateTitleAndDescription($typeItem, $idItem, $contentGenerated)
	{
		$newContent = $contentGenerated;
		
		$melisEcomSeoTable = $this->serviceLocator->get('MelisEcomSeoTable');
		$datasComSeo = $melisEcomSeoTable->getEntryByField($typeItem, $idItem);
		if (!empty($datasComSeo))
		{
		    $datasComSeo = $datasComSeo->current();
		    if (!empty($datasComSeo))
		    {
		        $descriptionPage = addslashes($datasComSeo->eseo_meta_description);
		        
		        if ($descriptionPage != '')
		        {
		            $descriptionTag = "\n<meta name='description' content='$descriptionPage' />\n";
		            $descriptionRegex = '/(<meta[^>]*name=[\"\']description[\"\'][^>]*content=[\"\'](.*?)[\"\'][^>]*>)/i';
		            preg_match($descriptionRegex, $contentGenerated, $descriptions);
		            	
		            if (!empty($descriptions))
		            {
		                // Replace existing title in source with the page name
		                $newContent = preg_replace($descriptionRegex, $descriptionTag, $contentGenerated);
		            }
		            else
		            {
		                // Title doesn't exist, look for head tag to add
		                // if no head tag, then nothing will happen
		                $headRegex = '/(<head[^>]*>)/im';
		                $newContent = preg_replace($headRegex, "$1$descriptionTag", $contentGenerated);
		            }
		            	
		            $contentGenerated = $newContent;
		        }
		        
		        /**
		         * Title tag
		         */
		        $titlePage = null;
		        if (!empty($datasComSeo) && !empty($datasComSeo->eseo_meta_title))
		            $titlePage = $datasComSeo->eseo_meta_title;
		        
		        if (!empty($titlePage))
		        {
		            $titleRegex = '/(<title[^>]*>)([^<]+)(<\/title>)/im';
		            preg_match($titleRegex, $contentGenerated, $titles);
		            if (!empty($titles))
		            {
		                // Replace existing title in source with the page name
		                $newContent = preg_replace($titleRegex, "$1$titlePage$3", $contentGenerated);
		            }
		            else
		            {
		                // Title doesn't exist, look for head tag to add
		                // if no head tag, then nothing will happen
		                $headRegex = '/(<head[^>]*>)/im';
		                $titleTag = "\n<title>$titlePage</title>\n";
		                $newContent = preg_replace($headRegex, "$1$titleTag", $contentGenerated);
		            }
		        }
		    }
		}
		
		return $newContent;
	}
}