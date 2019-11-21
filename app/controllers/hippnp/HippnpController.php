<?php

namespace hippnp;
use DB;
use Input;
use Validator;
use DateTime;
use Illuminate\Support\Facades\Redirect;
use Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Session;
use Route;
use \EngagePicknPayBeacon;

class HippnpController extends \BaseController {

	public static function showDashboard(){

        $period = 'today';
        $data = array() ;
        $data['currentMenuItem'] = "Dashboard";
        $data['report_period'] = 'rep7day';
        $data['url'] = 'http://' . $_SERVER['SERVER_NAME'].'/';
        $data['customer_in_store_today'] = \Picknpay::customerInStoreToday();
        $data['customer_in_store_this_month'] = \Picknpay::customerInStoreThisMonth();


        /////////


        $finalChartObjectStaff = array();

        $allStaff = \Picknpay::fetchAllStaff($period, null, null, null);
        $datesForAllStaff = \Picknpay::datesToFetchChartDataFor($period, null, null, null)
            ->map(function($row) {
                    return ['label' => $row['created_att']];
            });

        $data['all_staff'] = $allStaff;
        $data['staff_list'] = $datesForAllStaff; //////////TOP ONE

        foreach ($allStaff as $staff) {

            $staffObj = \EngagePicknPayStaff::getStaffWithID($staff->staff_id);
            $stafId = $staffObj->id;
            $staffName = $staffObj->name;
            $dataArray = array();

            foreach ( $datesForAllStaff as $date ) {

                $response = \Picknpay::fetchDwellTimeDataForStaffWithDate($date['label'], $stafId, "", "", "SUM");
                if (count($response) == 0) {
                    $empty_array = array(['value' => '0']);
                    array_push($dataArray, $empty_array);
                } else {
                    array_push($dataArray, $response);
                }

            }

            $obj[] = [
                'seriesname' => $staffName,
                'data' => $dataArray
            ];

            array_push($finalChartObjectStaff, $obj);

        };

        if (count($finalChartObjectStaff) > 0) {
            $data['staff_list_data'] = json_encode($finalChartObjectStaff[count($finalChartObjectStaff)- 1]);
        }
        else {
            $data['staff_list_data'] = json_encode([]); ////DATASET
        }

        $obj = null;


        /////////



        //Get all categories for charts to render

        $allCategories = \Picknpay::fetchAllCategories($period, null, null, null, null);
        $allStores = \Picknpay::fetchAllStores($period, null, null, null);
        $allCategoriesForFilter = \Picknpay::fetchAllCategoriesForFilter(null);
        $data['all_categories_for_filter'] = $allCategoriesForFilter;

        $allProvinces = array();
        foreach ($allStores as $store) {
            $province = \Picknpay::fetchStoreForVenue($store);

            $hasVal = false;
            foreach ($allProvinces as $prov) {
                if($prov->name == $province->name) {
                    $hasVal = true;
                }
            }

            if ($hasVal == false) {
                array_push($allProvinces, $province);
            }


        }

        $data['all_categories'] = $allCategories;
        $data['all_stores'] = $allStores;
        $data['all_provinces'] = $allProvinces;


        $dates = \Picknpay::datesToFetchChartDataFor($period, null, null, null)
        ->map(function($row) {
                return ['label' => $row['created_att']];
            });

        $data['category_list'] = $dates;

        // Sum of all categories

        $finalChartObject = array();

        foreach ($allCategories as $category) {

            $categoryName = \EngagePicknPayCategory::getCategoryWithID($category->id);
            $categoryId = $category->id;

            $dataArray = array();

            foreach ( $dates as $date ) {
                $response = \Picknpay::fetchDwellTimeDataForCategoryWithDate($date['label'], $categoryId, "", "", "SUM");
                if (count($response) == 0) {
                    $empty_array = array(['value' => '0']);
                    array_push($dataArray, $empty_array);
                } else {
                    array_push($dataArray, $response);
                }

            }

            $obj[] = [
                'seriesname' => $categoryName,
                'data' => $dataArray
            ];
            array_push($finalChartObject, $obj);
        };

        if (count($finalChartObject) > 0) {
            $data['category_list_data'] = json_encode($finalChartObject[count($finalChartObject)- 1]);
        }
        else {
            $data['category_list_data'] = json_encode([]);
        }
        $obj = null;

        // Average of all categories

        $finalAverageChartObject = array();

        foreach ($allCategories as $category) {

            $categoryName = \EngagePicknPayCategory::getCategoryWithID($category->id);
            $categoryId = $category->id;

            $dataArrayAvg = array();

            foreach ( $dates as $date ) {
                $response = \Picknpay::fetchDwellTimeDataForCategoryWithDate($date['label'], $categoryId, "", "", "AVG");
                if (count($response) == 0) {
                    $empty_array = array(['value' => '0']);
                    array_push($dataArrayAvg, $empty_array);
                } else {
                    array_push($dataArrayAvg, $response);
                }

            }

            $obj[] = [
                'seriesname' => $categoryName,
                'data' => $dataArrayAvg
            ];
            array_push($finalAverageChartObject, $obj);
        };

        if (count($finalAverageChartObject) > 0) {
            $data['category_list_data_average'] = json_encode($finalAverageChartObject[count($finalAverageChartObject)- 1]);
        }
        else {
            $data['category_list_data_average'] = json_encode([]);
        }
        $obj = null;


        //Number of visits per category

        $finalVisitsChartObject = array();

        foreach ($allCategories as $category) {
            $categoryName = \EngagePicknPayCategory::getCategoryWithID($category->id);
            $categoryId = $category->id;
            $dataArrayVisits = array();

            foreach ( $dates as $date ) {
                $response = \Picknpay::fetchDwellVisitsForCategoryWithDate($date['label'], $categoryId, "", "");
                if (count($response) == 0) {
                    $empty_array = array(['value' => '0']);
                    array_push($dataArrayVisits, $empty_array);
                } else {
                    array_push($dataArrayVisits, $response);
                }

            }

            $obj[] = [
                'seriesname' => $categoryName,
                'data' => $dataArrayVisits
            ];
            array_push($finalVisitsChartObject, $obj);
        };

        if (count($finalVisitsChartObject) > 0) {
            $data['category_list_data_visits'] = json_encode($finalVisitsChartObject[count($finalVisitsChartObject)- 1]);
        }
        else {
            $data['category_list_data_visits'] = json_encode([]);
        }
        $obj = null;


        //Number of visits per store


        $finalVisitsStoreChartObject = array();
        $allStores = \EngagePicknpay::fetchAllStores($period, null, null, null);

        foreach ($allStores as $store) {

            $storeName = $store->store;
            $storeId = $store->store_id;
            $dataArrayVisitsStore = array();

            foreach ( $dates as $date ) {

                $response = \Picknpay::fetchDwellVisitsForStoreWithDate($date['label'], $storeId, "");
                if (count($response) == 0) {

                    $empty_array = array(['value' => '0']);
                    array_push($dataArrayVisitsStore, $empty_array);
                } else {

                    array_push($dataArrayVisitsStore, $response);
                }

            }
            $obj[] = [
                'seriesname' => $storeName,
                'data' => $dataArrayVisitsStore
            ];
            array_push($finalVisitsStoreChartObject, $obj);
        };

        if (count($finalVisitsStoreChartObject) > 0) {
            $data['category_list_data_visits_store'] = json_encode($finalVisitsStoreChartObject[count($finalVisitsStoreChartObject)- 1]);
        }
        else {
            $data['category_list_data_visits_store'] = json_encode([]);
        }

        $obj = null;

        //Tests:
        $startd = date('Y-m-d',strtotime('last sunday'));
        $endd = date('Y-m-d',strtotime('today'));


        $data['testStart'] = "$startd 00.00.00";
        $data['testEnd'] = "$endd 23:59:59";

        //



        return \View::make('hippnp.showdashboard')->with('data', $data);

    }

