<?php
declare(strict_types=1);

namespace idimsh\BypassFinal\Listener;

use idimsh\BypassFinals;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\Warning;

/**
 * Usage is to be included in phpunit.xml configuration as a listener like:
 *
 * ```xml
 * <phpunit bootstrap="vendor/autoload.php">
 *     <listeners>
 *         <listener class="idimsh\BypassFinal\Listener\BypassFinalListener"/>
 *     </listeners>
 * </phpunit>
 * ```
 */
class BypassFinalListener implements TestListener
{
    public function addError(Test $test, \Throwable $t, float $time): void
    {
    }

    public function addWarning(Test $test, Warning $e, float $time): void
    {
    }

    public function addFailure(Test $test, AssertionFailedError $e, float $time): void
    {
    }

    public function addIncompleteTest(Test $test, \Throwable $t, float $time): void
    {
    }

    public function addRiskyTest(Test $test, \Throwable $t, float $time): void
    {
    }

    public function addSkippedTest(Test $test, \Throwable $t, float $time): void
    {
    }

    public function startTestSuite(TestSuite $suite): void
    {
    }

    public function endTestSuite(TestSuite $suite): void
    {
    }

    public function startTest(Test $test): void
    {
        BypassFinals::enable();
    }

    public function endTest(Test $test, float $time): void
    {
        BypassFinals::disable();
    }
}
