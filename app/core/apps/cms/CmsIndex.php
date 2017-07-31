<?php

namespace Bc\App\Core\Apps\Cms;

use Bc\App\Core\RouteExtenders\AppIndexRouteExtender;
use Bc\App\Core\Util;
use Exception;

class CmsIndex extends AppIndexRouteExtender {
    
    protected $portalRoute;
    
    protected function init()
    {
        $this->settings = $this->bc->getSetting('cms');
        $this->app      = $this->settings->appName;
        $this->uri      = $_SERVER['REQUEST_URI'];

        $this->addMatchVar('cms');
        $this->addMatchVar('route');
        
        $this->isApiCall();  
        $this->isPortalRoute();
        $this->isCmsRoute();
        $this->routeError();
    }

    protected function isCmsRoute()
    {
        $apiRequestUri = Util::getBasePath() . "/api/cms/get/route" . $this->uri;
        
        $response = Util::makeCurlCall($apiRequestUri, [], true, true);
        $data = json_decode($response);
        
        if (!$data->success) {
            return;
        }
        
        $displayType = $data->data->displayType;
        $controller = $this->getDisplayController($displayType);
        
        if (empty($controller) || !class_exists($controller)) {
            return;
        }
        
        $this->bc->setRouteExtender($controller, true);
        
        try {
            new $controller(
                $this->bc, 
                $data
            );
        } catch (Exception $ex) {
            $this->routeError();
        }
        
        exit();
    }
}

