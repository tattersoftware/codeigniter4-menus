<?php namespace Tatter\Menus\Config;

use CodeIgniter\Config\BaseConfig;
use Tatter\Menus\Menus\BreadcrumbsMenu;

class Menus extends BaseConfig
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
