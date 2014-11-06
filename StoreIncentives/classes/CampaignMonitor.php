<?php
/**
 * Created by michaelwatts
 * Date: 14/05/2014
 * Time: 11:46
 */

namespace StoreIncentives;

date_default_timezone_set("Europe/London");

require "../vendor/campaignmonitor/createsend-php/csrest_general.php";
require "../vendor/campaignmonitor/createsend-php/csrest_lists.php";
require "../vendor/campaignmonitor/createsend-php/csrest_subscribers.php";
require "../vendor/campaignmonitor/createsend-php/csrest_campaigns.php";


class CampaignMonitor {

    const SFM_CLIENT_ID = "*****";
    const TEMPLATE_ID = "096e3121f00542fc2de4cbe6e8843a1e";
    const LIST_ID = "89c0710c93d687afcf2794841c1855db";

    private $auth = array('api_key' => '*****');

    protected $file_name = "store_test_data";
    protected $list_id;
    protected $campaign_id;
    protected $subscribers;
    protected $campaign_from_template;

    /**
     * @param mixed $subscribers
     */
    public function setSubscribers($subscribers)
    {
        $this->subscribers = $subscribers;
    }

    /**
     * @return mixed
     */
    public function getSubscribers()
    {
        return $this->subscribers;
    }

    /**
     * Create a list, returns list ID
     *
     * @return mixed
     */
    /*
    public function createList()
    {
        $wrap = new \CS_REST_Lists(NULL, $this->auth);

        $result = $wrap->create(self::SFM_CLIENT_ID, array(
            'Title' => "StoreIncentives (" . $this->file_name . ") -- " . date(DATE_ATOM),
            'UnsubscribePage' => 'http://saudifashionmagazine.createsend1.com/t/d-u-adkidk-l-j/',
            'ConfirmedOptIn' => false,
            'ConfirmationSuccessPage' => 'what?',
            'UnsubscribeSetting' => CS_REST_LIST_UNSUBSCRIBE_SETTING_ALL_CLIENT_LISTS
          ));

        if ($result->was_successful()) {
            new Logger("CPMN_SUCCS", $result->http_status_code . ". List has been added");
            $this->list_id = $result->response;
            return $result->response;
        } else {
            new Logger("CPMN_ERROR", $result->http_status_code . ". Create list failed");
            echo('Failed with code ' . $result->http_status_code);
            var_dump($result->response);
        }
    }
    */

    public function createCustomTextField($field_name)
    {
        $wrap = new \CS_REST_Lists(self::LIST_ID, $this->auth);

        $result = $wrap->create_custom_field(array(
            'FieldName' => $field_name,
            'DataType' => CS_REST_CUSTOM_FIELD_TYPE_TEXT
          )
        );

        return $result;
    }

    public function addSubscribers()
    {
        $wrap = new \CS_REST_Subscribers(self::LIST_ID, $this->auth);

        foreach ($this->subscribers as $subscriber) {
            $result = $wrap->add(array(
                'EmailAddress' => $subscriber["email"],
                'Name' => $subscriber["name"],
                'CustomFields' => [
                  [
                    'Key' => 'encrypted_email',
                    'Value' => $subscriber["encrypted_email"]
                  ],
                  [
                    'Key' => 'gender',
                    'Value' => $subscriber["gender"]
                  ],
                  [
                    'Key' => 'store',
                    'Value' => $subscriber["store"]
                  ],
                  [
                    'Key' => 'brand',
                    'Value' => $subscriber["brand"]
                  ],
                  [
                    'Key' => 'mall',
                    'Value' => $subscriber["mall"]
                  ],
                  [
                    'Key' => 'staff_id',
                    'Value' => $subscriber["staff_id"]
                  ]
                ],
                'Resubscribe' => true
              )
            );
        }

        if($result->was_successful()) {
            new Logger("CPMN_SUCCS", $result->http_status_code . ". Subscribers have been added");
            return $result;
        } else {
            new Logger("CPMN_ERROR", $result->http_status_code . ". Create list failed");
            echo('Failed with code ' . $result->http_status_code);
            var_dump($result->response);
        }

    }

