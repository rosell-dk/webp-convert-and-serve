# WebP Convert and Serve

[![Build Status](https://travis-ci.org/rosell-dk/webp-convert.png?branch=master)](https://travis-ci.org/rosell-dk/webp-convert-and-serve)

This library can be used for converting and serving WebP images instead of jpeg/png. It is based on [WebPConvert](https://github.com/rosell-dk/webp-convert), which takes care of the conversion. On top of that, it adds a method for serving the converted image with options on how to handle conversion failures.

The task of serving is in itself actually quite small.
It can be implemented in a few lines, like this:

```php
try {
    $success = WebPConvert::convert($source, $destination, $options);
    if ($success) {
        header('Content-type: image/webp');
        readfile($destination);        
    }
}
```

*But then comes error handling.*

If conversion fails, it will make sense to serve the source image instead (if that exists, that is). For that, we need to inspect the extension in order to provide the correct Content-type header. Also, we want to add headers that tells the browser not to cache it. And what if the source file isn't even available? This should be handled as well. Tedious stuff, that this library takes care of.


## API

**WebPConvertAndServe::convertAndServeImage($source, $destination, $options, $failAction, $criticalFailAction)**

| Parameter                   | Type    | Description                                                                                |
| --------------------------- | ------- | ------------------------------------------------------------------------------------------ |
| `$source`                   | String  | Absolute path to source image (only forward slashes allowed)                               |
| `$destination`              | String  | Absolute path to converted image (only forward slashes allowed)                            |
| `$options`                  | Array   | Array of conversion options (see WebPConvert documentation)                                |
| `$failAction`               | Number  | How to handle conversion failure                                                           |
| `$criticalFailAction`       | Number  | How to handle when source image is missing or isn't an image                               |


**failAction**

Indicate what to serve, in case of normal conversion failure

| Possible values:                                    | Meaning                                         |
| --------------------------------------------------- | ----------------------------------------------- |
| *WebPConvertAndServe::$ORIGINAL*                    | Serve the original image.                       |
| *WebPConvertAndServe::$HTTP_404*                    | Serve 404 status (not found)                    |
| *WebPConvertAndServe::$REPORT_AS_IMAGE*             | Serve an image with text explaining the problem |
| *WebPConvertAndServe::$REPORT*                      | Serve a textual report explaining the problem   |

**criticalFailAction**

Possible values: Same as above, except that `ORIGINAL` is not an option.

**Return value**

Number indicating what was served. On fail or critical fail, the value will be one of the constants listed in failAction. On success, it will be *WebPConvertAndServe::$CONVERTED_IMAGE*. All fail constants are negative. The success constant is positive &ndash; so you can test success with a `if ($returnValue > 0)`

# Example:

```php
require 'vendor/autoload.php';

use WebPConvertAndServe\WebPConvertAndServe;

$source = __DIR__ . '/logo.jpg';
$destination = $source . '.webp';
$options = [];

$failAction = WebPConvertAndServe::$ORIGINAL;
//$failAction = WebPConvertAndServe::$REPORT;
//$failAction = WebPConvertAndServe::$HTTP_404;
//$failAction = WebPConvertAndServe::$REPORT_AS_IMAGE;

//$criticalFailAction = WebPConvertAndServe::$REPORT;
$criticalFailAction = WebPConvertAndServe::$HTTP_404;
//$criticalFailAction = WebPConvertAndServe::$REPORT_AS_IMAGE;

WebPConvertAndServe::convertAndServeImage($source, $destination, $options, $failAction, $criticalFailAction);
```


# Installing

Run `composer require rosell-dk/webp-convert-and-serve`
