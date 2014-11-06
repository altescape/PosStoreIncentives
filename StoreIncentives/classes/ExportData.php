<?php
/**
 * Created by michaelwatts
 * Date: 02/05/2014
 * Time: 17:47
 */

namespace StoreIncentives;


class ExportData {

    public $data;
    public $bogus_data;

    function __construct(ValidateData $validateData, FormatData $formatData)
    {
        $this->data = $formatData->getData();
        $this->bogus_data = $validateData->getBogusEmails();
    }

    /**
     * @return string
     */
    public function campaignMonitor()
    {
        $members = $this->data;

        $records = "FIRST NAME, EMAIL, ENCRYPTED_EMAIL, USERNAME, GENDER, MALL" . PHP_EOL;

        foreach ($members as $member) {

            $text_line = $member["first_name"] . ',';
            $text_line .= $member["email"] . ',';
            $text_line .= $member["encrypted_email"] . ',';
            $text_line .= $member["first_name"] . "14" . ',';
            $text_line .= $member["gender"] . ',';
            $text_line .= $member["mall"];

            $records .= $text_line . PHP_EOL;

        }

        return trim($records);

    }

    public function campaignMonitorForImport()
    {
        $members = $this->data;
        $subscribers = [];

        foreach ($members as $member) {
            $subscribers[] = [
              "email" => $member["email"],
              "name" => $member["first_name"],
              "encrypted_email" => $member["encrypted_email"],
              "gender" => $member["gender"],
              "store" => $member["store"],
              "brand" => $member["brand"],
              "mall" => $member["mall"],
              "staff_id" => $member["staff_id"]
            ];
        }

        return $subscribers;
    }

    public function json()
    {
        return json_encode($this->data);
    }

    public function formatForXml()
    {
        $members = $this->data;

        $a = [];
        $b = [];

        foreach ($members as $member) {

            // $b["these match expressionengine member fields"]
            $b["screen_name"] = $member["first_name"] . "_" . rand(0, 999);
            $b["email"] = $member["email"];
            $b["username"] = $member["email"];
            $b["first_name"] = $member["first_name"];
            $b["last_name"] = $member["last_name"];
            $b["mall"] = $member["mall"];
            $b["gender"] = $member["gender"];
            $b["telephone"] = $member["mobile"];
            $b["password"] = $member["password"];
            $b["group_id"] = 8;
            $b["location"] = "";

            $a[] = $b;
        }

        return $this->data;
    }

    public function generateXml($array, $node_name) {

        $xml = "";

        if (is_array($array) || is_object($array)) {
            foreach ($array as $key=>$value) {
                if (is_numeric($key)) {
                    $key = $node_name;
                }

                $xml .= '<' . $key . ($key == "password" ? ' type="text"' : '') . '>' . "\n" . $this->generateXml($value, $node_name) . '</' . $key . '>' . "\n";
            }
        } else {
            $xml = htmlspecialchars($array, ENT_QUOTES) . "\n";
        }

        return $xml;
    }

    public function generateValidXml($array, $node_block='nodes', $node_name='node') {
        $xml = '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";

        $xml .= '<' . $node_block . '>' . "\n";
        $xml .= $this->generateXml($array, $node_name);
        $xml .= '</' . $node_block . '>' . "\n";

        return $xml;
    }

    public function xml()
    {
        return $this->generateValidXml($this->formatForXml(), $node_block='members', $node_name='member');
    }

    public function bad()
    {
        return json_encode($this->bogus_data);
    }
} 