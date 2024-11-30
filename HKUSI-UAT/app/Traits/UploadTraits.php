<?php
// namespace App\Http\Controllers;
namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use App\Models\StaticContent;
//use Image;

trait UploadTraits
{

	public function uploadSingleImage($requestfiles, $imageType = 'general',$fileContent='',$application_number=null) {
		$outputfile = '';
		$diskName = Config::get('DISK_NAME');
		$profileImgPath = Config::get('PROFILE_PIC_PATH');
		$productImgPath = Config::get('MEMBER_PIC_PATH');
		$eventImgPath = Config::get('EVENT_PIC_PATH');
		$categoryImgPath = Config::get('CATEGORY_PIC_PATH');
		$bannerImgPath = Config::get('BANNER_PIC_PATH');
		$generalImgPath = Config::get('GENERAL_IMG_PATH');
		$imgPath = DIRECTORY_SEPARATOR;
		
		if($imageType == "profile") {
			$imgPath = $imgPath . $profileImgPath . DIRECTORY_SEPARATOR;
		} else if ($imageType == "members") {
			$imgPath = $imgPath . $productImgPath . DIRECTORY_SEPARATOR;
		}else if ($imageType == "event") {
			$imgPath = $imgPath . $eventImgPath . DIRECTORY_SEPARATOR;
		}else if ($imageType == "hotel") {
			$imgPath = $imgPath . $eventImgPath . DIRECTORY_SEPARATOR;
		} else {
			// default to general image folder
			$imgPath = $imgPath . $generalImgPath . DIRECTORY_SEPARATOR;
		}
		if (!empty($requestfiles)) {
			//get file extension
			$extension = $requestfiles->getClientOriginalExtension();
			$extension = strtolower($extension);
			if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'JPG' || $extension == 'JPEG' || $extension =='svg' || $extension == 'png' || $extension == 'gif' || $extension == 'doc' || $extension == 'docx' || $extension == 'pdf'|| $extension == 'xlsx') {
				//filename to store
				if($imageType == "profile") {
					$filenametostore = $application_number . '.' . $extension;  
				} else {
					$filenametostore = $this->generateRandomString(25) . '_' . time() . '.' . $extension;
				}
				$imagePathWithFilename = $imgPath . $filenametostore;
				//Upload File
				if(!empty($fileContent)){
					Storage::disk($diskName)->put($imagePathWithFilename,base64_decode($fileContent));
					// if ($imageType == "event") {
					// 	$destinationPath = 'storage/event';
					// 	$t3_image = (new ImageManager('gd'))->make($fileContent)->scale(600);
					// 	$thumb_image = "thumb_".$filenametostore;
					// 	$t3_image->toJpeg()->save($destinationPath . '/' . $thumb_image);
					// }
				}else{
					Storage::disk($diskName)->put($imagePathWithFilename, file_get_contents($requestfiles));
					// if ($imageType == "event") {
					// 	$destinationPath = 'storage/event';
					// 	$t3_image = (new ImageManager('gd'))->make($requestfiles->path())->scale(600);
					// 	$thumb_image = "thumb_".$filenametostore;
					// 	$t3_image->toJpeg()->save($destinationPath . '/' . $thumb_image);
					// }
				}
				// $newFileName = $imageType.'/'.$filenametostore;
				$newFileName = $imagePathWithFilename;
				return $newFileName;
			} else {
				return  '';
			}
		}

		return $outputfile;
	}


	public function uploadSingleFile($requestfiles,$fileContent='') {
        $outputfile = '';
		$diskName = Config::get('global.DISK_NAME');
		$generalDocPath = Config::get('global.GENERAL_DOC_PATH');
		
		$filePath = DIRECTORY_SEPARATOR . $generalDocPath . DIRECTORY_SEPARATOR;

		if (!empty($requestfiles)) {
			//get file extension
			$extension = $requestfiles->getClientOriginalExtension();

			if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'gif' || $extension == 'doc' || $extension == 'docx' || $extension == 'pdf') {
				//filename to store
				$filenametostore = $this->generateRandomString(25) . '_' . time() . '.' . $extension;
				$imagePathWithFilename = $filePath . $filenametostore;
				//Upload File
				if(!empty($fileContent))
				{
					Storage::disk($diskName)->put($imagePathWithFilename,base64_decode($fileContent));
				}else{
					Storage::disk($diskName)->put($imagePathWithFilename, file_get_contents($requestfiles));
				}
				$msg = 'Document successfully uploaded';
				return $imagePathWithFilename;
			} else {
				return  '';
			}
		}
        return $outputfile;
    }
	 

	function removeFile($path) {
		$diskName = Config::get('global.DISK_NAME');
		if (!empty($path)) {
			Storage::disk($diskName)->delete($path);
		}
	}

	function getFileURL($filename) {
		$diskName = Config::get('global.DISK_NAME');
		//echo $diskName;
		if(Storage::disk($diskName)->exists($filename)) {
			return Storage::url($filename);
		} else {
			return $filename;
		}
	}

	function isFileExists ($filename) {
		$diskName = Config::get('global.DISK_NAME');
		$retData = Storage::disk($diskName)->exists($filename);
		return $retData;
	}

	function getFileSize($filename) {
		$diskName = Config::get('global.DISK_NAME');
		$retData = Storage::disk($diskName)->size($filename);
		return $retData;
	}

	function getFileContent($filecontent=''){
		$imageContent = '';
		if(!empty($filecontent)){
			$imageContentArray = explode('base64',$filecontent);
            if(isset($imageContentArray[1])){
             	$imageContent = $imageContentArray[1];  
             	$imageContent = str_replace(' ', '+', $imageContent); 
         	}
		}
		
        return $imageContent;
	}

	/* Get slug for title */
    public function createSlug($title, $id = 0,$dbtype)
    {
        // Normalize the title
        $slug = str_slug($title);

        // Get any that could possibly be related.
        // This cuts the queries down by doing it once.
        $allSlugs = $this->getRelatedSlugs($slug, $id,$dbtype);

        // If we haven't used it before then we are all good.
        if (! $allSlugs->contains('slug', $slug)){
            return $slug;
        }

        // Just append numbers like a savage until we find not used.
        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug.'-'.$i;
            if (! $allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }

        throw new \Exception('Can not create a unique slug');
    }

    protected function getRelatedSlugs($slug, $id = 0,$dbtype)
    {
        return StaticContent::select('slug')->where('slug', 'like', $slug.'%')->where('id', '<>', $id)->get();
    }

    protected function generateRandomString( $len=64,$type=''){
		$secret = "";
		if($type=='AlphaBet'){
			$charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		}else{
			$charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		}
		$charset = str_shuffle( $charset );
		for ( $s = 1; $s <= $len; $s++ ){
			if($type=='AlphaBet'){
				$secret .= substr( $charset, random_int( 0, 25 ), 1);
			}else{
				$secret .= substr( $charset, random_int( 0, 86 ), 1);
			}            
		}
		return $secret; 
  	}  

}
