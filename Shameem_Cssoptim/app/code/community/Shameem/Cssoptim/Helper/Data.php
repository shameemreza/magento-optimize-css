<?php
/**
 *
 * @package     Shameem_Cssoptim
 * @author      Shameem Reza
 * @copyright   2015
 * @email       adaptcoder@gmail.com
 * @license     https://opensource.org/licenses/MIT
 */



class Shameem_Cssoptim_Helper_Data
{
    const CONFIG_PATH_ENABLED = 'cssoptim/general/enabled';
    
    public function isConfigEnabled()
    {
        return Mage::getStoreConfig(self::CONFIG_PATH_ENABLED);
    }
    
    public function isEnabled()
    {
        return $this->isConfigEnabled() && count(array_intersect(array('catalog_product_view', 'catalog_category_view', 'cms_page'), Mage::app()->getLayout()->getUpdate()->getHandles()));
    }
    
    public function canAdd($resource_url, $referer_url)
    {
        $collection = Mage::getModel('cssoptim/cssOptim')->getCollection();
        
        $collection->getSelect()
            ->where('resource_url = ?', $resource_url)
            ->where('referer_url = ?', $referer_url);
        
        return !count($collection);
    }

}