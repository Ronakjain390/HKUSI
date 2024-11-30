<?php
namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Verified;
use App\Traits\VerifyTokenStatus;
use App\Jobs\SendEmailJob;
use Exception, Validator, DB, Storage, Config;

class HomeController extends Controller
{
    use VerifyTokenStatus;

    protected $loginField;
    protected $loginValue;

    public function __construct()
    {
        $this->apiArray = array();
        $this->apiArray['error'] = true;
        $this->apiArray['message'] = '';
        $this->apiArray['errorCode'] = 4;
        // $this->DISK_NAME = Config::get('DISK_NAME');
    }

    public function hkuApp(Request $request)
    {
        try{
            $this->apiArray['state'] = 'hkuapp';
             $headers = getallheaders();
            /*Check header */
            if (!$this->verifyTokens($headers['Authkey'],'')){
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 200);
            }
            /*End*/
            
            $data = [
                'heading' => 'SUMMER AT HKU',
                'title' => 'Download HKU APP',
                'message' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur',
                'playstore_link' => 'https://play.google.com/',
                'apple_store_link' => 'https://www.apple.com/',
                'image' => asset('img/app.png'),
            ];
            $this->apiArray['message'] = 'hku app';
            $this->apiArray['data'] = $data;
            $this->apiArray['errorCode'] = 0;
            $this->apiArray['error'] = false;
            return response()->json($this->apiArray);
        }catch (\Exception $e){
            $this->apiArray['message'] = 'Something is wrong, please try after some time';
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 200);
        }
    }

}