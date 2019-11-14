<?php
// https://stackoverflow.com/questions/38459308/how-to-connect-another-database-with-model-in-laravel
class EngagePicknPay extends Eloquent {

    protected $connection = 'hipengage';
    protected $table = 'picknpay';

    public function __construct() {
        $this->connection = \Utils::getEngageDbConnection();
    }

    public static function fetchAllStores($date, $startDate, $endDate, $storeID = null){

        if ($startDate == null && $endDate == null) {

            $dateRange = Picknpay::getDateForPeriodAndTimeOfDay($date);

            $startDate = $dateRange['startDate'];
            $endDate = $dateRange['endDate'];

        }

        // return EngagePicknPay::select(DB::raw("DISTINCT store, store_id"))
        // ->whereraw("DATE_FORMAT(created_at, '%Y-%m-%d') >= '$startDate'")
        // ->whereraw("DATE_FORMAT(created_at, '%Y-%m-%d') <= '$endDate'")
        // ->get();

        if ($storeID != null) {
            return EngagePicknPay::select(DB::raw("DISTINCT store, store_id"))
            ->where("store_id", "=", $storeID)
            ->get();
        }
        else {
            return EngagePicknPay::select(DB::raw("DISTINCT store, store_id"))
            ->get();
        }



    }

}