    public function periodchartJsonDataStaffAjax(){

        $period = 'today';
        $data = array() ;
        $data['currentMenuItem'] = "Dashboard";
        $data['report_period'] = 'today';
        $data['url'] = 'http://' . $_SERVER['SERVER_NAME'].'/';
        $dateSelected = Input::get('date');

        $finalChartObjectStaff = array();

        $timeList = array();

        array_push($timeList, ['label' => "9AM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '8AM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '8AM', 'end')]);
        array_push($timeList, ['label' => "10AM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '9AM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '9AM', 'end')]);
        array_push($timeList, ['label' => "11AM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '10AM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '10AM', 'end')]);
        array_push($timeList, ['label' => "12PM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '11AM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '11AM', 'end')]);
        array_push($timeList, ['label' => "13PM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '12PM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '12PM', 'end')]);
        array_push($timeList, ['label' => "14PM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '13PM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '13PM', 'end')]);
        array_push($timeList, ['label' => "15PM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '14PM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '14PM', 'end')]);
        array_push($timeList, ['label' => "16PM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '15PM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '15PM', 'end')]);
        array_push($timeList, ['label' => "17PM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '16PM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '16PM', 'end')]);

        $data['time_list'] = $timeList;

        //All staff memebers present in database for selected period.

        $startDate = \Picknpay::getDateForTimeOfDayPerHour($dateSelected, 'allDay', 'start');
        $endDate = \Picknpay::getDateForTimeOfDayPerHour($dateSelected, 'allDay', 'end');
        $allStaff = \Picknpay::fetchAllStaff('today', $startDate, $endDate, null);

        \Log::info("Hannes KOM HIER 0");
        foreach ($allStaff as $staff) {

            //Get staff memeber with all his details.
            $staffObj = \EngagePicknPayStaff::getStaffWithID($staff->staff_id);
            $stafId = $staff->staff_id;
            $staffName = $staffObj->name;

            $dataArray = array();

            \Log::info("Hannes KOM HIER");

            foreach ( $timeList as $timeObject ) {

                $startTime = $timeObject['startDate'];
                $endTime = $timeObject['endDate'];

                \Log::info("Hannes logs: start time = $startTime");
                \Log::info("Hannes logs: end time = $endTime");

                $response = \Picknpay::fetchDwellTimeDataForStaffWithinAnHour($stafId, $startTime, $endTime);
                if (count($response) == 0) {
                    $empty_array = array(['value' => '0', 'id' => $stafId]);
                    array_push($dataArray, $empty_array);
                } else {
                    \Log::info("Hannes NOU NOU NOU  HIER");

                    $eightAmStartDateString = "$startTime 08:00:00";

                    $dwellTime = $response->first()->value;

                    $recordStartTime = $response->first()->start_time;


                    // $eightAmStartDate = "16/10/2013";
                    $eightAmStartDate = \DateTime::createFromFormat("yyyy-MM-dd HH:mm:SS", $eightAmStartDateString);

                    \Log::info("Hannes eightAmStartDate IS $eightAmStartDate");
                    \Log::info("Hannes THE ID IS $test");
                    \Log::info("Hannes THE VALUE IS $vaaaaaa");
                    \Log::info("Hannes STD IS $startDate");
                    // $eightAmStart  = new Carbon('2018-10-04 15:00:03');

                    $objectArr = array(['value' => $response->first()->value, 'id' => $stafId]);
                    array_push($dataArray, $objectArr);
                }

            }

            $obj[] = [
                'seriesname' => $staffName,
                'data' => $dataArray
            ];

            array_push($finalChartObjectStaff, $obj);

        };

        if (count($finalChartObjectStaff) > 0) {
            $data['time_list_data'] = $finalChartObjectStaff[count($finalChartObjectStaff)- 1];
        }
        else {
            $data['time_list_data'] = [];
        }

        $obj = null;

        $json = json_encode($data);

        print_r($json);

    }

    public function getStoresForProvince($id) {

        $data = array();
        $data["all_stores_for_province"] = \Picknpay::storesForProvince($id);
        return \View::make('hippnp.stores_dropdown')->with('data', $data);
    }

    public function getCategoriesForStore($id){

        $data = array();
        $data["all_categories_for_filter"] = \Picknpay::categoriesForStore($id);
        return \View::make('hippnp.categories_for_store_dropdown')->with('data', $data);

    }

    public function getCategoriesForStoreBeacon($id){

        $data = array();
        $data["all_categories_for_filter"] = \Picknpay::categoriesForStore($id);
        return \View::make('hippnp.categories_for_store_beacon')->with('data', $data);

    }

    public function staffCategoryActivity(){

        $dateSelected = Input::get('date');
        $staffId = Input::get('staff_id');

        $period = 'today';
        $data = array() ;
        $data['currentMenuItem'] = "Dashboard";
        $data['report_period'] = 'today';
        $data['url'] = 'http://' . $_SERVER['SERVER_NAME'].'/';

        $finalChartObjectCategories = array();

        $timeList = array();

        array_push($timeList, ['label' => "9AM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '8AM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '8AM', 'end')]);
        array_push($timeList, ['label' => "10AM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '9AM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '9AM', 'end')]);
        array_push($timeList, ['label' => "11AM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '10AM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '10AM', 'end')]);
        array_push($timeList, ['label' => "12PM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '11AM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '11AM', 'end')]);
        array_push($timeList, ['label' => "13PM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '12PM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '12PM', 'end')]);
        array_push($timeList, ['label' => "14PM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '13PM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '13PM', 'end')]);
        array_push($timeList, ['label' => "15PM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '14PM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '14PM', 'end')]);
        array_push($timeList, ['label' => "16PM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '15PM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '15PM', 'end')]);
        array_push($timeList, ['label' => "17PM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '16PM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '16PM', 'end')]);

        $data['time_list'] = $timeList;

        $startDate = \Picknpay::getDateForTimeOfDayPerHour($dateSelected, 'allDay', 'start');
        $endDate = \Picknpay::getDateForTimeOfDayPerHour($dateSelected, 'allDay', 'end');
        $allStaff = \EngagePicknPayStaff::getStaffAsArrayWithID($staffId);


        $allCategoryIdsForFilter = \Picknpay::fetchAllCategoriesForStaffActivity($staffId, $dateSelected);
        $allCategories = array();

        $data['test'] = $allCategoryIdsForFilter;

        foreach ($allCategoryIdsForFilter as $categoryID) {
            $category = \EngagePicknPayCategory::find($categoryID->category_id);
            array_push($allCategories, $category);
        }

        foreach ($allCategories as $category) {

            //Get staff memeber with all his details.
            // $staffObj = \EngagePicknPayStaff::getStaffWithID($staffId);
            // $staffName = $staffObj->name;

            $dataArray = array();

            foreach ( $timeList as $timeObject ) {

                $startTime = $timeObject['startDate'];
                $endTime = $timeObject['endDate'];

                $response = \Picknpay::fetchDwellTimeDataForStaffWithinAnHour($staffId, $startTime, $endTime);
                if (count($response) == 0) {
                    $empty_array = array(['value' => '0', 'id' => $staffId]);
                    array_push($dataArray, $empty_array);
                } else {
                    $objectArr = array(['value' => $response->first()->value, 'id' => $staffId]);
                    array_push($dataArray, $objectArr);
                }

            }

            $obj[] = [
                'seriesname' => $category->name,
                'data' => $dataArray
            ];

            array_push($finalChartObjectCategories, $obj);

        };


        if (count($finalChartObjectCategories) > 0) {
            $data['time_list_data'] = $finalChartObjectCategories[count($finalChartObjectCategories)- 1];
        }
        else {
            $data['time_list_data'] = [];
        }

        $obj = null;

        $json = json_encode($data);

        print_r($json);

    }

    public function periodchartJsondataSingleStaffAjax(){

        $dateSelected = Input::get('date');
        $staffId = Input::get('staff_id');

        $period = 'today';
        $data = array() ;
        $data['currentMenuItem'] = "Dashboard";
        $data['report_period'] = 'today';
        $data['url'] = 'http://' . $_SERVER['SERVER_NAME'].'/';

        $finalChartObjectStaff = array();

        $timeList = array();

        array_push($timeList, ['label' => "9AM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '8AM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '8AM', 'end')]);
        array_push($timeList, ['label' => "10AM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '9AM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '9AM', 'end')]);
        array_push($timeList, ['label' => "11AM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '10AM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '10AM', 'end')]);
        array_push($timeList, ['label' => "12PM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '11AM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '11AM', 'end')]);
        array_push($timeList, ['label' => "13PM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '12PM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '12PM', 'end')]);
        array_push($timeList, ['label' => "14PM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '13PM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '13PM', 'end')]);
        array_push($timeList, ['label' => "15PM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '14PM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '14PM', 'end')]);
        array_push($timeList, ['label' => "16PM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '15PM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '15PM', 'end')]);
        array_push($timeList, ['label' => "17PM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '16PM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '16PM', 'end')]);

        $data['time_list'] = $timeList;

        //All staff memebers present in database for selected period.

        $startDate = \Picknpay::getDateForTimeOfDayPerHour($dateSelected, 'allDay', 'start');
        $endDate = \Picknpay::getDateForTimeOfDayPerHour($dateSelected, 'allDay', 'end');
        $allStaff = \EngagePicknPayStaff::getStaffAsArrayWithID($staffId);


        foreach ($allStaff as $staff) {

            //Get staff memeber with all his details.
            $staffObj = \EngagePicknPayStaff::getStaffWithID($staff->id);
            $stafId = $staff->id;
            $staffName = $staffObj->name;

            $dataArray = array();

            foreach ( $timeList as $timeObject ) {

                $startTime = $timeObject['startDate'];
                $endTime = $timeObject['endDate'];

                $response = \Picknpay::fetchDwellTimeDataForStaffWithinAnHour($stafId, $startTime, $endTime);
                if (count($response) == 0) {
                    $empty_array = array(['value' => '0', 'id' => $stafId]);
                    array_push($dataArray, $empty_array);
                } else {
                    $objectArr = array(['value' => $response->first()->value, 'id' => $stafId]);
                    array_push($dataArray, $objectArr);
                }

            }

            $obj[] = [
                'seriesname' => $staffName,
                'data' => $dataArray
            ];

            array_push($finalChartObjectStaff, $obj);

        };

        if (count($finalChartObjectStaff) > 0) {
            $data['time_list_data'] = $finalChartObjectStaff[count($finalChartObjectStaff)- 1];
        }
        else {
            $data['time_list_data'] = [];
        }

        $obj = null;

        $json = json_encode($data);

        print_r($json);


    }

    public function periodchartJsondataStaff(){
        $period = 'today';
        $data = array() ;
        $data['currentMenuItem'] = "Dashboard";
        $data['report_period'] = 'today';
        $data['url'] = 'http://' . $_SERVER['SERVER_NAME'].'/';

        $data['staff'] = \EngagePicknPayStaff::getAllStaff();

        $finalChartObjectStaff = array();

        $timeList = array();

        $dateSelected = date('Y-m-d',strtotime('today'));

        array_push($timeList, ['label' => "9AM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '8AM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '8AM', 'end')]);
        array_push($timeList, ['label' => "10AM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '9AM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '9AM', 'end')]);
        array_push($timeList, ['label' => "11AM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '10AM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '10AM', 'end')]);
        array_push($timeList, ['label' => "12PM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '11AM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '11AM', 'end')]);
        array_push($timeList, ['label' => "13PM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '12PM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '12PM', 'end')]);
        array_push($timeList, ['label' => "14PM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '13PM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '13PM', 'end')]);
        array_push($timeList, ['label' => "15PM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '14PM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '14PM', 'end')]);
        array_push($timeList, ['label' => "16PM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '15PM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '15PM', 'end')]);
        array_push($timeList, ['label' => "17PM", 'startDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '16PM', 'start'), 'endDate' => \Picknpay::getDateForTimeOfDayPerHour($dateSelected, '16PM', 'end')]);

        $data['time_list'] = json_encode($timeList);

        //All staff memebers present in database for selected period.

        $startDate = \Picknpay::getDateForTimeOfDayPerHour($dateSelected, 'allDay', 'start');
        $endDate = \Picknpay::getDateForTimeOfDayPerHour($dateSelected, 'allDay', 'end');
        $allStaff = \Picknpay::fetchAllStaff('today', $startDate, $endDate, null);


        foreach ($allStaff as $staff) {




            //Get staff memeber with all his details.
            $staffObj = \EngagePicknPayStaff::getStaffWithID($staff->staff_id);
            $stafId = $staff->staff_id;
            $staffName = $staffObj->name;

            $dataArray = array();

            foreach ( $timeList as $timeObject ) {

                $startTime = $timeObject['startDate'];
                $endTime = $timeObject['endDate'];

                $data["test1"] = $startTime;
                $data["test2"] = $endTime;

                $response = \Picknpay::fetchDwellTimeDataForStaffWithinAnHour($stafId, $startTime, $endTime);
                if (count($response) == 0) {
                    $empty_array = array(['value' => '0', 'id' => $stafId]);
                    array_push($dataArray, $empty_array);
                } else {
                    $objectArr = array(['value' => $response->first()->value, 'id' => $stafId]);
                    array_push($dataArray, $objectArr);
                }

            }

            $obj[] = [
                'seriesname' => $staffName,
                'data' => $dataArray
            ];

            array_push($finalChartObjectStaff, $obj);

        };

        if (count($finalChartObjectStaff) > 0) {
            $data['time_list_data'] = json_encode($finalChartObjectStaff[count($finalChartObjectStaff)- 1]);
        }
        else {
            $data['time_list_data'] = json_encode([]);
        }

        $obj = null;

        return \View::make('hippnp.showstaffdata')->with('data', $data);

    }

    public function periodchartJsondata(){

        $data = array();

        $period = Input::get('period');
        $start = Input::get('start');
        $end = Input::get('end');

        $categoryId = Input::get('category_id');
        $storeId = Input::get('store_id');
        $provinceId = Input::get('province_id');

        $allCategoriesForFilter = \Picknpay::fetchAllCategoriesForFilter($storeId);
        $data['all_categories_for_filter'] = $allCategoriesForFilter;


        if ($start != null && $end != null){
            $allCategories = \Picknpay::fetchAllCategories($period, $start, $end, $categoryId, $storeId);
            $dates = \Picknpay::datesToFetchChartDataFor($period, $start, $end, $storeId)
            ->map(function($row) {
                    return ['label' => $row['created_att']];
                });
        }
        else {
            $allCategories = \Picknpay::fetchAllCategories($period, $start, $start, $categoryId, $storeId);
            $dates = \Picknpay::datesToFetchChartDataFor($period, $start, $start, $storeId)
            ->map(function($row) {
                    return ['label' => $row['created_att']];
            });
        }



        $data['all_categories'] = $allCategories;
        $data['category_list'] = $dates;




        ////////////////////

        // Sum of all categories

        $finalChartObjectStaff = array();

        $allStaff = \Picknpay::fetchAllStaff($period, $start, $end, $storeId);
        $staffCount = count($allStaff);

        $datesForAllStaff = \Picknpay::datesToFetchChartDataFor($period, $start, $start, $storeId)
            ->map(function($row) {
                    return ['label' => $row['created_att']];
            });


        $data['all_staff'] = $allStaff;
        $data['staff_list'] = $datesForAllStaff; //////////TOP ONE


        foreach ($allStaff as $staff) {


            $staffObj = \EngagePicknPayStaff::getStaffWithID($staff->staff_id);
            $stafId = $staffObj->id;
            $staffName = $staffObj->name;
            $dataArray = array();



            foreach ( $datesForAllStaff as $date ) {


                $response = \Picknpay::fetchDwellTimeDataForStaffWithDate($date['label'], $stafId, $storeId, $provinceId, "SUM");
                if (count($response) == 0) {

                    $empty_array = array(['value' => '0', 'id' => $stafId]);
                    array_push($dataArray, $empty_array);
                } else {

                    $objectArr = array(['value' => $response->first()->value, 'id' => $stafId]);
                    array_push($dataArray, $objectArr);
                }

            }

            $obj[] = [
                'seriesname' => $staffName,
                'data' => $dataArray
            ];

            array_push($finalChartObjectStaff, $obj);

        };

        if (count($finalChartObjectStaff) > 0) {
            $data['staff_list_data'] = $finalChartObjectStaff[count($finalChartObjectStaff)- 1];
        }
        else {
            $data['staff_list_data'] = []; ////DATASET
        }

        $obj = null;


        /////////////////





        // Sum of all categories

        $finalChartObject = array();

        foreach ($allCategories as $category) {
            $categoryName = \EngagePicknPayCategory::getCategoryWithID($category->id);
            $categoryId = $category->id;
            $dataArray = array();

            foreach ( $dates as $date ) {
                $response = \Picknpay::fetchDwellTimeDataForCategoryWithDate($date['label'], $categoryId, $storeId, $provinceId, "SUM");
                if (count($response) == 0) {
                    $empty_array = array(['value' => '0']);
                    array_push($dataArray, $empty_array);
                } else {
                    array_push($dataArray, $response);
                }

            }

            $obj[] = [
                'seriesname' => $categoryName,
                'data' => $dataArray
            ];
            array_push($finalChartObject, $obj);
        };

        if (count($finalChartObject) > 0) {
            $data['category_list_data'] = $finalChartObject[count($finalChartObject)- 1];
        }
        else {
            $data['category_list_data'] = [];
        }

        $obj = null;

        // Average of all categories

        $finalAverageChartObject = array();

        foreach ($allCategories as $category) {
            $categoryName = \EngagePicknPayCategory::getCategoryWithID($category->id);
            $categoryId = $category->id;
            $dataArrayAvg = array();

            foreach ( $dates as $date ) {
                $response = \Picknpay::fetchDwellTimeDataForCategoryWithDate($date['label'], $categoryId, $storeId, $provinceId, "AVG");
                if (count($response) == 0) {
                    $empty_array = array(['value' => '0']);
                    array_push($dataArrayAvg, $empty_array);
                } else {
                    array_push($dataArrayAvg, $response);
                }

            }

            $obj[] = [
                'seriesname' => $categoryName,
                'data' => $dataArrayAvg
            ];
            array_push($finalAverageChartObject, $obj);
        };

        if (count($finalAverageChartObject) > 0) {
            $data['category_list_data_average'] = $finalAverageChartObject[count($finalAverageChartObject)- 1];
        }
        else {
            $data['category_list_data_average'] = [];
        }

        $obj = null;

        //Number of visits per category

        $finalVisitsChartObject = array();

        foreach ($allCategories as $category) {
            $categoryName = \EngagePicknPayCategory::getCategoryWithID($category->id);
            $categoryId = $category->id;
            $dataArrayVisits = array();

            foreach ( $dates as $date ) {
                $response = \Picknpay::fetchDwellVisitsForCategoryWithDate($date['label'], $categoryId, $storeId, $provinceId);
                if (count($response) == 0) {
                    $empty_array = array(['value' => '0']);
                    array_push($dataArrayVisits, $empty_array);
                } else {
                    array_push($dataArrayVisits, $response);
                }

            }

            $obj[] = [
                'seriesname' => $categoryName,
                'data' => $dataArrayVisits
            ];
            array_push($finalVisitsChartObject, $obj);
        };

        if (count($finalVisitsChartObject) > 0) {
            $data['category_list_data_visits'] = $finalVisitsChartObject[count($finalVisitsChartObject)- 1];
        }
        else {
            $data['category_list_data_visits'] = [];
        }
        $obj = null;

        //Number of visits per store

        $finalVisitsStoreChartObject = array();
        $allStores = \EngagePicknpay::fetchAllStores($period, null, null, $storeId);

        foreach ($allStores as $store) {
            $storeName = $store->store;
            $storeId = $store->store_id;
            $dataArrayVisitsStore = array();

            foreach ( $dates as $date ) {
                $response = \Picknpay::fetchDwellVisitsForStoreWithDate($date['label'], $storeId, $provinceId);
                if (count($response) == 0) {
                    $empty_array = array(['value' => '0']);
                    array_push($dataArrayVisitsStore, $empty_array);
                } else {
                    array_push($dataArrayVisitsStore, $response);
                }

            }

            $obj[] = [
                'seriesname' => $storeName,
                'data' => $dataArrayVisitsStore
            ];
            array_push($finalVisitsStoreChartObject, $obj);
        };

        if (count($finalVisitsStoreChartObject) > 0) {
            $data['category_list_data_visits_store'] = $finalVisitsStoreChartObject[count($finalVisitsStoreChartObject)- 1];
        }
        else {
            $data['category_list_data_visits_store'] = [];
        }

        $obj = null;


        //Tests:
        $startd = date('Y-m-d',strtotime('last sunday'));
        $endd = date('Y-m-d',strtotime('today'));


        $data['testStart'] = "$startd 00.00.00";
        $data['testEnd'] = "$endd 23:59:59";
        $data['testPeriod'] = $period;

        //

        $json = json_encode($data);

        print_r($json);

    }

    public static function picknpayCategoryManagement(){

        $picknpayBrand = \Brand::where('name', '=', 'PicknPay')->firstOrFail();
        $venues = $picknpayBrand->venues()->get();

        $data = array() ;
        $data['currentMenuItem'] = "Dashboard";
        $data['url'] = 'http://' . $_SERVER['SERVER_NAME'].'/';
        $data['venues'] = $venues;
        $data['brand'] = $picknpayBrand;

        return \View::make('hippnp.categorymanagement')->with('data', $data);
    }

    public static function picknpayBeaconManagement(){


        $beacons = EngagePicknPayBeacon::all();

        // $picknpayBrand = \Brand::where('name', '=', 'PicknPay')->firstOrFail();
        // $venues = $picknpayBrand->venues()->get();

        $data = array() ;
        $data['currentMenuItem'] = "Dashboard";
        $data['url'] = 'http://' . $_SERVER['SERVER_NAME'].'/';
        $data['beacons'] = $beacons;

        return \View::make('hippnp.beaconmanagement')->with('data', $data);
    }

    public static function picknpayStoreCategoryManagement(){


        $pnpBrand = \Brand::where('name', '=', 'PicknPay')->firstOrFail();
        $venues = $pnpBrand->venues()->get();

        $data = array();
        $data['currentMenuItem'] = "Dashboard";
        $data['url'] = 'http://' . $_SERVER['SERVER_NAME'].'/';
        $data['brands'] = $venues;
        $engageCategories = \EngagePicknPayCategory::all();

        $data['engageCategories'] = $engageCategories;

        return \View::make('hippnp.storecategorylist')->with('data', $data);

    }

    public static function addBeacon(){


        $categories = \Picknpay::fetchAllCategoriesWithoutDateFilter();
        $picknpayBrand = \Brand::where('name', '=', 'PicknPay')->firstOrFail();
        $venues = $picknpayBrand->venues()->get();

        $data = array();
        $data['currentMenuItem'] = "Dashboard";
        $data['url'] = 'http://' . $_SERVER['SERVER_NAME'].'/';
        $data['categories'] = $categories;
        $data['brands'] = $venues;

        return \View::make('hippnp.add_beacon')->with('data', $data);

    }

    public static function deleteBeacon($id) {

        $data = array();
        $data['currentMenuItem'] = "Dashboard";
        $data['url'] = 'http://' . $_SERVER['SERVER_NAME'].'/';

        $beacon = \EngagePicknPayBeacon::find($id);
        $beacon->delete();

        return \Redirect::to("/hippnp/picknpay_beacon_management");

    }

    public static function saveBeacon(){

        $data = array();
        $storeID = \Input::get('store_id');
        $categoryID = \Input::get('category_id');
        $beaconUUID = \Input::get('beacon_uuid');
        $beaconMinor = \Input::get('beacon_minor');
        $beaconMajor = \Input::get('beacon_major');

        //Get store
        $store = \Venue::find($storeID);
        $beaconStoreName = $store->sitename;

        //Get venue
        $province = \Picknpay::fetchStoreForVenue($store);

        //Get category

        $engageCategory = \EngagePicknPayCategory::find($categoryID);
        $categoryName = $engageCategory->name;

        $engageBeacon = new \EngagePicknPayBeacon();
        $engageBeacon->beacon_uuid = $beaconUUID;
        $engageBeacon->beacon_minor = $beaconMinor;
        $engageBeacon->beacon_major = $beaconMajor;
        $engageBeacon->store_id = $storeID;
        $engageBeacon->category_id = $categoryID;
        $engageBeacon->store_name = $beaconStoreName;
        $engageBeacon->category_name = $categoryName;
        $engageBeacon->province_id = $province->id;
        $engageBeacon->province_name = $province->name;
        $engageBeacon->save();

        return \Redirect::to("/hippnp/picknpay_beacon_management");
    }

    public static function addCategoryToStore() {

        $pnpBrand = \Brand::where('name', '=', 'PicknPay')->firstOrFail();
        $venues = $pnpBrand->venues()->get();

        $data = array() ;
        $data['currentMenuItem'] = "Dashboard";
        $data['url'] = 'http://' . $_SERVER['SERVER_NAME'].'/';
        $data['brands'] = $venues;

        return \View::make('hippnp.addcategory')->with('data', $data);
    }

    public static function saveCategoryToStore(){

        $categoryName = \Input::get('category_name');
        $storeId = \Input::get('store_id');

        $engageCategory = new \EngagePicknPayCategory();
        $engageCategory->name = $categoryName;
        $engageCategory->store_id = $storeId;
        $engageCategory->save();


        return \Redirect::to("/hippnp/picknpay_manage_store_categories");

    }

    public static function removeCategoryFromStore($id){

        $findID = Route::current()->getParameter('id');

        $engageCategory = \EngagePicknPayCategory::find($findID);
        $engageCategory->delete();

        return \Redirect::to("/hippnp/picknpay_manage_store_categories");
    }

    public static function getStoreCategories($id) {

        $storeCategories = \EngagePicknPayCategory::where('store_id', '=', $id)->get();
        $json = json_encode($storeCategories);
        print_r($json);

    }

    public static function getStoreCategoriesForCategoryDash($id) {

        $data = array();

        $storeCategories = \EngagePicknPayCategory::where('store_id', '=', $id)->get();

        $data['engageCategories'] = $storeCategories;
        return \View::make('hippnp.categeory_list_table_view')->with('data', $data);

    }

    public function convertSvgToImage()
    {
        $data                       =       array();
        $input_data                 =       Input::all();

        $fusionchart_spans                  =   $input_data['fusionchartspans'];
        $chart_svg                          =   "";
        $images                         =   array();
        $svgs                           =   array();
        $i                              =   0;
        //converting svg code to image
        foreach($fusionchart_spans as $key => $charts) {
            $i++;
            $chart_svg                 .=       $charts;
            $path                       =       base_path()."/public/fc_images/svg_temp/";
            $fileName                   =       $key.strtotime(date('H:i:s'));
            $svg_file                   =       fopen($path.$fileName.".svg","w");
            fwrite($svg_file, $charts);
            fclose($svg_file);
            $svgs["img_".$key]               =   $fileName;
        }


/////////////for dev1 pdfinvestigation /////////////
            $svgpath                    =   base_path().'/public/fc_images/svg_temp/';
            $imgpath                    =   base_path().'/public/fc_images/image_temp/';
        foreach($svgs as $key => $val) {
            //shell excution for svg to image
            $cmd                        =   'inkscape -f '.$svgpath.$val.'.svg -e '.$imgpath.$val.'.png';
            shell_exec($cmd);
            $images[$key]               =   $val.".png";
            unlink($svgpath.$val.'.svg');
        }

        $response                       =   array('status' => "success" , 'result_img' => $images);
        print_r(json_encode($response));exit;

    }

    public function hippnpPDFPreview()
    {
        $input_data                 =       Input::all();
        $data                       =       array();
        $data['currentMenuItem'] = "Dashboard";
        $data['fusionchartElementOne'] =  $input_data['myPageone'];
        $data['fusionchartElementTwo'] =  $input_data['myPagetwo'];
        $data['fusionchartElementThree'] =  $input_data['myPagethree'];
        $data['fusionchartElementFour'] =  $input_data['myPagefour'];
        // $data['reportName'] =  $input_data['reportName'];

//         if(isset($input_data['printtoken'])) {

//             $data['totalWifiSessions'] = $input_data['totalWifiSessions'];
//             $data['wifiDataTotal'] = $input_data['wifiDataTotal'];
//             $data['avgNumberofPeople'] = $input_data['avgNumberofPeople'];
//             $data['avgFirstTimeUsers'] = $input_data['avgFirstTimeUsers'];
//             $data['avgDataPerSession'] = $input_data['avgDataPerSession'];
//             $data['avgTimePerSession'] = $input_data['avgTimePerSession'];

//             // return \View::make('hipreports.hipwifi_brand_download', $data);
//             $dompdf = \PDF::loadView('hipreports.hipwifi_brand_download', $data);
// //            $pdf->set_paper(DEFAULT_PDF_PAPER_SIZE, 'portrait');
// //            $pdf->get_option('enable_css_float');
            //$filename               =       $data['report_name'].".pdf";
//             $filename = preg_replace( "/\s+/", " ", $data['report_name'].".pdf" );
//             $filename = str_ireplace(" ", "_", $filename);

           $filename           =       "graphview".strtotime(date('h:i:s')).".pdf";
           $data['reportName'] =  $filename;
//             return $dompdf->download($filename);
//            // $pdf = $dompdf->output();
//            // $file_location = base_path()."/public/fc_images/pdfreport/".$filename;
//            // file_put_contents($file_location,$pdf);
//         } else {



// $json = json_encode($input_data);

//         print_r($json);


            $data['printButtonToken']   =   TRUE;
            return \View::make('hippnp.hippnp_brand_download_preview', $data);
        // }
    }

    public function hippnpBrandPdfDownload(){

        $input_data                 =       Input::all();
        $data                       =       array();
        $data['currentMenuItem'] = "Dashboard";
        $data['fusionchartElementOne'] =  $input_data['myPageone'];
        $data['fusionchartElementTwo'] =  $input_data['myPagetwo'];
        $data['fusionchartElementThree'] =  $input_data['myPagethree'];
        $data['fusionchartElementFour'] =  $input_data['myPagefour'];
        $data['reportName'] = $input_data['reportName'];

        // if(isset($input_data['printtoken'])) {



            $dompdf = \PDF::loadView('hipreports.hippnp_brand_download', $data);
            $filename = preg_replace( "/\s+/", " ", $data['reportName'].".pdf" );
            $filename = str_ireplace(" ", "_", $filename);

            return $dompdf->download($filename);

        // } else {
            // $data['printButtonToken']   =   TRUE;
            // return \View::make('hipreports.hippnp_brand_download', $data);
            // $data['printButtonToken']   =   TRUE;
            // return \View::make('hippnp.hippnp_brand_download_preview', $data);
        // }

        // $json = json_encode($input_data);

        // print_r($json);

    }


}