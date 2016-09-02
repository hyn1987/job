<?php
/**
* @author Brice
* @created Mar 22, 2016
*/

if ( !function_exists('get_notification') ) {
    function get_notification($content, $params) {
        if (!empty($params) && $params != null) {
            foreach ($params as $key => $val)
            {
                if (strpos($key, '@#') !== false) {
                    $content = str_replace($key, $val, $content);
                }else {
                    $content = str_replace('@#' . $key . '#', $val, $content);
                }
                
            }
        }
    	//Replace " with html character
    	$content = str_replace('"', '&quot;', $content);
        return $content;
    }
}

if ( !function_exists('parse_notification') ) {
    /**
    * @created briceyu
    * @update paulz - May 21, 2016
    */
    function parse_notification($notifications, $language)
    {
    	foreach ($notifications as $n)
	    {
            // value list language (see `user_notifications`)
            $v_lang = strtoupper($language);

            // notification formart language (see `notifications`)
            $f_lang = $v_lang;

            $formats = json_decode($n->content, true);
            if ( !isset($formats[$f_lang])){
                $f_lang = "EN";
            }

            $values = json_decode($n->notification, true);
            if ( !isset($values[$v_lang]) ) {
                $v_lang = "EN";
            }

            if (empty($values[$v_lang])) {
                $values[$v_lang] = [];
            }

            $n->notification = get_notification($formats[$f_lang], $values[$v_lang]);
    	}

        return $notifications;
    }
}