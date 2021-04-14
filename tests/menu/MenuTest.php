<?php namespace Tests\Support;

use CodeIgniter\HTTP\URI;
use Config\Services;
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

		$request      = Services::request();
		$request->uri = new URI('http://example.com/bulgar');
		Services::injectMock('request', $request);

		$this->menu = new class extends Menu {

			public function get(): string
			{
				return $this->builder
					->link('/', 'Home')
					->link('/bulgar', 'Grain')
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
		$menu = new class(BaseMenu::new()->link('/home', 'asparagus')) extends Menu {

			public function get(): string
			{
				return $this->builder->render();
			}
		};

		$result = $menu->get();

		$this->assertSame('<ul><li><a href="/home">asparagus</a></li></ul>', $result);
	}

	public function testGetUsesCurrentUrl()
	{
		$expected = '<ul><li><a href="/">Home</a></li><li class="active exact-active"><a href="/bulgar">Grain</a></li></ul>';
		$result   = $this->menu->get();

		$this->assertSame($expected, $result);
	}
}
