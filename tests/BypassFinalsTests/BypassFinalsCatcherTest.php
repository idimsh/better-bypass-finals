<?php
declare(strict_types=1);

namespace BypassFinalsTests;

use PHPUnit\Framework\Error\Warning;
use PHPUnit\Framework\TestCase;

/**
 * @covers \idimsh\BypassFinalsCatcher
 * @runTestsInSeparateProcesses
 */
final class BypassFinalsCatcherTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        \idimsh\BypassFinalsCatcher::enable();
    }

    protected function tearDown()
    {
        \idimsh\BypassFinalsCatcher::disable();
        parent::tearDown();
    }


    public function testFinalClass(): void
    {
        require __DIR__ . '/fixtures/final.class.php';

        $rc = new \ReflectionClass('FinalClass');
        $this->assertFalse($rc->isFinal());
        $this->assertFalse($rc->getMethod('finalMethod')->isFinal());
        $this->assertSame(123, \FinalClass::FINAL);
        $this->assertSame(456, (new \FinalClass)->final());
    }

    public function testFinalClassException(): void
    {
        \idimsh\BypassFinalsCatcher::setWhitelist(
            [
                '*/fixtures/final.class.php',
            ]
        );

        require __DIR__ . '/fixtures/final.class.php';
        require __DIR__ . '/fixtures/final.excluded.class.php';

        $rc = new \ReflectionClass('FinalClass');
        $this->assertFalse($rc->isFinal());

        $rc = new \ReflectionClass('FinalClassExcluded');
        $this->assertTrue($rc->isFinal());
    }

    public function testLock(): void
    {
        file_put_contents(__DIR__ . '/fixtures/tmp', 'foo', LOCK_EX);
        unlink(__DIR__ . '/fixtures/tmp');
        $this->assertSame(1, 1, 'no error so test passed');
    }

    public function testMagic(): void
    {
        require __DIR__ . '/fixtures/magic.constants.php';

        $rc = new \ReflectionClass('Foo');
        $this->assertFalse($rc->isFinal());

        $res = getMagic();
        $this->assertSame(__DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'magic.constants.php', $res[0]);
        $this->assertSame(__DIR__ . DIRECTORY_SEPARATOR . 'fixtures', $res[1]);
        $this->assertSame(__DIR__ . DIRECTORY_SEPARATOR . 'fixtures', $res[2]);
    }

    public function testMkdirNotRecursive(): void
    {
        @rmdir(__DIR__ . '/temp/sub');
        @rmdir(__DIR__ . '/temp');

        $this->expectException(Warning::class);
        mkdir(__DIR__ . '/temp/sub');

        @rmdir(__DIR__ . '/temp/sub');
        @rmdir(__DIR__ . '/temp');
    }

    public function testMkdirRecursive(): void
    {
        @rmdir(__DIR__ . '/temp/sub');
        @rmdir(__DIR__ . '/temp');
        mkdir(__DIR__ . '/temp/sub', 0777, true);

        @rmdir(__DIR__ . '/temp/sub');
        @rmdir(__DIR__ . '/temp');
        $this->assertSame(1, 1, 'no error so test passed');
    }

    public function test_file_put_contents(): void
    {
        file_put_contents(__DIR__ . '/fixtures/not_existing_class.php', 'test');
        unlink(__DIR__ . '/fixtures/not_existing_class.php');

        $this->assertSame(1, 1, 'no error so test passed');
    }

    public function testTouch1(): void
    {
        touch('known');
        unlink('known');
        $this->assertSame(1, 1, 'no error so test passed');
    }

    public function testTouch2(): void
    {
        touch('known', time());
        unlink('known');
        $this->assertSame(1, 1, 'no error so test passed');
    }

    public function testTouch3(): void
    {
        $this->expectException(\TypeError::class);
        touch('known', 'foo');
    }

    public function testWhiteList(): void
    {
        $reflectionMethod = new \ReflectionMethod(\idimsh\BypassFinalsCatcher::class, 'isPathInWhiteList');
        $reflectionMethod->setAccessible(true);
        $this->assertTrue($reflectionMethod->invoke(null, __DIR__ . '/fixtures/final.class.php'));

        \idimsh\BypassFinalsCatcher::setWhitelist(
            [
                __DIR__ . '/fixtures/final.class.php',
            ]
        );
        $this->assertTrue($reflectionMethod->invoke(null, __DIR__ . '/fixtures/final.class.php'));
        $this->assertFalse($reflectionMethod->invoke(null, __DIR__ . '/fixtures/other.class.php'));


        \idimsh\BypassFinalsCatcher::setWhitelist(
            [
                __DIR__ . '/fixtures/*',
            ]
        );
        $this->assertTrue($reflectionMethod->invoke(null, __DIR__ . '/fixtures/class.php'));
        $this->assertFalse($reflectionMethod->invoke(null, __DIR__ . '/other/class.php'));
    }
}
