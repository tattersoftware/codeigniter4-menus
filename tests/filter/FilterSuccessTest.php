<?php namespace Tests\Support;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Test\FilterTestTrait;
use Tatter\Menus\Filters\MenusFilter;
use Tests\Support\MenusTestCase;

class FilterSuccessTest extends MenusTestCase
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
		$this->response->setHeader('Content-Type', 'text/html');
		$this->caller = $this->getFilterCaller(MenusFilter::class, 'after');
	}

	public function testValid()
	{
		$result = ($this->caller)(['test']);
		$this->assertInstanceOf(ResponseInterface::class, $result);

		$body = $result->getBody();
		$this->assertSame('bananas', $body);		
	}
}
