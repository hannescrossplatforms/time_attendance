<?php

use \EngageBidvestCategory;
use \EngageBidvest;
use \Brand;

class Bidvest extends Eloquent {

    protected $connection = 'hipengage';
    protected $table = 'bidvest';

    public function __construct() {
        $this->connection = \Utils::getEngageDbConnection();
    }

    public static function datesToFetchChartDataFor($date, $startDate, $endDate){

        if ($startDate == null && $endDate == null) {

            $dateRange = Bidvest::getDateForPeriodAndTimeOfDay($date);

            $startDate = $dateRange['startDate'];
            $endDate = $dateRange['endDate'];

        }

        $dateRange = Bidvest::getDateForPeriodAndTimeOfDay($date);

        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];

        return Bidvest::orderBy('created_at', 'ASC')
        ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') AS created_att"))
        ->whereraw("DATE_FORMAT(created_at, '%Y-%m-%d') >= '$startDate'")
        ->whereraw("DATE_FORMAT(created_at, '%Y-%m-%d') <= '$endDate'")
        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
        ->get();
    }

    public static function fetchAllCategories($date, $startDate, $endDate){

        if ($startDate == null && $endDate == null) {

            $dateRange = Bidvest::getDateForPeriodAndTimeOfDay($date);

            $startDate = $dateRange['startDate'];
            $endDate = $dateRange['endDate'];

        }

        return EngageBidvestCategory::raw("SELECT DISTINCT name FROM bidvest_category WHERE DATE_FORMAT(created_at, '%Y-%m-%d') >= '$startDate' AND DATE_FORMAT(created_at, '%Y-%m-%d') <= '$endDate'")->get();

    }

    public static function fetchAllCategoriesWithoutDateFilter(){
        return EngageBidvestCategory::raw("SELECT DISTINCT name FROM bidvest_category")->get();
    }

    public static function fetchDwellTimeDataForCategoryWithDate($date, $categoryId, $storeId, $provinceId, $verb){
        $query = Bidvest::orderBy('created_at', 'ASC')
        ->select(DB::raw("IFNULL($verb(CAST(dwell_time AS UNSIGNED)), 0) AS value"))
        ->whereraw("DATE_FORMAT(created_at, '%Y-%m-%d') = '$date'")
        ->whereraw("category_id = '$categoryId'")
        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
        ->orderBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"));

        if($storeId != "") {
            $query = $query->whereraw("store_id = '$storeId'");
        }
        if($provinceId != "") {
            $query = $query->whereraw("province_id = '$provinceId'");
        }
        return $query->get();

    }

    public static function fetchDwellVisitsForCategoryWithDate($date, $categoryId, $storeId, $provinceId){
        $query = Bidvest::orderBy('created_at', 'ASC')
        ->select(DB::raw("COUNT(*) AS value"))
        ->whereraw("DATE_FORMAT(created_at, '%Y-%m-%d') = '$date'")
        ->whereraw("category_id = '$categoryId'");

        if($storeId != "") {
            $query = $query->whereraw("store_id = '$storeId'");
        }
        if($provinceId != "") {
            $query = $query->whereraw("province_id = '$provinceId'");
        }
        return $query->get();

    }

    public static function fetchDwellVisitsForStoreWithDate($date, $storeId, $provinceId){
        $query = Bidvest::orderBy('created_at', 'ASC')
        ->select(DB::raw("COUNT(*) AS value"))
        ->whereraw("DATE_FORMAT(created_at, '%Y-%m-%d') = '$date'")
        ->whereraw("store_id = '$storeId'");

        if ($provinceId != ""){
            $query = $query->whereraw("province_id = '$provinceId'");
        }
        return $query->get();
    }

    public static function customerInStoreToday(){

        $dateRange = Bidvest::getDateForPeriodAndTimeOfDay('today');

        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];

        //TODO: Where date is today && group by customer uuid(maybe device uuid or something)
        return Bidvest::where('created_at', ">=", $startDate)
        ->where('created_at', "<=", $endDate)
        ->count();

    }

    public static function customerInStoreThisMonth(){

        $dateRange = Bidvest::getDateForPeriodAndTimeOfDay('repthismonth');

        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];

        //TODO: Where date is this month && group by customer uuid(maybe device uuid or something)
        return Bidvest::where('created_at', ">=", $startDate)
        ->where('created_at', "<=", $endDate)
        ->count();

    }

    public static function fetchAllStores(){
        $bidvestBrand = \Brand::where('name', '=', 'Bidvest')->firstOrFail();
        return $bidvestBrand->venues()->get();
    }

    public static function fetchStoreForVenue($venue){
        return $province = \Province::find($venue->province_id);
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


            $returnValue['startDate'] = "$start 00.00.00";
            $returnValue['endDate'] = "$end 23:59:59";
            $returnValue['period'] = "week";

        }else if($period == 'repthismonth'){

            $start = date('Y-m-d',strtotime('first day of this month'));
            $end = date('Y-m-d',strtotime('today'));

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