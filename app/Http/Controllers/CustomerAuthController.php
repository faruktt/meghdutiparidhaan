<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('account.dashboard');
        }
        return view('frontend.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->login;
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $credentials = [
            $field     => $login,
            'password' => $request->password,
        ];

        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $this->syncSessionWishlist();
            return redirect()->intended(route('account.dashboard'))->with('success', 'Welcome back! You have successfully signed in.');
        }

        return back()->withErrors([
            'login' => 'Invalid phone number / email or password.',
        ])->withInput($request->only('login'));
    }

    public function showRegister()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('account.dashboard');
        }
        return view('frontend.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|max:20|unique:users,phone',
            'email'    => 'nullable|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'phone'    => $request->phone,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('web')->login($user);
        $this->syncSessionWishlist();

        return redirect()->route('account.dashboard')->with('success', 'Your account has been created successfully!');
    }

    public function dashboard()
    {
        $user = Auth::guard('web')->user();
        $orders = Order::where('user_id', $user->id)
            ->orWhere('customer_phone', $user->phone)
            ->with(['items.product'])
            ->latest()
            ->paginate(10);

        return view('frontend.account.dashboard', compact('user', 'orders'));
    }

    public function orderDetails($orderNumber)
    {
        $user = Auth::guard('web')->user();
        $order = Order::where('order_number', $orderNumber)
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('customer_phone', $user->phone);
            })
            ->with(['items.product', 'shippingZone'])
            ->firstOrFail();

        return view('frontend.account.order_detail', compact('order'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::guard('web')->user();

        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:500',
        ]);

        $user->update([
            'name'    => $request->name,
            'email'   => $request->email,
            'address' => $request->address,
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('success', 'Profile updated successfully!');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been signed out successfully.');
    }

    protected function syncSessionWishlist()
    {
        $user = Auth::guard('web')->user();
        if (!$user) return;

        $sessionWishlist = session()->get('wishlist', []);
        if (!empty($sessionWishlist)) {
            foreach ($sessionWishlist as $pid) {
                \App\Models\Wishlist::firstOrCreate([
                    'user_id'    => $user->id,
                    'product_id' => $pid,
                ]);
            }
        }

        $allIds = \App\Models\Wishlist::where('user_id', $user->id)->pluck('product_id')->toArray();
        session()->put('wishlist', $allIds);
    }
}
