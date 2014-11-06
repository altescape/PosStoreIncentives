<?php
/**
 * Created by michaelwatts
 * Date: 02/05/2014
 * Time: 09:24
 */

namespace StoreIncentives;
use PDO;

class Conn
{

    public $db;
    public $user;
    public $pswd;

    function __construct($db, $user, $pswd)
    {
        $this->db = $db;
        $this->pswd = $pswd;
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @return mixed
     */
    public function getPswd()
    {
        return $this->pswd;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

}