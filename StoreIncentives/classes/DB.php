<?php
/**
 * Created by michaelwatts
 * Date: 12/05/2014
 * Time: 14:44
 */

namespace StoreIncentives;
use PDO;


class DB {

    protected $bogus_emails;
    protected $valid_emails;
    protected $existing_emails;
    protected $file_name;
    private $db;
    protected $conn;

    function __construct(Conn $conn, $bogus_emails, $existing_emails, $valid_emails, $file_name)
    {
        $this->conn = $conn;

        $this->db = new PDO($this->conn->getDb(), $this->conn->getUser(), $this->conn->getPswd());

        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->file_name = $file_name;
        $this->bogus_emails = $bogus_emails;
        $this->existing_emails = $existing_emails;
        $this->valid_emails = $valid_emails;
    }

    public function query()
    {
        if (!empty($this->valid_emails)) {
            // valid emails
            $this->bindParams("valid", $this->valid_emails);
        }

        if (!empty($this->valid_emails)) {
            // bogus emails
            $this->bindParams("bogus", $this->bogus_emails);
        }

        if (!empty($this->valid_emails)) {
            // existing emails
            $this->bindParams("existing", $this->existing_emails);
        }
    }

    private function bindParams($status, $datas)
    {
        if (sizeof($datas) >= 1) {
            $sql = "INSERT INTO store_incentive_data (
                        store,
                        brand,
                        mall,
                        tran_date,
                        tran_no,
                        customer_name,
                        gender,
                        mobile,
                        email,
                        staff_id,
                        import_date,
                        file_name,
                        status) VALUES (
                        :store,
                        :brand,
                        :mall,
                        :tran_date,
                        :tran_no,
                        :customer_name,
                        :gender,
                        :mobile,
                        :email,
                        :staff_id,
                        :import_date,
                        :file_name,
                        :status)";

            $stmt = $this->db->prepare($sql);

            $the_date = date('Y-m-d H:i:s');

            foreach ($datas as $data) {
                $stmt->bindParam(":store", $data[0]);
                $stmt->bindParam(":brand", $data[1]);
                $stmt->bindParam(":mall", $data[2]);
                $stmt->bindParam(":tran_date", $data[3]);
                $stmt->bindParam(":tran_no", $data[4]);
                $stmt->bindParam(":customer_name", $data[5]);
                $stmt->bindParam(":gender", $data[6]);
                $stmt->bindParam(":mobile", $data[7]);
                $stmt->bindParam(":email", $data[8]);
                $stmt->bindParam(":staff_id", $data[9]);
                $stmt->bindParam(":import_date", $the_date);
                $stmt->bindParam(":file_name", $this->file_name);
                $stmt->bindParam(":status", $status);
                $stmt->execute();
            }
        }
    }

    private function buildData($type)
    {

        $document = $type;
        $tmp_doc = [];

        if (isset($type)) {
            foreach ($document as $data) {
                $tmp_doc[] = [
                  "store" => $data[0],
                  "brand" => $data[1],
                  "mall" => $data[2],
                  "tran_date" => $data[3],
                  "tran_no" => $data[4],
                  "customer_name" => $data[5],
                  "gender" => $data[6],
                  "mobile" => $data[7],
                  "email" => $data[8],
                  "staff_id" => $data[9],
                ];
            }
        }

        return $tmp_doc;
    }

}