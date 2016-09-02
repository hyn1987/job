<?php
/**
* @author paulz
* @created Mar 8, 2016
*/

if ( !defined('DS') ) {
    define('DS', DIRECTORY_SEPARATOR);
}

// freelancer_amount = buyer_amount * 0.9
// wawjob_fee = buyer_amount - freelancer_amount
if ( !defined('FEE_RATE') ) {
    define('FEE_RATE', 0.9);
}

if ( !defined('SUPERADMIN_ID') ) {
    define('SUPERADMIN_ID', 1);
}

// Deduct $1 when freelancers withdraw
if ( !defined('WITHDRAW_FEE') ) {
    define('WITHDRAW_FEE', 1);
}

// Min withdraw amount
if ( !defined('MIN_WITHDRAW_AMOUNT') ) {
    define('MIN_WITHDRAW_AMOUNT', 10);
}

// Max withdraw amount
if ( !defined('MAX_WITHDRAW_AMOUNT') ) {
    define('MAX_WITHDRAW_AMOUNT', 9999);
}

if ( !function_exists('pr') ) {
    function pr($obj) {
        echo "<pre>";
        print_r($obj);
        echo "</pre>";
    }
}


if ( !function_exists('get_wawjob_database_setting')) {
    function get_wawjob_database_setting()
    {
        $path = dirname(dirname(dirname(__FILE__))).DS."config".DS."database.ini";

        $ini = parse_ini_file($path, true);
        $active = $ini['env']['active'];
        if ( !isset($ini[$active]) ) {
            exit;
        }

        return $ini[$active];
    }
}

if ( !function_exists('isWindows') ) {
    function isWindows()
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }
}

if ( !function_exists('getRoot') ) {
    function getRoot()
    {
        $root = dirname(dirname(dirname(__FILE__)));
        return $root;
    }
}

if ( !function_exists('getMimeType') ) {
    function getMimeType($filename)
    {
        preg_match("|\.([a-z0-9]{2,4})$|i", $filename, $fileSuffix);

        switch(strtolower($fileSuffix[1]))
        {
        case "js" :
            return "application/x-javascript";

        case "json" :
            return "application/json";

        case "jpg" :
        case "jpeg" :
        case "jpe" :
            return "image/jpg";

        case "png" :
        case "gif" :
        case "bmp" :
        case "tiff" :
            return "image/".strtolower($fileSuffix[1]);

        case "css" :
            return "text/css";

        case "xml" :
            return "application/xml";

        case "doc" :
        case "docx" :
            return "application/msword";

        case "xls" :
        case "xlsx" :
        case "xlt" :
        case "xlm" :
        case "xld" :
        case "xla" :
        case "xlc" :
        case "xlw" :
        case "xll" :
            return "application/vnd.ms-excel";

        case "ppt" :
        case "pps" :
            return "application/vnd.ms-powerpoint";

        case "rtf" :
            return "application/rtf";

        case "pdf" :
            return "application/pdf";

        case "html" :
        case "htm" :
        case "php" :
            return "text/html";

        case "txt" :
            return "text/plain";

        case "mpeg" :
        case "mpg" :
        case "mpe" :
            return "video/mpeg";

        case "mp3" :
            return "audio/mpeg3";

        case "wav" :
            return "audio/wav";

        case "aiff" :
        case "aif" :
            return "audio/aiff";

        case "avi" :
            return "video/msvideo";

        case "wmv" :
            return "video/x-ms-wmv";

        case "mov" :
            return "video/quicktime";

        case "zip" :
            return "application/zip";

        case "tar" :
            return "application/x-tar";

        case "swf" :
            return "application/x-shockwave-flash";

        default :

        }

        return "application/octet-stream";
    }
}


if ( !function_exists('getUploadPrefix') ) {
    /**
    * Generate two-level subdirectory where each level has 2000 directories to hold plengty of
    * files with quick access speed.
    *
    * 20001 => 0/10
    */
    function getUploadPrefix($idv)
    {
        $deep1 = floor($idv / (2000 * 2000));
        $deep2 = floor(($idv - ($deep1 * (2000 * 2000))) / 2000);

        return $deep1 . '/' . $deep2;
    }
}


