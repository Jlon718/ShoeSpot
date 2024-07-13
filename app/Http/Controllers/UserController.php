<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('customer')->get();
        return response()->json($users);
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
