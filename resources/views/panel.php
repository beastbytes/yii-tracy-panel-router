<?php
/**
 * @var array $currentRoute
 */
?>
<table>
    <tbody>
    <tr>
        <th>Name</th>
        <td><?= $currentRoute['name'] ?></td>
    </tr>
    <tr>
        <th>Pattern</th>
        <td><?= $currentRoute['pattern'] ?></td>
    </tr>
    <tr>
        <th>Host</th>
        <td><?= $currentRoute['host'] ?></td>
    </tr>
    <tr>
        <th>URI</th>
        <td><?= $currentRoute['uri'] ?></td>
    </tr>
    <tr>
        <th>Action</th>
        <td><?= $currentRoute['action'][0] ?>::<?= $currentRoute['action'][1] ?></td>
    </tr>
    <tr>
        <th>Arguments</th>
        <td><ul>
                <?php foreach ($currentRoute['arguments'] as $argument): ?>
                    <li><?= $argument ?></li>
                <?php endforeach; ?>
            </ul></td>
    </tr>
    <tr>
        <th>Middlewares</th>
        <td><ul>
                <?php foreach ($currentRoute['middlewares'] as $middleware): ?>
                    <li><?= $middleware ?></li>
                <?php endforeach; ?>
            </ul></td>
    </tr>
    <tr>
        <th>Match Time</th>
        <td><?= (string)($currentRoute['matchTime'] * 1000) ?>&nbsp;ms</td>
    </tr>
    </tbody>
</table>