<?php
namespace App\Model;

use Base\Db;
use Base\Model;

/**
 * Class File
 * @package App\Model
 *
 * @property-read $name
 * @property-read $size
 * @property-read $upload_at
 */
class File extends Model
{
    protected $id;
    protected $data;
    protected $idField = 'id';
    protected static $table = 'files';

    public function __get($name)
    {
        return $this->get($name);
    }

    public static function getFilesByUserId(int $userId)
    {
        $db = Db::getInstance();
        $table = static::$table;
        $select = "SELECT * FROM $table WHERE user_id = $userId ORDER BY id DESC LIMIT 1000";
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
}