<?php

namespace App\Http\Controllers\API;

use App\Models\Page;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }

        try {
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
            $success['token'] = $user->createToken(env('TOKEN_SALT'))->plainTextToken;
            $success['name'] = $user->name;

            $page = new Page([
                'primary_color' => 'ffffff',
                'secondary_color' => '000000',
                'background_color' => 'ffffff',
                'text_color' => '000000',
                'custom_url' => uniqid(),
                'theme' => 'default',
                'title' => null,
                'tagline' => null
            ]);

            $user->pages()->save($page);
        } catch (\Exception $exception) {
            $this->sendError($exception->getMessage(), ['error' => 'Unauthorised'], 403);
        }

        return $this->sendResponse($success, 'User register successfully');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return $this->sendError('Unauthorised', ['error' => 'Unauthorised'], 403);
        }

        $user = Auth::user();
        $success['token'] = $user->createToken(env('TOKEN_SALT'))->plainTextToken;

        return $this->sendResponse($success, 'User login successfully');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? $this->sendResponse([], $status)
            : $this->sendError($status, ['error' => $status]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? $this->sendResponse([], $status)
            : $this->sendError($status, ['error' => 'Unauthorised'], 401);
    }

    public function destroy()
    {
        $user = \auth()->user();
        try {
            $user->delete();
            return $this->sendResponse([], 'User delete successfully');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error' => 'Unauthorised'], 401);
        }
    }
}
