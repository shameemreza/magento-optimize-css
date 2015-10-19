<?php
/**
 *
 * @package     Shameem_Cssoptim
 * @author      Shameem Reza
 * @copyright   2015
 * @email       adaptcoder@gmail.com
 * @license     https://opensource.org/licenses/MIT
 */



class Shameem_Cssoptim_Model_Resource_CssOptim extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('cssoptim/cssOptim', 'css_optim_id');
    }
    
    public function truncate() {
        $this->_getWriteAdapter()->query('TRUNCATE TABLE '.$this->getMainTable());
        return $this;
    }
}