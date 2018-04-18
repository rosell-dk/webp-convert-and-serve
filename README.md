# WebP Convert and Serve

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

| Possible values:                                    | Meaning                                         |
| --------------------------------------------------- | ----------------------------------------------- |
| *WebPConvertAndServe::SERVE_ORIGINAL*               | Serve the original image.                       |
| *WebPConvertAndServe::$SERVE_404*                   | Serve 404 status (not found)                    |
| *WebPConvertAndServe::$SERVE_ERROR_MESSAGE_IMAGE*   | Serve an image with text explaining the problem |
| *WebPConvertAndServe::$SERVE_ERROR_MESSAGE_TEXT*    | Serve text explaining the problem               |

**criticalFailAction**

Possible values: Same as above, except that `SERVE_ORIGINAL` is not an option.

# Example:

```php
require 'vendor/autoload.php';

use WebPConvertAndServe\WebPConvertAndServe;

$source = __DIR__ . '/logo.jpg';
$destination = $source . '.webp';
$options = [];

$failAction = WebPConvertAndServe::$SERVE_ORIGINAL;
//$failAction = WebPConvertAndServe::$SERVE_ERROR_MESSAGE_TEXT;
//$failAction = WebPConvertAndServe::$SERVE_404;
//$failAction = WebPConvertAndServe::$SERVE_ERROR_MESSAGE_IMAGE;

//$criticalFailAction = WebPConvertAndServe::$SERVE_ERROR_MESSAGE_TEXT;
$criticalFailAction = WebPConvertAndServe::$SERVE_404;
//$criticalFailAction = WebPConvertAndServe::$SERVE_ERROR_MESSAGE_IMAGE;

WebPConvertAndServe::convertAndServeImage($source, $destination, $options, $failAction, $criticalFailAction);
```


# Installing

Run `composer install`
