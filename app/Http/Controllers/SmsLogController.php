<?php

namespace App\Http\Controllers;

use App\Models\Sender;
use App\Models\SmsLog;
use App\Models\Template;
use Illuminate\Http\Request;
use App\Classes\Messaging;
use App\Jobs\SendSMSJob;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;

class SmsLogController extends Controller
{
    private $messaging;
    public function __construct()
    {
        $this->middleware('auth');

        //messaging
        $this->messaging = new Messaging();
    }

    //lists
    public function lists()
    {
        $title = 'SMS Logs';
        //has_allowed('roles', 'lists');

        //sms logs
        $sms_logs = SmsLog::paginate(50);

        return view('sms_logs.lists', compact('sms_logs', 'title'));
    }

    //send quick sms
    function quick_sms()
    {
        $title = 'Send Quick SMS';
        //has_allowed('roles', 'create');

        //populate data
        $senders = Sender::all();
        $templates = Template::all();

        //render view
        return view('sms_logs.quick_sms', compact('title', 'senders', 'templates'));
    }

    //send quick sms
    function send_quick_sms(Request $request)
    {
        //validation
        $this->validate(
            $request,
            [
                'phone' => 'required|string',
                'sender' => 'required|string',
                'message' => 'required|string',
                'schedule_at' => 'required_if:schedule,==,1',
                'schedule_time' => 'required_if:schedule,==,1',

            ],
            [
                'phone.required' => 'Phone required',
                'sender.required' => 'Sender required',
                'message.required' => 'Message required',
                'schedule_at.required_if' => 'Schedule date required',
                'schedule_time.required_if' => 'Schedule time required',
            ]
        );

        //post data
        $arr_phone = trim($request->input('phone'));
        $sender = $request->input('sender');
        $message = $request->input('message');
        $schedule_at = $request->input('schedule_at') . ' ' . $request->input('schedule_time');

        //message id
        $messageId = $this->messaging->generateMessageId();

        //expode phones
        $recipients = explode(',', $arr_phone);

        foreach ($recipients as $val) {
            //save to database
            $sms_log = SmsLog::firstOrNew([
                'message_id' => $messageId,
                'phone' => $val,
                'sender' => $sender
            ]);
            $sms_log->message = $message;
            $sms_log->sms_count = 1;
            $sms_log->schedule = $request->input('schedule');
            $sms_log->schedule_at = date('Y-m-d H:i:s', strtotime($schedule_at));
            $sms_log->created_by = Auth::user()->id;
            $sms_log->save();
        }

        //call background job for sending direct sms
        //schedule = null
        $schedule = $request->input('schedule');

        $data = [
            "schedule" => $schedule,
            "message_id" => $messageId,
            "sender" => $sender,
            "message" => $message
        ];
        dispatch(new SendSMSJob($data));

        //redirect 
        return Redirect::route('sms-logs.quick-sms')->with('success', 'Quick sms processed successfully!');
    }

    //send group sms
    function group_sms()
    {
        $title = 'Send Group SMS';
        //has_allowed('roles', 'create');

        //populate data
        $groups = Group::all();
        $senders = Sender::all();
        $templates = Template::all();

        //render view
        return view('sms_logs.group_sms', compact('title', 'senders', 'templates', 'groups'));
    }

    //send group sms
    function send_group_sms(Request $request)
    {
        //validation
        $this->validate(
            $request,
            [
                'group_ids' => 'required',
                'sender' => 'required|string',
                'message' => 'required|string',
                'schedule_at' => 'required_if:schedule,==,1',
                'schedule_time' => 'required_if:schedule,==,1',
            ],
            [
                'group_ids.required' => 'Group Name(s) required',
                'sender.required' => 'Sender required',
                'message.required' => 'Message required',
                'schedule_at.required_if' => 'Schedule date required',
                'schedule_time.required_if' => 'Schedule time required',
            ]
        );

        //post data
        $arr_groups = $request->input('group_ids');
        $sender = $request->input('sender');
        $message = $request->input('message');
        $schedule_at = $request->input('schedule_at') . ' ' . $request->input('schedule_time');

        //message id
        $messageId = $this->messaging->generateMessageId();

        //group contacts
        foreach ($arr_groups as $group_id) {
            //groups
            $group = Group::findOrFail($group_id);

            foreach ($group->contacts as $val) {
                //save to database
                $sms_log = SmsLog::firstOrNew([
                    'message_id' => $messageId,
                    'phone' => $val->phone,
                    'sender' => $sender
                ]);
                $sms_log->message = $message;
                $sms_log->sms_count = 1;
                $sms_log->schedule = $request->input('schedule');
                $sms_log->schedule_at = date('Y-m-d H:i:s', strtotime($schedule_at));
                $sms_log->created_by = Auth::user()->id;
                $sms_log->save();
            }
        }

        //call background job for sending direct sms
        //schedule = null
        $schedule = $request->input('schedule');

        $data = [
            "schedule" => $schedule,
            "message_id" => $messageId,
            "sender" => $sender,
            "message" => $message
        ];
        dispatch(new SendSMSJob($data));

        //redirect 
        return Redirect::route('sms-logs.group-sms')->with('success', 'Group sms processed successfully!');
    }

    //send file sms
    function file_sms()
    {
        $title = 'Send File SMS';
        //has_allowed('roles', 'create');

        //populate data
        $groups = Group::all();
        $senders = Sender::all();
        $templates = Template::all();

        //render view
        return view('sms_logs.file_sms', compact('title', 'senders', 'templates'));
    }

