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
        <th><?= $translator->translate('router.heading.name') ?></th>
        <td><?= $currentRoute['name'] ?></td>
    </tr>
    <tr>
        <th><?= $translator->translate('router.heading.pattern') ?></th>
        <td><?= $currentRoute['pattern'] ?></td>
    </tr>
    <tr>
        <th><?= $translator->translate('router.heading.host') ?></th>
        <td><?= $currentRoute['host'] ?></td>
    </tr>
    <tr>
        <th>URI</th>
        <td><?= $currentRoute['uri'] ?></td>
    </tr>
    <tr>
        <th><?= $translator->translate('router.heading.action') ?></th>
        <td><?= $currentRoute['action'][0] ?>::<?= $currentRoute['action'][1] ?></td>
    </tr>
    <tr>
        <th><?= $translator->translate('router.heading.arguments') ?></th>
        <td><ul>
                <?php foreach ($currentRoute['arguments'] as $argument): ?>
                    <li><?= $argument ?></li>
                <?php endforeach; ?>
            </ul></td>
    </tr>
    <tr>
        <th><?= $translator->translate('router.heading.middlewares') ?></th>
        <td><ul>
                <?php foreach ($currentRoute['middlewares'] as $middleware): ?>
                    <li><?= $middleware ?></li>
                <?php endforeach; ?>
            </ul></td>
    </tr>
    <tr>
        <th><?= $translator->translate('router.heading.match-time') ?></th>
        <td><?= number_format($currentRoute['matchTime'] * 1000, 3) ?>&nbsp;ms</td>
    </tr>
    </tbody>
</table>