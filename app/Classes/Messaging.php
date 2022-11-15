<?php

namespace App\Classes;

class Messaging
{
    private $API_BASE_URL;
    private $API_DELIVERY_URL;
    private $API_KEY;
    private $SECRET_KEY;
    private $M_CODE;

    public function __construct()
    {
        $this->API_BASE_URL = 'https://apisms.beem.africa/v1/send';
        $this->API_DELIVERY_URL = 'https://dlrapi.beem.africa/public/v1/delivery-reports';

        $this->API_KEY = env('API_KEY');
        $this->SECRET_KEY = env('SECRET_KEY');
        $this->M_CODE = "255";
    }

    //action to send notification
    function sendSMS($arr_data)
    {
        $ch = curl_init($this->API_BASE_URL);
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
        if (preg_match("~^0\d+$~", $mobile)) {
            return $this->M_CODE . substr($mobile, 1);
        } else if (substr($mobile, 0, 3) != '255' & strlen($mobile) == 9) {
            return $this->M_CODE . $mobile;
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
