<?php

namespace Tests\Support;

use Config\Services;
use Spatie\Menu\Menu as BaseMenu;
use Tatter\Menus\Menu;

/**
 * @internal
 */
final class MenuTest extends MenusTestCase
{
    /**
     * Generates a test Menu on-the-fly
     */
    private function menu(): Menu
    {
        return new class () extends Menu {
            public function __toString(): string
            {
                return $this->builder
                    ->link(site_url(''), 'Home')
                    ->link(site_url('/current'), 'Grain')
                    ->render();
            }
        };
    }

    public function testGetBuilder()
    {
        $result = $this->menu()->builder();

        $this->assertInstanceOf(BaseMenu::class, $result);
    }

    public function testUsesBuilder()
    {
        $menu = new class (BaseMenu::new()->link(site_url('/home'), 'asparagus')) extends Menu {
            public function __toString(): string
            {
                return $this->builder->render();
            }
        };

        $result = (string) $menu;

        $this->assertSame('<ul><li><a href="http://example.com/home">asparagus</a></li></ul>', $result);
    }

    public function testGetUsesCurrentUrl()
    {
        $expected = '<ul><li><a href="http://example.com/">Home</a></li><li class="active exact-active"><a href="http://example.com/current">Grain</a></li></ul>';
        $result   = $this->menu()->__toString();

        $this->assertSame($expected, $result);
    }

    public function testGetUsesIndexPage()
    {
        $config            = config('App');
        $config->indexPage = 'index.php';
        Services::injectMock('request', null);

        $expected = '<ul><li><a href="http://example.com/index.php">Home</a></li><li class="active exact-active"><a href="http://example.com/index.php/current">Grain</a></li></ul>';
        $result   = $this->menu()->__toString();

        $this->assertSame($expected, $result);
    }
}
