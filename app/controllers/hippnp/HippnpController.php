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

        $allStaff = \Picknpay::fetchAllStaff($period, null, null);
        $datesForAllStaff = \Picknpay::datesToFetchChartDataFor($period, null, null)
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

        $allCategories = \Picknpay::fetchAllCategories($period, null, null, null);
        $allStores = \Picknpay::fetchAllStores($period, null, null);
        $allCategoriesForFilter = \Picknpay::fetchAllCategoriesForFilter();
        $data['all_categories_for_filter'] = $allCategoriesForFilter;

        $allProvinces = array();
        foreach ($allStores as $store) {
            $province = \Picknpay::fetchStoreForVenue($store);
            array_push($allProvinces, $province);
        }

        $data['all_categories'] = $allCategories;
        $data['all_stores'] = $allStores;
        $data['all_provinces'] = $allProvinces;


        $dates = \Picknpay::datesToFetchChartDataFor($period, null, null)
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
        $allStores = \EngagePicknpay::fetchAllStores($period, null, null);

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

    public function periodchartJsondata(){

        $data = array();

        $period = Input::get('period');
        $start = Input::get('start');
        $end = Input::get('end');

        $categoryId = Input::get('category_id');
        $storeId = Input::get('store_id');
        $provinceId = Input::get('province_id');

        $allCategoriesForFilter = \Picknpay::fetchAllCategoriesForFilter();
        $data['all_categories_for_filter'] = $allCategoriesForFilter;


        if ($start != null && $end != null){
            $allCategories = \Picknpay::fetchAllCategories($period, $start, $end, $categoryId);
            $dates = \Picknpay::datesToFetchChartDataFor($period, $start, $end)
            ->map(function($row) {
                    return ['label' => $row['created_att']];
                });
        }
        else {
            $allCategories = \Picknpay::fetchAllCategories($period, $start, $start, $categoryId);
            $dates = \Picknpay::datesToFetchChartDataFor($period, $start, $start)
            ->map(function($row) {
                    return ['label' => $row['created_att']];
            });
        }



        $data['all_categories'] = $allCategories;
        $data['category_list'] = $dates;




        ////////////////////

        // Sum of all categories

        $finalChartObjectStaff = array();

        $allStaff = \Picknpay::fetchAllStaff($period, $start, $end);
        $datesForAllStaff = \Picknpay::datesToFetchChartDataFor($period, $start, $start)
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
                    $empty_array = array(['value' => '0', 'id' => $staffId]);
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
        $allStores = \EngagePicknpay::fetchAllStores($period, null, null);

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

        $data = array();
        $data['currentMenuItem'] = "Dashboard";
        $data['url'] = 'http://' . $_SERVER['SERVER_NAME'].'/';
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

        $data = array() ;
        $data['currentMenuItem'] = "Dashboard";
        $data['url'] = 'http://' . $_SERVER['SERVER_NAME'].'/';

        return \View::make('hippnp.addcategory')->with('data', $data);
    }

    public static function saveCategoryToStore(){

        $categoryName = \Input::get('category_name');

        $engageCategory = new \EngagePicknPayCategory();
        $engageCategory->name = $categoryName;
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

}


