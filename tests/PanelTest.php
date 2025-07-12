<?php

namespace BeastBytes\Yii\Tracy\Panel\Router\Tests;

use BeastBytes\Yii\Tracy\Panel\Router\Panel;
use BeastBytes\Yii\Tracy\Panel\Router\Tests\Support\TestController;
use BeastBytes\Yii\Tracy\ProxyContainer;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\BeforeClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Router\Debug\RouterCollector;
use Yiisoft\Router\Debug\UrlMatcherInterfaceProxy;
use Yiisoft\Router\FastRoute\UrlMatcher;
use Yiisoft\Router\MatchingResult;
use Yiisoft\Router\Route;
use Yiisoft\Router\RouteCollection;
use Yiisoft\Router\RouteCollector;
use Yiisoft\Router\UrlMatcherInterface;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Translator\IntlMessageFormatter;
use Yiisoft\Translator\CategorySource;
use Yiisoft\Translator\Message\Php\MessageSource;
use Yiisoft\Translator\Translator;
use Yiisoft\View\View;

class PanelTest extends TestCase
{
    private const PANEL = <<<HTML
<h1>Router</h1>
<div class="tracy-inner"><div class="tracy-inner-container">
<table>
    <tbody>
    <tr>
        <th>Name</th>
        <td>{name}</td>
    </tr>
    <tr>
        <th>Pattern</th>
        <td>{pattern}</td>
    </tr>
    <tr>
        <th>Host</th>
        <td>{host}</td>
    </tr>
    <tr>
        <th>URI</th>
        <td>{uri}</td>
    </tr>
    <tr>
        <th>Action</th>
        <td>{action}</td>
    </tr>
    <tr>
        <th>Arguments</th>
        <td>{arguments}</td>
    </tr>
    <tr>
        <th>Middlewares</th>
        <td>{middlewares}</td>
    </tr>
    <tr>
        <th>Match Time</th>
        <td>%f&nbsp;ms</td>
    </tr>
    </tbody>
</table>
</div></div>
HTML;
    private const TAB = <<<TAB
<span title="Router"><svg
    xmlns="http://www.w3.org/2000/svg"
    height="24px"
    viewBox="0 -960 960 960"
    width="24px"
    fill="#2ea4cc"
>
    <path
        d="M360-120q-66 0-113-47t-47-113v-327q-35-13-57.5-43.5T120-720q0-50 35-85t85-35q50 0 85 35t35 85q0 39-22.5
        69.5T280-607v327q0 33 23.5 56.5T360-200q33 0 56.5-23.5T440-280v-400q0-66 47-113t113-47q66 0 113 47t47 113v327q35
        13 57.5 43.5T840-240q0 50-35 85t-85 35q-50 0-85-35t-35-85q0-39 22.5-70t57.5-43v-327q0-33-23.5-56.5T600-760q-33
        0-56.5 23.5T520-680v400q0 66-47 113t-113 47ZM240-680q17 0 28.5-11.5T280-720q0-17-11.5-28.5T240-760q-17 0-28.5
        11.5T200-720q0 17 11.5 28.5T240-680Zm480 480q17 0 28.5-11.5T760-240q0-17-11.5-28.5T720-280q-17 0-28.5
        11.5T680-240q0 17 11.5 28.5T720-200ZM240-720Zm480 480Z"
    />
</svg><span class="tracy-label">{name}:&nbsp;%f&nbsp;ms</span></span>
TAB;

    private const LOCALE = 'en-GB';

    private static ?ContainerInterface $container = null;
    private static ?ContainerInterface $proxyContainer = null;

    private ?Panel $panel = null;

    #[After]
    public function tearDown(): void
    {
        $this->panel->shutdown();
    }

    #[Before]
    public function setUp(): void
    {
        $routeCollector = new RouteCollector();
        foreach (self::getRoutes() as $route) {
            $routeCollector->addRoute($route);
        }

        self::$container = new SimpleContainer([
            CurrentRoute::class => new CurrentRoute(),
            UrlMatcherInterface::class => new UrlMatcher(new RouteCollection($routeCollector)),
            View::class => (new View())
                ->setParameter(
                    'translator',
                    (new Translator())
                        ->withLocale(self::LOCALE)
                        ->addCategorySources(new CategorySource(
                            Panel::MESSAGE_CATEGORY,
                            new MessageSource(
                                dirname(__DIR__)
                                . DIRECTORY_SEPARATOR . 'resources'
                                . DIRECTORY_SEPARATOR . 'messages',
                            ),
                            new IntlMessageFormatter(),
                        )),
                )
            ,
        ]);

        $collector = new RouterCollector(self::$container);
        $this->panel = (new Panel(
            $collector,
            [
                UrlMatcherInterface::class => new UrlMatcherInterfaceProxy(
                    self::$container->get(UrlMatcherInterface::class),
                    $collector,
                ),
            ],
        ));

        self::$proxyContainer = new ProxyContainer(self::$container);
        $this->panel = $this->panel->withContainer(self::$proxyContainer);
        $this->panel->startup();
    }

