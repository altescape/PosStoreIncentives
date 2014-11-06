<?php
/**
 * Created by michaelwatts
 * Date: 08/05/2014
 * Time: 09:24
 */

namespace StoreIncentives;

require "../vendor/phpmailer/phpmailer/PHPMailerAutoload.php";

class EmailRun {

    protected $file;
    protected $file_name;
    protected $valid_emails;
    protected $bogus_emails;
    protected $members_exist;

    private $payload;
    private $batch;

    function __construct($bogus_emails, $file, $members_exist, $valid_emails)
    {
        $file_name_part = pathinfo($file);

        $this->bogus_emails = $bogus_emails;
        $this->file = $file;
        $this->file_name = $file_name_part['filename'];
        $this->members_exist = $members_exist;
        $this->valid_emails = $valid_emails;

        if (count($this->valid_emails) > 0) {
            $this->payload = file_get_contents("http://www.saudifashionmagazine.com/?ACT=78&hash=UpH8bC2nI8yXsWRX5Hg4GDmKGXoMTDOI");
        } else {
            die('No valid detail');
        }

        if (count($this->valid_emails) >= 100) {
            $this->batch = "Batch importing is in progress";
        }
    }


    public function emailTemplate()
    {
        // Build emails
        $message = '<html><body>';

        $message .= '<hr>';

        $message .= '<h3>File info</h3>';
        $message .= '<p><strong>Uploaded file processed:</strong> ' . $this->file . '</p>';

        $message .= '<hr>';

        $message .= '<h3>Overview</h3>';
        $message .= '<table>';

        $message .= '<tr>';
        $message .= '<td>';
        $message .= '<p><strong>Valid Emails</strong></p>';
        $message .= '</td>';
        $message .= '<td>';
        $message .= '<p>' . count($this->valid_emails) . '</p>';
        $message .= '</td>';
        $message .= '</tr>';

        $message .= '<tr>';
        $message .= '<td>';
        $message .= '<p><strong>Bogus Emails</strong></p>';
        $message .= '</td>';
        $message .= '<td>';
        $message .= '<p>' . count($this->bogus_emails) . '</p>';
        $message .= '</td>';
        $message .= '</tr>';

        $message .= '<tr>';
        $message .= '<td>';
        $message .= '<p><strong>Members exist</strong></p>';
        $message .= '</td>';
        $message .= '<td>';
        $message .= '<p>' . count($this->members_exist) . '</p>';
        $message .= '</td>';
        $message .= '</tr>';

        $message .= '</table>';
        $message .= '</body></html>';

        $message .= '<hr>';

        $message .= '<h3>Files attached</h3>';
        $message .= '<ul>';
        $message .= '<li><p>' . $this->file_name . '.xml</li></p>';
        $message .= '<li><p>' . $this->file_name . '.txt</li></p>';
        $message .= '</ul>';

        $message .= '<hr>';

        $message .= '<h3>Import overview</h3>';
        $message .= $this->batch;
        $message .= $this->payload;

        return $message;

    }

    public function sendEmail()
    {
        // Send email
        $mail = new \PHPMailer();
        $mail->isSendmail();
        $mail->From = 'data@saudifashionmagazine.com';
        $mail->FromName = 'Store Incentive';
        $mail->addAddress('mike@smswmedia.com');
        $mail->Subject = 'A store incentive file has been processed';
        $mail->isHTML();
        $mail->Body = $this->emailTemplate();

        $file_to_attach = '../data_sets/formats/';

        $mail->addAttachment($file_to_attach . $this->file_name . '.xml');
        $mail->addAttachment($file_to_attach . $this->file_name . '.txt');

        $mail->send();

        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message sent!";
        }
    }

} 