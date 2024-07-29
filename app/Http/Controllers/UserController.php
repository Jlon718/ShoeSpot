<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


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

    public function manageProfile()
    {
        $user = Auth::user();
        $customer = Customer::where('user_id', $user->id)->first();
        return view('customer.cusmanage', compact('user', 'customer'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        // Update more fields as necessary
        $user->save();

        return response()->json(['success' => true]);
    }

}
