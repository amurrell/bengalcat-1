<?php

namespace Bc\App\Core\Apps\Admin\Portal;

use Bc\App\Core\Apps\Admin\Portal\AdminPortal;

class AdminPortalError extends AdminPortal {
    
    protected function init()
    {
        $this->render(
            $this->getThemePart('/src/main/error.php'),
            $this->routeData, 
            [
                '[:nav]' => '',
                '[:body]' => $this->body,
            ]
        );
    }
}

