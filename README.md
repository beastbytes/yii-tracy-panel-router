The Yii Tracy Panel Router package is a panel for [Yii Tracy](https://github.com/beastbytes/yii-tracy)
(integration of the [Tracy debugging tool](https://tracy.nette.org/)into [Yii3](https://www.yiiframework.com/))
that provides information from [Yii Router](https://github.com/yiisoft/router) about the current route.

## Requirements
- PHP 8.1 or higher

## Installation
Install the package using [Composer](https://getcomposer.org):

Either:
```shell
composer require beastbytes/yii-tracy-panel-router
```
or add the following to the `require` section of your `composer.json`
```json
"beastbytes/yii-tracy-panel-router": "<version_constraint>"
```

## Information Displayed
#### Tab
Shows the name of the route and the match time.

#### Panel
Shows the following information about the route:
* matchTime
* name
* pattern
* arguments
* host
* uri
* action
* middlewares

## License
The BeastBytes Yii Tracy package is free software. It is released under the terms of the BSD License.
Please see [`LICENSE`](./LICENSE.md) for more information.