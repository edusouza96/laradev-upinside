<?php

namespace LaraDev\Http\Controllers\Admin;

use LaraDev\User;
use LaraDev\Contract;
use LaraDev\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LaraDev\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if(Auth::check() === true){
            return redirect()->route('admin.home');
        }
        return view('admin.index');
    }
    
    public function home()
    {
        $lessors = User::lessors()->count();
        $lessees = User::lessees()->count();
        $team = User::where('admin', 1)->count();
        
        $propertiesAvailable = Property::available()->count();
        $propertiesUnavailable = Property::unavailable()->count();
        $propertiesTotal = Property::all()->count();

        $contractsPendent = Contract::pending()->count();
        $contractsActive = Contract::active()->count();
        $contractsCanceled = Contract::canceled()->count();
        $contractsTotal = Contract::all()->count();
        
        $contracts = Contract::orderBy('id', 'desc')->limit(10)->get();
        $properties = Property::orderBy('id', 'desc')->limit(3)->get();
        
        return view('admin.dashboard', [
            'lessors' => $lessors, 
            'lessees' => $lessees, 
            'team' => $team, 
            'propertiesAvailable' => $propertiesAvailable, 
            'propertiesUnavailable' => $propertiesUnavailable, 
            'propertiesTotal' => $propertiesTotal, 
            'contractsPendent' => $contractsPendent,
            'contractsActive' => $contractsActive,
            'contractsCanceled' => $contractsCanceled,
            'contractsTotal' => $contractsTotal,    
            'contracts' => $contracts,
            'properties' => $properties,
        ]);
    }
    
    public function login(Request $request)
    {
        if(in_array('', $request->only('email', 'password'))){
            $json['message'] = $this->message->error('Ooops, informe todos os dados para efeturar o login')->render();
            return response()->json($json);
        }
        
        if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)){
            $json['message'] = $this->message->error('Ooops, informe um email válido')->render();
            return response()->json($json);
        }

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if(!Auth::attempt($credentials)){
            $json['message'] = $this->message->error('Ooops, usuário e senha não confere')->render();
            return response()->json($json);
        }

        $this->authenticate($request->ip());
        $json['redirect'] = route('admin.home');
        return response()->json($json);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
    
    private function authenticate(string $ip)
    {
        $user = User::where('id', Auth::user()->id);
        $user->update([
            'last_login_at' => date('Y-m-d H:i:s'),
            'last_login_ip' => $ip

        ]);
    }
}
