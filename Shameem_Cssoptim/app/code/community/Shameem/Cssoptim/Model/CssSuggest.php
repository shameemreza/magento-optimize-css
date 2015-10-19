<?php
/**
 * @package     Shameem_Cssoptim
 * @author      Shameem Reza
 * @copyright   2015
 * @email       adaptcoder@gmail.com
 * @license     https://opensource.org/licenses/MIT
 */



class Shameem_Cssoptim_Model_CssSuggest  extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('cssoptim/cssSuggest');
    }
    
    /**
     * approves the css suggestion for the page
     * @return boolean
     */
    public function approve()
    {
        return $cssOptim = Mage::getModel('cssoptim/cssOptim')
            ->setData('referer_url', $this->getData('referer_url'))
            ->setData('css', $this->getData('css'))
            ->setData('resource_url', $this->getData('resource_url'))
            ->save() ? true : false;
    }

}