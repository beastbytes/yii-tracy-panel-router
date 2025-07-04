<?php

declare(strict_types=1);

namespace BeastBytes\Yii\Tracy\Panel\Router;

use BeastBytes\Yii\Tracy\Panel\ProxyCollectorPanel;
use BeastBytes\Yii\Tracy\ViewTrait;

class Panel extends ProxyCollectorPanel
{
    use ViewTrait;

    public const MESSAGE_CATEGORY = 'tracy-router';

    private const ICON = <<<ICON
<svg
    xmlns = "http://www.w3.org/2000/svg"
    height = "24px"
    viewBox = "0 -960 960 960"
    width = "24px"
    fill = "#2ea4cc"
>
    <path
        d = "M360-120q-66 0-113-47t-47-113v-327q-35-13-57.5-43.5T120-720q0-50 35-85t85-35q50 0 85 35t35 85q0 
        39-22.5 69.5T280-607v327q0 33 23.5 56.5T360-200q33 0 56.5-23.5T440-280v-400q0-66 47-113t113-47q66 0 113 
        47t47 113v327q35 13 57.5 43.5T840-240q0 50-35 85t-85 35q-50 0-85-35t-35-85q0-39 
        22.5-70t57.5-43v-327q0-33-23.5-56.5T600-760q-33 0-56.5 23.5T520-680v400q0 66-47 113t-113 47ZM240-680q17 0 
        28.5-11.5T280-720q0-17-11.5-28.5T240-760q-17 0-28.5 11.5T200-720q0 17 11.5 28.5T240-680Zm480 480q17 0 
        28.5-11.5T760-240q0-17-11.5-28.5T720-280q-17 0-28.5 11.5T680-240q0 17 11.5 28.5T720-200ZM240-720Zm480 480Z"
    />
</svg>
ICON;

    protected function panelParameters(): array
    {
        return $this->getCollected();
    }
    
    protected function panelTitle(): array
    {
        return [
            'id' => 'router.tab.title',
            'category' => 'tracy-router',
        ];
    }

    protected function tabIcon(array $parameters): string
    {
        return self::ICON;
    }

    protected function tabParameters(): array
    {
        return $this->getSummary();
    }

    protected function tabTitle(): array
    {
        return [
            'id' => 'router.panel.title',
            'category' => 'tracy-router',
        ];
    }
}