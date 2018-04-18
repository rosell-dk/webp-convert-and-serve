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
        ), WebPConvertAndServe::$SERVE_ERROR_MESSAGE_TEXT, WebPConvertAndServe::$SERVE_404);
        $result = ob_get_contents();

        $this->assertInternalType('string', $result);
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

        $failAction = WebPConvertAndServe::$SERVE_ORIGINAL;
        $criticalFailAction = WebPConvertAndServe::$SERVE_404;

        ob_start();
        WebPConvertAndServe::convertAndServeImage($source, $destination, array(
            'converters' => array()
        ), WebPConvertAndServe::$SERVE_ORIGINAL, WebPConvertAndServe::$SERVE_404);

        $result = ob_get_contents();


        $this->assertContains('Content-type: image/jpeg', xdebug_get_headers());
    }*/
}
