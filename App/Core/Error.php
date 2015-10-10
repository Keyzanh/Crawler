<?php
/**
 * The error handler class
 *
 * @class Error
 */
namespace App\Core;

use App\Core\Kernel;

class Error {

    /**
     * Register the error function
     */
    public static function Register() {
    
        set_error_handler(array(__CLASS__, 'Error'));
    
    }
    
    /**
     * Deal with the PHP's execution errors
     *
     * @param int
     * @param string
     * @param string
     * @param int
     */
    public static function Error($severity, $message, $file, $line) {
    
        Kernel::LogError("$file at line $line: $message");
        $backtrace = print_r(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), true);
        Kernel::LogError($backtrace);
        die();
    
    }

}

?>
