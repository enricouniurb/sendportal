<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SetupController extends Controller
{
    /**
     * @return View|RedirectResponse
     */
    public function index()
    {
        try {
            if (User::exists()) {
                return redirect()->route('saml2_login', env('IDP_ENV_ID', 'local'));
            }
        } catch (Exception $e) {
            //
        }

        return view('setup.index');
    }
}
