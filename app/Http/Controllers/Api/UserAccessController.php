<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserAccess;
use Illuminate\Http\Request;

class UserAccessController extends Controller
{
    public function __invoke(Request $request)
    {
        $paginator = UserAccess::paginate(20);

        return response()->json($paginator);
    }
}
