<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use File;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class ApiController extends Controller
{
    public function register(Request $request)
    {
    	//Validate data
        $data = $request->only('name', 'email', 'password');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new user
        $user = User::create([
        	'name' => $request->name,
        	'email' => $request->email,
        	'password' => bcrypt($request->password)
        ]);

        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }
 
    public function hellotest() {
        return response()->json([
          'text' => 'Hello Laravel REST Api'
        ], Response::HTTP_OK);
    }
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is validated
        //Crean token
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                	'success' => false,
                	'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
    	return $credentials;
            return response()->json([
                	'success' => false,
                	'message' => 'Could not create token.',
                ], 500);
        }
 	
 		//Token created, return with success response and jwt token
        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }
 

  public function fileupload(Request $request)
    {
 
      // $validator = Validator::make($request->all(), 
      //         [ 
      //         'file' => 'required|mimes:doc,docx,pdf,txt|max:2048',
      //        ]);   
 
      //   if ($validator->fails()) {          
      //       return response()->json(['error'=>$validator->errors()], 401);                        
      //    }  
 
  
        if ($files = $request->file('file')) {
             
            //store file into document folder
            $file = $request->file->store('public/uploads');
 
            //store your file into database
            //$document = new Document();
            //$document->title = $file;
            //$document->user_id = $request->user_id;
            //$document->save();
              
            return response()->json([
                "success" => true,
                "message" => "File successfully uploaded",
                "file" => $file
            ]);
  
        }
 
  
    }


public function uploadImage(Request $request)
{
 // $validator = Validator::make($request->all(), [
 //    'image' => 'required|image:jpeg,png,jpg,gif,svg|max:2048'
 // ]);
 // if ($validator->fails()) {
 //    //return response()->json(['error'=> $validator->messages()->first()],  'error', 500);
 //    return response()->json(['error'=>$validator->errors()], 401);
 // }
 $uploadFolder = 'uploads';
 $currentPageUrl = 'http://' . $_SERVER["HTTP_HOST"].'/laravel-jwt-auth/public/files/';



 //$image = $request->file('image');

//if($request->hasfile('images')) {

    $mimages = array('sample.jpg','admin.jpg','test.jpg','ornatte.png');
     
    $imageresponse = [];
      

    foreach($request->file('image') as $images) {

        $name = time().rand(1,100).'.'.$images->extension();
        $path = $currentPageUrl.$name;

        $images->move(public_path('files'), $name);

        $img_res = array('Success' => true, 'message' => 'File successfully uploaded', 'image' => $path);

        array_push($imageresponse, $img_res);

     // return response()->json([
     //             "success" => true,
     //             "message" => "File successfully uploaded",
     //             "image" => $name
     //         ]);
        
        //echo $name.'<br>';
    }

    return response()->json($imageresponse);
         

     //return $img_return;
 // foreach($request->file('images') as $image) {


 //   //$files[] = $image->getClientOriginalName();
 //   //$image_uploaded_path = $image->store($uploadFolder, 'public');

 //    $name = time().rand(1,100).'.'.$image->extension();
      
 //    //$files[] = time().rand(1,100).'.'.$image->extension();
 //    $image->move(public_path('files'), $name);

 //      // $uploadedImageResponse = array(
 //      //    "image_name" => basename($image_uploaded_path),
 //      //    "image_url" => $currentPageUrl.$image_uploaded_path
 //      //   );
 //     return response()->json([
 //                "success" => true,
 //                "message" => "File successfully uploaded"
 //            ]);
 //     }

 // }

}
 

  
  

 // $uploadedImageResponse = array(
 //    "image_name" => basename($image_uploaded_path),
 //    "image_url" => $currentPageUrl.$image_uploaded_path,
 //    "mime" => $image->getClientMimeType()
 // );

 //             return response()->json([
 //                "success" => true,
 //                "message" => "File successfully uploaded",
 //                "file" => $uploadedImageResponse
 //            ]);
 //return sendCustomResponse('File Uploaded Successfully', 'success',   200, $uploadedImageResponse);
//}

public function helloworld() {

      echo 'test success';
}
    public function logout(Request $request)
    {

        // JWTAuth::invalidate();

        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Sorry, user cannot be logged out'
        //     ], Response::HTTP_INTERNAL_SERVER_ERROR);

        try {

            if ( $user = JWTAuth::parseToken()->authenticate()) {
                    JWTAuth::invalidate();
                }
            
            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }



    }
 

        public function get_user()
            {
                    try {

                            if (! $user = JWTAuth::parseToken()->authenticate()) {
                                    return response()->json(['user_not_found'], 404);
                            }

                    } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                            return response()->json(['token_expired'], $e->getStatusCode());

                    } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                            return response()->json(['token_invalid'], $e->getStatusCode());

                    } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

                            return response()->json(['token_absent'], $e->getStatusCode());

                    }

                    return response()->json(compact('user'));
            }
}