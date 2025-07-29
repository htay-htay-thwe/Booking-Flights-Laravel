<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // Register user
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'                  => 'required|string',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|string|confirmed|min:6',
            'accepted'              => 'required|accepted',
            'password_confirmation' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),

        ]);

        // Auto-login after register
        $token = auth('api')->attempt($request->only('email', 'password'));

        if (! $token) {
            return response()->json(['error' => 'Authentication failed after registration.'], 500);
        }

        return $this->respondWithToken($token, auth('api')->user());

    }

    // Login user
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string',
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
            'accepted' => 'required|accepted',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $user        = User::where('email', $request->email)->first();
        $credentials = $request->only('email', 'password');

        if (! $user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        if (! Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'password is incorrect.'], 403);
        }

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Account Does not Exits! Something Wrong,'], 401);
        }

        return $this->respondWithToken($token, auth('api')->user());
    }

    // change password
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current'           => 'required|string|min:6',
            'password'          => 'required|string|min:6',
            'confirmedPassword' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::find($request->id);

        if (! $user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        if (! Hash::check($request->current, $user->password)) {
            return response()->json(['error' => 'Current password is incorrect.'], 403);
        }

        if ($request->password != $request->confirmedPassword) {
            return response()->json(['error' => 'Password and Confirm Password do not match.'], 403);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Password updated successfully.',
            'user'    => $user,
        ], 200);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
        ? response()->json(['message' => __($status)])
        : response()->json(['message' => __($status)], 400);

    }

    public function changeEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email|unique:users,email',
            'username' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::find($request->id);

        if (! $user) {
            return response()->json(['error' => 'User not found.'], 404);
        }
        $user->update([
            'email' => $request->email,
            'name'  => $request->username,
        ]);

        return response()->json([
            'message' => 'Data updated successfully.',
            'user'    => $user,
        ], 200);

    }

    public function changeProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::find($request->id);

        if (! $user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        if ($request->hasFile('profile')) {
            $filePath    = $request->file('profile')->store('image', 'public');
            $user->image = $filePath;
            $user->save();
        }

        return response()->json([
            'message' => 'Data updated successfully.',
            'user'    => $user,
        ], 200);

    }

    public function googleRedirect()
    {
        return response()->json([
            'url' => Socialite::driver('google')
                ->stateless()
                ->redirect()
                ->getTargetUrl(),
        ]);

    }

    public function googleCallback(Request $request)
    {

        if (! $request->has('code')) {
            return response()->json(['error' => 'Missing authorization code'], 422);
        }

        try {
            // Attempt to get the user from GitHub
            $githubUser = Socialite::driver('google')->stateless()->user();

            $user = User::updateOrCreate(
                ['email' => $githubUser->getEmail()],
                [
                    'name'      => $githubUser->getName() ?? $githubUser->getNickname(),
                    'google_id' => $githubUser->getId(),
                    'image'     => $githubUser->getAvatar(),
                ]
            );

            $token = JWTAuth::fromUser($user);

            return $this->respondWithToken($token, $user);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to authenticate with GitHub', 'message' => $e->getMessage()], 422);
        }

    }

    public function facebookRedirect()
    {

        return response()->json([
            'url' => Socialite::driver('facebook')
                ->stateless()
                ->redirect()
                ->getTargetUrl(),
        ]);

    }

    public function facebookCallback(Request $request)
    {
        if (! $request->has('code')) {
            return response()->json(['error' => 'Missing authorization code'], 422);
        }

        try {
            // Attempt to get the user from GitHub
            $githubUser = Socialite::driver('facebook')->stateless()->user();

            $user = User::updateOrCreate(
                ['email' => $githubUser->getEmail()],
                [
                    'name'      => $githubUser->getName() ?? $githubUser->getNickname(),
                    'github_id' => $githubUser->getId(),
                    'image'     => $githubUser->getAvatar(),
                ]
            );

            $token = JWTAuth::fromUser($user);

            return $this->respondWithToken($token, $user);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to authenticate with GitHub', 'message' => $e->getMessage()], 422);
        }
    }

    public function githubRedirect(Request $request)
    {
        return response()->json([
            'url' => Socialite::driver('github')
                ->stateless()
                ->redirect()
                ->getTargetUrl(),
        ]);
    }

    public function githubCallback(Request $request)
    {

        if (! $request->has('code')) {
            return response()->json(['error' => 'Missing authorization code'], 422);
        }

        try {
            // Attempt to get the user from GitHub
            $githubUser = Socialite::driver('github')->stateless()->user();

            $user = User::updateOrCreate(
                ['email' => $githubUser->getEmail()],
                [
                    'name'      => $githubUser->getName() ?? $githubUser->getNickname(),
                    'github_id' => $githubUser->getId(),
                    'image'     => $githubUser->getAvatar(),
                ]
            );

            $token = JWTAuth::fromUser($user);

            return $this->respondWithToken($token, $user);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to authenticate with GitHub', 'message' => $e->getMessage()], 422);
        }
    }

    // Get authenticated user details
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    // Format the token response
    protected function respondWithToken($token, $user = null)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => JWTAuth::factory()->getTTL() * 60,
            'user'         => $user,
        ]);
    }
}
