<?php namespace Tatter\Menus\Interfaces;

/**
 * Menu Interface
 *
 * Defines the methods required for
 * a class to serve as a menu.
 */
interface MenuInterface
{
	/**
	 * Renders the menu as an HTML string.
	 *
	 * @return string
	 */
	public function render(): string;
}
