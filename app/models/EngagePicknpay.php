<?php
// https://stackoverflow.com/questions/38459308/how-to-connect-another-database-with-model-in-laravel
class EngagePicknPay extends Eloquent {

    protected $connection = 'hipengage';
    protected $table = 'picknpay';

    public function __construct() {
        $this->connection = \Utils::getEngageDbConnection();
    }

    public static function fetchAllStores($date, $startDate, $endDate){

        if ($startDate == null && $endDate == null) {

            \Log::info("Hannes FETCH STORES start and end date was nil");

            $dateRange = Picknpay::getDateForPeriodAndTimeOfDay($date);

            $startDate = $dateRange['startDate'];
            $endDate = $dateRange['endDate'];

        }

        // return EngagePicknPay::select(DB::raw("DISTINCT store, store_id"))
        // ->whereraw("DATE_FORMAT(created_at, '%Y-%m-%d') >= '$startDate'")
        // ->whereraw("DATE_FORMAT(created_at, '%Y-%m-%d') <= '$endDate'")
        // ->get();

        return EngagePicknPay::select(DB::raw("DISTINCT store, store_id"))
        ->get();

    }

}