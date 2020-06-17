<?php

namespace App\Daran\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class Ecommerce extends Model
{
    protected $table = 'settings_ecommerce';
    public $timestamps = false;

    protected $guarded = [];

    protected $dates = [
        'valid_from',
        'valid_until',
    ];

    public function setValidFromAttribute($valid_from)
    {
        $this->attributes['valid_from'] = $valid_from ? \Carbon\Carbon::createFromFormat('d/m/Y', $valid_from) : null;
    }

    public function setValidUntilAttribute($valid_until)
    {
        $this->attributes['valid_until'] = $valid_until ? \Carbon\Carbon::createFromFormat('d/m/Y', $valid_until) : null;
    }

    /**
     * Scope a query to only include latest  settings.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLast($query)
    {
        return $query->where('valid_from','<=',date('Y-m-d H:i:s'))->where(function ($query){
            $query->whereNull('valid_until')->orWhere('valid_until','>',date('Y-m-d H:i:s'));
        })->orderBy('id','desc');

    }
}
