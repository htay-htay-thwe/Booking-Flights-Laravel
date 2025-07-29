<?php
namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\Reserve;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    #register Page
    public function register()
    {
        return view('auth.register');
    }
    #login Page
    public function login()
    {
        return view('auth.login');
    }

    public function settingPage()
    {
        return view('flight.settings');
    }

    public function forgotPw()
    {
        return view('auth.livewire-forgot');

    }

    public function resetPw(Request $request, $token)
    {
        return view('auth.livewire-reset', ['token' => $token, 'email' => $request->email]);
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current'  => 'required|string|min:6',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();

        if (! $user || ! Hash::check($request->current, $user->password)) {
            return back()->with('failed', 'Current Password Not Same!');

        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password Changed Successfully!');
    }

    public function updateEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'name'  => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();

        $user->update([
            'email' => $request->email,
            'name'  => $request->name,
        ]);

        return back()->with('email', 'Changed Successfully!');
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();

        if ($request->hasFile('image')) {
            // Delete old image if exists and is not default
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            // Store new image
            $path        = $request->file('image')->store('img', 'public');
            $user->image = $path;
            $user->save();
        }

        return redirect()->back()->with('profile', 'Profile picture updated successfully!');

    }

    public function forgotPasswordPage()
    {
        return view('auth.reset-password');
    }

    #register Post
    public function registerPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'              => 'required|unique:users',
            'password'           => 'required|min:8',
            'confirmed_password' => 'required|min:8',
            'username'           => 'required',
            'role'               => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/register')
                ->withErrors($validator)
                ->withInput();
        }

        if ($request->role == 'admin') {
            if ($request->password == $request->confirmed_password) {
                $user = User::create([
                    'name'     => $request->username,
                    'email'    => $request->email,
                    'password' => Hash::make($request->password),
                    'role'     => $request->role,
                ]);
            }
            Auth::login($user);
            return redirect('/dashboard')->with('success', 'Register Successfully...');
        }
        return back()->with('error', 'Only Admin Can register!');
    }

    #login Post
    public function loginPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|min:8',
            'role'     => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/login')
                ->withErrors($validator)
                ->withInput();
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            if (Auth::user()->role === $request->role) {
                return redirect('/dashboard')->with('success', 'Login Successfully...');
            } else {
                Auth::logout(); // Wrong role
                return back()->withErrors(['role' => 'Invalid role.']);
            }
        }

        return back()->withErrors(['email' => 'Invalid email or password.']);
    }

    // log out
    public function logout()
    {
        Auth::logout(); // Logs out user
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/login');
    }

    public function dashboard()
    {
        $userCount = User::where('role', 'user')->count();
        $admin     = User::where('role', 'admin')->get();

        $newUserCount       = $this->newUser();
        $bookingsPerMonth   = $this->monthlyBookings();
        $revenuePerMonth    = $this->totalRevenue();
        $flightStatusCounts = $this->flightStatus();
        $usersPerMonth      = $this->userPerMonth();
        $totalYearlyRevenue = array_sum($revenuePerMonth);

        return view('dashboard', compact('userCount', 'admin', 'totalYearlyRevenue', 'newUserCount', 'bookingsPerMonth', 'revenuePerMonth', 'flightStatusCounts', 'usersPerMonth'));
    }

    public function adminDelete($id)
    {
        User::where('id', $id)->delete();
        return back()->with('success', 'deleted!');
    }

    private function userPerMonth()
    {
        $monthlyUsers = User::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('count', 'month')
            ->toArray();

        $usersPerMonth = [];
        $allMonths     = range(1, 12);

        foreach ($allMonths as $month) {
            $usersPerMonth[] = $monthlyUsers[$month] ?? 0;
        }
        return $usersPerMonth;
    }

    private function flightStatus()
    {
        $cancelled          = Flight::where('flightStatus', 'cancel')->count();
        $delayed            = Flight::where('flightStatus', 'delayed')->count();
        $all                = Flight::count();
        $onTime             = $all - ($cancelled + $delayed);
        $flightStatusCounts = [
            'Cancelled' => $cancelled,
            'Delayed'   => $delayed,
            'On Time'   => $onTime,
        ];
        return $flightStatusCounts;
    }

    private function totalRevenue()
    {
        $rates = config('exchange');

        $paidReserves = Reserve::where('paymentStatus', 'paid')->get();

        $monthlyRevenue = [];
        $allMonths      = range(1, 12);

        foreach ($paidReserves as $reserve) {
            $month     = Carbon::parse($reserve->created_at)->month;
            $rate      = $rates[$reserve->currency] ?? 1; // Default to 1 if currency unknown
            $usdAmount = $reserve->total / $rate;

            if (! isset($monthlyRevenue[$month])) {
                $monthlyRevenue[$month] = 0;
            }

            $monthlyRevenue[$month] += $usdAmount;
        }
        $revenuePerMonth = [];

        foreach ($allMonths as $month) {
            $revenuePerMonth[] = round($monthlyRevenue[$month] ?? 0, 2);
        }

        return $revenuePerMonth;
    }

    private function monthlyBookings()
    {
        $monthlyBookings = Reserve::where('paymentStatus', 'paid')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('count', 'month')
            ->toArray();
        // Fill missing months with 0
        $allMonths        = range(1, 12);
        $bookingsPerMonth = [];

        foreach ($allMonths as $month) {
            $bookingsPerMonth[] = $monthlyBookings[$month] ?? 0;
        }

        return $bookingsPerMonth;
    }

    private function newUser()
    {
        $now = Carbon::now();

        $newUserCount = User::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->where('role', 'user')
            ->count();
        return $newUserCount;
    }

    // public function googleRedirect()
    // {
    //     return Socialite::driver('google')->redirect();
    // }

    // public function googleCallback()
    // {
    //     try {
    //         $googleUser = Socialite::driver('google')->user();

    //         $user = User::updateOrCreate(
    //             ['email' => $googleUser->getEmail()],
    //             [
    //                 'name'      => $googleUser->getName(),
    //                 'email'     => $googleUser->getEmail(),
    //                 'google_id' => $googleUser->getId(),
    //                 'image'     => $googleUser->getAvatar(), // optional
    //             ]
    //         );

    //         Auth::login($user);

    //         return redirect('/dashboard');

    //     } catch (\Exception $e) {
    //         return redirect('/login')->withErrors(['msg' => 'Google login failed.']);
    //     }
    // }

    // public function facebookRedirect()
    // {

    //     return Socialite::driver('facebook')
    //         ->scopes(['email'])
    //         ->redirect();

    // }

    // public function facebookCallback()
    // {
    //     try {
    //         $fbUser = Socialite::driver('facebook')->user();

    //         // Fallback for users with no email
    //         $email = $fbUser->getEmail();
    //         if (! $email) {
    //             return redirect('/login')->withErrors(['msg' => 'Unable to retrieve email from Facebook.']);
    //         }

    //         $user = User::updateOrCreate(
    //             ['email' => $email],
    //             [
    //                 'name'  => $fbUser->getName(),
    //                 'email' => $email,
    //                 'fb_id' => $fbUser->getId(),
    //                 'image' => $fbUser->getAvatar(),
    //             ]
    //         );

    //         Auth::login($user);

    //         return redirect('/dashboard');

    //     } catch (\Exception $e) {
    //         return redirect('/login')->withErrors(['msg' => 'Facebook login failed.']);
    //     }
    // }

    // public function githubRedirect()
    // {
    //     return Socialite::driver('github')
    //         ->redirect();
    // }

    // public function githubCallback()
    // {
    //     try {
    //         $githubUser = Socialite::driver('github')->user();

    //         // Fallback for users with no email
    //         $email = $githubUser->getEmail();
    //         if (! $email) {
    //             return redirect('/login')->withErrors(['msg' => 'Unable to retrieve email from Facebook.']);
    //         }

    //         $user = User::updateOrCreate(
    //             ['email' => $email],
    //             [
    //                 'name'  => $githubUser->getName() ?? $githubUser->getNickname(),
    //                 'email' => $email,
    //                 'fb_id' => $githubUser->getId(),
    //                 'image' => $githubUser->getAvatar(),
    //             ]
    //         );

    //         Auth::login($user);

    //         return redirect('/dashboard');

    //     } catch (\Exception $e) {
    //         return redirect('/login')->withErrors(['msg' => 'Github login failed.']);
    //     }
    // }

}
