<?php
/**
 *
 * @package     Shameem_Cssoptim
 * @author      Shameem Reza
 * @copyright   2015
 * @email       adaptcoder@gmail.com
 * @license     https://opensource.org/licenses/MIT
 */



/* @var $installer Mage_Core_Model_Resource_Setup */

$installer = $this;

$installer->startSetup();

/**
 * Create table 'Css Suggest'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('cssoptim/cssSuggest'))
    ->addColumn('css_suggest_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Css Suggest Id')
    ->addColumn('css', Varien_Db_Ddl_Table::TYPE_TEXT, 512, array(
        ), 'Css Content')
    ->addColumn('ip', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        ), 'IP')
    ->addColumn('resource_url', Varien_Db_Ddl_Table::TYPE_VARCHAR, 512, array(
        ), 'Resource Url')
    ->addColumn('referer_url', Varien_Db_Ddl_Table::TYPE_VARCHAR, 512, array(
        ), 'Referer Url')
    ->setComment('Css Suggest');
$installer->getConnection()->createTable($table);

/**
 * Create table 'Css Optim'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('cssoptim/cssOptim'))
    ->addColumn('css_optim_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Css Suggest Id')
    ->addColumn('css', Varien_Db_Ddl_Table::TYPE_TEXT, 512, array(
        ), 'Css Content')
    ->addColumn('resource_url', Varien_Db_Ddl_Table::TYPE_VARCHAR, 512, array(
        ), 'Resource Url')
    ->addColumn('referer_url', Varien_Db_Ddl_Table::TYPE_VARCHAR, 512, array(
        ), 'Referer Url')
    ->setComment('Css Optim');
$installer->getConnection()->createTable($table);

$installer->endSetup();
