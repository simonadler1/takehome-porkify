<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


 


class SongController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Song::paginate($request->get('pageSize'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $fields = $request->validate([
        'title' => 'required|string',
        'artist' => 'required|string',
        'album' => 'required|string',
        'category' => 'required|string',
        'tags' => 'required',
        'songurl' => 'required'
        ]);
        return Song::create([
            'title' => $fields['title'],
            'artist'=> $fields['artist'],
            'album' => $fields['album'],
            'category'=> $fields['category'],
            'tags' => json_encode($fields['tags']),
            'songurl' => $fields['songurl']

]);
    }
public function uploadMedia(Request $request){

    $file = $request->file('song')->store('songs');

    $url = Storage::url($file);

    $song = Song::find($request->get('songid'));

    $song->songurl = $url;
    
    $song->save();
       


}
  

  


}
