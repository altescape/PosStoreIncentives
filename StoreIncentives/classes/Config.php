<?php
/**
 * Created by michaelwatts
 * Date: 02/05/2014
 * Time: 11:51
 */

namespace StoreIncentives;


class Config {

    private $debug = false;
    private $home = false;
    private $echo_log = true;
    protected $route_dir;
    protected $data_dir;
    protected $db_conn;
    protected $db_user;
    protected $db_pswd;

    function __construct()
    {
        if ($this->debug === true) {
            if ($this->home === true) { //at home
                $this->route_dir = "/Library/WebServer/Documents/StoreIncentives/data/";
                $this->data_dir = "/Library/WebServer/Documents/StoreIncentives/store_data/";
                $this->db_conn = "mysql:host=localhost;dbname=*****;charset=utf8";
                $this->db_user = "*****";
                $this->db_pswd  = "*****";
            } else { // at the office
                $this->route_dir = "/Users/michaelwatts/Documents/_amazing_php_scripts/StoreIncentives 2/StoreIncentives/data/";
                $this->data_dir = "/Users/michaelwatts/Documents/_amazing_php_scripts/StoreIncentives 2/StoreIncentives/store_data/";
                $this->db_conn = "mysql:host=localhost;dbname=*****;charset=utf8";
                $this->db_user = "*****";
                $this->db_pswd  = "*****";
            }
        } else { // live, on the server
            $this->route_dir = "/home/saudifas/public_html/live/csv/data_sets/";
            $this->data_dir = "/home/saudifas/public_html/storedata/";
            $this->db_conn = "mysql:host=localhost;dbname=*****;charset=utf8";
            $this->db_user = "*****";
            $this->db_pswd  = "*****";
        }
    }


    /**
     * @param mixed $db_conn
     */
    public function setDbConn($db_conn)
    {
        $this->db_conn = $db_conn;
    }

    /**
     * @return mixed
     */
    public function getDbConn()
    {
        return $this->db_conn;
    }

    /**
     * @param mixed $db_pswd
     */
    public function setDbPswd($db_pswd)
    {
        $this->db_pswd = $db_pswd;
    }

    /**
     * @return mixed
     */
    public function getDbPswd()
    {
        return $this->db_pswd;
    }

    /**
     * @param mixed $db_user
     */
    public function setDbUser($db_user)
    {
        $this->db_user = $db_user;
    }

    /**
     * @return mixed
     */
    public function getDbUser()
    {
        return $this->db_user;
    }

    /**
     * @param mixed $data_dir
     */
    public function setDataDir($data_dir)
    {
        $this->data_dir = $data_dir;
    }

    /**
     * @return mixed
     */
    public function getDataDir()
    {
        return $this->data_dir;
    }

    /**
     * @param mixed $route_dir
     */
    public function setRouteDir($route_dir)
    {
        $this->route_dir = $route_dir;
    }

    /**
     * @return mixed
     */
    public function getRouteDir()
    {
        return $this->route_dir;
    }

    /**
     * @return boolean
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * @return boolean
     */
    public function getEchoLog()
    {
        return $this->echo_log;
    }

} 