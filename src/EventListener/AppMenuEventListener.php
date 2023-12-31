<?php

namespace App\EventListener;

use Survos\BootstrapBundle\Event\KnpMenuEvent;
use Survos\BootstrapBundle\Traits\KnpMenuHelperInterface;
use Survos\BootstrapBundle\Traits\KnpMenuHelperTrait;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[AsEventListener(event: KnpMenuEvent::NAVBAR_MENU, method: 'navbarMenu')]
#[AsEventListener(event: KnpMenuEvent::PAGE_MENU, method: 'pageMenu')]
#[AsEventListener(event: KnpMenuEvent::FOOTER_MENU, method: 'footerMenu')]
final class AppMenuEventListener implements KnpMenuHelperInterface
{
    use KnpMenuHelperTrait;

    // this should be optional, not sure we really need it here.
    public function __construct(private ?AuthorizationCheckerInterface $security = null)
    {
    }

    public function navbarMenu(KnpMenuEvent $event): void
    {
        $menu = $event->getMenu();
        $options = $event->getOptions();

        foreach (['ui-alerts','cards-basic','ui-badges','ui-accordion'] as $pageCode) {
            $this->add($menu, 'app_page', ['code' => $pageCode], label: $pageCode);
        }

//        $this->add($menu, 'app_homepage');
        // for nested menus, don't add a route, just a label, then use it for the argument to addMenuItem

        $nestedMenu = $this->addSubmenu($menu, 'Credits');

        foreach (['bundles', 'javascript'] as $type) {
            // $this->addMenuItem($nestedMenu, ['route' => 'survos_base_credits', 'rp' => ['type' => $type], 'label' => ucfirst($type)]);
            $this->addMenuItem($nestedMenu, ['uri' => "#$type", 'label' => ucfirst($type)]);
        }
    }

    public function footerMenu(KnpMenuEvent $event): void
    {
        $menu = $event->getMenu();
        $options = $event->getOptions();
        $subMenu = $this->addSubmenu($menu, 'github');
        $this->add($subMenu, uri: 'https://github.com');
    }

    public function pageMenu(KnpMenuEvent $event): void
    {
    }

}

