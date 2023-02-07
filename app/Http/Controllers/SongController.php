<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
 
// return [
//     'file' => [
//         'required',
//         File::types(['mp3'])
//             ->max(10 * 1024),
//     ]
// ];

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
        ]);
        return Song::create([
            'title' => $fields['title'],
            'artist'=> $fields['artist'],
            'album' => $fields['album'],
            'category'=> $fields['category'],
            'tags' => json_encode($fields['tags'])
]);
    }
public function uploadMedia(Request $request){
if ($request->file('song')->isValid()) {
    $file = $request->file('file');
        $name = $file->hashName();
        $songid = $request -> input('songid');
        $upload = Storage::put("songs/{$name}", $file);
 
        Song::query()->create(
            attributes: [
                'name' => "{$name}",
                'songId' => "{$songid}",
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'path' => "songs/{$name}",
                'disk' => config('app.uploads.disk'),
                'file_hash' => hash_file(
                    config('app.uploads.hash'),
                    storage_path(
                        path: "avatars/{$name}",
                    ),
                ),
                'collection' => $request->get('collection'),
                'size' => $file->getSize(),
            ],
        );
            // add url to song entry 
                $song = Song::where('id', $songid)->update(array('url' => "{$upload}"));
                // $song = Song::find($songid);
                // $song->update($request->all());
                return $song;
}

}
  

  


}
