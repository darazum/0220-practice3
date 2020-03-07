<?php
namespace App\Model;

use Base\Db;
use Base\Model;

class User extends Model
{
    protected $id;
    protected $data;
    protected $idField = 'id';
    protected static $table = 'users';

    public function setName(string $name): self
    {
        $this->set('name', $name);
        return $this;
    }

    public function setBirthDate(string $date): self
    {
        $this->set('birth_date', $date);
        return $this;
    }

    public function setPhotoId(int $id): self
    {
        $this->set('photo_id', $id);
        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->set('password', $password);
        return $this;
    }

    public function getName()
    {
        return $this->get('name');
    }

    public function getBithday()
    {
        return $this->get('birth_date');
    }

    public function getAvatarUrl()
    {
        $file = new File();
        $file->getById((int)$this->get('photo_id'));
        return '/upload/user/' . $this->getId() . '/' . $file->name;
    }

    public function updateUserPhoto(int $userId, int $fileId)
    {
        $table = static::$table;
        $update = "UPDATE $table SET photo_id = $fileId WHERE id = $userId";
        Db::getInstance()->exec($update, __METHOD__);
    }
}