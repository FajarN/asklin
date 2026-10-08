<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Hash;
use Illuminate\Support\Arr;
use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;

class ProfileController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        return view('backend.profile.index', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|same:confirm-password',
        ]);

        $input = $request->only(['name', 'email']);
        if (!empty($request->password)) {
            $input['password'] = Hash::make($request->password);
        }

        $user->update($input);

        return redirect()->route('profile.index', $user->id)->with('success', 'User updated successfully');
    }
}