    private function campaignContent()
    {
        // based on templates/email.html

//        $template_content = array(
//          'Repeaters' => array(
//            array(
//              'Items' => array(
//                array(
//                  'Layout' => 'Welcome',
//                  'Singlelines' => array(
//                    array(
//                      'Content' => 'Welcome [firstname]'
//                    )
//                  ),
//                  'Multilines' => array(
//                    array(
//                      'Content' => '<p style="text-align: center;">
//	<span style="font-size:18px;"><em>Thank you for signing up to <a href="http://www.saudifashionmagazine.com/pos/receiver/?user=[encrypted_email,fallback=]">SFM</a>, the hottest new online fashion &amp; lifestyle magazine.</em></span></p>
//'
//                    )
//                  )
//                ),
//                array(
//                  'Layout' => 'Confirm',
//                  'Singlelines' => array(
//                    array(
//                      'Content' => 'Confirm Your Registration'
//                    )
//                  ),
//                  'Multilines' => array(
//                    array(
//                      'Content' => '
//                    <p style="text-align: center; font-size:18px;">
//	                    <em>
//	                      All you need to do now is <a href="http://www.saudifashionmagazine.com/pos/receiver/?user=[encrypted_email,fallback=]">click here</a> to confirm your registration.
//	                    </em>
//	                  </p>
//	                  <p style="text-align: center; font-size:16px; color: #555;">Then you can simply sit back and enjoy a seriously stylish selection of news, advice and exclusive competitions.</p>
//	                  <p style="text-align: center; font-size:16px; color: #555;">Just in case you miss anything on SFM, we’ll send you a weekly newsletter bursting with the latest and best from the glamorous worlds of fashion, lifestyle &amp; beauty.</p>'
//                    )
//                  )
//                ),
//                array(
//                  'Layout' => 'Confirm',
//                  'Singlelines' => array(
//                    array(
//                      'Content' => 'Your SFM account details'
//                    )
//                  ),
//                  'Multilines' => array(
//                    array(
//                      'Content' => '<p style="text-align: center;">
//	<span style="font-size:18px;"><em>Username: <a href="http://www.saudifashionmagazine.com/pos/receiver/?user=[encrypted_email,fallback=]">[email]</a></em></span></p>
//'
//                    )
//                  )
//                ),
//              )
//            )
//          ),
//          'Singlelines' => array(
//            array(
//              'Content' => 'Win A 7th Generation iPod Nano - 16GB',
//              'Href' => 'http://www.saudifashionmagazine.com/sfm-plus/offers/win-a-7th-generation-ipod-nano-16gb'
//            ),
//            array(
//              'Content' => 'Mega-Giveaway! Beats by Dr. Dre',
//              'Href' => 'http://www.saudifashionmagazine.com/sfm-plus/offers/mega-giveaway-beats-by-dr.-dre'
//            ),
//          ),
//          'Images' => array(
//            array(
//              'Content' => 'http://cdn.saudifashionmagazine.com/images/uploads/offers/offers_image/_350x350/ipodnano.png',
//              'Alt' => 'WIN! iPod Nano 7th Generation 16GB',
//              'Href' => 'http://www.saudifashionmagazine.com/sfm-plus/offers/win-a-7th-generation-ipod-nano-16gb'
//            ),
//            array(
//              'Content' => 'http://cdn.saudifashionmagazine.com/images/uploads/offers/offers_image/_350x350/beatsheadphones_.png',
//              'Alt' => 'Mega-Giveaway! Beats by Dr. Dre',
//              'Href' => 'http://www.saudifashionmagazine.com/sfm-plus/offers/mega-giveaway-beats-by-dr.-dre'
//            ),
//          ),
//          'Multilines' => array(
//            array(
//              'Content' => '<p>Love music? The ultra-portable 7th generation iPod nano could become your favourite new accessory.</p>'
//            ),
//            array(
//              'Content' => '<p><a href=\"http://www.saudifashionmagazine.com/sfm-plus/offers/win-a-7th-generation-ipod-nano-16gb\">Enter now</a></p>'
//            ),
//            array(
//              'Content' => '<p>Music like you\'ve never heard it before. Want a pair? Enter now for a chance to win!</p>'
//            ),
//            array(
//              'Content' => '<p><a href=\"http://www.saudifashionmagazine.com/sfm-plus/offers/mega-giveaway-beats-by-dr.-dre\">Enter now</a></p>'
//            ),
//          )
//        );
//
//        return $template_content;

        $template_content = array(
            'Repeaters' => array(
                array(
                    'Items' => array(

                        array(
                            'Layout' => 'Welcome',
                            'Singlelines' => array(
                                array(
                                    'Content' => '[firstname]   مرحبا '
                                )
                            ),
                            'Multilines' => array(
                                array(
                                    'Content' => '<p style="text-align: center;">
                                    <span style="font-size:18px;"><em>نشكرك للإشتراك في مجلة سعودي فاشن  <a href="http://www.saudifashionmagazine.com/pos/receiver/?user=[encrypted_email,fallback=]">SFM</a>, ، أفضل مجلة جديدة للموضة واللايف ستايل على الإنترنت.</em></span></p>
                                '
                                )
                            )
                        ),
                        array(
                            'Layout' => 'Confirm',
                            'Singlelines' => array(
                                array(
                                    'Content' => 'تأكيد التسجيل. '
                                )
                            ),
                            'Multilines' => array(
                                array(
                                    'Content' => '
                                <p style="text-align: center; font-size:18px;">
                                    <em>
                                      <a href="http://www.saudifashionmagazine.com/pos/receiver/?user=[encrypted_email,fallback=]">كل ما عليك القيام به الآن هو النقر هنا لتأكيد التسجيل. </a>
                                    </em>
                                  </p>
                                  <p style="text-align: center; font-size:16px; color: #555;font-style: italic;">ثم يمكنك ببساطة الجلوس والاستمتاع بتشكيلة رائعة من الأخبار والنصائح والمسابقات الحصرية.</p>
                                  <p style="text-align: center; font-size:16px; color: #555;font-style: italic;">في حال أنكي تفويت أي شيئ من مجلة سعودي فاشن سنرسل لك رسالة إخبارية مليئة بأحدث وأفضل أخبار عالم الأزياء والجمال واللايف ستايل. </p>'
                                )
                            )
                        ),
                        array(
                            'Layout' => 'Confirm',
                            'Singlelines' => array(
                                array(
                                    'Content' => 'تفاصيل حساب مجلة سعودي فاشن الخاص بك '
                                )
                            ),
                            'Multilines' => array(
                                array(
                                    'Content' => '<p style="text-align: center;">
                                        <span style="font-size:18px;"><em>إسم المستخدم:  <a href="http://www.saudifashionmagazine.com/pos/receiver/?user=[encrypted_email,fallback=]">[email]</a></em></span></p>
                                    '
                                )
                            )
                        ),

                                                array(
                            'Layout' => 'Welcome',
                            'Singlelines' => array(
                                array(
                                    'Content' => 'Welcome [firstname]'
                                )
                            ),
                            'Multilines' => array(
                                array(
                                    'Content' => '<p style="text-align: center;">
	                                <span style="font-size:18px;"><em>Thank you for signing up to <a href="http://www.saudifashionmagazine.com/pos/receiver/?user=[encrypted_email,fallback=]">SFM</a>, the hottest new online fashion &amp; lifestyle magazine.</em></span></p>'
                                )
                            ),
                        ),


                        array(
                            'Layout' => 'Confirm',
                            'Singlelines' => array(
                                array(
                                    'Content' => 'Confirm Your Registration'
                                )
                            ),
                            'Multilines' => array(
                                array(
                                    'Content' => '
                                <p style="text-align: center; font-size:18px;">
                                    <em>
                                      All you need to do now is <a href="http://www.saudifashionmagazine.com/pos/receiver/?user=[encrypted_email,fallback=]">click here</a> to confirm your registration.
                                    </em>
                                  </p>
                                  <p style="text-align: center; font-size:16px; color: #555;font-style: italic;">Then you can simply sit back and enjoy a seriously stylish selection of news, advice and exclusive competitions.</p>
                                  <p style="text-align: center; font-size:16px; color: #555;font-style: italic;">Just in case you miss anything on SFM, we’ll send you a weekly newsletter bursting with the latest and best from the glamorous worlds of fashion, lifestyle &amp; beauty.</p>'
                                )
                            )
                        ),

                        array(
                            'Layout' => 'Confirm',
                            'Singlelines' => array(
                                array(
                                    'Content' => 'Your SFM account details'
                                )
                            ),
                            'Multilines' => array(
                                array(
                                    'Content' => '<p style="text-align: center;">
	<span style="font-size:18px;"><em>Username: <a href="http://www.saudifashionmagazine.com/pos/receiver/?user=[encrypted_email,fallback=]">[email]</a></em></span></p>
'
                                )
                            )
                        ),
                    )
                )
            ),

        );

        return $template_content;
    }

