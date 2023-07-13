<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class MovieController extends Controller
{
    public function getMovies()
    {
        return response()->json(Movie::latest()->get());
    }

    public function getSingleMovie($id)
    {
        $movie = Movie::findOrFail($id);
        return response()->json([
            'data' => $movie,
        ]);
    }

    public function getMoviesPage()
    {
        $movies = Movie::paginate(2)->toArray();
        return array_reverse($movies);
    }
    
    public function saveMovie(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'title' => 'required',
                'director' => 'required',
                'year' => 'required | numeric',
                'runtime' =>'required',
                'rating' => 'required',
                'info' => 'required',
            ]);
            if($validator->fails()){
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()
                ]);
            } else {

                // if ($request->img){
                //     $upload_path = public_path('img/movie');
                //     $extension = $request->img->getClientOriginalExtension();
                //     $img = time() . '.' . $extension;
                //     $request->img->move($upload_path, $img);
                // } else {
                //     $img = null;
                // }

                $img = '';

                if($request->file('img')){
                    $file = $request->file('img');
                    $img = time().$file->getClientOriginalName();
                    $file->move(public_path('img/movie'), $img);
                }

                Movie::create([
                    'title' => $request->title,
                    'director' => $request->director,
                    'year' => $request->year,
                    'runtime' => $request->runtime,
                    'rating' => $request->rating,
                    'info' => $request->info,
                    'img' => $img,
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Movie add successfully'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
    
    public function updateMovie(Request $request, $id)
    {
        $movie = Movie::findOrFail($id);
        
        if ($request->file('img')){
            
            if ($movie->img) {
                unlink(public_path('img/movie/' . $movie->img));
            }
            $file = $request->file('img');
            $img = time().$file->getClientOriginalName();
            $file->move(public_path('img/movie'), $img);
        } else {
            $img = $movie->img;
        }

        Movie::where('id',$id)->update([
            'title' => $request->title,
            'director' => $request->director,
            'year' => $request->year,
            'runtime' => $request->runtime,
            'rating' => $request->rating,
            'info' => $request->info,
            'img' => $img,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Movie update successfully',
        ]);
    }
    public function deleteMovie($id){
        $movie = Movie::findOrFail($id);
        if ($movie->img) {
            unlink(public_path('img/movie/' . $movie->img));
        }
        Movie::where('id',$id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Movie delete successfully',
        ]);
    }
}
