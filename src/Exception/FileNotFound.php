<?php

namespace DMS\Raml\PathValidator\Exception;

/**
 * Class FileNotFound
 */
class FileNotFound extends \Exception
{
    /**
     * @param string $path
     *
     * @return static
     */
    public static function withPath($path)
    {
        return new static(sprintf('Unable to find a file at: %s', $path));
    }
}
