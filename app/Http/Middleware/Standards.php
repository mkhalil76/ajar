<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Standards
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $provider = Auth::user();

        if ($provider->standerds == null && $provider->super_admin == 0) {
            //return view('providers.add-documents')->with(['provider_id' => $provider->id]);
            return redirect()->route('add.standerds', $provider->id);
        }
        return $next($request);
    }
}
