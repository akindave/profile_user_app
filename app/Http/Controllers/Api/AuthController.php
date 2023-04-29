<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\RegisterUserRequest;
use Session;

class AuthController extends BaseController
{


    public function register(RegisterUserRequest $request)
    {

        $newUser = new User();
        $newUser->fill($request->validated());

        // if ($validator->fails()) {
        //     return redirect()->back()->withInput()->withErrors($validator);
        // }


        if ($request->file('profile_image')) {
            $image = $request->file('profile_image');
            $imagePath = $image->store('public/profile');
        } else {
            $base64File = $newUser->profile_image;
            // decode the base64 file
            $fileData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64File));
            // save it to temporary dir first.
            $tmpFilePath = sys_get_temp_dir() . '/' . Str::uuid()->toString();
            file_put_contents($tmpFilePath, $fileData);
            // this just to help us get file info.
            $tmpFile = new File($tmpFilePath);


            $file = new UploadedFile(
                $tmpFile->getPathname(),
                $tmpFile->getFilename(),
                $tmpFile->getMimeType(),
                0,
                true // Mark it as test, since the file isn't from real HTTP POST.
            );
            $imagePath = $file->store('public/profile');
        }
        $newUser->profile_image = $imagePath;
        $newUser->password = bcrypt($request->password);
        // $user = $newUser->save();

        $user = User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'is_admin' => false,
            'profile_image' => $imagePath
        ]);



        if(!$user){
            return $this->sendError('Cant Create user', []);
        }else{
            $success['token'] =  $user->createToken('auth_token')->plainTextToken;
            $success['user'] =  $user;
            return $this->sendResponse($success, 'User register successfully.');
        }



    }

    public function login(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string|max:11',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Something Went Wrong', $validator->errors());
        }
        $credentials = $validator->validated();
        if (!Auth::attempt($credentials)) {
             # Return invalid password
            //  $error = Session::flash('error', 'Invalid Phone and password!');
            // return redirect()->back()->withInput()->with($error);
            return $this->sendError('Cant Login user', []);
        }


        $user = Auth::user();
        $success['token'] =  $user->createToken('mycredly')->plainTextToken;
        $success['user'] =  $user;
        return $this->sendResponse($success, 'User login successfully.');
    }

    public function logout()
    {

        Auth::logout();
        return redirect()->to('/');
    }
}

