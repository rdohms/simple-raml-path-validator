<?php

namespace DMS\Raml\PathValidator;

use DMS\Raml\PathValidator\Exception\FileNotFound;
use DMS\Raml\PathValidator\Exception\InvalidMethodException;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Validator
 */
class Validator
{
    /**
     * @var array
     */
    protected $rawRaml;

    /**
     * Validator constructor.
     *
     * @param array $rawRaml
     */
    public function __construct(array $rawRaml)
    {
        $this->rawRaml = $rawRaml;
    }

    /**
     * @param string $method
     * @param string $path
     *
     * @return bool
     * @throws \Exception if method is invalid
     */
    public function isValidDefinedPath($method, $path)
    {
        if (RamlSpec::isValidMethod($method) === false) {
            throw InvalidMethodException::forMethod($method);
        }

        $pathDefinition = $this->resolvePathPart($path, $this->rawRaml);

        return $this->doesPathDefineMethod($method, $pathDefinition);
    }

    /**
     * @param string $method
     * @param array  $pathDefinition
     *
     * @return bool
     */
    protected function doesPathDefineMethod($method, array $pathDefinition)
    {
        return array_key_exists(strtolower($method), $pathDefinition);
    }

    /**
     * @param string $path
     * @param array  $ramlDefinition
     *
     * @return array
     */
    protected function resolvePathPart($path, array $ramlDefinition)
    {
        if ($path[0] === '/') {
            $parts = explode('/', substr($path, 1));
        } else {
            $parts = explode('/', $path);
        }

        $iMax = count($parts);
        for ($i = 0; $i < $iMax; $i++) {

            $current = '';
            $x       = $i;
            while ($x >= 0) {
                $current = '/' . $parts[$x] . $current;
                $x--;
            }

            if (array_key_exists($current, $ramlDefinition)) {
                if ($i === count($parts) - 1) {
                    return $ramlDefinition[$current];
                } else {
                    return $this->resolvePathPart(
                        str_replace($current, '', strstr($path, $current)),
                        $ramlDefinition[$current]
                    );
                }
            }
        }

        return [];
    }

    /**
     * @param string $file
     *
     * @return static
     * @throws FileNotFound
     */
    public static function forRamlFile($file)
    {

        if (file_exists($file) === false) {
            throw FileNotFound::withPath($file);
        }

        $content = Yaml::parse(file_get_contents($file));
        return new static($content);
    }
}
