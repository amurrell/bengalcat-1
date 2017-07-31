<?php

namespace Bc\App\Core\RouteExtenders;

use Bc\App\Core\Util;

abstract class AppIndexRouteExtender extends DataRouteExtender {
    
    protected $app;
    protected $apiPath;
    protected $uri;
    protected $settings;
    protected $apiRoute;
    protected $portalRoute;
    
    protected $matchVars = [
        'create',
        'get',
        'update',
        'delete',
    ];
    
    protected function addMatchVar($var)
    {
        $this->matchVars = array_merge($this->matchVars, [$var]);
    }
    
    protected function isApiCall()
    {
        if (!preg_match("#{$this->settings->apiPath}#", $this->uri)) {
            return;
        }
        
        $this->setupApiCall();
        $this->buildApiRoute();
        
        // Bad because the class for this api route does not exist.
        if (!$this->apiRoute) {
            // No need to change error to a theme because api routes should return json
            $this->triggerError(
                404,
                "There are no matching defined routes in the {$this->settings->displayName} API."
            );
        }
        
        $this->bc->setRouteExtender($this->apiRoute, true);
        
        try {
            new $this->apiRoute(
                $this->bc, 
                (object) [
                    'routeVars' => $this->routeVars,
                    'isGated'   => $this->isGatedRoute()
                ]
            );
        } catch (Exception $ex) {
            $this->routeError();
        }

        exit();
    }

    protected function isPortalRoute()
    {
        // Determine Portal Route stuff
        $routeData = $this->bc->findMatchingRoute(
            $this->settings->portalRoutes,
            $this->bc->getRoute()
        );
        
        if (empty($routeData->routeExtenderPath)) {
            return;
        }
        
        $this->portalRoute = $routeData->routeExtenderPath;
        $this->bc->setRouteExtender($this->portalRoute, true);

        try {
            new $this->portalRoute(
                $this->bc, 
                (object) [
                    'routeVars' => $this->routeVars,
                    'isGated'   => $this->isGatedRoute()
                ]
            );
        } catch (Exception $ex) {
            $this->routeError($this->settings->errorPortalRoute);
        }
        
        exit();
    }
    
    protected function isGatedRoute()
    {
        $gatedRoutes = array_combine(
            $this->settings->gatedRoutes,
            $this->settings->gatedRoutes
        );
        
        $gated = $this->bc->findMatchingRoute(
            $gatedRoutes,
            $this->bc->getRoute()
        );
        
        return (!empty($gated->routeExtenderPath));
    }
    
    protected function getDisplayController($displayType)
    {
        if (empty($this->settings)) {
            return false;
        }
        
        if (empty($this->settings->displays)) {
            return false;
        }
        
        if (empty($this->settings->displays->$displayType)) {
            return false;
        }
        
        if (empty($this->settings->displays->$displayType->controller)) {
            return false;
        }
        
        return $this->settings->displays->$displayType->controller;
    }
    
    protected function setupApiCall()
    {
        $variants = $this->variants;
        $this->variants = $this->routePieces;
        $this->buildRouteVars();
        $this->variants = $variants;
        
        $this->routeVars->route = (!empty($this->routeVars->route)) 
            ? $this->variants[1] : null;
    }
    
    protected function buildApiRoute()
    {
        if (
            empty($this->routeVars->{$this->routeVars->{$this->app}}) || 
            empty($this->routeVars->{$this->app})
        ) {
            $this->apiRoute = false;
            return;
        }
        
        $action = $this->routeVars->{$this->app};
        $object = $this->routeVars->$action;
        
        $route = vsprintf($this->settings->apiRouteTemplate, [
            ucfirst($action),
            ucfirst($action),
            ucfirst($object),
        ]);

        $this->apiRoute = (class_exists($route) ? $route : false);
    }
    
    protected function routeError($errorRoute)
    {
        $errorRoute = !empty($errorRoute) ? $errorRoute : $this->bc->getSetting('errorRoute');
        $this->bc->changeSetting(
            'errorRoute',
            $errorRoute
        );
        
        $this->triggerError(
            404, 
            "There are no defined routes matching this url in the {$this->settings->displayName}."
        );
    }
    
}