if ( !function_exists('getUploadDir')) {
  /**
  * Get full path to the upload directory for given type
  *
  */
  function getUploadDir($id, $type = 'user')
  {
    $prefix = getUploadPrefix($id);
    $dir = getRoot(). "/uploads";

    if ($type == 'user' || $type == 'ticket') {       
        $dir .= "/$type/$prefix/$id/";
    } else {
        $dir .= "/user/$prefix/$id/$type/";
    }
    
    return $dir;
  }
}

if ( !function_exists('getTicketUploadDir')) {
  /**
  * Get full path to the upload directory for Ticket
  *
  * Assume Ticket ID = 20001
  * Ticket path:           D:\projects\www.wawjob.com\uploads\ticket\0\2000\20001
  */
  function getTicketUploadDir($t_id)
  {
    return getUploadDir($t_id, 'ticket');
  }
}

if ( !function_exists('getTicketCommentUploadDir')) {
  /**
  * Get full path to the upload directory for Ticket Comment
  */
  function getTicketCommentUploadDir($t_id, $tc_id)
  {
    $root = getRoot();
    $prefix = getUploadPrefix($t_id);

    $dir = $root . "/uploads/ticket/" . $prefix . "/" . $t_id . "/" . $tc_id . "/";

    return $dir;
  }
}

/* Mar 16, 2016 - paulz */
if ( !function_exists('getScreenshotPath')) {
  /**
  * Get full path to the upload directory for Work diary screenshot
  *
  * @param integer $cid: Contract ID
  * @param string $datetime: YYYYMMDDHHmm
  * @param string $type: full | thumbnail | thumbnail_path
  *       `thumbnail_path` returns thumbnail path
  *       `thumbnail` returns full path when thumbnail is not found
  *       `array` returns path, filename and thumbnamil filename
  * @return mixed
  */
  function getScreenshotPath($cid, $datetime, $type = 'full')
  {
    $root = getRoot();
    $prefix = getUploadPrefix($cid);

    $date = substr($datetime, 0, 8);
    $hm = substr($datetime, 8, 4);

    $slug = "$root/uploads/ss/$prefix/$cid/$date/";
    $filename = "$hm.jpg";
    $thumb_filename = "${hm}_s.jpg";
    $path_full = $slug . $filename;
    $path_thumb = $slug . $thumb_filename;

    if ($type == 'thumbnail_path') {
        $path = $path_thumb;
    } else if ($type == 'thumbnail') {
        if (file_exists($path_thumb)) {
            $path = $path_thumb;
        } else {
            $path = $path_full;
        }
    } else if ($type == 'array') {
        $path = [
            'path' => $slug,
            'filename' => $filename,
            'thumb_filename' => $thumb_filename,
        ];
    } else {
        $path = $path_full;
    }

    return $path;
  }
}

/**
 * Create directory.
 *
 * @param  string $path The path string to create directory.
 * @return boolean
 */
if ( !function_exists("createDir") ) {
    function createDir($path)
    {
        $old_umask = umask(0);
        if ( !is_dir($path) ) {
            if ( !mkdir($path, 0777, true) ) {
                return false;
            }
        }
        umask($old_umask);

        return true;
    }
}


/**
* Recursively remove a directory when it is not empty
*
* @author paulz
* @created Mar 9, 2016
*/
if ( !function_exists("rrmdir") ) {
  function rrmdir($dir) {
    if (!is_dir($dir)) {
      return false;
    }

    $objects = scandir($dir);
    foreach ($objects as $object) {
      if ($object != "." && $object != "..") {
        if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
      }
    }

    reset($objects);
    rmdir($dir);
  }
}

if ( !function_exists("removeDir") ) {
  function removeDir($dir) {
    //if (isWindows()) {
      return rrmdir($dir);
    //} else {
      // # remove dir by command "rm -rf [DIR]"
    //}
  }
}

/**
 * Get FontAwesome Icon Class from filename.
 *
 * @param  string $filename
 * @return string
 */
