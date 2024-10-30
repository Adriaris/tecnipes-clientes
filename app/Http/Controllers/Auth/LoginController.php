<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    protected function authenticated(Request $request, $user)
    {
        $user->api_token = Str::random(60);
        $user->save();

        if ($request->expectsJson()) {
            return response()->json([
                'user' => $user,
                'token' => $user->api_token,
            ]);
        }

        return redirect()->intended($this->redirectPath());
    }
}

