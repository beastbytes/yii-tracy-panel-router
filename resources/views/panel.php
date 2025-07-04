<?php
/**
 * @var array $currentRoute
 * @var TranslatorInterface $translator
 */

use BeastBytes\Yii\Tracy\Panel\Router\Panel;
use Yiisoft\Translator\TranslatorInterface;

$translator = $translator->withDefaultCategory(Panel::MESSAGE_CATEGORY);
?>
<table>
    <tbody>
    <tr>
        <th><?= $translator->translate('router.heading.name') ?>Name</th>
        <td><?= $currentRoute['name'] ?></td>
    </tr>
    <tr>
        <th><?= $translator->translate('router.heading.pattern') ?>Pattern</th>
        <td><?= $currentRoute['pattern'] ?></td>
    </tr>
    <tr>
        <th><?= $translator->translate('router.heading.host') ?>Host</th>
        <td><?= $currentRoute['host'] ?></td>
    </tr>
    <tr>
        <th>URI</th>
        <td><?= $currentRoute['uri'] ?></td>
    </tr>
    <tr>
        <th><?= $translator->translate('router.heading.action') ?>Action</th>
        <td><?= $currentRoute['action'][0] ?>::<?= $currentRoute['action'][1] ?></td>
    </tr>
    <tr>
        <th><?= $translator->translate('router.heading.arguments') ?>Arguments</th>
        <td><ul>
                <?php foreach ($currentRoute['arguments'] as $argument): ?>
                    <li><?= $argument ?></li>
                <?php endforeach; ?>
            </ul></td>
    </tr>
    <tr>
        <th><?= $translator->translate('router.heading.middlewares') ?>Middlewares</th>
        <td><ul>
                <?php foreach ($currentRoute['middlewares'] as $middleware): ?>
                    <li><?= $middleware ?></li>
                <?php endforeach; ?>
            </ul></td>
    </tr>
    <tr>
        <th><?= $translator->translate('router.heading.match-time') ?>Match Time</th>
        <td><?= number_format($currentRoute['matchTime'] * 1000, 3) ?>&nbsp;ms</td>
    </tr>
    </tbody>
</table>