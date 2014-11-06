<?php
/**
 * Created by michaelwatts
 * Date: 02/05/2014
 * Time: 11:13
 */

namespace StoreIncentives;


class Logger {

    protected $message;
    protected $error_code;
    protected $directories;
    private $config;

    function __construct($error_code = "PROG_MESSG", $message = "No message set.")
    {
        $this->config = new Config();

        $this->error_code = $error_code;
        $this->message = $message;

        $this->writeToLogFile();
    }

    private function restrictErrorCodeLength()
    {
        $this->error_code = substr($this->error_code, 0, 10);
        return $this;
    }

    private function formatErrorCode()
    {
        $this->error_code = str_replace(" ", "_", strtoupper($this->error_code));
        return $this;
    }

    private function getFormattedCode()
    {
        $this->restrictErrorCodeLength();
        $this->formatErrorCode();
        return $this->error_code;

    }

    private function formatMessage($message)
    {
        return rtrim($message);
    }

    public function writeToLogFile()
    {
        $log_line = $this->getFormattedCode() . " - - " . "[" . date(DATE_ATOM) . "] " . $this->formatMessage($this->message) . PHP_EOL;
        if ( $this->config->getEchoLog() === true ) {
            echo $log_line;
        }
        file_put_contents($this->config->getRouteDir() . "logs/log.txt", $log_line, FILE_APPEND | LOCK_EX);
    }

    /**
     * @return \StoreIncentives\Directories
     */
    public function getDirectories()
    {
        return $this->directories;
    }

} 