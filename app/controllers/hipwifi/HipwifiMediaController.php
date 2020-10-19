<?php
namespace hipwifi;


use Image;
use FFMpeg;
//use Validator;

use Illuminate\Validation\Validator;
//use ValidatesRequests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// use BaseController;

class HipwifiMediaController extends \BaseController {

    public function showBrands() {
    
    $brand = new \Brand();
    $brands = $brand->getBrandsForUser(\Auth::user()->id);
    $data = array();
    $data['currentMenuItem'] = "Media Management";
    $data['brands'] = $brands;
    return \View::make('hipwifi.showbrandmedia')->with('data', $data);
    }   

    
    public function addAdvert($id) {
        $data = array();
        $brand = new \Brand();

        
        $brandobj = $brand->find($id);
        $data['brandid'] = $id;
        $data['brandname'] = $brandobj->name;
        $data['brandcountry'] = $brandobj->countrie->name;
        $data['brandcountrieid'] = $brandobj->countrie->id;
        $data['brandprovincenames'] = array();
        $data['brandprovinceids'] = array();
        $data['brandcountryid'] = $brandobj->countrie->id;
        $data['edit'] = false;
        //dd($brandcountryid);
        //$province = new \Province();
        $countryprovinces = \DB::table('provinces')->where('countrie_id', $data['brandcountryid'])->get();
        //dd($brandcountryid, $countryprovinces);
        foreach ($countryprovinces as $province) {
            $id = $province->id;
            $name = $province->name;
            array_push($data['brandprovinceids'], $id);
            array_push($data['brandprovincenames'], $name);
        }
        //dd($data['brandname']);
        $data['province'] = array_combine($data['brandprovinceids'], $data['brandprovincenames']);
        $data['currentMenuItem'] = 'Media Management';


        return \View::make('hipwifi.addadvert')->with('data', $data);
    }

    public function addAdvertSave(){

    $input = \Input::all();
  // dd($input['image']);

    $util = new \Utils();
    $advertmedia = new \Advertmedia();
    $brand = new \Brand();
    $venue = new \Venue();
    $brand_name = $input['brand'];
    $brand_id = $brand->where('name', $brand_name)->pluck('id');
    $assets= \DB::table('systemconfig')->select("*")->where('name', '=', "assetsdir")->first();
    $assetsdir = $assets->value;
    $dir = \DB::table('systemconfig')->select("*")->where('name', '=', "mikrotikdir")->first();
    //dd($assetsdir);
     if (isset ($input['target']) ){
        $location = $venue->where('id', $input['venue_id'])->pluck('location');
        $input['location'] = $location;

     }
     else{
        $templocation =$brand->where('id', $brand_id)->pluck('code');
        $location = $templocation. "XXXXXXXXXXXXXXXXZA";
         $input['location'] = 'HIP'. $location;
     }

    


    if ($input['video'] == 'image'){
        $messages = array(
        'campaign.required' => 'Please enter a campaign name',
        'image.required' => 'Please select an image file to upload',
        'image.size' => 'Please make sure image size is less than  or equal to 100kb',
        'image.mimes' => 'Please upload a jpeg or png image type',
        'location.unique' => 'An advert has already been configured for this venue, please delete that and add a new one.',
        );
        $rules = array(
        'campaign' => 'required',
        'image' => 'required|between:1,100|mimes:jpeg,png',
        'location' => 'unique:advertmedias,location',
        );
     }
     elseif ($input['video'] == 'video') {
        //image here refers to video as the file input for either image or video file is named image.
        $messages = array(
        'campaign.required' => 'Please enter a campaign name',
        'image.required' => 'Please select a video file to upload',
        'image.size' => 'Please make sure video size is less than or equal to 100MB',
        'image.mimes' => 'Please upload an mp4 video',
        'location.unique' => 'An advert has already been configured for this venue, please delete that and add a new one.',
        );
        $rules = array(
        'campaign' => 'required',
        'image' => 'required|between:1,100000|mimes:mp4',
        'location' => 'unique:advertmedias,location',
        );
     }
    
   
   $validator = \Validator::make($input, $rules, $messages); 

    if ($validator->fails())
    {
        $messages = $validator->messages();
        return \Redirect::route('hipwifi_addadvert', ['id' => $brand_id])->withErrors($validator)->withInput();
    } else{

                $advertmedia->campaign = $input['campaign'];
             
                $isp_id = $brand->where('name', $brand_name)->pluck('isp_id');
                $advertmedia->brand_id = $brand_id;

                $advertmedia->countrie_id = $input['countrie_id'];
    if (isset ($input['target']) ){
            $advertmedia->province_id = $input['province_id'];
            $advertmedia->citie_id = $input['city_id'];
            $advertmedia->venue_id = $input['venue_id'];
            $advertmedia->location = $venue->where('id', $advertmedia->venue_id)->pluck('location');
        }
        else{
  
            $advertmedia->province_id = 0;
            $advertmedia->citie_id = 0;
            $advertmedia->venue_id = 0;
            $location = $brand->where('id', $brand_id)->pluck('code');
            $brand = $brand->find($brand_id);
            $ispcode = $brand->isp->code;
            $advertmedia->location = $ispcode . $brand->code . "XXXXXXXXXXXXXXXXZA";

         }
   

    if ($input['video' ] == 'image'){
        $image = \Input::file('image');
        $advertmedia->type = "image";
        $imageextension = $image->getClientOriginalExtension();
        $imagename = $advertmedia->location . '_1' . '.'.$imageextension;
        $imagelocation = $assetsdir. 'hiprm/advertimages/'. $imagename;
        $advertmedia->medianame = "hiprm/advertimages/" . $imagename;
        Image::make($image)->resize(600, 1200)->save($imagelocation);
    }
    if ($input['video'] == 'video'){
        $advertmedia->type = "video";
        $video = \Input::file('image');
        $type = $video->getClientOriginalExtension();
        $size = filesize($video);
        $location = $advertmedia->location;
        $videoname = $location. '_1'. '.' .$type;
        $advertmedia->medianame = "hiprm/advertvideos/" . $videoname;
        $videolocation = $assetsdir. 'hiprm/advertvideos/';
        $video->move($videolocation, $videoname);
        $finalvideolocation = $videolocation.$videoname;
        $this->makeThumbnail($finalvideolocation, $videolocation, $location);
        //dd($videothumbnail);
    
       
        //The below code implements getting the aps to download the uploaded video into their file directory....not yet tested.
            if (isset($input['target'])){
                $apmac = $venue->where('id', $advertmedia->venue_id)->pluck('macaddress');
                $aptype = $venue->where('id', $advertmedia->venue_id)->pluck('device_type');
                    if($aptype == "Mikrotik"){
                        
                        $script = "\n" . "/tool fetch address=manage.hipzone.co.za src-path=/var/www/assets/hiprm/advertvideos/".$videoname." user=mikrotik mode=ftp password=mikmanage dst-path=".$videoname ." keep-result=yes;/import file-name=/runall.rsc" ."\n";
                        $rsc = $dir->value. "deployment/".$apmac."_951-2n.rsc";
                        $file = fopen($rsc, 'a');
                        fwrite($file, $script);
                        fclose($file);
                    }
            }
            else{
                $aps= $venue->select('macaddress')->where('brand_id', $brand_id)->where('device_type', 'Mikrotik')->get();
                foreach($aps as $ap){
                    $apmac = $ap->macaddress;
                    $script = "\n" . "/tool fetch address=manage.hipzone.co.za src-path=/var/www/assets/hiprm/advertvideos/".$videoname." user=mikrotik mode=ftp password=mikmanage dst-path=".$videoname ." keep-result=yes;/import file-name=/runall.rsc" . "\n";
                        $rsc = $dir->value. "deployment/".$apmac."_951-2n.rsc";
                        $file = fopen($rsc, 'a');
                        fwrite($file, $script);
                        fclose($file);

                }
            }

    }

     $advertmedia->save();

     $venue->refreshAdvertMediaNames();
     $venue->synchToHiprm();

     return \Redirect::route('hipwifi_showsinglebrandmedia', ['id' => $brand_id]);
    }
   }

