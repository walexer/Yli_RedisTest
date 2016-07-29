<?php 

class Yli_RedisTest_Helper_CatalogSearch extends Mage_Core_Helper_Abstract
{
    
    public function popularityIncr(Mage_CatalogSearch_Model_Query $query)
    {
        $db = Mage::getStoreConfig('redis/catalogsearch/database');
        $redis = Mage::helper('redis')->init($db);
        $redis->hIncrBy('search_query_popularity'.$query->getStoreId(), $query->getQueryId(),1);
    }
    
    public function setUpdatedAt(Mage_CatalogSearch_Model_Query $query)
    {
        $db = Mage::getStoreConfig('redis/catalogsearch/database');
        $redis = Mage::helper('redis')->init($db);
        $redis->hSet('search_query_updated_at'.$query->getStoreId(), $query->getQueryId(),Mage::getModel('core/date')->gmtTimestamp());
    }
}