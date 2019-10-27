<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\ProviderPayments;

class PaymentType
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
        $provider_payments = ProviderPayments::where('provider_id', '=', $provider->id)->first();
        if (empty($provider_payments) && $provider->super_admin == 0) {
            //return view('providers.add-documents')->with(['provider_id' => $provider->id]);
            return redirect()->route('payments-type.add', $provider->id);
        }
        return $next($request);
    }
}
