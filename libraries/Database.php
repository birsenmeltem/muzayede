<?php

class Database extends \PDO
{
    /**
    * Built SQL Query
    *
    * @var
    *
    */
    private $sql;
    /**
    * Table Name
    *
    * @var
    *
    */
    private $tableName;
    /**
    * Condittions
    *
    * @var
    *
    */
    private $where;
    /**
    * Join Rules
    *
    * @var
    *
    */
    private $join;
    /**
    * Duplıcate Value
    *
    * @var
    *
    */
    private $on_duplicate;
    /**
    * OrderBy Value
    *
    * @var
    *
    */
    private $orderBy;
    private $having;
    /**
    * GroupBy Value
    *
    * @var
    *
    */
    private $groupBy;
    /**
    * Limit Value
    *
    * @var
    *
    */
    private $limit;
    /**
    * $_GET[] parameter
    *
    * @var
    *
    */
    private $page;
    /**
    * Row Count
    *
    * @var
    *
    */
    private $totalRecord;
    /**
    * Page Count
    *
    * @var
    *
    */
    private $pageCount;
    /**
    * Pagination Limit
    *
    * @var
    *
    */
    private $paginationLimit;
    /**
    * HTML generated
    *
    * @var
    *
    */
    private $html;

    private $lang;
    public $mainlang;
    public $Admin = 0;


    public function __construct($charset = 'utf8')
    {
        if (!$settings = parse_ini_file(BASE . 'config/database.ini', TRUE)) throw new exception('DB Dosyası okunamıyor !');

        parent::__construct('mysql:host=' . $settings['database']['hostname'] . ';dbname=' . $settings['database']['db'], $settings['database']['username'], $settings['database']['password']);
        $this->query('SET CHARACTER SET ' . $charset);
        $this->query('SET NAMES ' . $charset);
    }

    public function MainLang($lang)
    {
        $this->mainlang = $lang;
    }

    public function SetLang($lang)
    {
        $this->lang = $lang;
    }

    /**
    * Defines select table operation in sql query
    *
    * @param
    *            $tableName
    * @return $this
    */
    public function from($tableName)
    {
        $this->sql = 'SELECT * FROM ' . $tableName . '';
        $this->tableName = $tableName;
        return $this;
    }

    /**
    * Defines select row operation in sql query
    *
    * @param
    *            $from
    * @return $this
    */
    public function select($from)
    {
        $this->sql = preg_replace('/SELECT (.*?) FROM/i', "SELECT {$from} FROM", $this->sql);
        return $this;
    }

    /**
    * WHERE value at SQL query
    *
    * @param
    *            $column
    * @param
    *            $value
    * @param string $mark
    * @param bool $filter
    * @return $this
    */
    public function where($column, $value = '', $mark = '=', $logical = '&&')
    {
        $this->where[] = [
            $column,
            $value,
            $mark,
            $logical
        ];
        return $this;
    }

    /**
    * Defines -or where- operation in sql query
    *
    * @param
    *            $column
    * @param
    *            $value
    * @param
    *            $mark
    * @return $this
    */
    public function or_where($column, $value, $mark = '=')
    {
        $this->where($column, $value, $mark, '||');
        return $this;
    }

    /**
    * Defines -join- operation in sql query
    *
    * @param
    *            $targetTable
    * @param
    *            $joinSql
    * @param string $joinType
    * @return $this
    */
    public function join($targetTable, $joinSql, $joinType = 'inner')
    {
        $this->join[] = ' ' . strtoupper($joinType) . ' JOIN ' . $targetTable . ' ON ' . sprintf($joinSql, $targetTable, $this->tableName);
        return $this;
    }


    public function on_duplicate_key_update($value)
    {
        if(is_array($value) && count($value)>0) {
            foreach($value as $key => $val)
            {
                $where[] = $key.' = "'.$val.'"';
            }
            $where = rtrim(implode(', ',$where),',');
            if($where) $this->on_duplicate = ' ON DUPLICATE KEY UPDATE '. $where;
        }
        return $this;
    }

