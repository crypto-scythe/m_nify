# m_nify - Simple JavaScript / CSS Minifier

Simple PHP library for minifying and concatenating JavaScript and CSS files for use in my projects.

## Installing

File **composer.json**

    {
      "require": {
        "crypto_scythe/m_nify": "*"
      }
    }

Then on command line:

    composer install

## Usage

    <?php

    require( 'vendor/autoload.php' );

    new \crypto_scythe\m_nify();

    ?>

To minify the files just add the GET parameter **files** to the file which instantiates the m_nify object like this:
 
    http://example.com/m_nify.php?files=/path/to/first_file.css,/path/to/second_file.css

## Options

Per default m_nify uses the DOCUMENT_ROOT for fetching the file. If you have your files in a different location you need to define the path like this before instantiating the m_nify object.

    define( 'M_NIFY_PATH', $_SERVER['DOCUMENT_ROOT'] . '/path/to/files' );

By using this you can shorten the paths or restrict it to a specific folder.

## Additional Information

m_nify automatically removes ".." from given file paths and automatically adds "/" in front of them so make sure you don't use relative paths when specifying files. However the ".." is allowed in the M_NIFY_PATH constant.

## License

m_nify is licensed under the MIT License. See the LICENSE file for details.

## Used libraries

JShrink from Robert Hafner - [Github](https://github.com/tedious/JShrink)  
CssMin from Joe Scylla - [Github](https://github.com/natxet/CssMin) / [Google Code](http://code.google.com/p/cssmin/)