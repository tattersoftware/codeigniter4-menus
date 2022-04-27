<?php namespace Tatter\Menus\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Tatter\Menus\Menu;
use InvalidArgumentException;
use RuntimeException;

/**
 * Menus Filter
 *
 * Replaces menu tokens with actual
 * menu content from their aliases.
 */
class MenusFilter implements FilterInterface
{
	/**
	 * @codeCoverageIgnore
	 */
	public function before(RequestInterface $request, $arguments = null)
	{
	}

	/**
	 * Renders the menus and injects their content.
	 *
	 * @param RequestInterface  $request
	 * @param ResponseInterface $response
	 * @param array|null        $arguments
	 *
	 * @return ResponseInterface|null
	 */
	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null): ?ResponseInterface
	{
		// Verify menu conditions
		if (empty($arguments))
		{
			throw new InvalidArgumentException('No arguments supplied to Menus filter.');
		}

		// Ignore irrelevent responses
		if ($response instanceof RedirectResponse || empty($response->getBody()))
		{
			return null;
		}

		// Check CLI separately for coverage
		if (is_cli() && ENVIRONMENT !== 'testing')
		{
			return null; // @codeCoverageIgnore
		}

		// Only run on HTMl content
		if (strpos($response->getHeaderLine('Content-Type'), 'html') === false)
		{
			return null;
		}

		$config = config('Menus');
		$body   = $response->getBody();

		// Locate each placeholder
		foreach ($arguments as $alias)
		{
			if (! isset($config->aliases[$alias]))
			{
				throw new RuntimeException('Unknown menu alias requested: ' . $alias);
			}

			if (! class_exists($class = $config->aliases[$alias]))
			{
				throw new RuntimeException('Unable to locate menu class: ' . $class);
			}

			if (! is_a($class, Menu::class, true))
			{
				throw new RuntimeException($class . ' must extend ' . Menu::class);
			}

			// Grab the menu content
			$content = (string) (new $class);
			$count   = 0;

			// Swap the content for the placeholder
			$body = str_replace('{{' . $alias . '}}', $content, $body, $count);
		}

		// Use the new body and return the updated Response
		return $response->setBody($body);
	}
}
