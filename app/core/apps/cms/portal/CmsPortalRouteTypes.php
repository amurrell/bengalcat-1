<?php

namespace Bc\App\Core\Apps\Cms\Portal;

use Bc\App\Core\Apps\Cms\Portal\CmsPortal;

class CmsPortalRouteTypes extends CmsPortal {
    
    protected function init()
    {
        echo "Route Types (CMS)!";
            
//        $this->render(
//            $this->getThemePart('/src/main/error.php'),
//            $this->routeData, 
//            [
//                '[:nav]' => '',
//                '[:body]' => $this->body,
//            ]
//        );
    }
}

