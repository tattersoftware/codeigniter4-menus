<?php namespace Config;

/***
*
* This file contains example values to alter default library behavior.
* Recommended usage:
*	1. Copy the file to app/Config/Menus.php
*	2. Change any values
*	3. Remove any lines to fallback to defaults
*
***/

class Menus extends \Tatter\Menus\Config\Menus
{
	/**
	 * Menu class aliases.
	 *
	 * @var array<string, string>
	 */
	public $aliases = [];
}
