<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\UploadTraits;
use App\Models\HotelSetting;
use App\Models\HallSetting;
use App\Models\HotelSettingImage;

class HotelSettingController extends Controller
{
    use UploadTraits;
	
	// Hotel Setting Controller created By Akash

    public function index(Request $request)
    {
        $headerTitle = "Hotel Setting";
        return view('admin.hotel-setting.index',compact('headerTitle'));
    }    

    public function create(){
        $headerTitle = "Hotel Create";
        $HallSetting = HallSetting::where('status',1)->get();
        return view('admin.hotel-setting.create',compact('headerTitle','HallSetting'));
    }
    
    public function hotelSettingDetails(Request $request , $id ,$type){ 
        $dataId = $id;        
        $dataType = $type;
        $hotelImages = $hotelInfo = $HallSetting = [];
        $hotelInfo = HotelSetting::find($id);
        if ($dataType=="show") {
            $headerTitle = "Hotel Details";
        }elseif($dataType=="edit"){
            $HallSetting = HallSetting::where('status',1)->get();
            $headerTitle = "Hotel Edit";
        }elseif($dataType=="images"){
            $headerTitle = "Hotel Details";
            $hotelImages = HotelSettingImage::where('hotel_id',$hotelInfo->id)->get();
        }elseif($dataType=="editimage"){
            $headerTitle = "Hotel Details";
            $hotelImages = HotelSettingImage::where('hotel_id',$hotelInfo->id)->get();
        }else{
            return redirect()->route('admin.hotel-setting.index');
        }
        if (!empty($hotelInfo)) {
            return view('admin.hotel-setting.comman',compact('headerTitle','hotelInfo','dataId','dataType','hotelImages', 'HallSetting'));          
        }else{
            return redirect()->route('admin.hotel-setting.index');
        }        
    }


