<?php

namespace DMS\Raml\PathValidator\Exception;

/**
 * Class InvalidMethodException
 */
class InvalidMethodException extends \Exception
{
    /**
     * @param string $method
     *
     * @return static
     */
    public static function forMethod($method)
    {
        return new static(sprintf("%s is not supported by RAML", strtoupper($method)));
    }
}
