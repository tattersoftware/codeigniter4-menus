<?php namespace Tests\Support;

use Closure;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Test\FilterTestTrait;
use Tatter\Menus\Filters\MenusFilter;
use Tests\Support\MenusTestCase;

class MenusFilterTest extends MenusTestCase
{
	use FilterTestTrait;

	/**
	 * @var Closure
	 */
	private $caller;

	protected function setUp(): void
	{
		parent::setUp();

		$this->response->setBody('{{test}}');
		$this->response->setHeader('Content-Type', 'text/html');
		$this->caller = $this->getFilterCaller(MenusFilter::class, 'after');
	}

	public function testEmptyArguments()
	{
		$this->expectException('InvalidArgumentException');
		$this->expectExceptionMessage('No arguments supplied to Menus filter.');

		($this->caller)();
	}

	public function testRedirects()
	{
		$this->response = new RedirectResponse(config('App'));
		$caller = $this->getFilterCaller(MenusFilter::class, 'after');

		$result = $caller(['test']);

		$this->assertNull($result);
	}

	public function testUnknownAlias()
	{
		$this->expectException('RuntimeException');
		$this->expectExceptionMessage('Unknown menu alias requested: popcorn');

		($this->caller)(['popcorn']);
	}

	public function testUnknownClass()
	{
		$this->expectException('RuntimeException');
		$this->expectExceptionMessage('Unable to locate menu class: MissingMenu');

		($this->caller)(['fail']);
	}

	public function testMissingInterface()
	{
		$this->expectException('RuntimeException');
		$this->expectExceptionMessage('Tests\Support\Menus\NotMenu must extend Tatter\Menus\Menu');

		($this->caller)(['fake']);
	}

	public function testEmptyBody()
	{
		$this->response->setBody('');
		$caller = $this->getFilterCaller(MenusFilter::class, 'after');

		$result = ($caller)(['test']);
		$this->assertNull($result);
	}

	public function testWrongContentType()
	{
		$this->response->setHeader('Content-Type', 'application/json');
		$caller = $this->getFilterCaller(MenusFilter::class, 'after');

		$result = ($caller)(['test']);
		$this->assertNull($result);
	}

	public function testValid()
	{
		$result = ($this->caller)(['test']);
		$this->assertInstanceOf(ResponseInterface::class, $result);

		$body = $result->getBody();
		$this->assertSame('bananas', $body);		
	}
}
