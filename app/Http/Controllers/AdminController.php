<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public $perPage;

    public function __construct()
    {
        $this->middleware('auth');
        $this->perPage = 25; //每頁顯示筆數
    }

    public function category_list()
    {
        $data = Category::paginate($this->perPage);
        $total = $data->count();

        return view('goods.category_list', compact(['data', 'total']));
    }

    public function category_search(Request $request)
    {
        $inputData = $request->all();
        $rules = [
            'category_name' => 'nullable|string|between:1,255',
        ];
        $validator = Validator::make($inputData, $rules);
        if ($validator->fails()) {
            $data = [];
            $total = 0;
            return view('goods.category_list', compact(['data', 'total']));
        }

        $categoryName = $request->category_name;

        $query = Category::query();
        if (!empty(trim($categoryName))) {
            $query->where('category_name', 'LIKE', "%{$categoryName}%");
        }
        $data = $query->paginate($this->perPage);
        $total = $query->count();

        return view('goods.category_list', compact(['data', 'total']));
    }

    public function category_create_index()
    {
        return view('goods.category_create');
    }

    public function category_create(Request $request)
    {
        $inputData = $request->all();
        $rules = [
            'category_name' => 'required|string',
            'is_show' => 'required|integer',
        ];
        $validator = Validator::make($inputData, $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput(); 
        }

        //排除重複(可用->first()/count()/exists())
        $check = Category::orWhere('category_name', $request->title)
            ->count();
        if ($check) {
            return redirect()->back()->withErrors("類別已存在");
        }

        $query = new Category();
        $query->category_name = $request->category_name;
        $query->is_show = $request->is_show;
        $query->created_at = date('Y-m-d H:i:s');
        $query->updated_at = date('Y-m-d H:i:s');
        $query->save();
        $id = $query->id;

        if ($id) {
            return redirect('/category');
        }
        return redirect()->back()->withErrors(['msg' => '新增失敗,請聯繫IT']);
    }

    public function category_delete(Request $request)
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

        $query = Category::find($request->id);
        if (!$query) {
            $output['msg'] = '該筆資料不存在';
            return response()->json($output);
        }
        $destory = Category::destroy($request->id);
        if ($destory) {
            $output['code'] = 200;
            $output['msg'] = '刪除成功';
            return response()->json($output);
        }

        $output['code'] = 500;
        $output['msg'] = '刪除失敗';
        return response()->json($output);
    }

    public function category_update_index($id)
    {
        $id = (int) $id ?? 0;
        $data = Category::find($id);

        return view('goods.category_edit', compact(['data']));
    }

    public function category_update(Request $request)
    {
        $inputData = $request->all();
        $rules = [
            'category_name' => 'required|string',
            'is_show' => 'required|integer',
            'id' => 'required|integer',
        ];
        $validator = Validator::make($inputData, $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput(); 
        }

        $query = Category::find($request->id);
        if (!$query) {
            return redirect()->back()->withErrors(['msg' => '該筆資料不存在']);
        }
        $query->category_name = $request->category_name;
        $query->is_show = $request->is_show;
        $query->updated_at = date('Y-m-d H:i:s');
        $check = $query->save();

        if ($check) {
            return redirect('/category');
        }
        return redirect()->back()->withErrors(['msg' => '修改失敗,請聯繫IT']);
    }

    public function brand_list()
    {
        $data = Brand::paginate($this->perPage);
        $total = $data->count();

        $categoryArr = [];
        $dataCategory = Category::select('id', 'category_name')->get();
        if ($dataCategory) {
            foreach ($dataCategory as $row) {
                $categoryArr[$row->id] = $row->title;
            }
        }

        return view('goods.brand_list', compact(['data', 'total', 'categoryArr']));
    }

    public function brand_search(Request $request)
    {
        $inputData = $request->all();
        $rules = [
            'brand_name' => 'nullable|string|between:1,255',
        ];
        $validator = Validator::make($inputData, $rules);
        if ($validator->fails()) {
            $data = [];
            $total = 0;
            return view('goods.brand_list', compact(['data', 'total']));
        }

        $brandName = $request->brand_name;

        $query = Brand::query();
        if (!empty(trim($brandName))) {
            $query->where('brand_name', 'LIKE', "%{$brandName}%");
        }
        $data = $query->paginate($this->perPage);
        $total = $query->count();

        $categoryArr = [];
        $dataCategory = Category::select('id', 'category_name')->get();
        if ($dataCategory) {
            foreach ($dataCategory as $row) {
                $categoryArr[$row->id] = $row->category_name;
            }
        }

        return view('goods.brand_list', compact(['data', 'total', 'categoryArr']));
    }

    public function brand_create_index()
    {
        $dataCategory = Category::select('id', 'category_name')->get();

        return view('goods.brand_create', compact(['dataCategory']));
    }

    public function brand_create(Request $request)
    {
        $inputData = $request->all();
        $rules = [
            'category_id' => 'required|integer',
            'brand_name' => 'required|string',
            'is_show' => 'required|integer',
        ];
        $validator = Validator::make($inputData, $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput(); 
        }

        //排除重複(可用->first()/count()/exists())
        $check = Brand::orWhere('brand_name', $request->title)
            ->count();
        if ($check) {
            return redirect()->back()->withErrors("品牌已存在");
        }

        $query = new Brand();
        $query->category_id = $request->category_id;
        $query->brand_name = $request->brand_name;
        $query->is_show = $request->is_show;
        $query->created_at = date('Y-m-d H:i:s');
        $query->updated_at = date('Y-m-d H:i:s');
        $query->save();
        $id = $query->id;

        if ($id) {
            return redirect('/brand');
        }
        return redirect()->back()->withErrors(['msg' => '新增失敗,請聯繫IT']);
    }

    public function brand_delete(Request $request)
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

        $query = Brand::find($request->id);
        if (!$query) {
            $output['msg'] = '該筆資料不存在';
            return response()->json($output);
        }
        $destory = Brand::destroy($request->id);
        if ($destory) {
            $output['code'] = 200;
            $output['msg'] = '刪除成功';
            return response()->json($output);
        }

        $output['code'] = 500;
        $output['msg'] = '刪除失敗';
        return response()->json($output);
    }

    public function brand_update_index($id)
    {
        $id = (int) $id ?? 0;
        $data = Brand::find($id);
        $dataCategory = Category::select('id', 'category_name')->get();

        return view('goods.brand_edit', compact(['data', 'dataCategory']));
    }

    public function brand_update(Request $request)
    {
        $inputData = $request->all();
        $rules = [
            'category_id' => 'required|integer',
            'brand_name' => 'required|string',
            'is_show' => 'required|integer',
            'id' => 'required|integer',
        ];
        $validator = Validator::make($inputData, $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput(); 
        }

        $query = Brand::find($request->id);
        if (!$query) {
            return redirect()->back()->withErrors(['msg' => '該筆資料不存在']);
        }
        $query->category_id = $request->category_id;
        $query->brand_name = $request->brand_name;
        $query->is_show = $request->is_show;
        $query->updated_at = date('Y-m-d H:i:s');
        $check = $query->save();

        if ($check) {
            return redirect('/brand');
        }
        return redirect()->back()->withErrors(['msg' => '修改失敗,請聯繫IT']);
    }
}
