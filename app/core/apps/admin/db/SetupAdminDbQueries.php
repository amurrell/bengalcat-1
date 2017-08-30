<?php

namespace Bc\App\Core\Apps\Admin\Db;

use Bc\App\Core\DbExtender;

class SetupAdminDbQueries extends DbExtender {

    protected $db;

    public function setup()
    {
        $this->createAdminDatabase();
    }
    
     public function createAdminDatabase()
    {
        $this->db->queryExec(
            "CREATE DATABASE IF NOT EXISTS `bc_admin` DEFAULT CHARACTER SET utf8mb4;
            USE `bc_admin`;"
        );
    }
    
    public function createAppsIfNotExists()
    {
        return $this->db->queryExec(
          "CREATE TABLE `apps` (
            `id` int(11) NOT NULL,
            `name` varchar(45) NOT NULL,
            `status` varchar(45) NOT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        );
    }
    
    public function createAuthAppsIfNotExists()
    {
        return $this->db->queryExec(
          "CREATE TABLE `auth_apps` (
            `id` int(11) NOT NULL,
            `app_id` int(11) NOT NULL,
            `auth_id` int(11) NOT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        );
    }
    
    public function createAuthLoginIfNotExists()
    {
        return $this->db->queryExec(
          "CREATE TABLE `auth_login` (
            `id` int(11) NOT NULL,
            `auth_id` int(11) NOT NULL,
            `handle_key` varchar(45) NOT NULL,
            `handle_value` varchar(45) NOT NULL,
            `password_key` varchar(45) NOT NULL,
            `password_hash` varchar(45) NOT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        );
    }
    
    public function createAppMetatemplateIfNotExists()
    {
        return $this->db->queryExec(
          "CREATE TABLE `app_meta_template` (
            `id` int(11) NOT NULL,
            `app_id` int(11) NOT NULL,
            `namespace` varchar(45) NOT NULL,
            `meta_key` varchar(45) NOT NULL,
            `meta_value` varchar(45) DEFAULT NULL,
            `meta_type` varchar(45) DEFAULT NULL,
            `meta_order` int(11) DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        );
    }
    
    public function createAppMetaIfNotExists()
    {
        return $this->db->queryExec(
          "CREATE TABLE `app_meta` (
            `id` int(11) NOT NULL,
            `app_id` int(11) NOT NULL,
            `meta_key` varchar(45) NOT NULL,
            `meta_value` varchar(45) DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        );
    }
    
    public function createAppPermissionsTemplateIfNotExists()
    {
        return $this->db->queryExec(
          "CREATE TABLE `app_permissions_template` (
            `id` int(11) NOT NULL,
            `app_id` int(11) NOT NULL,
            `perm_key` varchar(45) NOT NULL,
            `perm_value` varchar(45) DEFAULT NULL,
            `perm_type` varchar(45) DEFAULT NULL,
            `perm_order` int(11) DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        );
    }
    
    public function createAuthPermissionsIfNotExists()
    {
        return $this->db->queryExec(
          "CREATE TABLE `auth_permissions` (
            `id` int(11) NOT NULL,
            `auth_id` int(11) NOT NULL,
            `app_id` int(11) NOT NULL,
            `perm_key` varchar(45) NOT NULL,
            `perm_value` varchar(45) DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        );
    }
}