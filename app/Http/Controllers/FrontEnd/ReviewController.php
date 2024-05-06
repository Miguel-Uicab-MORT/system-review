<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ReviewController extends Controller
{
    public function index() : Response
    {
        return Inertia::render('Dashboard', [
            'userLogged' => Auth::check(),
            'userAuth' => Auth::user(),
        ]);
    }

    public function createReview() : Response
    {
        return Inertia::render('Review/ReviewCreate', [
            'userLogged' => Auth::check(),
            'userAuth' => Auth::user(),
        ]);
    }

    public function show($id) : Response
    {
        return Inertia::render('Review/ReviewShow', [
            'id' => $id,
            'userLogged' => Auth::check(),
            'userAuth' => Auth::user(),
        ]);
    }
}
