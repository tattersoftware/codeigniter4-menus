<?php namespace Tatter\Menus\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Tatter\Menus\Menu;
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
		// Check a few short-circuit conditions
		if (empty($arguments))
		{
			throw new RuntimeException('No arguments supplied to Menus filter.');
		}
		if (empty($response->getBody()))
		{
			throw new RuntimeException('Response body is empty.');
		}
		if (strpos($response->getHeaderLine('Content-Type'), 'html') === false)
		{
			throw new RuntimeException('Menus may only be applied to HTML content.');
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

			// Swap the content for the placeholder and verify a match
			$body = str_replace('{{' . $alias . '}}', $content, $body, $count);
			if ($count === 0)
			{
				throw new RuntimeException('Missing placeholder text for menu: ' . $alias);
			}
		}

		// Use the new body and return the updated Reponse
		return $response->setBody($body);
	}
}
