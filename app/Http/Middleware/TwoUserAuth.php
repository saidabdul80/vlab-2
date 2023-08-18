<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session as FacadesSession;
use Session;
class TwoUserAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        
        if(FacadesSession::has('info')){
            $userData = session('info')->data->user ?? '';
            $role = $userData->role_id ?? '';
                     
            if ($role == '3' || $role == '2') {
                return $next($request);                
            }else{
                return redirect('/login');
            }
        }
        else {
            return redirect('/login');
        }
        
    
    }
}