    /**
    * Defines -orderby- operation in sql query
    *
    * @param
    *            $columnName
    * @param string $sort
    */
    public function having($columnName)
    {
        $this->having = ' HAVING ' . $columnName;
        return $this;
    }


    public function orderby($columnName, $sort = 'ASC')
    {
        $this->orderBy = ' ORDER BY ' . $columnName . ' ' . strtoupper($sort);
        $this->orderBy = str_replace([' rows ',' mainpage_rows '],[' `rows` ',' `mainpage_rows` '],$this->orderBy);
        return $this;
    }

    /**
    * Defines -groupby- operation in sql query
    *
    * @param
    *            $columnName
    * @return $this
    */
    public function groupby($columnName)
    {
        $this->groupBy = ' GROUP BY ' . $columnName;
        return $this;
    }

    /**
    * Defines -limit- operation in sql query
    *
    * @param
    *            $start
    * @param
    *            $limit
    * @return $this
    */
    public function limit($start, $limit)
    {
        $this->limit = ' LIMIT ' . $start . ',' . $limit;
        return $this;
    }

    /**
    * Used for running Insert/Update/Select operations.
    *
    * @param bool $single
    * @return array|mixed
    */
    public function run($assoc = parent::FETCH_ASSOC)
    {
        $query = $this->generateQuery();
        $values =  $query->fetchAll($assoc);
        return $this->translate($values,'all');
    }

    public function first()
    {
        $query = $this->generateQuery();
        $value = $query->fetch(parent::FETCH_ASSOC);
        return $this->translate($value);
    }

    public function translate($values,$fetch='fetch')
    {
        if($this->mainlang != $this->lang) {
            if($fetch == 'all')
            {
                foreach($values as $key => $val)
                {
                    $rs = $this->query("SELECT lang_content, lang_field FROM langs_translate WHERE lang='{$this->lang}' && lang_table='{$this->tableName}' && record_id='{$val['id']}' ");
                    $rs->execute();
                    $results = $rs->fetchAll(PDO::FETCH_ASSOC);
                    foreach($results as $vals) {
                        $values[$key][$vals['lang_field']] = $vals['lang_content'];
                    }
                }
            }
            else
            {
                $rs = $this->query("SELECT lang_content, lang_field FROM langs_translate WHERE lang='{$this->lang}' && lang_table='{$this->tableName}' && record_id='{$values['id']}' ");
                $rs->execute();
                $results = $rs->fetchAll(PDO::FETCH_ASSOC);
                foreach($results as $key => $val) {
                    $values[$val['lang_field']] = $val['lang_content'];
                }
            }
        }
        return $values;
    }

    public function generateQuery()
    {
        if ($this->join) {
            $this->sql .= implode(' ', $this->join);
            $this->join = null;
        }
        $this->get_where();

        if ($this->having) {
            $this->sql .= $this->having;
            $this->having = null;
        }

        if ($this->on_duplicate) {
            $this->sql .= $this->on_duplicate;
            $this->on_duplicate = null;
        }
        if ($this->groupBy) {
            $this->sql .= $this->groupBy;
            $this->groupBy = null;
        }
        if ($this->orderBy) {
            $this->sql .= $this->orderBy;
            $this->orderBy = null;
        }
        if ($this->limit) {
            $this->sql .= $this->limit;
            $this->limit = null;
        }
        //dump($this->sql);
        $query = $this->query($this->sql);
        return $query;
    }

