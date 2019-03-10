<?php

namespace App\Fs;

class File {

  public static function locateOne($mask)
  {
    $list = self::locate($mask);
    return $list[0];
  }

  public static function locate($mask)
  {
    return glob($mask);
  }

}
