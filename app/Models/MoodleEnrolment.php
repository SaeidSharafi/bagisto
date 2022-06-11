<?php

namespace App\Models;

use App\Models\Shop\JeduCustomer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoodleEnrolment extends Model
{
    use HasFactory;

    protected $fillable =[
        'customer_national_code',
        'moodle_course_id'
    ];

    public function customer(){
        $this->belongsTo(JeduCustomer::class,'customer_national_code','national_code');
    }
}
