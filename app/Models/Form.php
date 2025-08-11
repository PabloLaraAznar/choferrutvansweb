<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = [
        'title', 'name', 'email', 'phone', 'pickup_location', 
        'destination', 'service_type', 'additional_details', 
        'people', 'comments'
    ];
}