    #[Test]
    public function viewPath(): void
    {
        $this->assertSame(
            dirname(__DIR__)
            . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR,
            $this->panel->getViewPath());
    }

    #[DataProvider('routesProvider')]
    #[Test]
    public function routes(string $method, string $uri, array $arguments, string $name): void
    {
        $request = new ServerRequest($method, $uri);
        $result = self::$proxyContainer->get(UrlMatcherInterface::class)->match($request);
        $currentRoute = self::$container->get(CurrentRoute::class);
        $currentRoute->setUri(new Uri($uri));
        $currentRoute->setRouteWithArguments(
            $result->route(),
            $result->arguments()
        );

        $this->assertStringMatchesFormat(
            strtr(self::TAB, ['{name}' => $name]),
            $this->panel->getTab()
        );

        $route = $this->getRoute($name);
        $middlewares = $route->getData('enabledMiddlewares');
        $action = array_pop($middlewares);

        $this->assertStringMatchesFormat(
            $this->stripWhitespace(strtr(
                self::PANEL,
                [
                    '{name}' => $name,
                    '{pattern}' => $route->getData('pattern'),
                    '{host}' => $route->getData('host'),
                    '{uri}' => $uri,
                    '{action}' => implode('::', $action),
                    '{arguments}' => $this->array2String($arguments),
                    '{middlewares}' => $this->array2String($middlewares),
                ]
            )),
            $this->stripWhitespace($this->panel->getPanel())
        );
    }

    public function routesProvider(): array
    {
        return [
            'test.index'  => [
                'method' => 'GET',
                'uri' => '/',
                'arguments' => [],
                'name' => 'test.index',
            ],
            'test.view' => [
                'method' => 'GET',
                'uri' => '/view/1',
                'arguments' => ['id' => '1'],
                'name' => 'test.view',
            ],
            'test.create' => [
                'method' => 'GET',
                'uri' => '/create',
                'arguments' => [],
                'name' => 'test.create',
            ],
            'test.delete' => [
                'method' => 'POST',
                'uri' => '/delete/9',
                'arguments' => ['id' => '9'],
                'name' => 'test.delete',
            ],
        ];
    }

    private function array2String(array $arguments): string
    {
        $result = [];
        foreach ($arguments as $key => $value) {
            $result[] = is_int($key)
                ? $value
                : sprintf('%s&nbsp;=&nbsp;%s', $key, $value)
            ;
        }

        return empty($result)
            ? ''
            : '<ul><li>' . implode('</li><li>', $result) . '</li></ul>'
        ;
    }

    private function getRoute(string $name): Route
    {
        foreach (self::getRoutes() as $route) {
            if ($route->getData('name') === $name) {
                return $route;
            }
        }

        throw new \RuntimeException("Route `$name` not found");
    }

    private static function getRoutes(): array
    {
        return [
            Route::methods(['GET'], '/')
                ->name('test.index')
                ->action([TestController::class, 'index'])
            ,
            Route::methods(['GET', 'POST'], '/create')
                ->name('test.create')
                ->middleware('\Test\Auth\Middleware\IsLoggedIn')
                ->action([TestController::class, 'create'])
            ,
            Route::methods(['POST'], '/delete/{id: [1-9]\d*}')
                ->name('test.delete')
                ->middleware('\Test\Auth\Middleware\IsLoggedIn')
                ->action([TestController::class, 'delete'])
            ,
            Route::methods(['GET', 'POST'], '/update/{id: [1-9]\d*}')
                ->name('test.update')
                ->middleware('\Test\Auth\Middleware\IsLoggedIn')
                ->action([TestController::class, 'update'])
            ,
            Route::methods(['GET'], '/view/{id: [1-9]\d*}')
                ->name('test.view')
                ->action([TestController::class, 'view'])
            ,
        ];
    }

    private function stripWhitespace(string $string): string
    {
        return preg_replace('/>\s+</', '><', $string);
    }
}