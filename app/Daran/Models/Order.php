<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Daran\Payment\PayableOrder;
use App\Daran\Shipment\ShippableOrder;
use Carbon\Carbon;

class Order extends Model implements PayableOrder, ShippableOrder
{
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'payed_at',
        'delivered_at',
        'return_requested_at'
    ];

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [

    ];

    /**
     * Get the user that owns the item.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }

    /**
     * Get the shipping address for the order
     */
    public function address()
    {
        return $this->belongsTo('App\Daran\Models\Address')->withTrashed();
    }

    /**
     * Get the invoice address for the order.
     */
    public function invoice_address()
    {
        return $this->belongsTo('App\Daran\Models\Address')->withTrashed();
    }

    /**
     * Get the payment method for the order.
     */
    public function payment_method()
    {
        return $this->belongsTo('App\Daran\Models\PaymentMethod');
    }

    /**
     * Get the shipping method for the order.
     */
    public function shipping_method()
    {
        return $this->belongsTo('App\Daran\Models\ShippingMethod');
    }

    /**
     * Get the coupon for the order.
     */
    public function coupon()
    {
        return $this->belongsTo('App\Daran\Models\Coupon');
    }

    /**
     * Get the details of the order
     */
    public function order_details()
    {
        return $this->hasMany('App\Daran\Models\OrderDetail');
    }

    /**
    * @return string
    */
    public function getPaymentOrderId()
    {
        return $this->uuid;
    }

    /**
     * Should be in eurocents for most payments providers
     * @return double
     */
     public function getPaymentAmount()
     {
         return ($this->goods_amount + $this->shipping_cost + $this->payment_cost + $this->country_cost - $this->coupon_amount) * 100;
     }

    /**
     * @return string
     */
    public function getPaymentDescription()
    {
        return 'Ordine nr. ' . $this->id;
    }

    /**
     * @return string
     */
    public function getCustomerEmail()
    {
        return $this->user->email;
    }

    /**
     * @return string
     */
    public function getCustomerName()
    {
        return $this->user->surname.','.$this->user->name;
    }

    /**
     * @return string
     */
    public function getCustomerLanguage()
    {
        return App::getLocale();
    }

    /**
    * @param string $payment_id
    * @return PayableOrder
    */
    public function setPaymentUid($payment_id)
    {
        $this->payment_uid = $payment_id;
        $this->save();
        return $this;
    }

    /**
    * @return string
    */
    public function getPaymentUid()
    {
        return $this->payment_uid;
    }

    public function getGrossTotalAttribute()
    {
        return $this->goods_amount + $this->shipping_cost + $this->payment_cost + $this->country_cost - $this->coupon_amount;
    }

    public function getCanReturnOrderAttribute()
    {
        if($this->status == 'delivered' && $this->delivered_at && !$this->return_requested_at){
            if(Carbon::now() <= $this->delivered_at->addDays(14)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function scopePositive($query)
    {
        return $query->where('status','!=','new')->where('status','!=','cancelled');
    }

    public static function GetMonthlyNumber($from, $to)
    {
        $i=1;
        $query = '';
        $temp_date = $from->copy();
        //for($i=1;$i<=$month_diff;$i++){
        while($temp_date<=$to){
            $query .= "SUM(IF(MONTH(created_at) = '".$temp_date->month."' AND YEAR(created_at) = '".$temp_date->year."', 1, 0) * 1) as M".$i.", ";
            $temp_date->addMonth();
            $i++;
        }
        $query = substr($query,0,strlen($query)-2);

        $numbers = DB::table('orders')->select(DB::raw($query))->where('status','!=','new')->where('status','!=','cancelled')->where('created_at','>=',$from->format('Y-m-d').' 00:00:00')->where('created_at','<=',$to->format('Y-m-d').' 23:59:59')->get();
        return $numbers;

    }
    public static function GetMonthlyAmount($from, $to)
    {
        $i=1;
        $query = '';
        $temp_date = $from->copy();
        while($temp_date<=$to){
            $query .= "SUM(IF(MONTH(created_at) = '".$temp_date->month."' AND YEAR(created_at) = '".$temp_date->year."', 1, 0) * total) as M".$i.", ";
            $temp_date->addMonth();
            $i++;
        }
        $query = substr($query,0,strlen($query)-2);

        $numbers = DB::table('orders')->select(DB::raw($query))->where('status','!=','new')->where('status','!=','cancelled')->where('created_at','>=',$from->format('Y-m-d').' 00:00:00')->where('created_at','<=',$to->format('Y-m-d').' 23:59:59')->get();
        return $numbers;
    }
}
