<?php

namespace App\Http\Controllers;

use App\User;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    use ApiResponser;

    /**
     * Get the authenticated User.
     *
     * @return Response
     */
    public function profile()
    {
        // return response()->json(['user' => Auth::user()], 200);
        $user = Auth::user();
        return $this->successResponse($user);
    }

    /**
     * Get all User.
     *
     * @return Response
     */
    public function allUsers()
    {
        // return response()->json(['users' =>  User::all()], 200);
        $users = User::all();
        $this->successResponse($users);
    }

    /**
     * Get one user.
     *
     * @return Response
     */
    public function singleUser($id)
    {
        // try {
        //     $user = User::findOrFail($id);

        //     return response()->json(['user' => $user], 200);

        // } catch (\Exception $e) {

        //     return response()->json(['message' => 'user not found!'], 404);
        // }
        $user = User::findOrFail($id);
        $this->successResponse($user);

    }

}
