<?php

namespace Tatter\Menus;

use CodeIgniter\HTTP\URI;
use Spatie\Menu\Menu as BaseMenu;

/**
 * Menu Class
 *
 * A route-aware extension of Spatie\Menu.
 */
abstract class Menu
{
    /**
     * Instance of the Spatie Menu
     * to build with.
     *
     * @var BaseMenu
     */
    protected $builder;

    /**
     * Initializes the Spatie Menu and
     * sets the current URL to be "active".
     * Calls any additional functions
     * from traits.
     */
    public function __construct(?BaseMenu $builder = null)
    {
        $current = $this->detectCurrent();
        $root    = (new URI(site_url()))->getPath() ?: '/';

        $this->builder = $builder ?? BaseMenu::new()->setActive($current, $root);

        foreach (class_uses_recursive($this) as $trait) {
            $method = 'apply' . class_basename($trait);

            if (method_exists($this, $method)) {
                $this->{$method}();
            }
        }
    }

    /**
     * Returns the underlying Spatie Menu.
     */
    public function builder(): BaseMenu
    {
        return $this->builder;
    }

    /**
     * Builds the Menu and returns the
     * rendered HTML string.
     */
    abstract public function __toString(): string;

    /**
     * Returns the current URL to use for determining
     * which menu items should be active.
     * Due to this bug:
     * - https://github.com/codeigniter4/CodeIgniter4/issues/4116
     * ...we cannot use current_url(). This method can be
     * replaced if that bug is fixed or if we get this:
     * - https://github.com/codeigniter4/CodeIgniter4/pull/4647
     *
     * @internal
     */
    protected function detectCurrent(): string
    {
        // Force path discovery in a new IncomingRequest
        $request = service('request', null, false);
        $path    = ltrim($request->detectPath($request->config->uriProtocol), '/');

        // Build the full URL based on the config and path
        $url = rtrim($request->config->baseURL, '/ ') . '/';

        // Check for an index page
        if ($request->config->indexPage !== '') {
            $url .= $request->config->indexPage;

            // If there is a path then we need a separator
            if ($path !== '') {
                $url .= '/';
            }
        }

        return (string) (new URI($url . $path));
    }
}
