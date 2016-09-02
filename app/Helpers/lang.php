<?php
if ( !function_exists('parse_multilang') ) {
  function parse_multilang($lang, $code = '') {
      if (empty($code)) {
        $code = App::getLocale();
      }

      $code = strtoupper($code);
      $lang = str_replace('<' . strtolower($code) . '>', '<' . $code . '>', $lang);
      $lang = str_replace('</' . strtolower($code) . '>', '</' . $code . '>', $lang);
      
      preg_match('/<' . $code . '>([^<>]+)<\/' . $code .'>/', $lang, $result );
      if (isset($result[1])){
          return $result[1];
      } else {
          $lang = str_replace('<en>', '<EN>', $lang);
          $lang = str_replace('</en>', '</EN>', $lang);
          preg_match('/<EN>([^<>]+)<\/EN>/', $lang, $en_result);
          
          if (isset($en_result[1])) {
              return $en_result[1];
          } else {
              return $lang;    
          }   
      }
  }

}