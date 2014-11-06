<?php
/**
 * Created by michaelwatts
 * Date: 02/05/2014
 * Time: 15:40
 */

namespace StoreIncentives;


class FormatData {

    protected $data;
    public $bogus_data;

    function __construct(ValidateData $validateData)
    {
        $this->data = $validateData->getData();

        $this->buildAssociativeArray();
    }

    /**
     * @return array
     */
    public function buildAssociativeArray()
    {
        $members = $this->data;

        $a = [];
        $b = [];

        foreach ($members as $member) {

            // clean the data a bit
            $customer_name = ucwords(strtolower($member[5]));
            $names = explode(" ", $customer_name);
            $first_name = trim($names[0]);
            if (count($names) > 1) {
                $last_name = trim($names[1]);
            } else {
                $last_name = "";
            }

            // reassign to associated keys
            $b["store"] = $member[0];
            $b["brand"] = strtoupper($member[1]);
            $b["mall"] = ucwords(strtolower($member[2]));
            $b["tran_date"] = $member[3];
            $b["customer_name"] = ucwords(strtolower($member[5]));
            $b["first_name"] = $first_name;
            $b["last_name"] = $last_name;
            $b["gender"] = strtolower($member[6]);
            $b["mobile"] = $member[7];
            $b["email"] = strtolower($member[8]);
            $b["encrypted_email"] = urlencode(new SimpleEncrypt(strtolower($member[8])));
            $b["staff_id"] = $member[9];
            $b["password"] = strtolower($first_name . "14");

            $a[] = $b;
        }

        $this->data = $a;

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

} 