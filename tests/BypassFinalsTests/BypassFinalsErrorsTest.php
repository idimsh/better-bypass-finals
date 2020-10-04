<?php
declare(strict_types=1);

namespace BypassFinalsTests;

use PHPUnit\Framework\Error\Warning;
use PHPUnit\Framework\TestCase;

/**
 * @covers \idimsh\BypassFinals
 */
final class BypassFinalsErrorsTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        \idimsh\BypassFinals::enable();
    }

    protected function tearDown()
    {
        \idimsh\BypassFinals::disable();
        parent::tearDown();
    }

    public function dataWarnings(): \Generator
    {
        yield 'chmod' => [function () {
            chmod('unknown', 0777);
        }];
        yield 'copy' => [function () {
            copy('unknown', 'unknown2');
        }];
        yield 'file_get_contents' => [function () {
            file_get_contents('unknown');
        }];
        yield 'file_put_contents' => [function () {
            file_put_contents(__DIR__, 'content');
        }];
        yield 'file' => [function () {
            file('unknown');
        }];
        yield 'fileatime' => [function () {
            fileatime('unknown');
        }];
        yield 'filectime' => [function () {
            filectime('unknown');
        }];
        yield 'filegroup' => [function () {
            filegroup('unknown');
        }];
        yield 'fileinode' => [function () {
            fileinode('unknown');
        }];
        yield 'filemtime' => [function () {
            filemtime('unknown');
        }];
        yield 'fileowner' => [function () {
            fileowner('unknown');
        }];
        yield 'fileperms' => [function () {
            fileperms('unknown');
        }];
        yield 'filesize' => [function () {
            filesize('unknown');
        }];
        yield 'filetype' => [function () {
            filetype('unknown');
        }];
        yield 'fopen' => [function () {
            fopen('unknown', 'r');
        }];
        yield 'link' => [function () {
            link('unknown', 'unknown2');
        }];
        yield 'linkinfo' => [function () {
            linkinfo('unknown');
        }];
        yield 'lstat' => [function () {
            lstat('unknown');
        }];
        yield 'mkdir' => [function () {
            mkdir(__DIR__);
        }];
        yield 'parse_ini_file' => [function () {
            parse_ini_file('unknown');
        }];
        yield 'readfile' => [function () {
            readfile('unknown');
        }];
        yield 'readlink' => [function () {
            readlink('unknown');
        }];
        yield 'rename' => [function () {
            rename('unknown', 'unknown2');
        }];
        yield 'rmdir' => [function () {
            rmdir('unknown');
        }];
        yield 'stat' => [function () {
            stat('unknown');
        }];
        yield 'unlink' => [function () {
            unlink('unknown');
        }];

        if (!defined('PHP_WINDOWS_VERSION_BUILD')) {
            yield 'chgrp' => [function () {
                chgrp('unknown', 'group');
            }];
            yield 'chown' => [function () {
                chown('unknown', 'user');
            }];
            yield 'lchgrp' => [function () {
                lchgrp('unknown', 'group');
            }];
            yield 'lchown' => [function () {
                lchown('unknown', 'user');
            }];
        }
    }

    /**
     * @dataProvider dataWarnings
     * @param callable $callable
     */
    public function testWarnings(callable $callable): void
    {
        $this->expectException(Warning::class);
        $callable();
    }

    public function dataFalses(): \Generator
    {
        yield 'file_exists' => [function () {
            return file_exists('unknown');
        }];
        yield 'is_dir' => [function () {
            return is_dir('unknown');
        }];
        yield 'is_executable' => [function () {
            return is_executable('unknown');
        }];
        yield 'is_file' => [function () {
            return is_file('unknown');
        }];
        yield 'is_link' => [function () {
            return is_link('unknown');
        }];
        yield 'is_readable' => [function () {
            return is_readable('unknown');
        }];
        yield 'is_writable' => [function () {
            return is_writable('unknown');
        }];
        yield 'is_dir' => [function () {
            return is_dir('unknown');
        }];
        yield 'realpath' => [function () {
            return realpath('unknown');
        }];
    }

    /**
     * @dataProvider dataFalses
     * @param callable $callable
     */
    public function testFalses(callable $callable): void
    {
        $this->assertFalse($callable());
    }

    public function testMisc(): void
    {
        $this->assertSame([], glob('unknown'));
        $this->assertSame(-1, fseek(fopen(__FILE__, 'r'), -1));
    }

}
