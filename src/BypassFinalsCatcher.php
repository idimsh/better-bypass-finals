<?php
declare(strict_types=1);

namespace idimsh;

class BypassFinalsCatcher extends BypassFinals
{
    /** @var bool */
    protected static $isShutdownHandlerRegistered = false;

    /** @var string|null */
    protected static $lastPath = null;

    /** @var bool|resource */
    protected static $errorStream = STDERR;

    /**
     * @param resource $stream
     * @throws \TypeError
     */
    public static function setErrorStream($stream): void
    {
        if (!is_resource($stream)) {
            throw new \TypeError(
                'Paramter 0 passed to ' . __METHOD__ . ' must be of type resource, got type: ' . gettype($stream)
            );
        }
        static::$errorStream = $stream;
    }

    /**
     * @return resource
     */
    public static function getErrorStream()
    {
        return static::$errorStream;
    }


    public static function removeFinals(string $code, string $path): string
    {
        if (!static::$isShutdownHandlerRegistered) {
            static::$isShutdownHandlerRegistered = true;
            register_shutdown_function(
                static function () {
                    $triggerErrorMessage = static::$lastPath !== null && PHP_SAPI === 'cli';
                    if ($triggerErrorMessage) {
                        fputs(static::getErrorStream(), sprintf("Error occurred while processing the file at path: [%s]\n\n", static::$lastPath));
                    }
                }
            );
        }
        if (stripos($code, 'final') !== false) {
            static::$lastPath = $path;
            $tokens           = @token_get_all($code, TOKEN_PARSE);
            static::$lastPath = null;
            $code             = '';
            foreach ($tokens as $token) {
                $code .= is_array($token)
                    ? ($token[0] === T_FINAL ? '' : $token[1])
                    : $token;
            }
        }
        return $code;
    }
}
