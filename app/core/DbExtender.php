<?php

namespace Bc\App\Core;

abstract class DbExtender {

    protected $connections;
    protected $db;
    protected $bc;

    public function __construct($dbName, Core $bc)
    {
        $this->connections = include APP_DIR . 'config/connections.php';
        $this->bc = $bc;

        $db = $this->getDbConnectionData($dbName);

        $this->db = new Db(
            $this->bc,
            !empty($db->name) ? $db->name : '', // allow no database
            $db->user,
            $db->pass,
            $db->host,
            !empty($db->port) ? $db->port : '3306'
        );
    }

    protected function triggerError(
        $errorCode = 500,
        $message = 'Database does not seem to exist.'
    ) {
        Util::triggerError(
            $this->bc,
            $this->bc->getSetting('errorRoute'),
            [
                'success' => false,
                'error_code' => $errorCode,
                'message' => $message
            ]
        );
    }

    protected function getDbConnectionData($dbName)
    {
        $db = isset($this->connections[$dbName])
            ? (object) $this->connections[$dbName]
            : [];

        if (empty($db)) {
            $this->triggerError(501,
                'This route has not been fully implemented. Check Database Connection Exists.'
            );
        }

        $db->host = (empty($db->host)) ? 'localhost' : $db->host;

        return $db;
    }

    public function beginTransaction()
    {
        $this->db->beginTransaction();
        return $this;
    }

    public function commit()
    {
        $this->db->commit();
        return $this;
    }

    public function rollBack()
    {
        $this->db->rollBack();
        return $this;
    }

    public function getDb()
    {
        return $this->db;
    }

    public function setDb($db)
    {
        $this->db = $db;
        return $this;
    }

}