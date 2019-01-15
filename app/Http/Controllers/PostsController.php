<?php

namespace App\Http\Controllers;

use App\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Posts::all();
    }

    public function store(Request $request)
    {
        $post = new Posts();

        $path = $request->file('arquivo')->store('imagens', 'public');
        
        $post->nome         = $request->nome;
        $post->email        = $request->email;
        $post->titulo       = $request->titulo;
        $post->subtitulo    = $request->subtitulo;
        $post->mensagem     = $request->mensagem;
        $post->arquivo      = $path;
        $post->likes        = 0;

        $post->save();

        return response($post, 200);
    }

    public function destroy($id)
    {
        $post = Posts::find($id);
        if(isset($post)){
            Storage::disk('public')->delete($post->arquivo);
            $post->delete();
            return 204;
        }
        return response('Post não encontrado', 404);
    }
    
    public function like($id)
    {
        $post = Posts::find($id);
        if(isset($post)){
            $post->likes++;
            $post->save();
            return $post;
        }
        return response('Post não encontrado', 404);
    }
}
