<?php
/**
 * Created by michaelwatts
 * Date: 02/05/2014
 * Time: 09:13
 */

namespace StoreIncentives;
use PDO;


class ExistingMembers {

    protected $emails;
    protected $conn;

    function __construct(Conn $conn)
    {
        $this->conn = $conn;
        $this->data();
    }

    private function data()
    {
        $db = new PDO($this->conn->getDb(), $this->conn->getUser(), $this->conn->getPswd());

        try {
            //connect as appropriate as above
            foreach($db->query('SELECT * FROM `exp_members`') as $row) {
                $this->emails[] = $row['email'];
            }
        } catch(PDOException $ex) {
            return "An Error occured!"; //user friendly message
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmails()
    {
        return $this->emails;
    }

}