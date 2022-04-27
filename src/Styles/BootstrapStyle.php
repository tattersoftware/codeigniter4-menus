<?php

namespace Tatter\Menus\Styles;

use Spatie\Menu\Link;
use Tatter\Menus\Menu;

/**
 * Bootstrap Styler Trait
 *
 * Applies CSS classes & styles
 * to make a Bootstrap-style Menu.
 *
 * @mixin Menu
 */
trait BootstrapStyle
{
    protected function applyBootstrapStyle(): void
    {
        $this->builder
            ->addClass('navbar-nav mr-auto')
            ->registerFilter(static function (Link $link) {
                $link->addParentClass('nav-item');
                $link->addClass('nav-link');
            });
    }
}
