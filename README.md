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

***But then comes error handling.***

If conversion fails, it will make sense to serve the source image instead (if that exists, that is). For that, we need to inspect the extension in order to provide the correct Content-type header. Also, we want to add headers that tells the browser not to cache it. And what if the source file isn't even available? This should be handled as well. Tedious stuff, that this library takes care of.


## API


### WebPConvertAndServe::convertAndServe($source, $destination, $options)
*Note: This method was added in 0.4.0. The old one, `convertAndServeImage()` still works, but is depreciated.*

| Parameter                   | Type    | Description                                                                                |
| --------------------------- | ------- | ------------------------------------------------------------------------------------------ |
| *$source*                   | String  | Absolute path to source image (only forward slashes allowed)                               |
| *$destination*              | String  | Absolute path to converted image (only forward slashes allowed)                            |
| *$options*                  | Array   | Array of conversion options. See below |

#### The *$options* argument

The options argument is a named array. *WebPConvertAndServe* has just two available options (*fail* and *critical-fail*). However, the options will be handed over to *WebPConvert*. So Any option available in webp-convert are available here.


##### *fail*

Indicate what to serve, in case of normal conversion failure.
Default value: *"original"*

| Possible values   | Meaning                                         |
| ----------------- | ----------------------------------------------- |
| "original"        | Serve the original image.                       |
| "404"             | Serve 404 status (not found)                    |
| "report-as-image" | Serve an image with text explaining the problem |
| "report"          | Serve a textual report explaining the problem   |

Instead of the string values (ie "original"), you can also use the following constants: *WebPConvertAndServe::$ORIGINAL*, *WebPConvertAndServe::$HTTP_404*, *WebPConvertAndServe::$REPORT_AS_IMAGE* and *WebPConvertAndServe::$REPORT*

##### critical-fail

Possible values: Same as above, except that "original" is not an option.
Default value: *"404"*

##### Return value

Number indicating what was served. On fail or critical fail, the value will be one of the following constants: following constants *WebPConvertAndServe::$ORIGINAL*, *WebPConvertAndServe::$HTTP_404*, *WebPConvertAndServe::$REPORT_AS_IMAGE* and *WebPConvertAndServe::$REPORT*. On success, it will be *WebPConvertAndServe::$CONVERTED_IMAGE*. All fail constants are negative. The success constant is positive &ndash; so you can test success with a `if ($returnValue > 0)`

# Example:

```php
require 'vendor/autoload.php';

use WebPConvertAndServe\WebPConvertAndServe;

$source = __DIR__ . '/logo.jpg';
$destination = $source . '.webp';
$options = [
    'fail' => 'original',
    'critical-fail' => '404',

    // You can specify any WebPConvert option here - such as defining a converters array, which
    // is needed, if you need to use a cloud converter
    'converters' => [
        [
            'converter' => 'ewww',
            'options' => [
                'key' => 'blah',
            ],
        ],
        'cwebp',
        'gd'
    ];

$status = WebPConvertAndServe::convertAndServe($source, $destination, $options);

```


# Installing

`composer require rosell-dk/webp-convert-and-serve`
