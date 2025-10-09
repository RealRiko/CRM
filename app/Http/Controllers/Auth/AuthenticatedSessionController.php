<?php

namespace App\Http\Controllers\Auth; 
// Definē kontrolieri autentifikācijas funkcijām

use App\Http\Controllers\Controller; 
// Iekļauj pamata Controller klasi
use App\Http\Requests\Auth\LoginRequest; 
// Iekļauj LoginRequest klasi, kas validē pieteikšanās datus
use Illuminate\Http\RedirectResponse; 
// Nodrošina RedirectResponse klasi, lai veiktu pāradresācijas
use Illuminate\Http\Request; 
// Iekļauj Request klasi, lai apstrādātu HTTP pieprasījumus
use Illuminate\Support\Facades\Auth; 
// Nodrošina Auth fasādi autentifikācijai
use Illuminate\View\View; 
// Nodrošina View klasi, lai atgrieztu skatus

class AuthenticatedSessionController extends Controller
// Definē kontrolieri sesiju (autentifikācijas) darbībām
{
    /**
     * Parāda pieteikšanās skatu.
     */
    public function create(): View
    // Funkcija, kas atgriež pieteikšanās lapas skatu
    {
        return view('auth.login'); 
        // Atgriež Blade skatu 'auth.login'
    }

    /**
     * Apstrādā ienākošo autentifikācijas pieprasījumu.
     */
    public function store(LoginRequest $request): RedirectResponse
    // Funkcija, kas apstrādā pieteikšanās formu
    {
        $request->authenticate(); 
        // Veic lietotāja autentifikāciju, izmantojot LoginRequest

        $request->session()->regenerate(); 
        // Atjauno sesijas ID, lai novērstu sesijas pārņemšanu (security)

        return redirect()->intended(route('dashboard', absolute: false)); 
        // Pāradresē lietotāju uz iepriekš plānoto lapu vai uz dashboard
    }

    /**
     * Iznīcina autentificētu sesiju.
     */
    public function destroy(Request $request): RedirectResponse
    // Funkcija, kas veic izrakstīšanos
    {
        Auth::guard('web')->logout(); 
        // Izrakstās no 'web' guard (standarta Laravel lietotāji)

        $request->session()->invalidate(); 
        // Noliek sesiju nederīgu, dzēšot datus

        $request->session()->regenerateToken(); 
        // Atjauno CSRF token, lai saglabātu drošību

        return redirect('/'); 
        // Pāradresē uz mājaslapas sākumlapu pēc izrakstīšanās
    }
}

