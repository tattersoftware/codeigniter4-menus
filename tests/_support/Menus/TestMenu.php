<?php namespace Tests\Support\Menus;

use Tatter\Menus\Interfaces\MenuInterface;

class TestMenu implements MenuInterface
{
	/**
	 * Renders the menu as an HTML string.
	 *
	 * @return string
	 */
	public function render(): string
	{
		return 'bananas';
	}
}
