<?php

namespace Tatter\Menus\Menus;

use CodeIgniter\HTTP\URI;
use Config\Services;
use Tatter\Menus\Breadcrumb;
use Tatter\Menus\Menu;
use Tatter\Menus\Styles\BreadcrumbsStyle;

/**
 * Breadcrumbs Menu
 *
 * Generates a BootStrap-style series
 * of links for nested content.
 *
 * This class assumes some very basic
 * yet specific application structure
 * and should often be used only as
 * a guide for implementing your own.
 */
class BreadcrumbsMenu extends Menu
{
    use BreadcrumbsStyle;

    /**
     * Array of the Breadcrumbs.
     *
     * @var Breadcrumb[]|null
     */
    protected static $breadcrumbs;

    /**
     * Sets the crumbs based on the
     * current/provided URI segments.
     *
     * @return Breadcrumb[] The discovered crumbs
     */
    public static function discover(?URI $uri = null): array
    {
        $uri ??= Services::request()->uri;

        // Always start with the base URL
        $breadcrumbs = [
            new Breadcrumb(base_url(), lang('Menus.home')),
        ];

        // Add each segment
        $segments = [];

        foreach ($uri->getSegments() as $segment) {
            $segments[]    = $segment;
            $breadcrumbs[] = new Breadcrumb(site_url($segments));
        }
        self::set($breadcrumbs);

        return self::get();
    }

    /**
     * Returns the currently-configured crumbs.
     *
     * @return Breadcrumb[]|null
     */
    public static function get(): ?array
    {
        return self::$breadcrumbs;
    }

    /**
     * Sets the crumbs used to build the Menu.
     *
     * @param Breadcrumb[]|null $breadcrumbs
     */
    public static function set(?array $breadcrumbs)
    {
        self::$breadcrumbs = $breadcrumbs;
    }

    /**
     * Removes and returns the last crumb.
     *
     * @return Breadcrumb
     */
    public static function pop(): ?Breadcrumb
    {
        return null === self::$breadcrumbs ? null : array_pop(self::$breadcrumbs);
    }

    /**
     * Adds a new Breadcrumb to the Menu.
     *
     * @return int New number of items in the Menu
     */
    public static function push(Breadcrumb $breadcrumb): int
    {
        self::$breadcrumbs ??= [];

        return array_push(self::$breadcrumbs, $breadcrumb);
    }

    //--------------------------------------------------------------------
    /**
     * Builds the Menu and returns the
     * rendered HTML string.
     */
    public function __toString(): string
    {
        // If no breadcrumbs are set then initiate discovery
        if (null === self::$breadcrumbs) {
            self::discover();
        }

        // Use the last item without a link
        $last = self::pop();

        foreach (self::$breadcrumbs as $breadcrumb) {
            $this->builder->link($breadcrumb->url, $breadcrumb->display);
        }

        return $this->builder
            ->html($last->display, [
                'class' => 'breadcrumb-item active',
            ])->render();
    }
}
