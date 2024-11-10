<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Members\Models\Member;
use Modules\Form\Models\Form;

class ApiController extends Controller
{
    public function getUsers($code)
    {
        if ($code != 4) {
            abort(403);
        }

        $data = Member::all();
        return response()->json($data);
    }

    public function getFlights($code)
    {
        if ($code != 4) {
            abort(403);
        }

        $data = Form::orderBy('date_time', 'desc')
                            ->with('member')
                            ->with('submittedModels')
                            ->get();

        return response()->json($data, 200);
    }
}
