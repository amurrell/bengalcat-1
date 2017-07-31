<?php

namespace Bc\App\Core\Apps\Admin\Portal;

use Bc\App\Core\Apps\Admin\Portal\AdminPortal;

class AdminPortalApps extends AdminPortal {
    
    protected function init()
    {
        parent::init();
        
        $this->render(
            $this->getThemePart('/src/main/apps.php'),
            $this->routeData, 
            [
                '[:nav]' => '',
                '[:body]' => $this->body,
            ]
        );
    }
}