    /**
    * Runs where operation at query running.
    */
    private function get_where()
    {
        if (is_array($this->where) && count($this->where) > 0) {
            $this->sql .= ' WHERE ';
            $where = [];

            foreach ($this->where as $key => $arg) {

                if ($arg[2] == 'LIKE' || $arg[2] == 'NOT LIKE') {
                    $where[] = $arg[3] . ' ' . $arg[0] . ' ' . $arg[2] . " '%" . $arg[1] . "%' ";
                } elseif ($arg[2] == 'BETWEEN' || $arg[2] == 'NOT BETWEEN') {
                    $where[] = $arg[3] . ' ' . ($arg[0] . ' ' . $arg[2] . ' ' . $arg[1][0] . ' AND ' . $arg[1][1]);
                } elseif ($arg[2] == 'FIND_IN_SET') {
                    $where[] = $arg[3] . ' FIND_IN_SET(' . $arg[0] . ', "' . (is_array($arg[1]) ? implode(',', $arg[1]) : $arg[1]) . '")';
                } elseif ($arg[2] == 'REGEXP REPLACE') {
                    $where[] = $arg[3] . ' ' . $arg[0] . ' REGEXP "[[:<:]]('.(is_array($arg[1]) ? implode('|', $arg[1]) : $arg[1]).')[[:>:]]"';
                } elseif ($arg[2] == 'REGEXP') {
                    $where[] = $arg[3] . ' ' . $arg[0] . " REGEXP '\b".(is_array($arg[1]) ? implode(',', $arg[1]) : $arg[1])."\b'";
                } elseif ($arg[2] == 'IN' || $arg[2] == 'NOT IN') {
                    $where[] = $arg[3] . ' ' . $arg[0] . ' ' . $arg[2] . '(' . (is_array($arg[1]) ? implode(',', $arg[1]) : $arg[1]) . ')';
                } else {
                    if(isset($arg[1])) $where[] = $arg[3] . ' ' . $arg[0] . ' ' . $arg[2] . ' "' . $arg[1] . '"';
                    else $where[] = $arg[3] . ' ' . $arg[0] . '';
                }

            }
            $this->sql .= ltrim(implode(' ', $where), '&&');
            $this->where = null;
        }
    }

    /**
    * Used for insert operation
    *
    * @param
    *            $tableName
    * @return $this
    */
    public function insert($tableName)
    {
        $this->sql = 'INSERT INTO ' . $tableName;
        return $this;
    }

    /**
    * Used for setting data at insert operation.
    *
    * @param
    *            $columns
    * @return bool
    */
    public function set($columns)
    {
        if($this->inserttranslate($columns)) return true;
        $val = [];
        $col = [];
        foreach ($columns as $column => $value) {
            $val[] = $value;
            $col[] = $column . ' = ? ';
        }
        $this->sql .= ' SET ' . implode(', ', $col);
        $this->get_where();
        if ($this->on_duplicate) {
            $this->sql .= $this->on_duplicate;
            $this->on_duplicate = null;
        }

        $this->sql =  str_replace(['mainpage_rows',' rows'],['`mainpage_rows`',' `rows`'],$this->sql);

        $query = $this->prepare($this->sql);
        $result = $query->execute($val);
        if($query->errorCode() != 00000) {
            dump($this->sql);
            dump($query->errorInfo(),1);
        }
        return $result;
    }

    public function inserttranslate($columns)
    {
        if($this->Admin) {
            if($this->mainlang != $this->lang) {
                if($this->where[0][0] == 'id') {
                    foreach ($columns as $column => $value) {
                        if(in_array($column,['id','rows','hash','status','main'])) continue;
                        $q = "INSERT INTO langs_translate SET lang='{$this->lang}', lang_table='{$this->tableName}',
                        lang_field = '{$column}', lang_content='{$value}', record_id='{$this->where[0][1]}' ON DUPLICATE KEY UPDATE lang_content='{$value}'";
                        $this->exec($q);
                    }
                    $this->on_duplicate = $this->where = null;
                    return true;
                }
            }
        }
        return false;
    }

    /**
    * Returns last added Id.
    *
    * @return string
    */
    public function lastId()
    {
        return $this->lastInsertId();
    }

    /**
    * Used for update operation.
    *
    * @param
    *            $columnName
    * @return $this
    */
    public function update($columnName)
    {
        $this->sql = 'UPDATE ' . $columnName;
        $this->tableName = $columnName;
        return $this;
    }

