<?php

/**
 * WebPConvert - Convert JPEG & PNG to WebP with PHP
 *
 * @link https://github.com/rosell-dk/webp-convert
 * @license MIT
 */

namespace WebPConvertAndServe\Tests;

use WebPConvertAndServe\WebPConvertAndServe;
use PHPUnit\Framework\TestCase;

class WebPConvertAndServeTest extends TestCase
{
    public function testConvertWithNoConverters()
    {
        $source = __DIR__ . '/test.jpg';
        $destination = $source . '.webp';

        ob_start();
        WebPConvertAndServe::convertAndServeImage($source, $destination, array(
            'converters' => array()
        ), WebPConvertAndServe::$REPORT, WebPConvertAndServe::$HTTP_404);
        $result = ob_get_contents();

        $this->assertInternalType('string', $result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testNormalFailOriginal()
    {
        $source = __DIR__ . '/test.jpg';
        $destination = __DIR__ . '/test.jpg.webp';

        // Remove all converters to trigger a normal fail
        ob_start();
        $result = WebPConvertAndServe::convertAndServeImage($source, $destination, array(
            'converters' => array()
        ), WebPConvertAndServe::$ORIGINAL, WebPConvertAndServe::$HTTP_404);
        $echoed = ob_get_contents();

        $this->assertEquals(WebPConvertAndServe::$ORIGINAL, $result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testSuccesOrNormalFail()
    {
        $source = __DIR__ . '/test.jpg';
        $destination = __DIR__ . '/test.jpg.webp';

        // Remove all converters to trigger a normal fail
        ob_start();
        $result = WebPConvertAndServe::convertAndServeImage(
            $source,
            $destination,
            [],
            WebPConvertAndServe::$ORIGINAL,
            WebPConvertAndServe::$HTTP_404
        );
        $echoed = ob_get_contents();

        $this->assertContains($result, [
            WebPConvertAndServe::$CONVERTED_IMAGE,
            WebPConvertAndServe::$ORIGINAL,
        ], $result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testCriticalFail404()
    {
        $source = __DIR__ . '/i-dont-exist.jpg';
        $destination = __DIR__ . '/i-should-never-exist.jpg.webp';

        // Remove all converters to trigger a normal fail
        ob_start();
        $result = WebPConvertAndServe::convertAndServeImage($source, $destination, array(
            'converters' => array()
        ), WebPConvertAndServe::$ORIGINAL, WebPConvertAndServe::$HTTP_404);
        $echoed = ob_get_contents();

        $this->assertEquals(WebPConvertAndServe::$HTTP_404, $result);
    }
    /*
    TODO: test headers.
    https://github.com/sebastianbergmann/phpunit/issues/720
    */

    /**
     * @runInSeparateProcess
     */
     /*
    public function testSomethingThatSendsHeaders()
    {
        // got code for testing headers here: https://github.com/sebastianbergmann/phpunit/issues/720

        //$this->expectException(\WebPConvert\Exceptions\NoOperationalConvertersException::class);
        $source = __DIR__ . '/test.jpg';
        $destination = __DIR__ . '/test.jpg.webp';

        $failAction = WebPConvertAndServe::$ORIGINAL;
        $criticalFailAction = WebPConvertAndServe::$HTTP_404;

        ob_start();
        WebPConvertAndServe::convertAndServeImage($source, $destination, array(
            'converters' => array()
        ), WebPConvertAndServe::$ORIGINAL, WebPConvertAndServe::$HTTP_404);

        $result = ob_get_contents();


        $this->assertContains('Content-type: image/jpeg', xdebug_get_headers());
    }*/
}
