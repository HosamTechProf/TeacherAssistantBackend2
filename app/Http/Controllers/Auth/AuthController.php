<?php
namespace App\Http\Controllers\Auth;
use App\User;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request) {
        $request->validate([
            'email'   => 'email|required',
            'password' => 'required|string'
        ]);
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized',
                'error' => 'Unauthorized'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
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
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
                  'name'        => 'required',
                  'password'    => 'required|min:6',
                  'c_password'  => 'required|same:password',
                  'email'       => 'email|required|unique:users',
                  'phone'       => 'required|unique:users',
        ]);

        if ($validator->fails()) {
           return response()->json(['error'=>$validator->errors()], 401);
        }

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = bcrypt($request->password);
        $user->type = "admin";
        $image = $request->image;
        if ($image == null) {
          $imageUrl = 'user.png';
          $user->image = $imageUrl;
          $user->save();
          $success['token'] =  $user->createToken('Personal Access Token')->accessToken;
          return response()->json(['status'=>true,'access_token'=>$success['token'],'msg'=>'Register Successful']);
        }
        else{
          // $image = substr($image, strpos($image, ",")+1);
          $data = base64_decode($image);
          $imageUrl = "user-".time().".png";
          $path = public_path().'/img/users/' . $imageUrl;
          $user->image = $imageUrl;
          $user->save();
          file_put_contents($path, $data);
          $success['token'] =  $user->createToken('Personal Access Token')->accessToken;
          return response()->json(['status'=>true,'access_token'=>$success['token'],'msg'=>'Register Successful']);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
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
