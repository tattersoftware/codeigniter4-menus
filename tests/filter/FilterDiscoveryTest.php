<?php

namespace Tests\Support;

use Config\Services;
use Tatter\Menus\Filters\MenusFilter;

/**
 * Verifies that the MenusFilter and
 * its alias are discovered automatically
 * by the Filters library. This test
 * should not be necessary but is provided
 * as a precaution.
 *
 * @internal
 */
final class FilterDiscoveryTest extends MenusTestCase
{
    public function testDiscovers()
    {
        $filters = Services::filters();
        $filters->initialize();

        $result = $this->getPrivateProperty($filters, 'config');

        $this->assertArrayHasKey('menus', $result->aliases);
        $this->assertSame(MenusFilter::class, $result->aliases['menus']);
    }
}
