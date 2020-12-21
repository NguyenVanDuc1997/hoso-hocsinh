<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/login', ['as' => 'front.home',   'uses' => 'Front\PagesController@getHome']);
Route::get('/hoso/login', ['as' => 'front.home',   'uses' => 'Auth\LoginController@login']);
Route::get('/autocomplete/login','Front\PagesController@autocomplete_login');
Route::post('/logins', ['as' => 'front.home.login',   'uses' => 'Auth\LoginController@login']);

//Route::get('/logout',['as' => 'front.logout',   'uses' => 'Front\PagesController@getHome']);
 //Route::get('/{category}', 'PagesController@myFunction');
// Route::get('/', function(){
// 	if(Auth::guest()){
// 		return view('admin');
// 	}else{
// 		return view('admin')->with('category', 'sss');
// 	}
	
// });

Route::group(['namespace' => 'Admin', 'prefix' => '', 'middleware' => 'auth'], function()
{
    Route::get('/', ['as' => 'admin.dashboard', 'uses' => 'PagesController@getDashboard']);
    Route::get('/home', ['as' => 'admin.dashboard', 'uses' => 'PagesController@Dashboard']);
	Route::get('/{category}', ['as' => 'admin.blank', 'uses' => 'PagesController@getBlank']);


    Route::get('quan-ly-ho-so/{url}', 'PagesController@getPage');


 //    Route::get('quan-ly-ho-so/welcome', function () {
 //        return view('welcome',['category' => 'Phần mềm quản lý hồ sơ']);
 //    });
	// Route::get('quan-ly-ho-so/bao-cao', function () {
	// 	return view('admin.baocao.listing',['category' => 'Hệ thống']);
	// });
	// Route::get('quan-ly-ho-so/ho-so-chinh-sach', function () {
	// 	return view('admin.hoso.listing',['category' => 'Hồ sơ chinh sách']);
	// });
	// Route::get('quan-ly-ho-so/kinh-phi-ho-tro', function () {
	// 	return view('admin.kinhphi.listing',['category' => 'Quản lý kinh phí hỗ trợ']);
	// });
	// Route::get('quan-ly-ho-so/danh-muc', function () {
	// 	return view('admin.danhmuc.listing',['category' => 'Quản lý danh mục']);
	// });
	// Route::get('quan-ly-ho-so/he-thong', function () {
	// 	return view('admin.hethong.listing',['category' => 'Hệ thống']);
	// });
    Route::post('load-message', ['as' => 'admin.blank', 'uses' => 'HethongController@message']);
	Route::get('change-pass/{user}', ['as' => 'admin.blank', 'uses' => 'HethongController@changePasswordUser']);
    Route::post('change-pass/update', ['as' => 'admin.blank', 'uses' => 'HethongController@changeProfile']);
	Route::post('change-pass/pass', ['as' => 'admin.blank', 'uses' => 'HethongController@changePass']);



	Route::group(['prefix'=>'danh-muc'],function (){
        Route::post('kiem-tra-chu-ho','DanhmucController@getChuHo');
        Route::get('load/doi-tuong','DanhmucController@listDoituongByNameAndId');
        Route::get('load/doi-tuong/{id}','DanhmucController@listDoituongById');
        Route::get('load/nhom-doi-tuong','DanhmucController@listNhomDoituongByNameAndId');
        Route::get('load/truong-hoc','DanhmucController@listTruongHocByNameAndId');
        Route::get('load/lop/{id}','DanhmucController@listLopByNameAndId');
        Route::get('load/class/{school}/{value}','DanhmucController@loadClassByClass');
        Route::get('load/city/{id}','DanhmucController@listCityByNameAndId');
        Route::get('load/citys','DanhmucController@listCityByNameAndIds');
        Route::get('load/nam-hoc','DanhmucController@listNamHoc');
        Route::get('load/dan-toc','DanhmucController@listDanTocByNameAndId');
        Route::get('permission','DanhmucController@getPermission');
        Route::get('exportExcel/{formName}', 'DanhmucController@exportExcel');
        //Quản lý người nấu ăn
        Route::group(['prefix'=>'nguoinauan'],function (){
            Route::get('list',['as'=>'admin.danhmuc.nguoinauan.listing','uses'=>'DanhmucController@listGetNGNA']);
            Route::post('loadDataUpdateInsert',['as'=>'admin.danhmuc.nguoinauan.loadDataUpdateInsert','uses'=>'DanhmucController@loadDataUpdateInsert']);
            Route::post('loadNguoinauan',['as'=>'admin.danhmuc.nguoinauan.loadNguoinauan','uses'=>'DanhmucController@loadNguoinauan']);
            Route::post('getbyngnaid',['as'=>'admin.danhmuc.nguoinauan.getbyngnaid','uses'=>'DanhmucController@getNgnabyID']);
            Route::post('insert',['as'=>'admin.danhmuc.nguoinauan.insert','uses'=>'DanhmucController@insertNGNA']);
            Route::post('update',['as'=>'admin.danhmuc.nguoinauan.update','uses'=>'DanhmucController@insertNGNA']);
            Route::post('delete',['as'=>'admin.danhmuc.nguoinauan.delete','uses'=>'DanhmucController@deleteNGNA']);
        });

        //Quản lý danh sách hộ nghèo
        Route::group(['prefix'=>'danhsachhongheo'],function (){
            Route::get('list',['as'=>'admin.danhmuc.danhsachhongheo.listing','uses'=>'DanhmucController@listingDShongheo']);
            Route::post('loadDanhsachhongheo',['as'=>'admin.danhmuc.danhsachhongheo.loadDanhsachhongheo','uses'=>'DanhmucController@loadDanhsachhongheo']);
            Route::get('getSite',['as'=>'admin.danhmuc.danhsachhongheo.getSite','uses'=>'DanhmucController@loadDataSite']);
            Route::get('getSiteByID/{id}',['as'=>'admin.danhmuc.danhsachhongheo.getSiteByID','uses'=>'DanhmucController@loadDataSiteByID']);
            Route::post('getbydshnid',['as'=>'admin.danhmuc.danhsachhongheo.getbydshnid','uses'=>'DanhmucController@getDSHNbyID']);
            Route::post('insert',['as'=>'admin.danhmuc.danhsachhongheo.insert','uses'=>'DanhmucController@insertDSHN']);
            Route::post('update',['as'=>'admin.danhmuc.danhsachhongheo.update','uses'=>'DanhmucController@updateDSHN']);
            Route::post('delete',['as'=>'admin.danhmuc.danhsachhongheo.delete','uses'=>'DanhmucController@deleteDSHN']);
        });

        //Loại trường
        Route::group(['prefix'=>'loaitruong'],function (){
            Route::get('list',['as'=>'admin.danhmuc.loaitruong.listing','uses'=>'DanhmucController@viewLoaitruong']);
            Route::post('loadLoaiTruong',['as'=>'admin.danhmuc.loaitruong.loadLoaiTruong','uses'=>'DanhmucController@loadLoaiTruong']);
            Route::post('getbytypeid',['as'=>'admin.danhmuc.loaitruong.getbytypeid','uses'=>'DanhmucController@getbytypeid']);
            Route::post('insert',['as'=>'admin.danhmuc.loaitruong.insert','uses'=>'DanhmucController@insertLoaitruong']);
            Route::post('update',['as'=>'admin.danhmuc.loaitruong.update','uses'=>'DanhmucController@updateLoaitruong']);
            Route::post('delete',['as'=>'admin.danhmuc.loaitruong.delete','uses'=>'DanhmucController@deleteLoaitruong']);

        });

        //Khối lớp
        Route::group(['prefix'=>'khoilop'],function (){
            Route::get('list',['as'=>'admin.danhmuc.khoilop.listing','uses'=>'DanhmucController@viewKhoilop']);
            Route::post('loadKhoilop',['as'=>'admin.danhmuc.khoilop.loadKhoilop','uses'=>'DanhmucController@loadKhoilop']);
            Route::post('getbyKhoilopid',['as'=>'admin.danhmuc.khoilop.getbyKhoilopid','uses'=>'DanhmucController@getbyKhoilopid']);
            Route::post('insert',['as'=>'admin.danhmuc.khoilop.insert','uses'=>'DanhmucController@insertKhoilop']);
            Route::post('update',['as'=>'admin.danhmuc.khoilop.update','uses'=>'DanhmucController@updateKhoilop']);
            Route::post('delete',['as'=>'admin.danhmuc.khoilop.delete','uses'=>'DanhmucController@deleteKhoilop']);

        });

		Route::group(['prefix'=>'khoi'],function (){
            Route::get('list',['as'=>'admin.danhmuc.khoi.listing','uses'=>'DanhmucController@listGetKhoi']);
            Route::get('exportExcelUnit',['as'=>'admin.danhmuc.khoi.exportExcelUnit','uses'=>'DanhmucController@exportExcelUnit']);
            Route::post('loadKhoi',['as'=>'admin.danhmuc.khoi.loadKhoi','uses'=>'DanhmucController@loadKhoi']);
            Route::post('getbyunitid',['as'=>'admin.danhmuc.khoi.getbyunitid','uses'=>'DanhmucController@getUnitbyID']);
            Route::post('insert',['as'=>'admin.danhmuc.khoi.insert','uses'=>'DanhmucController@insertKhoi']);
            Route::post('update',['as'=>'admin.danhmuc.khoi.update','uses'=>'DanhmucController@updateKhoi']);
            Route::post('delete',['as'=>'admin.danhmuc.khoi.delete','uses'=>'DanhmucController@deleteKhoi']);

        });
        Route::group(['prefix'=>'truong'],function (){
            Route::get('list',['as'=>'admin.danhmuc.truong.listing','uses'=>'DanhmucController@listGetTruong']);
            Route::get('exportExcelSchool',['as'=>'admin.danhmuc.truong.exportExcelSchool','uses'=>'DanhmucController@exportExcelSchool']);
            Route::post('loadTruong',['as'=>'admin.danhmuc.truong.loadTruong','uses'=>'DanhmucController@loadTruong']);
            Route::get('loadLoaitruong',['as'=>'admin.danhmuc.truong.loadLoaitruong','uses'=>'DanhmucController@loadSchoolType']);
            Route::post('getbyschoolid',['as'=>'admin.danhmuc.truong.getbyschoolid','uses'=>'DanhmucController@getSchoolbyID']);
            Route::post('insert',['as'=>'admin.danhmuc.truong.insert','uses'=>'DanhmucController@insertTruong']);
            Route::post('update',['as'=>'admin.danhmuc.truong.update','uses'=>'DanhmucController@updateTruong']);
            Route::post('delete',['as'=>'admin.danhmuc.truong.delete','uses'=>'DanhmucController@deleteTruong']);
        });
        Route::group(['prefix'=>'lop'],function (){
            Route::get('list',['as'=>'admin.danhmuc.lop.listing','uses'=>'DanhmucController@listGetLop']);
            Route::get('exportExcelClass',['as'=>'admin.danhmuc.lop.exportExcelClass','uses'=>'DanhmucController@exportExcelClass']);
            Route::post('loadLop',['as'=>'admin.danhmuc.lop.loadLop','uses'=>'DanhmucController@loadLop']);
            Route::post('getbyclassid',['as'=>'admin.danhmuc.lop.getbyclassid','uses'=>'DanhmucController@getClassbyID']);
            Route::post('getUnitAll',['as'=>'admin.danhmuc.lop.getUnitAll','uses'=>'DanhmucController@getUnit']);
            Route::post('getLevelbyUnitID',['as'=>'admin.danhmuc.lop.getLevelbyUnitID','uses'=>'DanhmucController@getLevelbyUnitID']);
            Route::post('insert',['as'=>'admin.danhmuc.lop.insert','uses'=>'DanhmucController@insertLop']);
            Route::post('update',['as'=>'admin.danhmuc.lop.update','uses'=>'DanhmucController@updateLop']);
            Route::post('delete',['as'=>'admin.danhmuc.lop.delete','uses'=>'DanhmucController@deleteLop']);
        });
        Route::group(['prefix'=>'dantoc'],function (){
            Route::get('list',['as'=>'admin.danhmuc.dantoc.listing','uses'=>'DanhmucController@listGetDantoc']);
            Route::get('exportExcelNation',['as'=>'admin.danhmuc.dantoc.exportExcelNation','uses'=>'DanhmucController@exportExcelNation']);
            Route::post('loadDantoc',['as'=>'admin.danhmuc.dantoc.loadDantoc','uses'=>'DanhmucController@loadDantoc']);
            Route::post('getbynationid',['as'=>'admin.danhmuc.dantoc.getbynationid','uses'=>'DanhmucController@getNationalbyID']);
            Route::post('insert',['as'=>'admin.danhmuc.dantoc.insert','uses'=>'DanhmucController@insertDantoc']);
            Route::post('update',['as'=>'admin.danhmuc.dantoc.update','uses'=>'DanhmucController@updateDantoc']);
            Route::post('delete',['as'=>'admin.danhmuc.dantoc.delete','uses'=>'DanhmucController@deleteDantoc']);
        });
        Route::group(['prefix'=>'nhomdoituong'],function (){
            Route::get('list',['as'=>'admin.danhmuc.nhomdoituong.listing','uses'=>'DanhmucController@listGetGroupDoiTuong']);
            Route::get('exportExcelGroup',['as'=>'admin.danhmuc.nhomdoituong.exportExcelGroup','uses'=>'DanhmucController@exportExcelGroup']);
            Route::post('loadNhomDoiTuong',['as'=>'admin.danhmuc.nhomdoituong.loadNhomDoiTuong','uses'=>'DanhmucController@loadNhomDoiTuong']);
            Route::post('getbygroupid',['as'=>'admin.danhmuc.nhomdoituong.getbygroupid','uses'=>'DanhmucController@getGroupbyID']);
            Route::post('insert',['as'=>'admin.danhmuc.nhomdoituong.insert','uses'=>'DanhmucController@insertGroupDoiTuong']);
            Route::post('update',['as'=>'admin.danhmuc.nhomdoituong.update','uses'=>'DanhmucController@updateGroupDoiTuong']);
            Route::post('delete',['as'=>'admin.danhmuc.nhomdoituong.delete','uses'=>'DanhmucController@deleteGroupDoiTuong']);
        });
        Route::group(['prefix'=>'doituong'],function (){
            Route::get('list',['as'=>'admin.danhmuc.doituong.listing','uses'=>'DanhmucController@listGetDoiTuong']);
            Route::get('exportExcelSubject',['as'=>'admin.danhmuc.doituong.exportExcelSubject','uses'=>'DanhmucController@exportExcelSubject']);
            Route::post('loadDoiTuong',['as'=>'admin.danhmuc.doituong.loadDoiTuong','uses'=>'DanhmucController@loadDoiTuong']);
            Route::post('getbysubjectid',['as'=>'admin.danhmuc.doituong.getbysubjectid','uses'=>'DanhmucController@getSubjectbyID']);
            Route::post('getlistgroupbysubjectid',['as'=>'admin.danhmuc.doituong.getlistgroupbysubjectid','uses'=>'DanhmucController@getListGroupIDbySubID']);
            Route::post('insert',['as'=>'admin.danhmuc.doituong.insert','uses'=>'DanhmucController@insertDoiTuong']);
            Route::post('update',['as'=>'admin.danhmuc.doituong.update','uses'=>'DanhmucController@updateDoiTuong']);
            Route::post('delete',['as'=>'admin.danhmuc.doituong.delete','uses'=>'DanhmucController@deleteDoiTuong']);
        });
        Route::group(['prefix'=>'phanloaixa'],function (){
            Route::get('list',['as'=>'admin.danhmuc.phanloaixa.listing','uses'=>'DanhmucController@listGetPLXa']);
            Route::get('loadcomboPLXa',['as'=>'admin.danhmuc.phanloaixa.loadcomboPLXa','uses'=>'DanhmucController@loadcomboWard']);
            Route::post('getWardbyID',['as'=>'admin.danhmuc.phanloaixa.getWardbyID','uses'=>'DanhmucController@getWardbyID']);
            Route::post('insert',['as'=>'admin.danhmuc.phanloaixa.insert','uses'=>'DanhmucController@insertPLXa']);
            Route::post('update',['as'=>'admin.danhmuc.phanloaixa.update','uses'=>'DanhmucController@updatePLXa']);
            Route::post('delete',['as'=>'admin.danhmuc.phanloaixa.delete','uses'=>'DanhmucController@deletePLXa']);
            Route::get('deptwards', function () {
        
                $data = DB::select('select * from qlhs_wards where wards_parent_id = 0', array());

                $results = array(
                    'id' => '',
                    'text' => '',
                    'children' => true,
                    'type' => 'root'
                );

                $jsonData = array();

                // Biến lưu kết quả trả về
                foreach ($data as $value) {
                    $results['id'] = $value->wards_id;
                    $results['text'] = $value->wards_name;

                    array_push($jsonData, $results);
                }

                return response()->json($jsonData);
            });

            Route::get('childwards', function () {

                $input = Request::get("id");

                $arrData;

                $jsonData = array();
                
                $results = DB::select("select wards_id as id, wards_name as text from qlhs_wards where wards_parent_id = ?", array($input));

                if (!empty($results)) {
                    $arrData = array(
                        'id' => '',
                        'text' => '',
                        'children' => true,
                        'type' => 'root'
                    );
                }
                foreach ($results as $value) {
                    $arrData['id'] = $value->id;
                    $arrData['text'] = $value->text;

                    array_push($jsonData, $arrData);
                }

                return response()->json($jsonData);
            });
        });
        Route::group(['prefix'=>'xaphuong'],function (){
            Route::get('list',['as'=>'admin.danhmuc.xaphuong.listing','uses'=>'DanhmucController@listGetXaPhuong']);
            Route::get('loadcomboXaPhuong/{level}',['as'=>'admin.danhmuc.xaphuong.loadcomboXaPhuong','uses'=>'DanhmucController@loadcomboxaphuong']);
            Route::post('loadXaPhuongbyLevel',['as'=>'admin.danhmuc.xaphuong.loadXaPhuongbyLevel','uses'=>'DanhmucController@getSitebyLevel']);
            Route::post('getXaPhuongbyID',['as'=>'admin.danhmuc.xaphuong.getXaPhuongbyID','uses'=>'DanhmucController@getSitebyID']);
            Route::post('insert',['as'=>'admin.danhmuc.xaphuong.insert','uses'=>'DanhmucController@insertXaPhuong']);
            Route::post('update',['as'=>'admin.danhmuc.xaphuong.update','uses'=>'DanhmucController@updateXaPhuong']);
            Route::post('delete',['as'=>'admin.danhmuc.xaphuong.delete','uses'=>'DanhmucController@deleteXaPhuong']);
            Route::get('deptsites', function () {
        
                $data = DB::select('select * from qlhs_site where site_parent_id = 0', array());

                $results = array(
                    'id' => '',
                    'text' => '',
                    'children' => true,
                    'type' => 'root'
                );

                $jsonData = array();

                // Biến lưu kết quả trả về
                foreach ($data as $value) {
                    $results['id'] = $value->site_id;
                    $results['text'] = $value->site_name;

                    array_push($jsonData, $results);
                }

                return response()->json($jsonData);
            });

            Route::get('childsites', function () {

                $input = Request::get("id");

                $arrData;

                $jsonData = array();
                
                $results = DB::select("select site_id as id, site_name as text from qlhs_site where site_parent_id = ?", array($input));

                if (!empty($results)) {
                    $arrData = array(
                        'id' => '',
                        'text' => '',
                        'children' => true,
                        'type' => 'root'
                    );
                }
                foreach ($results as $value) {
                    $arrData['id'] = $value->id;
                    $arrData['text'] = $value->text;

                    array_push($jsonData, $arrData);
                }

                return response()->json($jsonData);
            });
        });
        // Route::group(['prefix'=>'phongban'],function (){
        //     Route::get('list',['as'=>'admin.danhmuc.phongban.listing','uses'=>'DanhmucController@listGetPhongBan']);
        //     Route::get('loadcomboDepartment',['as'=>'admin.danhmuc.phongban.loadcomboDepartment','uses'=>'DanhmucController@loadcomboDepartment']);
        //     Route::post('getDepartmentbyID',['as'=>'admin.danhmuc.phongban.getDepartmentbyID','uses'=>'DanhmucController@getDepartmentbyID']);
        //     Route::post('insert',['as'=>'admin.danhmuc.phongban.insert','uses'=>'DanhmucController@insertPhongBan']);
        //     Route::post('update',['as'=>'admin.danhmuc.phongban.update','uses'=>'DanhmucController@updatePhongBan']);
        //     Route::post('delete',['as'=>'admin.danhmuc.phongban.delete','uses'=>'DanhmucController@deletePhongBan']);
        //     Route::get('/deptdepartment', function () {
    
        //         $data = DB::select('select * from qlhs_department where department_id = 1', array());

        //         $results = array(
        //             'id' => '',
        //             'text' => '',
        //             'children' => true,
        //             'type' => 'root'
        //         );
        //         // Biến lưu kết quả trả về
        //         foreach ($data as $value) {
        //             $results['id'] = $value->department_id;
        //             $results['text'] = $value->department_name;
        //         }

        //         return response()->json($results);
        //     });
        //     Route::get('/childdepartment', function () {

        //         $input = Request::get("id");

        //         $arrData;

        //         $jsonData = array();
                
        //         $results = DB::select("select department_id as id, department_name as text from qlhs_department where department_parent_id = ?", array($input));

        //         if (!empty($results)) {
        //             $arrData = array(
        //                 'id' => '',
        //                 'text' => '',
        //                 'children' => true,
        //                 'type' => 'root'
        //             );
        //         }
        //         foreach ($results as $value) {
        //             $arrData['id'] = $value->id;
        //             $arrData['text'] = $value->text;

        //             array_push($jsonData, $arrData);
        //         }

        //         return response()->json($jsonData);
        //     });
        // });

        Route::get('add',['as'=>'admin.danhmuc.addCategory','uses'=>'PagesController@addGet']);
        Route::get('list',['as'=>'admin.danhmuc.listCategory','uses'=>'PagesController@listGet']);

        Route::get('getAllDepartment', function () {
            // Biến lưu kết quả trả về
            $results = DB::table('qlhs_department')->get();

            //return response()->json($results);
            //die (json_encode($results));
            return view('admin.danhmuc.listCategory')->with('departments', $results);
        });
        Route::get('loadcomboDepartment',['as'=>'admin.danhmuc.phongban.loadcomboDepartment','uses'=>'DanhmucController@loadcomboDepartment']);
        Route::post('getDepartmentbyID',['as'=>'admin.danhmuc.phongban.getDepartmentbyID','uses'=>'DanhmucController@getDepartmentbyID']);
        Route::post('insert',['as'=>'admin.danhmuc.phongban.insert','uses'=>'DanhmucController@insertPhongBan']);
        Route::post('update',['as'=>'admin.danhmuc.phongban.update','uses'=>'DanhmucController@updatePhongBan']);
        Route::post('delete',['as'=>'admin.danhmuc.phongban.delete','uses'=>'DanhmucController@deletePhongBan']);
        Route::get('choosedepartment/{jsonData}', function ($jsonData) {
            $my_array_data = json_decode($jsonData, TRUE);
                //echo "<pre>";
                //print_r($my_array_data);

                $currentid = '';

                foreach ($my_array_data as $key => $value) {
                    switch ($key) {
                        case 'CURRENTID':
                            $currentid = $value;
                            break;
                        
                        default:
                            # code...
                            break;
                    }
                }
                //var_dump($currentid) or die();

                $results = DB::select('select * from qlhs_department where department_id = ?', array($currentid));
                
                return json_encode($results);
        });
        Route::get('/deptdepartment', function () {
    
            $data = DB::select('select * from qlhs_department where department_parent_id = 0', array());

            $results = array(
                'id' => '',
                'text' => '',
                'children' => true,
                'type' => 'root'
            );

            $jsonData = array();
            // Biến lưu kết quả trả về
            foreach ($data as $value) {
                $results['id'] = $value->department_id;
                $results['text'] = $value->department_name;

                array_push($jsonData, $results);
            }

            return response()->json($jsonData);
        });
        Route::get('/childdepartment', function () {

            $input = Request::get("id");

            $arrData;

            $jsonData = array();
            
            $results = DB::select("select department_id as id, department_name as text from qlhs_department where department_parent_id = ?", array($input));

            if (!empty($results)) {
                $arrData = array(
                    'id' => '',
                    'text' => '',
                    'children' => true,
                    'type' => 'root'
                );
            }
            foreach ($results as $value) {
                $arrData['id'] = $value->id;
                $arrData['text'] = $value->text;

                array_push($jsonData, $arrData);
            }

            return response()->json($jsonData);
        });

    });

    Route::group(['prefix'=>'du-toan-chi-tra'],function (){
        //Quản lý dự toán
        Route::group(['prefix'=>'dutoan'],function (){
            Route::get('list',['as'=>'admin.dutoanchitra.dutoan.dutoan','uses'=>'DuToanController@viewLapdutoan']);
            Route::get('load','DuToanController@statistic');
            Route::get('loadngna','DuToanController@statisticNGNA');
            // Route::post('loadNguoinauan',['as'=>'admin.dutoanchitra.dutoan.loadNguoinauan','uses'=>'LapHoSo9Controller@loadNguoinauan']);
            // Route::post('getbyngnaid',['as'=>'admin.dutoanchitra.dutoan.getbyngnaid','uses'=>'LapHoSo9Controller@getNgnabyID']);
            // Route::post('insert',['as'=>'admin.dutoanchitra.dutoan.insert','uses'=>'LapHoSo9Controller@insertNGNA']);
            // Route::post('update',['as'=>'admin.dutoanchitra.dutoan.update','uses'=>'LapHoSo9Controller@updateNGNA']);
            // Route::post('delete',['as'=>'admin.dutoanchitra.dutoan.delete','uses'=>'LapHoSo9Controller@deleteNGNA']);
        });
        Route::group(['prefix'=>'chi-tra'],function (){
            Route::get('list',['as'=>'admin.dutoanchitra.dutoan.chitra','uses'=>'DuToanController@viewChiTra']);
            Route::get('load','DuToanController@statistic');
            Route::post('cong-van','DuToanController@getDataCVDLBySchool');
            Route::post('load_data','DuToanController@load_data_chi_tra');
            Route::post('load_data_ct','DuToanController@load_data_done');

            // Route::post('loadNguoinauan',['as'=>'admin.dutoanchitra.dutoan.loadNguoinauan','uses'=>'LapHoSo9Controller@loadNguoinauan']);
            // Route::post('getbyngnaid',['as'=>'admin.dutoanchitra.dutoan.getbyngnaid','uses'=>'LapHoSo9Controller@getNgnabyID']);
            // Route::post('insert',['as'=>'admin.dutoanchitra.dutoan.insert','uses'=>'LapHoSo9Controller@insertNGNA']);
            // Route::post('update',['as'=>'admin.dutoanchitra.dutoan.update','uses'=>'LapHoSo9Controller@updateNGNA']);
            // Route::post('delete',['as'=>'admin.dutoanchitra.dutoan.delete','uses'=>'LapHoSo9Controller@deleteNGNA']);
        });
        
    });
//
   // Route::post('testlaravel/test', function()
   //  {
   //   $roleName = Input::get('roleName');
   //   //$roleName = $request->input('roleName');
   //      return $roleName;
   //  });
    Route::post('testlaravel/test','HethongController@updateGroupRole');
    Route::group(['prefix'=>'he-thong'],function (){
    	Route::group(['prefix'=>'role'],function (){
			//Route::get('list',['as'=>'admin.hethong.group.listRoleGroup','uses'=>'HethongController@loadListRole']);
            Route::post('load',['as'=>'admin.hethong.group.listRoleGroup','uses'=>'HethongController@loadListRole']);
            Route::post('save',['as'=>'admin.hethong.group.listRoleGroup','uses'=>'HethongController@updateGroupRole']);
            Route::post('insert',['as'=>'admin.hethong.group.listRoleGroup','uses'=>'HethongController@insertGroupRole']);
            Route::get('edit/{id}',['as'=>'admin.hethong.group.listRoleGroup','uses'=>'HethongController@getgroupbyid']);
            Route::get('delete/{id}',['as'=>'admin.hethong.group.listRoleGroup','uses'=>'HethongController@delGroupRole']);
            Route::get('nhom-quyen',['as'=>'admin.hethong.group.listRoleGroup','uses'=>'HethongController@getRoleGroup']);
    	});
        Route::group(['prefix'=>'permission'],function (){
            Route::get('list','PagesController@phanquyennguoidung');

        });
    	Route::group(['prefix'=>'group'],function (){
			Route::get('list',['as'=>'admin.hethong.group.listRoleGroup','uses'=>'HethongController@getGroupList']);
			Route::post('config','HethongController@configGroupRole');
			Route::get('getrole/{id}','HethongController@getconfigGroupRole');


    	});
    	Route::group(['prefix'=>'user'],function (){
			Route::get('list',['as'=>'admin.hethong.user.listUsers','uses'=>'HethongController@getUserList']);
			Route::get('info/{id}',['as'=>'layouts.change-password','uses'=>'HethongController@getUserInfo']);
            Route::post('load',['as'=>'admin.hethong.user.listUsers','uses'=>'HethongController@loadAllUser']);
            Route::get('getrole/{id}','HethongController@getconfigUserRole');
            Route::post('config','HethongController@configUserRole');
            Route::get('edit/{id}',['as'=>'admin.hethong.group.listRoleGroup','uses'=>'HethongController@getuserbyid']);

            Route::get('lock/{id}',['as'=>'admin.hethong.group.listRoleGroup','uses'=>'HethongController@lockuser']);
            Route::get('unlock/{id}',['as'=>'admin.hethong.group.listRoleGroup','uses'=>'HethongController@unlockuser']);
            Route::post('insert','HethongController@insertUser');
            Route::post('update','HethongController@updateUser');
            Route::get('delete/{id}','HethongController@deleteUser');
    	});
    });
    Route::group(['prefix'=>'bao-cao'],function (){
		Route::get('list',['as'=>'admin.baocao.listing','uses'=>'BaocaoController@getReport']);
        Route::get('thong-ke',['as'=>'admin.hoso.baocao.thongke','uses'=>'BaocaoController@viewThongKeHocSinh']);
		Route::get('thong-ke-doi-tuong',['as'=>'admin.hoso.baocao.thongkedoituong','uses'=>'BaocaoController@viewThongKeDoiTuong']);
		Route::get('du-toan',['as'=>'admin.hoso.baocao.dutoan','uses'=>'BaocaoController@viewDuToan']);
		
        Route::get('cong-van',['as'=>'admin.hoso.baocao.thongke','uses'=>'BaocaoController@viewThongKeCongVan']);
        Route::post('cong-van-phong-da-lap','BaocaoController@getDataCVDLBySchool');

           // Route::get('list','HosoController@thamdinh');
        Route::post('post-statistic','BaocaoController@postThongKe');
        Route::post('statistic-by-school','BaocaoController@statisticBySchool');
		
		Route::post('statistic-by-subject','BaocaoController@statisticBySubject');// Báo cáo theo chế độ
        Route::post('statistic-by-subject-view','BaocaoController@statisticBySubjectView');// Báo cáo theo chế độ

        Route::post('statistic-by-history-subject','BaocaoController@statisticByHistorySubject');// Báo cáo theo đối tượng
        Route::post('statistic-by-history-subject-view','BaocaoController@statisticByHistorySubjectView');// Báo cáo theo đối tượng
		Route::post('du-toan-view','BaocaoController@BaoCaoDuToanView');// Báo cáo dự toán
		
        Route::post('statistic-by-site','BaocaoController@statisticBySite');
		

        Route::post('statistic-by-year','BaocaoController@statisticByYear');

        Route::post('loadprofile','BaocaoController@loadProfile');
        Route::get('exportProfile','BaocaoController@exportStatisticByBanTru');
    	Route::group(['prefix'=>'group'],function (){
			Route::get('list',['as'=>'admin.hethong.group.listRoleGroup','uses'=>'HethongController@getGroupList']);
			Route::post('config','HethongController@configGroupRole');
			Route::get('getrole/{id}','HethongController@getconfigGroupRole');

    	});
		Route::get('tong-hop',['as'=>'admin.hoso.baocao.tonghop','uses'=>'BaocaoController@viewTongHop']);
        Route::post('statistic-by-total','BaocaoController@statisticByTotal');// Báo cáo tổng hợp
        Route::post('statistic-by-total-view','BaocaoController@statisticByTotalView');// Báo cáo theo tong hop
		
   //  	Route::group(['prefix'=>'user'],function (){
			// Route::get('list',['as'=>'admin.hethong.user.listUsers','uses'=>'HethongController@getUserList']);
			// Route::get('info/{id}',['as'=>'layouts.change-password','uses'=>'HethongController@getUserInfo']);
   //          Route::post('load',['as'=>'admin.hethong.user.listUsers','uses'=>'HethongController@loadAllUser']);
   //  	});
    });

    // new
    Route::group(['prefix'=>'danh-sach'],function (){
        Route::get('danh-sach-de-nghi','HosoController@danhsachdenghi');
        Route::post('de-nghi','DanhSachController@loadDanhSachDeNghi');
        //getDataCVDLBySchool
    });
    Route::group(['prefix'=>'du-toan-chi-tra'],function (){
        Route::get('cap-kinh-phi','DuToanController@capkinhphi');
        Route::post('danh-sach-kinh-phi','DuToanController@danhsachkinhphi');
        Route::get('cap-nhat-kinh-phi','DuToanController@capnhatkinhphi');
        Route::post('cap-nhat-kinh-phi/insert','DuToanController@themmoicapkinhphi');
        Route::post('cap-nhat-chi-tra/insert','DuToanController@themmoichitra');
        Route::get('cap-nhat-chi-tra/update','DuToanController@capnhatchitra');
        Route::get('cap-nhat-kinh-phi/download','DuToanController@downloadQDChiTra');
    });


    Route::group(['prefix'=>'ho-so'],function (){
        


        Route::post('/updateSubject/profile','HosohocsinhController@updateByProfile');//
        //Cập nhập và thay đổi hộ khẩu
            Route::post('/getDatabyProfileid','HosohocsinhController@getDataByProfileID');
            Route::post('/updateSite/profile','HosohocsinhController@updateSiteByProfile');
            Route::post('/insertSiteProfile/profile','HosohocsinhController@insertSiteProfile');
            Route::get('/deleteSiteProfile','HosohocsinhController@deleteSiteProfile');

        Route::post('load-list-message','PagesController@loadMessage');
        Route::post('importHoSo','HosohocsinhController@importHoSo');
        Route::get('downloaddemo',['as' =>'exportdemo','uses' => 'HosohocsinhController@downloadDemo']);
        Route::get('downloadFile/{filename}',['as' =>'downloadimport','uses' => 'HosohocsinhController@downloadFileImport']);
        Route::get('listing-message','PagesController@listingMessage');
        Route::get('message/{limit}','PagesController@loadListMessage');
        Route::get('tham-dinh/taive/{id}','HosoController@download_attach');
        Route::get('tham-dinh/download/{id}','HosoController@download_file');

        Route::get('tham-dinh/downfileExcelpheduyet/{id}','HosoController@download_file_pheduyet');

		Route::get('duyet-danh-sach/list',['as'=>'admin.hoso.duyetdanhsach.listing','uses'=>'HosoController@getList']);
        
        Route::post('duyet-danh-sach/load/verify','HosoController@loadVerifyThamDinh');
        Route::get('duyet-danh-sach/viewPheDuyet/{id}','HosoController@getViewPheDuyet');
        Route::post('duyet-danh-sach/updatetpheduyet','HosoController@updatePheDuyet');
        Route::post('duyet-danh-sach/revertpheduyet','HosoController@revertPheDuyet');
        Route::group(['prefix'=>'tham-dinh'],function (){
            Route::post('load/verify','HosoController@loadDaPheDuyet');
            Route::post('ho-so-duyet/insert','HosoController@insertDataTotal');
            Route::get('permission/info','HosoController@getPermissionThamdinh');
            Route::post('sendtotal','HosoController@sendPheDuyet');
            Route::post('loadtotal','HosoController@loadData');
            Route::get('loadUsertotal','HosoController@loadAllUserTotal');
            Route::get('downloadFileExcel/{id}','HosoController@download_ExcelExport');
            Route::get('deletePheduyettonghop/{id}','HosoController@delete_Report');
            Route::get('list','HosoController@thamdinh');
            Route::get('inbox','HosoController@vanbanthamdinh');
            Route::get('listing','HosoController@tonghopthamdinh');
            Route::get('view/{id}','HosoController@getViewThamdinh');
            Route::post('load','HosoController@loadThamDinh');
            
            Route::post('send','HosoController@sendThamDinh');
            Route::post('resend','HosoController@resendThamDinh');

            //-------------------------------------------------------
            Route::get('loadInforRevert/{id}','HosoController@loadNoteAndFile_Pheduyet');            
            Route::get('download_file_revert_pheduyet/{id}','HosoController@download_file_Revert'); 
            Route::get('download_file_approved_pheduyet/{id}','HosoController@download_file_Approved');
        });
        Route::group(['prefix'=>'phe-duyet'],function (){
            Route::get('permission/info','HosoController@getPermissionPheDuyet');

            Route::get('pheduyethocsinh/{objData}','HosoController@approvedPheDuyet');
            Route::get('danh-sach-truong-de-nghi','HosoController@ViewPheDuyetNew');
            Route::get('danh-sach-truong-de-nghi/{school}/{congvan}','HosoController@getViewPheDuyetNew');
            Route::get('danh-sach-so-tra-lai','HosoController@getViewThamDinhNew');

           // Route::get('list','HosoController@thamdinh');
          //  Route::get('inbox','HosoController@vanbanthamdinh');
          //  Route::get('listing','HosoController@tonghopthamdinh');
           // Route::get('view/{id}','HosoController@getViewThamdinh');
            Route::post('load','HosoController@loadPheDuyet');
            Route::post('cong-van-cho-phe-duyet','HosoController@loadCongVanByTruongChoDuyet');
            Route::post('cong-van-da-phe-duyet','HosoController@loadCongVanByTruongDaDuyet');
            Route::post('cong-van-tra-lai','HosoController@loadCongVanTraLai');
            Route::post('cong-van','HosoController@loadCongVan');
          //  Route::post('send','HosoController@sendThamDinh');
          //  Route::post('resend','HosoController@resendThamDinh');
            Route::get('download_file_pheduyettonghop/{id}','HosoController@download_file_pheduyettonghop');
        });

        //Route::get('tham-dinh/list','HosoController@thamdinh');
        //Route::get('xem-tham-dinh/{id}','HosoController@getViewThamdinh');
        Route::post('duyet-danh-sach/load','HosoController@loadThamDinh');
        //Route::post('tham-dinh/send','HosoController@sendThamDinh');

    	Route::group(['prefix'=>'lap-danh-sach'],function (){
			Route::get('list',['as'=>'admin.hoso.lapdanhsach.listing','uses'=>'HosoController@getViewLapDS']);
            
            Route::get('permission/info',['as'=>'admin.hoso.lapdanhsach.miengiamhocphi','uses'=>'LapHoSoController@getPermission']);
            //send mien giam hoc phi
			Route::post('send','LapHoSoController@sendMGHP');
            Route::post('sendTonghop','TongHopHoSoController@sendDSTonghop');
            Route::post('deleteTonghop','TongHopHoSoController@deleteDSTonghop');
            // //send chi phi hoc tap
            // Route::get('chi-phi-hoc-tap/send/{id}',['as'=>'admin.hoso.lapdanhsach.miengiamhocphi','uses'=>'LapHoSoController@sendMGHP']);
            // //send ho tro an trua
            // Route::get('ho-tro-an-trua/send/{id}',['as'=>'admin.hoso.lapdanhsach.miengiamhocphi','uses'=>'LapHoSoController@sendMGHP']);
            // // send ho tro hoc sinh dan toc thieu so
            // Route::get('hoc-sinh-dan-toc-thieu-so/send/{id}',['as'=>'admin.hoso.lapdanhsach.miengiamhocphi','uses'=>'LapHoSoController@sendMGHP']);
            // //send hoc sinh ban tru
            // Route::get('hoc-sinh-ban-tru/send/{id}',['as'=>'admin.hoso.lapdanhsach.miengiamhocphi','uses'=>'LapHoSoController@sendMGHP']);
            // // send nguoi nau an
            // Route::get('nguoi-nau-an/send/{id}',['as'=>'admin.hoso.lapdanhsach.miengiamhocphi','uses'=>'LapHoSoController@sendMGHP']);
            // // send hoc sinh khuyet tat
            // Route::get('hoc-sinh-khuyet-tat/send/{id}',['as'=>'admin.hoso.lapdanhsach.miengiamhocphi','uses'=>'LapHoSoController@sendMGHP']);
            // // send chinh sach uu dai
            // Route::get('chinh-sach-uu-dai/send/{id}',['as'=>'admin.hoso.lapdanhsach.miengiamhocphi','uses'=>'LapHoSoController@sendMGHP']);
// miễn giảm học phí
            Route::get('mien-giam-hoc-phi',['as'=>'admin.hoso.lapdanhsach.miengiamhocphi','uses'=>'HosoController@getView1']);
            Route::get('mien-giam-hoc-phi/downloadfileExport/{id}',['as'=>'admin.hoso.lapdanhsach.miengiamhocphi','uses'=>'LapHoSoController@downloadfile_Export']);
            Route::get('mien-giam-hoc-phi/exportExcelAllSchool',['as'=>'admin.hoso.lapdanhsach.miengiamhocphi','uses'=>'LapHoSoController@exportExcelAllSchool']);
            Route::get('mien-giam-hoc-phi/download/{id}',['as'=>'admin.hoso.lapdanhsach.miengiamhocphi','uses'=>'LapHoSoController@download_attach']);
            Route::get('mien-giam-hoc-phi/delete/{id}',['as'=>'admin.hoso.lapdanhsach.miengiamhocphi','uses'=>'LapHoSoController@delete_report']);
            Route::post('mien-giam-hoc-phi/load',['as'=>'admin.hoso.lapdanhsach.miengiamhocphi','uses'=>'LapHoSoController@loadData']);
            Route::post('mien-giam-hoc-phi/insert',['as'=>'admin.hoso.lapdanhsach.miengiamhocphi','uses'=>'LapHoSoController@miengiamhocphi']);
//Chi phí học tập
            Route::get('chi-phi-hoc-tap',['as'=>'admin.hoso.lapdanhsach.chiphihoctap','uses'=>'HosoController@getView2']);
            Route::get('chi-phi-hoc-tap/downloadfileExport/{id}',['as'=>'admin.hoso.lapdanhsach.chiphihoctap','uses'=>'LapHoSo2Controller@downloadfile_Export']);
            Route::get('chi-phi-hoc-tap/download/{id}',['as'=>'admin.hoso.lapdanhsach.chiphihoctap','uses'=>'LapHoSo2Controller@download_attach']);
            Route::get('chi-phi-hoc-tap/delete/{id}',['as'=>'admin.hoso.lapdanhsach.chiphihoctap','uses'=>'LapHoSo2Controller@delete_report']);
            Route::post('chi-phi-hoc-tap/load',['as'=>'admin.hoso.lapdanhsach.chiphihoctap','uses'=>'LapHoSo2Controller@loadDataAll']);
            Route::post('chi-phi-hoc-tap/insert',['as'=>'admin.hoso.lapdanhsach.chiphihoctap','uses'=>'LapHoSo2Controller@chiphihoctap']);
//Chính sách ưu đãi            
            Route::get('chinh-sach-uu-dai',['as'=>'admin.hoso.lapdanhsach.chinhsachuudai','uses'=>'HosoController@getView3']);
            Route::get('chinh-sach-uu-dai/downloadfileExport/{id}',['as'=>'admin.hoso.lapdanhsach.chinhsachuudai','uses'=>'LapHoSo7Controller@downloadfile_Export']);
            Route::get('chinh-sach-uu-dai/download/{id}',['as'=>'admin.hoso.lapdanhsach.chinhsachuudai','uses'=>'LapHoSo7Controller@download_attach']);
            Route::get('chinh-sach-uu-dai/delete/{id}',['as'=>'admin.hoso.lapdanhsach.chinhsachuudai','uses'=>'LapHoSo7Controller@delete_report']);
            Route::post('chinh-sach-uu-dai/load',['as'=>'admin.hoso.lapdanhsach.chinhsachuudai','uses'=>'LapHoSo7Controller@loadDataTongHop']);
            Route::post('chinh-sach-uu-dai/getData',['as'=>'admin.hoso.lapdanhsach.chinhsachuudai','uses'=>'LapHoSo7Controller@getData']);
//Bán trú
            Route::get('hoc-sinh-ban-tru',['as'=>'admin.hoso.lapdanhsach.hocsinhbantru','uses'=>'HosoController@getView4']);
            Route::get('hoc-sinh-ban-tru/downloadfileExport/{id}',['as'=>'admin.hoso.lapdanhsach.hocsinhbantru','uses'=>'LapHoSo4Controller@downloadfile_Export']);
            Route::get('hoc-sinh-ban-tru/download/{id}',['as'=>'admin.hoso.lapdanhsach.hocsinhbantru','uses'=>'LapHoSo4Controller@download_attach']);
            Route::get('hoc-sinh-ban-tru/delete/{id}',['as'=>'admin.hoso.lapdanhsach.hocsinhbantru','uses'=>'LapHoSo4Controller@delete_report']);
            Route::post('hoc-sinh-ban-tru/load',['as'=>'admin.hoso.lapdanhsach.hocsinhbantru','uses'=>'LapHoSo4Controller@loadDataBanTru']);
            Route::post('hoc-sinh-ban-tru/getData',['as'=>'admin.hoso.lapdanhsach.hocsinhbantru','uses'=>'LapHoSo4Controller@getData']);
//Học sinh dân tộc thiểu số
            Route::get('hoc-sinh-dan-toc-thieu-so',['as'=>'admin.hoso.lapdanhsach.hocsinhdantocthieuso','uses'=>'HosoController@getView5']);
            Route::get('hoc-sinh-dan-toc-thieu-so/downloadfileExport/{id}',['as'=>'admin.hoso.lapdanhsach.hocsinhdantocthieuso','uses'=>'LapHoSo5Controller@downloadfile_Export']);
            Route::get('hoc-sinh-dan-toc-thieu-so/download/{id}',['as'=>'admin.hoso.lapdanhsach.hocsinhdantocthieuso','uses'=>'LapHoSo5Controller@download_attach']);
            Route::get('hoc-sinh-dan-toc-thieu-so/delete/{id}',['as'=>'admin.hoso.lapdanhsach.hocsinhdantocthieuso','uses'=>'LapHoSo5Controller@delete_report']);
            Route::post('hoc-sinh-dan-toc-thieu-so/load',['as'=>'admin.hoso.lapdanhsach.hocsinhdantocthieuso','uses'=>'LapHoSo5Controller@loadDataHSDTTS']);
            Route::post('hoc-sinh-dan-toc-thieu-so/getData',['as'=>'admin.hoso.lapdanhsach.hocsinhdantocthieuso','uses'=>'LapHoSo5Controller@getData']);
//Hỗ trợ học sinh khuyết tật
            Route::get('hoc-sinh-khuyet-tat',['as'=>'admin.hoso.lapdanhsach.hocsinhkhuyettat','uses'=>'HosoController@getView6']);
            Route::get('hoc-sinh-khuyet-tat/downloadfileExport/{id}',['as'=>'admin.hoso.lapdanhsach.hocsinhkhuyettat','uses'=>'LapHoSo6Controller@downloadfile_Export']);
            Route::get('hoc-sinh-khuyet-tat/download/{id}',['as'=>'admin.hoso.lapdanhsach.hocsinhkhuyettat','uses'=>'LapHoSo6Controller@download_attach']);
            Route::get('hoc-sinh-khuyet-tat/delete/{id}',['as'=>'admin.hoso.lapdanhsach.hocsinhkhuyettat','uses'=>'LapHoSo6Controller@delete_report']);
            Route::post('hoc-sinh-khuyet-tat/load',['as'=>'admin.hoso.lapdanhsach.hocsinhkhuyettat','uses'=>'LapHoSo6Controller@loadDataHSKT']);
            Route::post('hoc-sinh-khuyet-tat/getData',['as'=>'admin.hoso.lapdanhsach.hocsinhkhuyettat','uses'=>'LapHoSo6Controller@getData']);
//Hỗ trợ ăn trưa cho trẻ em
            Route::get('ho-tro-an-trua-tre-em',['as'=>'admin.hoso.lapdanhsach.hotroantruatreem','uses'=>'HosoController@getView7']);
            Route::get('ho-tro-an-trua-tre-em/downloadfileExport/{id}',['as'=>'admin.hoso.lapdanhsach.hotroantruatreem','uses'=>'LapHoSo3Controller@downloadfile_Export']);
            Route::get('ho-tro-an-trua-tre-em/download/{id}',['as'=>'admin.hoso.lapdanhsach.hotroantruatreem','uses'=>'LapHoSo3Controller@download_attach']);
            Route::get('ho-tro-an-trua-tre-em/delete/{id}',['as'=>'admin.hoso.lapdanhsach.hotroantruatreem','uses'=>'LapHoSo3Controller@delete_report']);
            Route::post('ho-tro-an-trua/load',['as'=>'admin.hoso.lapdanhsach.hotroantruatreem','uses'=>'LapHoSo3Controller@loadDataAll']);
            Route::post('ho-tro-an-trua/insert',['as'=>'admin.hoso.lapdanhsach.hotroantruatreem','uses'=>'LapHoSo3Controller@hoTroAnTruaTreEm']);
// Hỗ trợ người nấu ăn
            Route::get('nguoi-nau-an',['as'=>'admin.hoso.lapdanhsach.nguoinauan','uses'=>'HosoController@getView8']);
            Route::get('nguoi-nau-an/downloadfileExport/{id}',['as'=>'admin.hoso.lapdanhsach.nguoinauan','uses'=>'LapHoSo8Controller@downloadfile_Export']);
            Route::get('nguoi-nau-an/download/{id}',['as'=>'admin.hoso.lapdanhsach.nguoinauan','uses'=>'LapHoSo8Controller@download_attach']);
            Route::get('nguoi-nau-an/delete/{id}',['as'=>'admin.hoso.lapdanhsach.nguoinauan','uses'=>'LapHoSo8Controller@delete_report']);
            Route::post('nguoi-nau-an/load',['as'=>'admin.hoso.lapdanhsach.nguoinauan','uses'=>'LapHoSo8Controller@loadDataNGNA']);
            Route::post('nguoi-nau-an/getData',['as'=>'admin.hoso.lapdanhsach.nguoinauan','uses'=>'LapHoSo8Controller@getData']);

//Tổng hợp chế độ hỗ trợ
            Route::get('tong-hop-che-do-ho-tro',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'HosoController@getView9']);
            Route::post('tong-hop-che-do-ho-tro/load',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@loadData']);
            Route::post('lap-cong-van/load',['as'=>'admin.hoso.lapdanhsach.loaddatacongvan','uses'=>'XacNhanCongVan@loadCongVanLap']);

            Route::post('tong-hop-che-do-ho-tro/update',['as'=>'admin.hoso.lapdanhsach.capnhatchedohocsinh','uses'=>'XacNhanCongVan@updateData']);
            // thu hồi công văn
            Route::get('thuhoi_cv/{congvan}',['as'=>'admin.hoso.lapdanhsach.thuhoicongvan','uses'=>'LapHoSo9Controller@thuhoi_congvan']);
            // thu hồi công văn trả lại
            Route::get('huycongvan_tralai/{congvan}','LapHoSo9Controller@huycongvan_tralai');
            //Khóa dữ liệu
            Route::get('lock_cv/{congvan}',['as'=>'admin.hoso.lapdanhsach.thuhoicongvan','uses'=>'LapHoSo9Controller@khoa_congvan']);
            // đồng ý thu hồi
            Route::get('accept_congvan/{congvan}',['as'=>'admin.hoso.lapdanhsach.dongythuhoi_congvan','uses'=>'LapHoSo9Controller@dongythuhoi_congvan']);
            // không đồng ý thu hồi
            Route::get('cancel_congvan/{congvan}',['as'=>'admin.hoso.lapdanhsach.huythuhoi_congvan','uses'=>'LapHoSo9Controller@huythuhoi_congvan']);

            Route::post('load_congvan_tonghop','XacNhanCongVan@load_congvan_tonghop');
            Route::post('tong-hop-cong-van','XacNhanCongVan@TongHopCongVan');
            //
            Route::get('tong-hop-che-do-ho-tro/loadhocky',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@loadHocky']);
            Route::post('tong-hop-che-do-ho-tro/approved',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@approvedTHCD']);

            Route::post('tong-hop-che-do-ho-tro/xetduyetchedo',['as'=>'admin.hoso.lapdanhsach.xetduyetchedo','uses'=>'XacNhanCongVan@xetduyethocsinh']);

            Route::post('tong-hop-che-do-ho-tro/approvedNew',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@approvedTHCDNew']);
            Route::post('tong-hop-che-do-ho-tro/approvedPhongSo',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@approvedPhongSo']);
            Route::post('tong-hop-che-do-ho-tro/revertedPhongSo',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@revertedPhongSo']);
            Route::post('tong-hop-che-do-ho-tro/reloadPhongSo',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@reloadPhongSo']);
            Route::post('tong-hop-che-do-ho-tro/approvedAllPhongSo',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@approvedAllPhongSo']);
            Route::post('tong-hop-che-do-ho-tro/revertedAllPhongSo',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@revertedAllPhongSo']);
            Route::post('tong-hop-che-do-ho-tro/reloadAllPhongSo',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@reloadAllPhongSo']);
            Route::post('tong-hop-che-do-ho-tro/lapdanhsachPhongSo',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@lapdanhsach_PhongSo']);

            Route::post('tong-hop-che-do-ho-tro/approvedchedoPD',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@approvedchedoPD']);
            Route::post('tong-hop-che-do-ho-tro/revertchedoPD',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@revertchedoPD']);

            Route::get('tong-hop-che-do-ho-tro/revertApproved/{strData}',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@revertApprovedTHCD']);
            Route::get('tong-hop-che-do-ho-tro/getProfileSubById/{id}',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@getProfileSubjectById']);
            Route::get('tong-hop-che-do-ho-tro/getProfileSubjectByIdPhongSo/{objJson}',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@getProfileSubjectByIdPhongSo']);
            Route::post('tong-hop-che-do-ho-tro/lapdanhsach',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@lapdanhsach_Truong']);
            Route::post('tong-hop-che-do-ho-tro/loadDanhsachbaocao',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@loadDataBaocao']);
            Route::post('tong-hop-che-do-ho-tro/loadDanhSachChoPheDuyet',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@loadDanhSachChoPheDuyet']);
            Route::post('tong-hop-che-do-ho-tro/loadDanhSachDaPheDuyet',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@loadDanhSachDaPheDuyet']);
            Route::post('tong-hop-che-do-ho-tro/loadDanhSachTraLai',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@loadDanhSachTraLai']);
            Route::post('tong-hop-che-do-ho-tro/loadDanhSach',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@loadDanhSach']);

            Route::get('tong-hop-che-do-ho-tro/downloadfileExportHTATHS/{id}',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@downloadfile_ExportHTATHS']);
            
            Route::get('tong-hop-che-do-ho-tro/downloadfileExportHBHSDTNT/{id}',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@downloadfile_ExportHBHSDTNT']);
	// in danh sach công văn
            
            Route::post('/ds-de-nghi/{congvan}',['as'=>'admin.hoso.lapdanhsach.incongvan','uses'=>'LapHoSo9Controller@printCongVanPheDuyetView']);
            //Xem chi tiết công văn
            Route::get('danh-sach-de-nghi/{congvan}',['as'=>'admin.hoso.lapdanhsach.tonghopchedotheocongvan','uses'=>'LapHoSo9Controller@getViewApprovedPheDuyetTheoCV']);
            //Xem lọc công văn
            Route::get('loc-danh-sach/{type}-{congvan}',['as'=>'admin.hoso.lapdanhsach.loccongvan','uses'=>'LapHoSo9Controller@locCongVanTheoTruong']);

            Route::get('danh-sach-de-nghi',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@getViewApprovedPheDuyet']);

            
            // danh sach công văn
            Route::post('tong-hop-che-do-ho-tro/loadDanhSachByPhongSo',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@loadDanhSachByPhongSo']);
            Route::post('tong-hop-che-do-ho-tro/loadDanhSachHSDaLap',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@loadDanhSachHSDaLap']);
            Route::get('tong-hop-che-do-ho-tro/active_ngna',['as'=>'admin.hoso.lapdanhsach.active_ngna','uses'=>'LapHoSo9Controller@activeNGNA']);


            Route::post('tong-hop-che-do-ho-tro/loadListUnApprovedThamDinh',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@loadListUnApprovedThamDinh']);
            Route::get('tong-hop-che-do-ho-tro/approvedchedoTD/{objJson}',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@approvedchedoTD']);

            //Load danh sách đã lập
            Route::post('tong-hop-che-do-ho-tro/loadDataGroupA',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@loadDatabyGroupA']);
            Route::post('tong-hop-che-do-ho-tro/loadDataGroupB',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@loadDatabyGroupB']);
            Route::post('tong-hop-che-do-ho-tro/loadDataGroupC',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@loadDatabyGroupC']);

            //Thu hồi công văn đã lập
            Route::post('tong-hop-che-do-ho-tro/thuhoicongvanlap',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@thuhoicongvanlap']);

            //Thu hồi HỌC SINH
            Route::post('tong-hop-che-do-ho-tro/loadthuhoihocsinh',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@loadthuhoiHocSinh']);
            Route::post('tong-hop-che-do-ho-tro/thuhoihocsinh',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@thuhoiHocSinh']);
            Route::post('tong-hop-che-do-ho-tro/getLevelUser',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@getLevelUser']);

            //Duyệt toàn bộ học sinh
            Route::post('tong-hop-che-do-ho-tro/approvedAll',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@approvedAll']);
            Route::post('tong-hop-che-do-ho-tro/unApprovedAll',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@unApprovedAll']);

            Route::post('tong-hop-che-do-ho-tro/approvedAllCreate',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'XacNhanCongVan@approvedAllCreate']);
            Route::post('tong-hop-che-do-ho-tro/unApprovedAllCreate',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'XacNhanCongVan@unApprovedAllCreate']);

            //Phê duyệt và trả lại toàn bộ học sính
            Route::post('tong-hop-che-do-ho-tro/approvedAllPheDuyet',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@approvedAllPheDuyet']);
            Route::post('tong-hop-che-do-ho-tro/approvedAllDaPheDuyet',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@approvedAllDaPheDuyet']);
            Route::post('tong-hop-che-do-ho-tro/revertAllPheDuyet',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@revertsAllPheDuyet']);
            Route::post('tong-hop-che-do-ho-tro/revertAllDaPheDuyet',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@revertAllDaPheDuyet']);

            //Thẩm định toàn bộ học sính
            Route::post('tong-hop-che-do-ho-tro/approvedAllThamDinh',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@approvedAllThamDinh']);
            Route::post('tong-hop-che-do-ho-tro/unApprovedAllThamDinh',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSo9Controller@unApprovedAllThamDinh']);

            //Lập danh sách cấp Phòng
            Route::post('tong-hop-che-do-ho-tro/lapdanhsach_PD',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSoPhongController@lapdanhsachTHCD_pheduyet']);
            //Lập danh sách cấp sở
            Route::post('tong-hop-che-do-ho-tro/lapdanhsach_TD',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSoSoController@lapdanhsachTHCD_thamdinh']);

            //Insert Danh sách phòng trả lại
            Route::post('tong-hop-che-do-ho-tro/insertPhongtralai',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'LapHoSoPhongController@insertPhongtralai']);

            //Export danh sách trường đề nghị
            Route::get('tong-hop-che-do-ho-tro/exportExcel/{jsonData}',['as'=>'admin.hoso.hosohocsinh.exportExcel','uses'=>'LapHoSo9Controller@exportExcelTruongDeNghi']);

            //Update lại tiền cho học sinh
            Route::post('tong-hop-che-do-ho-tro/updatemoneynew',['as'=>'admin.hoso.hosohocsinh.updatemoneynew','uses'=>'LapHoSo9Controller@updateMoneyNew']);

//Tổng hợp hồ sơ
            Route::get('bao-cao',['as'=>'admin.hoso.lapdanhsach.tonghophoso','uses'=>'TongHopHoSoController@viewList']);
            

            Route::post('tong-hop-ho-so/load',['as'=>'admin.hoso.lapdanhsach.tonghopchedo','uses'=>'TongHopHoSoController@loadData']);

            //Get All User
            Route::get('nguoi-nau-an/getAllUser',['as'=>'admin.hoso.lapdanhsach.nguoinauan','uses'=>'LapHoSo8Controller@loadAllUser']);
            Route::get('nguoi-nau-an/getContent/{id}',['as'=>'admin.hoso.lapdanhsach.nguoinauan','uses'=>'LapHoSo8Controller@loadNoteAndFile']);
            Route::get('nguoi-nau-an/download_file_revert/{id}',['as'=>'admin.hoso.lapdanhsach.nguoinauan','uses'=>'LapHoSo8Controller@download_filerevert']);
    	});
        Route::group(['prefix'=>'hoc-sinh'],function (){
            // Xóa học sinh theo lop
            Route::post('delete-by-class','HosohocsinhController@delProfileByClass');
            Route::post('tong-hop-cong-van-da-lap','HosohocsinhController@getDataCVDL');
            Route::post('tong-hop-cong-van-da-lap-theo-truong','HosohocsinhController@getDetailCVDLBySchool');
            Route::post('tong-hop-cong-van-da-lap-theo-truong/upload','HosohocsinhController@uploadDetailCVDLBySchool');
            Route::get('tong-hop-cong-van-da-lap-theo-truong/delete','HosohocsinhController@deleteDetailCVDLBySchool');
            Route::get('tong-hop-cong-van-da-lap-theo-truong/download','HosohocsinhController@downloadDetailCVDLBySchool');
            Route::post('cong-van-phong-da-lap','HosohocsinhController@getDataCVDLBySchool');
            //
            Route::get('permission/{id}',['as'=>'admin.hoso.hosohocsinh.permission','uses'=>'HosohocsinhController@getPermission']);
            // danh sach hoc sinh
            Route::get('danh-sach',['as'=>'admin.hoso.hosohocsinh.danhsach','uses'=>'HosoController@viewlist']);
            Route::get('listing',['as'=>'admin.hoso.hosohocsinh.createHoSoHS','uses'=>'HosohocsinhController@viewCreateHS']);
            // cap nhat thay doi doi tuong
            Route::get('doi-tuong',['as'=>'admin.hoso.hosohocsinh.doituong','uses'=>'HosoController@changeSubject']);

            Route::get('change/{id}',['as'=>'admin.hoso.hosohocsinh.changeSubjectById','uses'=>'HosohocsinhController@getChangeSubject']);
            Route::get('cap-nhat-ho-khau',['as'=>'admin.hoso.hosohocsinh.updateRegistration','uses'=>'HosohocsinhController@updateSubject']);
            Route::get('cap-nhat-ho-khau/{id}',['as'=>'admin.hoso.hosohocsinh.getUpdateRegistration','uses'=>'HosohocsinhController@getUpdateSubject']);
            Route::get('subject/getByProfile/{time}/{id}',['as'=>'admin.hoso.hosohocsinh.getByProfile','uses'=>'HosohocsinhController@getByProfile']);
            Route::get('subject/delByProfile/{time}/{p_id}',['as'=>'admin.hoso.hosohocsinh.delByProfile','uses'=>'HosohocsinhController@delSubject']);
            Route::post('subject/updateByProfile',['as'=>'admin.hoso.hosohocsinh.updateByProfile','uses'=>'HosohocsinhController@updateByProfile']);//
            Route::post('/subject/insertByProfile/profile',['as'=>'admin.hoso.hosohocsinh.insertByProfile','uses'=>'HosohocsinhController@insertByProfile']);//insertByProfile
            // Load danh sach hoc sinh
            Route::post('subject/load',['as'=>'admin.hoso.hosohocsinh.changeLoad','uses'=>'HosohocsinhController@changeSubjectLoad']);
            Route::post('loadSiteHistory',['as'=>'admin.hoso.hosohocsinh.updateRegistration','uses'=>'HosohocsinhController@loadDataHisSite']);
            Route::post('subject/loadnew',['as'=>'admin.hoso.hosohocsinh.updateLoad','uses'=>'HosohocsinhController@updateSubjectLoad']);
            Route::get('exportExcel/{jsonData}',['as'=>'admin.hoso.hosohocsinh.exportExcel','uses'=>'HosohocsinhController@exportExcel']);
            Route::post('load',['as'=>'admin.hoso.hosohocsinh.listing','uses'=>'HosohocsinhController@load']);
            Route::post('getProfilePopupUpto',['as'=>'admin.hoso.hosohocsinh.getProfilePopupUpto','uses'=>'HosohocsinhController@getProfilePopupUpto']);
            Route::post('getYearHistory',['as'=>'admin.hoso.hosohocsinh.getYearHistory','uses'=>'HosohocsinhController@getYearHistory']);
            Route::post('getprofilebyid',['as'=>'admin.hoso.hosohocsinh.getprofilebyid','uses'=>'HosohocsinhController@getHoSoHocSinhbyID']);

            Route::get('getprofilebySite/{id}',['as'=>'admin.hoso.hosohocsinh.getprofilebySite','uses'=>'HosohocsinhController@getprofilebySite']);

            Route::post('insert',['as'=>'admin.hoso.hosohocsinh.insert','uses'=>'HosohocsinhController@insertHoSoHocSinh']);
            Route::post('update',['as'=>'admin.hoso.hosohocsinh.update','uses'=>'HosohocsinhController@updateHoSoHocSinh']);
            Route::get('download_quyetdinh/{id}',['as'=>'admin.hoso.hosohocsinh.update','uses'=>'HosohocsinhController@download_quyetdinh']);
            Route::post('delete',['as'=>'admin.hoso.hosohocsinh.delete','uses'=>'HosohocsinhController@deleteHoSoHocSinh']);
            Route::get('viewhistory/{id}',['as'=>'admin.hoso.hosohocsinh.viewhistory','uses'=>'HosohocsinhController@viewHistory']);
            Route::post('uptoprofile',['as'=>'admin.hoso.hosohocsinh.uptoprofile','uses'=>'HosohocsinhController@uptoProfile']);
            Route::post('revertprofile',['as'=>'admin.hoso.hosohocsinh.revertprofile','uses'=>'HosohocsinhController@revertProfile']);
            Route::post('getYearHisByClassID',['as'=>'admin.hoso.hosohocsinh.getYearHisByClassID','uses'=>'HosohocsinhController@getYearHisByClassID']);

            //Form mới thêm học sinh
            Route::get('danh-sach-de-nghi',['as'=>'admin.hoso.hosohocsinh.listingnew','uses'=>'LapHoSo9Controller@getViewChedo']);
            Route::get('lap-cong-van',['as'=>'admin.hoso.lapdanhsach.listinglapcongvan','uses'=>'LapHoSo9Controller@getViewLapCongVan']);
            Route::get('danh-sach-da-lap-de-nghi',['as'=>'admin.hoso.hosohocsinh.listingview','uses'=>'LapHoSo9Controller@getViewDanhsachdalap']);
            Route::get('danh-sach-phong-tra-lai',['as'=>'admin.hoso.hosohocsinh.listingnew','uses'=>'LapHoSo9Controller@ViewApproved']);
            Route::get('danh-sach-phong-tra-lai/{school}/{congvan}',['as'=>'admin.hoso.hosohocsinh.getlistingnew','uses'=>'LapHoSo9Controller@getViewApproved']);
            Route::post('loadMoneybySub',['as'=>'admin.hoso.hosohocsinh.loadMoneybySub','uses'=>'HosohocsinhController@loadMoneyBySubject']);
            Route::get('loadComboReport',['as'=>'admin.hoso.hosohocsinh.loadComboReport','uses'=>'LapHoSo9Controller@loadDataReport']);
            Route::get('loadComboReportType/{objData}',['as'=>'admin.hoso.hosohocsinh.loadComboReportType','uses'=>'LapHoSo9Controller@loadDataReportType']);


            Route::get('loadComboReportBySchool/{id}',['as'=>'admin.hoso.hosohocsinh.loadComboReport','uses'=>'LapHoSo9Controller@loadDataReportBySchool']);
            Route::post('loadComboReportBySchoolStatus',['as'=>'admin.hoso.hosohocsinh.loadComboReport','uses'=>'LapHoSo9Controller@loadComboReportBySchoolStatus']);
            Route::get('loadCongVanThuHoi/{socongvan}',['as'=>'admin.hoso.hosohocsinh.loadComboReport','uses'=>'LapHoSo9Controller@loadCongVanThuHoi']);
            
            Route::get('loadComboUnReportBySchool/{id}',['as'=>'admin.hoso.hosohocsinh.loadComboUnReport','uses'=>'LapHoSo9Controller@loadDataUnReportBySchool']);
        });
    	Route::group(['prefix'=>'user'],function (){
			Route::get('list',['as'=>'admin.hethong.user.listUsers','uses'=>'HethongController@getUserList']);
			Route::get('info/{id}',['as'=>'layouts.change-password','uses'=>'HethongController@getUserInfo']);
    	});
    });
    Route::group(['prefix'=>'kinh-phi'],function (){
    	Route::group(['prefix'=>'muc-ho-tro-doi-tuong'],function (){
			Route::get('list',['as'=>'admin.kinhphi.capnhatmuchotrodoituong.listing','uses'=>'KinhphiController@getMucTheoDoiTuong']);
            Route::get('permission/info',['as'=>'admin.kinhphi.capnhatmuchotrodoituong.listing','uses'=>'KinhphiController@getPermission']);
            
            Route::post('insert',['as'=>'admin.kinhphi.capnhatmuchotrodoituong.listing','uses'=>'KinhphiController@insertMucTheoDoiTuong']);
            Route::post('update',['as'=>'admin.kinhphi.capnhatmuchotrodoituong.listing','uses'=>'KinhphiController@updateMucTheoDoiTuong']);
            Route::post('load',['as'=>'admin.kinhphi.capnhatmuchotrodoituong.listing','uses'=>'KinhphiController@loadMucTheoDoiTuong']);
            Route::get('getId/{id}',['as'=>'admin.kinhphi.capnhatmuchotrodoituong.listing','uses'=>'KinhphiController@getMucTheoDoiTuongById']);
            Route::get('delId/{id}',['as'=>'admin.kinhphi.capnhatmuchotrodoituong.listing','uses'=>'KinhphiController@delMucTheoDoiTuongById']);
            Route::post('search',['as'=>'admin.kinhphi.capnhatmuchotrodoituong.listing','uses'=>'KinhphiController@searchMucTheoDoiTuong']);
            Route::get('load-nhom-doi-tuong',['as'=>'admin.kinhphi.capnhatmuchotrodoituong.listing','uses'=>'KinhphiController@listNhomDoituong']);
    	});
    	Route::group(['prefix'=>'muc-ho-tro-hoc-phi'],function (){
			Route::get('list',['as'=>'admin.kinhphi.capnhatmuchocphithemnam.listing','uses'=>'KinhphiController@getHocPhiTheoNam']);
            Route::post('insert',['as'=>'admin.kinhphi.capnhatmuchocphithemnam.listing','uses'=>'KinhphiController@insertMucTheoNamHoc']);
            Route::post('update',['as'=>'admin.kinhphi.capnhatmuchocphithemnam.listing','uses'=>'KinhphiController@updateMucTheoNamHoc']);
            Route::post('load',['as'=>'admin.kinhphi.capnhatmuchocphithemnam.listing','uses'=>'KinhphiController@loadMucTheoNamHoc']);
            Route::get('getId/{id}',['as'=>'admin.kinhphi.capnhatmuchocphithemnam.listing','uses'=>'KinhphiController@getMucTheoNamHocById']);
            Route::get('delId/{id}',['as'=>'admin.kinhphi.capnhatmuchocphithemnam.listing','uses'=>'KinhphiController@delMucTheoNamHocById']);
            Route::get('loadXa',['as'=>'admin.kinhphi.capnhatmuchocphithemnam.loadXa','uses'=>'KinhphiController@LoadXa']);
    	});
        Route::group(['prefix'=>'danh-sach-ho-tro'],function (){
            Route::get('list','KinhphiController@danhsachhotro');
            Route::post('loadDSHTDT','KinhphiController@loadDataDSHTDT');
            Route::get('downloadDSHTDT-dinhkem/{id}','KinhphiController@downloadDSHTDT_fileDinhkem');
            Route::get('downloadDSHTDT-dikem/{id}','KinhphiController@downloadDSHTDT_fileDikem');
        });
    });
});

// auth routes setup
Auth::routes();

// registration activation routes
Route::get('activation/key/{activation_key}', ['as' => 'activation_key', 'uses' => 'Auth\ActivationKeyController@activateKey']);
Route::get('activation/resend', ['as' =>  'activation_key_resend', 'uses' => 'Auth\ActivationKeyController@showKeyResendForm']);
Route::post('activation/resend', ['as' =>  'activation_key_resend.post', 'uses' => 'Auth\ActivationKeyController@resendKey']);

// username forgot_username
Route::get('username/reminder', ['as' =>  'username_reminder', 'uses' => 'Auth\ForgotUsernameController@showForgotUsernameForm']);
Route::post('username/reminder', ['as' =>  'username_reminder.post', 'uses' => 'Auth\ForgotUsernameController@sendUserameReminder']);

