<?php

namespace Bc\App\Core\Apps\Admin;

use Bc\App\Core\RouteExtenders\AppIndexRouteExtender;
use Bc\App\Core\Util;
use Exception;

class AdminIndex extends AppIndexRouteExtender {

    protected function init()
    {
        $this->settings = $this->bc->getSetting('admin');
        $this->app      = $this->settings->appName;
        $this->uri      = $_SERVER['REQUEST_URI'];

        $this->addMatchVar('admin');
        $this->addMatchVar('route');
        
        $this->isApiCall();
        $this->isPortalRoute();
        $this->routeError($this->settings->errorPortalRoute);
    }
}

