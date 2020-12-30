<?php

  namespace App\Http\Controllers;

  use Illuminate\Http\Request;
  use App\Models\Account as AccountModel;
  use App\Models\Blog as BlogModel;

  class AccountController extends Controller
  {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($uname)
    {
      $record_ppage = 6;
      $record_pline = 3;
      $account = AccountModel::where('uname', '=', $uname)->first();
      $entries = $this->hasBlogs($uname, 0, $record_ppage);
      return view('profile')
        ->with('record_pline', $record_pline)
        ->with('account', $account)
        ->with('entries', $entries->get())
        ->with('total_entries', $entries->get()->count());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      // Validate the request...
      $account = new AccountModel;

      $account->uname = $request->uname;
      $account->fname = $request->fname;
      $account->email = $request->email;
      $account->pass = $request->pass;
      $account->gender = $request->gender;

      $account->save();
    }

    public function login(Request $request) {
      if (session_status() == PHP_SESSION_NONE) session_start();
      if ($request->isMethod('post'))
      {
        $uname = $request->input('uname');
        $pass = $request->input('pass');
        if (empty($uname) || empty($pass))
          return "Empty input";
        else
        {
          $res = AccountModel::where('uname', '=', $uname)
            ->where('pass', '=', $pass)
            ->get();
          if ($res->count() == 0) return "Login failed";
          else
          {
            $res = $res->first();
            $_SESSION['login-user'] = $res['uname'];
            return $res['uname'];
          }
        }
      }
      else return "Cannot execute request method";
    }

    public function findExistedUser(Request $request) {
      $res = AccountModel::where('uname', '=', $request->input('uname'))
        ->orWhere('email', '=', $request->input('email'))
        ->get()
        ->count();
      return $res > 0 ? "Existed username or email" : "Ready to register";
    }

    public function register(Request $request) {
      if (session_status() == PHP_SESSION_NONE) session_start();

      $this->store($request);
      $_SESSION['login-user'] = $request->uname;
      return true;
    }

    public function getAccount(Request $request) {
      $account = AccountModel::where('uname', '=', $request->input('uname'))->first();
      return $account;
    }

    public function editProfile(Request $request) {
      if ($request->isMethod('post'))
      {
        $account = AccountModel::find($request->input('uname'));

        $account->fname = $request->input('fname');
        $account->email = $request->input('email');
        $account->pass = $request->input('pass');
        $account->gender = $request->input('gender');

        $saved = $account->save();
        if (!$saved) return false;
        return "Save changes";
      }
      return false;
    }

    public function hasBlogs($uname, $start_entry, $record_ppage) {
      $entries = BlogModel::select(
        'uname',
        'title',
        BlogModel::raw('DATE_FORMAT(_date, "%H:%i:%s %d-%m-%Y") as d'),
        'blog',
        'summary',
        'url')
        ->join('accounts', 'accounts.uname', '=', 'blogs.reviewer')
        ->join('images', 'images.blog', '=', 'blogs.id')
        ->where('reviewer', '=', $uname)
        ->orderBy('_date', 'desc')
        ->offset($start_entry)
        ->limit($record_ppage);
      return $entries;
    }
  }
 ?>
