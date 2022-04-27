<?php

namespace Tatter\Menus\Styles;

use Spatie\Menu\Link;
use Tatter\Menus\Menu;

/**
 * AdminLTE Styler Trait
 *
 * Applies CSS classes & styles
 * to start a Menu for AdminLTE.
 *
 * @mixin Menu
 */
trait AdminLTEStyle
{
    protected function applyAdminLTEStyle(): void
    {
        $this->builder
            ->addClass('nav nav-pills nav-sidebar flex-column')
            ->setActiveClass('active menu-open')
            ->setAttribute('data-widget', 'treeview')
            ->setAttribute('role', 'menu')
            ->setAttribute('data-accordion', 'false')
            ->registerFilter(static function (Link $link) {
                $link->addParentClass('nav-item');
                $link->addClass('nav-link');
            });
    }
}
