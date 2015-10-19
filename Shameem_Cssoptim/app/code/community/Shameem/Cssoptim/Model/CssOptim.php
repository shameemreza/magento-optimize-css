<?php
/**
 *
 * @package     Shameem_Cssoptim
 * @author      Shameem Reza
 * @copyright   2015
 * @email       adaptcoder@gmail.com
 * @license     https://opensource.org/licenses/MIT
 */



/**
 *
 * @method string getCss()
 * @method Shameem_Cssoptim_Model_Observer setCss()
 * @method string getResourceUrl()
 * @method Shameem_Cssoptim_Model_Observer setResourceUrl()
 * @method string getRefererUrl()
 * @method Shameem_Cssoptim_Model_Observer setRefererUrl()
 */
class Shameem_Cssoptim_Model_CssOptim  extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('cssoptim/cssOptim');
    }
    
    public function getHtml()
    {
        return '<style css-optim-data-url="' . $this->getResourceUrl() . '">' . PHP_EOL . 
            Mage::getStoreConfig('cssoptim/general/required_before_all') . PHP_EOL .
            $this->getCss() . PHP_EOL . 
            Mage::getStoreConfig('cssoptim/general/required_after_all') . PHP_EOL .
            '</style>';
    }
    
}