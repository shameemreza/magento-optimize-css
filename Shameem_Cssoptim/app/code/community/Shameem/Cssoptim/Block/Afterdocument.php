<?php
/**
 *
 * @package     Shameem_Cssoptim
 * @author      Shameem Reza
 * @copyright   2015
 * @email       adaptcoder@gmail.com
 * @license     https://opensource.org/licenses/MIT
 */



class Shameem_Cssoptim_Block_Afterdocument extends Mage_Core_Block_Abstract
{
    private $_postHtml = '';
    private $_reloadCss = array();
    
    public function _toHtml()
    {
        $url = Mage::getBaseUrl('js');
        $baseurl = Mage::getBaseUrl();
        
        $this->_processCssOptim();
        
        //$reload = json_encode($this->_reloadCss);
        
        $html = <<<HTML
{$this->getContentHtml()}
<script>var MAGEURL = "$baseurl";</script>
        
<script type="text/javascript" src="{$url}jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="{$url}restractor.min.js"></script>
{$this->_postHtml}
HTML;

        return $html;
    }
    
    private function _processCssOptim()
    {
        $html = $this->getContentHtml();
        //getRequestString, getPathInfo, getOriginalPathInfo
        $url = Mage::getUrl() . ltrim(Mage::app()->getRequest()->getRequestString(), '/');
        
        $collection = Mage::getModel('cssoptim/cssOptim')
            ->getCollection();
        
        $url = preg_replace('(index\\.php/?$)', '', $url);
        $collection->getSelect()
            ->where('referer_url = ?', $url);
        //echo "<!-- ";
        
        foreach ($collection as $cssOptim) {
            //pagespeed problem
            $url = explode(',', $cssOptim->getResourceUrl());
            $url = $url[0];
            $re = '(<link.*?href="' . preg_quote($url) . '[^>]*/>)';
            if (preg_match($re, $html, $match)) {
                $html = str_replace($match, $cssOptim->getHtml(), $html);
                //echo " one replace $url \n ";
                //$this->_reloadCss[] = $match[0];
            } else {
                //$file = tempnam(Mage::getBaseDir('var') . '/log/', 'debug-cssoptim-');
                //file_put_contents($file, $html);
                //echo "no matches for \n$url in $file\n";
            }
            //var_dump($cssOptim->getResourceUrl());
        }

        //echo ' --> ';
        
        $this->setContentHtml($html);
    }
}