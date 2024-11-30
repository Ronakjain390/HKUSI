<?php
namespace App\Http\Controllers\API\V1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Traits\VerifyTokenStatus;
use App\Models\EventSetting;
use App\Models\EventBooking;
use App\Models\EventPayment;
use App\Models\MemberEventCart;
use App\Models\PrivateEventSetting;
use App\Models\MemberProgramme;
use App\Models\EventProgramme;
use App\Models\PrivateEventOrder;
use DB;
use Storage, Config, Validator;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EventController extends Controller
{
    use VerifyTokenStatus;

    public function __construct()
    {
        $this->apiArray = array();
        $this->apiArray['error'] = true;
        $this->apiArray['message'] = '';
        $this->apiArray['errorCode'] = 4;
        // $this->DISK_NAME = Config::get('DISK_NAME');
    }

    /* Get event filter API by Ashish */
    public function getEventFilter(Request $request){
        try {
            $inputs = $request->all();
            $this->apiArray['state'] = 'getEventFilter';
            /*Check header */
            $headers = getallheaders();
            if (!$this->verifyTokens($headers['Authkey'])){
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 401);
            }
            /*End*/
            $eventData = EventSetting::where('status','Enabled')->groupBy('event_category_id')->get();
            $category = [];
            $language = [];
            if(count($eventData)){
                $c = 0;
                foreach ($eventData as $key => $value) {
                    if(isset($value->getCategoryDetails) && !empty($value->getCategoryDetails)){
                        $category[$c]['category_id'] = $value->getCategoryDetails->id;
                        $category[$c]['category_name'] = $value->getCategoryDetails->name;
                        $c++;
                    }
                }
            }
            $eventData = EventSetting::where('status','Enabled')->groupBy('language_id')->get();
            $language = [];
            if(count($eventData)){
                $l = 0;
                foreach ($eventData as $key => $value) {
                    if(isset($value->getLanguage) && !empty($value->getLanguage)){
                        $language[$l]['language_id'] = $value->getLanguage->id;
                        $language[$l]['language_name'] = $value->getLanguage->name;
                        $l++;
                    }
                }
            }
            $sort_by = [];
            $sort = ['Activity fee (low - high)','Activity fee (high - low)','Name (A-Z)','Name (Z-A)'];
            for ($i=0; $i <4 ; $i++) {
                $sort_by[$i]['value'] = $i+1;
                $sort_by[$i]['label'] = $sort[$i];
            }

			$price_range = [];
			$eventData = EventSetting::select(\DB::raw("MIN(unit_price) AS MinPrice, MAX(unit_price) AS MaxPrice"))->where('status','Enabled')->first();

            if(!empty($eventData) || !empty($privateEventData)){

                $minPrice = ( @$eventData->MinPrice ) ? $eventData->MinPrice : 0;
                $maxPrice = ( @$eventData->MaxPrice ) ? $eventData->MaxPrice : 0;

                $i=0;
                $price_range[0]['range_name'] = 'Free';
                $i++;
                if(!empty($minPrice)){
					if($minPrice==$maxPrice){
						$price_range[1]['range_name'] = "1-".$minPrice;
					} else {
						$tmp = 1;
						while($tmp<=$maxPrice){
							$price_range[$i]['range_name'] = $tmp."-".$tmp+99;
							$tmp+=100;
							$i++;
						}
					}
				} else if(isset($minPrice) && $minPrice==0){
					if($minPrice==$maxPrice){

					} else {
						$tmp = 1;
						while($tmp<=$maxPrice){
							$price_range[$i]['range_name'] = $tmp."-".$tmp+99;
							$tmp+=100;
							$i++;
						}
					}
				} else {
					$price_range = [];
				}
            }

            $data['category'] = $category;
            $data['language'] = $language;
            $data['sort_by'] = $sort_by;
            $data['price_range'] = $price_range;

            $this->apiArray['data'] = $data;
            $this->apiArray['message'] = 'Success';
            $this->apiArray['errorCode'] = 0;
            $this->apiArray['error'] = false;
            return response()->json($this->apiArray, 200);
        } catch (\Exception $e) {
            $this->apiArray['message'] = 'Something is wrong, please try after some time'.$e->getMessage();
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 200);
        }
    }
    /* End */


    /* Get get event list API by Ashish */
    public function getEventList(Request $request){
        try {
            $inputs = $request->all();
            $this->apiArray['state'] = 'getEventList';
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
            $eventData = EventSetting::where('status','Enabled');

            $DISK_NAME = Config::get('DISK_NAME');
            if(isset($userinfo) && !empty($userinfo)){
                $validEventIdArr = array(0);
                $memberProgrammeData = MemberProgramme::join('programmes','programmes.id','=','member_programmes.programme_id')->where('member_programmes.member_info_id',@$userinfo->getMemberInfo->id)->get();
                if(!empty($memberProgrammeData)){
                    foreach($memberProgrammeData as $key => $memberProgramme) {
                        $eventIdArr = EventSetting::where('event_settings.status','Enabled')->whereBetween("date",[$memberProgramme->start_date,$memberProgramme->end_date])->get();
                        if(!empty($eventIdArr)){
                            foreach($eventIdArr as $key => $eventId) {
                                $eventIdCount = EventProgramme::where('event_id',$eventId->id)->where("program_id",$memberProgramme->programme_id)->count();
                                if($eventIdCount > 0){
                                    $validEventIdArr[] = $eventId->id;
                                }
                            }
                        }
                    }
                }
                $eventData = $eventData->whereIn('event_settings.id',$validEventIdArr);
            }

            if(isset($inputs['category']) && !empty($inputs['category'])){
                $eventData = $eventData->where("event_category_id",$inputs['category']);
            }
            if(isset($inputs['search_keyword']) && !empty($inputs['search_keyword'])){
                $search = $inputs['search_keyword'];
                $eventData = $eventData->where(function($query) use($search) {
                    $query->where('event_name', 'like', '%' . $search . '%')
                          ->orWhere('short_description', 'like', '%' . $search . '%');
                });
            }
            if(isset($inputs['language']) && !empty($inputs['language'])){
                $eventData = $eventData->where("language_id",$inputs['language']);
            }
            if(isset($inputs['price_range']) && !empty($inputs['price_range'])){
                if($inputs['price_range']=='Free'){
                    $eventData = $eventData->whereNull("unit_price");
                }else{
                    $price = explode("-", $inputs['price_range']);
                    if(count($price)>1){
                        $eventData = $eventData->whereBetween("unit_price",[$price[0],$price[1]])->whereNotNull("unit_price");
                    }
                }
            }
            if(isset($inputs['period']) && !empty($inputs['period'])){
                $period = explode("/", $inputs['period']);
                if(count($period)>1){
                    $eventData = $eventData->whereBetween("date",[strtotime($period[0]),strtotime($period[1])]);
                }
            }
            if(isset($inputs['sort_by']) && !empty($inputs['sort_by'])){
                if($inputs['sort_by']=='1'){
                    $eventData = $eventData->orderBy('unit_price','ASC');
                }elseif($inputs['sort_by']=='2'){
                    $eventData = $eventData->orderBy('unit_price','DESC');
                }elseif($inputs['sort_by']=='3'){
                    $eventData = $eventData->orderBy('event_name','ASC');
                }elseif($inputs['sort_by']=='4'){
                    $eventData = $eventData->orderBy('event_name','DESC');
                }else{
                    $eventData = $eventData->orderBy('id','DESC');
                }
            }
            $eventData = $eventData->paginate(9);

            if(count($eventData)){
                foreach ($eventData as $key => $value) {
                    $data[$key]['event_id'] = $value->id;
                    $data[$key]['event_name'] = $value->event_name;
                    $data[$key]['category'] = $value->getCategoryDetails->name ?? '';;
                    $data[$key]['description'] = $value->short_description;
                    $data[$key]['date'] = date("Y-m-d",$value->date);
                    $data[$key]['time'] = date("H:i",@$value->start_time)."-".date("H:i",@$value->end_time);
                    $data[$key]['location'] = $value->location;
                    $data[$key]['price'] = $value->unit_price;
                    $data[$key]['status'] = '';

                    $data[$key]['image'] = (isset($value->main_image) && $value->main_image != '' && Storage::disk($DISK_NAME)->exists($value->main_image))?asset(Storage::url($value->main_image)):asset('img/default-image.jpg');

                    if($value->quota_balance>0){
                        $data[$key]['status'] = '';
                    }
                    $programmeDate = '';
                    $userProgramme = '';
                    if(isset($value->getEventProgrammes) && count($value->getEventProgrammes)){
                        foreach ($value->getEventProgrammes as $keypro => $valuepro){
                            if(isset($valuepro->getProgrammeDetailApi) && !empty($valuepro->getProgrammeDetailApi)){
                                if(isset($userinfo) && !empty($userinfo)){
                                    if($valuepro->getProgrammeDetailApi->checkMemberProgramme($userinfo->getMemberInfo->id)){
                                        $userProgramme = 'Yes';
                                    }
                                }
                                if($valuepro->getProgrammeDetailApi->start_date<=$value->date && $valuepro->getProgrammeDetailApi->end_date>=$value->date){
                                    $programmeDate = 'Yes';
                                }
                            }
                        }
                    }
                    else{
                       //  $data[$key]['status'] = 'Non-available';
                    }

                    if(empty($programmeDate)){
                        // $data[$key]['status'] = 'Non-available1';
                    }

                    if($value->quota_balance<=0){
                        $data[$key]['status'] = 'FULL';
                    }

                    if($value->date < time()){
                        $data[$key]['status'] = 'Closed';
                    }

                    if($value->application_deadline < time()){
                        $data[$key]['status'] = 'Non-available';
                    }
                    if(isset($userinfo) && !empty($userinfo)){
                        /*if(empty($userProgramme)){
                            $data[$key]['status'] = 'Non-available';
                        }*/

                        if(!empty($value->checkMemberEvent($userinfo->getMemberInfo->application_number))){
                            $data[$key]['status'] = 'Booked';
                        }
                    }

                }
                $this->apiArray['data'] = $data;
                $this->apiArray['totalPage']    =   $eventData->lastPage();
                $this->apiArray['message'] = 'Success';
                $this->apiArray['errorCode'] = 0;
                $this->apiArray['error'] = false;
                return response()->json($this->apiArray, 200);
            }
            $this->apiArray['message'] = 'No event found.';
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
    /* End */


    /* Get get event detail API by Ashish */
    public function getEventDetail(Request $request){
        try {
            $inputs = $request->all();
            $this->apiArray['state'] = 'getEventDetail';
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
                'event_id' => 'required',
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
            $eventData = EventSetting::where('status','Enabled')->where('id',$inputs['event_id'])->first();
            if(!empty($eventData)){
                $data['event_id'] = $eventData->id;
                $data['event_name'] = $eventData->event_name;
                $data['category'] = $eventData->getCategoryDetails->name ?? '';
                $data['description'] = $eventData->description;
                $data['short_description'] = $eventData->short_description;
                $data['date'] = date("Y-m-d",$eventData->date);
                $data['time'] = date("H:i",@$eventData->start_time)."-".date("H:i",@$eventData->end_time);
                $data['location'] = $eventData->location;
                $data['price'] = $eventData->unit_price;
                $data['max_ticket_limit'] = $eventData->booking_limit;
                $data['terms_condition'] = $eventData->terms_condition;
                $data['terms_link'] = $eventData->terms_link;
                $data['pre_arrival'] = $eventData->pre_arrival;
                $data['pre_link'] = $eventData->pre_link;
                $data['notes'] = $eventData->notes;
                $data['additional_info'] = $eventData->additional_info;
                $data['status'] = '';
                $data['image'] = (isset($eventData->main_image) && $eventData->main_image != '' && Storage::disk($DISK_NAME)->exists($eventData->main_image))?asset(Storage::url($eventData->main_image)):asset('img/default-image.jpg');

                if(isset($eventData->getEventImages) && count($eventData->getEventImages)){
                    foreach ($eventData->getEventImages as $key => $value) {
                        $data['slider_image'][$key]['image_url'] = (isset($value->main_image) && $value->main_image != '' && Storage::disk($DISK_NAME)->exists($value->main_image))?asset(Storage::url($value->main_image)):asset('img/default-image.jpg');
                    }
                }

                if($eventData->quota_balance>0){
                     $data['status'] = '';
                }
                $programmeDate = '';
                $userProgramme = '';
                if(isset($eventData->getEventProgrammes) && count($eventData->getEventProgrammes)){
                    foreach ($eventData->getEventProgrammes as $keypro => $valuepro){
                        if(isset($valuepro->getProgrammeDetailApi) && !empty($valuepro->getProgrammeDetailApi)){
                            if($valuepro->getProgrammeDetailApi->start_date<=$eventData->date && $valuepro->getProgrammeDetailApi->end_date>=$eventData->date){
                                $programmeDate = 'Yes';
                            }
                            if(isset($userinfo) && !empty($userinfo)){
                                if($valuepro->getProgrammeDetailApi->checkMemberProgramme($userinfo->getMemberInfo->id)){
                                    $userProgramme = 'Yes';
                                }
                            }
                        }
                    }
                }else{
                    //$data['status'] = 'Non-available';
                }


                if(empty($programmeDate)){
                     //$data['status'] = 'Non-available';
                }
                if($eventData->quota_balance<=0){
                    $data['status'] = 'FULL';
                }
                if($eventData->date<time()){
                     $data['status'] = 'Closed';
                }
                if($eventData->application_deadline < time()){
                     $data['status'] = 'Non-available';
                }
                $data['added_in_cart'] = false;
                if(isset($userinfo) && !empty($userinfo)){
                    if(empty($userProgramme)){
                        $data['status'] = 'Non-available';
                    }
                    if(!empty($eventData->checkMemberEvent($userinfo->getMemberInfo->application_number))){
                        $data['status'] = 'Booked';
                    }

                    if(MemberEventCart::where('application_id',$userinfo->getMemberInfo->application_number)->where('event_id',$eventData->id)->exists()){
                       $data['added_in_cart'] = true;
                    }
                }
                $this->apiArray['data'] = $data;
                $this->apiArray['message'] = 'Success';
                $this->apiArray['errorCode'] = 0;
                $this->apiArray['error'] = false;
                return response()->json($this->apiArray, 200);
            }
            $this->apiArray['message'] = 'No event found.';
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
    /* End */


    /* Check event data */
    public function checkEventDetail(Request $request){
        try {
            $inputs = $request->all();
			$this->apiArray['state'] = 'checkEventDetail';
            /*Check header */
            $headers = getallheaders();
            if (!$this->verifyTokens($headers['Authkey'])){
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 401);
            }
            /*End*/

            if(count($inputs)){
                foreach ($inputs as $key => $value) {
                    $inputs1['event_id'][$key] = $value['event_id'];

                    if ( isset($value['event_type']) ) {
                        $inputs1['event_type'][$key] = $value['event_type'];
                    }else{
                        $inputs1['event_type'][$key] = null;
                    }
                    if(isset($value['no_of_tickets'])){
                        $inputs1['no_of_tickets'][$key] = $value['no_of_tickets'];
                    }else{
                        $inputs1['no_of_tickets'][$key] = 1;
                    }

					if(isset($value['type'])){
                        $inputs1['request_type'][$key] = $value['type'];
                    }else{
                        $inputs1['request_type'][$key] = null;
                    }
				}
            }
            $validator = Validator::make($inputs1, [
                'event_id' => ['required']
            ]);
			if($validator->fails()){
                $this->apiArray['message'] = $validator->messages()->first();
                $this->apiArray['errorCode'] = 2;
                $this->apiArray['error'] = true;
                return response()->json($this->apiArray, 200);
            }

            $userinfo = $request->user('sanctum');
            $errorMessage = '';
            if(count($inputs1['event_id'])){
				foreach ($inputs1['event_id'] as $key => $value) {
					$request_type = $inputs1['request_type'][$key];
                    $eventData = EventSetting::where('id',$value)->where('status','Enabled')->first();

					if(!empty($eventData->quota_balance) && $eventData->quota_balance <= 0){
						//$errorMessage.= "We regret to inform you that the current quota for ".$eventData->event_name." has been fully booked, and unfortunately, we are unable to process your reservation request.";
						$this->apiArray['message'] = "We regret to inform you that the current quota for ".$eventData->event_name." has been fully booked, and unfortunately, we are unable to process your reservation request.";
						$this->apiArray['errorCode'] = 4;
						$this->apiArray['error'] = true;
						return response()->json($this->apiArray, 200);
					}

					$bookedEventList = EventBooking::where('application_id',@$userinfo->getMemberInfo->application_number)->whereNotIn('booking_status',array('Cancelled','Completed'))->get('event_id');
					if(!empty($bookedEventList)){
						foreach($bookedEventList as $BookedEvent){
							$bookedEventData = EventSetting::where('id',@$BookedEvent->event_id)->where('status','Enabled')->first();

                            //echo "booked_date :".date('Y-m-d',@$bookedEventData->date)."</br>booked_start_time:".date('H:i',@$bookedEventData->start_time)."</br>booked_end_time:".date('H:i',@$bookedEventData->end_time)."</br>date:".date("Y-m-d",$eventData->date)."</br>start_time:".date("H:i",$eventData->start_time)."</br>end_time".date("H:i",$eventData->end_time);
                            //die;

							$bookedEventDate = strtotime(date('Y-m-d',@$bookedEventData->date));
							$bookedEventStartTime = strtotime(date('H:i',$bookedEventData->start_time));
							$bookedEventEndTime = strtotime(date('H:i',$bookedEventData->end_time));
							$eventDate = strtotime(date('Y-m-d',@$eventData->date));
							$eventStartTime = strtotime(date('H:i',$eventData->start_time));
							$eventEndTime = strtotime(date('H:i',$eventData->end_time));

							if(!empty($bookedEventData)  && ($request_type=='cart' || $request_type=='booking') && $eventDate==$bookedEventDate
                            && (

							($bookedEventStartTime < $eventStartTime
                            && $bookedEventEndTime  > $eventStartTime)

							||($bookedEventStartTime  < $eventEndTime
                            && $bookedEventEndTime  > $eventEndTime)

							||($eventStartTime  < $bookedEventStartTime
                            && $eventEndTime > $bookedEventStartTime)

							||($eventStartTime  < $bookedEventEndTime
                            && $eventEndTime  > $bookedEventEndTime)

							|| ($bookedEventStartTime == $eventStartTime)
                            || ($bookedEventStartTime == $eventEndTime)
                            || ($bookedEventEndTime == $eventEndTime)
                            || ($bookedEventEndTime == $eventStartTime)
                            )
                            ){
								$this->apiArray['message'] = "An event already booked with same time period.";
								$this->apiArray['errorCode'] = 4;
								$this->apiArray['error'] = true;
								return response()->json($this->apiArray, 200);
							}
						}
					}

					$cartEventList = MemberEventCart::where('application_id',@$userinfo->getMemberInfo->application_number)->get('event_id');
					if(!empty($cartEventList)){
						foreach($cartEventList as $CartEvent){
							$cartEventData = EventSetting::where('id',@$CartEvent->event_id)->where('status','Enabled')->first();
							/*
                            echo "cart_event_date :".date('Y-m-d',@$cartEventData->date);
							echo "</br>cart_event_start_time:".date('H:i',@$cartEventData->start_time);
							echo "</br>cart_event_end_time:".date('H:i',@$cartEventData->end_time);
							echo "</br>date:".date("Y-m-d",$eventData->date);
							echo "</br>start_time:".date("H:i",$eventData->start_time);
							echo "</br>end_time".date("H:i",$eventData->end_time);
							echo "</br>request_type:".$request_type;
							echo "<br>cart start time :".strtotime(date('H:i',$cartEventData->start_time));
							echo "<br>event start time :".strtotime(date('H:i',$eventData->start_time));
							*/
							$cartEventDate = strtotime(date('Y-m-d',@$cartEventData->date));
							$cartEventStartTime = strtotime(date('H:i',$cartEventData->start_time));
							$cartEventEndTime = strtotime(date('H:i',$cartEventData->end_time));
							$eventDate = strtotime(date('Y-m-d',@$eventData->date));
							$eventStartTime = strtotime(date('H:i',$eventData->start_time));
							$eventEndTime = strtotime(date('H:i',$eventData->end_time));

							if(!empty($cartEventData) && $request_type=='cart' && $eventDate==$cartEventDate
                            && (

							($cartEventStartTime < $eventStartTime
                            && $cartEventEndTime  > $eventStartTime)

							||($cartEventStartTime  < $eventEndTime
                            && $cartEventEndTime  > $eventEndTime)

							||($eventStartTime  < $cartEventStartTime
                            && $eventEndTime > $cartEventStartTime)

							||($eventStartTime  < $cartEventEndTime
                            && $eventEndTime  > $cartEventEndTime)

							|| ($cartEventStartTime == $eventStartTime)
                            || ($cartEventStartTime == $eventEndTime)
                            || ($cartEventEndTime == $eventEndTime)
                            || ($cartEventEndTime == $eventStartTime)
                            )
                            ){
								$this->apiArray['message'] = "An event already added in cart with same time period.";
								$this->apiArray['errorCode'] = 4;
								$this->apiArray['error'] = true;
								return response()->json($this->apiArray, 200);
							}

						}
					}
					// echo "</br>final"; die;

					if(isset($eventData->getEventProgrammes) && count($eventData->getEventProgrammes)){
						foreach ($eventData->getEventProgrammes as $keypro => $valuepro){
							if(isset($valuepro->getProgrammeDetailApi) && !empty($valuepro->getProgrammeDetailApi)){
								if($valuepro->getProgrammeDetailApi->start_date<=$eventData->date && $valuepro->getProgrammeDetailApi->end_date>=$eventData->date){
									$programmeDate = 'Yes';
								}
								if(isset($userinfo) && !empty($userinfo)){
									 if($valuepro->getProgrammeDetailApi->checkMemberProgramme($userinfo->getMemberInfo->id)){
										$userProgramme = 'Yes';
									}
								}
							}
						}
					}

					if(!empty($eventData)){
                        $programmeDate = '';
                        $userProgramme = '';
						if(isset($eventData->getEventProgrammes) && count($eventData->getEventProgrammes)){
                            foreach ($eventData->getEventProgrammes as $keypro => $valuepro){
                                if(isset($valuepro->getProgrammeDetailApi) && !empty($valuepro->getProgrammeDetailApi)){
                                    if($valuepro->getProgrammeDetailApi->start_date<=$eventData->date && $valuepro->getProgrammeDetailApi->end_date>=$eventData->date){
                                        $programmeDate = 'Yes';
                                    }
                                    if(isset($userinfo) && !empty($userinfo)){
                                         if($valuepro->getProgrammeDetailApi->checkMemberProgramme($userinfo->getMemberInfo->id)){
                                            $userProgramme = 'Yes';
                                        }
                                    }
                                }
                            }
                        }else{
                            //$errorMessage.= "No programme associated with the event.";
                            $this->apiArray['message'] = "No programme associated with the event.";
                            $this->apiArray['errorCode'] = 4;
                            $this->apiArray['error'] = true;
                            return response()->json($this->apiArray, 200);
                        }
                        if(empty($programmeDate)){
                            //$errorMessage.= "Event date is not matched with programme date.";
                            $this->apiArray['message'] = "Event date is not matched with programme date.";
                            $this->apiArray['errorCode'] = 4;
                            $this->apiArray['error'] = true;
                            return response()->json($this->apiArray, 200);
                        }
                        if(isset($userinfo) && !empty($userinfo)){
                            if(empty($userProgramme)){
                                //$errorMessage.= "Event not in your programme.";
                                $this->apiArray['message'] = "Event not in your programme";
                                $this->apiArray['errorCode'] = 4;
                                $this->apiArray['error'] = true;
                                return response()->json($this->apiArray, 200);
                            }
                            if(!empty($eventData->checkMemberEvent($userinfo->getMemberInfo->application_number))){
                                //$errorMessage.= "Event is already booked by you.";
                                $this->apiArray['message'] = "Event is already booked by you.";
                                $this->apiArray['errorCode'] = 4;
                                $this->apiArray['error'] = true;
                                return response()->json($this->apiArray, 200);
                            }
                        }
                        if($eventData->quota_balance <= 0){
                            //$errorMessage.= "We regret to inform you that the current quota for ".$eventData->event_name." has been fully booked, and unfortunately, we are unable to process your reservation request.";
                            $this->apiArray['message'] = "We regret to inform you that the current quota for ".$eventData->event_name." has been fully booked, and unfortunately, we are unable to process your reservation request.";
                            $this->apiArray['errorCode'] = 4;
                            $this->apiArray['error'] = true;
                            return response()->json($this->apiArray, 200);
                        }
                        if($eventData->date < time()){
                            //$errorMessage.= "You can not book same date Event.";
                            $this->apiArray['message'] = "You can not book same date Event";
                            $this->apiArray['errorCode'] = 4;
                            $this->apiArray['error'] = true;
                            return response()->json($this->apiArray, 200);
                        }
                        if($eventData->application_deadline < time()){
                            //$errorMessage.= "Event deadline is over.";
                            $this->apiArray['message'] = "Event deadline is over";
                            $this->apiArray['errorCode'] = 4;
                            $this->apiArray['error'] = true;
                            return response()->json($this->apiArray, 200);
                        }
                    }else{
                        //$errorMessage.= "Event deadline is over.";
                        $this->apiArray['message'] = "Event is non available.";
                        $this->apiArray['errorCode'] = 4;
                        $this->apiArray['error'] = true;
                        return response()->json($this->apiArray, 200);
                    }
                    /*if(!empty($errorMessage)){
                        $this->apiArray['message'] = $errorMessage;
                        $this->apiArray['errorCode'] = 4;
                        $this->apiArray['error'] = true;
                        return response()->json($this->apiArray, 200);
                    }*/
                    if(isset($userinfo) && !empty($userinfo)){
                        if(MemberEventCart::where('event_id',$eventData->id)->where('application_id',$userinfo->getMemberInfo->application_number)->doesntExist()){
                            MemberEventCart::create([
                                'event_id'  => $eventData->id,
                                'application_id' => $userinfo->getMemberInfo->application_number,
                                'no_of_seats' => $inputs1['no_of_tickets'][$key],
                            ]);
                        }else{
                            MemberEventCart::where('event_id',$eventData->id)->where('application_id',$userinfo->getMemberInfo->application_number)->update(['no_of_seats'=>$inputs1['no_of_tickets'][$key]]);
                        }
                    }
                }
                $this->apiArray['message'] = "Event added to your cart.";
                $this->apiArray['errorCode'] = 0;
                $this->apiArray['error'] = false;
                return response()->json($this->apiArray, 200);
            }

            $this->apiArray['message'] = "No event Found.";
            $this->apiArray['errorCode'] = 0;
            $this->apiArray['error'] = true;
            return response()->json($this->apiArray, 200);
        } catch (\Exception $e) {
            $this->apiArray['message'] = 'Something is wrong, please try after some time'.$e->getMessage();
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 200);
        }
    }
    /* End */


    /* Member Data */
    /* Get get event list API by Ashish */
     /* Private Event List Added by Akash */
    public function getMyEventList(Request $request){
        try {
            $inputs = $request->all();
            $this->apiArray['state'] = 'getMyEventList';
            /*Check header */
            $headers = getallheaders();
            if(!$this->verifyTokens($headers['Authkey'])){
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 401);
            }

            /*End*/
            $userinfo = $request->user('sanctum');
            $DISK_NAME = Config::get('DISK_NAME');
            $application = $userinfo->getMemberInfo->application_number;


            // Public Event Data
            $eventData = EventSetting::select('event_settings.*','event_bookings.id as eventboookingid')->leftJoin('event_bookings', function ($join) use($application) {
                        $join->on('event_settings.id', '=', 'event_bookings.event_id');
            })->where('event_bookings.application_id',$application)->where('status','!=','Disabled');
            if(isset($inputs['category']) && !empty($inputs['category'])){
                $eventData = $eventData->where("event_category_id",$inputs['category']);
            }
            if(isset($inputs['search_keyword']) && !empty($inputs['search_keyword'])){
                $search = $inputs['search_keyword'];
                $eventData = $eventData->where(function($query) use($search) {
                    $query->where('event_name', 'like', '%' . $search . '%')
                          ->orWhere('short_description', 'like', '%' . $search . '%');
                });
            }
            if(isset($inputs['language']) && !empty($inputs['language'])){
                $eventData = $eventData->where("language_id",$inputs['language']);
            }
            if(isset($inputs['price_range']) && !empty($inputs['price_range'])){
                if($inputs['price_range']=='Free'){
                    $eventData = $eventData->whereNull("event_settings.unit_price");
                }else{
                    $price = explode("-", $inputs['price_range']);
                    if(count($price)>1){
                        $eventData = $eventData->whereBetween("event_settings.unit_price",[$price[0],$price[1]])->whereNotNull("event_settings.unit_price");
                    }
                }
            }
            if(isset($inputs['period']) && !empty($inputs['period'])){
                $period = explode("/", $inputs['period']);
                if(count($period)>1){
                    $eventData = $eventData->whereBetween("date",[strtotime($period[0]),strtotime($period[1])]);
                }
            }
            if(isset($inputs['type']) && !empty($inputs['type'])){
                if($inputs['type']=='c'){
                    //$eventData = $eventData->where('date','>=',time());
                    $eventData = $eventData->whereIn('event_bookings.booking_status',array('Paid','Updated','Pending'));
                }else{
                    //$eventData = $eventData->where('date','<',time());
                    $eventData = $eventData->whereIn('event_bookings.booking_status',array('Cancelled','Completed'));
                }
            }
            $eventData = $eventData->orderBy('eventboookingid','DESC')->get();


            // Private Event Data by Akash
            $privateEventData = PrivateEventSetting::with(['getBookings' => function($query) use($application)

                                                    {
                                                        $query->where('application_id', $application);

                                                    }])
                                                    ->whereHas('getBookings', function($query) use($application, $inputs)
                                                    {
                                                        $query->where('application_id', $application);
                                                        if(isset($inputs['type']) && !empty($inputs['type'])){
                                                            if($inputs['type']=='c'){
                                                                //$privateEventData = $privateEventData->where('date','>=',time());
                                                                $query->whereNotIn('event_status',array('Cancelled','Completed'));
                                                            }else{
                                                                //$privateEventData = $privateEventData->where('date','<',time());
                                                                $query->whereIn('event_status',array('Cancelled','Completed'));
                                                            }
                                                        }
                                                    })
                                                    ->where('status','!=','Disabled');
            if(isset($inputs['category']) && !empty($inputs['category'])){
                $privateEventData = $privateEventData->where("event_category_id",$inputs['category']);
            }
            if(isset($inputs['search_keyword']) && !empty($inputs['search_keyword'])){
                $search = $inputs['search_keyword'];
                $privateEventData = $privateEventData->where(function($query) use($search) {
                    $query->where('event_name', 'like', '%' . $search . '%')
                          ->orWhere('short_description', 'like', '%' . $search . '%');
                });
            }
            if(isset($inputs['language']) && !empty($inputs['language'])){
                $privateEventData = $privateEventData->where("language_id",$inputs['language']);
            }
            if(isset($inputs['price_range']) && !empty($inputs['price_range'])){
                if($inputs['price_range']=='Free'){
                    $privateEventData = $privateEventData->whereNull("unit_price");
                }else{
                    $price = explode("-", $inputs['price_range']);
                    if(count($price)>1){
                        $privateEventData = $privateEventData->whereBetween("unit_price",[$price[0],$price[1]])->whereNotNull("unit_price");
                    }
                }
            }
            if(isset($inputs['period']) && !empty($inputs['period'])){
                $period = explode("/", $inputs['period']);
                if(count($period)>1){
                    $privateEventData = $privateEventData->whereBetween("date",[strtotime($period[0]),strtotime($period[1])]);
                }
            }

            $privateEventData = $privateEventData->orderBy('created_at','DESC')->get();



            // Merge Public Event and Private Event
            $eventData = $eventData->merge($privateEventData)->sortByDesc('created_at');
            if(isset($inputs['sort_by']) && !empty($inputs['sort_by'])){
                if($inputs['sort_by']=='1'){
                    $eventData = $eventData->sortBy('unit_price');
                }elseif($inputs['sort_by']=='2'){
                    $eventData = $eventData->sortByDesc('unit_price');
                }elseif($inputs['sort_by']=='3'){
                    $eventData = $eventData->sortBy('event_name');
                }elseif($inputs['sort_by']=='4'){
                    $eventData = $eventData->sortByDesc('event_name');
                }
            }

            // Pagination of merged data
            $eventData = $this->paginate($eventData, 6);
                if(count($eventData)){
                $key = 0;
                foreach ($eventData as $value) {

                    if ( $value->getTable() == 'event_settings') {

                        $eventBooking = EventBooking::find($value['eventboookingid']);
                        if(!empty($eventBooking)){
                            $checkPayment = EventPayment::where('payment_id',$eventBooking->payment_id)->where('service_type','Event Booking')->first();
                            if(!empty($checkPayment)){
                                $data[$key]['event_id'] = $eventBooking->id;
                                $data[$key]['event_name'] = $value->event_name;
                                $data[$key]['category'] = $value->getCategoryDetails->name ?? '';;
                                $data[$key]['description'] = $value->short_description;
                                $data[$key]['date'] = date("Y-m-d",$value->date);
                                $data[$key]['time'] = date("H:i",@$value->start_time)."-".date("H:i",@$value->end_time);
                                $data[$key]['location'] = $value->location;
                                $data[$key]['price'] = $value->unit_price;
                                $data[$key]['status'] = $value->qouta_status;
								$data[$key]['booking_status'] = $eventBooking->booking_status ?? 'Pending';
								$data[$key]['event_type'] = 'public_event';

                                $data[$key]['image'] = (isset($value->main_image) && $value->main_image != '' && Storage::disk($DISK_NAME)->exists($value->main_image))?asset(Storage::url($value->main_image)):asset('img/default-image.jpg');
                            }
                        }
                    }else{
                                $eventBooking = $value->getBookings->first();
                                $data[$key]['event_id'] = $eventBooking->id;
                                $data[$key]['event_name'] = $value->event_name;
                                $data[$key]['category'] = $value->getCategoryDetails->name ?? '';;
                                $data[$key]['description'] = $value->short_description;
                                $data[$key]['date'] = date("Y-m-d",$value->date);
                                $data[$key]['time'] = date("H:i",@$value->start_time)."-".date("H:i",@$value->end_time);
                                $data[$key]['location'] = $value->location;
                                $data[$key]['price'] = $value->unit_price;
                                $data[$key]['status'] = $eventBooking->booking_status;
                                // $data[$key]['booking_status'] = $eventBooking->event_status ?? 'Pending';
								$data[$key]['booking_status'] = $eventBooking->booking_status ?? 'Pending';
								$data[$key]['event_type'] = 'private_event';

                                $data[$key]['image'] = (isset($value->main_image) && $value->main_image != '' && Storage::disk($DISK_NAME)->exists($value->main_image))?asset(Storage::url($value->main_image)):asset('img/default-image.jpg');
                    }
                    $key++;
                }

                $this->apiArray['data'] = $data;
                $this->apiArray['totalPage']    =   $eventData->lastPage();
                $this->apiArray['message'] = 'Success';
                $this->apiArray['errorCode'] = 0;
                $this->apiArray['error'] = false;
                return response()->json($this->apiArray, 200);
            }
            $this->apiArray['message'] = 'No event found.';
            $this->apiArray['errorCode'] = 3;
            $this->apiArray['error'] = false;
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
    /* End */

    // Paginate merged events data Ronak
    public function paginate($items, $perPage = 9, $page = null, $options = [])
    {
        $options['path'] =  Paginator::resolveCurrentPath();
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    /* Get get event detail API by Ronak */
    public function getMyEventDetails(Request $request){
        try {
            $inputs = $request->all();
            $this->apiArray['state'] = 'getMyEventDetails';
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
                'event_id' => 'required',
                'event_type' => 'required'
            ]);
            if($validator->fails()){
                $this->apiArray['message'] = $validator->messages()->first();
                $this->apiArray['errorCode'] = 2;
                $this->apiArray['error'] = true;
                return response()->json($this->apiArray, 200);
            }
            /*End*/
            $userinfo = $request->user('sanctum');
            $DISK_NAME = Config::get('DISK_NAME');

            if ( isset($inputs['event_type']) && $inputs['event_type'] == 'public_event') {

                $application = $userinfo->getMemberInfo->application_number;
                $eventData = EventSetting::select('event_settings.*','event_bookings.id as eventboookingid','event_bookings.unit_price')->leftJoin('event_bookings', function ($join) {
                            $join->on('event_settings.id', '=', 'event_bookings.event_id');
                })->where('event_bookings.application_id',$application)->where('event_settings.status','!=','Disabled')->where('event_bookings.id',$inputs['event_id'])->first();
                if(!empty($eventData)){
                    $eventBooking = EventBooking::find($eventData['eventboookingid']);
                    if(!empty($eventBooking)){
                        $checkPayment = EventPayment::where('payment_id',$eventBooking->payment_id)->where('service_type','Event Booking')->first();
                        if(!empty($checkPayment)){
                            $data['event_id'] = $eventData->id;
                            $data['event_name'] = $eventData->event_name;
                            $data['application_no'] = $userinfo->getMemberInfo->application_number ?? '';
                            $data['member_name'] = $userinfo->getMemberInfo->given_name;
                            $data['category'] = $eventData->getCategoryDetails->name ?? '';
                            $data['description'] = $eventData->description;
                            $data['short_description'] = $eventData->short_description;
                            $data['additional_info'] = $eventData->additional_info;
                            $data['date'] = date("Y-m-d",$eventData->date);
                            $data['time'] = date("H:i",@$eventData->start_time)."-".date("H:i",@$eventData->end_time);
                            $data['location'] = $eventData->location;
                            $data['price'] = $eventData->unit_price;
                            $data['assembly_time'] =  date("H:i",@$eventData->assembly_start_time)."-".date("H:i",@$eventData->assembly_end_time);
                            $data['assembly_location'] = $eventData->assembly_location;
                            $data['terms_condition'] = $eventData->terms_condition;
                            $data['terms_link'] = $eventData->terms_link;
                            $data['pre_arrival'] = $eventData->pre_arrival;
                            $data['pre_link'] = $eventData->pre_link;
                            $data['notes'] = $eventData->notes;
                            $data['status'] = $eventData->qouta_status;
                            $data['order_no'] = $eventBooking->paymentBooking->payment_id ?? '';
                            $data['transaction_no'] = $eventBooking->paymentBooking->transaction_id ?? '';
                            $amount = 0;
                            if (!empty($eventData->unit_price)) {
                               $amount += $eventData->unit_price * $eventBooking->no_of_seats;
                            }
                            $data['amount'] = $amount;
                            $data['payment_status'] = $eventBooking->paymentBooking->payment_status ?? 'Pending';
                            if(!empty($eventBooking->booking_status) && $eventBooking->booking_status=='Completed'){
								$data['booking_status'] = 'Completed';
							} else {
								$data['booking_status'] = $eventBooking->booking_status ?? 'Pending';
							}
                            $data['order_date'] = date("Y-m-d",strtotime($eventBooking->paymentBooking->created_at)) ?? '';
                            $data['no_of_tickets'] = $eventBooking->no_of_seats ?? '';
                            $data['image'] = (isset($eventData->main_image) && $eventData->main_image != '' && Storage::disk($DISK_NAME)->exists($eventData->main_image))?asset(Storage::url($eventData->main_image)):asset('img/default-image.jpg');

                            if(isset($eventData->getEventImages) && count($eventData->getEventImages)){
                                foreach ($eventData->getEventImages as $key => $value) {
                                    $data['slider_image'][$key]['image_url'] = (isset($value->main_image) && $value->main_image != '' && Storage::disk($DISK_NAME)->exists($value->main_image))?asset(Storage::url($value->main_image)):asset('img/default-image.jpg');
                                }
                            }
                        }
                    }

                    $this->apiArray['data'] = $data;
                    $this->apiArray['message'] = 'Success';
                    $this->apiArray['errorCode'] = 0;
                    $this->apiArray['error'] = false;
                    return response()->json($this->apiArray, 200);
                }

            }else if( isset($inputs['event_type']) && $inputs['event_type'] == 'private_event'){

                // Get private Event detail By Akash
                $application = $userinfo->getMemberInfo->application_number;
                $eventData = PrivateEventSetting::select('private_event_settings.*','private_event_orders.id as eventboookingid')->leftJoin('private_event_orders', function ($join) {
                            $join->on('private_event_settings.id', '=', 'private_event_orders.event_id');
                })->where('private_event_orders.application_id',$application)->where('private_event_settings.status','!=','Disabled')->where('private_event_orders.id',$inputs['event_id'])->first();
                if(!empty($eventData)){

                    $eventBooking = PrivateEventOrder::find($eventData['eventboookingid']);

                    if(!empty($eventBooking)){

                            $data['event_id'] = $eventData->id;
                            $data['event_name'] = $eventData->event_name;
                            $data['application_no'] = $userinfo->getMemberInfo->application_number ?? '';
                            $data['member_name'] = $userinfo->getMemberInfo->given_name;
                            $data['category'] = $eventData->getCategoryDetails->name ?? '';
                            $data['description'] = $eventData->description;
                            $data['short_description'] = $eventData->short_description;
                            $data['additional_info'] = $eventData->additional_info;
                            $data['date'] = date("Y-m-d",$eventData->date);
                            $data['time'] = date("H:i",@$eventData->start_time)."-".date("H:i",@$eventData->end_time);
                            $data['location'] = $eventData->location;
                            $data['price'] = $eventData->unit_price;

                            $data['assembly_time'] = date("H:i",@$eventData->assembly_start_time)."-".date("H:i",@$eventData->assembly_end_time);

                            $data['assembly_location'] = $eventData->assembly_location;
                            $data['terms_condition'] = $eventData->terms_condition;
                            $data['terms_link'] = $eventData->terms_link;
                            $data['pre_arrival'] = $eventData->pre_arrival;
                            $data['pre_link'] = $eventData->pre_link;
                            $data['notes'] = $eventData->notes;
                            $data['status'] = $eventBooking->booking_status;
                            $data['order_no'] = $eventBooking->booking_id ?? '';
                            $data['booking_status'] = $eventBooking->event_status ?? 'Pending';
                            $data['order_date'] = date("Y-m-d",strtotime($eventBooking->created_at)) ?? '';
                            $amount = 0;
                            if (!empty($eventData->unit_price)) {
                               $amount = $eventData->unit_price * $eventBooking->no_of_seats;
                            }
                            $data['amount'] = $amount;
                            $data['no_of_tickets'] = $eventBooking->no_of_seats ?? '';
                            $data['image'] = (isset($eventData->main_image) && $eventData->main_image != '' && Storage::disk($DISK_NAME)->exists($eventData->main_image))?asset(Storage::url($eventData->main_image)):asset('img/default-image.jpg');

                            if(isset($eventData->getEventImages) && count($eventData->getEventImages)){
                                foreach ($eventData->getEventImages as $key => $value) {
                                    $data['slider_image'][$key]['image_url'] = (isset($value->main_image) && $value->main_image != '' && Storage::disk($DISK_NAME)->exists($value->main_image))?asset(Storage::url($value->main_image)):asset('img/default-image.jpg');
                                }
                            }

                    }

                    $this->apiArray['data'] = $data;
                    $this->apiArray['message'] = 'Success';
                    $this->apiArray['errorCode'] = 0;
                    $this->apiArray['error'] = false;
                    return response()->json($this->apiArray, 200);
                }
            }

            $this->apiArray['message'] = 'No event found.';
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
    /* End */


    /* Get get event detail API by Ronak */
    public function getMyEventCart(Request $request){
        try {
            $this->apiArray['state'] = 'getMyEventCart';
            /*Check header */
            $headers = getallheaders();
            if (!$this->verifyTokens($headers['Authkey'])){
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 401);
            }
            /*End*/
            $userinfo = $request->user('sanctum');
            $application = $userinfo->getMemberInfo->application_number;
            $key = 0;
            $data = array();
            $cartitems = MemberEventCart::where('application_id',$userinfo->getMemberInfo->application_number)->get();
            //dd($cartitems);
            if(count($cartitems)){
                foreach ($cartitems as $valueEvent) {
                    $eventData = EventSetting::where('status','Enabled')->where('id',$valueEvent->event_id)->first();
                    if(!empty($eventData)){
                        $data[$key]['event_id'] = $eventData->id;
                        $data[$key]['event_name'] = $eventData->event_name;
                        $data[$key]['date'] = date("Y-m-d",$eventData->date);
                        $data[$key]['time'] = date("H:i",@$eventData->start_time).'-'.date("H:i",@$eventData->end_time);
						$data[$key]['amount'] = $eventData->unit_price;
                        $data[$key]['location'] = $eventData->location;
                        $data[$key]['no_of_tickets'] = $valueEvent->no_of_seats;
                        $booking_limit = $eventData->booking_limit;
                        $qouta_balance = $eventData->quota_balance;
                        if($qouta_balance < $booking_limit){
                            $data[$key]['max_ticket_limit'] = $qouta_balance;
                        }else{
                            $data[$key]['max_ticket_limit'] = $booking_limit;
                        }
                        $key++;
                    }
                }
                $this->apiArray['data'] = $data;
                $this->apiArray['message'] = 'Success';
                $this->apiArray['errorCode'] = 0;
                $this->apiArray['error'] = false;
                return response()->json($this->apiArray, 200);
            }
            $this->apiArray['data'] = NULL;
            $this->apiArray['message'] = 'Success';
            $this->apiArray['errorCode'] = 0;
            $this->apiArray['error'] = false;
            return response()->json($this->apiArray, 200);
        } catch (\Exception $e) {
            $this->apiArray['message'] = 'Something is wrong, please try after some time'.$e->getMessage();
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 200);
        }
    }
    /* End */


    /* Get get event detail API by Ronak */
    public function removeMyEventCart(Request $request){
        try {
            $inputs = $request->all();
            $this->apiArray['state'] = 'removeMyEventCart';
            /*Check header */
            $headers = getallheaders();
            if (!$this->verifyTokens($headers['Authkey'])){
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 401);
            }
            $validator = Validator::make($inputs, [
                'event_id' => ['required']
            ]);
            if($validator->fails()){
                $this->apiArray['message'] = $validator->messages()->first();
                $this->apiArray['errorCode'] = 2;
                $this->apiArray['error'] = true;
                return response()->json($this->apiArray, 200);
            }
            /*End*/
            $userinfo = $request->user('sanctum');
            $application = $userinfo->getMemberInfo->application_number;
            MemberEventCart::where('application_id',$userinfo->getMemberInfo->application_number)->where('event_id',$inputs['event_id'])->delete();

            $this->apiArray['data'] = NULL;
            $this->apiArray['message'] = 'Success';
            $this->apiArray['errorCode'] = 0;
            $this->apiArray['error'] = false;
            return response()->json($this->apiArray, 200);
        } catch (\Exception $e) {
            $this->apiArray['message'] = 'Something is wrong, please try after some time'.$e->getMessage();
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 200);
        }
    }
    /* End */
}
