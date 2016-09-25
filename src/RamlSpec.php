<?php

namespace DMS\Raml\PathValidator;

/**
 * Class RamlSpec
 */
class RamlSpec
{
    const HTTP_OPTIONS = 'options';
    const HTTP_HEAD    = 'head';
    const HTTP_GET     = 'get';
    const HTTP_POST    = 'post';
    const HTTP_PUT     = 'put';
    const HTTP_DELETE  = 'delete';
    const HTTP_PATCH   = 'patch';

    /**
     * @return array
     */
    public static function getValidMethods()
    {
        $ref = new \ReflectionClass(self::class);

        return array_filter(
            $ref->getConstants(),
            function ($value, $key) {
                return (strpos($key, 'HTTP') === 0);
            },
            ARRAY_FILTER_USE_BOTH
        );
    }

    /**
     * @param string $method
     *
     * @return bool
     */
    public static function isValidMethod($method)
    {
        return in_array(strtolower($method), self::getValidMethods(), true);
    }
}
