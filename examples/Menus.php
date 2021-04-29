<?php namespace Config;

use Tatter\Menus\Config\Menus as BaseMenus;
use Tatter\Menus\Menus\BreadcrumbsMenu;

/***
*
* This file contains example values to alter default library behavior.
* Recommended usage:
*	1. Copy the file to app/Config/Menus.php
*	2. Change any values
*	3. Remove any lines to fallback to defaults
*
***/

class Menus extends BaseMenus
{
	/**
	 * Menu class aliases.
	 *
	 * @var array<string, string>
	 */
	public $aliases = [
		'breadcrumbs' => BreadcrumbsMenu::class,
	];
}
