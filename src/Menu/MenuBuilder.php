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


        //================ BCC payments ==================
       /* $menu->addChild('Bcc Payments')
            ->setAttribute('class', 'nav-item mr-auto');

        $menu['Bcc Payments']->addChild('Upload', ['route' => 'uploadBcc'])->setAttribute('class', 'nav-item mr-auto')->setLinkAttribute('class', 'nav-link');
        $menu['Bcc Payments']->addChild('View', ['route' => 'bcc_payment_index'])->setAttribute('class', 'nav-item mr-auto')->setLinkAttribute('class', 'nav-link');*/

        return $menu;
    }

}
