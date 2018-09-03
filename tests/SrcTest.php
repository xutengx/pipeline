<?php
declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Xutengx\Pipeline\Pipeline;

final class SrcTest extends TestCase {

	public function setUp() {
	}

	public function testObject() {
		$this->assertInstanceOf(Pipeline::class, $pipeline = new Pipeline);

	}

}


