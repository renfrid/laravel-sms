<?php

namespace App\Jobs;

use App\Classes\Messaging;
use App\Mail\SendEmail;
use App\Models\SmsLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendSMSJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //call messaging class
        $messaging = new Messaging();

        //data submitted
        $messageId = $this->data['message_id'];
        $message = $this->data['message'];
        $sender = $this->data['sender'];
        $schedule = $this->data['schedule'];

        //limit
        $limit = 100;
        $no_of_pending_sms = SmsLog::where(['message_id' => $messageId, 'status' => 'PENDING'])->count();

        //looping sms
        $looping = $no_of_pending_sms / $limit;

        //iterate looping
        for ($i = 1; $i <= ceil($looping); $i++) {
            $recipients = SmsLog::select('id', 'phone', 'schedule_at')->where(['message_id' => $messageId, 'status' => 'PENDING'])->take($limit)->get();

            foreach ($recipients as $val) {
                //create arr data
                $postData = array(
                    'source_addr' => $sender,
                    'encoding' => 0,
                    // 'schedule_time' => '',
                    'message' => $message,
                    'recipients' => [array('recipient_id' => 1, 'dest_addr' => $messaging->castPhone($val->phone))]
                );

                //check for schedule
                if ($schedule == 1)
                    $postData['schedule_time'] = $val->schedule_at;
                else
                    $postData['schedule_time'] = '';

                Log::info("post data => " , json_encode($postData));    

                //post data
                $response = $messaging->sendSMS($postData);
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
}
