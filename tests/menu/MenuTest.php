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
	 * Sets the current URL and creates
	 * a basic Menu to use for testing.
	 */
	protected function setUp(): void
	{
		parent::setUp();

		$this->menu = new class extends Menu {

			public function get(): string
			{
				return $this->builder
					->link(site_url('/'), 'Home')
					->link(site_url('/current'), 'Grain')
					->render();
			}
		};
	}

	public function testGetBuilder()
	{
		$result = $this->menu->builder();

		$this->assertInstanceOf(BaseMenu::class, $result);
	}

	public function testUsesBuilder()
	{
		$menu = new class(BaseMenu::new()->link(site_url('/home'), 'asparagus')) extends Menu {

			public function get(): string
			{
				return $this->builder->render();
			}
		};

		$result = $menu->get();

		$this->assertSame('<ul><li><a href="http://example.com/home">asparagus</a></li></ul>', $result);
	}

	public function testGetUsesCurrentUrl()
	{
		$expected = '<ul><li><a href="http://example.com/">Home</a></li><li class="active exact-active"><a href="http://example.com/current">Grain</a></li></ul>';
		$result   = $this->menu->get();

		$this->assertSame($expected, $result);
	}
}
