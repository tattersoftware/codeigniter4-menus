<?php

namespace Tatter\Menus;

use CodeIgniter\HTTP\URI;

/**
 * Breadcrumb Class
 *
 * Represents a single item
 * in a breadcrumb menu.
 */
class Breadcrumb
{
    /**
     * The URL.
     *
     * @var string
     */
    public $url;

    /**
     * The display value.
     *
     * @var string
     */
    public $display;

    public function __construct(string $url, ?string $display = null)
    {
        // If no display was passed then make a best guess
        if (empty($display)) {
            $uri      = new URI($url);
            $segments = $uri->getSegments();
            $display  = ucfirst(end($segments) ?: lang('Menus.home'));
        }

        $this->url     = $url;
        $this->display = $display;
    }
}
