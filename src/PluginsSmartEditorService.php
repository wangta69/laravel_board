<?php
namespace Pondol\Bbs;

use Illuminate\Http\Request;

use Validator;
use Storage;
use Response;

class PluginsSmartEditorService {
	
	

    
	public function getSmartEditorSkin() {
	   // return view('bbs.plugins.smart_editor.test');
        return view('bbs.plugins.smart_editor.skin');
		//return view('bbs::plugins.smart_editor.skin');
	}
	
	public function getInputArea($mode = '') {
		if ($mode == 'ie8') {
			return view('bbs.plugins.smart_editor.inputarea_ie8');
		} else {
			return view('bbs.plugins.smart_editor.inputarea');
		}
	}
	
	public function getPhotoUploader() {
		return view('bbs.plugins.smart_editor.photo_uploader');
	}
	
	public function postFileUpload(Request $request) {
	    
		$url = $request->get('callback').'?callback_func='.$request->get('callback_func');
		

		if ($request->hasFile('Filedata')) {
		    
			$validator = Validator::make($request->all(), [
				'Filedata' => 'image',
			]);
			
			// if it is not image file type
			if ($validator->fails()) {
				$url .= '&errstr=not_image_file';
                return redirect($url);
			}
			
			$file = $request->file('Filedata');
			//$filename = time();
			
			//while (Storage::exists('editor/'.$filename)) {
			//	$filename ++;
			//}
			
            $filepath = 'public/bbs/tmp/editor/'.session()->getId();
            //upload to storage
            $filename = $file->getClientOriginalName();
            $path=Storage::put($filepath,$file); // //Storage::disk('local')->put($name,$file,'public');
                
                
           // $uploadPath = session()->getId();
			//$file->move($uploadPath, $filename);
			
			$url .= '&bNewLine=true';
			$url .= '&sFilename='.basename($path);;
			$url .= '&sFileURL=/storage/bbs/tmp/editor/'.session()->getId().'/'.basename($path);
		} else {
			$url .= '&errstr=not_exist_file';
		}
		
        return  $url;
		
	}

	public function getCallback() {
		return view('bbs.plugins.smart_editor.callback');
	}
	
	public function getImage($filename) {
		$filename = 'editor/'.$filename;
		
		if (Storage::exists($filename)) {
			$file = Storage::get($filename);
			$type = Storage::mimeType($filename);
			
			return Response::make($file, 200)->header("Content-Type", $type);						
		}
	}
	
	public function missingMethod($parameters = []) {
	}
}