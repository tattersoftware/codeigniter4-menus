<?php namespace Tests\Support\Menus;

use Tatter\Menus\Menu;

class TestMenu extends Menu
{
	/**
	 * Builds the Menu and returns the
	 * rendered HTML string.
	 */
	public function __toString(): string
	{
		return 'bananas';
	}
}
