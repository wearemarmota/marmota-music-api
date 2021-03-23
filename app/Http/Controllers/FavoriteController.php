<?php

namespace App\Http\Controllers;

use App\Favorite;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{

    use ApiResponser;

    /**
     * Get the authenticated User.
     *
     * @return Response
     */
    public function index()
    {
        // $favorites = Auth::userOrFail()->favorites()->pluck('songs.id');
        $favorites = Auth::userOrFail()->favorites()->get();
        return $this->successResponse($favorites);
    }

    /**
     * Add song to favorites.
     *
     * @return Response
     */
    public function store(Request $request)
    {

        $rules = [
            'song_id' => 'required|exists:songs,id',
        ];

        $this->validate($request, $rules);

        $user = Auth::userOrFail();

        $favorite = Favorite::firstOrCreate([
            'user_id' => $user->id,
            'song_id' => $request->input('song_id'),
        ]);

        return $this->successResponse($favorite);
    }

    /**
     * Remove song from favorutes.
     *
     * @return Response
     */
    public function destroy($favorite)
    {
        $favorite = Favorite::findOrFail($favorite);
        $favorite->delete();
        return $this->successResponse($favorite);
    }

}