    public function store(Request $request){
        $input = $request->all();
        //dd($input);
         $this->validate($request, [
           'hotel_name'                     => 'required',
        ]); 
        $thumbnail = $map_photo = $room_type_thumbnail_1 = $room_type_thumbnail_2 = $room_type_thumbnail_3 = '';
        if (isset($input['thumbnail']) && !empty($input['thumbnail'])) {
            $image = $this->uploadSingleImage($input['thumbnail'],'hotel','');
            if ($image != "") {
                
                $thumbnail = $image;
            }
        }

        if (isset($input['map_photo']) && !empty($input['map_photo'])) {
            $image = $this->uploadSingleImage($input['map_photo'],'hotel','');
            if ($image != "") {
                
                $map_photo = $image;
            }
        }

        if (isset($input['room_type_thumbnail_1']) && !empty($input['room_type_thumbnail_1'])) {
            $image = $this->uploadSingleImage($input['room_type_thumbnail_1'],'hotel','');
            if ($image != "") {
                
                $room_type_thumbnail_1 = $image;
            }
        }

        if (isset($input['room_type_thumbnail_2']) && !empty($input['room_type_thumbnail_2'])) {
            $image = $this->uploadSingleImage($input['room_type_thumbnail_2'],'hotel','');
            if ($image != "") {
                
                $room_type_thumbnail_2 = $image;
            }
        }

        if (isset($input['room_type_thumbnail_3']) && !empty($input['room_type_thumbnail_3'])) {
            $image = $this->uploadSingleImage($input['room_type_thumbnail_3'],'hotel','');
            if ($image != "") {
                
                $room_type_thumbnail_3 = $image;
            }
        }

        $hotelData                           			  =  new HotelSetting();
        $hotelData['hall_setting_id']                     =  $input['hall_setting_id'];
        $hotelData['hotel_name']        	 			  =  $input['hotel_name'];
        $hotelData['description']      		 			  =  $input['description'];
        $hotelData['short_description']                   =  $input['short_description'];
        $hotelData['location']      		 			  =  $input['location'];
        $hotelData['distance']      		 			  =  $input['distance'];
        $hotelData['price_range']      		 			  =  $input['price_range'];
        $hotelData['website']      		 	 			  =  $input['website'];
        $hotelData['download_form_url']                   =  $input['download_form_url'];
        $hotelData['remark']      			 			  =  $input['remark'];
        $hotelData['property_amenities_description']      =  $input['property_amenities_description'];
        $hotelData['transportation_method_description']   =  $input['transportation_method_description'];
        $hotelData['notes_description']      			  =  $input['notes_description'];
        $hotelData['thumbnail']      					  =  $thumbnail;
        $hotelData['map_photo']      					  =  $map_photo;
        $hotelData['map_url']      	 					  =  $input['map_url'];
        $hotelData['status']                             =  $input['status'];

        //Room types
        $hotelData['room_type_name_1']      	 =  $input['room_type_name_1'];
        $hotelData['room_type_description_1']    =  $input['room_type_description_1'];
        $hotelData['room_type_thumbnail_1']      =  $room_type_thumbnail_1;

        $hotelData['room_type_name_2']      	 =  $input['room_type_name_2'];
        $hotelData['room_type_description_2']    =  $input['room_type_description_2'];
        $hotelData['room_type_thumbnail_2']      =  $room_type_thumbnail_2;

        $hotelData['room_type_name_3']      	 =  $input['room_type_name_3'];
        $hotelData['room_type_description_3']    =  $input['room_type_description_3'];
        $hotelData['room_type_thumbnail_3']      =  $room_type_thumbnail_3;

        $hotelData->save();

        $last_id = $hotelData->id;
        $date = date('Y-m-d H:i');
        

        if(isset($hotelData->id)){
            if (isset($input['images']) && count($input['images'])) {
                foreach ($input['images'] as $key => $imageValue) {
                    $hotel_main_image = '';

                    if (isset($imageValue) && !empty($imageValue)) {
                        $images = $this->uploadSingleImage($imageValue,'hotel','');
                        if ($images != "") {
                            
                            $hotel_main_image = $images;
                        }
                    }
                    HotelSettingImage::insert(['hotel_id'=>$hotelData->id,'image'=>$hotel_main_image]);
                }
            }
        }
        return redirect()->route('admin.hotel-setting.index')->with('message', 'Hotel create successfully.');

    }




    public function multipleHotelDelete(Request $request)
	{        
        $input = $request->all();
        if (isset($input['id']) && count($input['id'])) {
            foreach ($input['id'] as $hotel) {
                if (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Delete') {
                    
                    HotelSetting::where('id', $hotel)->delete();
                    HotelSettingImage::where('hotel_id', $hotel)->delete();

                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Disabled'){
                    $hallbookingRecord = HotelSetting::where('id',$hotel)->update(['status'=>'Disabled']);
                    
                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Enabled'){
                    HotelSetting::where('id',$hotel)->update(['status'=>'Enabled']);
                }
            }
        }
		return redirect()->back();
	}

