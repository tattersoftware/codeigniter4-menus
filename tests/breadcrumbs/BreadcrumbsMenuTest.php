<?php

namespace Tests\Support;

use Config\Services;
use Tatter\Menus\Breadcrumb;
use Tatter\Menus\Menus\BreadcrumbsMenu;

/**
 * @internal
 */
final class BreadcrumbsMenuTest extends MenusTestCase
{
    private BreadcrumbsMenu $menu;

    /**
     * Initializes the Breadcrumbs menu.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->menu = new BreadcrumbsMenu();
    }

    /**
     * Removes any Breadcrumbs.
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        BreadcrumbsMenu::set(null);
    }

    public function testDiscovery()
    {
        $_SERVER['REQUEST_URI'] = '/chicken/toast';
        Services::resetSingle('request');

        $breadcrumbs = BreadcrumbsMenu::discover();

        $expected = [
            'http://example.com',
            'http://example.com/chicken',
            'http://example.com/chicken/toast',
        ];
        $this->assertSame($expected, array_column($breadcrumbs, 'url'));

        $expected = ['Home', 'Chicken', 'Toast'];
        $this->assertSame($expected, array_column($breadcrumbs, 'display'));
    }

    public function testDefaultUsesDiscovery()
    {
        $expected = '<nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="http://example.com">Home</a></li><li class="breadcrumb-item active">Current</li></ol></nav>';
        $result   = (string) $this->menu;

        $this->assertSame($expected, $result);
    }

    public function testGet()
    {
        $breadcrumbs = BreadcrumbsMenu::discover();

        $result = BreadcrumbsMenu::get();

        $this->assertSame($breadcrumbs, $result);
    }

    public function testSetNull()
    {
        BreadcrumbsMenu::discover();
        BreadcrumbsMenu::set(null);

        $result = BreadcrumbsMenu::get();

        $this->assertNull($result);
    }

    public function testPopNull()
    {
        $result = BreadcrumbsMenu::pop();

        $this->assertNull($result);
    }

    public function testPop()
    {
        $breadcrumbs = BreadcrumbsMenu::discover();

        $result = BreadcrumbsMenu::pop();

        $this->assertSame($breadcrumbs[1], $result);
    }

    public function testPushNull()
    {
        $breadcrumb = new Breadcrumb('food');

        $result = BreadcrumbsMenu::push($breadcrumb);
        $this->assertSame(1, $result);

        $this->assertSame($breadcrumb, BreadcrumbsMenu::pop());
    }

    public function testPush()
    {
        BreadcrumbsMenu::discover();

        $breadcrumb = new Breadcrumb('food');

        $result = BreadcrumbsMenu::push($breadcrumb);
        $this->assertSame(3, $result);

        $this->assertSame($breadcrumb, BreadcrumbsMenu::pop());
    }
}
