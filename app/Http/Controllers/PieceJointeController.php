<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PointODJ;
use Illuminate\Support\Facades\Storage;

class PieceJointeController extends Controller
{
  public function store(Request $r, PointODJ $point){
    $r->validate(['file'=>['required','file','mimes:pdf,doc,docx,png,jpg,jpeg','max:10240']]);
    $path = $r->file('file')->store('pieces','public');
    return $point->pieces()->create([
      'nom_fichier'=>$r->file('file')->getClientOriginalName(),
      'url'=>asset('storage/'.$path),
      'type_mime'=>$r->file('file')->getMimeType(),
      'taille_ko'=>intval(filesize($r->file('file')->getRealPath())/1024),
    ]);
  }
}
