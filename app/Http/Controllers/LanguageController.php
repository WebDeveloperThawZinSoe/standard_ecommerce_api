<?php

  

namespace App\Http\Controllers;

  

use Illuminate\Http\Request;

use Illuminate\View\View;

use Illuminate\Http\RedirectResponse;

use App;

  

class LanguageController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

    */

    public function index(): View

    {

        return view('lang');

    }

  

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

    */

    public function change(Request $request): RedirectResponse

    {

        // dd($request->lang);
        App::setLocale($request->lang);

        session()->put('locale', $request->lang);

  

        return redirect()->back();

    }

}