<?php
declare(strict_types=1);

namespace BypassFinalsTests;

use PHPUnit\Framework\TestCase;

/**
 * @covers \idimsh\BypassFinalsCatcher
 */
final class BypassFinalsCatcherSyntaxErrorTest extends TestCase
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


    public function testFinalClassWithSyntaxError(): void
    {
        @unlink('errors');
        $errorStream = fopen('errors', 'a');
        \idimsh\BypassFinalsCatcher::setErrorStream($errorStream);
        register_shutdown_function(
            static function () use ($errorStream) {
                register_shutdown_function(
                    static function () use ($errorStream) {
                        fclose($errorStream);
                        static::assertStringContainsString('final.class.with-syntax-error.php', file_get_contents('errors'));
                        unlink('errors');
                    }
                );
            }
        );
        $this->expectException(\ParseError::class);
        file_get_contents(__DIR__ . '/fixtures/final.class.with-syntax-error.php');
    }
}
