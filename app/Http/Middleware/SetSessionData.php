<?php

namespace App\Http\Middleware;

use App\Business;
use App\Utils\BusinessUtil;
use Closure;
use Illuminate\Support\Facades\Auth;

class SetSessionData
{
    /**
     * Checks if session data is set or not for a user. If data is not set then set it.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        
        // Set user session data if not exists
        if (! $request->session()->has('user')) {
            $session_data = ['id' => $user->id,
                'surname' => $user->surname,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'business_id' => $user->business_id,
                'language' => $user->language,
            ];
            $request->session()->put('user', $session_data);
        }
        
        // Always refresh business data from database to ensure latest settings (e.g., layout_template)
        $business = Business::findOrFail($user->business_id);
        $request->session()->put('business', $business);
        
        // Set currency data if not exists
        if (! $request->session()->has('currency')) {
            $currency = $business->currency;
            $currency_data = ['id' => $currency->id,
                'code' => $currency->code,
                'symbol' => $currency->symbol,
                'thousand_separator' => $currency->thousand_separator,
                'decimal_separator' => $currency->decimal_separator,
            ];
            $request->session()->put('currency', $currency_data);
        }

        //set current financial year to session if not exists
        if (! $request->session()->has('financial_year')) {
            $business_util = new BusinessUtil;
            $financial_year = $business_util->getCurrentFinancialYear($business->id);
            $request->session()->put('financial_year', $financial_year);
        }

        return $next($request);
    }
}
