<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $users = User::with('customer')
        ->skip(($page - 1) * 10)
        ->take(10)
        ->get();
        $end = $users->count() < 10;

        return response()->json([
            'data' => $users,
            'end' => $end,
        ]);
    }

    public function show(string $id)
    {
        $user = User::where('id', $id)->first();
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        $product = User::updateOrCreate(
            ['id' => $request->user_id],
            [
                'role_as' => $request->role_as,
                'status' => $request->status,
            ]
        );

        return response()->json(["success" => "user updated successfully.", "user" => $user, "status" => 200]);
    }

}
