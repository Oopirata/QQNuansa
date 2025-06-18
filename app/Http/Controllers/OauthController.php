<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception; // Ini sudah ada
use App\Models\User;
use Illuminate\Support\Facades\Log; // Tambahkan ini
use Illuminate\Http\Request;       // Tambahkan ini

class OauthController extends Controller
{
    public function redirectToProvider()
    {
        try {
            Log::info('OAuth: Redirecting to Google. Session ID: ' . session()->getId());
            return Socialite::driver('google')->redirect();
        } catch (\Exception $e) {
            Log::error('OAuth: Exception during redirect: ' . $e->getMessage());
            // Tampilkan error langsung jika redirect gagal parah, atau DD jika perlu
            // dd('Exception during redirect', $e); 
            return redirect()->route('login')->with('error', 'Gagal mengarahkan ke Google: ' . $e->getMessage());
        }
    }

    // Tambahkan Request $request agar bisa mengambil input
    public function handleProviderCallback(Request $request)
    {
        Log::info('OAuth: handleProviderCallback hit. Session ID: ' . session()->getId());
        Log::info('OAuth: State from request: ' . $request->input('state'));
        Log::info('OAuth: State from session (key: state): ' . session()->get('state')); // Cek state Socialite
        Log::info('OAuth: All session data on callback: ', session()->all());

        try {
            $googleUser = Socialite::driver('google')->user(); // Saya ganti nama variabel agar tidak bentrok dengan $user dari model

            $finduser = User::where('gauth_id', $googleUser->id)->first();

            if ($finduser) {
                Auth::login($finduser);
                Log::info('OAuth: Existing user logged in.', ['user_id' => $finduser->id, 'email' => $finduser->email]);
                return redirect('/');
            } else {
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'gauth_id' => $googleUser->id,
                    'gauth_type' => 'google',
                    'password' => bcrypt('admin@123') // Gunakan bcrypt() atau Hash::make()
                ]);
                Auth::login($newUser);
                Log::info('OAuth: New user created and logged in.', ['user_id' => $newUser->id, 'email' => $newUser->email]);
                return redirect('/');
            }
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) { // Tangkap InvalidStateException secara spesifik
            Log::error('OAuth: InvalidStateException caught!', [
                'message' => $e->getMessage(),
                'session_id' => session()->getId(),
                'request_state' => $request->input('state'),
                'session_state' => session()->get('state'), // Ini adalah state yang disimpan Socialite
                'all_session_data' => session()->all()
            ]);
            // !!! MODIFIKASI UNTUK DEBUG !!!
            dd('InvalidStateException:', $e, 'Request State: ' . $request->input('state'), 'Session State (key: state): ' . session()->get('state'), 'All Session Data:', session()->all());
            // return redirect()->route('login')->with('error', 'Login gagal: State tidak valid. Silakan coba lagi.');

        } catch (\Exception $e) { // Tangkap exception umum lainnya
            Log::error('OAuth: General Exception caught in callback: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            // !!! MODIFIKASI UNTUK DEBUG !!!
            dd('General Exception:', $e, 'All Session Data:', session()->all());
            // return redirect()->route('login')->with('error', 'Login gagal: ' . $e->getMessage());
        }
    }
}
