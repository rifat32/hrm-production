<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobListingJobPlatforms extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_listing_id', 'job_platform_id'
    ];

    public function job_listing()
    {
        return $this->belongsTo(JobListing::class,"job_listing_id","id");
    }


}
