<?php
// https://stackoverflow.com/questions/38459308/how-to-connect-another-database-with-model-in-laravel
class EngageBidvest extends Eloquent {

    protected $connection = 'hipengage';
    protected $table = 'bidvest';

    public function __construct() {
        $this->connection = \Utils::getEngageDbConnection();
    }

    public static function fetchAllStores($date, $startDate, $endDate){

        if ($startDate == null && $endDate == null) {

            $dateRange = Bidvest::getDateForPeriodAndTimeOfDay($date);

            $startDate = $dateRange['startDate'];
            $endDate = $dateRange['endDate'];

        }


        return EngageBidvest::select(DB::raw("DISTINCT store, store_id"))
        ->whereraw("DATE_FORMAT(created_at, '%Y-%m-%d') >= '$startDate'")
        ->whereraw("DATE_FORMAT(created_at, '%Y-%m-%d') <= '$endDate'")
        ->get();

    }

}