<?php
  class Session
  {
      public static function set($key,$value)
      {
          $_SESSION[$key] = $value;
      }

      public static function fetch($key,$key2=NULL)
      {
          if($key2) return $_SESSION[$key][$key2];
          return $_SESSION[$key];
      }

      public static function uset($key,$key2=NULL)
      {
          if($key2) unset($_SESSION[$key][$key2]);
          unset($_SESSION[$key]);
      }
  }
?>
