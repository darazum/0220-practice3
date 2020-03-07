<?php
namespace Base;

class Model
{
    protected $id;
    protected $data;
    protected $idField = 'id';
    protected static $table;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function getId()
    {
        if (!$this->id && isset($this->data['id'])) {
            $this->setId($this->data['id']);
        }

        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function save()
    {
        $db = Db::getInstance();
        $fields = implode(',', array_keys($this->data));
        $keyValues = array_map(function($value){
            return ':' . $value;
        }, array_keys($this->data));
        $values = implode(',', $keyValues);
        $table = static::$table;
        $insert = "INSERT INTO $table ($fields) VALUES ($values)";
        $data = array_combine(
            $keyValues,
            array_values($this->data)
        );
        $result = $db->exec($insert, __METHOD__, $data);
        $this->setId($db->lastInsertId());
        return $result;
    }

    public function getById(int $id)
    {
        $table = static::$table;
        $select = "SELECT * FROM $table WHERE {$this->idField} = " . (int)$id;
        $data = Db::getInstance()->fetchOne($select, __METHOD__);
        if (!$data) {
            return null;
        }
        $this->data = $data;
    }

    public static function getList(
        int $limit = 10,
        int $offset = 0,
        string $where = '',
        string $order = ''
    ): array {
        $db = Db::getInstance();
        $table = static::$table;
        $select = "SELECT * FROM $table $where LIMIT $limit OFFSET $offset $order";
        $data = $db->fetchAll($select, __METHOD__);

        if(!$data) {
            return  [];
        }

        $result = [];
        foreach ($data as $elem) {
            $model = new static();
            $model->data = $elem;
            $model->setId($elem['id']);
            $result[] = $model;
        }

        return $result;
    }

    protected function get(string $field)
    {
        return $this->data[$field] ?: null;
    }

    protected function set(string $field, $value)
    {
        $this->data[$field] = $value;
        return $this;
    }


}