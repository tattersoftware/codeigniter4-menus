<?php namespace Tests\Support;

use CodeIgniter\Config\Factories;
use CodeIgniter\Test\CIUnitTestCase;
use Tatter\Menus\Config\Menus as MenusConfig;
use Tests\Support\Menus\NotMenu;
use Tests\Support\Menus\TestMenu;

class MenusTestCase extends CIUnitTestCase
{
	/**
	 * The configuration.
	 *
	 * @var MenusConfig
	 */
	protected $config;

	protected function setUp(): void
	{
		parent::setUp();

		$config          = new MenusConfig();
		$config->aliases = [
			'test' => TestMenu::class,
			'fake' => NotMenu::class,
			'fail' => 'MissingMenu',
		];

		$this->config = $config;
		Factories::injectMock('config', 'Menus', $this->config);
	}
}
