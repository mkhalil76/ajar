<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Provider;
use Auth;

class isAdmin
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
        $provider = Provider::where('id', '=', $provider->id)->first();
        if ($provider->super_admin == 1) {
            //return view('providers.add-documents')->with(['provider_id' => $provider->id]);
            return redirect()->route('admin.home');
        }
        return $next($request);
    }
}
