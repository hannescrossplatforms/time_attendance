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

    public static function fetchAllCategories($date, $startDate, $endDate, $categoryID, $storeID){

        if ($startDate == null && $endDate == null) {

            $dateRange = Picknpay::getDateForPeriodAndTimeOfDay($date);

            $startDate = $dateRange['startDate'];
            $endDate = $dateRange['endDate'];

        }
        if ($categoryID != '' && $categoryID != null && $storeID != '' && $storeID != null) {

            $query = EngagePicknPayCategory::raw("SELECT DISTINCT name FROM pnp_category WHERE DATE_FORMAT(created_at, '%Y-%m-%d') >= '$startDate' AND DATE_FORMAT(created_at, '%Y-%m-%d') <= '$endDate' AND id = '$categoryID' AND store_id = '$storeID'");
            if($categoryID != "" && $categoryID != null && $categoryID != '') {
                $query = $query->where('id', "=", $categoryID);
            }

            return $query->get();

        }
        else if ($categoryID != '' && $categoryID != null) {

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

    public static function categoriesForStore($storeId) {

        // $query = EngagePicknPayCategory::raw("SELECT DISTINCT name FROM pnp_category WHERE store_id = '$storeId'");
        // return $query->get();

        return EngagePicknPayCategory::raw("SELECT DISTINCT name FROM pnp_category")
        ->where('store_id', "=", $storeId)->get();

    }

    public static function storesForProvince($provinceId) {
        return \Venue::where('province_id', "=", $provinceId)->get();
    }

    public static function fetchAllCategoriesForFilter(){
        return EngagePicknPayCategory::raw("SELECT DISTINCT name FROM pnp_category")->get();
    }

    public static function fetchAllCategoriesForStaffActivity($staffID, $date) {
        return EngagePicknPay::raw("SELECT DISTINCT category_id FROM picknpay")
        ->where('staff_id', "=", $staffID)
        ->whereraw("DATE_FORMAT(end_time, '%Y-%m-%d') = '$date'")
        ->groupBy('category_id')
        ->get();

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
        ->where('end_time', "<=", $endDate)
        ->where('end_time', ">=", $startDate)
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
        ->where('end_time', ">=", $startDate)
        ->where('end_time', "<=", $endDate)
        ->whereraw("staff_id = '$staffID'")
        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
        ->orderBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"));


        return $query->get();

    }

    public static function fetchDwellTimeDataForCategoryWithinAnHour($staffID, $startDate, $endDate){
        $query = Picknpay::orderBy('created_at', 'ASC')
        ->select(DB::raw("IFNULL(SUM(ROUND(CAST(dwell_time AS UNSIGNED)/60)), 0) AS value"))
        ->where('end_time', ">=", $startDate)
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

    public static function getDateForTimeOfDayPerHour($date, $time, $startOrEnd){

        if ($time == 'allDay') {

            if ($startOrEnd == 'start') {
                return "$date 08:00:00";
            }
            else {
                return "$date 17:00:00";
            }

        }
        else if($time == '8AM'){

            if($startOrEnd == 'start') {
                return "$date 08:00:00";
            }
            else {
                return "$date 08:59:59";
            }

        } else if($time == '9AM'){

            if($startOrEnd == 'start') {
                return "$date 09:00:00";
            }
            else {
                return "$date 09:59:59";
            }

        } else if($time == '10AM'){

            if($startOrEnd == 'start') {
                return "$date 10:00:00";
            }
            else {
                return "$date 10:59:59";
            }

        }
        else if($time == '11AM'){

            if($startOrEnd == 'start') {
                return "$date 10:00:00";
            }
            else {
                return "$date 11:00:00";
            }

        }
        else if($time == '12PM'){

            if($startOrEnd == 'start') {
                return "$date 12:00:00";
            }
            else {
                return "$date 12:59:59";
            }

        }
        else if($time == '13PM'){

            if($startOrEnd == 'start') {
                return "$date 13:00:00";
            }
            else {
                return "$date 13:59:59";
            }

        }
        else if($time == '14PM'){

            if($startOrEnd == 'start') {
                return "$date 14:00:00";
            }
            else {
                return "$date 14:59:59";
            }

        }
        else if($time == '15PM'){

            if($startOrEnd == 'start') {
                return "$date 15:00:00";
            }
            else {
                return "$date 15:59:59";
            }

        }
        else if($time == '16PM'){

            if($startOrEnd == 'start') {
                return "$date 16:00:00";
            }
            else {
                return "$date 17:00:00";
            }

        }

    }

}