# 說明

### 版本

- Laravel: 8.83.27
- AdminLTE: 3.2.0
- PHP: 7.4.33

---

### 開發流程(6/1-?)

- 新增laravel專案 `composer create-project --prefer-dist laravel/laravel admin-cloud`
- 修改.env檔案DB設定(DB,user,password)
- 新增資料庫laravel
- 新增table `php artisan migrate`
- 補安裝額外table `php artisan session:table`
- 然後再新增一次 `php artisan migrate`
- 安裝額外套件 `composer require laravel/ui`
- 產生auth相關檔案 `php artisan ui bootstrap --auth`
- `npm install`
- `npm run dev`
- 透過網頁註冊帳號
- 測試登入帳號
- 製作AdminLTE共用模板
- 替換原始認證功能為AdminLTE模板
- 測試註冊及登入功能
- 新增目錄表、權限表、管理目錄功能、管理權限功能

---

### 安裝流程

- 下載本專案
- 新增資料庫laravel
- 使用指令新增table `php artisan migrate`
- 執行本專案 `php artisan serve`
- 根據提示連到專案網站
- 註冊帳號
- 登入
- 完成安裝

---

### 備註

- 2023/5/26開始

---

### 版權宣告

- 本專案引用之任何圖檔、文字及任何媒體檔案皆用於個人研究用途
- 請勿用於任何商業行為
