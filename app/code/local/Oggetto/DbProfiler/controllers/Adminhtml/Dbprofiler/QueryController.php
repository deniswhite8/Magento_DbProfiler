<?php
/**
 * Oggetto Web extension for Magento
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade
 * the Oggetto DbProfiler module to newer versions in the future.
 * If you wish to customize the Oggetto DbProfiler module for your needs
 * please refer to http://www.magentocommerce.com for more information.
 * 
 * @category  Oggetto
 * @package   Oggetto_DbProfiler
 * @copyright Copyright (C) 2014, Oggetto Web (http://oggettoweb.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * Query admin controller
 *
 * @category    Oggetto
 * @package     Oggetto_DbProfiler
 */
class Oggetto_DbProfiler_Adminhtml_Dbprofiler_QueryController
    extends Mage_Adminhtml_Controller_Action {

     /**
     * Default view action
      *
     * @return void
     */
    public function indexAction() {
        $this->loadLayout();
        $this->_title(Mage::helper('oggetto_dbprofiler')->__('Oggetto Db Profiler'));
        $this->renderLayout();
    }

    /**
     * clear all queries action
     *
     * @return void
     */
    public function clearAction() {
        $queryCollection = Mage::getResourceModel('oggetto_dbprofiler/query_collection');

        foreach ($queryCollection as $query) {
            $query->delete();
        }
    }
    
	/**
     * Save config action
     *
     * @return void
     */
    public function saveConfigAction() {
        $helper = Mage::helper('oggetto_dbprofiler');

        $helper->setModuleNamespace($this->getRequest()->getParam('module_namespace'));
        $helper->setTableNamespace($this->getRequest()->getParam('table_namespace'));
    }

    /**
     * Ajax action
     *
     * @return void
     */
    public function ajaxAction() {
        $queryCollection = Mage::getResourceModel('oggetto_dbprofiler/query_collection');
        $result = '';

        foreach ($queryCollection as $query) {
            $result .= '<pre>' . Mage::helper('oggetto_dbprofiler')->mysqlDebug($query->getText()) . '</pre>' .
                       '<small><i>' . $query->getElapsedTime() . ' seconds</i></small><br>' .
                       '<small>' . $query->getFileLine() . '</small><br><br>';
        }

        $this->getResponse()->setBody($result);
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @return boolean
     */
    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('system/oggetto_dbprofiler');
    }
}
