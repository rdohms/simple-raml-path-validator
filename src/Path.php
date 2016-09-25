<?php

namespace DMS\Raml\PathValidator;

class Path
{
    protected $path;

    /**
     * Path constructor.
     *
     * @param $path
     */
    public function __construct($path)
    {
        $this->path = $this->normalizePath($path);
    }

    /**
     * @param $path
     *
     * @return string
     */
    protected function normalizePath($path)
    {
        return ltrim($path, '/');
    }

    /**
     * @return array
     */
    public function getParts()
    {
        return explode('/', $this->path);
    }

    /**
     * @param string $part
     *
     * @return string
     */
    public function getFullPathFromPart($part = '')
    {
        if (empty($part)) {
            return '/';
        }

        return '/' . stristr($this->path, $part, true) . $part;
    }

    /**
     * @param $part
     *
     * @return mixed
     */
    public function getPathAfterPart($part)
    {
        return str_replace($part, '', strstr('/' . $this->path, $part));
    }

    /**
     * @param string $path
     *
     * @return static
     */
    public static function fromString($path)
    {
        return new static($path);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
