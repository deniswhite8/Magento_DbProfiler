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
 * DbProfiler module install script
 *
 * @category    Oggetto
 * @package     Oggetto_DbProfiler
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('oggetto_dbprofiler/query'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Query ID')
    ->addColumn('text', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'Text')

    ->addColumn('elapsed_time', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
        'nullable'  => false,
        ), 'Elapsed time')

    ->addColumn('file_line', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'File line')
    ->setComment('Query Table');
$this->getConnection()->createTable($table);
$this->endSetup();
