# Tatter\Menus
Dynamic menus for CodeIgniter 4

[![](https://github.com/tattersoftware/codeigniter4-menus/workflows/PHPUnit/badge.svg)](https://github.com/tattersoftware/codeigniter4-menus/actions?query=workflow%3A%22PHPUnit%22)
[![](https://github.com/tattersoftware/codeigniter4-menus/workflows/PHPStan/badge.svg)](https://github.com/tattersoftware/codeigniter4-menus/actions?query=workflow%3A%PHPStan%22)
[![Coverage Status](https://coveralls.io/repos/github/tattersoftware/codeigniter4-menus/badge.svg?branch=develop)](https://coveralls.io/github/tattersoftware/codeigniter4-menus?branch=develop)

## Quick Start

1. Install with Composer: `> composer require tatter/menus`
2. Create your menus by extending `Tatter\Menus\Menu`
3. Add your menu aliases to `Config\Menus`
4. Apply `MenuFilter` to all routes that need menus

## Features

**Menus** provides dynamic menus across your application. **Menus** organizes and injects the
menu content, so you can focus on building.

## Installation

Install easily via Composer to take advantage of CodeIgniter 4's autoloading capabilities
and always be up-to-date:
* `> composer require tatter/menus`

Or, install manually by downloading the source files and adding the directory to
`app/Config/Autoload.php`.

## Configuration (optional)

The library's default behavior can be altered by extending its config file. Copy
**examples/Menus.php** to **app/Config/** and follow the instructions
in the comments. If no config file is found in app/Config the library will use its own.

## Usage

### Building

**Menus** is built on `Spatie\Menu` with all of its wonderful, dynamic and fluent functionality.
Use their documentation to craft your menus as simple or complex as you like:
* [Version 2](https://spatie.be/docs/menu/v2)
* [Version 3](https://spatie.be/docs/menu/v3) (PHP 8 only)

Create your menus by extending `Tatter\Menus\Menu`. You will notice in the source code that
`Menu` requires you to provide one method: `public function __toString(): string;`. You may use the
supplied `$builder` property to access the underlying `Spatie\Menu` to build your menu,
or provide your own HTML code or `view()` return. Some examples:
```
class MainMenu extends \Tatter\Menus\Menu
{
	public function __toString(): string
	{
		return $this->builder
			->link(site_url('/'), 'Home')
			->link(site_url('/about'), 'About')
			->html('<hr>')
			->link(site_url('/contact'), 'Contact')
			->render();
	}
}

class FruitMenu extends \Tatter\Menus\Menu
{
	public function __toString(): string
	{
		return view('menus/fruit', ['active' => 'banana']);
	}
}
```

Note: `$builder` is initialized with "set active" to the current URL. You may call `setActive()`
again to remove or change the active menu item. Due to a limitation in `Spatie\Menu` with mixing
relative and absolute URLs you must supply full URL values (e.g. with `site_url()`) to your
`Menu` if you want to use this default "active" URL.

### Deploying

Since `Menu` is `Stringable` it can be used in your view or layout files as is.
However, **Menus** also comes with a [Controller Filter](https://codeigniter4.github.io/CodeIgniter4/incoming/filters.html)
that you can use to inject menu content directly into your responses. First you need to create
an alias for each `Menu` class you would like to use. Create **app/Config/Menus.php** (or
start with a copy from the **examples** folder) and add your menu classes to the `$aliases`
array. For example:
```
class Menus extends \Tatter\Menus\Config\Menus
{
	/**
	 * Menu class aliases.
	 *
	 * @var array<string, string>
	 */
	public $aliases = [
		'main'  => \App\Menus\MainMenu::class,
		'fruit' => \ShopModule\FruitMenu::class,
	];
}
```

Once aliases are set up you can pass them as an argument to the `MenuFilter` for any route:
```
$routes->add('shop/(:any)', 'ShopModule\ShopController::show/$1', ['filter' => 'menus:fruit']);
```

Then in your view or layout put the placeholder token with the name of the alias target in
double curly braces:
```
<html>
	<body>
		{{main}}
		<h1>Fruit Shop</h1>
		{{fruit}}
...
```

Note that sometimes it is preferable to apply the filter in bulk using **app/Config/Filters.php**.
Unfortunately parameters are [not yet supported](https://github.com/codeigniter4/CodeIgniter4/issues/2078)
in `Config\Filters`, but you can work around this by creating your own parameter-specific Filter:
```
<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Tatter\Menus\Filters\MenusFilter;

class MainMenuFilter extends MenusFilter
{
	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null): ?ResponseInterface
	{
		return parent::after($request, $response, ['main', 'fruit']);
	}
}
```

## Packaged Menus

`Menus` comes with some pre-made menus which can be used immediately or built on to create
your own variants. All menus are in the `Tatter\Menus\Menus` namespace and extend the `Menu`
class so can be used with the Filter or as any other menu you would make.

### Breadcrumbs

The `BreadcrumbsMenu` is a special menu, using horizontal-style navigation links for nested
content. This menu comes pre-styled for [Bootstrap](https://getbootstrap.com/docs/4.3/components/breadcrumb/)
and defaults to the segments retrieved from the framework's `IncomingRequest::$uri`, but
you may provide your own using the static methods `set`, `get`, `push`, and `pop`. Additionally,
`BreadcrumbsMenu::discover()` will attempt to create a default menu. All these methods use
the `Breadcrumb` class, a simple wrapper for the URL and display value.
For example:
```
use Tatter\Menus\Breadcrumb;
use Tatter\Menus\Menus\BreadcrumbsMenu;

class Users extends Controller
{
	public function show(int $userId)
	{
		// Get the User
		$user = model('UserModel')->find($userId);

		// Start with the default breadcrumbs
		BreadcrumbsMenu::discover();

		// Pop off the numeric last segment
		BreadcrumbsMenu::pop();

		// Replace it with the user's name
		BreadcrumbsMenu::push(new Breadcrumb(current_url(), $user->name));

		return view('users/show', ['user' => $user]);
	}
}
```
... if you have the filter in place the rest is handled for you.
