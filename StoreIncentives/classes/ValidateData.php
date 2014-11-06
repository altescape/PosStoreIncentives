<?php
/**
 * Created by michaelwatts
 * Date: 02/05/2014
 * Time: 14:35
 */

namespace StoreIncentives;


class ValidateData
{

    protected $data;
    protected $existing_members;
    protected $valid_emails;
    protected $bogus_emails;
    protected $exists_emails;

    function __construct(ReadData $readData, ExistingMembers $existingMembers)
    {
        $this->data = $readData->fetch();
        $this->existing_members = $existingMembers->getEmails();

        // Check and remove header
        $this->checkHeader();
        $this->removeHeader();

        // Check for dupes in file
        $this->removeDuplicates();

        // Validate email addresses
        $this->validateEmail();

        // Remove existing members
        $this->removeExistingMembers();

        // Write invalid data to log
        $this->createBadDataLog();
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }


    /**
     * Check headers are the same
     * @return bool
     */
    public function checkHeader()
    {
        $test = array("STORE", "BRAND", "MALL", "TRAN_DATE", "TRAN_NO", "CUSTOMER_NAME", "GENDER", "MOBILE", "EMAIL", "STAFF_ID");
        $actual_header = $this->data[0];

        if ($test !== $actual_header) {
            new Logger("FORM_ERROR", "Data format is incorrect");
            die("Data format is incorrect");
        }

        return $this;
    }

    /**
     * Remove header
     * @return mixed
     */
    public function removeHeader()
    {
        return array_shift($this->data);
    }

    /**
     * Remove duplicates within uploaded file
     * @return $this
     */
    public function removeDuplicates()
    {
        $members = $this->data;

        if (isset($members)) {

            // this quickly deletes dupes by making email the key and overwriting the key with new values: clever!
            foreach ($members as $member) {
                $de_duplicated[trim($member[8])] = $member;
                $this->data = $de_duplicated;
            }

            // This restores the array keys as numerals
            $this->data = array_values($this->data);
        }

        return $this;
    }

    /**
     * Validates email addresses
     * @return $this
     */
    public function validateEmail()
    {
        $members = $this->data;

        if (isset($members)) {

            foreach ($members as $member_data) {
                // FILTER_VALIDATE_EMAIL validates the '=' sign in email addresses so adding extra check with strpos
                if (filter_var(trim($member_data[8]), FILTER_VALIDATE_EMAIL) && strpos($member_data[8], '=') === false) {
                    $this->valid_emails[] = $member_data;
                } else {
                    $this->bogus_emails[] = $member_data;
                }
            }

            $this->data = $this->valid_emails;
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function removeExistingMembers()
    {
        // Array to store existing members
        $existing_members = $this->existing_members;

        $count_existing_members = 0;

        if (isset($existing_members) && sizeof($existing_members) >= 1) {

            foreach ($this->existing_members as $existing_member) {
                foreach ($this->data as $member => $val) {
                    if (trim(strtolower($val[8])) === trim(strtolower($existing_member))) {
                        $this->exists_emails[] = $this->data[$member];
                        $count_existing_members ++;
                        unset( $this->data[$member] );
                    }
                }
            }
        }

        new Logger("EXIS_MEMBR", $count_existing_members . " existing member(s).");

        return $this;

    }

    /**
     * Write bad data to log
     * @return $this
     */
    public function createBadDataLog()
    {
        $see_file = "";
        $bogus_count = count($this->getBogusEmails());

        if ($bogus_count > 0) {
            $see_file = "See bad data file";
        }

        new Logger("INVL_EMAIL", $bogus_count . " bogus email(s). " . $see_file);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBogusEmails()
    {
        return $this->bogus_emails;
    }

    /**
     * @return mixed
     */
    public function getExistingMembers()
    {
        return $this->existing_members;
    }

    /**
     * @return mixed
     */
    public function getExistingEmails()
    {
        return $this->exists_emails;
    }

}