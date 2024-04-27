<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    //change user role
    public function ChangeRole(Request $request)
    {
        $user_id = $request->route('user_id');
        $request->validate([
            'roles' => 'required|array|in:SuperAdmin,Admin,Employee,Client'
        ]);

        $user = User::find($user_id);

        // assign the selected roles 
        $user->syncRoles($request->roles);
        
        return response()->json(['message' => 'User role changed successfully'], 200);
    }
}
