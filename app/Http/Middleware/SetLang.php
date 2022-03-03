<?php

namespace App\Http\Middleware;

use App\Language;
use Closure;
use Illuminate\Http\Request;

class SetLang
{

    public function handle($request, Closure $next)
    {
        $defaultLang =  Language::where('default',1)->first();
        if (session()->has('lang')) {
            $current_lang = Language::where('slug',session()->get('lang'))->first();
            if (!empty($current_lang)){
                app()->setLocale($current_lang->slug);
            }else {
                session()->forget('lang');
            }
        }else{
            app()->setLocale($defaultLang->slug);
        }
        return $next($request);
    }
}
