<?php

namespace App\Http\Controllers;

use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    use FileUploadTrait;

    function update(Request $request)
    {
        $request->validate([
            'avatar' => ['nullable', 'image', 'max:500'],
            'name' => ['required', 'string', 'max:50'],
            'user_name' => ['required', 'string', 'max:50', 'unique:users,user_name,' . auth()->user()->id],
            'email' => ['required', 'email', 'max:100']
        ]);

        $avatarPath = $this->uploadFile($request, 'avatar');
        $user = auth()->user();
        if ($avatarPath) $user->avatar = $avatarPath;

        $user->name = $request->name;
        $user->user_name = $request->user_name;
        $user->email = $request->email;

        if ($request->filled('current_password')) {
            $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' =>  ['required', 'string', 'min:8', 'confirmed']
            ]);
            $user->password = Hash::make($request->password);
        }

        $user->save();

        notyf()
            ->position('x', 'right')
            ->position('y', 'top')
            ->duration(2000)
            ->addSuccess('Updated profile Successfully.');


        return response()->json(['message' => 'Updated Successfully!'], 200);
    }
}
