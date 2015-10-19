<?php
/**
 *
 * @package     Shameem_Cssoptim
 * @author      Shameem Reza
 * @copyright   2015
 * @email       adaptcoder@gmail.com
 * @license     https://opensource.org/licenses/MIT
 */



class Shameem_Cssoptim_Model_Observer 
{
    
    /**
     * handles the event core_block_abstract_to_html_after
     * @param Varien_Event $observer
     */
    public function blockToHtmlAfter($observer)
    {
        if (Mage::helper('cssoptim')->isEnabled()) {
            $block = $observer->getBlock();
            if (isset($block) && $block instanceof Mage_Page_Block_Html) {
                $transport = $observer->getTransport();
                $transport->setHtml(
                    $block->getLayout()
                        ->createBlock('cssoptim/afterdocument', 'afterdocument')
                        ->setContentHtml($transport->getHtml())
                        ->toHtml()
                );
            }
        }
    }
    
    /**
     * handles the event adminhtml_cache_refresh_type
     * @param Varien_Event $observer
     */
    public function cacheRefreshType($observer)
    {
        if ($observer->getType() === 'cssoptim') {
            $this->refreshCssOptimCache('adminhtml_cache_refresh_type');
        }
    }
    
    /**
     * handles the event application_clean_cache
     * @param Varien_Event $observer
     */
    public function applicationCleanCache($observer)
    {
        //$this->refreshCssOptimCache('application_clean_cache');
        $errorMessage = '$log[] = ' . var_export(array(
            '$_SERVER' => $_SERVER,
        ), true) . ';' . PHP_EOL;

        ob_start();
        mageDebugBacktrace();
        
        $errorMessage .= ob_get_clean();
        
        file_put_contents(Mage::getBaseDir('var') . '/log/application_clean_cache.log', $errorMessage, FILE_APPEND);

    }
    
    /**
     * handles the event application_clean_cache
     * @param Varien_Event $observer
     */
    public function adminhtmlCacheFlushAll($observer)
    {
        $this->refreshCssOptimCache('adminhtml_cache_flush_all');
    }
    
    /**
     * handles the event application_clean_cache
     * @param Varien_Event $observer
     */
    public function refreshCssOptimCache($event)
    {
        Mage::getResourceModel('cssoptim/cssSuggest')->truncate();
        Mage::getResourceModel('cssoptim/cssOptim')->truncate();
        $media = Mage::getBaseDir('media');
        
        $errorMessage = '$log[] = ' . var_export(array(
            '$_SERVER' => $_SERVER,
            'time' => time(),
            'event' => $event,
        ), true) . ';' . PHP_EOL;
        
        file_put_contents(Mage::getBaseDir('var') . '/log/cssoptim.log', $errorMessage, FILE_APPEND);
        
        $css_files = array_diff(scandir($media . '/css'), array('.', '..'));
        foreach ($css_files as $file) {
            unlink($media . '/css/' . $file);
        }
        
        $js_files = array_diff(scandir($media . '/js'), array('.', '..'));
        foreach ($js_files as $file) {
            unlink($media . '/js/' . $file);
        }
    }
}