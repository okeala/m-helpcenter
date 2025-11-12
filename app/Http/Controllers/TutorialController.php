<?php

namespace Modules\Helpcenter\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Modules\Helpcenter\Models\Tutorial;

class TutorialController extends Controller
{
    /**
     * @return View
     */
    public function index()
    {
        return view('helpcenter::tutorials.index');
    }

    /**
     * @param Tutorial $tutorial
     * @return View
     */
    public function show(Tutorial $tutorial)
    {
        return view('helpcenter::tutorials.show', compact('tutorial'));
    }

}
