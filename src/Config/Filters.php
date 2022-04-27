<?php

namespace Tatter\Menus\Config;

use Config\Filters;
use Tatter\Menus\Filters\MenusFilter;

/**
 * @var Filters $filters
 */
$filters->aliases['menus'] = MenusFilter::class;
