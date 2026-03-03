<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SystemSelectionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the system selection screen.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Eğer zaten seçim yapıldıysa, anasayfaya yönlendir (Frit için) veya ilgili sisteme
        if (session()->has('selected_system')) {
            if (session('selected_system') === 'frit') {
                return redirect()->route('home');
            } elseif (session('selected_system') === 'granilya') {
                return redirect()->route('granilya.dashboard');
            }
        }

        return view('auth.system-selection');
    }

    /**
     * Store the selected system in session and redirect.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'system' => 'required|in:frit,granilya',
        ]);

        $system = $request->input('system');
        session(['selected_system' => $system]);

        if ($system === 'frit') {
            return redirect()->route('home');
        } else {
            return redirect()->route('granilya.dashboard');
        }
    }

    /**
     * Clear the system selection and redirect to the selection screen.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function change()
    {
        session()->forget('selected_system');
        return redirect()->route('system.selection.index');
    }
}
