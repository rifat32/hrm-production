<?php

namespace App\Models;

use App\Http\Utils\DefaultQueryScopesTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobType extends Model
{
    use HasFactory, DefaultQueryScopesTrait;
    protected $fillable = [
        'name',
        'description',
        "is_active",
        "is_default",
        "business_id",
        "created_by",
        "parent_id",
    ];

    public function disabled()
    {
        return $this->hasMany(DisabledJobType::class, 'job_type_id', 'id');
    }


 


    // public function getCreatedAtAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }
    // public function getUpdatedAtAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }

}
