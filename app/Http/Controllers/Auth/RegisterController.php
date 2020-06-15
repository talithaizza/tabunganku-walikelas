<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('user-should-verified');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'avatar' => 'nullable|image|max:2048',
            'g-recaptcha-response' => 'required|captcha'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $request = app('request');

        // Isi field cover jika ada cover yang diupload
        if ($request->hasFile('avatar')) {

            // Mengambil file yang diupload
            $uploaded_avatar = $request->file('avatar');

            // Mengambil extension file
            $extension = $uploaded_avatar->getClientOriginalExtension();

            // Membuat nama file random berikut extension
            $filename = md5(time()) . "." . $extension;

            // Menyimpan cover ke folder public/img
            $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'img';
            $uploaded_avatar->move($destinationPath, $filename);

            // Mengisi field cover di book dengan filename yang baru dibuat
            $user->avatar = $filename;
            $user->save();

        } else {

            // Jika tidak ada cover yang diupload, pilih member_avatar.png
            $filename = "member_avatar.png";
            $user->avatar = $filename;
            $user->save();
        }

        $memberRole = Role::where('name', 'member')->first();
        $user->attachRole($memberRole);
        $user->sendVerification();

        return $user;
    }
	// Fungsi Buatan Ku :: Arfan Salamun ===========================================>
	// Register Function Check by GET method
	protected function createNewUser($name,$email,$password)
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        $request = app('request');

        // Isi field cover jika ada cover yang diupload
        if (false) {

            // Mengambil file yang diupload
            $uploaded_avatar = $request->file('avatar');

            // Mengambil extension file
            $extension = $uploaded_avatar->getClientOriginalExtension();

            // Membuat nama file random berikut extension
            $filename = md5(time()) . "." . $extension;

            // Menyimpan cover ke folder public/img
            $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'img';
            $uploaded_avatar->move($destinationPath, $filename);

            // Mengisi field cover di book dengan filename yang baru dibuat
            $user->avatar = $filename;
            $user->save();

        } else {

            // Jika tidak ada cover yang diupload, pilih member_avatar.png
            $filename = "member_avatar.png";
            $user->avatar = $filename;
            $user->save();
        }

        $memberRole = Role::where('name', 'walikelas')->first();
        $user->attachRole($memberRole);
        //$user->sendVerification();

        return response("SUCCESS!");
    }
	// Fungsi Buatan Ku :: Arfan Salamun ===========================================>

    public function verify(Request $request, $token)
    {
        $email = $request->get('email');
        $user = User::where('verification_token', $token)->where('email', $email)->first();

        if ($user) {

            $user->verify();

            Session::flash("flash_notification", [
                "level" => "success",
                "icon" => "fa fa-check",
                "message" => "Berhasil melakukan verifikasi."
            ]);

            Auth::login($user);
        }

        return redirect('/');
    }

    public function sendVerification(Request $request)
    {
        $user = User::where('email', $request->get('email'))->first();

        if ($user && !$user->is_verified) {

            $user->sendVerification();

            Session::flash("flash_notification", [
                "level" => "success",
                "icon" => "fa fa-check",
                "message" => "Silahkan klik pada link aktivasi yang telah kami kirim."
            ]);
        }

        return redirect('/login');
    }
}
