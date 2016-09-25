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
     * @param string $pathString
     * @param array  $ramlDefinition
     *
     * @return array
     * @internal param string $path
     */
    protected function resolvePathPart($pathString, array $ramlDefinition)
    {
        $path = Path::fromString($pathString);

        $parts = $path->getParts();
        $iMax = count($parts);
        for ($i = 0; $i < $iMax; $i++) {

            $current = $path->getFullPathFromPart($parts[$i]);

            if (array_key_exists($current, $ramlDefinition)) {
                if ($i === count($parts) - 1) {
                    return $ramlDefinition[$current];
                } else {
                    return $this->resolvePathPart(
                        $path->getPathAfterPart($current),
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