   public function makeThumbnail($finalvideolocation, $videolocation, $location){
        $ffmpeg = FFMpeg\FFMpeg::create();
        $video = $ffmpeg->open($finalvideolocation);
        $thumbnailfile = $videolocation."thumbnails/" . $location;
        $videothumbnail = $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(5))->save($thumbnailfile);

   }

   public function editAdvertMedia($id, $brandid){
    $assets= \DB::table('systemconfig')->select("*")->where('name', '=', "assetsdir")->first();
    $assets_server= \DB::table('systemconfig')->select("*")->where('name', '=', "assetsserver")->first();
    $assets_server = $assets_server->value;
    $data = array();
    $advert = new \Advertmedia(); 
    $advertobj = $advert->find($id);
    $data['location'] = $advertobj->location;
    $data['media'] = $assets_server.$advertobj->medianame;
    $data['type'] = $advertobj->type;
    if ($data['type'] == 'video'){
    $data['media'] = $assets_server.'hiprm/advertvideos/thumbnails/'.$data['location'];    
    }
    $data['edit'] = true;
    $data['currentMenuItem'] = 'Media Management';
    $data['campaign'] = $advertobj->campaign;
    $data['brandid'] = $advertobj->brand_id;
    $data['mediaid'] = $id;
    $data['brandcountryid'] = $advertobj->countrie_id;
    $data['country'] = $advertobj->countrie->name;
    if ( $advertobj->province_id != 0){
    $data['province'] = ',' . $advertobj->province->name;      
    }
    else{
       $data['province'] = '';      
    }
       if ( $advertobj->citie_id != 0){
    $data['city'] = ',' .$advertobj->citie->name;      
    }
    else{
       $data['city'] = '';      
    }    
    if ( $advertobj->venue_id != 0){
    $data['venue'] = ',' . $advertobj->venue->sitename;      
    }
    else{
       $data['venue'] = '';      
    }  
    $data['target'] = $data['country'] . $data['province'] . $data['city'] . $data['venue'];
       // dd($data);
      return \View::make('hipwifi.addadvert')->with('data', $data);
   }

   public function editAdvertMediaSave(){
    $assets= \DB::table('systemconfig')->select("*")->where('name', '=', "assetsdir")->first();
    $assetsdir = $assets->value;
    $dir = \DB::table('systemconfig')->select("*")->where('name', '=', "mikrotikdir")->first();
    $input = \Input::all();
    $advert = new \Advertmedia();
    $venue = new \Venue();
    $advertid = $input['id'];
    $advertentry = $advert->find($advertid);
    $advertbrandid = $input['brandid'];
    $advertentry->campaign = $input['campaign'];
    $location = $advertentry->location;
    $venueid = $advertentry->venue_id;
    //dd($venueid);
    $determine_target = substr_count($location, 'X');
    $target = true;
    if ($determine_target >= 10){
        $target = false; // this means that the location is just the country
    }

   
    if(null !== \Input::file('video')){
         $video = \Input::file('video');
         $type = $video->getClientOriginalExtension();

        $messages = array(
        'campaign.required' => 'Please enter a campaign name',
        'video.size' => 'Please make sure video size is less than or equal to 100MB',
        'video.mimes' => 'Please upload an mp4 video',
        );
        $rules = array(
        'campaign' => 'required',
        'video' => 'required|between:1,100000|mimes:mp4',

        );

        $validator = \Validator::make($input, $rules, $messages); 

        if ($validator->fails()){
            $messages = $validator->messages();
            return \Redirect::route('hipwifi_editadvertmedia',  array($advertid,  $advertbrandid))->withErrors($validator)->withInput();
            } 
        else{
            //fetch the details of the already configured file in the db and then delete it before adding the new one.
                $oldvideoname = $assetsdir . $advertentry->medianame;
                if(file_exists($oldvideoname)){
                    unlink($oldvideoname);
                }
                $oldvideonameonap = substr($advertentry->medianame, strpos($advertentry->medianame, 'H'));
                $location = $advertentry->location;
                $videoname = $location. '_1'. '.' .$type;
                $videolocation = $assetsdir. 'hiprm/advertvideos/';
                $video->move($videolocation, $videoname);
                $finalvideolocation = $videolocation.$videoname;
                $this->makeThumbnail($finalvideolocation, $videolocation, $location);
        //code below will create an rsc script that will instruct related mikrotik APs to delete the present video and then download the newly uploaded video.
                if ($target == true){
                                $ap = $venue->find($venueid);
                                $apmac = $ap->macaddress;
                                $script = "\n". "/file remove ". $oldvideonameonap. "\n" . "/tool fetch address=manage.hipzone.co.za src-path=/var/www/assets/hiprm/advertvideos/".$videoname." user=mikrotik mode=ftp password=mikmanage dst-path=".$videoname ." keep-result=yes;/import file-name=/runall.rsc" . "\n";
                                $rsc = $dir->value. "deployment/".$apmac."_951-2n.rsc";
                                $file = fopen($rsc, 'a');
                                fwrite($file, $script);
                                fclose($file);
                    }
                else{
                            $aps= $venue->select('macaddress')->where('brand_id', $advertbrandid)->where('device_type', 'Mikrotik')->get();
                            foreach($aps as $ap){
                                    $apmac = $ap->macaddress;
                                    $script = "\n". "/file remove ". $oldvideonameonap. "\n" . "/tool fetch address=manage.hipzone.co.za src-path=/var/www/assets/hiprm/advertvideos/".$videoname." user=mikrotik mode=ftp password=mikmanage dst-path=".$videoname ." keep-result=yes;/import file-name=/runall.rsc" . "\n";
                                    $rsc = $dir->value. "deployment/".$apmac."_951-2n.rsc";
                                    $file = fopen($rsc, 'a');
                                    fwrite($file, $script);
                                    fclose($file);
                                }
                    }
                }
            }
        if (null !== \Input::file('image')){
                 $messages = array(
                            'campaign.required' => 'Please enter a campaign name',
                            'image.required' => 'Please select a file to upload',
                            'image.size' => 'Please make sure image size is less than  or equal to 100kb',
                            'image.mimes' => 'Please upload a jpeg or png image type',
                            );
                 $rules = array(
                            'campaign' => 'required',
                            'image' => 'required|between:1,100|mimes:jpeg,png',
                            );
                 $validator = \Validator::make($input, $rules, $messages); 

                if ($validator->fails())
                {
                    $messages = $validator->messages();
                    return \Redirect::route('hipwifi_editadvertmedia',  array($advertid,  $advertbrandid))->withErrors($validator)->withInput();
                } else{
                            $oldimagename = $assetsdir. $advertentry->medianame;
                            if(file_exists($oldimagename)){
                                unlink($oldimagename);
                            }
                            $image = \Input::file('image');
                            $advertentry->type = "image";
                            $imageextension = $image->getClientOriginalExtension();
                            $imagename = $advertentry->location . '_1' . '.'.$imageextension;
                            $imagelocation = $assetsdir. 'hiprm/advertimages/'. $imagename;
                            $advertentry->medianame = 'hiprm/advertimages/' . $imagename;
                            Image::make($image)->resize(600, 1200)->save($imagelocation);
                    }
            }      
   
    
        $advertentry->save();
        return \Redirect::route('hipwifi_showsinglebrandmedia', ['id' => $advertbrandid]);
}

   public function deleteAdvertMedia($id, $brandid){
        $assets = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsdir")->first();
        $assetsdir = $assets->value;
        $dir = \DB::table('systemconfig')->select("*")->where('name', '=', "mikrotikdir")->first();
        $advert = new \Advertmedia();
        $advertentry = $advert->find($id);
        $filename = $assetsdir . $advertentry->medianame;
        $venue = new \Venue();
        $advertbrandid = $advertentry->brand_id;
        $venueid = $advertentry->venue_id;
        

        unlink($filename);
        if($advertentry->type == 'video'){
            $videoname = substr($advertentry->medianame, strpos($advertentry->medianame, 'H'));
            if(substr_count($advertentry->location, 'X') >= 10){
                  $aps= $venue->select('macaddress')->where('brand_id', $advertbrandid)->where('device_type', 'Mikrotik')->get();
                  foreach($aps as $ap){
                        $apmac = $ap->macaddress;
                        $script = "\n". "/file remove ". $videoname. "\n";
                        $rsc = $dir->value. "deployment/".$apmac ."_951-2n.rsc";
                        $file = fopen($rsc, 'a');
                        fwrite($file, $script);
                        fclose($file);
                    }

            }
            else{
                        $ap = $venue->find($venueid);
                        $apmac = $ap->macaddress;
                        $script = "\n". "/file remove ". $videoname. "\n";
                        $rsc = $dir->value. "deployment/".$apmac."_951-2n.rsc";
                        $file = fopen($rsc, 'a');
                        fwrite($file, $script);
                        fclose($file);
             }

           
        }

        $advert->deleteadvertmedia($id);
        $venue->refreshAdvertMediaNames();
        $venue->synchToHiprm();

        return \Redirect::route('hipwifi_showsinglebrandmedia', ['id' => $brandid]);

    }

    public function addConnectPage($id){
        $brand = new \Brand();
        $brand = $brand->find($id);
        $data = array();
        $data['edit'] = false;
        $data['currentMenuItem'] = "Media Management";
        $data['brandid'] = $id;
        $data['brandcountryid'] = $brand->countrie_id;
        $data['brandcountry'] = $brand->countrie->name;
        $data['brandname'] = $brand->name;
        $data['isp_id'] = $brand->isp_id;
        return \View::make('hipwifi.addconnectpagemedia')->with('data', $data);

    }

     public function addConnectPageSave(){
        $input = \Input::all();
        $cpmedia = new \Cpmedia();
         $isp_id = $input["isp_id"];
         $brand_id = $input['brandid'];
         $countrie_id = $input['countrie_id'];
           if(!isset($input['province_id']) || $input['province_id'] == "Please select"){
                   $province_id = null;
            }
            else{
                    $province_id = $input['province_id'];
            }

            if(!isset($input['citie_id']) || $input['citie_id'] == "Please select"){
                   $citie_id = null;

            }
            else{
                    $citie_id = $input['citie_id'];

            }

                 if(!isset($input['venue_id']) || $input['venue_id'] == "Please select"){
                   $venue_id = null;
            }
            else{
                    $venue_id = $input['venue_id'];
                 
            }

            $util = new \Utils();
            $location = $util->buildMatchLocationCode($isp_id, $brand_id, $countrie_id, $province_id, $citie_id, $venue_id);
            $input['location'] = $location;
               
         $messages = array(
                            'top.required' => 'Please enter a top value',
                            'top.integer' => 'Top value must be an integer',
                            'width.required' => 'Please enter a width value',
                            'width.integer' => 'Width value must be an integer',
                            'height.required' => 'Please enter a height value',
                            'height.integer' => 'Height value must be an integer',
                            'left.required' => 'Please enter a left value',
                            'left.integer' => 'Left value must be an integer',
                            'location.unique' => 'A connect page already exist for this target, please it before adding a new one',
                            'image.required' => 'Please select a file to upload',
                            'image.size' => 'Please make sure image size is less than  or equal to 100kb',
                            'image.mimes' => 'Please upload a jpeg or png image type',
                            );
                 $rules = array(
                            'top' => 'integer|required',
                            'width' => 'integer|required',
                            'height' => 'integer|required',
                            'left' => 'integer|required',
                            'image' => 'required|between:1,100|mimes:jpeg,png',
                            'location' => 'unique:cpmedias,location',
                            );


                $validate = \Validator::make($input, $rules, $messages);
                if ($validate->fails())
                {
                        $messages = $validate->messages();
                        return \Redirect::route('hipwifi_addconnectpage',  array($brand_id))->withErrors($validate)->withInput();
                } else{
                        
                        $assets = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsdir")->first();
                        $assetsdir = $assets->value;
                        $cpimagelocation = $assetsdir . "hipwifi/connect/" . $location;
                        $image = \Input::file('image');
                        Image::make($image)->resize(600, 1200)->save($cpimagelocation);
                        $cpmedia->brand_id = $brand_id;
                        $cpmedia->countrie_id = $countrie_id;
                        $cpmedia->province_id = $province_id;
                        $cpmedia->citie_id = $citie_id;
                        $cpmedia->venue_id = $venue_id;
                        $cpmedia->location = $location;
                        $cpmedia->cp_medianame =  "hipwifi/connect/" . $location;
                        $cpmedia->cp_btn1_top = $input['top'];
                        $cpmedia->cp_btn1_width = $input['width'];
                        $cpmedia->cp_btn1_left = $input['left'];
                        $cpmedia->cp_btn1_height = $input['height'];
                        $cpmedia->save();
                        
                }
        return \Redirect::route('hipwifi_showsinglebrandmedia', ['id' => $brand_id]);

    }

    public function editCpMedia($id){
        $assetsserver = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsserver")->first();
        $data = array();
        $data['edit'] = true;
        $data['currentMenuItem'] = "Media Management";
        // $cpmedia = new \Cpmedia();
        // $cpmedia = $cpmedia->find($id);

        $cpmedia = new \Media();
        $cpmedia = $cpmedia->find($id);
        $brand = new \Brand();
        $brand = $brand->find($cpmedia->brand_id);
        error_log("editCpMedia : brand_id = " . $brand->id);

        $data['brandid'] = $brand->id;
        $data['cpmediaid'] = $id;
        $data['brandname'] = $brand->name;
        $data['connect_btn_enabled'] = $cpmedia->connect_btn_enabled;
        if($data['connect_btn_enabled']) {
            $data['connect_btn_enabled'] = "checked";
        } else {
            $data['connect_btn_enabled'] = "";
        }
        $data['connect_btn_colour'] = $cpmedia->connect_btn_colour;
        $data['connect_text_colour'] = $cpmedia->connect_text_colour;
        $data['connect_btn_offset_from_top'] = $cpmedia->connect_btn_offset_from_top;
        // $data['cpmedia'] = $assetsserver->value. $cpmedia->cp_medianame;
        $data['location'] = $cpmedia->location;
        $data['dt_ext'] = $cpmedia->dt_ext;
        $data['mb_ext'] = $cpmedia->mb_ext;

        $data['brandcountryid'] = $brand->countrie_id;

        error_log("sfasdfasdfsadfsadfasfasfasfdasdfasf");

        return \View::make('hipwifi.addconnectpagemedia')->with('data', $data);



    }

     public function editCpMediaSave(){

        $input = \Input::all();
        $id = \Input::get('id');
        error_log("editCpMediaSave : id = $id");
        $media =  \Media::find($id);
        $rules = array();

        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            $messages = $validator->messages();

            return \Redirect::to('hipwifi_addmedia')->withErrors($validator)->withInput();

        } else {

            error_log("editCpMediaSave : location = " . $media->location);
            if( \Input::get("connect_btn_enabled") == "on") {
                $media->connect_btn_enabled = 1;
            } else {
                $media->connect_btn_enabled = 0;
            }
            $media->connect_btn_colour = \Input::get("connect_btn_colour");
            $media->connect_text_colour = \Input::get("connect_text_colour");
            $media->connect_btn_offset_from_top = \Input::get("connect_btn_offset_from_top");

            error_log("editCpMediaSave : logo_choice : " . $media->logo_choice);

            $media->save();

            $remotedb_id = \Brand::find(\Input::get("brand_id"))->remotedb->id;
            $venue = new \Venue();
            $venue->refreshMediaLocations();
            $venue->synchToRadius($remotedb_id);


        }

        return \Redirect::route('hipwifi_showbrandmedia');

        // $input = \Input::all();
        // $brand_id = $input['brandid'];
        // $id = $input['cpmediaid'];
        // error_log("editCpMediaSave 10 - id = $id");
        // $cpmedia = new \Media();
        // $cpmediaentry = $cpmedia->find($id);
        // $messages = array(
        //                     'connect_btn_offset_from_top.integer' => 'Offset value must be an integer',
        //                     );
        // $rules = array(
        //                     'connect_btn_offset_from_top.integer' => 'Offset value must be an integer',
        //                     );

        // $validate = \Validator::make($input, $rules, $messages);
        // if ($validate->fails())
        // {
        //     $messages = $validate->messages();
        //     return \Redirect::route('hipwifi_editcpmedia',  array($id))->withErrors($validate)->withInput();
        // } else {
        // error_log("editCpMediaSave 10 - connect_btn_colour = " . $input['connect_btn_colour']);
        // error_log("editCpMediaSave 10 - connect_text_colour = " . $input['connect_text_colour']);
        // error_log("editCpMediaSave 10 - connect_btn_offset_from_top = " . $input['connect_btn_offset_from_top']);
              
        //     $cpmediaentry->connect_btn_colour = $input['connect_btn_colour'];
        //     $cpmediaentry->connect_text_colour = $input['connect_text_colour'];
        //     $cpmediaentry->connect_btn_offset_from_top = $input['connect_btn_offset_from_top'];
        //     $cpmediaentry->save();
        // }
                    
        return \Redirect::route('hipwifi_showsinglebrandmedia', ['id' => $brand_id]);    
     }
    
        
   

     public function deleteCpMedia($id, $brand_id){
        $assets = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsdir")->first();
        $assetsdir =  $assets->value;
        $cpmedia = new \Cpmedia();
        $cpmediaentry = $cpmedia->find($id);
        $filelocation = $assetsdir . $cpmediaentry->cp_medianame;
        if ($filelocation){
            unlink($filelocation);
        }
        $cpmedia->deletecpmedia($id);
        return \Redirect::route('hipwifi_showsinglebrandmedia', ['id' => $brand_id]);
        
    }


   public function showMedias($id, $json = null)
    {
        error_log("showMedias");

        $data = array();
        $data['currentMenuItem'] = "Media Management";
        $mediaObj = new \Media();

        $medias = $mediaObj->getMedias();
        // $medias = \Media::All();

        foreach($medias as $media) {
            // $media = \Media::find($m->id);
            error_log("showMedias : brand_id : " . $media->brand_id);
            $countrie_name = $province_name = $citie_name = $venue_name = null;

            error_log("showMedias : brand_id : " . $media->brand->name);
            $media["brandname"] = $media->brand->name;
            $countrie_name = \Countrie::find($media->countrie_id)->name;

           $province = \Province::find($media->province_id);
            if($province) $province_name = ', ' . $province->name;

            $citie = \Citie::find($media->citie_id);
            if($citie) $citie_name = ', ' . $citie->name;
            
            $venue = \Venue::find($media->venue_id);
            if($venue) $venue_name = ', ' . $venue->sitename;

            $media->target = $countrie_name . $province_name . $citie_name . $venue_name;
             $media["country"] = $countrie_name;
        }
        // error_log("showMedias : countrie_name : $countrie_name" );
        
        $data['mediasJason'] = json_encode($medias);

        $data['currentMenuItem'] = "Media Management";

        if($json) {
            error_log("showMedias : returning json" );
            return \Response::json($data['mediasJason']);

        } else {
            error_log("showMedias : returning NON json" );
            return \View::make('hipwifi.showmedias')->with('data', $data);
            
        }
    }



    public function showSingleBrandMedia($id) {

        $medias = new \Media();
        $mediasobj = $medias->getLoginMedia($id);
        $brand = new \Brand();
        $data = array();
        $data['currentMenuItem'] = "Media Management";
        $data['medianame'] = array();
        $data['mediatarget'] = array();
        $data['mediaid'] = array();
        $data['brandid'] = $id;
        $brandentry = $brand->find($id);
        $data['brandname'] = $brandentry->name;
        //dd($data['brandid']);
          foreach($mediasobj as $media) {
            $name = $media->brand->name;
            $id = $media->id;
            array_push($data['mediaid'], $id);
            array_push($data['medianame'], $name);
            error_log("showMedias : brand_id : " . $media->brand_id);
            $countrie_name = $province_name = $citie_name = $venue_name = null;
            error_log("showMedias : brand_id : " . $media->brand->name);
            $countrie_name = $media->countrie->name;
            if($media->province_id){
                $province = $media->province->name;
                if($province) $province_name = ', ' . $province;
            }
            if ($media->citie_id){
            $citie = $media->citie->name;
            if($citie) $citie_name = ', ' . $citie;
             }
              if ($media->venue_id) {
            $venue = $media->venue->sitename;
            if($venue) $venue_name = ', ' . $venue;
            }
            $target = $countrie_name . $province_name . $citie_name . $venue_name;
            array_push($data['mediatarget'], $target);
             }
        
        // Listing the advert media entry, targets and type.
            $advertmedia = new \Advertmedia();
            $data['advertid'] = array();
            $data['advertname'] = array();
            $data['adverttarget'] = array();
            $data['adverttype'] = array();

            $brandadvertentries = $advertmedia->getadvertmedia($data['brandid']);
            //$brandadvertentries = $advertmedia->where('brand_id', $data['brandid'])->get();
            foreach ($brandadvertentries as $advert) {
                $id = $advert->id;
                $name = $advert->campaign;
                $type =$advert->type;
                $countryn = $advert->countrie->name;


                if ($advert->province_id != 0){
                     $province = $advert->province->name;
                     if ($province) $provincen = ', '. $province;
                }
               else{
                        $provincen = '';
                }
                
                

                if ($advert->citie_id != 0 ) {
                     $city = $advert->citie->name;
                     if($city) $cityn = ', ' . $city;
               }
                else {
                         $cityn = '';
                    }
                
                
                if($advert->venue_id != 0){
                    $venue = $advert->venue->sitename;
                    if($venue) $venuen = ', ' . $venue;
                  }
                else{
                        $venuen = '';
                    }
              
                
                $target = $countryn. $provincen. $cityn. $venuen;

                array_push($data['advertid'], $id);
                array_push($data['advertname'], $name);
                array_push($data['adverttarget'], $target);
                array_push($data['adverttype'], $type);


            }
            //dd($adverttarget);
             //$data['advertentries'] = array_combine($advertname, $adverttarget);
             //dd($data['advertentries']);

             // Listing the cpmedia entry and target.
            $cpmedia = new \Cpmedia();
            $data['cpmediaid'] = array();
            $data['cpmediatarget'] = array();

            $cpmediaentries = $cpmedia->getcpmedia($data['brandid']);
            foreach ($cpmediaentries as $cpmedia) {
                $id = $cpmedia->id;
                
               
                $countryn = $cpmedia->countrie->name;


                if ($cpmedia->province_id != null){
                     $province = $cpmedia->province->name;
                     if ($province) $provincen = ', '. $province;
                }
               else{
                        $provincen = '';
                }
                
                

                if ($cpmedia->citie_id != null ) {
                     $city = $cpmedia->citie->name;
                     if($city) $cityn = ', ' . $city;
               }
                else {
                         $cityn = '';
                    }
                
                
                if($cpmedia->venue_id != 0){
                    $venue = $cpmedia->venue->sitename;
                    if($venue) $venuen = ', ' . $venue;
                  }
                else{
                        $venuen = '';
                    }
              
                
                $target = $countryn. $provincen. $cityn. $venuen;

                array_push($data['cpmediaid'], $id);
                array_push($data['cpmediatarget'], $target);
              

            }

        return \View::make('hipwifi.showmedias')->with('data', $data);


    }

    private function deletePreviewfiles() {

        $assetsdir = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsdir")->first();
        $fullpath = $assetsdir->value . 'hipwifi/images/';

        exec("rm -f " . $fullpath . "preview*");

    }


    private function resetPreviewfiles() {

        $assetsdir = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsdir")->first();
        $fullpath = $assetsdir->value . 'hipwifi/images/';
        $sourceFullName = $fullpath . 'HIPXXXXXXXXXXXXXXXXXXXXXXZA-dt.jpg'; 

        exec("rm -f " . $fullpath . "preview*");

        $destFullName = $fullpath . 'preview-dt.jpg'; 
        \File::copy($sourceFullName, $destFullName);
        $destFullName = $fullpath . 'preview-mb.jpg'; 
        \File::copy($sourceFullName, $destFullName);

    }


    public function addMedia($brandid)
    {
        error_log("addMedia");

        // Clear out any remaining files from the preview directory
        // $customerdir = \DB::table('systemconfig')->select("*")->where('name', '=', "customerdir")->first();
        // $previewDirectory = $customerdir->value . 'hipwifi/images/';
        // \File::cleanDirectory($previewDirectory);

        $this->resetPreviewfiles();
        
        $assetsserver = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsserver")->first();

        // error_log("myerrors : " . print_r($myerrors, true));
        $brand = new \Brand();
        $brandentry = $brand->find($brandid);
        $data = array();
        $data['currentMenuItem'] = "Media Management";
        $data['edit'] = false;
        $data['brandid'] = $brandid;
        $data['brandname'] = $brandentry->name;

        $data['previewurl'] = $assetsserver->value . 'hipwifi/images/';

        $media = new \Media();
        $media->bg_colour = "#00B551";
        $media->welcome_flag = 0;
        $media->ef_group_pos = 25;
        $media->ef_transparency = 80;
        $media->ef_colour = "#23E07B";
        $media->ef_outline_text_colour = "#FFFFFF";
        $media->zonein_gap = 30;
        $media->zonein_btn_colour = "#00AB4F";
        $media->zone_txt_colour = "#FFFFFF";
        $media->faq_colour = "#FFFFFF";
        $media->logo_choice = "white";
        $media->dt_ext = "jpg";
        $media->mb_ext = "jpg";
        $data['media'] = $media;

        $data['image-dt'] = "/img/mediaplaceholder.png";
        $data['image-mb'] = "/img/mediaplaceholder.png";

        $data['logo_choice_black'] = "checked";
        $data['logo_choice_white'] = "";

        $data['flag_off_checked'] = "";
        $data['flag_on_checked'] = "";

        $servers = \Server::All();
        $data['allservers'] = $servers;

        $countries = \Countrie::All();
        $data['allcountries'] = $countries;

        $isps = \Isp::All();
        $data['allisps'] = $isps;

        $brand = new \Brand();
        $data['brands'] = $brand->getBrandsForProduct('hipwifi');

        return \View::make('hipwifi.addmedia')->with('data', $data);
    }



    public function addMediaSave()
    {
        \Log::info("[HipwifiMediaController - addMediaSave]");
        $input = \Input::all();
        print_r($input, false);
        error_log("brand_id : " . $input['brand_id']);
        $rules = array();

        $messages = array(
            'location.unique' => 'This location has already been configured'
        );


        $location = \Input::get("location");
            error_log("addMediaSave : 10 : location : $location");
        $exists = \Media::where("location", "like", $location)->first();
        if(! is_null($exists)) {
            error_log("addMediaSave : 15");
            $exists->forceDelete();
        } 
            error_log("addMediaSave : 20");

        $rules = array(
            'location'      => 'required|unique:medias'          
        );

        $validator = \Validator::make($input, $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();

            return \Redirect::to('hipwifi_addmedia')->withErrors($validator)->withInput();

        } else {
            error_log("addMediaSave : 10");

            $dt_ext = \Input::get("dt_ext"); 
            $mb_ext = \Input::get("mb_ext");

            // $customerdir = \DB::table('systemconfig')->select("*")->where('name', '=', "customerdir")->first();
            // $sourcePath = $customerdir->value . 'hipwifi/images/';

            $assetsdir = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsdir")->first();
            $fullpath = $assetsdir->value . 'hipwifi/images/';

            // Get Desktop File ///////////////////////////////////////////
            $sourceFullName = $fullpath . 'preview-dt.' . $dt_ext; 
            $destFullName = $fullpath . $input['location'] . '-dt.' . $dt_ext; 
            error_log("addMediaSave: DT : sourceFullName : $sourceFullName :::: destFullName : $destFullName");
            \File::move($sourceFullName, $destFullName);

            // Get Mobile File ///////////////////////////////////////////
            $sourceFullName = $fullpath . 'preview-mb.' . $mb_ext; 
            $destFullName = $fullpath . $input['location'] . '-mb.' . $mb_ext; 
            error_log("addMediaSave: MB : sourceFullName : $sourceFullName :::: destFullName : $destFullName");
            \File::move($sourceFullName, $destFullName);

            $media = new \Media();
            $media->isp_id = \Brand::find($input['brand_id'])->isp_id;
            $media->brand_id = $input['brand_id'];
            $media->countrie_id = $input['countrie_id'];
            $media->province_id = $input['province_id'];
            $media->citie_id = $input['citie_id'];
            $media->venue_id = $input['venue_id'];
            $media->location = $input['location'];
            $media->dt_ext = $input['dt_ext'];
            $media->mb_ext = $input['mb_ext'];
            $media->welcome_flag = \Input::get("welcome_flag");
            $media->ef_group_pos = \Input::get("ef_group_pos");
            $media->ef_transparency = \Input::get("ef_transparency");
            $media->ef_colour = \Input::get("ef_colour");
            $media->ef_outline_text_colour = \Input::get("ef_outline_text_colour");
            $media->zonein_gap = \Input::get("zonein_gap");
            $media->zonein_btn_colour = \Input::get("zonein_btn_colour");
            $media->zone_txt_colour = \Input::get("zone_txt_colour");
            $media->faq_colour = \Input::get("faq_colour");
            $media->logo_choice = \Input::get("logo_choice");



            error_log("addMediaSave: addMediaSave : logo_choice : " . $media->logo_choice);
            error_log("addMediaSave: addMediaSave : loginprocess : " . $media->loginprocess);
            error_log("addMediaSave: addMediaSave : dt_ext : " . $media->dt_ext);
            error_log("addMediaSave: addMediaSave : mb_ext : " . $media->mb_ext);

            $media->save();

            $remotedb_id = \Brand::find($input['brand_id'])->remotedb->id;
            $venue = new \Venue();
            $venue->refreshMediaLocations();
            $venue->synchToRadius($remotedb_id);

        }
        $brand_id = $input['brand_id'];
        return \Redirect::route('hipwifi_showsinglebrandmedia' , ['id' => $brand_id]);
        // return \Redirect::route('hipwifi_showbrandmedia');
    }

    public function editMedia($id)
    {

        \Log::info("[HipwifiMediaController - editMedia]");
        error_log('editMedia ' . $id);
        
        $assetsserver = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsserver")->first();

        $data = array();
        $data['currentMenuItem'] = "Media Management";
        $data['edit'] = true;
        $logvar = $assetsserver->value . 'hipwifi/images/';
        // Hannes image 2
        $data['previewurl'] = $assetsserver->value . 'hipwifi/images/';
        \Log::info("[HipwifiMediaController - editMedia] - previewurl: $logvar");
        $data['media'] = \Media::find($id);
        $data['media']['brand_name'] = \Brand::find($data['media']['brand_id'])->name;
        $brandname = \Brand::find($data['media']['brand_id'])->name;
        \Log::info("[HipwifiMediaController - editMedia] - brand name: $brandname");
        $dt_ext = $data['media']->dt_ext;
        $mb_ext = $data['media']->mb_ext;

        if($data['media']->welcome_flag == 1) {
            $data['flag_on_checked'] = "checked";
            $data['flag_off_checked'] = "";
        } else {
            $data['flag_on_checked'] = "";
            $data['flag_off_checked'] = "checked";
        }

        if($data['media']->logo_choice == "black") {
            $data['logo_choice_black'] = "checked";
            $data['logo_choice_white'] = "";
        } else {
            $data['logo_choice_black'] = "";
            $data['logo_choice_white'] = "checked";
        }


        // Clear out any remaining files from the preview directory
        $assetsdirObj = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsdir")->first();
        $assetsfolder = $assetsdirObj->value . 'hipwifi/images/';

        $dt_preview = $assetsfolder . "preview-dt." . $dt_ext;
        $mb_preview = $assetsfolder . "preview-mb." . $mb_ext;

        $dt_file = $assetsfolder . $data['media']['location'] . '-dt.' . $dt_ext ;
        $mb_file = $assetsfolder . $data['media']['location'] . '-mb.' . $mb_ext ;

        // exec("touch " . $assetsfolder . "lalalala");
        // unlink($assetsfolder . "preview-dt.jpg");

        $this->deletePreviewfiles();

        // Copy in the files for preview
        if (\File::exists($dt_file)) {
            \Log::info("[HipwifiMediaController - editMedia] - preview file exists at path: $dt_file");
            \Log::info("[HipwifiMediaController - editMedia] - copying that preview file to: $dt_preview");
            \File::copy($dt_file, $dt_preview);
        }
        if (\File::exists($mb_file)) {
            \Log::info("[HipwifiMediaController - editMedia] - actual file exists at path: $mb_file");
            \Log::info("[HipwifiMediaController - editMedia] - copying that actual file to: $mb_preview");
            \File::copy($mb_file, $mb_preview);
        }
        
        return \View::make('hipwifi.addmedia')->with('data', $data);
    }

    public function editMediaSave()
    {
        // Hannes hier
        \Log::info("[HipwifiMediaController - editMediaSave]");
        $input = \Input::all();
        $id = \Input::get('id');
        $media =  \Media::find($id);
        \Log::info("[HipwifiMediaController - editMediaSave] media id after found: $media->id");
        $rules = array();
        \Log::info("[HipwifiMediaController - editMediaSave] - 1");
        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            \Log::info("[HipwifiMediaController - editMediaSave] - 2");
            $messages = $validator->messages();
            \Log::info("[HipwifiMediaController - editMediaSave] - error messages: $messages");

            return \Redirect::to('hipwifi_addmedia')->withErrors($validator)->withInput();

        } else {
            \Log::info("[HipwifiMediaController - editMediaSave] - 3");
            $dt_ext = \Input::get("dt_ext"); 
            $mb_ext = \Input::get("mb_ext");

            $assetsdir = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsdir")->first();
            $fullPath = $assetsdir->value . 'hipwifi/images/';

            \Log::info("[HipwifiMediaController - editMediaSave] - fullPath is: $fullPath");

            // Get Desktop File ///////////////////////////////////////////
            $sourceFullName = $fullPath . 'preview-dt.' . $dt_ext; 
            $destFullName = $fullPath . $input['location'] . '-dt.' . $dt_ext; 

            \Log::info("[HipwifiMediaController - editMediaSave] - sourceFullName is: $sourceFullName");
            \Log::info("[HipwifiMediaController - editMediaSave] - destFullName is: $destFullName");

            error_log("addMediaSave: DT : sourceFullName : $sourceFullName :::: destFullName : $destFullName");
            
            \File::move($sourceFullName, $destFullName);

            // Get Mobile File ///////////////////////////////////////////
            $sourceFullName = $fullPath . 'preview-mb.' . $mb_ext; 
            $destFullName = $fullPath . $input['location'] . '-mb.' . $mb_ext; 
            error_log("addMediaSave: MB : sourceFullName : $sourceFullName :::: destFullName : $destFullName");
            \File::move($sourceFullName, $destFullName);
            
            // $media->brand_id = $input['brand_id'];
            // $media->countrie_id = $input['countrie_id'];
            // $media->province_id = $input['province_id'];
            // $media->citie_id = $input['citie_id'];
            // $media->venue_id = $input['venue_id'];
            // $media->location = $input['location'];
            $media->dt_ext = \Input::get("dt_ext"); 
            $media->mb_ext = \Input::get("mb_ext"); 
            // $media->login_process = \Input::get("loginprocess"); // Mismatch in names do to a javascript name confusion
            $media->welcome_flag = \Input::get("welcome_flag");
            $media->ef_group_pos = \Input::get("ef_group_pos");

            $ef_group_pos = \Input::get("ef_group_pos");
            \Log::info("[HipwifiMediaController - editMediaSave] media id after found: $ef_group_pos");

            $media->ef_transparency = \Input::get("ef_transparency");
            $media->ef_colour = \Input::get("ef_colour");
            $media->ef_outline_text_colour = \Input::get("ef_outline_text_colour");
            $media->zonein_gap = \Input::get("zonein_gap");
            $media->zonein_btn_colour = \Input::get("zonein_btn_colour");
            $media->zone_txt_colour = \Input::get("zone_txt_colour");
            $media->faq_colour = \Input::get("faq_colour");
            $media->logo_choice = \Input::get("logo_choice");

            $media->connect_btn_enabled = \Input::get("connect_btn_enabled");
            $media->connect_btn_colour = \Input::get("connect_btn_colour");
            $media->connect_text_colour = \Input::get("connect_text_colour");
            $media->connect_btn_offset_from_top = \Input::get("connect_btn_offset_from_top");
            \Log::info("[HipwifiMediaController - editMediaSave] - 4");
            error_log("editMediaSave : logo_choice : " . $media->logo_choice);

            $media->save();

            $remotedb_id = \Brand::find($input['brand_id'])->remotedb->id;
            $venue = new \Venue();
            $venue->refreshMediaLocations();
            $venue->synchToRadius($remotedb_id);
            \Log::info("[HipwifiMediaController - editMediaSave] - 5");

        }
        $brand_id = $input['brand_id'];
        \Log::info("[HipwifiMediaController - editMediaSave] - 6");
        return \Redirect::route('hipwifi_showsinglebrandmedia' , ['id' => $brand_id]);
    }

    public function deleteMedia($id)
    {
        \Log::info("[HipwifiMediaController - deleteMedia]");
        error_log("deleteMedia");
        $media = \Media::find($id);

            error_log("addMediaSave: addMediaSave : logo_choice : " . $media->logo_choice);

        if($media) {
            $remotedb_id = $media->brand->remotedb_id;
            $brand_id = $media->brand->id;
            $media->delete();  

            $venue = new \Venue();
            $venue->refreshMediaLocations();
            $venue->synchToRadius($remotedb_id);
        }

        return \Redirect::route('hipwifi_showsinglebrandmedia' , ['id' => $brand_id]);
    }

    public function preview($type)
    {
        \Log::info("[HipwifiMediaController - preview]");
        $customerdir = \DB::table('systemconfig')->select("*")->where('name', '=', "customerdir")->first();
        $previewDirectory = $customerdir->value . 'hipwifi/images/';
        $dtfiles = count(glob($previewDirectory . "preview-dt*"));
        $mbfiles = count(glob($previewDirectory . "preview-mb*"));

        if($type == "dt") {

            if ($dtfiles) {
                return \View::make('hipwifi.preview-dt');
            } else {
                return \View::make('hipwifi.preview-nomedia');
            }

        } else {

            if ($mbfiles) {
                return \View::make('hipwifi.preview-mb');
            } else {
                return \View::make('hipwifi.preview-nomedia');
            }
            
        }
    }

}