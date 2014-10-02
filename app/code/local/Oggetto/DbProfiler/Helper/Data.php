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
 * DbProfiler default helper
 *
 * @category    Oggetto
 * @package     Oggetto_DbProfiler
 */
class Oggetto_DbProfiler_Helper_Data
    extends Mage_Core_Helper_Abstract {

    /**
     * Save quotes info
     *
     * @param Zend_Db_Profiler_Query $profilerQuery Profiler query
     * @return void
     */
    public function log($profilerQuery)
    {
        $queryText = $profilerQuery->getQuery();
        $elapsedTime = $profilerQuery->getElapsedSecs();

        try {
            $moduleNamespace = strtolower($this->getModuleNamespace());
            $tablesNamespace = strtolower($this->getTableNamespace());
        } catch (Exception $e) {
            return;
        }

        if (!$moduleNamespace || !$tablesNamespace) {
            return;
        }

        if (strpos(strtolower($queryText), '`' . $tablesNamespace) !== false) {
            foreach (debug_backtrace() as $call) {
                if (strpos(strtolower($call['file']), $moduleNamespace)) {
                    Mage::getModel('oggetto_dbprofiler/query')
                        ->setText($queryText)
                        ->setElapsedTime($elapsedTime)
                        ->setFileLine(split(Mage::getBaseDir(), $call['file'])[1] . ':' . $call['line'])
                        ->save();
                    break;
                }
            }
        }
    }

    /**
     * Query syntax highlighting
     *
     * @param string $query MySql query
     * @return string
     */
    public function mysqlDebug($query) {
        $tmp = htmlspecialchars($query);
        $tmp = str_replace("\r", '', $tmp);
        $tmp = trim(str_replace("\n", "\r\n", $tmp)) . "\r\n";

        $quoteListText = array();
        $quoteListSymbols = array();

        $k = 0;
        $quotes = array();

        // Process shielded quotes
        preg_match_all("/\\\'|\\\&quot;/is", $tmp, $quotes);
        array_unique($quotes);
        if (count($quotes)) {
            foreach($quotes[0] as $i) {
                $k++;
                $quoteListSymbols[$k] = $i;
                $tmp = str_replace($i, '<symbol' . $k . '>', $tmp);
            }
        }

        $matches=Array(
            "/(&quot;|'|`)(.*?)(\\1)/is", // quoted text
            "/\/\*.*?\*\//s",             // comment text
            "/ \-\-.*\x0D\x0A/",          // text ' --' comment
            "/ #.*\x0D\x0A/",             // text ' #' comment
        );

        // Process text
        foreach($matches as $match) {
            $found = array();
            preg_match_all($match, $tmp, $found);
            $quotes = (array)$found[0];
            array_unique($quotes);
            if (count($quotes)) {
                foreach($quotes as $i) {
                    $k++;
                    $quoteListText[$k] = $i;
                    $tmp = str_replace($i, '<text' . $k . '>', $tmp);
                }
            }
        }

        // MySQL reserved keywords
        $keywords = array(
            "avg", "as", "auto_increment", "and", "analyze", "alter",
            "asc", "all", "after", "add", "action", "against",
            "aes_encrypt", "aes_decrypt", "ascii", "abs", "acos",
            "asin", "atan", "authors", "between", "btree", "backup",
            "by", "binary", "before", "binlog", "benchmark", "blob",
            "bigint", "bit_count", "bit_or", "bit_and", "bin",
            "bit_length", "both", "create", "count", "comment",
            "check", "char", "concat", "cipher", "changed", "column",
            "columns", "change", "constraint", "cascade", "checksum",
            "cross", "close", "concurrent", "commit", "curdate",
            "current_date", "curtime", "current_time",
            "current_timestamp", "cast", "convert", "connection_id",
            "coalesce", "case", "conv", "concat_ws", "char_length",
            "character_length", "ceiling", "cos", "cot", "crc32",
            "compress", "delete", "drop", "default", "distinct",
            "decimal", "date", "describe", "data", "desc",
            "dayofmonth", "date_add", "database", "databases",
            "double", "duplicate", "disable", "datetime", "dumpfile",
            "distinctrow", "delayed", "dayofweek", "dayofyear",
            "dayname", "day_minute", "date_format", "date_sub",
            "decode", "des_encrypt", "des_decrypt", "degrees",
            "decompress", "dec", "engine", "explain", "enum",
            "escaped", "execute", "extended", "errors", "exists",
            "enable", "enclosed", "extract", "encrypt", "encode",
            "elt", "export_set", "escape", "exp", "end", "from",
            "float", "flush", "fields", "file", "for", "fast", "full",
            "fulltext", "first", "foreign", "force", "from_days",
            "from_unixtime", "format", "found_rows", "floor", "field",
            "find_in_set", "group", "grant", "grants", "global",
            "get_lock", "greatest", "having", "high_priority",
            "handler", "hour", "hex", "insert", "into", "inner",
            "int", "ifnull", "if", "isnull", "in", "infile", "is",
            "interval", "ignore", "identified", "index", "issuer",
            "integer", "is_free_lock", "inet_ntoa", "inet_aton",
            "instr", "join", "kill", "key", "keys", "left", "load",
            "local", "limit", "like", "lock", "lpad", "last_insert_id",
            "logs", "length", "longblob", "longtext", "last", "lines",
            "low_priority", "locate", "ltrim", "leading", "lcase",
            "lower", "load_file", "ln", "log", "least", "month", "mod",
            "max", "min", "mediumint", "medium", "master", "modify",
            "mediumblob", "mediumtext", "match", "mode", "monthname",
            "mid", "minute", "master_pos_wait", "make_set", "null",
            "not", "now", "none", "new", "numeric", "no", "natural",
            "next", "nullif", "national", "nchar", "on", "or",
            "optimize", "order", "optionally", "option", "outfile",
            "open", "offset", "outer", "old_password", "ord", "oct",
            "octet_length", "primary", "password", "privileges",
            "process", "processlist", "purge", "partial", "procedure",
            "prev", "period_add", "period_diff", "position", "pow",
            "power", "pi", "quick", "quarter", "quote", "right",
            "repair", "restore", "reset", "regexp", "references",
            "replace", "revoke", "reload", "require", "replication",
            "read", "rand", "rename", "real", "restrict",
            "release_lock", "rpad", "rtrim", "repeat", "reverse",
            "rlike", "round", "radians", "rollup", "select", "sum",
            "set", "show", "substring", "smallint", "super", "subject",
            "status", "slave", "session", "start", "share",
            "straight_join", "sql_small_result", "sql_big_result",
            "sql_buffer_result", "sql_cache", "sql_no_cache",
            "sql_calc_found_rows", "second", "sysdate", "sec_to_time",
            "system_user", "session_user", "substring_index", "std",
            "stddev", "soundex", "space", "strcmp", "sign", "sqrt",
            "sin", "straight", "sleep", "text", "truncate", "table",
            "tinyint", "tables", "to_days", "temporary", "terminated",
            "to", "types", "time", "timestamp", "tinytext",
            "tinyblob", "transaction", "time_format", "time_to_sec",
            "trim", "trailing", "tan", "then", "update", "union",
            "using", "unsigned", "unlock", "usage", "use_frm",
            "unix_timestamp", "unique", "use", "user", "ucase",
            "upper", "uuid", "values", "varchar", "variables",
            "version", "variance", "varying", "where", "with",
            "warnings", "write", "weekday", "week", "when", "xor",
            "year", "yearweek", "year_month", "zerofill");

        $replace = array();
        foreach($keywords as $keyword) {
            $replace[] = '/\b' . $keyword . '\b/ie';
        }

        // Highlight function words in the query text
        $tmp = preg_replace($replace, '"<b style=\"color:#39C\">".strtoupper("$0")."</b>"', $tmp);

        // Highlight the numeric values ​​in the query text
        $tmp = preg_replace('/\b([\.0-9]+)\b/', '<b style="color:#339832">\1</b>', $tmp);

        // Highlight brackets in the text of the request
        $tmp = preg_replace('/([\(\)])/', '<b style="color:#D73737">\1</b>', $tmp);

        // Return a quoted string
        if (count($quoteListText)) {
            $quoteListText=array_reverse($quoteListText, true);
            foreach($quoteListText as $k => $i) {
                $tmp=str_replace('<text' . $k . '>', '<span style="color:#777;">' . $i . '</span>', $tmp);
            }
        }

        // Return escapes
        if (count($quoteListSymbols)) {
            $quoteListSymbols = array_reverse($quoteListSymbols, true);
            foreach($quoteListSymbols as $k => $i) {
                $tmp = str_replace('<symbol' . $k.'>', $i, $tmp);
            }
        }

        // Return highlighted text query
        return trim($tmp);
    }


    /**
     * Get module namespace
     *
     * @return string
     */
    public function getModuleNamespace()
    {
        return Mage::getStoreConfig('oggetto_dbprofiler/data/module_namespace');
    }

    /**
     * Get table namespace
     *
     * @return string
     */
    public function getTableNamespace()
    {
        return Mage::getStoreConfig('oggetto_dbprofiler/data/table_namespace');
    }

    /**
     * Set module namespace
     *
     * @param string $value Value
     * @return void
     */
    public function setModuleNamespace($value)
    {
        Mage::getModel('core/config')->saveConfig('oggetto_dbprofiler/data/module_namespace', $value);
    }

    /**
     * Set table namespace
     *
     * @param string $value Value
     * @return void
     */
    public function setTableNamespace($value)
    {
        Mage::getModel('core/config')->saveConfig('oggetto_dbprofiler/data/table_namespace', $value);
    }
}
