<?php

namespace App\Classes;

use App\Models\SmsLog;
use Illuminate\Support\Facades\Log;

class Messaging
{
    private $API_BASE_URL;
    private $API_DELIVERY_URL;
    private $API_KEY;
    private $SECRET_KEY;
    private $M_CODE;

    public function __construct()
    {
        $this->API_KEY = '694e9d101ab76d66';
        $this->SECRET_KEY = 'YTFjM2E4MmNhMmU0NWY4Nzk1NTYzOTkwNzExMWI0YmM1YWY0MGNkM2VkMDE2NjgxY2E2MDk1YzJhZTRkZWM3YQ==';
        $this->API_DELIVERY_URL = 'https://dlrapi.beem.africa/public/v1/delivery-reports';
    }

    //action to send sms
    function actionSendSMS($messageId)
    {
        //limit
        $limit = 100;
        $no_of_pending_sms = SmsLog::where(['message_id' => $messageId, 'status' => 'PENDING'])->count();

        //looping sms
        $looping = $no_of_pending_sms / $limit;

        //iterate looping
        for ($i = 1; $i <= ceil($looping); $i++) {
            $recipients = SmsLog::select('id', 'phone', 'message', 'sender', 'schedule', 'schedule_at')->where(['message_id' => $messageId, 'status' => 'PENDING'])->take($limit)->get();

            foreach ($recipients as $val) {
                //create arr data
                $postData = array(
                    'source_addr' => $val->sender,
                    'encoding' => 0,
                    'message' => $val->message,
                    'recipients' => [array('recipient_id' => 1, 'dest_addr' => $this->castPhone($val->phone))]
                );

                //check for schedule
                if ($val->schedule == 1)
                    $postData['schedule_time'] = $val->schedule_at;
                else
                    $postData['schedule_time'] = '';

                Log::debug("post data => ", $postData);

                //post data
                $response = $this->sendSMS($postData);
                $result = json_decode($response);

                //check for successful or failure of message
                if ($result->code == 100) {
                    //update sms status
                    $sms_log = SmsLog::findOrFail($val->id);
                    $sms_log->gateway_id = $result->request_id;
                    $sms_log->gateway_response = json_encode($result);
                    $sms_log->gateway_code = $result->code;
                    $sms_log->gateway_message = $result->message;
                    $sms_log->status = "SENT";
                    $sms_log->save();

                    //TODO: deduct bundle
                } else {
                    //update sms status
                    $sms_log = SmsLog::findOrFail($val->id);
                    $sms_log->gateway_response = json_encode($result);
                    $sms_log->gateway_code = $result->code;
                    $sms_log->gateway_message = $result->message;
                    $sms_log->status = "REJECTED";
                    $sms_log->save();
                }
            }
        }
    }

    //action to send notification
    function sendSMS($arr_data)
    {
        // Setup cURL
        $ch = curl_init('https://apisms.beem.africa/v1/send');
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Authorization:Basic ' . base64_encode("$this->API_KEY:$this->SECRET_KEY"),
                'Content-Type: application/json'
            ),
            CURLOPT_POSTFIELDS => json_encode($arr_data)
        ));

        $response = curl_exec($ch);

        if ($response === FALSE) {
            echo $response;
            die(curl_error($ch));
        }

        //resurn repsonse
        return $response;
    }

    //activon to get delivery reports
    function deliveryReport($arr_data)
    {
        //set_time_limit(0);
        ini_set('max_execution_time', 0);

        // Setup cURL
        $ch = curl_init();
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $URL = $this->API_DELIVERY_URL . '?' . http_build_query($arr_data);

        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($ch, array(
            CURLOPT_HTTPGET => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'Authorization:Basic ' . base64_encode("$this->API_KEY:$this->SECRET_KEY"),
                'Content-Type: application/json',
            ),
        ));

        // Send the request
        $response = curl_exec($ch);

        // Check for errors
        if ($response === false) {
            echo $response;

            die(curl_error($ch));
        }

        //logging the response
        Log::debug("response", [$response]);

        //resurn repsonse
        return $response;
    }

    //generate message_id
    function generateMessageId()
    {
        //the characters you want in your id
        $characters = '123456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $max = strlen($characters) - 1;
        $string = '';

        for ($i = 0; $i <= 10; $i++) {
            $string .= $characters[mt_rand(0, $max)];
        }

        return $string;
    }

    //remove 0 and + on start of mobile
    function castPhone($mobile)
    {
        $M_CODE = "255";

        if (preg_match("~^0\d+$~", $mobile)) {
            return $M_CODE . substr($mobile, 1);
        } else if (substr($mobile, 0, 3) != '255' & strlen($mobile) == 9) {
            return $M_CODE . $mobile;
        } else {
            return str_replace('+', '', $mobile);
        }
    }

    //add 0 on the mobile phone
    function addZeroOnPhone($mobile)
    {
        if (!empty($mobile)) {
            if (strlen($mobile) == 9) {
                $phone = '0' . $mobile;
            } else {
                $phone = $mobile;
            }
        } else {
            $phone = $mobile;
        }

        return $phone;
    }
}
