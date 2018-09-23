<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BackendController extends Controller
{

    /**
     * Get the admin home.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('backend.home');
    }


    /**
     * Redirect the response.
     *
     * @param Request $request
     * @param $message
     * @param string $type
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function redirectResponse(Request $request, $message, $type = 'success')
    {
        if ($request->wantsJson()) {
            return response()->json([
                'status'  => $type,
                'message' => $message,
            ]);
        }

        return redirect()->back()->with("flash_{$type}", $message);
    }
}
