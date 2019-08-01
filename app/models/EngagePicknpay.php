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

            $dateRange = Picknpay::getDateForPeriodAndTimeOfDay($date);

            $startDate = $dateRange['startDate'];
            $endDate = $dateRange['endDate'];

        }
asdfasdf
        return EngagePicknPay::raw("SELECT DISTINCT store, store_id FROM picknpay WHERE DATE_FORMAT(created_at, '%Y-%m-%d') >= '$startDate' AND DATE_FORMAT(created_at, '%Y-%m-%d') <= '$endDate' GROUP BY store, store_id")->get();

    }

}