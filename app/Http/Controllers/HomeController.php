<?php

namespace App\Http\Controllers;

use App\Models\SidebarMenu;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        $this->perPage = 25; //每頁顯示筆數
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
        $total = $data->count();

        return view('account.account_list', compact(['data', 'total']));
    }

    public function account_search(Request $request)
    {
        $inputData = $request->all();
        $rules = [
            'name' => 'nullable|string|between:2,100',
            'email' => 'nullable|string|max:100',
        ];
        $validator = Validator::make($inputData, $rules);
        if ($validator->fails()) {
            $data = [];
            $total = 0;
            return view('account.account_list', compact(['data', 'total']));
        }

        $name = $request->name;
        $email = $request->email;

        // $query = DB::table('users');
        // if (!empty(trim($name))) {
        //     $query->where('name', $name);
        // }
        // if (!empty(trim($email))) {
        //     $query->where('email', 'LIKE', '%' . $email . '%');
        // }
        // $data = $query->paginate($this->perPage);
        // $total = $query->count();

        $where = [];
        if (!empty(trim($name))) {
            $where[] = ['name', $name];
        }
        if (!empty(trim($email))) {
            $where[] = ['email', 'LIKE', '%' . $email . '%'];
        }
        $data = User::where($where)->paginate($this->perPage);
        $total = $data->count();

        return view('account.account_list', compact(['data', 'total']));
    }

    public function sidebar_menu_list()
    {
        $data = SidebarMenu::paginate($this->perPage);
        $total = $data->count();

        return view('account.sidebar_menu_list', compact(['data', 'total']));
    }

    public function sidebar_menu_search(Request $request)
    {
        $inputData = $request->all();
        $rules = [
            'name' => 'nullable|string|between:2,100',
            'email' => 'nullable|string|max:100',
            'title' => 'nullable|string',
            'category_name' => 'nullable|string',
        ];
        $validator = Validator::make($inputData, $rules);
        if ($validator->fails()) {
            $data = [];
            $total = 0;
            return view('account.sidebar_menu_list', compact(['data', 'total']));
        }

        $name = $request->name;
        $email = $request->email;
        $title = $request->title;
        $categoryName = $request->category_name;

        //待改成ORM...
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
            $query->where('title', 'LIKE', '%' . $title . '%');
        }
        if (!empty(trim($categoryName))) {
            $query->where('category_name', 'LIKE', '%' . $categoryName . '%');
        }
        $data = $query->paginate($this->perPage);
        $total = $query->count();

        //wherein有問題...
        // DB::enableQueryLog();
        // $whereIn = [];
        // $where = [];
        // if ($userList) {
        //     $whereIn = ['created_by_user_id', $userList];
        // }
        // if (!empty(trim($title))) {
        //     $where[] = ['title', 'LIKE', '%' . $title . '%'];
        // }
        // if (!empty(trim($categoryName))) {
        //     $where[] = ['category_name', 'LIKE', '%' . $categoryName . '%'];
        // }
        // if ($whereIn && $where) {
        //     dd($where, $whereIn);
        //     $data = SidebarMenu::whereIn($whereIn)->where($where)->paginate($this->perPage);
        // } elseif (!$whereIn && $where) {
        //     $data = SidebarMenu::whereIn($whereIn)->paginate($this->perPage);
        // } elseif (!$whereIn && $where) {
        //     $data = SidebarMenu::where($where)->paginate($this->perPage);
        // } else {
        //     $data = SidebarMenu::paginate($this->perPage);
        // }
        // var_dump(DB::getQueryLog());
        // $total = $data->count();

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

        // $id = DB::table('sidebar_menu')->insertGetId([
        //     'title' => $request->title,
        //     'category_name' => $request->category_name,
        //     'seq' => $request->seq,
        //     'created_by_user_id' => Auth::user()->id,
        //     'created_at' => date('Y-m-d H:i:s'),
        //     'updated_at' => date('Y-m-d H:i:s'),
        // ]);

        $query = new SidebarMenu();
        $query->title = $request->title;
        $query->category_name = $request->category_name;
        $query->seq = $request->seq;
        $query->created_by_user_id = Auth::user()->id;
        $query->created_at = date('Y-m-d H:i:s');
        $query->updated_at = date('Y-m-d H:i:s');
        $query->save();
        $id = $query->id;

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

        // $data = DB::table('sidebar_menu')->where('id', $request->id)->get();
        // if (!$data || !$data[0]->id) {
        //     $output['msg'] = '該筆資料不存在';
        //     return response()->json($output);
        // }
        // $check = DB::table('sidebar_menu')->where('id', $request->id)->delete();
        // if ($check) {
        //     $output['code'] = 200;
        //     $output['msg'] = '刪除成功';
        //     return response()->json($output);
        // }

        $query = SidebarMenu::find($request->id);
        if (!$query) {
            $output['msg'] = '該筆資料不存在';
            return response()->json($output);
        }
        $destory = SidebarMenu::destroy($request->id);
        if ($destory) {
            $output['code'] = 200;
            $output['msg'] = '刪除成功';
            return response()->json($output);
        }

        $output['code'] = 500;
        $output['msg'] = '刪除失敗';
        return response()->json($output);
    }

    public function sidebar_menu_update_index($id)
    {
        $data = SidebarMenu::find($id);
        return view('account.sidebar_menu_edit', compact(['data']));
    }

    public function sidebar_menu_update(Request $request)
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
        if (!isset($request['id']) || !$request->id) {
            return redirect()->back()->withErrors(['msg' => '參數錯誤']);
        }

        $query = SidebarMenu::find($request->id);
        if (!$query) {
            return redirect()->back()->withErrors(['msg' => '該筆資料不存在']);
        }
        $query->title = $request->title;
        $query->category_name = $request->category_name;
        $query->seq = $request->seq;
        $query->updated_at = date('Y-m-d H:i:s');
        $check = $query->save();

        if ($check) {
            return redirect('/sidebar_menu');
        }
        return redirect()->back()->withErrors(['msg' => '修改失敗,請聯繫IT']);
    }

    public function permission_list()
    {
        $data = UserPermission::paginate($this->perPage);
        $total = $data->count();

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

        //待改成ORM...
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

        $query = new UserPermission();
        $query->user_id = $request->user_id;
        $query->sidebar_menu_id = $request->sidebar_menu_id;
        $query->created_at = date('Y-m-d H:i:s');
        $query->updated_at = date('Y-m-d H:i:s');
        $query->save();
        $id = $query->id;

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

        $query = UserPermission::find($request->id);
        if (!$query) {
            $output['msg'] = '該筆資料不存在';
            return response()->json($output);
        }
        $destory = UserPermission::destroy($request->id);
        if ($destory) {
            $output['code'] = 200;
            $output['msg'] = '刪除成功';
            return response()->json($output);
        }

        $output['code'] = 500;
        $output['msg'] = '刪除失敗';
        return response()->json($output);
    }

    public function permission_update_index($id)
    {
        $data = UserPermission::find($id);
        return view('account.permission_edit', compact(['data']));
    }

    public function permission_update(Request $request)
    {
        if (empty($request->user_id)) {
            return redirect()->back()->withErrors(['msg' => '請輸入管理者編號']); 
        }
        if (empty($request->sidebar_menu_id)) {
            return redirect()->back()->withErrors(['msg' => '請輸入目錄編號']); 
        }
        if (!isset($request['id']) || !$request->id) {
            return redirect()->back()->withErrors(['msg' => '參數錯誤']);
        }

        $query = UserPermission::find($request->id);
        if (!$query) {
            return redirect()->back()->withErrors(['msg' => '該筆資料不存在']);
        }
        $query->user_id = $request->user_id;
        $query->sidebar_menu_id = $request->sidebar_menu_id;
        $query->updated_at = date('Y-m-d H:i:s');
        $check = $query->save();

        if ($check) {
            return redirect('/permission');
        }
        return redirect()->back()->withErrors(['msg' => '修改失敗,請聯繫IT']);
    }
}
