<?php

namespace Tests\Unit;

require_once __DIR__ . '/../Testing/Common.php';

class LinqTest extends \PHPUnit_Framework_TestCase
{
    function testFunctions ()
    {
        $this->assertInstanceOf('YaLinqo\Enumerable', from(new \EmptyIterator));
    }
}
