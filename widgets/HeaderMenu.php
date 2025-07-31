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
        /*
        <nav class="header__menu">
            <ul>
                <li class="active"><a href="./index.html">Home</a></li>
                <li><a href="#">Women’s</a></li>
                <li><a href="#">Men’s</a></li>
                <li><a href="./shop.html">Shop</a></li>
                <li><a href="#">Pages</a>
                    <ul class="dropdown">
                        <li><a href="./product-details.html">Product Details</a></li>
                        <li><a href="./shop-cart.html">Shop Cart</a></li>
                        <li><a href="./checkout.html">Checkout</a></li>
                        <li><a href="./blog-details.html">Blog Details</a></li>
                    </ul>
                </li>
                <li><a href="./blog.html">Blog</a></li>
                <li><a href="./contact.html">Contact</a></li>
            </ul>
        </nav>
    */
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
            ->render();
    }
}