    //send file sms
    function send_file_sms(Request $request)
    {
        //validation
        $this->validate(
            $request,
            [
                'attachment' => 'required',
                'sender' => 'required|string',
                'message' => 'required|string',
                'schedule_at' => 'required_if:schedule,==,1',
                'schedule_time' => 'required_if:schedule,==,1',
            ],
            [
                'attachment.required' => 'Attachment required',
                'sender.required' => 'Sender required',
                'message.required' => 'Message required',
                'schedule_at.required_if' => 'Schedule date required',
                'schedule_time.required_if' => 'Schedule time required',
            ]
        );

        //post data
        $sender = $request->input('sender');
        $message = $request->input('message');
        $schedule_at = $request->input('schedule_at') . ' ' . $request->input('schedule_time');

        //message id
        $messageId = $this->messaging->generateMessageId();

        //deal with attachment
        $path = $request->file('attachment');
        $row = Excel::toArray([], $path);

        //form validation
        $count = 2;
        $validation = [];
        for ($i = 1; $i < sizeof($row[0]); $i++) {
            if ($row[0][$i][0] == '')
                array_push($validation, 'Missing phone number ' . $count);

            $count++;
        }

        //check for validation
        if (count($validation) > 0) {
            return Redirect::back()->with('validation_errors', $validation);
        } else {
            $count = 2;
            $success = 0;
            for ($i = 1; $i < sizeof($row[0]); $i++) {
                //check if phone is 10 digits and start with 0
                if (strlen($row[0][$i][0]) == 10 && substr($row[0][$i][0], 1) == 0)
                    $phone = $row[0][$i][0];
                elseif (strlen($row['phone']) == 9)
                    $phone = '0' . trim($row[0][$i][0]);
                else
                    $phone = null;

                //check if phone is not null or ''
                if ($phone != null || $phone != '') {
                    //save to database
                    $sms_log = SmsLog::firstOrNew([
                        'message_id' => $messageId,
                        'phone' => $phone,
                        'sender' => $sender
                    ]);
                    $sms_log->message = $message;
                    $sms_log->sms_count = 1;
                    $sms_log->schedule = $request->input('schedule');
                    $sms_log->schedule_at = date('Y-m-d H:i:s', strtotime($schedule_at));
                    $sms_log->created_by = Auth::user()->id;
                    $sms_log->save();
                }
            }

            //call background job for sending direct sms
            //schedule = null
            $schedule = $request->input('schedule');

            $data = [
                "schedule" => $schedule,
                "message_id" => $messageId,
                "sender" => $sender,
                "message" => $message
            ];
            dispatch(new SendSMSJob($data));
        }

        //redirect 
        return Redirect::route('sms-logs.file-sms')->with('success', 'File sms processed successfully!');
    }

    //send schedule sms
    function send_schedule_sms()
    {
        //limit
        $limit = 100;
        $no_of_pending_sms = SmsLog::where(['schedule' => 1, 'status' => 'PENDING'])->count();

        //looping sms
        $looping = $no_of_pending_sms / $limit;

        //iterate looping
        // for ($i = 1; $i <= ceil($looping); $i++) {
        //     $recipients = SmsLog::select('id', 'phone', 'sender', 'message')->where(['schedule' => 1, 'status' => 'PENDING'])->take($limit)->get();

        //     foreach ($recipients as $val) {
        //         //create arr data
        //         $postData = array(
        //             'source_addr' => $val->sender,
        //             'encoding' => 0,
        //             'schedule_time' => '',
        //             'message' => $val->message,
        //             'recipients' => [array('recipient_id' => 1, 'dest_addr' => $messaging->castPhone($val->phone))]
        //         );

        //         //post data
        //         $response = $messaging->sendSMS($postData);
        //         $result = json_decode($response);

        //         //check for successful or failure of message
        //         if ($result->code == 100) {
        //             //update sms status
        //             $sms_log = SmsLog::findOrFail($val->id);
        //             $sms_log->gateway_id = $result->request_id;
        //             $sms_log->gateway_response = json_encode($result);
        //             $sms_log->gateway_code = $result->code;
        //             $sms_log->gateway_message = $result->message;
        //             $sms_log->status = "SENT";
        //             $sms_log->save();

        //             //TODO: deduct bundle
        //         } else {
        //             //update sms status
        //             $sms_log = SmsLog::findOrFail($val->id);
        //             $sms_log->gateway_response = json_encode($result);
        //             $sms_log->gateway_code = $result->code;
        //             $sms_log->gateway_message = $result->message;
        //             $sms_log->status = "REJECTED";
        //             $sms_log->save();
        //         }
        //     }
        // }
    }

    //delivery report
    function delivery_report()
    {
        //limit
        $limit = 100;
        $no_of_sent_sms = SmsLog::where(['status' => 'SENT'])->count();

        //looping sms
        $looping = $no_of_sent_sms / $limit;

        //iterate looping
        for ($i = 1; $i <= ceil($looping); $i++) {
            $recipients = SmsLog::select('id', 'gateway_id', 'phone')->where(['status' => 'SENT'])->take($limit)->get();

            foreach ($recipients as $val) {
                //array('request_id' => $request_id,'dest_addr' => $dest_addr);
                //create arr data
                $postData = array(
                    'request_id' => $val->gateway_id,
                    'dest_addr' => $this->messaging->castPhone($val->phone)
                );

                //post data
                $response = $this->messaging->deliveryReport($postData);
                $result = json_decode($response);

                //check for successful or failure of message
                $sms_log = SmsLog::findOrFail($val->id);
                $sms_log->status = $result->status;
                $sms_log->save();
            }
        }
        echo response()->json(["error" => false, "success_msg" => "Delivery report success!"]);
    }
}
