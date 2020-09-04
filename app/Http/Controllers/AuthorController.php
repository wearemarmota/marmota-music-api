<?php

namespace App\Http\Controllers;

use App\Author;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthorController extends Controller
{

    use ApiResponser;

    /**
     * Return authors list
     *
     * @return @Illuminate\Http\Response
     */
    public function index(Request $request)
    {

      if( $request->has('name') ){

        if( $request->input('exact') ){
            $authors = Author::where('name', $request->input('name'))->get();
        }else{
            $authors = Author::where('name', 'like', '%' . $request->input('name') . '%')->get();
        }
      }else{

          $authors = Author::all();
      }

      return $this->successResponse($authors);
    }

    /**
     * Create an instance of author
     *
     * @return @Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      $rules = [
        'name' => 'required|min:2|max:255',
      ];

      $this->validate($request, $rules);

      $author = Author::create($request->all());

      return $this->successResponse($author, Response::HTTP_CREATED);
    }

    /**
     * Return specific author
     *
     * @return @Illuminate\Http\Response
     */
    public function show($author)
    {
      $author = Author::findOrFail($author);

      return $this->successResponse($author);
    }

    /**
     * Update the information of an existing author
     *
     * @return @Illuminate\Http\Response
     */
    public function update(Request $request, $author)
    {
      $rules = [
        'name' => 'required|min:2|max:255',
      ];

      $this->validate($request, $rules);

      $author = Author::findOrFail($author);

      $author->fill($request->all());

      if($author->isClean()){
        return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
      }

      $author->save();

      return $this->successResponse($author);
    }

    /**
     * Removes an existing author
     *
     * @return @Illuminate\Http\Response
     */
    public function destroy($author)
    {
      $author = Author::findOrFail($author);
      $author->delete();
      return $this->successResponse($author);
    }

  }
