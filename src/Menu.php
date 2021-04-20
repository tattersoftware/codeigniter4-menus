<?php namespace Tatter\Menus;

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
	 *
	 * @param BaseMenu|null $builder
	 */
    public function __construct(BaseMenu $builder = null)
    {
		$this->builder = $builder ?? BaseMenu::new()->setActive(
			current_url(),
			(new URI(base_url()))->getPath() ?? '/'
		);

		foreach (class_uses_recursive($this) as $trait)
		{
			$method = 'apply' . class_basename($trait);

			if (method_exists($this, $method))
			{
				$this->$method();
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
	 *
	 * @return string
	 */
	abstract public function get(): string;
}
