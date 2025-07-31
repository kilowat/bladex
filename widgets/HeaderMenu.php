<?php

namespace Widgets;
use Bladex\View;
use Bladex\Widget;
use Spatie\Menu\Menu;
use Spatie\Menu\Link;

/**
 * Пример вызова
 * @widget('LastNews')
 * {!! Widgets\LastNews::make()->render() !!}
 */

class HeaderMenu extends Widget
{
    protected array $config = [

    ];

    public function render(): View|string
    {
        return Menu::new()
            ->wrap('nav', ['class' => 'header__menu'])
            ->add(Link::to(useRoute('home'), 'Home'))
            ->add(Link::to('#', 'Women’s'))
            ->add(Link::to('#', 'Men’s'))
            ->add(Link::to(useRoute('shop.index'), 'Shop'))
            ->submenu(
                '<a href="#">Pages</a>',
                Menu::new()
                    ->addClass('dropdown')
                    ->add(Link::to('#', 'Product Details'))
                    ->add(Link::to('#', 'Shop Cart'))
                    ->add(Link::to('#', 'Checkout'))
                    ->add(Link::to('#', 'Blog Details'))
            )
            ->add(Link::to('#', 'Blog'))
            ->add(Link::to('#', 'Contact'))
            ->setActive(function (Link $link) {
                if (str_contains($link->url(), '#'))
                    return false;
                return $link->url() == useCurrentRoute()->getUri();
            })
            ->render();
    }
}