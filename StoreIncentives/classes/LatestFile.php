<?php
/**
 * Created by michaelwatts
 * Date: 02/05/2014
 * Time: 12:39
 */

namespace StoreIncentives;


class LatestFile {

    protected $latest_file_date;
    protected $latest_file_name;
    protected $data_file_path;

    function __construct(Config $config)
    {
        $this->data_file_path = $config->getDataDir();
        $this->latestFile();
    }

    public function latestFile()
    {

        $d = dir($this->data_file_path);

        while ( false !== ( $entry = $d->read() ) ) {

            $file_path = "{$this->data_file_path}/{$entry}";

            // other checks here
            if (
              is_file($file_path)
                && filectime($file_path) > $this->latest_file_date
                && pathinfo($file_path, PATHINFO_EXTENSION) === "csv"
            ) {
                $this->latest_file_date = filectime($file_path);
                $this->latest_file_name = $entry;
            }
        }

        new Logger("FILE_PROCD", $this->data_file_path . $this->latest_file_name);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLatestFileName()
    {
        return $this->latest_file_name;
    }

    /**
     * @return mixed
     */
    public function getLatestFileDate()
    {
        return $this->latest_file_date;
    }

    function __toString()
    {
        return $this->latest_file_name;
    }


}