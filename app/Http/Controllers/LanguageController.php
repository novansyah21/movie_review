<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

class LanguageController extends Controller
{
    public function changeLocale($locale)
    {
        if (in_array($locale, ['en', 'id'])) {
            Session::put('app_locale', $locale);
        }
        return redirect()->back();
    }
}
