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
 * Query collection resource model
 *
 * @category    Oggetto
 * @package     Oggetto_DbProfiler
 */
class Oggetto_DbProfiler_Model_Resource_Query_Collection
    extends Mage_Core_Model_Resource_Db_Collection_Abstract {

    /**
     * Constructor
     *
     * @return Oggetto_DbProfiler_Model_Resource_Query_Collection
     */
    protected function _construct(){
        parent::_construct();
        $this->_init('oggetto_dbprofiler/query');
    }
}
