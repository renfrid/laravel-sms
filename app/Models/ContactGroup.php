<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactGroup extends Model
{
    use HasFactory;
    public $table = "contact_group";
    protected $guarded = ['id'];
    public $timestamps = false;
}
