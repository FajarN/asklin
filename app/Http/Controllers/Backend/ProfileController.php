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
        $user = User::where('id', Auth::user()->id);

        return view('backend.profile.index', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
        ]);

        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }

        $user = User::find($id);
        $user->update($input);

        return redirect()->route('profile.index', $user->id)->with('success','User updated successfully');
    }
}