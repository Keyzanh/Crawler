<?php

/**
 * This class is a log handler. It allow to log to the standard
 * output ans standard error descriptors, and if requested to any file.
 *
 * @class Log
 */

namespace App\Core;

class Log {

    /**
     * The file's descriptors stored by channels
     *
     * @var array
     */
    protected $channels = array(
        'info' => array(STDOUT),
        'warning' => array(STDERR),
        'error' => array(STDERR)
    );
    
    /**
     * Log content to the info channel
     *
     * @var mixed
     */
    public function info($content) {
    
        return $this -> _log('info', $content);
    
    }
    
    /**
     * Add a file to log into for the info channel
     *
     * @param string
     * @return bool
     */
    public function addInfoOutput($file) {
    
        return $this -> _addOutput('info', $file);
    
    }
    
    /**
     * Log content to the warning channel
     *
     * @var mixed
     */
    public function warning($content) {
    
        return $this -> _log('warning', $content);
    
    }
    
    /**
     * Add a file to log into for the warning channel
     *
     * @param string
     * @return bool
     */
    public function addWarningOutput($file) {
    
        return $this -> _addOutput('warning', $file);
    
    }
    
    /**
     * Log content to the error channel
     *
     * @var mixed
     */
    public function error($content) {
    
        return $this -> _log('error', $content);
    
    }
    
    /**
     * Add a file to log into for the error channel
     *
     * @param string
     * @return bool
     */
    public function addErrorOutput($file) {
    
        return $this -> _addOutput('error', $file);
    
    }
    
    /**
     * Write the content to each file's descriptors of a given
     * channel. If content is an array, it'll be tranformed
     * into a string.
     *
     * @param string
     * @param mixed
     */
    protected function _log($channel, $content) {
    
        if(gettype($content) == 'array')
            $content = print_r($content, true);
        if(substr($content, -1) != "\n")
            $content .= "\n";
        foreach($this -> channels[$channel] as $fd)
            fwrite($fd, $content);
        return true;
    
    }
    
    /**
     * Add a file descriptor to the given channel. This allow
     * to add a file in which logs will be stored.
     *
     * @param string
     * @param string
     * @return bool
     */
    protected function _addOutput($channel, $file) {
    
        if(false === ($fd = fopen($file, 'w')))
            return false;
        $this -> channels[$channel][] = $fd;
        return true;
    
    }
    
    /**
     * Destructor
     */
    public function __destruct() {
    
        foreach($this -> channels['info'] as $fd) {
            if($fd == STDOUT || $fd == STDERR)
                continue;
            fclose($fd);
        }
        
        foreach($this -> channels['warning'] as $fd) {
            if($fd == STDOUT || $fd == STDERR)
                continue;
            fclose($fd);
        }
        
        foreach($this -> channels['error'] as $fd) {
            if($fd == STDOUT || $fd == STDERR)
                continue;
            fclose($fd);
        }
    
    }

}

?>
