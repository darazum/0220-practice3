<?php
namespace Base;

class Db
{
    /** @var \PDO */
    private static $instance;
    private $_pdo;
    public $enableLog = true;
    private $_log = [];

    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function getConnection()
    {
        if (!$this->_pdo) {
            $this->_pdo = new \PDO('mysql:host=localhost;dbname=practice3', 'root', '');
        }

        return $this->_pdo;
    }

    public function fetchAll(string $query, $_method, array $params = [])
    {
        $t = microtime(1);
        $pdo = $this->getConnection();
        $prepared = $pdo->prepare($query);

        $ret = $prepared->execute($params);

        if (!$ret) {
            $errorInfo = $prepared->errorInfo();
            trigger_error("{$errorInfo[0]}#{$errorInfo[1]}: " . $errorInfo[2]);
            return [];
        }

        $data = $prepared->fetchAll(\PDO::FETCH_ASSOC);
        $affectedRows = $prepared->rowCount();
        if ($this->enableLog) {
            $this->_log[] = [$query, microtime(1) - $t, $_method, $affectedRows];
        }
        return $data;
    }

    public function fetchOne(string $query, $_method, array $params = [])
    {
        $t = microtime(1);
        $pdo = $this->getConnection();
        $prepared = $pdo->prepare($query);

        $ret = $prepared->execute($params);

        if (!$ret) {
            $errorInfo = $prepared->errorInfo();
            trigger_error("{$errorInfo[0]}#{$errorInfo[1]}: " . $errorInfo[2]);
            return [];
        }

        $data = $prepared->fetchAll(\PDO::FETCH_ASSOC);
        $affectedRows = $prepared->rowCount();

        if ($this->enableLog) {
            $this->_log[] = [$query, microtime(1) - $t, $_method, $affectedRows];
        }
        if (!$data) {
            return false;
        }
        return reset($data);
    }

    public function exec(string $query, $_method, array $params = [])
    {
        $t = microtime(1);
        $pdo = $this->getConnection();
        $prepared = $pdo->prepare($query);

        $ret = $prepared->execute($params);


        if (!$ret) {
            $errorInfo = $prepared->errorInfo();
            trigger_error("{$errorInfo[0]}#{$errorInfo[1]}: " . $errorInfo[2]);
            return -1;
        }
        $affectedRows = $prepared->rowCount();

        if ($this->enableLog) {
            $this->_log[] = [$query, microtime(1) - $t, $_method, $affectedRows];
        }

        return $affectedRows;
    }

    public function lastInsertId()
    {
        return $this->getConnection()->lastInsertId();
    }

    public function getLog($asHtml = true)
    {
        if (!$this->_log) {
            return '';
        }
        $res = '';
        foreach ($this->_log as $elem) {
            $res = $elem[1] . ': ' . $elem[0] . ' (' . $elem[2] . ') [' . $elem[3] . ']' . "\n";
        }
        if ($asHtml) {
            return '<pre>' . $res .'</pre>';
        } else {
            return $res;
        }
    }


}