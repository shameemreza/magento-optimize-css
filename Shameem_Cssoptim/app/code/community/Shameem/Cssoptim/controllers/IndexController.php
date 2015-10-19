<?php
/**
 *
 * @package     Shameem_Cssoptim
 * @author      Shameem Reza
 * @copyright   2015
 * @email       adaptcoder@gmail.com
 * @license     https://opensource.org/licenses/MIT
 */



class Shameem_Cssoptim_IndexController extends Mage_Core_Controller_Front_Action
{
    const CONFIG_PATH_MINIMAL_MATCHES = 'cssoptim/general/minimal_matches';
    
    public function indexAction()
    {
        if (Mage::helper('cssoptim')->isConfigEnabled()) {
            $request = $this->getRequest();
            $css = trim($request->getParam('css'));

            $resource_url = $request->getParam('url');
            $referer_url = $_SERVER['HTTP_REFERER'];
            if ($css && Mage::helper('cssoptim')->canAdd($resource_url, $referer_url)) {

                if (strpos($resource_url, Mage::getBaseUrl('media')) === 0) {
                    $ip = $_SERVER['REMOTE_ADDR'];

                    $cssSuggest = Mage::getModel('cssoptim/cssSuggest');
                    $collection = $cssSuggest->getCollection();
                    $select = $collection->getSelect();
                    $select->where('ip = ?', $ip)
                        ->where('resource_url = ?', $resource_url)
                        ->where('referer_url = ?', $referer_url)
                    ;

                    if (count($collection)) {
                        echo "already exists\n";
                    } else {
                        $cssSuggest->setCss($css)
                            ->setIp($ip)
                            ->setResourceUrl($resource_url)
                            ->setRefererUrl($referer_url)
                            ->save()
                        ;

                        echo "suggest added\n";
                        $collection = $cssSuggest->getCollection();
                        $select
                            ->reset(Zend_Db_Select::WHERE)
                            ->where('resource_url = ?', $resource_url)
                            ->where('referer_url = ?', $referer_url)
                            ->group('resource_url')
                            ->group('referer_url')
                            ->group('css')
                            ->reset(Zend_Db_Select::COLUMNS)
                            ->columns(array('count' => 'count(*)'))
                        ;

                        $readConnection = Mage::getSingleton('core/resource')->getConnection('core_read');

                        if ($readConnection->fetchOne($select) >= Mage::getStoreConfig(self::CONFIG_PATH_MINIMAL_MATCHES)) {
                            $cssSuggest->approve();
                            echo "suggestion approved\n";
                        }
                    }
                } else {
                    echo "invalid resource\n";
                }
            } else {
                echo "previously added\n";
            }
        }
        die();
    }
}