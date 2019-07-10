<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\RequestStack;


class MenuBuilder
{
    private $factory;

    /**
     * @param FactoryInterface $factory
     *
     * Add any other dependency you need
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * The Menu will be created here.
     *
     * @param RequestStack $requestStack
     *
     * @return ItemInterface
     */
    public function createMainMenu(RequestStack $requestStack): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Home', ['route' => 'home'])->setAttribute('class', '')->setLinkAttribute('class', '')->setLabel('Home');
        $menu->addChild('Configuration', ['route' => 'configuration'])->setAttribute('class', '')->setLinkAttribute('class', '')->setLabel('Configuration');
        $menu->addChild('Forecast', ['route' => 'weather_forecast'])->setAttribute('class', '')->setLinkAttribute('class', '')->setLabel('Forecast');
        $menu->addChild('History', ['route' => 'weather_history'])->setAttribute('class', '')->setLinkAttribute('class', '')->setLabel('History');

        return $menu;
    }

}
