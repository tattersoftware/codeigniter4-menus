<?php namespace Tatter\Menus\Menus;

use Config\Services;
use Tatter\Menus\Menu;

/**
 * Controllers Menu
 *
 * Generates a Menu using discovered
 * Controllers.
 *
 * This class assumes some very basic
 * yet specific application structure
 * and should often be used only as
 * a guide for implementing your own.
 */
class ControllersMenu extends Menu
{
	/**
	 * Array of the Controllers to use.
	 *
	 * @var array<string>
	 */
	protected $controllers;

	/**
	 * Initializes the crumbs off the
	 * current route segments.
	 *
	 * @param BaseMenu|null $builder
	 * @param array|null $controllers
	 */
    public function __construct(BaseMenu $builder = null, array $controllers = null)
	{
		parent::__construct($builder);

		$this->controllers = $controllers;

		if (isset($this->controllers))
		{
			return;
		}

		// Locate all the valid controllers
	}

	/**
	 * Builds the Menu and returns the
	 * rendered HTML string.
	 *
	 * @return string
	 */
	public function __toString(): string
	{
		foreach ($this->crumbs as $controller)
		{
			$this->builder->link($url, $display);
		}

		return $this->builder->render();
	}
}
