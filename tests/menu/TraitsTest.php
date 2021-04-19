<?php namespace Tests\Support;

use Tatter\Menus\Menu;
use Tatter\Menus\Traits\Bootstrap;
use Tests\Support\MenusTestCase;

class TraitsTest extends MenusTestCase
{
	public function testBootstrapAppliesClasses()
	{
		$menu = new class extends Menu {

			use Bootstrap;

			public function get(): string
			{
				return $this->builder
					->link(site_url('/'), 'Home')
					->link(site_url('/current'), 'Grain')
					->render();
			}
		};

		$result = $menu->get();

		$this->assertStringContainsString('li class="nav-item"', $result);
		$this->assertStringContainsString('class="nav-link"', $result);
	}
}
