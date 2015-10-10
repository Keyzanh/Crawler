<?php
/**
 * Set of functions to manipulate URLs
 *
 * @class Url
 */
namespace App\Lib;

class Url {

    /**
     * Get the complete valid URLs from the current page URL
     * and the new page URL
     *
     * @param string
     * @param string
     * @return string
     */
    public static function GetHttpValid($current, $new) {
    
        $url = '';
        if(preg_match('#^https?:#', $new))
            $url = $new;
        else if(substr($new, 0, 2) == '//')
            $url = substr($current, 0, strpos($current, '/')).$new;
        else if(substr($new, 0, 1) == '/')
            $url = preg_replace('#^((https?://[^/]+).*)#', '${2}'.$new, $current);
        else if(substr($new, 0, 1) == '?')
            $url = preg_replace('#^((https?://[^\?]+).*)#', '${2}'.$new, $current);
        else if(substr($current, -1) == '/')
            $url = $current.$url;
        else
            $url = $current.'/'.$new;
        return $url;
    
    }

}

?>
