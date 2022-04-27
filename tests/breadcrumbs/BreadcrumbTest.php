<?php

namespace Tests\Support;

use Tatter\Menus\Breadcrumb;

/**
 * @internal
 */
final class BreadcrumbTest extends MenusTestCase
{
    public function testUsesProvidedDisplay()
    {
        $breadcrumb = new Breadcrumb(site_url('fruit'), 'banana');

        $this->assertSame('banana', $breadcrumb->display);
    }

    public function testGuessesDisplay()
    {
        $breadcrumb = new Breadcrumb(site_url('fruit'));

        $this->assertSame('Fruit', $breadcrumb->display);
    }
}