    public function update(Request $request, $id){
        $input = $request->all();
        
        if (isset($input['submit_type']) && !empty($input['submit_type']) && $input['submit_type'] == 'basic') {

            $hotelData   =  HotelSetting::find($id);
            $hotelData['hall_setting_id']                     =  $input['hall_setting_id'];
            $hotelData['hotel_name']                         =  $input['hotel_name'];
            $hotelData['description']                         =  $input['description'];
            $hotelData['short_description']                   =  $input['short_description'];
            $hotelData['location']                            =  $input['location'];
            $hotelData['distance']                            =  $input['distance'];
            $hotelData['price_range']                         =  $input['price_range'];
            $hotelData['website']                             =  $input['website'];
            $hotelData['download_form_url']                   =  $input['download_form_url'];
            $hotelData['remark']                              =  $input['remark'];
            $hotelData['property_amenities_description']      =  $input['property_amenities_description'];
            $hotelData['transportation_method_description']   =  $input['transportation_method_description'];
            $hotelData['notes_description']                   =  $input['notes_description'];
            $hotelData['map_url']                             =  $input['map_url'];

            //Room types
            $hotelData['room_type_name_1']           =  $input['room_type_name_1'];
            $hotelData['room_type_description_1']    =  $input['room_type_description_1'];

            $hotelData['room_type_name_2']           =  $input['room_type_name_2'];
            $hotelData['room_type_description_2']    =  $input['room_type_description_2'];

            $hotelData['room_type_name_3']           =  $input['room_type_name_3'];
            $hotelData['room_type_description_3']    =  $input['room_type_description_3'];
            
            $hotelData['status']    =  $input['status'];

            $hotelData->save();

        // Images update
        }elseif(isset($input['submit_type']) && !empty($input['submit_type']) && $input['submit_type'] == 'images'){

            HotelSettingImage::where('hotel_id',$id)->delete();
            if (isset($input['images']) && count($input['images'])) {
                foreach ($input['images'] as $key => $imageValue) {
                    $hotel_image = '';

                    if (isset($imageValue) && !empty($imageValue)) {
                        $images = $this->uploadSingleImage($imageValue,'hotel','');
                        if ($images != "") {
                            
                            $hotel_image = $images;
                        }
                    }
                    HotelSettingImage::insert(['hotel_id'=>$id,'image'=>$hotel_image]);
                }
            }
            if (isset($input['old_images']) && count($input['old_images'])) {
                foreach ($input['old_images'] as $key => $imageValue) {
                    $old_hotel_image = $imageValue;
                    HotelSettingImage::insert(['hotel_id'=>$id,'image'=>$old_hotel_image]);
                }
            }

            $hotelData   =  HotelSetting::find($id);

            $thumbnail = $map_photo = $room_type_thumbnail_1 = $room_type_thumbnail_2 = $room_type_thumbnail_3 = '';

            if (isset($input['thumbnail']) && !empty($input['thumbnail'])) {
            
                $image = $this->uploadSingleImage($input['thumbnail'],'hotel','');
                if ($image != "") {
                    
                    $thumbnail = $image;
                    $hotelData['thumbnail']                           =  $thumbnail;
                }
            }

            if (isset($input['map_photo']) && !empty($input['map_photo'])) {
                $image = $this->uploadSingleImage($input['map_photo'],'hotel','');
                if ($image != "") {
                    
                    $map_photo = $image;
                    $hotelData['map_photo']                           =  $map_photo;

                }
            }

            if (isset($input['room_type_thumbnail_1']) && !empty($input['room_type_thumbnail_1'])) {
                $image = $this->uploadSingleImage($input['room_type_thumbnail_1'],'hotel','');
                if ($image != "") {
                    
                    $room_type_thumbnail_1 = $image;
                    $hotelData['room_type_thumbnail_1']      =  $room_type_thumbnail_1;

                }
            }

            if (isset($input['room_type_thumbnail_2']) && !empty($input['room_type_thumbnail_2'])) {
                $image = $this->uploadSingleImage($input['room_type_thumbnail_2'],'hotel','');
                if ($image != "") {
                    
                    $room_type_thumbnail_2 = $image;
                    $hotelData['room_type_thumbnail_2']      =  $room_type_thumbnail_2;

                }
            }

            if (isset($input['room_type_thumbnail_3']) && !empty($input['room_type_thumbnail_3'])) {
                $image = $this->uploadSingleImage($input['room_type_thumbnail_3'],'hotel','');
                if ($image != "") {
                    
                    $room_type_thumbnail_3 = $image;
                    $hotelData['room_type_thumbnail_3']      =  $room_type_thumbnail_3;

                }
            }

            $hotelData->save();

            return redirect()->back()->with('success' ,'Hotel images update successfully.');
        }

        return redirect()->route('admin.hotel-setting.index')->with('success' ,'Hotel update successfully.');
    }
    
    public function destroy($id)
    {
        HotelSetting::find($id)->delete();
        return redirect()->route('admin.hotel-setting.index')->with('success', 'Hotel Setting deleted successfully');
    }
}
