<?php

namespace Bc\App\Core\Apps\Cms\Portal;

use Bc\App\Core\Apps\Cms\Portal\CmsPortal;

class CmsPortalError extends CmsPortal {
    
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

