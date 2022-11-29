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
use Illuminate\Support\Facades\DB;
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
    public function lists(Request $request)
    {
        $title = 'SMS Logs';
        //has_allowed('roles', 'lists');

        //sms logs
        $sms_logs = SmsLog::orderBy('created_at');

        if (isset($_POST['filter'])) {
            $start_at = $request->input('start_at');
            $end_at = $request->input('end_at');
            $sender = $request->input('sender');
            $status = $request->input('status');

            //start data and end date
            if ($start_at != null && $end_at != null) {
                $start_at = date('Y-m-d', strtotime($start_at));
                $end_at = date('Y-m-d', strtotime($end_at));

                $sms_logs = $sms_logs->whereBetween('sms_logs.created_at', [$start_at, $end_at]);
            }

            //sender
            if ($sender != null)
                $sms_logs = $sms_logs->where('sms_logs.sender', $sender);

            //status
            if ($status != null)
                $sms_logs = $sms_logs->where('sms_logs.status', $status);

            //sms logs
            $sms_logs = $sms_logs->paginate(100);
        } else {
            //sms logs
            $sms_logs = $sms_logs->paginate(100);
        }

        //populate data
        $senders = Sender::all();

        return view('sms_logs.lists', compact('title', 'sms_logs', 'senders'));
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

        //send instant
        $this->messaging->actionSendSMS($messageId);

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
            // $schedule = $request->input('schedule');

            // $data = [
            //     "schedule" => $schedule,
            //     "message_id" => $messageId,
            //     "sender" => $sender,
            //     "message" => $message
            // ];
            // dispatch(new SendSMSJob($data));
        }

        //redirect 
        return Redirect::route('sms-logs.file-sms')->with('success', 'File sms processed successfully!');
    }

      /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $sms_log = SmsLog::findOrFail($id);

        if ($del = $sms_log->delete()) {
            //redirect
            return Redirect::route('sms-logs.lists')->with('success', 'SMS deleted!');
        } else {
            return Redirect::route('sms-logs.lists')->with('danger', 'Failed to delete sms!');
        }
    }

    //delete all
    public function delete_all()
    {
        if (!Auth::user()->hasRole('admin')) {
            return Redirect::route('sms-logs.lists')->with('danger', 'You can not delete sms logs!');
        } else {
            //delete all messages
            DB::table('sms_logs')->truncate();
            return Redirect::route('sms-logs.lists')->with('success', 'SMS logs successfully deleted!');
        }
    }
}
