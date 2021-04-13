<?php namespace Tests\Support;

use Spatie\Menu\Menu as BaseMenu;
use Tatter\Menus\Menu;
use Tests\Support\MenusTestCase;

class MenuTest extends MenusTestCase
{
	/**
	 * @var Menu
	 */
	private $menu;

	/**
	 * Creates a basic Menu to use
	 * for testing.
	 */
	protected function setUp(): void
	{
		parent::setUp();

		$this->menu = new class extends Menu {

			public function get(): string
			{
				return 'bulgar';
			}
		};
	}

	public function testBuilder()
	{
		$result = $this->menu->builder();

		$this->assertInstanceOf(BaseMenu::class, $result);
	}
}
