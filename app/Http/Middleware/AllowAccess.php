<?php
namespace App\Http\Middleware;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
class Allowaccess extends BaseVerifier{
	
	protected $except = [ 
		'/ajax-logout',
		'/ajax-check-login'
	];

	public function handle($request, \Closure $next)
	{
		if ($request->ajax()) {
			return $next($request);
		}
		return redirect()->guest('login');
	}
}