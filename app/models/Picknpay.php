<?php

class Picknpay extends Eloquent {


    protected $connection = 'hiphub';
    public function __construct() {
    	$this->connection = \Utils::getHiphubDbConnection();
	}

    protected $table = 'picknpay';

    public static function customerInStoreToday(){

        $dateRange = Picknpay::getDateForPeriodAndTimeOfDay('today');

        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];

        //TODO: Where date is today && group by customer uuid(maybe device uuid or something)
        return Picknpay::where('created_at', ">=", $startDate)
        ->where('created_at', "<=", $endDate)
        ->count();

    }

    public static function customerInStoreThisMonth(){


        $dateRange = Picknpay::getDateForPeriodAndTimeOfDay('repthismonth');

        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];

        //TODO: Where date is this month && group by customer uuid(maybe device uuid or something)
        return Picknpay::where('created_at', ">=", $startDate)
        ->where('created_at', "<=", $endDate)
        ->count();

    }

    public static function chartCategoriesAsJson($period){
        //TODO: Pass store name in here and filter according to store.

        $dateRange = Picknpay::getDateForPeriodAndTimeOfDay($period);

        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];

        $data = Picknpay::orderBy('id', 'ASC')
        ->select('category', 'created_at')
        ->where('created_at', ">=", $startDate)
        ->where('created_at', "<=", $endDate)
        ->groupBy('category')
        ->get()->map(function($row) {
            return array('label' => $row['created_at']->toDateString());
        })->toBase();

        $array = json_decode( $data, TRUE );
        $array = array_values( array_unique( $array, SORT_REGULAR ) );
        $result = json_encode( $array );

         return $result;

    }

    public static function getChartTotalDwellTimeData($period){
        //TODO: Pass store name in here and filter according to store.

        $dateRange = Picknpay::getDateForPeriodAndTimeOfDay($period);

        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];

        return Picknpay::orderBy('id', 'ASC')
        ->select('category', DB::raw('sum(CAST(dwell_time AS UNSIGNED)) dwell_time'))
        ->where('created_at', ">=", $startDate)
        ->where('created_at', "<=", $endDate)
        ->groupBy('category')
        ->get()->map(function($row){

            return array('seriesname' => $row['category'],
            'data' => array(
                    "value" => (int)((int)$row['dwell_time'] / 60)
                ));

        });

    }

    public static function getChartAverageDwellTimeData($period){
        //TODO: Pass store name in here and filter according to store.

        $dateRange = Picknpay::getDateForPeriodAndTimeOfDay($period);

        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];

        return Picknpay::orderBy('id', 'ASC')
        ->select('category', DB::raw('avg(CAST(dwell_time AS UNSIGNED)) dwell_time'))
        ->where('created_at', ">=", $startDate)
        ->where('created_at', "<=", $endDate)
        ->groupBy('category')
        ->get()->map(function($row){

            return array('seriesname' => $row['category'],
            'data' => array(
                    "value" => (int)((int)$row['dwell_time'] / 60)
                ));

        });

    }

    public static function getDateForPeriodAndTimeOfDay($period){

        $returnValue = '';

        if($period == 'today'){

            $date = date('Y-m-d',strtotime('today'));

            $returnValue['startDate'] = "$date 00.00.00";
            $returnValue['endDate'] = "$date 23:59:59";
            $returnValue['period'] = "today";

        }else if($period == 'rep7day'){

            $start = date('Y-m-d',strtotime('last monday'));
            $end = date('Y-m-d');


            $returnValue['startDate'] = "$start 00.00.00";
            $returnValue['endDate'] = "$end 23:59:59";
            $returnValue['period'] = "week";

        }else if($period == 'repthismonth'){

            $dateRange = Picknpay::getDateForPeriodAndTimeOfDay('repthismonth');

            $startDate = $dateRange['startDate'];
            $endDate = $dateRange['endDate'];


            $returnValue['startDate'] = "$start 00.00.00";
            $returnValue['endDate'] = "$end 23:59:59";
            $returnValue['period'] = "month";

        }else if($period == 'replastmonth'){

            $start = date('Y-m-d',strtotime('first day of last month'));
            $end = date('Y-m-d',strtotime('last day of last month'));

            $returnValue['startDate'] = "$start 00.00.00";
            $returnValue['endDate'] = "$end 23:59:59";
            $returnValue['period'] = "month";

        }else if($period == 'daterange'){

            $start = Input::get('start');
            $end = Input::get('end');

            $returnValue['startDate'] = "$start 00.00.00";
            $returnValue['endDate'] = "$end 23:59:59";
            $returnValue['period'] = "date";

        }else if($period == 'custom'){
            $start = Input::get('start');
            $end = Input::get('end');

            $returnValue['startDate'] = "$start 00.00.00";
            $returnValue['endDate'] = "$end 23:59:59";
            $returnValue['period'] = "date";

        }

        return $returnValue;

    }

}



//SHould look like this
        // {
        //     "chart": chartProperties,
        //     "categories": [{
        //         "category": [{"label":"2019-05-27"},{"label":"2019-05-28"}]                                }],
        //     "dataset": [{"seriesname":"Staff At Work","data":[{"value":"0"},{"value":"0"}]},{"seriesname":"Staff Not At Work","data":[{"value":"1"},{"value":"1"}]}]
        // }