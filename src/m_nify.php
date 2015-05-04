<?php

namespace crypto_scythe;

/*
 * m_nify is a class for easy minifying JavaScript and CSS assets
 *
 * m_nify can be used to concatenate multiple .js or .css files
 * and minify them in a simple manner.
 *
 * @access    public
 * @author    Chris Fasel <crypto.scythe@gmail.com>
 * @copyright Copyright (c) 2015, Chris Fasel
 * @license   http://opensource.org/licenses/MIT
 *
 */

class m_nify {

  protected $files = [];
  protected $path = '';
  protected $filetype = '';
  protected $filetypes = ['css', 'js'];
  protected $headers = [
    'js' => [
      'Content-Type: application/javascript'
    ],
    'css' => [
      'Content-Type: text/css'
    ]
  ];

  public function __construct( $options = null ) {

    if( defined( 'M_NIFY_PATH' ) ) {

      $this->path = M_NIFY_PATH;

    } else {

      $this->path = $_SERVER['DOCUMENT_ROOT'];

    }

    if( !$this->scrubFiles() ) {

      echo 'Error in specified file(s)';

    } else {

      $this->run();

    }

  }

  protected function scrub( $data ) {

    $scrubs = [ '..' ];

    if( strpos( $data, '/' ) !== 0 ) {

      $data = '/' . $data;

    }

    return str_replace( $scrubs, '', $data );

  }

  protected function scrubFiles() {

    if( !isset( $_GET['files'] ) ) {

      return false;

    }

    $files = explode( ',', $_GET['files'] );

    array_walk( $files, function( &$item ) {

      $item = $this->path . $this->scrub( $item );

    } );

    $filetype = array_reduce( $files, function( $carry, $item ) {

      $extension = $this->getFileType( $item );

      if( $carry == 'init' ) {

        return $extension;

      } else {

        if( $extension == $carry ) {

          return $extension;

        }

        return false;

      }

    } , 'init' );

    if( in_array( $filetype, $this->filetypes ) ) {

      $this->files = $files;
      $this->filetype = $filetype;

      return true;

    }

    return false;

  }

  protected function getFileType( $file ) {

    if( @is_file( $file ) ) {

      return pathinfo($file, PATHINFO_EXTENSION);

    } else {

      return false;

    }

  }

  private function concatFiles() {

    $string = '';

    foreach( $this->files as $file ) {

      $string .= PHP_EOL . file_get_contents( $file );

    }

    return $string;

  }

  private function sendHeaders() {

    foreach( $this->headers[$this->filetype] as $header ) {

      header( $header );

    }

  }

  private function run() {

    if( $this->filetype == 'js' ) {

      // https://github.com/tedious/JShrink
      $this->sendHeaders();
      echo \JShrink\Minifier::minify( $this->concatFiles() );

    } elseif( $this->filetype == 'css' ) {

      //  https://github.com/natxet/CssMin
      $this->sendHeaders();
      echo \CssMin::minify( $this->concatFiles() );

    }

  }

}