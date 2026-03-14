<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureVihoTemplate
{
    /**
     * Only allow /ai-template routes when the active template is viho.
     */
    public function handle(Request $request, Closure $next)
    {
        // Access layout_template from business object stored in session
        $business = session('business');
        $common_settings = !empty($business->common_settings) ? $business->common_settings : [];
        $layout_template = !empty($common_settings['layout_template']) ? $common_settings['layout_template'] : 'default';

        if ($layout_template !== 'viho') {
            // Keep behavior simple: if user is not on viho template, push them back to default home.
            return redirect()->to('/home');
        }

        return $next($request);
    }
}