if ( !function_exists("getFileIconClass") ) {
    function getFileIconClass($filename)
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $ext = strtolower($ext);

        switch ($ext) {
        // Code
        case "c": case "cpp": case "rb":
            $c = 'fa-file-code-o';
            break;

        // Word
        case "doc": case "docx":
            $c = 'fa-file-word-o';
            break;

        // Excel
        case "xls": case "xlsx":
            $c = 'fa-file-excel-o';
            break;

        // PowerPoint
        case "ppt": case "pptx":
            $c = 'fa-file-powerpoint-o';
            break;

        // PDF
        case "pdf":
            $c = 'fa-file-pdf-o';
            break;

        // Image
        case "jpg": case "png": case "bmp":
        case "jpeg": case "gif": case "psd":
            $c = 'fa-file-image-o';
            break;

        // Audio
        case "mp3": case "wma": case "wav":
            $c = 'fa-file-audio-o';
            break;

        // Video
        case "mp4": case "mpg": case "avi":
        case "vob":
            //$c = 'fa-file-video-o';
            $c = 'fa-video-camera';
            break;

        // Text
        case "txt": case "log":
            $c = 'fa-file-text-o';
            break;

        // Zip
        case "zip": case "7z": case "rar":
            $c = 'fa-file-zip-o';
            break;

        default:
            $c = 'fa-file-archive-o';
        }

        return $c;
    }
}


//////////////////////////////////////////////////////////////


/* Mar 16, 2016 - Ri Chol Min */
if ( !function_exists('formatCurrency') )
{
  function formatCurrency($rate)
  {
    return number_format($rate, 2, '.', ',');
  }
}

/* Mar 7, 2016 - Ri Chol Min */
if ( !function_exists('getEarningRate') )
{
  function getEarningRate($rate)
  {
    return intval($rate * FEE_RATE * 100) / 100;
  }
}

/* Apr 22, 2016 - Ri Chol Min */
if ( !function_exists('getBuyerRate') )
{
  function getBuyerRate($rate)
  {
    return intval($rate / FEE_RATE * 100) / 100;
  }
}

/* Apr 08, 2016 - Nada */
if ( !function_exists('priceRaw') )
{
  function priceRaw($amount)
  {
    $amount = str_replace(",", "", $amount);
    $amount = floatval($amount);
    $amount = round($amount, 2);

    return $amount;
  }
}

