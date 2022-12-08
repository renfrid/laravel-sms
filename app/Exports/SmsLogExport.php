<?php

namespace App\Exports;

use App\Models\CustomerAccount;
use App\Models\SmsLog;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromArray;

class SmsLogExport implements FromQuery, WithHeadings, ShouldAutoSize, WithEvents, WithMapping
{
    protected $start_at, $end_at, $sender, $status;

    public function __construct($start_at, $end_at, $sender, $status)
    {
        $this->start_at = $start_at;
        $this->end_at = $end_at;
        $this->sender = $sender;
        $this->status = $status;
    }

    public function query()
    {
        //sms logs
        $sms_logs = SmsLog::orderBy('created_at');

        //start date and end date
        if ($this->start_at != null && $this->end_at != null) {
            $start_at = date('Y-m-d', strtotime($this->start_at));
            $end_at = date('Y-m-d', strtotime($this->end_at));

            $sms_logs = $sms_logs->whereBetween('sms_logs.created_at', [$start_at, $end_at]);
        }

        //sender
        if ($this->sender != null)
            $sms_logs = $sms_logs->where('sms_logs.sender', $this->sender);

        //status
        if ($this->status != null)
            $sms_logs = $sms_logs->where('sms_logs.gateway_status', $this->status);

        //return 
        return $sms_logs;
    }

    //headings
    public function headings(): array
    {
        return [
            'Message ID',
            'SMS',
            'Phone',
            'Created Time',
            'No. of SMS',
            'Status',
            'Sent Time',
            'Delivered Time'
        ];
    }

    //map
    public function map($sms_logs): array
    {
        return [
            $sms_logs->message_id,
            $sms_logs->message,
            $sms_logs->phone,
            $sms_logs->created_at,
            $sms_logs->sms_count,
            $sms_logs->gateway_status,
            $sms_logs->sent_at,
            $sms_logs->delivered_at
        ];
    }

    //events
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:H1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
        ];
    }
}
