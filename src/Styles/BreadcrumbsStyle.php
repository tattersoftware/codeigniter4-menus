<?php namespace Tatter\Menus\Styles;

use Tatter\Menus\Menu;
use Spatie\Menu\Link;

/**
 * Breadcrumbs Style Trait
 *
 * Applies CSS classes & styles
 * to make a Bootstrap-style
 * breadcrumbs nav Menu.
 *
 * @mixin Menu
 */
trait BreadcrumbsStyle
{
	protected function applyBreadcrumbsStyle(): void
    {
    	$this->builder
	    	->addClass('breadcrumb')
	    	->setWrapperTag('ol')
    		->wrap('nav', ['aria-label' => 'breadcrumb'])
			->registerFilter(function (Link $link) {
				$link->addParentClass('breadcrumb-item');
			});
	}
}
