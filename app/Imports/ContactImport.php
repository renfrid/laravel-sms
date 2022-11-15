<?php

namespace App\Imports;

use App\Classes\Messaging;
use App\Models\Contact;
use App\Rules\ExcelPhone;
use App\Rules\PhoneNumber;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;

class ContactImport implements ToCollection, WithHeadingRow
{
    protected $groups;

    //constructor
    function __construct($groups)
    {
        $this->groups = $groups;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        Validator::make($rows->toArray(), [
            //'*.name' => 'required',
            '*.phone' => ['required']
        ])->validate();

        foreach ($rows as $row) {
            //check if phone is 10 digits and start with 0
            if (strlen($row['phone']) == 10 && substr($row['phone'], 1) == 0)
                $phone = $row['phone'];
            elseif (strlen($row['phone']) == 9)
                $phone = '0' . trim($row['phone']);
            else
                $phone = null;

            //check if phone is not null or ''
            if ($phone != null || $phone != '') {
                //insert or update contatc
                $contact = Contact::firstOrNew(
                    [
                        'phone' => $phone,
                        'created_by' => Auth::user()->id
                    ]
                );
                $contact->name = $row['name'];
                $contact->save();

                //insert contact group
                foreach ($this->groups as $group_id) {
                    $contact->groups()->sync($group_id);
                }
            }
        }
    }
}
