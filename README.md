Install
=========

  - Set `<profiler>true</profiler>` of `<connection>` section in local.xml. 

  - In lib/Zend/Db/Profiler.php, `queryEnd` method, change:
    ```
        return self::IGNORED;
    }

    return self::STORED;
    ```
    on
    ```
        return self::IGNORED;
    }

    Mage::helper('oggetto_dbprofiler/data')->log($qp->getQuery(), $qp->getElapsedSecs()); // <--- this is

    return self::STORED;
    ```