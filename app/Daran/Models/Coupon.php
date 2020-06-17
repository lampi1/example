<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
use App\Daran\Models\CouponUse;
use App\Daran\Cloner\Cloneable;
use Carbon\Carbon;

class Coupon extends Model
{
    use Cloneable;

    protected $guarded = [

    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'date_start',
        'date_end',
    ];

    protected $cloneable_relations = [];
    protected $clone_exempt_attributes = [];

    public function category()
    {
        return $this->belongsTo('App\Daran\Models\Category')->withDefault(['name'=>'Altro']);
    }
    public function family()
    {
        return $this->belongsTo('App\Daran\Models\Family')->withDefault(['name'=>'Altro']);
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function uses()
    {
        return $this->hasMany('App\Daran\Models\CouponUse');
    }
    public function codeExists($code)
    {
        return Coupon::where('code',$code)->where('id','!=',$this->id)->count() > 0;
    }

    public function setDateStartAttribute($published_at)
    {
        $this->attributes['date_start'] = $published_at == '' ? null : \Carbon\Carbon::createFromFormat('d/m/Y', $published_at);
    }
    public function setDateEndAttribute($published_at)
    {
        $this->attributes['date_end'] = $published_at == '' ? null : \Carbon\Carbon::createFromFormat('d/m/Y', $published_at);
    }

    public function calculate($rows)
    {
        if($this->amount > 0){
            return $this->amount;
        }

        $totale = 0;
        if($this->family_id || $this->category_id){
            foreach($rows as $row){
                if($this->category_id){
                    if($row->model->category_id == $this->category_id){
                        if($this->family_id){
                            if($this->family_id == $row->model->family_id){
                                $totale += $row->subtotal(2,'.','');
                            }
                        }else{
                            $totale += $row->subtotal(2,'.','');
                        }
                    }
                }elseif($this->family_id){
                    if($row->model->family_id == $this->family_id){
                        $totale += $row->subtotal(2,'.','');
                    }
                }else{
                    $totale += $row->subtotal(2,'.','');
                }
            }
        }else{
            foreach($rows as $row){
                $totale += $row->subtotal(2,'.','');
            }
        }
        return $totale * $this->discount / 100;
    }

    public function registerUse($order)
    {
        CouponUse::create([
            'coupon_id' => $this->id,
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'order_amount' => $order->total,
            'coupon_amount' => $order->coupon_amount
        ]);
    }

    public function onCloning($src) {
        $this->date_start = Carbon::tomorrow()->format('d/m/Y');
    }

}
