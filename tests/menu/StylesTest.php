<?php namespace Tests\Support;

use Tatter\Menus\Menu;
use Tatter\Menus\Styles\BootstrapStyle;
use Tatter\Menus\Styles\AdminLTEStyle;
use Tests\Support\MenusTestCase;

class StylesTest extends MenusTestCase
{
	public function testBootstrapStyleAppliesClasses()
	{
		$menu = new class extends Menu {

			use BootstrapStyle;

			public function __toString(): string
			{
				return $this->builder
					->link(site_url('/'), 'Home')
					->link(site_url('/current'), 'Grain')
					->render();
			}
		};

		$result = $menu->__toString();

		$this->assertStringContainsString('li class="nav-item"', $result);
		$this->assertStringContainsString('class="nav-link"', $result);
	}

	public function testAdminLTEStyleAppliesClasses()
	{
		$menu = new class extends Menu {

			use AdminLTEStyle;

			public function __toString(): string
			{
				return $this->builder
					->link(site_url('/'), 'Home')
					->link(site_url('/current'), 'Grain')
					->render();
			}
		};

		$result = $menu->__toString();

		$this->assertStringContainsString('data-widget="treeview"', $result);
		$this->assertStringContainsString('class="nav nav-pills', $result);
		$this->assertStringContainsString('class="nav-link"', $result);
	}
}
