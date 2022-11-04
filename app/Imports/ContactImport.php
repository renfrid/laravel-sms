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
            '*.name' => 'required',
            '*.phone' => ['required', new ExcelPhone()]
        ])->validate();

        foreach ($rows as $row) {
            //cast phone
            $phone = '0' . trim($row['phone']);

            //check if farmer insert
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
