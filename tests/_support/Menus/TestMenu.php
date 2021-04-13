<?php namespace Tests\Support\Menus;

use Tatter\Menus\Menu;

class TestMenu extends Menu
{
	/**
	 * Builds the Menu and returns the
	 * rendered HTML string.
	 *
	 * @return string
	 */
	public function get(): string
	{
		return 'bananas';
	}
}
