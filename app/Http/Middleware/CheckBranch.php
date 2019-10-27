<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckBranch
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
        if (count($provider->branches) == 0 && $provider->super_admin == 0) {
            //return view('providers.add-documents')->with(['provider_id' => $provider->id]);
            return redirect()->route('add.branche', $provider->id);
        }
        return $next($request);
    }
}