/* Mar 2, 2016 - paulz */
if ( !function_exists("siteProtocol") ) {
    function siteProtocol()
    {
        if ( (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 || strtolower(substr($_SERVER['SERVER_PROTOCOL'], 0, 5)) == 'https' ) {
            $is_https = true;
        } else {
            $is_https = false;
        }

        //for CURL from mobile/ curl calls
        if (isset($_REQUEST['is_secure']))
        {
            $is_https = true;
        }
        return $is_https ? "https" : "http";
    }
}

/* Mar 2, 2016 - paulz */
if ( !function_exists("get_site_url") ) {
    function siteUrl($protocol = '')
    {
        if (!$protocol) {
            $protocol = siteProtocol();
        }

        return $protocol."://".$_SERVER['HTTP_HOST'];
    }
}


/**
* @author paulz
* @created Apr 2, 2016
* @param object | int $user: User object or user_id
* @param boolean $is_temp: Whether this is temp file
* @param int $size: Avatar size [optional]
* @param boolean $Is_seek_default: Return default image when not found [optional]
*
* @return Full filepath to avatar of given size
*/
if ( !function_exists('avatarPath') ) {
    function avatarPath($user, $size = '', $is_temp = false, $is_seek_default = false)
    {
        if ( !$user ) {
            return '';
        }

        if (is_object($user)) {
            $user_id = $user->id;
        } else {
            $user_id = $user;
        }

        $size = intval($size);
        if ($is_temp) {
            $dir = getUploadDir($user_id, "tmp");
            createDir($dir);
            $path = $dir . "avatar.png";
        } else {
            $dir = getUploadDir($user_id, "avatar");
            createDir($dir);
            $path = $dir . ($size ? "${user_id}_$size.png" : "${user_id}.png");
        }

        if (file_exists($path)) {
            // ok, I found the exact image what I am looking for
            return $path;
        }
        
        if ( !$is_temp && $is_seek_default) {
            if ($size) {
                // default size avatar of this user
                $path = $dir . "$user_id.png";
                if (file_exists($path) ) {
                    return $path;
                }
            }
            
            // global default avatar
            $path = getRoot() . "/public/assets/images/default/avatar.png";
        }

        return $path;
    }
}

/**
* Returns URL or avatar (or temp avatar) image for given user_id and size
*
* @author paulz
* @created Mar 11, 2016
*
* @param  $is_url: TRUE = URL, FALSE = Full file path
*/
if ( !function_exists('avatarUrl') ) {
  function avatarUrl($user, $size = '', $is_temp = false)
  {
    if ( !$user ){
        return '';
    }
    $size = intval($size);

    if (is_object($user)) {
        $user_id = $user->id;
        $username = $user->username;
    } else {
        $user_id = $user["id"];
        $username = $user["username"];
    }

    $path = avatarPath($user_id, $size, $is_temp, true);
    if (!$path) {
        return '';
    }

    $url = siteUrl();
    if ($is_temp) {
        $url .= "/avatar_temp/$username";
    } else {
        $url .= "/avatar/$username";
    }

    if ($size) {
        $url .= "/$size";
    }

    $url .= "?fm=".filemtime($path);

    return $url;
  }
}


/**
* @author paulz
* @created Apr 18, 2016
* @param int $user_id: User ID
* @param int $pt_id: Portfolio ID (temp image when this = 0)
* @param int $size: Avatar size [optional]
* @param boolean $Is_seek_default: Return default image when not found [optional]
*
* @return Full filepath to portfolio screenshot of given size
*/
if ( !function_exists('portfolioPath') ) {
    function portfolioPath($user_id, $pt_id, $size = '', $is_seek_default = false)
    {
        if ( !$user_id ) {
            return '';
        }

        $size = intval($size);
        if ($pt_id == 0) {
            $dir = getUploadDir($user_id, "tmp");
            createDir($dir);
            $path = $dir . "portfolio.jpg";
        } else {
            $dir = getUploadDir($user_id, "portfolio");
            $path = $dir . ($size ? "${pt_id}_$size.jpg" : "${pt_id}.jpg");
        }

        if ( file_exists($path) ) {
            // great! this is just want I am looking for
            return $path;
        }

        if ($pt_id > 0 && $is_seek_default) {
            // If I was looking for custom size portfolio image, then try default size portfolio image
            if ($size) {
                $path = $dir . "$pt_id.jpg";
                if ( file_exists($path) ) {
                    return $path;
                }
            }
            
            // oh, I could not find image for this portfolio, just return no_background (transparent) image
            $path = getRoot() . "/public/assets/images/default/no_bg.png";
        }

        return $path;
    }
}

/**
* Returns image URL for portfolio or portfolio_temp for given user_id, pt_id and size
*
* @author paulz
* @created Apr 18, 2016
*
* e.g:
*    $user = new \stdClass;
*    $user->id = 2;
*    $user->username = 'jin';
*
*    echo portfolioUrl($user, 1);
*/
if ( !function_exists('portfolioUrl') ) {
  function portfolioUrl($user, $pt_id, $size = '')
  {
    if ( !$user ) {
        return '';
    }

    $user_id = $user->id;
    $username = $user->username;
    
    $pt_id = intval($pt_id);
    $path = portfolioPath($user_id, $pt_id, $size);
    if (!$path) {
        return '';
    }

    $url = siteUrl() . "/portfolio/$username/$pt_id";
    if ($pt_id > 0 && $size) {
        $url .= "/$size";
    }
    if (file_exists($path)) {
        $url .= "?fm=".filemtime($path);    
    }else {
        $url = "";
    }
    

    return $url;
  }
}


/**
* Similar to avatarUrl(), returns custom resource URL
*
* @author  paulz
* @created Mar 11, 2016
* @updated Mar 16, 2016 - added screenshot
*/
if ( !function_exists('resouceUrl') ) {
    function resourceUrl()
    {
        $args = func_get_args();
        $type = $args[0];
        $url = '';

        switch ($type) {
        case "ticket":
        case "tcomment":
            $id = $args[1];
            $filename = $args[2];
            $url = "/res/$type/$id/$filename";
            break;

        // Screenshot
        case "ss":
            $cid = $args[1]; // Contract ID
            $datetime = $args[2]; // YYYYMMDDHHmm: e.g: 201603160734
            $url = "/res/ss/$cid/$datetime";
            $type = $args[3];
            if ($type == "thumbnail") {
                $url .= '_s';
            }
            break;

        default:
        }

        return $url;
    }
}