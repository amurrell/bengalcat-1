<?php

/*
 * Version-Controlled default settings go here.
 * Create settings.php (is in .gitignore) to add to or override default settings.
 */

return [
    'defaultTheme'         => 'bengalcat',
    'timeZone'             => 'America/Los_Angeles',
    'errorRoute'           => '\Bc\App\Controllers\Example\View\Error',
    'errorJsonIdentifiers' => [
        '/api/'
    ],
    'navRenderPath'        => THEMES_DIR . 'bengalcat/src/tokenHTML/nav.php',
    'navActiveClass'       => 'bc-active',
    'navItems'             => [
        'docs'     => [
            'attributes'        => [
                'href' => '/docs/',
            ],
//            'fontawesomeIcon' => 'copy',
            'display'           => 'Docs',
            'matchingRoutePath' => '/docs/'
        ],
        'about'    => [
            'attributes'        => [
                'href' => '/about/',
            ],
//            'fontawesomeIcon'   => 'question',
            'display'           => 'About',
            'matchingRoutePath' => '/about/'
        ],
        'download' => [
            'attributes'      => [
                'href' => 'https://github.com/amurrell/bengalcat/',
            ],
            'fontawesomeIcon' => 'download',
            'display'         => 'Download',
        ],
    ],
    'cms'                  => [
        'appName'          => 'cms',
        'displayName'      => 'CMS',
        'errorPortalRoute' => '\Bc\App\Core\Apps\Cms\Portal\CmsPortalError',
        'apiPath'          => '/api/cms/',
        'apiRouteTemplate' => 'Bc\App\Core\Apps\Cms\Api\%s\CmsApi%s%s',
        'theme'            => 'admin',
        'displays'         => [
            'docs' => ['controller' => '\Bc\App\Controllers\Example\View\Docs'],
            'doc'  => ['controller' => '\Bc\App\Controllers\Example\View\Doc'],
        ],
        'portalRoutes'     => [
            '/portal/cms/' => '\Bc\App\Core\Apps\Cms\Portal\CmsPortalRouteTypes',
        ],
        'gatedRoutes'      => [
            '/portal/cms/',
        ]
    ],
    'admin'                => [
        'appName'          => 'admin',
        'displayName'      => 'Admin',
        'errorPortalRoute' => '\Bc\App\Core\Apps\Admin\Portal\AdminPortalError',
        'apiPath'          => '/api/admin/',
        'apiRouteTemplate' => 'Bc\App\Core\Apps\Admin\Api\%s\AdminApi%s%s',
        'theme'            => 'admin',
        'portalRoutes'     => [
            '/admin/'       => '\Bc\App\Core\Apps\Admin\Portal\AdminPortalApps',
        ],
        'gatedRoutes'      => [
            '/admin/(.*)',
        ]
    ]
];

