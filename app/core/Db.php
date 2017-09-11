<?php

/**
 * Db Class - MySQL
 *
 * @note Do not want to use mysql? You'll probably need to write your own Db Class
 * @note But, I believe in you! And the MIT License says GO FOR IT.
 */

namespace Bc\App\Core;

use PDO;
use PDOException;

class Db {

    private $bc;
    private $db;
    private $dbname;
    private $user;
    private $password;
    private $host;
    private $port;
    private $charset;

    /**
     * Do not store DB info in the code / version control!
     */
    public function __construct(
            $bc,
            $dbname,
            $user,
            $password,
            $host = 'localhost',
            $port = '3306',
            $charset = 'utf8mb4'
        ) {

        $this->bc = $bc;
        $this->dbname = $dbname;
        $this->user = $user;
        $this->password = $password;
        $this->host = $host;
        $this->port = is_null($port) ? '3306' : $port;
        $this->charset = $charset;

        return $this;
    }

    protected function triggerError(
        $errorCode = 500,
        $message   = 'Could not connect to the database.',
        $query     = '',
        $params = []
    ) {
        Util::triggerError(
            $this->bc,
            $this->bc->getSetting('errorRoute'),
            [
                'success'    => false,
                'error_code' => $errorCode,
                'message'    => $message,
                'query'      => $query,
                'params'     => $params
            ]
        );
    }

    protected function openConn()
    {
        $dbString = "mysql:host={$this->host};port={$this->port};"
        . (!empty($this->dbname) ? "dbname={$this->dbname};" : '')
        . "charset={$this->charset}";

        try {
            $this->db = new PDO($dbString, "{$this->user}", "{$this->password}");
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $errorCode = ( (int) ($err = $e->getCode()) ) == 0 ? 500 : $err;
            $message   = 'Check Database Permissions or SQL logs: ' . $e->getMessage();
            $this->triggerError($errorCode, $message);
        }

        return $this;
    }

    /**
     * Close db connection
     * @return \Bc\App\Db
     */
    public function closeConn()
    {
        // There is no db connection to close
        if (!isset($this->db)) {
            return $this;
        }

        // Close the db connection
        $this->db = null;
        unset($this->db);

        return $this;
    }

    public function beginTransaction()
    {
        // Open connection automatically
        if (!isset($this->db)) {
            $this->openConn();
        }
        $this->db->beginTransaction();
    }

    public function commit()
    {
        $this->db->commit();
    }

    public function rollBack()
    {
        $this->db->rollBack();
    }

    public function queryInsert(
            $string,
            $params = array()
        ) {
            $this->querySelect($string, $params, null, null);
        }

    public function queryDelete(
            $string,
            $params = array()
        ) {
        $this->querySelect($string, $params, null, null);
    }

    public function queryUpdate(
            $string,
            $params = array()
        ) {
        $this->querySelect($string, $params, null, null);
    }

    public function queryExec($string)
    {
        if (!isset($this->db)) {
            $this->openConn();
        }

        try {
            $this->db->exec($string);
        } catch (Exception $ex) {

            if ($this->db->inTransaction()) {
                $this->rollBack();
                $this->closeConn();
            }

            $errorCode = ( (int) ($err = $ex->getCode()) ) == 0 ? 500 : $err;
            $message   = $ex->getMessage();
            $query     = $string;
            $this->triggerError($errorCode, $message, $query);
        }
    }

    protected function bindParamsByType(&$q, &$params)
    {
        if (is_array($params) && !empty($params)) {
            foreach ($params as $key => $value) {
                $bind = false;

                if (is_int($value)) {
                    $q->bindValue($key, intval($value), PDO::PARAM_INT);
                    $bind = true;
                }
                else if (is_float($value) || is_string($value)) {
                    $q->bindValue($key, strval($value), PDO::PARAM_STR);
                    $bind = true;
                }
                else if (is_null($value)) {
                    $q->bindValue($key, $value, PDO::PARAM_NULL);
                    $bind = true;
                }
                else if (is_bool($value)) {
                    $q->bindValue($key, boolval($value), PDO::PARAM_BOOL);
                    $bind = true;
                }

                if ($bind) {
                    unset($params[$key]);
                }
            }
        }
    }

    protected function handleParamsAndExecute($q, $params)
    {
        $this->bindParamsByType($q, $params);

        if (empty($params)) {
            $q->execute();
        } else {
            $q->execute($params);
        }
    }

    /*
     * This is necessary because
     * if you have any params that are not used in the query, PDO gets mad.
     */
    protected function removeUnusedParams($string, $params)
    {
        $usedParams = [];
        foreach($params as $key => $value) {

            if (preg_match("#\s$key\s#", $string)) {
                $usedParams[$key] = $value;
            }
        }
        return $usedParams;
    }

    public function querySelect(
            $string,
            $params = array(),
            $statementMethod = 'fetchAll',
            $methodArgs = PDO::FETCH_ASSOC
        ) {
        // Open connection automatically
        if (!isset($this->db)) {
            $this->openConn();
        }

        try {

            // Use prepared statements only if needed
            if (empty($params)) {
                $q = $this->db->query($string);
            }
            else {
                $q = $this->db->prepare($string);
                $this->removeUnusedParams($string, $params);
                $this->handleParamsAndExecute($q, $params);
            }

            // If not empty statement method, apply it.
            /** @note if planning to use multiple methods, pass nothing. */
            if (!empty($statementMethod)) {

                $data = (empty($methodArgs)) ?
                        $q->{$statementMethod}() :
                        $q->{$statementMethod}($methodArgs);


                if (!$this->db->inTransaction()) {
                    $this->closeConn();
                }

                return $data;
            }
            else {
                /** @note return statement (remember to close conn) */
                return $q;
            }

        } catch (PDOException $e) {

            if ($this->db->inTransaction()) {
                $this->rollBack();
                $this->closeConn();
            }

            $errorCode = ( (int) ($err = $e->getCode()) ) == 0 ? 500 : $err;
            $this->triggerError($errorCode, $e->getMessage(), $string, $params);
        }
    }
}

