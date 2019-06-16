<?php

class Picknpay extends Eloquent {


    protected $connection = 'hiphub';
    public function __construct() {
    	$this->connection = \Utils::getHiphubDbConnection();
	}

    protected $table = 'picknpay';

    public static function datesToFetchChartDataFor($date, $startDate, $endDate){

        if ($startDate == null && $endDate == null) {

            $dateRange = Picknpay::getDateForPeriodAndTimeOfDay($date);

            $startDate = $dateRange['startDate'];
            $endDate = $dateRange['endDate'];

        }

        $dateRange = Picknpay::getDateForPeriodAndTimeOfDay($date);

        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];

        return Picknpay::orderBy('created_at', 'ASC')
        ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') AS created_att"))
        ->whereraw("DATE_FORMAT(created_at, '%Y-%m-%d') >= '$startDate'")
        ->whereraw("DATE_FORMAT(created_at, '%Y-%m-%d') <= '$endDate'")
        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
        ->get();
    }

    public static function fetchAllCategories($date, $startDate, $endDate){

        if ($startDate == null && $endDate == null) {

            $dateRange = Picknpay::getDateForPeriodAndTimeOfDay($date);

            $startDate = $dateRange['startDate'];
            $endDate = $dateRange['endDate'];

        }

        return DB::select(DB::raw("SELECT DISTINCT category FROM picknpay WHERE DATE_FORMAT(created_at, '%Y-%m-%d') >= '$startDate' AND DATE_FORMAT(created_at, '%Y-%m-%d') <= '$endDate'"));
    }

    public static function fetchDwellTimeDataForCategoryWithDate($date, $category, $verb){
        return Picknpay::orderBy('created_at', 'ASC')
        ->select(DB::raw("IFNULL($verb(CAST(dwell_time AS UNSIGNED)), 0) AS value"))
        ->whereraw("DATE_FORMAT(created_at, '%Y-%m-%d') = '$date'")
        ->whereraw("category = '$category'")
        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
        ->orderBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
        ->get();
    }

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

    public static function getDateForPeriodAndTimeOfDay($period){

        $returnValue = '';

        if($period == 'today'){

            $date = date('Y-m-d',strtotime('today'));

            $returnValue['startDate'] = "$date 00.00.01";
            $returnValue['endDate'] = "$date 23:59:59";
            $returnValue['period'] = "today";

        }else if($period == 'rep7day'){

            $start = date('Y-m-d',strtotime('last monday'));
            $end = date('Y-m-d');


            $returnValue['startDate'] = "$start 00.00.01";
            $returnValue['endDate'] = "$end 23:59:59";
            $returnValue['period'] = "week";

        }else if($period == 'repthismonth'){

            $start = date('Y-m-d',strtotime('first day of this month'));
            $end = date('Y-m-d',strtotime('today'));

            $returnValue['startDate'] = "$start 00.00.01";
            $returnValue['endDate'] = "$end 23:59:59";
            $returnValue['period'] = "month";

        }else if($period == 'replastmonth'){

            $start = date('Y-m-d',strtotime('first day of last month'));
            $end = date('Y-m-d',strtotime('last day of last month'));

            $returnValue['startDate'] = "$start 00.00.01";
            $returnValue['endDate'] = "$end 23:59:59";
            $returnValue['period'] = "month";

        }else if($period == 'daterange'){

            $start = Input::get('start');
            $end = Input::get('end');

            $returnValue['startDate'] = "$start 00.00.01";
            $returnValue['endDate'] = "$end 23:59:59";
            $returnValue['period'] = "date";

        }else if($period == 'custom'){
            $start = Input::get('start');
            $end = Input::get('end');

            $returnValue['startDate'] = "$start 00.00.01";
            $returnValue['endDate'] = "$end 23:59:59";
            $returnValue['period'] = "date";

        }

        return $returnValue;

    }

}


// https://stackoverflow.com/questions/33828769/add-new-element-in-laravel-collection-object
// https://stackoverflow.com/questions/33828769/add-new-element-in-laravel-collection-object
// https://stackoverflow.com/questions/43271110/laravel-eloquent-foreach-not-working
// https://scotch.io/tutorials/laravel-collections-php-arrays-on-steroids
//SHould look like this
        // {
        //     "chart": chartProperties,
        //     "categories": [{
        //         "category": [{"label":"2019-05-27"},{"label":"2019-05-28"}]                                }],
        //     "dataset": [{"seriesname":"Staff At Work","data":[{"value":"0"},{"value":"0"}]},{"seriesname":"Staff Not At Work","data":[{"value":"1"},{"value":"1"}]}]
        // }

        // category: "[{"label":"2019-06-05"},{"label":"2019-06-02"}]"



        // return Picknpay::orderBy('id', 'ASC')
        // ->select('category', 'DATE_FORMAT(created_at, '%Y-%m-%d') AS created_at', DB::raw('sum(CAST(dwell_time AS UNSIGNED)) dwell_time'))
        // ->where('created_at', ">=", $startDate)
        // ->where('created_at', "<=", $endDate)
        // ->groupBy('category', 'created_at')
        // ->get()->map(function($row){

        //     return array('seriesname' => $row['category'],
        //     'data' => array(
        //             "value" => (int)((int)$row['dwell_time'] / 60)
        //         ));

        // });