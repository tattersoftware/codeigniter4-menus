<?php namespace Tatter\Menus\Traits;

use Spatie\Menu\Link;

/**
 * Bootstrap Styler Trait
 *
 * Applies CSS classes & styles
 * to make a Bootstrap-style Menu.
 *
 * @mixin \Tatter\Menus\Menu
 */
trait BootstrapStyle
{
	protected function applyBootstrapStyle(): void
    {
		$this->builder->registerFilter(function (Link $link) {
			$link->addParentClass('nav-item');
			$link->addClass('nav-link');
		});
	}
}
