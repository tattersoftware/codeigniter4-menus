<?php namespace Tests\Support;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Test\FilterTestTrait;
use Tatter\Menus\Filters\MenusFilter;
use Tests\Support\MenusTestCase;

class FilterTest extends MenusTestCase
{
	use FilterTestTrait;

	/**
	 * @var \Closure
	 */
	private $caller;

	protected function setUp(): void
	{
		parent::setUp();

		$this->response->setBody('{{test}}');
		$this->caller = $this->getFilterCaller(MenusFilter::class, 'after');
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
		$this->expectExceptionMessage('Tests\Support\Menus\NotMenu must implement MenuInterface');

		($this->caller)(['fake']);
	}

	public function testMissingPlaceholder()
	{
		$this->response->setBody('{{foobar}}');
		$caller = $this->getFilterCaller(MenusFilter::class, 'after');

		$this->expectException('RuntimeException');
		$this->expectExceptionMessage('Missing placeholder text for menu: test');

		$caller(['test']);
	}

	public function testValid()
	{
		$result = ($this->caller)(['test']);
		$this->assertInstanceOf(ResponseInterface::class, $result);

		$body = $result->getBody();
		$this->assertSame('bananas', $body);		
	}
}
