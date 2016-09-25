<?php

namespace DMS\Raml\PathValidator\Tests;

use Symfony\Component\Yaml\Yaml;
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
    protected $reader;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$rawRaml = Yaml::parse(file_get_contents(__DIR__ . '/samples/sample.raml'));
    }

    protected function setUp()
    {
        parent::setUp();
        $this->reader = new Validator(self::$rawRaml);

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
        self::assertEquals($expectedResult, $this->reader->isValidDefinedPath($method, $path));
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
        ];
    }
}
