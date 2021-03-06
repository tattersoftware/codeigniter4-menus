<?php

namespace Tests\Support;

use CodeIgniter\Config\Factories;
use CodeIgniter\Test\CIUnitTestCase;
use Config\App;
use Config\Services;
use Nexus\PHPUnit\Extension\Expeditable;
use Tatter\Menus\Config\Menus as MenusConfig;
use Tests\Support\Menus\NotMenu;
use Tests\Support\Menus\TestMenu;

/**
 * @internal
 */
abstract class MenusTestCase extends CIUnitTestCase
{
    use Expeditable;

    /**
     * The configuration.
     *
     * @var MenusConfig
     */
    protected $config;

    protected function setUp(): void
    {
        parent::setUp();

        // Use some standard settings that affect URLs
        $config            = new App();
        $config->baseURL   = 'http://example.com';
        $config->indexPage = '';
        Factories::injectMock('config', 'App', $config);

        // Set a current URL for checking "active" links
        $_SERVER['REQUEST_URI'] = '/current';
        Services::resetSingle('request');

        // Create some Menu aliases for testing
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
