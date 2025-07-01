<?php

declare(strict_types=1);

use BeastBytes\Yii\Tracy\Panel\Router\Panel as RouterPanel;
use Yiisoft\Router\UrlMatcherInterface;
use Yiisoft\Router\Debug\UrlMatcherInterfaceProxy;
use Yiisoft\Router\Debug\RouterCollector;
use Yiisoft\Definitions\Reference;

return [
    'beastbytes/yii-tracy' => [
        'panels' => [
            'router' => [
                'class' => RouterPanel::class,
                '__construct()' => [
                    Reference::to(RouterCollector::class),
                    [
                        UrlMatcherInterface::class => Reference::to(UrlMatcherInterfaceProxy::class),
                    ]                   
                ],
            ],
        ],
    ],
];