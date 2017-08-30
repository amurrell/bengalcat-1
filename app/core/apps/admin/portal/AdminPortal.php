<?php

namespace Bc\App\Core\Apps\Admin\Portal;

use Bc\App\Core\RouteExtenders\PortalRouteExtender;
use Bc\App\Core\Util;

abstract class AdminPortal extends PortalRouteExtender {
    
    protected $body;
    protected $bodyWithSideBar;
    
    protected function setTemplatePaths() {
        
        if (!defined('CSS_DIR')) {
            define('CSS_DIR', $this->getThemePart('assets/build/css/'));
            define('JS_DIR', $this->getThemePart('assets/build/js/'));
            define('IMAGE_DIR', $this->getThemePart('assets/build/img/'));
        }
        
        parent::setTemplatePaths();
    }
    
    protected function init()
    {
        $this->gate();
    }
    
    protected function doCustomInit() {
        parent::doCustomInit();
        
        $this->settings = $this->bc->getSetting('admin');
        $this->bc->changeSetting(
            'errorRoute',
            $this->settings->errorPortalRoute
        );
        
        $this->setTheme($this->settings->theme);
        
        $this->body = Util::getTemplateContents(
            $this->bc,
            $this->getThemePart('/src/tokenHTML/body.php')
        );
        $this->bodyWithSideBar = Util::getTemplateContents(
            $this->bc,
            $this->getThemePart('/src/tokenHTML/body-sidebar.php')
        );
    }
    
    protected function renderLogin()
    {
        $this->render(
            $this->getThemePart('/src/main/login.php'),
            null, 
            [
                '[:nav]' => '',
                '[:body]' => $this->body,
                
            ]
        );
        exit();
    }
    
}

