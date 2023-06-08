<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public $perPage;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->perPage = 20; //每頁顯示筆數
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function account_list()
    {
        $data = User::paginate($this->perPage);
        $total = User::count();

        return view('account.account_list', compact(['data', 'total']));
    }

    public function account_search(Request $request)
    {
        $name = $request->name;
        $email = $request->email;

        $query = DB::table('users');
        if (!empty(trim($name))) {
            $query->where('name', $name);
        }
        if (!empty(trim($email))) {
            $query->where('email', 'LIKE', '%' . $email . '%');
        }
        $data = $query->paginate($this->perPage);
        $total = $query->count();

        return view('account.account_list', compact(['data', 'total']));
    }

    public function sidebar_menu_list()
    {
        $data = DB::table('sidebar_menu')->paginate($this->perPage);
        $total = DB::table('sidebar_menu')->count();

        return view('account.sidebar_menu_list', compact(['data', 'total']));
    }

    public function sidebar_menu_search(Request $request)
    {
        $name = $request->name;
        $email = $request->email;
        $title = $request->title;
        $categoryName = $request->category_name;

        $userList = [];
        if (!empty(trim($name)) || !empty(trim($email))) {
            $query = DB::table('users');
            if (!empty(trim($name))) {
                $query->where('name', $name);
            }
            if (!empty(trim($email))) {
                $query->where('email', 'LIKE', '%' . $email . '%');
            }
            $dataUser = $query->get();

            if ($query->count()) {
                foreach ($dataUser as $row) {
                    $userList[] = $row->id;
                }
            }
        }

        $query = DB::table('sidebar_menu');
        if ($userList) {
            $query->whereIn('created_by_user_id', $userList);
        }
        if (!empty(trim($title))) {
            $query->where('title', 'LIKE', '%' . $email . '%');
        }
        if (!empty(trim($categoryName))) {
            $query->where('category_name', 'LIKE', '%' . $email . '%');
        }
        $data = $query->paginate($this->perPage);
        $total = $query->count();

        return view('account.sidebar_menu_list', compact(['data', 'total']));
    }

    public function sidebar_menu_create_index()
    {
        return view('account.sidebar_menu_create');
    }

    public function sidebar_menu_create(Request $request)
    {
        if (empty($request->title)) {
            return redirect()->back()->withErrors(['msg' => '請輸入目錄名稱']); 
        }
        if (empty($request->category_name)) {
            return redirect()->back()->withErrors(['msg' => '請輸入目錄類別名稱']); 
        }
        if (empty($request->seq)) {
            return redirect()->back()->withErrors(['msg' => '請輸入排序']); 
        }

        $id = DB::table('sidebar_menu')->insertGetId([
            'title' => $request->title,
            'category_name' => $request->category_name,
            'seq' => $request->seq,
            'created_by_user_id' => Auth::user()->id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        if ($id) {
            return redirect('/sidebar_menu');
        }

        return redirect()->back()->withErrors(['msg' => '新增失敗,請聯繫IT']);
    }

    public function sidebar_menu_delete(Request $request)
    {
        $output = [
            'code' => 400,
            'msg' => '輸入資訊有誤',
        ];

        if (!isset($request->id) || !$request->id) {
            return response()->json($output);
        }

        $data = DB::table('sidebar_menu')->where('id', $request->id)->get();
        if (!$data || !$data[0]->id) {
            $output['msg'] = '該筆資料不存在';
            return response()->json($output);
        }

        $check = DB::table('sidebar_menu')->where('id', $request->id)->delete();
        if ($check) {
            $output['code'] = 200;
            $output['msg'] = '刪除成功';
            return response()->json($output);
        }

        $output['code'] = 500;
        $output['msg'] = '刪除失敗';
        return response()->json($output);
    }

    public function permission_list()
    {
        $data = DB::table('users_permission')->paginate($this->perPage);
        $total = DB::table('users_permission')->count();

        return view('account.permission_list', compact(['data', 'total']));
    }

    public function permission_search(Request $request)
    {
        $name = $request->name;
        $email = $request->email;

        $userList = [];
        if (!empty(trim($name)) || !empty(trim($email))) {
            $query = DB::table('users');
            if (!empty(trim($name))) {
                $query->where('name', $name);
            }
            if (!empty(trim($email))) {
                $query->where('email', 'LIKE', '%' . $email . '%');
            }
            $dataUser = $query->get();

            if ($query->count()) {
                foreach ($dataUser as $row) {
                    $userList[] = $row->id;
                }
            }
        }

        $query = DB::table('users_permission');
        if ($userList) {
            $query->whereIn('user_id', $userList);
        }
        $data = $query->paginate($this->perPage);
        $total = $query->count();

        return view('account.permission_list', compact(['data', 'total']));
    }

    public function permission_create_index()
    {
        return view('account.permission_create');
    }

    public function permission_create(Request $request)
    {
        if (empty($request->user_id)) {
            return redirect()->back()->withErrors(['msg' => '請輸入管理者編號']); 
        }
        if (empty($request->sidebar_menu_id)) {
            return redirect()->back()->withErrors(['msg' => '請輸入目錄編號']); 
        }

        $id = DB::table('users_permission')->insertGetId([
            'user_id' => $request->user_id,
            'sidebar_menu_id' => $request->sidebar_menu_id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        if ($id) {
            return redirect('/permission');
        }

        return redirect()->back()->withErrors(['msg' => '新增失敗,請聯繫IT']);
    }

    public function permission_delete(Request $request)
    {
        $output = [
            'code' => 400,
            'msg' => '輸入資訊有誤',
        ];

        if (!isset($request->id) || !$request->id) {
            return response()->json($output);
        }

        $data = DB::table('users_permission')->where('id', $request->id)->get();
        if (!$data || !$data[0]->id) {
            $output['msg'] = '該筆資料不存在';
            return response()->json($output);
        }

        $check = DB::table('users_permission')->where('id', $request->id)->delete();
        if ($check) {
            $output['code'] = 200;
            $output['msg'] = '刪除成功';
            return response()->json($output);
        }

        $output['code'] = 500;
        $output['msg'] = '刪除失敗';
        return response()->json($output);
    }
}
