<?php
  namespace App\Http\Controllers;

  use Illuminate\Http\Request;
  // use Illuminate\Http\File;
  use App\Models\Blog as BlogModel;
  use App\Models\Image as ImageModel;
  use App\Models\Rate as RateModel;
  use App\Models\Comment as CommentModel;
  use Illuminate\Support\Facades\File as File;

  class BlogController extends Controller
  {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $record_ppage = 6;
      $record_pline = 3;
      $entries = $this->loadnews(0, $record_ppage);
      return view('index')
        ->with('record_ppage', $record_ppage)
        ->with('record_pline', $record_pline)
        ->with('entries', $entries->get())
        ->with('total_entries', $entries->get()->count());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
      $image = ImageModel::where('blog', '=', $id)->first();
      $blog = BlogModel::where('id', '=', $id)->first();
      $rate = RateModel::where('blog', '=', $id)->avg('stars');
      $comments = CommentModel::where('blog', '=', $id)->get();
      return view('blog')
        ->with('imgUrl', $image->url)
        ->with('blog', $blog)
        ->with('rate', $rate)
        ->with('comments', $comments)
        ->with('total_comments', $comments->count());
    }

    public function loadnews($start_entry, $record_ppage) {
      $entries = BlogModel::select(
        'uname',
        'title',
        BlogModel::raw('DATE_FORMAT(_date, "%H:%i:%s %d-%m-%Y") as d'),
        'blog',
        'summary',
        'url')
        ->join('accounts', 'accounts.uname', '=', 'blogs.reviewer')
        ->join('images', 'images.blog', '=', 'blogs.id')
        ->orderBy('_date', 'desc')
        ->offset($start_entry)
        ->limit($record_ppage);
      return $entries;
    }

    public function autoload(Request $request) {
      $entries = $this->loadnews($request->input('start_entry'), $request->input('record_ppage'));
      return $entries->get();
    }

    public function addBlog(Request $request) {
      if ($request->isMethod('post'))
      {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $target_dir = '/images/images/';
        $target_file = $target_dir . basename($_FILES['fileToUpload']['name']);
        $imageFileType = $request->fileToUpload->extension();
        $newFileName = time() . '.' . $imageFileType;
        $readyToUpload = true;

        if ($_FILES['fileToUpload']['size'] > 500000)
        {
          return "Sorry, your file is too large.";
          $readyToUpload = false;
        }

        $cnt = 0;
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'webp');
        foreach ($allowTypes as $value)
        {
          if ($imageFileType == $value) break;
          ++$cnt;
        }

        if ($cnt == count($allowTypes))
        {
          return "File type is not allowed";
          $readyToUpload = false;
        }

        if ($readyToUpload)
        {
          // $saved = true;
          $saved = $request->fileToUpload->move(public_path($target_dir), $newFileName);
          if (!$saved) return "Cannot upload image";
          else
          {
            $blog = new BlogModel;
            $image = new ImageModel;

            $blogId = BlogModel::max('id') + 1;

            $blog->id = $blogId;
            $blog->title = $request->input('title');
            $blog->content = $request->input('content');
            $blog->summary = $request->input('summary');
            $blog->product = $request->input('product');
            $blog->reviewer = $request->input('reviewer');
            $blog->_date = date("Y-m-d H:i:s");

            $image->blog = $blogId;
            $image->id = 0;
            $image->url = $newFileName;

            if (!$blog->save() || !$image->save()) return "Cannot save information";
            else return "Uploaded";
          }
        }
      }
      return "Cannot upload image";
    }

    public function editBlog(Request $request) {
      if ($request->isMethod('post'))
      {
        $blog = BlogModel::find($request->input('id'));

        $blog->title = $request->input('title');
        $blog->summary = $request->input('summary');
        $blog->content = $request->input('content');
        $blog->product = $request->input('product');

        $saved = $blog->save();
        if (!$saved) return "Fail";
        return "Save changes";
      }
      return "Fail";
    }

    public function deleteBlog(Request $request) {
      if ($request->isMethod('post'))
      {
        $images = ImageModel::where('blog', '=', $request->input('blog'));
        foreach ($images->get() as $image) {
          $image_path = public_path('/images/images/'.$image['url']);
          if (!File::exists($image_path)) return false;
          if (!unlink($image_path)) return false;
        }
        if (!$images->delete()) return false;

        $blog = BlogModel::find($request->input('blog'));
        if ($blog->delete()) return "Deleted";
      }
      return false;
    }

    public function search() {
      return view('search');
    }

    public function loadresult($keyword, $start_entry, $record_ppage) {
      $entries = BlogModel::select(
        'uname',
        'title',
        BlogModel::raw('DATE_FORMAT(_date, "%H:%i:%s %d-%m-%Y") as d'),
        'blog',
        'summary',
        'url')
        ->join('accounts', 'accounts.uname', '=', 'blogs.reviewer')
        ->join('images', 'images.blog', '=', 'blogs.id')
        ->where('title', 'like', '%'.$keyword.'%')
        ->orderBy('_date', 'desc')
        ->offset($start_entry)
        ->limit($record_ppage);
      return $entries;
    }

    public function result(Request $request) {
      $keyword = trim($request->input('keyword'));
      $new_kw = str_replace(" ", "%' or title like '%", $keyword);
      // $total_entries = BlogModel::paginate();
      $total_entries = BlogModel::select(
        'uname',
        'title',
        BlogModel::raw('DATE_FORMAT(_date, "%H:%i:%s %d-%m-%Y") as d'),
        'blog',
        'summary',
        'url')
        ->join('accounts', 'accounts.uname', '=', 'blogs.reviewer')
        ->join('images', 'images.blog', '=', 'blogs.id')
        ->where('title', 'like', '%'.$new_kw.'%');

      $record_ppage = $request->input('record_ppage');
      $current_page = $request->input('current_page');
      $start_entry = ($current_page - 1)*$record_ppage;
      $total_results = $total_entries->get()->count();
      $total_pages = ceil($total_results/$record_ppage);
      $entries = $this->loadresult($new_kw, $start_entry, $record_ppage);
      $previous_page = ($current_page > 1) ? $current_page - 1 : 0;
      $next_page = ($current_page < $total_pages) ? $current_page + 1 : 0;
      return array(
        'keyword'=>$new_kw,
        'total_entries'=>$total_results,
        'total_pages'=>$total_pages,
        'previous_page'=>$previous_page,
        'next_page'=>$next_page,
        'entries'=>$entries->get(),
        'record_ppage'=>$record_ppage,
        'record_pline'=>3,
        'current_page'=>$current_page
      );
    }
  }
 ?>
