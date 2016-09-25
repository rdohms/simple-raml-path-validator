<?php

namespace DMS\Raml\PathValidator\Tests;

use DMS\Raml\PathValidator\Validator;

/**
 * Class DefinitionReaderTest
 */
class DefinitionReaderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var array
     */
    protected static $rawRaml;

    /**
     * @var Validator
     */
    protected static $reader;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$reader = Validator::forRamlFile(__DIR__ . '/samples/sample.raml');
    }

    /**
     * @param $path
     * @param $expectedResult
     *
     * @return void
     *
     * @dataProvider providePaths
     */
    public function testIsPathDefined($method, $path, $expectedResult)
    {
        self::assertEquals($expectedResult, self::$reader->isValidDefinedPath($method, $path));
    }

    /**
     * @return array
     */
    public function providePaths()
    {
        return [
            'root'                             => ['GET', '/', true],
            'regular, depth  = 1'              => ['GET', '/v2/user', true],
            'regular, depth  = 2'              => ['GET', '/v2/user/{id}', true],
            'regular, depth  = 2, other verb'  => ['POST', '/v2/user/{id}', true],
            'greedy, depth = 3'                => ['GET', '/v2/account/email/{email}', true],
            'greedy with middleman, depth = 2' => ['GET', '/v2/setting/preference', true],
            'greedy with middleman, depth = 3' => ['GET', '/v2/setting/preference/{id}', true],
            'mixed params'                     => ['GET', '/v2/{id}/{name}', true],
            'mixed params with extra'          => ['POST', '/v2/{id}/{name}/reset', true],
            'invalid verb'                     => ['PUT', '/', false],
            'invalid verb, depth > 1'          => ['PUT', '/v2/setting/preference/{id}', false],
            'invalid path'                     => ['GET', '/v2/something/not-here/{id}', false],
            'invalid path, with valid partial' => ['GET', '/v2/user/not-here/{id}', false],
        ];
    }
}
