<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment as CommentModel;

class CommentController extends Controller
{
  public function addComment(Request $request) {
    if ($request->content == '') return "Empty content";
    $id = CommentModel::max('id');
    $comment = new CommentModel;

    $comment->id = $id + 1;
    $comment->blog = $request->blog;
    $comment->author = $request->author;
    $comment->content = $request->content;

    $saved = $comment->save();
    if (!$saved) return false;
    return true;
  }

  public function deleteComment(Request $request) {
    $comment = CommentModel::find($request->id);
    $saved = $comment->delete();
    if (!$saved) return false;
    return true;
  }
}