    public function createCampaign()
    {
        $wrap = new \CS_REST_Campaigns(NULL, $this->auth);

        $result = $wrap->create_from_template(self::SFM_CLIENT_ID, array(
            'Subject' => 'Saudi Fashion Magazine Confirmation Signup',
            'Name' => 'Mall Signup_'.date(DATE_ATOM),
            'FromName' => 'Saudi Fashion Magazine',
            'FromEmail' => 'info@saudifashionmagazine.com',
            'ReplyTo' => 'info@saudifashionmagazine.com',
            'ListIDs' => array($this->list_id),
            'TemplateID' => self::TEMPLATE_ID,
            'TemplateContent' => $this->campaignContent()
          )
        );

        if ($result->was_successful()) {
            new Logger("CPMN_SUCCS", $result->http_status_code . ". Campaign has been added");
            $this->campaign_id = $result->response;
        } else {
            new Logger("CPMN_ERROR", $result->http_status_code . ". Create campaign failed");
            echo("Failed with code " . $result->http_status_code);
            var_dump($result->response);
        }
    }

    public function sendCampaign()
    {
        $wrap = new \CS_REST_Campaigns($this->campaign_id, $this->auth);

        $result = $wrap->send(array(
            'ConfirmationEmail' => 'grant@smswmedia.com,mike@smswmedia.com',
            'SendDate' => 'Immediately'
          )
        );

        if ($result->was_successful()) {
            new Logger("CPMN_SUCCS", $result->http_status_code . ". Campaign has been sent");
            return $result->response;
        } else {
            new Logger("CPMN_ERROR", $result->http_status_code . ". Send campaign failed");
            echo("Failed with code " . $result->http_status_code);
            var_dump($result->response);
        }
    }

}

