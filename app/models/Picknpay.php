<?php

use \EngagePicknPayCategory;
use \EngagePicknPay;
use \Brand;

class Picknpay extends Eloquent {

    protected $connection = 'hipengage';
    protected $table = 'picknpay';

    public function __construct() {
        $this->connection = \Utils::getEngageDbConnection();
    }

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
        ->where('created_at', ">=", $startDate)
        ->where('created_at', "<=", $endDate)
        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
        ->get();

    }


    public static function test($date, $startDate, $endDate){

        if ($startDate == null && $endDate == null) {

            $dateRange = Picknpay::getDateForPeriodAndTimeOfDay($date);

            $startDate = $dateRange['startDate'];
            $endDate = $dateRange['endDate'];

        }

        $dateRange = Picknpay::getDateForPeriodAndTimeOfDay($date);

        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];

        return $dateRange;

    }

    public static function fetchAllCategories($date, $startDate, $endDate, $categoryID){

        if ($startDate == null && $endDate == null) {

            $dateRange = Picknpay::getDateForPeriodAndTimeOfDay($date);

            $startDate = $dateRange['startDate'];
            $endDate = $dateRange['endDate'];

        }

        if ($categoryID != '' && $categoryID != null) {

            $query = EngagePicknPayCategory::raw("SELECT DISTINCT name FROM pnp_category WHERE DATE_FORMAT(created_at, '%Y-%m-%d') >= '$startDate' AND DATE_FORMAT(created_at, '%Y-%m-%d') <= '$endDate' AND id = '$categoryID'");
            if($categoryID != "" && $categoryID != null && $categoryID != '') {
                $query = $query->where('id', "=", $categoryID);
            }

            return $query->get();

        }
        else {
            return EngagePicknPayCategory::raw("SELECT DISTINCT name FROM pnp_category WHERE DATE_FORMAT(created_at, '%Y-%m-%d') >= '$startDate' AND DATE_FORMAT(created_at, '%Y-%m-%d') <= '$endDate'")->get();
        }



    }

    public static function fetchAllCategoriesForFilter(){
        return EngagePicknPayCategory::raw("SELECT DISTINCT name FROM pnp_category")->get();
    }

    public static function fetchAllStaffForFilter(){
        return EngagePicknPayCategory::raw("SELECT DISTINCT name FROM pnp_staff")->get();
    }

    public static function fetchAllStaff($date, $startDate, $endDate){

        if ($startDate == null && $endDate == null) {

            $dateRange = Picknpay::getDateForPeriodAndTimeOfDay($date);

            $startDate = $dateRange['startDate'];
            $endDate = $dateRange['endDate'];

        }
        //Was like this, change back to this if problems exist
        // return Picknpay::raw("SELECT DISTINCT staff_id FROM picknpay WHERE DATE_FORMAT(created_at, '%Y-%m-%d') >= '$startDate' AND DATE_FORMAT(created_at, '%Y-%m-%d') <= '$endDate'")->get();


        return Picknpay::select(DB::raw("DISTINCT staff_id"))
        ->where('created_at', "<=", $endDate)
        ->where('created_at', ">=", $startDate)
        ->get();

    }

    public static function fetchAllCategoriesWithoutDateFilter(){
        return EngagePicknPayCategory::raw("SELECT DISTINCT name FROM pnp_category")->get();
    }

    public static function fetchDwellTimeDataForCategoryWithDate($date, $categoryId, $storeId, $provinceId, $verb){
        $query = Picknpay::orderBy('created_at', 'ASC')
        ->select(DB::raw("IFNULL($verb(ROUND(CAST(dwell_time AS UNSIGNED)/60)), 0) AS value"))
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

    public static function fetchDwellTimeDataForStaffWithDate($date, $staffID, $storeId, $provinceId, $verb){
        $query = Picknpay::orderBy('created_at', 'ASC')
        ->select(DB::raw("IFNULL($verb(ROUND(CAST(dwell_time AS UNSIGNED)/60)), 0) AS value"))
        ->whereraw("DATE_FORMAT(created_at, '%Y-%m-%d') = '$date'")
        ->whereraw("staff_id = '$staffID'")
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

    public static function fetchDwellTimeDataForStaffWithinAnHour($staffID, $startDate, $endDate){
        $query = Picknpay::orderBy('created_at', 'ASC')
        ->select(DB::raw("IFNULL(SUM(ROUND(CAST(dwell_time AS UNSIGNED)/60)), 0) AS value"))
        ->where('start_time', ">=", $startDate)
        ->where('end_time', "<=", $endDate)
        ->whereraw("staff_id = '$staffID'")
        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
        ->orderBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"));


        return $query->get();

    }

    public static function fetchDwellVisitsForCategoryWithDate($date, $categoryId, $storeId, $provinceId){
        $query = Picknpay::orderBy('created_at', 'ASC')
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
        $query = Picknpay::orderBy('created_at', 'ASC')
        ->select(DB::raw("COUNT(*) AS value"))
        ->whereraw("DATE_FORMAT(created_at, '%Y-%m-%d') = '$date'")
        ->whereraw("store_id = '$storeId'");

        if ($provinceId != ""){
            $query = $query->whereraw("province_id = '$provinceId'");
        }
        return $query->get();
    }

    public static function customerInStoreToday(){

        $dateRange = Picknpay::getDateForPeriodAndTimeOfDay('today');

        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];

        $query = Picknpay::where('created_at', ">=", $startDate)
        ->where('created_at', "<=", $endDate)
        ->groupBy('staff_id')
        ->get();

        return count($query);

    }

    public static function customerInStoreThisMonth(){

        $dateRange = Picknpay::getDateForPeriodAndTimeOfDay('repthismonth');

        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];

        $query = Picknpay::where('created_at', ">=", $startDate)
        ->where('created_at', "<=", $endDate)
        ->groupBy('staff_id')
        ->get();

        return count($query);

    }

    public static function fetchAllStores(){
        $picknpayBrand = \Brand::where('name', '=', 'PicknPay')->firstOrFail();
        return $picknpayBrand->venues()->get();
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

            $start = date('Y-m-d',strtotime('last sunday'));
            $end = date('Y-m-d',strtotime('today'));


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

    public static function getDateForTimeOfDayPerHour($date, $time){

        $returnValue = '';

        if($time == '9AM'){

            $startDate = "$date 08:00.00";
            $endDate = "$date 08:59.59";

            $returnValue['startDate'] = $startDate;
            $returnValue['endDate'] = $endDate;

        } else if($time == '10AM'){

            $startDate = "$date 09:00.00";
            $endDate = "$date 09:59.59";

            $returnValue['startDate'] = $startDate;
            $returnValue['endDate'] = $endDate;

        } else if($time == '11AM'){

            $startDate = "$date 10:00.00";
            $endDate = "$date 10:59.59";

            $returnValue['startDate'] = $startDate;
            $returnValue['endDate'] = $endDate;

        }
        else if($time == '12PM'){

            $startDate = "$date 11:00.00";
            $endDate = "$date 11:59.59";

            $returnValue['startDate'] = $startDate;
            $returnValue['endDate'] = $endDate;

        }
        else if($time == '13PM'){

            $startDate = "$date 12:00.00";
            $endDate = "$date 12:59.59";

            $returnValue['startDate'] = $startDate;
            $returnValue['endDate'] = $endDate;

        }
        else if($time == '14PM'){

            $startDate = "$date 13:00.00";
            $endDate = "$date 13:59.59";

            $returnValue['startDate'] = $startDate;
            $returnValue['endDate'] = $endDate;

        }
        else if($time == '15PM'){

            $startDate = "$date 14:00.00";
            $endDate = "$date 14:59.59";

            $returnValue['startDate'] = $startDate;
            $returnValue['endDate'] = $endDate;

        }
        else if($time == '16PM'){

            $startDate = "$date 15:00.00";
            $endDate = "$date 15:59.59";

            $returnValue['startDate'] = $startDate;
            $returnValue['endDate'] = $endDate;

        }
        else if($time == '17PM'){

            $startDate = "$date 16:00.00";
            $endDate = "$date 16:59.59";

            $returnValue['startDate'] = $startDate;
            $returnValue['endDate'] = $endDate;

        }

        return $returnValue;

    }

}