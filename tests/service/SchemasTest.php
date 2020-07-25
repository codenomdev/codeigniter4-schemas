<?php

use Codenom\Schemas\Handlers\BaseHandler;
use Codenom\Schemas\Interfaces\SchemaHandlerInterface;
use Codenom\Schemas\Structures\Schema;

class SchemasTest extends Tests\Support\UnitTestCase
{
	public function testGetErrors()
	{
		$this->assertEquals([], $this->schemas->getErrors());
	}

	public function testStartsWithoutSchema()
	{
		$this->config->silent = true;
		$this->assertNull($this->schemas->get());
	}

	/*
	public function testGetHandlerFromClass()
	{
		$command = new \Codenom\Schemas\Commands\Schemas();
		$method = $this->getPrivateMethodInvoker($command, 'getHandlerFromClass');
		
		$handler = $method('Drafter', 'database');
		$this->assertInstanceOf(BaseHandler::class, $handler);

		$handler = $method('Archiver', 'cache');
		$this->assertInstanceOf(BaseHandler::class, $handler);
	}
*/
}
