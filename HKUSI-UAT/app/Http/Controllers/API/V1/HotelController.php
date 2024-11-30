<?php
namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\VerifyTokenStatus;
use App\Models\HotelSetting;
use DB;
use Storage, Config, Validator;

class HotelController extends Controller
{
    use VerifyTokenStatus;

    // Hotel Api Controller created By Akash
    public function __construct()
    {
        $this->apiArray = array();
        $this->apiArray['error'] = true;
        $this->apiArray['message'] = '';
        $this->apiArray['errorCode'] = 4;
        // $this->DISK_NAME = Config::get('DISK_NAME');
    }

    public function getHotelList(Request $request)
    {
    	try {
            $inputs = $request->all();
            $this->apiArray['state'] = 'getHotelList';
            $userinfo = $request->user('sanctum');
            /*Check header */
            $headers = getallheaders();
            if (!$this->verifyTokens($headers['Authkey'])){
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 401);
            }
            /*End*/
            $hotelData = HotelSetting::where('status','Enabled');
            
            $DISK_NAME = Config::get('DISK_NAME');
           
            
            if(isset($inputs['search_keyword']) && !empty($inputs['search_keyword'])){
                $search = $inputs['search_keyword'];
                $hotelData = $hotelData->where(function($query) use($search) {
                    $query->where('hotel_name', 'like', '%' . $search . '%')
                          ->orWhere('location', 'like', '%' . $search . '%')
                          ->orWhere('description', 'like', '%' . $search . '%')
                          ->orWhere('price_range', 'like', '%' . $search . '%');
                });
            }
            
            $hotelData = $hotelData->orderBy('created_at','DESC')->paginate(9);
  
            if(count($hotelData)){
                foreach ($hotelData as $key => $value) {

                    $data[$key]['hotel_id'] = $value->id;
                    $data[$key]['hotel_name'] = $value->hotel_name;
                    $data[$key]['short_description'] = $value->short_description;
                    $data[$key]['description'] = $value->description;
                    $data[$key]['location'] = $value->location;
                    $data[$key]['distance'] = $value->distance;
                    $data[$key]['price_range'] = $value->price_range;
                    $data[$key]['website'] = $value->website;
                    $data[$key]['download_form_url'] = $value->download_form_url;
                    $data[$key]['map_url'] = $value->map_url;
                    
                    $data[$key]['image'] = (isset($value->thumbnail) && $value->thumbnail != '' && Storage::disk($DISK_NAME)->exists($value->thumbnail))?asset(Storage::url($value->thumbnail)):asset('img/default-image.jpg');

                }
                $this->apiArray['data'] = $data;
                $this->apiArray['totalPage']    =   $hotelData->lastPage();
                $this->apiArray['message'] = 'Success';
                $this->apiArray['errorCode'] = 0;
                $this->apiArray['error'] = false;
                return response()->json($this->apiArray, 200);
            }
            $this->apiArray['message'] = 'No hotel found.';
            $this->apiArray['errorCode'] = 3;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 200);            
        } catch (\Exception $e) {
            $this->apiArray['message'] = 'Something is wrong, please try after some time'.$e->getMessage();
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 200);
        }
    }

    public function getHotelDetail(Request $request)
    {
    	try {
            $inputs = $request->all();
            $this->apiArray['state'] = 'getHotelDetail';
            /*Check header */
            $headers = getallheaders();
            if (!$this->verifyTokens($headers['Authkey'])){
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 401);
            }
            $inputs = $request->all();
            $validator = Validator::make($inputs, [
                'hotel_id' => 'required',
            ]);
            if($validator->fails()){
                $this->apiArray['message'] = $validator->messages()->first();
                $this->apiArray['errorCode'] = 2;
                $this->apiArray['error'] = true;
                return response()->json($this->apiArray, 200);
            }
            $userinfo = $request->user('sanctum');
            /*End*/
            $DISK_NAME = Config::get('DISK_NAME');
            $hotelData = HotelSetting::where('status','Enabled')->where('id',$inputs['hotel_id'])->first();
            if(!empty($hotelData)){
                $data['hotel_id'] = $hotelData->id;
                $data['hotel_name'] = $hotelData->hotel_name;
                $data['short_description'] = $hotelData->short_description;
                $data['description'] = $hotelData->description;
                $data['location'] = $hotelData->location;
                $data['distance'] = $hotelData->distance;
                $data['price_range'] = $hotelData->price_range;
                $data['website'] = $hotelData->website;
                $data['download_form_url'] = $hotelData->download_form_url;
                $data['remark'] = $hotelData->remark;
                $data['property_amenities_description'] = $hotelData->property_amenities_description;
                $data['transportation_method_description'] = $hotelData->transportation_method_description;
                $data['notes_description'] = $hotelData->notes_description;
                $data['map_url'] = $hotelData->map_url;
                $data['status'] = $hotelData->status;
                
                $data['image'] = (isset($hotelData->thumbnail) && $hotelData->thumbnail != '' && Storage::disk($DISK_NAME)->exists($hotelData->thumbnail))?asset(Storage::url($hotelData->thumbnail)):asset('img/default-image.jpg');
				
				$data['map_photo'] = (isset($hotelData->map_photo) && $hotelData->map_photo != '' && Storage::disk($DISK_NAME)->exists($hotelData->map_photo))?asset(Storage::url($hotelData->map_photo)):asset('img/default-image.jpg');

                if(isset($hotelData->getHotelImages) && count($hotelData->getHotelImages)){
                    foreach ($hotelData->getHotelImages as $key => $value) {
                        $data['slider_image'][$key]['image_url'] = (isset($value->image) && $value->image != '' && Storage::disk($DISK_NAME)->exists($value->image))?asset(Storage::url($value->image)):asset('img/default-image.jpg');
                    }
                }

                $ro = 0;
                if ( $hotelData->room_type_name_1 != '' ) {
                	
                	$data['room_info'][$ro]['type_name'] = $hotelData->room_type_name_1 ;
                	$data['room_info'][$ro]['type_description'] = $hotelData->room_type_description_1 ;
                	$data['room_info'][$ro]['image'] = (isset($hotelData->room_type_thumbnail_1) && $hotelData->room_type_thumbnail_1 != '' && Storage::disk($DISK_NAME)->exists($hotelData->room_type_thumbnail_1))?asset(Storage::url($hotelData->room_type_thumbnail_1)):asset('img/default-image.jpg');

                    $ro = $ro + 1;
                }

                if ( $hotelData->room_type_name_2 != '' ) {

                	$data['room_info'][$ro]['type_name'] = $hotelData->room_type_name_2 ;
                	$data['room_info'][$ro]['type_description'] = $hotelData->room_type_description_2 ;
                	$data['room_info'][$ro]['image'] = (isset($hotelData->room_type_thumbnail_2) && $hotelData->room_type_thumbnail_2 != '' && Storage::disk($DISK_NAME)->exists($hotelData->room_type_thumbnail_2))?asset(Storage::url($hotelData->room_type_thumbnail_2)):asset('img/default-image.jpg');

                    $ro = $ro + 1;
                }

                if ( $hotelData->room_type_name_3 != '' ){
                	$data['room_info'][$ro]['type_name'] = $hotelData->room_type_name_3 ;
                	$data['room_info'][$ro]['type_description'] = $hotelData->room_type_description_3 ;
                	$data['room_info'][$ro]['image'] = (isset($hotelData->room_type_thumbnail_3) && $hotelData->room_type_thumbnail_3 != '' && Storage::disk($DISK_NAME)->exists($hotelData->room_type_thumbnail_3))?asset(Storage::url($hotelData->room_type_thumbnail_3)):asset('img/default-image.jpg');

                    $ro = $ro + 1;
                }


                $this->apiArray['data'] = $data;
                $this->apiArray['message'] = 'Success';
                $this->apiArray['errorCode'] = 0;
                $this->apiArray['error'] = false;
                return response()->json($this->apiArray, 200);
            }
            $this->apiArray['message'] = 'No hotel found.';
            $this->apiArray['errorCode'] = 3;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 200);            
        } catch (\Exception $e) {
            $this->apiArray['message'] = 'Something is wrong, please try after some time'.$e->getMessage();
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 200);
        }
    }
}