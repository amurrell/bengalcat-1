<?php

namespace Bc\App\Core\Apps\App\Db;

use Bc\App\Core\DbExtender;

class SetupAppDbQueries extends DbExtender {

    protected $db;
    
    public function setup()
    {
        $this->createObjectDatabase();
        $this->createObjectTypesIfNotExists();
        $this->createObjectsIfNotExists();
        $this->createObjectTypesMetasTemplateIfNotExists();
        
        $this->createObjectMetasIfNotExists();
        $this->createObjectRelatedQueriesIfNotExists();
        
        $this->createObjectQueriesIfNotExists();
    }

    public function createObjectDatabase()
    {
        $this->db->queryExec(
            "CREATE DATABASE IF NOT EXISTS `bc_app` DEFAULT CHARACTER SET utf8mb4;
            USE `bc_app`;"
        );
    }
    
    public function createObjectTypesIfNotExists()
    {
        return $this->db->queryExec(
            "CREATE TABLE `object_types` (
            `id` int(11) NOT NULL,
            `type_name` varchar(45) NOT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        );
    }
    
    public function createObjectsIfNotExists()
    {
        return $this->db->queryExec(
           "CREATE TABLE `objects` (
            `id` int(11) NOT NULL,
            `object_type_id` int(11) NOT NULL,
            `status` varchar(45) DEFAULT NULL,
            `created_at` varchar(45) DEFAULT NULL,
            `modified_at` varchar(45) DEFAULT NULL,
            `deleted_at` varchar(45) DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        );
    }
    
    public function createObjectTypesMetasTemplateIfNotExists()
    {
        return $this->db->queryExec(
           "CREATE TABLE `object_types_meta_template` (
            `id` int(11) NOT NULL,
            `object_type_id` int(11) NOT NULL,
            `namespace` varchar(45) NOT NULL,
            `meta_key` varchar(45) NOT NULL,
            `meta_value` varchar(45) DEFAULT NULL,
            `meta_type` varchar(45) DEFAULT NULL,
            `meta_order` int(11) DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        );
    }
    
    public function createObjectMetasIfNotExists()
    {
        return $this->db->queryExec(
           "CREATE TABLE IF NOT EXISTS `object_metas` (
            `id` int(11) NOT NULL,
            `object_id` int(11) NOT NULL,
            `namespace` varchar(45) NOT NULL,
            `meta_key` varchar(45) NOT NULL,
            `meta_value` varchar(45) DEFAULT NULL,
            `meta_type` varchar(45) DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        );
    }
    
    public function createObjectRelatedQueriesIfNotExists()
    {
        return $this->db->queryExec(
           "CREATE TABLE `object_queries` (
            `id` int(11) NOT NULL,
            `object_id` int(11) NOT NULL,
            `query_id` int(11) NOT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        );
    }
    
    public function createObjectQueriesIfNotExists()
    {
        return $this->db->queryExec(
           "CREATE TABLE `queries` (
            `id` int(11) NOT NULL,
            `slug` varchar(45) NOT NULL,
            `json` longtext,
            `sql` longtext,
            `admin_only` tinyint(4) DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        );
    }
}