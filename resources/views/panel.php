<?php
/**
 * @var string $action
 *  @var string $arguments
 * @var string $host
 * @var float $matchTime
 * @var string $middlewares
 * @var string $name
 * @var string $pattern
 * @var string $uri
 */
?>
<table>
    <tbody>
        <tr>
            <th>Name</th>
            <td><?= $name ?></td>
        </tr>
        <tr>
            <th>Pattern</th>
            <td><?= $pattern ?></td>
        </tr>
        <tr>
            <th>Host</th>
            <td><?= $host ?></td>
        </tr>
        <tr>
            <th>URI</th>
            <td><?= $uri ?></td>
        </tr>
        <tr>
            <th>Action</th>
            <td><?= $action ?></td>
        </tr>
        <tr>
            <th>Arguments</th>
            <td><?= $arguments ?></td>
        </tr>
        <tr>
            <th>Middlewares</th>
            <td><?= $middlewares ?></td>
        </tr>
        <tr>
            <th>Match Time</th>
            <td><?= $matchTime ?></td>
        </tr>
    </tbody>
</table>