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
    
Usage
=========
  - In Admin panel select System > Db Profiler.
  - Input module namespace and table namespace or click Clear button.
  - Run you module.
  - Requests will be displayed, if in the call stack which is a file name containing the *module_namespace*, and the text that contains the *`table_namespace*.
