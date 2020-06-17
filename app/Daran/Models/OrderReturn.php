<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\App;
use App\Events\SendEmailOrderReturn;
use Carbon\Carbon;

class OrderReturn extends Model
{
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the user that owns the item.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }

    /**
     * Get the user that owns the item.
     */
    public function order()
    {
        return $this->belongsTo('App\Daran\Models\Order')->withTrashed();
    }


    /**
     * Get the details of the order
     */
    public function order_return_details()
    {
        return $this->hasMany('App\Daran\Models\OrderReturnDetail');
    }

    /**
     * Should be in eurocents for most payments providers
     * @return double
     */
     public function getPaymentAmount()
     {
         return ($this->total) * 100;
     }

    /**
     * Send mail to admin
     */
    public function sendEmailOrderReturn(){
        event(new SendEmailOrderReturn($this));
    }
}
