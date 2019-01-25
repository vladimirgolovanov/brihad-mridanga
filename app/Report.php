<?php

namespace App;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public $timestamps = false;
    public $incrementing = false;

    protected $table = 'reports';

    protected $primaryKey = 'custom_date';

    protected $fillable = [
        'custom_date',
        'compiled',
    ];
    public static function get_all_reports()
    {
        $reports = DB::table('reports AS r')
            ->orderBy('r.custom_date')
            ->select('r.*')
            ->get();
        return $reports;
    }
    public function setCustomDateAttribute($value) {
        $this->attributes['custom_date'] = (new Carbon($value))->format('Y-m-d');
    }
}
