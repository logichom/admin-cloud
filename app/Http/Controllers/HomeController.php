<?php

namespace App\Http\Controllers;

use App\Models\SidebarMenu;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $where = [];
        if (!empty(trim($name))) {
            $where[] = ['name', $name];
        }
        if (!empty(trim($email))) {
            $where[] = ['email', 'LIKE', "%{$email}%"];
        }
        $data = User::where($where)->paginate($this->perPage);
        $total = $data->count();

        return view('account.account_list', compact(['data', 'total']));
    }

    public function sidebar_menu_list()
    {
        $data = SidebarMenu::paginate($this->perPage);
        $total = $data->count();

        $userArr = [];
        $dataUser = User::select('id', 'name')->get();
        if ($dataUser) {
            foreach ($dataUser as $row) {
                $userArr[$row->id] = $row->name;
            }
        }

        return view('account.sidebar_menu_list', compact(['data', 'total', 'userArr']));
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

        $userList = [];
        if (!empty(trim($name)) || !empty(trim($email))) {
            $query = User::query();
            if (!empty(trim($name))) {
                $query->where('name', $name);
            }
            if (!empty(trim($email))) {
                $query->where('email', 'LIKE', "%{$email}%");
            }
            $dataUser = $query->get();

            if ($query->count()) {
                foreach ($dataUser as $row) {
                    $userList[] = $row->id;
                }
            }
        }

        // DB::enableQueryLog();
        $query = SidebarMenu::query();
        if ($userList) {
            $query->whereIn('created_by_user_id', $userList);
        }
        if (!empty(trim($title))) {
            $query->where('title', 'LIKE', "%{$title}%");
        }
        if (!empty(trim($categoryName))) {
            $query->where('category_name', 'LIKE', "%{$categoryName}%");
        }
        $data = $query->paginate($this->perPage);
        $total = $query->count();
        // var_dump(DB::getQueryLog());

        $userArr = [];
        $dataUser = User::select('id', 'name')->get();
        if ($dataUser) {
            foreach ($dataUser as $row) {
                $userArr[$row->id] = $row->name;
            }
        }

        return view('account.sidebar_menu_list', compact(['data', 'total', 'userArr']));
    }

    public function sidebar_menu_create_index()
    {
        return view('account.sidebar_menu_create');
    }

    public function sidebar_menu_create(Request $request)
    {
        $inputData = $request->all();
        $rules = [
            'title' => 'required|string',
            'category_name' => 'required|string',
            'seq' => 'required|integer',
        ];
        $validator = Validator::make($inputData, $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput(); 
        }

        //排除重複(可用->first()/count()/exists())
        $check = SidebarMenu::where('title', $request->title)
            ->where('category_name', $request->category_name)
            ->where('seq', $request->seq)
            ->count();
        if ($check) {
            return redirect()->back()->withErrors("目錄已存在");
        }

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

        $inputData = $request->all();
        $rules = [
            'id' => 'required|integer',
        ];
        $validator = Validator::make($inputData, $rules);
        if ($validator->fails()) {
            return response()->json($output);
        }

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
        $id = (int) $id ?? 0;
        $data = SidebarMenu::find($id);
        return view('account.sidebar_menu_edit', compact(['data']));
    }

    public function sidebar_menu_update(Request $request)
    {
        $inputData = $request->all();
        $rules = [
            'title' => 'required|string',
            'category_name' => 'required|string',
            'seq' => 'required|integer',
            'id' => 'required|integer',
        ];
        $validator = Validator::make($inputData, $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput(); 
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

        $userArr = [];
        $dataUser = User::select('id', 'name')->get();
        if ($dataUser) {
            foreach ($dataUser as $row) {
                $userArr[$row->id] = $row->name;
            }
        }

        $menuArr = [];
        $dataSidebarMenu = SidebarMenu::select('id', 'title')->get();
        if ($dataSidebarMenu) {
            foreach ($dataSidebarMenu as $row) {
                $menuArr[$row->id] = $row->title;
            }
        }

        return view('account.permission_list', compact(['data', 'total', 'userArr', 'menuArr']));
    }

    public function permission_search(Request $request)
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
            return view('account.permission_list', compact(['data', 'total']));
        }

        $name = $request->name;
        $email = $request->email;

        $userList = [];
        if (!empty(trim($name)) || !empty(trim($email))) {
            $query = User::query();
            if (!empty(trim($name))) {
                $query->where('name', $name);
            }
            if (!empty(trim($email))) {
                $query->where('email', 'LIKE', "%{$email}%");
            }
            $dataUser = $query->get();

            if ($query->count()) {
                foreach ($dataUser as $row) {
                    $userList[] = $row->id;
                }
            }
        }

        $query = UserPermission::query();
        if ($userList) {
            $query->whereIn('user_id', $userList);
        }
        $data = $query->paginate($this->perPage);
        $total = $query->count();

        $userArr = [];
        $dataUser = User::select('id', 'name')->get();
        if ($dataUser) {
            foreach ($dataUser as $row) {
                $userArr[$row->id] = $row->name;
            }
        }

        $menuArr = [];
        $dataSidebarMenu = SidebarMenu::select('id', 'title')->get();
        if ($dataSidebarMenu) {
            foreach ($dataSidebarMenu as $row) {
                $menuArr[$row->id] = $row->title;
            }
        }

        return view('account.permission_list', compact(['data', 'total', 'userArr', 'menuArr']));
    }

    public function permission_create_index()
    {
        $dataUser = User::select('id', 'name')->get();
        $dataSidebarMenu = SidebarMenu::select('id', 'title')->get();
        
        return view('account.permission_create', compact(['dataUser', 'dataSidebarMenu']));
    }

    public function permission_create(Request $request)
    {
        $inputData = $request->all();
        $rules = [
            'user_id' => 'required|integer',
            'sidebar_menu_id' => 'required|integer',
        ];
        $validator = Validator::make($inputData, $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput(); 
        }

        //排除重複(可用->first()/count()/exists())
        $check = UserPermission::where('user_id', $request->user_id)
            ->where('sidebar_menu_id', $request->sidebar_menu_id)
            ->count();
        if ($check) {
            return redirect()->back()->withErrors("權限已設定");
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

        $inputData = $request->all();
        $rules = [
            'id' => 'required|integer',
        ];
        $validator = Validator::make($inputData, $rules);
        if ($validator->fails()) {
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
        $id = (int) $id ?? 0;
        $data = UserPermission::find($id);
        return view('account.permission_edit', compact(['data']));
    }

    public function permission_update(Request $request)
    {
        $inputData = $request->all();
        $rules = [
            'user_id' => 'required|integer',
            'sidebar_menu_id' => 'required|integer',
            'id' => 'required|integer',
        ];
        $validator = Validator::make($inputData, $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput(); 
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
