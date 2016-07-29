<?php

/**
 * @author 
 * @copyright 
 * @package 
 */
class Yli_RedisTest_Model_CatalogSearch_Observer
{
    public function combineData()
    {
        $db = Mage::getStoreConfig('redis/catalogsearch/database');
        $redis = Mage::helper('redis')->init($db);
        $allStores = Mage::app()->getStores();
        foreach ($allStores as $store_id => $val)
        {
            try {
                $pop_datas = $redis->hGetAll('search_query_popularity'.$store_id);
                $transaction = Mage::getModel('core/resource_transaction');
                foreach ($pop_datas as $query_id => $pop_data)
                {
                    $query = Mage::getModel('catalogsearch/query')->load($query_id);
                    $query->setPopularity($query->getPopularity()+$pop_data);
                    $transaction->addObject($query);
                }
                $transaction->save();
                
                $updated_at_datas = $redis->hGetAll('search_query_updated_at'.$store_id);
                $transaction = Mage::getModel('core/resource_transaction');
                foreach ($updated_at_datas as $query_id => $updated_at)
                {
                    $query = Mage::getModel('catalogsearch/query')->load($query_id);
                    $query->setUpdatedAt(Varien_Date::formatDate($updated_at));
                    $transaction->addObject($query);
                }
                $transaction->save();
                
                $redis->del('search_query_popularity'.$store_id);
                $redis->del('search_query_updated_at'.$store_id);
            } catch (Exception $e) {
                Mage::logException($e);
            }

            
        }
    }
}