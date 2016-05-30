<?php

namespace system\components;


class Model
{
    /**
     * @var \PDO
     */
    public $db;

    /**
     * Model table in DB
     * @var string
     */
    public $table;

    /**
     * List of all table columns
     * @var array
     */
    public $fields = [];

    /**
     * @var array
     */
    public $data = [];

    public function __construct()
    {
        $this->db = App::$pdo;
        $this->setFields();
    }

    public function __set($name, $value)
    {
        return $this->data[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        } else {
            return $this->__set($name, null);
        }
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function __unset($name)
    {
        unset($this->data[$name]);
    }

    public function setFields()
    {
        $query = $this->db->prepare("SHOW COLUMNS FROM `{$this->table}`");
        $query->execute();

        while ($row = $query->fetch()) {
            $this->fields[] = $row[0];
        }
    }

    public function loadData(array $source = [])
    {
        if (count($source)) {
            foreach ($source as $key => $value) {
                $this->$key = $value;
            }
            return;
        }

        foreach ($_GET as $key => $value) {
            $this->$key = $value;
        }

        foreach ($_POST as $key => $value) {
            $this->$key = $value;
        }

        foreach ($_FILES as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Example => `id`,`title`,`created_at`
     * Or smthing like that => :id,:title,:created_at
     * @param $prefix string
     * @return string
     */
    public function getFieldsList($prefix = null)
    {
        $list = '';
        foreach ($this->fields as $field) {

            if ($prefix === null) {
                $list .= "`{$field}`";
            } else {
                $list .= $prefix . $field;
            }

            $list .= ',';
        }

        return substr($list, 0, -1);
    }

    /**
     * field_name => value
     * @return string
     */
    public function getValues()
    {
        $values = [];

        foreach ($this->fields as $field) {

            if (isset($this->$field) && !empty($this->$field)) {
                $value = $this->$field;
            } else {
                $value = '';
            }

            $values[$field] = $value;
        }

        return $values;
    }

    public function create()
    {
        $query = $this->db->prepare("INSERT INTO {$this->table} ({$this->getFieldsList()}) VALUES ({$this->getFieldsList(':')})");

        $this->beforeSave();

        if ($query && $query->execute($this->getValues())) {
            $this->id = $this->db->lastInsertId();

            $this->afterSave();

            return $this;
        } else {
            throw new \PDOException('Something goes wrong..');
        }

    }

    public function andWhere(array $params = [])
    {
        $where = '';
        $delimiter = ' AND ';

        foreach ($params as $field => $value) {
            $where .= "`{$field}` = :{$field}";
            $where .= ' AND ';
        }

        $where = substr($where, 0, -strlen($delimiter));

        $query = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$where}");

        $query->execute($params);

        return $query->fetchAll();
    }

    public function orWhere(array $params = [])
    {
        $where = '';
        $delimiter = ' OR ';

        foreach ($params as $field => $value) {
            $where .= "`{$field}` = :{$field}";
            $where .= ' OR ';
        }

        $where = substr($where, 0, -strlen($delimiter));

        $query = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$where}");

        $query->execute($params);

        return $query->fetchAll();
    }

    public function isNewRecord()
    {
        return (!isset($this->id) || empty($this->id));
    }

    public function beforeSave()
    {
        /* For child models */
    }

    public function afterSave()
    {
        /* For child models */
    }
}