<?php

/*
 * An example of a RouteExtender that Extends RouteExtender with more specific
 * methods, properties to a particular part of the site that many routes my share.
 *
 */

namespace Bc\App\Core\RouteExtenders;

use Bc\App\Core\Util;

abstract class PortalRouteExtender extends DataRouteExtender {
    
    protected $settings;
    protected $sessionAuthIsEmpty;
    
    protected $handleKey;
    protected $passwordKey;
    protected $handle;
    protected $password;
    
    abstract protected function renderLogin();
    
    protected function gate()
    {
        if (!$this->checkGateApplies()) {
            return;
        }
        
        $this->sessionStart();
        
        if (!$this->sessionAuthIsEmpty) {
            $this->checkAuth();
        }
        
        if ($this->sessionAuthIsEmpty) {
            $this->checkLogin();
        } 
        
        $this->renderLogin();
    }
    
    protected function checkGateApplies()
    {
        return isset($this->routeData->isGated)
            ? $this->routeData->isGated : false;
    }
    
    protected function checkAuth()
    {
        var_export($this->getSession());
        exit();
    }
    
    protected function checkLogin()
    {
        if (
            $this->bc->isEmptyQueryParam('handle_key') ||
            $this->bc->isEmptyQueryParam('password_key')
        ) {
            $this->renderLogin();
        }
        
        $handleKey   = $this->bc->getQueryParam('handle_key');
        $passwordKey = $this->bc->getQueryParam('password_key');
        $handle      = $this->bc->getQueryParam($handleKey);
        $password    = password_hash(
                        $this->bc->getQueryParam($passwordKey),
                        PASSWORD_DEFAULT);
        
        Util::var_print($password);
        exit();
    }
    
    protected function sessionStart()
    {
        session_start();
        $this->sessionAuthIsEmpty = !isset($_SESSION['auth']);
    }
    
    protected function sessionDestroy()
    {
        if ($this->sessionAuthIsEmpty) {
            return;
        }
        
        session_destroy();
    }
    
    protected function sessionRemoveKey($key)
    {
        unset($_SESSION[$key]);
    }
    
    protected function sessionAddKeyValue($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    
    protected function getSession()
    {
        return $_SESSION;
    }
}