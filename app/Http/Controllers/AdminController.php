<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public $adminLogData;
    public $adminLogArr;
    public $uri;

    public function __construct(Request $request)
    {
        $this->getAdminUser();
        $this->uri = $request->path();
        //待加入判斷是否當前頁面active
        //待換成登入使用者名稱
    }

    public function getAdminUser()
    {
        $adminLogData = DB::select("SELECT a_l_l.*,a_l_li.a_l_l_title
        FROM admin_lv_log a_l_l
        JOIN admin_lv_list a_l_li ON a_l_li.a_l_l_seq = a_l_l.a_l_l_seq
        WHERE user_id = ?
        ORDER BY a_l_li.a_l_l_sort", [3600]);
        if (!$adminLogData) {
            return [];
        }
        $this->adminLogData = $adminLogData;
        foreach ($adminLogData as $row) {
            $this->adminLogArr[$row->a_l_l_seq] = $row->a_l_l_title;
        }
    }

    public function index()
    {
        return view('Backend.index', [
            'adminLogData' => $this->adminLogData,
            'adminLogArr' => $this->adminLogArr,
            'uri' => $this->uri,
        ]);
    }
}
