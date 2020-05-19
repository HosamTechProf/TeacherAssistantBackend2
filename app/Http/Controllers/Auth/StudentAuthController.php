<?php
namespace App\Http\Controllers\Auth;
use App\User;
use App\Student;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentAuthController extends Controller
{
    public function login(Request $request) {
        $request->validate([
            'email'   => 'email|required',
            'password' => 'required|string'
        ]);
        $student = Student::where('email', $request->email)->first();
        if ($student) {
          if (!Hash::check($request->password, $student->password)) {
              return response()->json(['error' => 'Unauthorized'], 401);
          }
        $credentials = request(['email', 'password']);
        Auth::login($student);
        $tokenResult = $student->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'message'=>'Authorized',
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
      }else{
          return response()->json(['error' => 'Unauthorized'], 401);
      }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
                  'name'        => 'required',
                  'password'    => 'required|min:6',
                  'email'       => 'email|required|unique:students',
                  'phone'       => 'required|unique:students',
        ]);

        if ($validator->fails()) {
           return response()->json(['status'=>false, 'error'=>$validator->errors()], 401);
        }

        $school_id = $request->school_id;
        $student = new Student;
        $student->name = $request->name;
        $student->email = $request->email;
        $student->phone = $request->phone;
        $student->password = bcrypt($request->password);
        $student->school_id = $school_id;
        $image = $request->image;
        if ($image == null) {
          $imageUrl = 'student.png';
          $student->image = $imageUrl;
          $student->save();
          $success['token'] =  $student->createToken('Student Access Token')->accessToken;
          return response()->json(['status'=>true,'access_token'=>$success['token'],'msg'=>'Register Successful']);
        }
        else{
          $data = base64_decode($image);
          $imageUrl = "student-".time().".png";
          $path = public_path().'/img/students/' . $imageUrl;
          $student->image = $imageUrl;
          $student->save();
          file_put_contents($path, $data);
          $success['token'] =  $student->createToken('Student Access Token')->accessToken;
          return response()->json(['status'=>true,'access_token'=>$success['token'],'msg'=>'Register Successful']);
        }
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json([$request->user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $id = $user->id;
        $validator = Validator::make($request->all(), [
                  'name' => 'required',
                  'email' => 'nullable|email|unique:users,email,' . $id,
                  'phone'       => 'required|unique:users,email,' . $id

        ]);

        if ($validator->fails()) {
           return response()->json(['error'=>$validator->errors()], 401);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        $image = $request->image;
        if ($image == null) {
          $user->save();
        }
        else{
          $data = base64_decode($image);
          $imageUrl = "user-".time().".png";
          $path = public_path().'/img/users/' . $imageUrl;
          $user->image = $imageUrl;
          $user->save();
          file_put_contents($path, $data);
        }

        return response()->json([$user]);
    }
}
