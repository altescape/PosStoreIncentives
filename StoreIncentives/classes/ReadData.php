<?php
/**
 * Created by michaelwatts
 * Date: 02/05/2014
 * Time: 14:20
 */

namespace StoreIncentives;


class ReadData {

    protected $directory;
    protected $file;

    function __construct(Config $config, LatestFile $latestFile)
    {
        $this->directory = $config->getDataDir();
        $this->file = $latestFile;
    }

    public function fetch()
    {
        $file_handle = fopen($this->directory . $this->file, 'r');
        $line_of_text = [];

        if ($file_handle) {
            while ($result = fgetcsv($file_handle) ) {
                if ( array(null) !== $result ) {
                    $line_of_text[] = $result;
                }
            }
            fclose($file_handle);
            return $line_of_text;
        } else {
            new Logger("FILE_ERROR", "Cannot open file", (new Config())->getRouteDir());
            die("Cannot open file");
        }
    }

    function __toString()
    {
        return $this->directory . $this->file;
    }

}