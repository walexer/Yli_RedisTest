<?php

/**
 * Catalog search query resource model
 *
 * @category   Yli
 * @package    Yli_RedisTest
 * @author     Walexer 
 */
class Yli_RedisTest_Model_CatalogSearch_Resource_Query extends Mage_CatalogSearch_Model_Resource_Query
{
    /**
     * Enter description here ...
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Mage_CatalogSearch_Model_Resource_Query
     */
    public function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        if($object->isObjectNew()) {
            $object->setUpdatedAt($this->formatDate(Mage::getModel('core/date')->gmtTimestamp()));
        }else{
            Mage::helper('redistest/catalogSearch')->setUpdatedAt($object);
        }               
        return $this;
    }
}