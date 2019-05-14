<?php

namespace App;

use App\Application;
use App\Trigger;
use App\Measure;
use App\Operator;

use Illuminate\Database\Eloquent\Model;

class Listvalue extends Model
{
    public $timestamps = false;

    public function measure() {
        return $this->belongsTo('App\Measure', 'measure_code', 'code');
    }

}