    /**
    * Used for Delete operation
    *
    * @param
    *            $columnName
    * @return $this
    */
    public function delete($columnName)
    {
        $this->sql = 'DELETE FROM ' . $columnName;
        return $this;
    }

    /**
    * Used to complete delete operation.
    *
    * @return int
    */
    public function done()
    {
        $this->get_where();
        $query = $this->exec($this->sql);
        return $query;
    }

    /**
    * Returns total result with -total- table name.
    *
    * @return mixed
    */
    public function total()
    {
        if ($this->join) {
            $this->sql .= implode(' ', $this->join);
            $this->join = null;
        }
        $this->get_where();
        if ($this->orderBy) {
            $this->sql .= $this->orderBy;
            $this->orderBy = null;
        }
        if ($this->groupBy) {
            $this->sql .= $this->groupBy;
            $this->groupBy = null;
        }
        if ($this->limit) {
            $this->sql .= $this->limit;
            $this->limit = null;
        }
        $query = $this->query($this->sql)->fetch(parent::FETCH_ASSOC);
        return $query['total'];
    }

    /**
    * Returns pagination start and limit values.
    *
    * @param
    *            $totalRecord
    * @param
    *            $paginationLimit
    * @param
    *            $pageParamName
    * @return array
    */
    public function pagination($totalRecord, $paginationLimit, $pageParamName)
    {
        $this->paginationLimit = $paginationLimit;
        $this->page = isset($pageParamName) && is_numeric($pageParamName) ? $pageParamName : 1;
        $this->totalRecord = $totalRecord;
        $this->pageCount = ceil($this->totalRecord / $this->paginationLimit);
        $start = max(0,($this->page * $this->paginationLimit) - $this->paginationLimit);
        return [
            'start' => $start,
            'limit' => $this->paginationLimit
        ];
    }

    /**
    * Returns pagination
    *
    * @param
    *            $url
    * @return mixed
    */
    public function showPagination($url, $class = 'active')
    {
        if ($this->totalRecord > $this->paginationLimit) {
            for ($i = $this->page - 100; $i < $this->page + 100 + 1; $i++) {
                if ($i > 0 && $i <= $this->pageCount) {
                    $this->html .= '<li class="page-item ';
                    $this->html .= ($i == $this->page ? $class : null);
                    $this->html .= '"><a class="page-link" href="' . str_replace('[page]', $i, $url) . '">' . $i . '</a>';
                }
            }
            return $this->html;
        }
    }

    /**
    * Returns next page at pagination operation.
    *
    * @return bool
    */
    public function nextPage()
    {
        return ($this->page + 1 < $this->pageCount ? $this->page + 1 : $this->pageCount);
    }

    /**
    * Returns previous page at pagination operation.
    *
    * @return bool
    */
    public function prevPage()
    {
        return ($this->page - 1 > 0 ? $this->page - 1 : 1);
    }

    /**
    * Returns SQL query as string.
    *
    * @return mixed
    */
    public function getSqlString()
    {
        return $this->sql;
    }

    public function between($column, $values = [])
    {
        $this->where($column, $values, 'BETWEEN');
        return $this;
    }

    public function not_between($column, $values = [])
    {
        $this->where($column, $values, 'NOT BETWEEN');
        return $this;
    }

    public function find_in_set($column, $value)
    {
        $this->where($column, $value, 'FIND_IN_SET');
        return $this;
    }

    public function in($column, $value)
    {
        $this->where($column, $value, 'IN');
        return $this;
    }

    public function not_in($column, $value)
    {
        $this->where($column, $value, 'NOT IN');
        return $this;
    }

    public function like($column, $value)
    {
        $this->where($column, $value, 'LIKE');
        return $this;
    }

    public function not_like($column, $value)
    {
        $this->where($column, $value, 'NOT LIKE');
        return $this;
    }

    public function privateQuery($sql)
    {
        $this->sql = $sql;
        return $this;
    }

}