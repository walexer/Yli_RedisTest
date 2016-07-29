<?php

/**
 * CatalogSearch Result controller
 *
 * @category   Yli
 * @package    Yli_RedisTest
 * @author     Walexer 
 */
require_once(Mage::getModuleDir('controllers','Mage_CatalogSearch').DS.'ResultController.php');
class Yli_RedisTest_CatalogSearch_ResultController extends Mage_CatalogSearch_ResultController
{
    /**
     * Display search result
     */
    public function indexAction()
    {
        $query = Mage::helper('catalogsearch')->getQuery();
        /* @var $query Mage_CatalogSearch_Model_Query */
    
        $query->setStoreId(Mage::app()->getStore()->getId());
    
        if ($query->getQueryText() != '') {
            if (Mage::helper('catalogsearch')->isMinQueryLength()) {
                $query->setId(0)
                ->setIsActive(1)
                ->setIsProcessed(1);
            }
            else {
                if ($query->getId()) {
                    Mage::helper('redistest/catalogSearch')->popularityIncr($query);
                }
                else {
                    $query->setPopularity(1);
                }
                
    
                if ($query->getRedirect()){
                    $query->save();
                    $this->getResponse()->setRedirect($query->getRedirect());
                    return;
                }
                else {
                    $query->prepare();
                }
            }
    
            Mage::helper('catalogsearch')->checkNotes();
    
            $this->loadLayout();
            $this->_initLayoutMessages('catalog/session');
            $this->_initLayoutMessages('checkout/session');
            $this->renderLayout();
    
            if (!Mage::helper('catalogsearch')->isMinQueryLength()) {
                $query->save();
            }
        }
        else {
            $this->_redirectReferer();
        }
    }
}