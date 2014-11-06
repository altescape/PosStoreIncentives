<?php
/**
 * Created by michaelwatts
 * Date: 02/05/2014
 * Time: 11:08
 */

namespace StoreIncentives;


class Directories {
    
    private $route_dir;

    function __construct(Config $config)
    {
        $this->route_dir = $config->getRouteDir();
        $this->checkExist();
    }

    public function checkExist()
    {
        // check data_sets exists
        if (file_exists($this->route_dir) === false) {
            mkdir($this->route_dir, 0777);
        }

        // Check data_sets/bad exists
        if (file_exists($this->route_dir . "bad/") === false) {
            mkdir($this->route_dir . "bad/", 0777);
        }

        // Check data_sets/cron_list_data exists
        if (file_exists($this->route_dir . "cron_list_data/") === false) {
            mkdir($this->route_dir . "cron_list_data/", 0777);
        }

        // Check data_sets/formats exists
        if (file_exists($this->route_dir . "formats/") === false) {
            mkdir($this->route_dir . "formats/", 0777);
        }

        // Check data_sets/logs exists
        if (file_exists($this->route_dir . "logs/") === false) {
            mkdir($this->route_dir . "logs/", 0777);
        }

        // Check data_sets/logs/logs.txt exists
        if (file_exists($this->route_dir . "logs/log.txt") === false) {
            touch($this->route_dir . "logs/log.txt");
        }

        $dirs_exist = true;

        if ($dirs_exist === true) {
            return true;
        } else {
            new Logger("DIRE_ERROR", "Directory tests not passed");
            die("tests not passed");
        }
    }

    /**
     * @return mixed
     */
    function __toString()
    {
        return $this->route_dir;
    }
} 