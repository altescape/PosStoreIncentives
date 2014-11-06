<?php
/**
 * Created by michaelwatts
 * Date: 12/05/2014
 * Time: 14:44
 */

namespace StoreIncentives;


class MongoDB {

    protected $bogus_emails;
    protected $valid_emails;
    protected $existing_emails;
    protected $file_name;
    private $db;

    function __construct($bogus_emails, $existing_emails, $valid_emails, $file_name)
    {
        $this->db = (new \MongoClient())->storeincentive_data;

        $this->file_name = $file_name;
        $this->bogus_emails = $bogus_emails;
        $this->existing_emails = $existing_emails;
        $this->valid_emails = $valid_emails;
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
                  "tran_data" => $data[3],
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

    public function insertBogusEmails()
    {
        return $this->buildData($this->bogus_emails);
    }

    public function insertValidEmails()
    {
        return $this->buildData($this->valid_emails);
    }

    public function insertExistingEmails()
    {
        return $this->buildData($this->existing_emails);
    }

    public function getFileName()
    {
        return $this->file_name;
    }

    public function collectData()
    {
        $collection_array = [
            "meta" => [
                "date" => date('d-m-Y H:i:s'),
                "timestamp" => time(),
                "file" => $this->getFileName(),
            ],
            "valid_emails" => $this->insertValidEmails(),
            "bogus_emails" => $this->insertBogusEmails(),
            "existing_emails" => $this->insertExistingEmails()
        ];

        $collection = $this->db->store_datas;
        $collection->insert($collection_array);
    }

}