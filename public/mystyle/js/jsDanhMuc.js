$(function () {
    $('#datepicker1').datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true
    });
    $('#datepicker2').datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true
    });

//---------------------------------------------------------Khối lớp--------------------------------------------------------
    $('#btnInsertKhoilop').click(function(){
        messageValidate = "";
        ValidateKhoilop();
        if (messageValidate !== "") {
            utility.messagehide("group_message", messageValidate, 1, 5000);
            return;
        }

        var objData = {
            KHOILOPID: Khoilop_id,
            KHOILOPNAME: $('#txtTenkhoilop').val(),
            LEVEL: $('#drLevel').val(),
            // SCHOOLID: $('#sltTruongDt').val(),
            UNITID: $('#sltKhoiDt').val()
        };
        if (Khoilop_id == "") {
            insertKhoilop(objData);
        }
        else {updateKhoilop(objData);}
    });

    getKhoilop = function (id) {
        Khoilop_id = id;
        PostToServer('/danh-muc/khoilop/getbyKhoilopid',{KHOILOPID: Khoilop_id},function(data){
            Khoilop_id = data[0]['level_id'];
                $('#txtTenkhoilop').val(data[0]['level_name']);
                $('#drLevel').selectpicker('val',data[0]['level_level']);
                // $('#sltTruongDt').val(data[0]['level_school_id']);
                $('#sltKhoiDt').selectpicker('val',data[0]['level_unit_id']);
                $('#btnInsertKhoilop').html('Lưu');
                popupUpdateKhoilop();
        },function(data){
            console.log("getKhoilop");
            console.log(data);
        },"","","");
    };

    deleteKhoilop = function (id) {
        utility.confirm("Xóa bản ghi?", "Bạn có chắc chắn muốn xóa?", function () {
            Khoilop_id = id;
            var objJson = JSON.stringify({KHOILOPID: Khoilop_id});
            PostToServer('/danh-muc/khoilop/delete',{KHOILOPID: Khoilop_id},function(data){
                if (data['success'] !== "" || data['success'] !== undefined) {
                        utility.message("Thông báo",data['success'],null,3000);
                        $('#txtTenkhoilop').val("");
                        $('#drLevel').selectpicker('val',"");
                        // $('#sltTruongDt').val("");
                        $('#sltKhoiDt').selectpicker('val',"");
                        Khoilop_id = "";
                        $('#btnInsertKhoilop').html('Thêm mới');
                        GET_INITIAL_NGHILC();
                        loaddataKhoilop($('#drPagingKhoilop').val(), $('#txtSearchKhoilop').val());
                    }
                    if (data['error'] != "" && data['error'] != undefined) {
                        utility.message("Thông báo",data['error'],null,3000);
                    }
            },function(data){
                console.log("deleteKhoilop");
                console.log(data);
            },"","","");
        });
    };

    $('#btnCloseKhoilop').click(function(){
        $('#txtTenkhoilop').val("");
        $('#drLevel').val("");
        // $('#sltTruongDt').val("");
        $('#sltKhoiDt').val("");
        Khoilop_id = "";
    });

//---------------------------------------------------------Loại trường--------------------------------------------------------
    $('#btnInsertSchoolType').click(function(){
        // messageValidate = "";
        // validateInput("UNIT");
         if ($('#txtNametype').val() == "") {
             utility.message("Thông báo", "Yêu cầu nhập tên loại trường!", null, 5000,1);
             return;
         }

        var objData = {
            SCHOOLTYPEID: schooltype_id,
            SCHOOLTYPENAME: $('#txtNametype').val(),
            SCHOOLTYPEACTIVE: $('#drSchoolTypeActive').val()
        };
        if (schooltype_id == "") {
            insertLoaitruong(objData);
        }
        else {updateLoaitruong(objData);}
    });

    getLoaitruong = function (id) {
        schooltype_id = id;
        PostToServer('/danh-muc/loaitruong/getbytypeid',{SCHOOLTYPEID: schooltype_id},function(data){
            schooltype_id = data[0]['school_type_id'];
                $('#txtNametype').val(data[0]['school_type_name']);
                $('#drSchoolTypeActive').selectpicker('val',data[0]['active']);
                $('#btnInsertSchoolType').html('Lưu');
                popupUpdateSchoolType();
        },function(data){
            console.log("getLoaitruong");
            console.log(data);
        },"","","");
    };

    deleteLoaitruong = function (id) {
        utility.confirm("Xóa bản ghi?", "Bạn có chắc chắn muốn xóa?", function () {
            schooltype_id = id;
            var objJson = JSON.stringify({SCHOOLTYPEID: schooltype_id});
            //alert(objJson);
            $.ajax({
                type: "POST",
                url:'/danh-muc/loaitruong/delete',
                data: objJson,
                dataType: 'json',
                contentType: 'application/json; charset=utf-8',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                success: function(data) {
                    //alert(data);
                    if (data['success'] !== "" || data['success'] !== undefined) {
                        utility.message("Thông báo",data['success'],null,3000);
                        schooltype_id = "";
                        $('#btnInsertSchoolType').html('Thêm mới');
                        GET_INITIAL_NGHILC();
                        loaddataSchoolType($('#drPagingSchoolType').val(), $('#txtSearchSchoolType').val());
                    }
                    if (data['error'] != "" && data['error'] != undefined) {
                        utility.message("Thông báo",data['error'],null,3000,1);
                    }
                }, error: function(data) {
                }
            });
        });
    };

    $('#btnCloseSchoolType').click(function(){
        $('#txtNametype').val("");
        $('#drSchoolTypeActive').val(1);
        schooltype_id = "";
    });

//---------------------------------------------------Danh sách hộ nghèo---------------------------------------------------------
    $('#btnInsertDSHN').click(function(){
        messageValidate = "";
        ValidateDSHN();
        if (messageValidate !== "") {
            utility.messagehide("group_message", messageValidate, 1, 5000);
            return;
        }
        var objData = {
            DSHNID: dshn_id,
            NAME: $('#txtName').val(),
            BIRTHDAY: $('#txtBirthday').val(),
            SEX: $('#sltSex').val(),
            NATION: $('#sltNations').val(),
            RELATIONSHIP: $('#sltRelationShip').val(),
            SITE1: $('#sltSite1').val(),
            SITE2: $('#sltSite2').val(),
            TYPE: $('#sltTypeName').val(),
            INDEX: $('#txtIndex').val()
        };
        if (dshn_id == "") {
            insertDSHN(objData);
        }
        else {updateDSHN(objData);}
    });

    getDSHN = function (id) {
        dshn_id = id;
        var objJson = JSON.stringify({DSHNID: dshn_id});
        PostToServer('/danh-muc/danhsachhongheo/getbydshnid',{DSHNID: dshn_id},function(data){
            dshn_id = data[0]['DShongheo_id'];
                $('#txtName').val(data[0]['DShongheo_name']);
                $('#txtBirthday').val(data[0]['DShongheo_birthday']);
                $('#sltSex').selectpicker('val',data[0]['DShongheo_sex']);
                $('#sltRelationShip').selectpicker('val',data[0]['DShongheo_relationship']);
                $('#sltTypeName').selectpicker('val',data[0]['DShongheo_type']);
                $('#txtIndex').val(data[0]['DShongheo_index']);

                $('#sltNations').selectpicker('val',data[0]['DShongheo_nation_id']);
                $('#sltSite1').selectpicker('val',data[0]['DShongheo_site_idxa']);
               //loadDataDantoc(parseInt(data[0]['DShongheo_nation_id']));
                //loadDataSite(parseInt(data[0]['DShongheo_site_idxa']));
                loadDataSiteByID(parseInt(data[0]['DShongheo_site_idxa']), parseInt(data[0]['DShongheo_site_idthon']));

                $('#btnInsertDSHN').html('Lưu');
                popupUpdateDSHN();
            },function(data){
                console.log("getDSHN");
                console.log(data);
            },"","","");
    };

    deleteDSHN = function (id) {
        utility.confirm("Xóa bản ghi?", "Bạn có chắc chắn muốn xóa?", function () {
            dshn_id = id;
            var objJson = JSON.stringify({DSHNID: dshn_id});
            //alert(objJson);
            $.ajax({
                type: "POST",
                url:'/danh-muc/danhsachhongheo/delete',
                data: objJson,
                dataType: 'json',
                contentType: 'application/json; charset=utf-8',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                success: function(data) {
                    //alert(data);
                    if (data['success'] !== "" || data['success'] !== undefined) {
                        utility.message("Thông báo",data['success'],null,3000);
                        dshn_id = "";
                        $('#btnInsertDSHN').html('Thêm mới');
                        GET_INITIAL_NGHILC();
                        loaddataDSHN($('#drPagingDSHN').val(), $('#txtSearchDSHN').val());
                    }
                    if (data['error'] != "" && data['error'] != undefined) {
                        utility.message("Thông báo",data['error'],null,3000,1);
                    }
                }, error: function(data) {
                }
            });
        });
    };
    
    $('#btncloseDSHN').click(function(){
        $("#sltSex").selectpicker('val','');

        $("#sltNations").selectpicker('val','');
    
        $("#sltTypeName").selectpicker('val','');

        $("#sltSite2").attr('disabled', 'disabled');
        $("#sltSite2").selectpicker('val','');

        $("#sltSite1").selectpicker('val','');

        $("#sltRelationShip").selectpicker('val','1');

        $('#txtName').val('');
        $('#txtBirthday').val('');
        dshn_id = "";
    });

//---------------------------------------------------quản lý người nấu ăn---------------------------------------------------------
    $('#btnInsertNgna').click(function(){
        messageValidate = "";
        ValidateNGNA("NGNA");
        if (messageValidate !== "") {
            utility.messagehide("group_message", messageValidate, 1, 5000);
            return;
        }
        var objData = {
            NGNAID: ngna_id,
            SCHOOLID: $('#sltTruongDt').val(),
            HSBT: $('#txtHSBT').val(),
            NVTW: $('#txtNVTW').val(),
            NVDP: $('#txtNVDP').val(),
            NV68: $('#txtNV68').val(),
            Years: $('#sltYearNA').val(),
            Units: $('#sltKhoiLop').val(),
            HSTW : $('#txtHSTW').val(),
            HSDP : $('#txtHSDP').val()
        };
        if (ngna_id == "") {
            insertNGNA(objData);
        }
        else {updateNGNA(objData);}
    });

    getNGNA = function (id) {
        ngna_id = id;
      // var objJson = JSON.stringify({NGNAID: ngna_id});
        PostToServer('/danh-muc/nguoinauan/getbyngnaid',{NGNAID: ngna_id},function(data){
            ngna_id = data[0]['NGNA_id'];
                $('#sltTruongDt').selectpicker('val',data[0]['NGNA_school_id']);
                // $('#txtAmount').val(data[0]['NGNA_amount']);
                // $('#startDate').datepicker('setDate', new Date(data[0]['NGNA_startdate']));
                $('#txtHSBT').val(data[0]['NGNA_amount']);
                $('#txtNVTW').val(data[0]['NGNA_TW']);
                $('#txtNVDP').val(data[0]['NGNA_DP']);
                $('#txtNV68').val(data[0]['NGNA_68']);
                $('#sltYearNA').selectpicker('val',data[0]['NGNA_HK']);
                $('#sltKhoiLop').selectpicker('val',data[0]['NGNA_unit_id']);
                $('#txtHSTW').val(data[0]['NGNA_HSTW']);
                $('#txtHSDP').val(data[0]['NGNA_HSDP']);


                $('#btnInsertNgna').html('Lưu');
                popupUpdateNgna();
        },function(data){
            console.log("getNGNA");
            console.log(data);
        },"","","");
    };

    deleteNGNA = function (id) {
        utility.confirm("Xóa bản ghi?", "Bạn có chắc chắn muốn xóa?", function () {
            ngna_id = id;
            var objJson = JSON.stringify({NGNAID: ngna_id});
            PostToServer('/danh-muc/nguoinauan/delete',{NGNAID: ngna_id},function(data){
                if (data['success'] !== "" || data['success'] !== undefined) {
                        utility.message("Thông báo",data['success'],null,3000);
                        ngna_id = "";
                        $('#btnInsertNgna').html('Thêm mới');
                        GET_INITIAL_NGHILC();
                        loaddataNguoinauan($('#drPagingNguoinauan').val(), $('#txtSearchNgna').val());
                    }
                    if (data['error'] != "" && data['error'] != undefined) {
                        utility.message("Thông báo",data['error'],null,3000,1);
                    }
            },function(data){
                console.log("deleteNGNA");
                console.log(data);
            },"","","");
        });
    };
    
    $('#btncloseNGNA').click(function(){
        $("#sltTruongDt").selectpicker('val','');
        $('#txtAmount').val("");
        $('#startDate').val("");
        // $('#endDate').val("");
        ngna_id = "";
    });

//Nhóm đối tượng----------------------------------------------------------------------------------------------------
    $('#btnInsertGroup').click(function(){
        messageValidate = "";
        validateInput("GROUP");
        if (messageValidate !== "") {
            utility.messagehide("group_message", messageValidate, 1, 5000);
            return;
        }
        var objData = {
            GROUPID: group_id,
            // GROUPCODE: $('#txtGroupCode').val(),
            GROUPNAME: $('#txtGroupName').val(),
            GROUPACTIVE: $('#drGroupActive').val(),
        };
        if (group_id == "") {
            insertNhomDoiTuong(objData);
        }
        else { updateNhomDoiTuong(objData); }
    });
    
    $('a#btnUpdateGroup').click(function(){
        group_id = $(this).attr('data');
        var objData = {
            GROUPID: group_id,
        };

        getNhomDoiTuongbyID(objData);
        $('#btnInsertGroup').html('Lưu');
    });
    
    $('a#btnDeleteGroup').click(function(){
        group_id = $(this).attr('data'),
        $('#btnInsertGroup').html('Thêm mới');
    });

    $('#btnConfirmDeleteGroup').click(function(){
        var objData = {
            GROUPID: group_id,
        };

        deleteNhomDoiTuong(objData);
    });

    getGroup = function (id) {
        group_id = id;
        var objJson = JSON.stringify({GROUPID: group_id});
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/nhomdoituong/getbygroupid',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                group_id = data[0]['group_id'];
                // $('#txtGroupCode').val(data[0]['group_code']);
                $('#txtGroupName').val(data[0]['group_name']);
                $('#drGroupActive').val(data[0]['group_active']);
                $('#btnInsertGroup').html('Lưu');
                popupUpdateGroup();
            }, error: function(data) {
            }
        });
    };

    deleteGroup = function (id) {
        utility.confirm("Xóa bản ghi?", "Bạn có chắc chắn muốn xóa?", function () {
            group_id = id;
            var objJson = JSON.stringify({GROUPID: group_id});
            //alert(objJson);
            $.ajax({
                type: "POST",
                url:'/danh-muc/nhomdoituong/delete',
                data: objJson,
                dataType: 'json',
                contentType: 'application/json; charset=utf-8',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                success: function(data) {
                    //alert(data);
                    if (data['success'] !== "" || data['success'] !== undefined) {
                        utility.message("Thông báo",data['success'],null,3000);
                        group_id = "";
                        $('#btnInsertGroup').html('Thêm mới');
                        loaddataGroup($('#drPagingGroup').val(), $('#txtSearchGroup').val());
                    }
                    if (data['error'] != "" && data['error'] != undefined) {
                        utility.message("Thông báo",data['error'],null,3000);
                    }
                }, error: function(data) {
                }
            });
        });
    };

    $('#btnCloseGroup').click(function(){
        // $('#txtGroupCode').val("");
        $('#txtGroupName').val("");
        $('#drGroupActive').val(1);
        group_id = "";
    });

//Khối----------------------------------------------------------------------------------------------------
    $('#btnInsertUnit').click(function(){
        messageValidate = "";
        validateInput("UNIT");
        if (messageValidate !== "") {
            utility.messagehide("group_message", messageValidate, 1, 5000);
            return;
        }
        var objData = {
            UNITID: unit_id,
            // UNITCODE: $('#txtUnitCode').val(),
            UNITNAME: $('#txtUnitName').val(),
            UNITACTIVE: $('#drUnitActive').val()
            // UNITSCHOOLID: $('#sltTruongDt').val()
        };
        if (unit_id == "") {
            insertKhoi(objData);
        }
        else {updateKhoi(objData);}
    });
    
    $('a#btnUpdateUnit').click(function(){
        unit_id = $(this).attr('data');
        var objData = {
            UNITID: unit_id,
        };

        $('#btnInsertUnit').html('Lưu');
        getKhoibyID(objData);
    });
    
    $('a#btnDeleteUnit').click(function(){
        unit_id = $(this).attr('data');
        $('#btnInsertUnit').html('Thêm mới');
    });
    
    $('#btnConfirmDeleteUnit').click(function(){
        var objData = {
            UNITID: unit_id,
        };

        deleteKhoi(objData);
    });

    getUnit = function (id) {
        unit_id = id;
        var objJson = JSON.stringify({UNITID: unit_id});
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/khoi/getbyunitid',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                unit_id = data[0]['unit_id'];
                $('#txtUnitCode').val(data[0]['unit_code']);
                $('#txtUnitName').val(data[0]['unit_name']);
                $('#drUnitActive').val(data[0]['unit_active']);
                $('#btnInsertUnit').html('Lưu');
                popupUpdateUnit();
            }, error: function(data) {
            }
        });
    };

    deleteUnit = function (id) {
        utility.confirm("Xóa bản ghi?", "Bạn có chắc chắn muốn xóa?", function () {
            unit_id = id;
            var objJson = JSON.stringify({UNITID: unit_id});
            //alert(objJson);
            $.ajax({
                type: "POST",
                url:'/danh-muc/khoi/delete',
                data: objJson,
                dataType: 'json',
                contentType: 'application/json; charset=utf-8',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                success: function(data) {
                    //alert(data);
                    if (data['success'] !== "" || data['success'] !== undefined) {
                        utility.message("Thông báo",data['success'],null,3000);
                        nation_id = "";
                        $('#btnInsertUnit').html('Thêm mới');
                        loaddataUnit($('#drPagingUnit').val(), $('#txtSearchUnit').val());
                    }
                    if (data['error'] != "" && data['error'] != undefined) {
                        utility.message("Thông báo",data['error'],null,3000);
                    }
                }, error: function(data) {
                }
            });
        });
    };

    $('#btnCloseUnit').click(function(){
        $('#txtUnitCode').val("");
        $('#txtUnitName').val("");
        $('#drUnitActive').val(1);
        unit_id = "";
    });

//Dân tộc----------------------------------------------------------------------------------------------------
    $('#btnInsertNation').click(function(){
        messageValidate = "";
        validateInput("NATION");
        if (messageValidate !== "") {
            utility.messagehide("group_message", messageValidate, 1, 5000);
            return;
        }
        var type = 0;
        if ($('#cbxType').prop('checked')) {
            type = 1;
        }
        var objData = {
            NATIONID: nation_id,
            NATIONTYPE: type,
            NATIONNAME: $('#txtNationName').val(),
            NATIONACTIVE: $('#drNationActive').val(),
        };
        console.log(objData);
        if (nation_id == "") {
            insertDantoc(objData);
        }
        else {updateDantoc(objData);}
    });
    
    $('a#btnUpdateNation').click(function(){
        nation_id = $(this).attr('data');
        var objData = {
            NATIONID: nation_id,
        };

        $('#btnInsertNation').html('Lưu');
        getDantocbyID(objData);
    });

    getNation = function (id) {
        nation_id = id;

        PostToServer('/danh-muc/dantoc/getbynationid',{NATIONID: nation_id},function(data){
            nation_id = data[0]['nationals_id'];
                // $('#txtNationCode').val(data[0]['nationals_code']);
                $('#txtNationName').val(data[0]['nationals_name']);
                $('#drNationActive').selectpicker('val',data[0]['nationals_active']);
                if (parseInt(data[0]['nationals_type']) > 0) {
                    $('#cbxType').prop('checked', true);
                }
                else{$('#cbxType').prop('checked', false);}
                $('#btnInsertNation').html('Lưu');
                // $('#txtNationCode').attr('readonly', true);
                popupUpdateNation();
        },function(data){
            console.log("getNation");
            console.log(data);
        },"","","");
    };
    
    $('a#btnDeleteNation').click(function(){
        nation_id = $(this).attr('data');
        $('#btnInsertNation').html('Thêm mới');
    });

    deleteNation = function (id) {
        utility.confirm("Xóa bản ghi?", "Bạn có chắc chắn muốn xóa?", function () {
            nation_id = id;
            var objJson = JSON.stringify({NATIONID: nation_id});
            //alert(objJson);
            $.ajax({
                type: "POST",
                url:'/danh-muc/dantoc/delete',
                data: objJson,
                dataType: 'json',
                contentType: 'application/json; charset=utf-8',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                success: function(data) {
                    //alert(data);
                    if (data['success'] !== "" || data['success'] !== undefined) {
                        utility.message("Thông báo",data['success'],null,3000);
                        nation_id = "";
                        $('#btnInsertNation').html('Thêm mới');
                        // $('#txtNationCode').attr('readonly', true);
                        loaddataNation($('#drPagingNation').val(), $('#txtSearchNational').val());
                    }
                    if (data['error'] != "" && data['error'] != undefined) {
                        utility.message("Thông báo",data['error'],null,3000);
                    }
                }, error: function(data) {
                }
            });
        });
    };
    
    $('#btnCloseNation').click(function(){
        // $('#txtNationCode').val("");
        $('#txtNationName').val("");
        $('#drNationActive').val(1);
        nation_id = "";
    });

//Đối tượng----------------------------------------------------------------------------------------------------
    
    $('a#btnUpdateSubject').click(function(){
        subject_id = $(this).attr('data');
        $('#btnInsertSubject').html('Lưu');
        getDoituongbyID();
        getNhombyDoituongID();
        popupAddnewSubject();
    });
    
    $('a#btnDeleteSubject').click(function(){
        subject_id = $(this).attr('data');
        $('#btnInsertSubject').html('Thêm mới');
        popupConfirmDeleteSubject();
    });

    getSubject = function (id) {
        subject_id = id;
        var objJson = JSON.stringify({SUBJECTID: subject_id});
        PostToServer('/danh-muc/doituong/getbysubjectid',{SUBJECTID: subject_id},function(data){
            subject_id = data['objSubject'][0]['subject_id'];
                $('#txtSubjectName').val(data['objSubject'][0]['subject_name']);
                $('#drSubjectActive').selectpicker('val',data['objSubject'][0]['subject_active']);
                $('#btnInsertSubject').html('Lưu');
                var arrData = "";
                var arrGroupID = data['arrGroupID'];
                for (var i = 0; i < arrGroupID.length; i++) {
                    arrData += (arrGroupID[i]['subject_history_group_id']) + ",";    
                }
                var item = arrData.split(",");
                $("#drGroupSubject").selectpicker('val',item);
                $("#drGroupSubject").selectpicker("refresh");
                popupUpdateSubject();
        },function(data){
            console.log("getSubject");
            console.log(data);
        },"","","");
    };

    deleteSubject = function (id) {
        utility.confirm("Xóa bản ghi?", "Bạn có chắc chắn muốn xóa?", function () {
            subject_id = id;
            var objJson = JSON.stringify({SUBJECTID: subject_id});
            //alert(objJson);
            $.ajax({
                type: "POST",
                url:'/danh-muc/doituong/delete',
                data: objJson,
                dataType: 'json',
                contentType: 'application/json; charset=utf-8',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                success: function(data) {
                    //alert(data);
                    if (data['success'] !== "" || data['success'] !== undefined) {
                        utility.message("Thông báo",data['success'],null,3000);
                        subject_id = "";
                        $('#btnInsertSubject').html('Thêm mới');
                        loaddataSubject($('#drPagingSubject').val(), $('#txtSearchSubject').val());
                    }
                    if (data['error'] != "" && data['error'] != undefined) {
                        utility.message("Thông báo",data['error'],null,3000);
                    }
                }, error: function(data) {
                }
            });
        });
    };

    $('#btnCloseSubject').click(function(){
        // $('#txtSubjectCode').val("");
        $('#txtSubjectName').val("");
        $('#drSubjectActive').val(1);

        subject_id = "";
    });

//Trường----------------------------------------------------------------------------------------------------
    $('#btnInsertSchool').click(function(){
        messageValidate = "";
        validateInput("SCHOOL");
        if (messageValidate !== "") {
            utility.messagehide("group_message", messageValidate, 1, 5000);
            return;
        }
        var cbxMCCOrTT = 0;
        if($('#cbxMCCOrTT').is(':checked')){
            cbxMCCOrTT = 1;
        }
        var str = "";
        $.each($('#sltUnit').val(), function( index, value ) {
            str += value + "-";
        });
        var objData = {
            SCHOOLID: school_id,
            UnitSchool: str,
            SCHOOLNAME: $('#txtSchoolName').val(),
            MCCTT: cbxMCCOrTT,
            SCHOOLTYPE: $('#drSchoolType').val(),
            STARTDATE: $('#txtStartDate').val(),
            SCHOOLACTIVE: $('#drSchoolActive').val(),
            UPDATETYPE: update_type,
            HISID: school_his_id
        };
        if (school_id == "") {
            insertTruong(objData);
        }
        else {updateTruong(objData);}
    });
    
    $('a#btnUpdateSchool').click(function(){
        school_id = $(this).attr('data');
        var objData = {
            SCHOOLID: school_id,
        };

        $('#btnInsertSchool').html('Lưu');
        getTruongbyID(objData);
    });
    
    $('a#btnDeleteSchool').click(function(){
        school_id = $(this).attr('data');
        $('#btnInsertSchool').html('Thêm mới');
    });
    
    $('#btnConfirmDeleteSchool').click(function(){
        var objData = {
            SCHOOLID: school_id,
        };

        deleteTruong(objData);
    });

    var update_type = 0;
    var school_his_id = 0;

    getSchool = function (id, idHis, modifier = 0) {
        school_id = id;
        update_type = modifier;
        school_his_id = idHis;
        var objJson = "";

        if (idHis !== null && idHis !== "" && idHis > 0) {
            objJson = {SCHOOLHISID: idHis};
        }
        else if (id !== null && id !== "" && id > 0) {
            objJson =  {SCHOOLID: id};
        }
        PostToServer('/danh-muc/truong/getbyschoolid',objJson,function(data){
            if (modifier > 0) {
                    $('.modal-title').html('Cập nhập trường');
                    $('#btnInsertSchool').html('Lưu');
                    $('#txtStartDate').attr('disabled', 'disabled');
                }
                else {
                    $('.modal-title').html('Thay đổi trường');
                    $('#btnInsertSchool').html('Thay đổi');
                    $('#txtStartDate').removeAttr('disabled');
                }
                school_id = data[0]['schools_id'];
                if(parseInt(data[0]['TramTauOrMCC']) == 1){
                    $('#cbxMCCOrTT').prop('checked', true);
                }else{
                    $('#cbxMCCOrTT').prop('checked', false);
                }
                $('#txtSchoolName').val(data[0]['schools_name']);                
                $('#drSchoolType').selectpicker('val',data[0]['type_his_type_id']);
                 var item = (data[0]['LienCap'] != null ? data[0]['LienCap'] : "").split("-");
                $('#sltUnit').selectpicker('deselectAll');
                $('#sltUnit').selectpicker('val',item);
                if (idHis !== null && idHis !== "" && idHis > 0) {
                    $('#txtStartDate').datepicker('setDate', new Date(data[0]['type_his_startdate']));
                }
                
                $('#drSchoolActive').selectpicker('val',data[0]['schools_active']);
                popupUpdateSchool();
        },function(data){
            console.log("getSchool");
            console.log(data);
        },"","","");
    };

    deleteSchool = function (id, hisID) {
        utility.confirm("Xóa bản ghi?", "Bạn có chắc chắn muốn xóa?", function () {
            PostToServer('/danh-muc/truong/delete',{ SCHOOLID: id, SCHOOLHISID: hisID },
                function(data){
                    if (data['success'] !== "" || data['success'] !== undefined) {
                        utility.message("Thông báo",data['success'],null,3000);
                        school_id = "";
                        $('#btnInsertSchool').html('Thêm mới');
                        GET_INITIAL_NGHILC();
                        loaddataSchool($('#drPagingSchool').val(), $('#txtSearchSchool').val());
                    }
                    if (data['error'] != "" && data['error'] != undefined) {
                        utility.message("Thông báo",data['error'],null,3000, 1);
                    }
                },function(data){
                    console.log("deleteSchool");
                    console.log(data);
                },"","","");
        });
    };

    $('#btnCloseSchool').click(function(){
        school_id = "";
        update_type = 0;
        school_his_id = 0;
        // $('#txtSchoolCode').val("");
        $('#txtSchoolName').val("");
        // $('#drUnit').val("");
        $('#drSchoolType').val("");
        $('#txtStartDate').val("");
        $('#drSchoolActive').val(1);
    });

//Lớp----------------------------------------------------------------------------------------------------
    $('#btnInsertClass').click(function(){
        messageValidate = "";
        validateInput("CLASS");
        if (messageValidate !== "") {
            utility.messagehide("group_message", messageValidate, 1, 5000);
            return;
        }
        var objData = {
            CLASSID: class_id,
            UNITID: $('#sltKhoiDt').val(),
            CLASSNAME: $('#txtClassName').val(),
            SCHOOLID: $('#sltTruongDt').val(),
            LEVELID: $('#drLevel').val(),
            CLASSACTIVE: $('#drClassActive').val(),
            DIEMTRUONG: $('#sltDiemTruong').val(),
        };

        // console.log(objData);
        if (class_id == "") {
            insertLop(objData);
        }
        else {updateLop(objData);}
    });
    
    $('a#btnUpdateClass').click(function(){
        class_id = $(this).attr('data');
        var objData = {
            CLASSID: class_id,
        };

        $('#btnInsertClass').html('Lưu');
        getLopbyID(objData);
    });
    
    $('a#btnDeleteClass').click(function(){
        class_id = $(this).attr('data');
        $('#btnInsertClass').html('Thêm mới');
    });
    
    $('#btnConfirmDeleteClass').click(function(){
        var objData = {
            CLASSID: class_id,
        };

        deleteLop(objData);
    });

    getClass = function (id) {
        class_id = id;
        var objJson = {CLASSID: class_id};
        PostToServer('/danh-muc/lop/getbyclassid',objJson,function(data){
            school_id = data[0]['class_id'];
                $('#txtClassCode').val(data[0]['class_code']);
                $('#txtClassName').val(data[0]['class_name']);                
                $('#sltTruongDt').val(data[0]['class_schools_id']).trigger('change');
                $('#sltDiemTruong').val(data[0]['warehouse_id']).selectpicker('refresh');
                //loadComboxTruongHocSingle('sltTruongDt', function(){}, $('#school-per').val(), parseInt(data[0]['class_schools_id']));
                getUnitAll(data[0]['class_unit_id'],function(){
                    getLevelbyUnitID(data[0]['class_unit_id'],data[0]['class_level_id'],null);
                });
                $('#drClassActive').val(data[0]['class_active']);
                
                popupUpdateClass();
        },function(data){
            console.log("getClass");
            console.log(data);
        },"btnInsertClass","","");
    };

    deleteClass = function (id) {
        utility.confirm("Xóa bản ghi?", "Bạn có chắc chắn muốn xóa?", function () {
            class_id = id;
            var objJson = JSON.stringify({CLASSID: class_id});
            PostToServer('/danh-muc/lop/delete',{CLASSID: class_id},function(data){
                if (data['success'] !== "" || data['success'] !== undefined) {
                        utility.message("Thông báo",data['success'],null,3000);
                        class_id = "";
                        $('#txtClassCode').val("");
                        $('#txtClassName').val("");
                        $("#sltTruongDt").val('').select2();
                        $('#sltKhoiDt').val("");
                        $('#drLevel').val("");
                        $('#drClassActive').val(1);
                        $('#btnInsertClass').html('Thêm mới');
                        GET_INITIAL_NGHILC();
                        loaddataClass($('#drPagingClass').val(), $('#txtSearchClass').val());
                    }
                    if (data['error'] != "" && data['error'] != undefined) {
                        utility.message("Thông báo",data['error'],null,3000);
                    }
            },function(data){
                console.log("deleteClass");
                console.log(data);
            },"","","");
        });
    };

    $('#btnCloseClass').click(function(){
        $('#txtClassCode').val("");
        $('#txtClassName').val("");
        $("#sltTruongDt").selectpicker('val','');
        $('#drLevel').selectpicker('val',"");
        $('#drClassActive').selectpicker('val',1);
        class_id = "";
    });

//Phân loại xã----------------------------------------------------------------------------------------------------
    $('#btnInsertWard').click(function(){
        messageValidate = "";
        validateInput("WARD");
        if (messageValidate !== "") {
            //utility.messagehide("group_message", messageValidate, 1, 5000);
            alert(messageValidate);
            return;
        }
        ward_id = $('#txtWardID').val();
        var ward_level = $('#drWardParent').find(":selected").attr('data');
        var objData = {
            WARDID: ward_id,
            // WARDCODE: $('#txtWardCode').val(),
            WARDNAME: $('#txtWardName').val(),
            WARDPARENTID: $('#drWardParent').val(),
            WARDLEVEL: ward_level,
            WARDACTIVE: $('#drWardActive').val(),
        };
        if (ward_id == "") {
            insertWard(objData);
        }
        else {updateWard(objData);}
    });
    
    $('#btnDeleteWard').click(function(){
        utility.confirm("Xóa bản ghi?", "Bạn có chắc chắn muốn xóa?", function () {
            var objData = {
                WARDID: $('#txtWardID').val()
            };

            deleteWard(objData);
        });
    });

    $('#btnResetWard').click(function(){
        // $('#txtWardCode').focus();
         $('#drWardParent').selectpicker('val',0);
        $('#txtWardName').val("");
        // loadComboWard( function(data){
        // });
        $('#drWardActive').selectpicker('val',1);
        $('#txtWardID').val("");
        $("#btnInsertWard").html("Thêm mới");
        ward_id = "";
        $("#btnDeleteWard").attr("disabled", true);
        // $("#txtWardCode").attr("disabled", false);
        //$("#using_json_2").jstree().deselect_node(true);
        //$('#using_json_2').jstree("destroy").empty();
        //$("#using_json_2").jstree('destroy');
    });

//Xã Phường----------------------------------------------------------------------------------------------------
    $('#btnInsertSite').click(function(){
        messageValidate = "";
        validateInput("SITE");
        if (messageValidate !== "") {
            //utility.messagehide("group_message", messageValidate, 1, 5000);
            alert(messageValidate);
            return;
        }
        site_id = $('#txtSiteID').val();
        var site_level = $('#drSiteLevel').val();
        var objData = {
            SITEID: site_id,
            SITENAME: $('#txtSiteName').val(),
            SITEPARENTID: $('#drSiteParents').val(),
            SITELEVEL: site_level++,
            SITETYPE: $('#drSiteType').val(),
            SITEACTIVE: $('#drSiteActive').val(),
        };
        // console.log(objData);
        if (site_id == "") {
            insertSite(objData);
        }
        else {updateSite(objData);}
    });
    
    $('#btnDeleteSite').click(function(){
        utility.confirm("Xóa bản ghi?", "Bạn có chắc chắn muốn xóa?", function () {
            var objData = {
                SITEID: $('#txtSiteID').val()
            };

            deleteSite(objData);
        });
    });

    $('#btnResetSite').click(function(){
        resetXaPhuong();
    });

//Phòng ban----------------------------------------------------------------------------------------------------
    $('#btnInsertDepartment').click(function(){
        messageValidate = "";
        validateInput("DEPARTMENT");
        if (messageValidate !== "") {
            //utility.messagehide("group_message", messageValidate, 1, 5000);
            alert(messageValidate);
            return;
        }
        depart_id = $('#txtDepartID').val();
        var depart_level = $('#drpDepartment').find(":selected").attr('data');
        var objData = {
            DEPARTMENTID: depart_id,
            // DEPARTMENTCODE: $('#txtDepartCode').val(),
            DEPARTMENTNAME: $('#txtDepartName').val(),
            DEPARTMENTPARENTID: $('#drpDepartment').val(),
            DEPARTMENTLEVEL: depart_level,
            DEPARTMENTACTIVE: $('#drDepartActive').val(),
        };
        if (depart_id == "") {
            insertDepartment(objData);
        }
        else {updateDepartment(objData);}
    });
    
    $('#btnDeleteDepartment').click(function(){
        utility.confirm("Xóa bản ghi?", "Bạn có chắc chắn muốn xóa?", function () {
            var objData = {
                DEPARTMENTID: $('#txtDepartID').val()
            };

            deleteDepartment(objData);
        });
    });    

    $('#btnResetDepartment').click(function(){
        // $('#txtDepartCode').focus();
        // $('#txtDepartCode').val("");
        $('#txtDepartName').val("");
        loadComboDepartment( function(data){
        });
        $('#drDepartActive').val(1);
        $("#btnInsertDepartment").html("Thêm mới");
        $('#txtDepartID').val("");
        depart_id = "";
        $("#btnDeleteDepartment").attr("disabled", true);        
        // $("#txtDepartCode").attr("disabled", false);
    });

    $('button.close').click(function(){
        $('#txtUnitCode').val("");
        $('#txtUnitName').val("");
        $('#drUnitActive').val(1);
        unit_id = "";
        // $('#txtSubjectCode').val("");
        $('#txtSubjectName').val("");
        $('#drSubjectActive').val(1);
        $("#drGroupSubject").val('');
        $("#drGroupSubject option").removeAttr('selected');
        subject_id = "";
        // $('#txtGroupCode').val("");
        $('#txtGroupName').val("");
        $('#drGroupActive').val(1);
        group_id = "";
        // $('#txtNationCode').val("");
        $('#txtNationName').val("");
        $('#drNationActive').val(1);
        nation_id = "";
        // $('#txtSchoolCode').val("");
        $('#txtSchoolName').val("");
        $('#drUnit').val("");
        $('#drSchoolActive').val(1);
        school_id = "";
        $('#txtClassCode').val("");
        $('#txtClassName').val("");
        $('#drSchool').val("");
        $('#drLevel').val("");
        $('#drClassActive').val(1);
        class_id = "";
    });

//End Action Form-------------------------------------------------------------------------------------------------------

    // var t = $('#example1').DataTable({
    //     "paging": true,
    //     "language": {
    //         "lengthMenu":  "b _MENU_ a",
    //         "info": "Hiển thị _START_ đến _END_ của _TOTAL_ bản ghi" ,
    //         "paginate": {
    //                     "first": "First",
    //                     "last": "Last",
    //                     "next": "Trang sau",
    //                     "previous": "Trang trước"
    //         },"emptyTable": "Không có dữ liệu"
    //     },
    //     "lengthChange": false,
    //     "searching": false,
    //     "ordering": true,
    //     "info": true,
    //     //"ajax":'load',
    //     "ajax": {
    //         // "url": "load",
    //         // "type": 'POST'
    //         "type": "POST",
    //         "url": 'load',
    //         "contentType": 'application/json; charset=utf-8',
    //         "headers": {
    //             'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
    //         }
    //     },
    //     "columns": [
    //         { "data": "id" },
    //         { "data": "code" },
    //         { "data": "subject_name" },
    //         { "data": "money" },
    //         { "data": "start_date" },
    //         { "data": "end_date" },
    //         { "data": "updated_at" },
    //         { "data": "username" },
    //         {
    //             "data": null,
    //             "className": "center",
    //             "defaultContent": '<a href="" class="btn btn-info btn-xs editor_edit"><i class="glyphicon glyphicon-pencil"></i> Sửa</a> <a href="" class="btn btn-danger btn-xs editor_remove"><i class="glyphicon glyphicon-remove"></i> Xóa</a>'
    //         }
    //     ],
    //     // "columns": [
    //     //     { "data": "data.code" },
    //     //     { "data": "data.subject_name" },
    //     //     { "data": "data.money" },
    //     //     { "data": "data.start_date" },
    //     //     { "data": "data.end_date" },
    //     //     { "data": "data.updated_at" }
    //     //     { "data": "data.username" }
    //     // ],
    //     // "select": true,
       
    //   "autoWidth": false
    // });
    // t.on( 'order.dt search.dt', function () {
    //     t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
    //         cell.innerHTML = i+1;
    //     } );
    // }).draw();
});

var Khoilop_id = "";
var schooltype_id = "";
var dshn_id = "";
var ngna_id = "";
var group_id = "";
var unit_id = "";
var nation_id = "";
var subject_id = "";
var school_id = "";
var class_id = "";
var site_id = "";
var ward_id = "";
var depart_id = "";

//Check Permisstion
    var module = 0;
    var CODE_FEATURES;
    function permission(callback) {
                // console.log(module);
        $.ajax({
            type: "GET",
            url: '/danh-muc/permission?module='+module,
            success: function(data) {
                // console.log(data);
                CODE_FEATURES = data.permission;
                if(callback!=null){
                    callback();
                }
            }, error: function(data) {
            }
        });
    };

    function check_Permission_Feature(featureCode) {
        // console.log(featureCode);
        // console.log(CODE_FEATURES);
        try {
            if (CODE_FEATURES.indexOf(featureCode) >= 0) {
                // console.log(Object.values(CODE_FEATURES).indexOf(featureCode));
                return true;
            }
            // if(callback!=null){
            //     callback();
            // }

            // for (var i = 0; i < CODE_FEATURES.length; i++) {
            //     if(CODE_FEATURES[i] == featureCode) { 
            //         console.log(featureCode); 
            //         return true; }
            // }
                
            return false;


        } catch (e) {
            console.log(e);
        }
        return true;
    }

//---------------------------------------------------------------Khối lớp---------------------------------------------------------
    function loaddataKhoilop(row, keySearch) {
        var html_show = "";
        $('#dataKhoilop').html("<tr><td colspan='50' class='text-center'>Đang tải dữ liệu...</td></tr>");
        var o = {
            start: (GET_START_RECORD_NGHILC()),
            limit : row,
            key: keySearch
        };
        PostToServer('/danh-muc/khoilop/loadKhoilop',o,function(dataget){
            SETUP_PAGING_NGHILC(dataget, function () {
                        loaddataKhoilop(row, keySearch);
                    });
                    var v_status = "";
                    $('#dataKhoilop').html("");
                    data = dataget.data;
                    if(data.length > 0){
                        for (var i = 0; i < data.length; i++) {
                            html_show += "<tr><td class='text-center'>"+(i + 1 + (GET_START_RECORD_NGHILC() * row))+"</td>";
                            // html_show += "<td>"+ConvertString(data[i].schools_name)+"</td>";
                            html_show += "<td>"+ConvertString(data[i].unit_name)+"</td>";
                            html_show += "<td>"+ConvertString(data[i].level_name)+"</td>";
                            html_show += "<td class='text-right'>"+formatDateTimes(data[i].create_date)+"</td>";
                            
                            if(check_Permission_Feature('2')){
                                html_show += "<td class='text-center'>";
                                html_show += "<button data='"+data[i].level_id+"' onclick='getKhoilop("+data[i].level_id+");' class='btn btn-info btn-xs' id='editor_editss'><i class='glyphicon glyphicon-pencil'></i> Sửa</button> ";
                                html_show += "</td>";
                            }
                            if(check_Permission_Feature('3')){
                                html_show += "<td class='text-center'>";
                                html_show += "<button  onclick='deleteKhoilop("+data[i].level_id+");'  class='btn btn-danger btn-xs editor_remove'><i class='glyphicon glyphicon-remove'></i> Xóa</button>";
                                html_show += "</td>";
                            }
                            html_show += "</td></tr>";
                        }
                        
                    } else {
                        html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
                    }
                    $('#dataKhoilop').html(html_show);
            },function(dataget){

            },"","","");
        };

    function insertKhoilop(temp) {
        var objJson = JSON.stringify(temp);
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/khoilop/insert',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                // console.log(data);
                if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    
                    $('#txtTenkhoilop').val("");
                    $('#drLevel').val("");
                    // $("#sltTruongDt").val('').select2();
                    $('#sltKhoiDt').val("");
                    Khoilop_id = "";
                    GET_INITIAL_NGHILC();
                    loaddataKhoilop($('#drPagingKhoilop').val(), $('#txtSearchKhoilop').val());
                }
                if (data['error'] !== "" && data['error'] !== undefined) {
                    utility.message("Thông báo",data['error'],null,3000,1);
                }
            }, error: function(data) {
            }
        });
    };

    function updateKhoilop(temp) {
        var objJson = JSON.stringify(temp);
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/khoilop/update',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    $('#txtTenkhoilop').val("");
                    $('#drLevel').val("");
                    // $("#sltTruongDt").val('').select2();
                    $('#sltKhoiDt').val("");
                    Khoilop_id = "";
                    $('#btnInsertKhoilop').html('Thêm mới');
                    $("#modalAddNew").modal("hide");
                    GET_INITIAL_NGHILC();
                    loaddataKhoilop($('#drPagingKhoilop').val(), $('#txtSearchKhoilop').val());
                }
                
                if (data['error'] != "" && data['error'] != undefined) {
                    utility.message("Thông báo",data['error'],null,3000,1);
                }
            }, error: function(data) {
            }
        });
    };

//---------------------------------------------------------------School Type---------------------------------------------------------
    function loaddataSchoolType(row, keySearch) {
        var html_show = "";
        $('#dataSchoolType').html("<tr><td colspan='50' class='text-center'>Đang tải dữ liệu...</td></tr>");
        var o = {
            start: (GET_START_RECORD_NGHILC()),
            limit : row,
            key: keySearch
        };
        PostToServer('/danh-muc/loaitruong/loadLoaiTruong',o,function(dataget){
            SETUP_PAGING_NGHILC(dataget, function () {
                        loaddataSchoolType(row, keySearch);
                    });
                    var v_status = "";
                    
                    data = dataget.data;
                    if(data.length > 0){
                        for (var i = 0; i < data.length; i++) {
                            if (data[i].active == 1) {
                                v_status = "Đang hoạt động";
                            }
                            else {v_status = "Không hoạt động";}

                            html_show += "<tr><td class='text-center'>"+(i + 1 + (GET_START_RECORD_NGHILC() * row))+"</td>";
                            html_show += "<td>"+data[i].school_type_name+"</td>";
                            html_show += "<td>"+v_status+"</td>";
                            html_show += "<td class='text-right'>"+formatDateTimes(data[i].create_date)+"</td>";
                            
                            if(check_Permission_Feature('2')){
                                html_show += "<td class='text-center'>";
                                html_show += "<button data='"+data[i].school_type_id+"' onclick='getLoaitruong("+data[i].school_type_id+");' class='btn btn-info btn-xs' id='editor_editss'><i class='glyphicon glyphicon-pencil'></i> Sửa</button> ";
                                html_show += "</td>";
                            }
                            if(check_Permission_Feature('3')){
                                html_show += "<td class='text-center'>"
                                html_show += "<button  onclick='deleteLoaitruong("+data[i].school_type_id+");'  class='btn btn-danger btn-xs editor_remove'><i class='glyphicon glyphicon-remove'></i> Xóa</button>";
                                html_show += "</td>";
                            }
                            html_show += "</tr>";
                        }
                        
                    } else {
                        html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
                    }
                    $('#dataSchoolType').html(html_show);
            },function(dataget){
                console.log("loaddataSchoolType");
                console.log(dataget);
            },"","","");
        };

    function insertLoaitruong(temp) {
        var objJson = JSON.stringify(temp);
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/loaitruong/insert',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                console.log(data);
                if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    
                    $('#txtNametype').val("");
                    $('#drSchoolTypeActive').val(1);
                    GET_INITIAL_NGHILC();
                    loaddataSchoolType($('#drPagingSchoolType').val(), $('#txtSearchSchoolType').val());
                }
                if (data['error'] !== "" && data['error'] !== undefined) {
                    utility.message("Thông báo",data['error'],null,3000,1);
                }
            }, error: function(data) {
            }
        });
    };

    function updateLoaitruong(temp) {
        var objJson = JSON.stringify(temp);
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/loaitruong/update',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    schooltype_id = "";
                    
                    $('#txtNametype').val("");
                    $('#drSchoolTypeActive').val(1);
                    $('#btnInsertSchoolType').html('Thêm mới');
                    $("#modalAddNew").modal("hide");
                    GET_INITIAL_NGHILC();
                    loaddataSchoolType($('#drPagingSchoolType').val(), $('#txtSearchSchoolType').val());
                }
                
                if (data['error'] != "" && data['error'] != undefined) {
                    utility.message("Thông báo",data['error'],null,3000,1);
                }
            }, error: function(data) {
            }
        });
    };

//------------------------------------------------Danh sách hộ nghèo----------------------------------------------------------------------
    function loaddataDSHN(row, keySearch) {
        $('#dataTableDSHN').html("<tr><td colspan='50' class='text-center'>Đang tải dữ liệu...</td></tr>");
        var html_show = "";
        var o = {
            start: (GET_START_RECORD_NGHILC()),
            limit : row,
            key: keySearch
        };
        PostToServer('/danh-muc/danhsachhongheo/loadDanhsachhongheo',o,function(dataget){
            SETUP_PAGING_NGHILC(dataget, function () {
                    loaddataDSHN(row, keySearch);
                });
                var v_status = "";
                
                data = dataget.data;
                if(data.length>0){
                    for (var i = 0; i < data.length; i++) {
                        html_show += "<tr><td class='text-center'>"+(i + 1 + (GET_START_RECORD_NGHILC() * row))+"</td>";
                        html_show += "<td>"+ConvertString(data[i].DShongheo_name)+"</td>";
                        html_show += "<td class='text-right'>"+ConvertString(data[i].DShongheo_birthday)+"</td>";
                        html_show += "<td>"+ConvertString(data[i].DShongheo_sex)+"</td>";
                        html_show += "<td>"+ConvertString(data[i].nationals_name)+"</td>";
                        if (parseInt(data[i].DShongheo_relationship) == 1) {
                            html_show += "<td>Chủ hộ</td>";
                        }
                        else {
                            html_show += "<td>Chưa rõ</td>";
                        }
                        html_show += "<td>"+ConvertString(data[i].DShongheo_index)+"</td>";
                        html_show += "<td>"+ConvertString(data[i].tenthon)+"</td>";
                        html_show += "<td>"+ConvertString(data[i].tenxa)+"</td>";
                        html_show += "<td>"+ConvertString(data[i].DShongheo_typename)+"</td>";

                        html_show += "<td class='text-center'>"
                        if(check_Permission_Feature('2')){
                            html_show += "<button onclick='getDSHN("+data[i].DShongheo_id+");' class='btn btn-info btn-xs' id='editor_editss'><i class='glyphicon glyphicon-pencil'></i> Sửa</button> ";
                        }
                        if(check_Permission_Feature('3')){
                            html_show += "<button onclick='deleteDSHN("+data[i].DShongheo_id+");' class='btn btn-danger btn-xs editor_remove'><i class='glyphicon glyphicon-remove'></i> Xóa</button>";
                        }
                        html_show += "</td></tr>";
                    }
                        
                } else {
                    html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
                }
                $('#dataTableDSHN').html(html_show);
        },function(dataget){
            console.log("loaddataDSHN");
            console.log(dataget);
        },"","","");
    };

    function loadDataDantoc(idchoise = 0){
        GetFromServer('/danh-muc/load/dan-toc',function(dataget){
            $('#sltNations').html("");
                var html_show = "";
                if(dataget.length >0){
                     
                    html_show += "<option value=''>-- Chọn dân tộc --</option>";
                    for (var i = 0; i < dataget.length; i++) {
                        html_show += "<option value='"+dataget[i].nationals_id+"'>"+dataget[i].nationals_name+"</option>";
                    }
                    $('#sltNations').html(html_show);
                }
                else{
                    $('#sltNations').html("<option value=''>-- Chưa có dân tộc --</option>");
                }

                if (idchoise > 0){
                    $('#sltNations').val(idchoise);
                }
        },function(dataget){
            console.log("loadDataDantoc");
            console.log(dataget);
        },"","","");
    };

    function loadDataSite(idchoise = 0){
        GetFromServer('/danh-muc/danhsachhongheo/getSite',function(data){
            var dataSite1 = data.SITE1;
                var dataSite2 = data.SITE2;
                $('#sltSite1').html("");
                var html_show = "";

                if (dataSite1.length > 0 && dataSite2.length > 0) {
                    html_show += "<option value=''>-- Chọn xã --</option>";
                    for (var i = 0; i < dataSite1.length; i++) {
                        html_show +="<optgroup label='"+dataSite1[i].site_name+"'>";
                        
                        for (var j = 0; j < dataSite2.length; j++) {
                            if (parseInt(dataSite1[i].site_id) == parseInt(dataSite2[j].site_parent_id)) {
                                html_show += "<option value='"+dataSite2[j].site_id+"'>"+dataSite2[j].site_name+"</option>";
                            }
                        }

                        html_show +="</optgroup>";
                    }

                    $('#sltSite1').html(html_show);

                    if (idchoise > 0){
                        $('#sltSite1').val(idchoise);
                    }
                }
                else {
                    $('#sltSite1').html("<option value=''>-- Không có xã nào --</option>");
                }
        },function(data){
            console.log("loadDataSite");
            console.log(data);
        },"","","");
    };



    function loadDataSiteByID(siteID, idchoise = 0){
        $('#sltSite2').html("<option value=''>Đang tải dữ liệu...</option>").selectpicker('refresh');
        GetFromServer('/danh-muc/danhsachhongheo/getSiteByID/' + siteID,function(data){
                var html_show = "";
                if (data.length > 0) {
                    html_show += "<option value=''>-- Chọn thôn --</option>";
                    for (var i = 0; i < data.length; i++) {
                        html_show += "<option value='"+data[i].site_id+"'>"+data[i].site_name+"</option>";
                    }

                    $('#sltSite2').html(html_show);

                    if (idchoise > 0){
                        $('#sltSite2').val(idchoise);
                    }

                    $('#sltSite2').removeAttr('disabled');
                }
                else {
                    $('#sltSite2').html("<option value='' selected>-- Không có thôn nào --</option>");
                    $('#sltSite2').attr('disabled','disabled');
                }
            $('#sltSite2').selectpicker('refresh');
        },function(data){
            console.log("loadDataSiteByID");
            console.log(data);
        },"","","");
    };

    function insertDSHN(temp) {
        PostToServer('/danh-muc/danhsachhongheo/insert',temp,function(data){
            if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    $('#txtName').val('');
                    $('#txtBirthday').val('');
                    $("#sltSex").selectpicker('val','');
                    $("#sltNations").selectpicker('val','');
                    $("#sltTypeName").selectpicker('val','');
                    $("#sltSite2").attr('disabled', 'disabled');
                    $("#sltSite2").selectpicker('val','');
                    $("#sltSite1").selectpicker('val','');
                    $('#txtIndex').val('');

                    $("#sltRelationShip").selectpicker('val','1');
                    dshn_id = "";
                    GET_INITIAL_NGHILC();
                    loaddataDSHN($('#drPagingDSHN').val(), $('#txtSearchDSHN').val());
                }
                if (data['error'] !== "" && data['error'] !== undefined) {
                    utility.message("Thông báo",data['error'],null,3000,1);
                }
        },function(data){
            console.log("insertDSHN");
            console.log(data);
        },"","","");

    };

    function updateDSHN(temp) {
        var objJson = JSON.stringify(temp);
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/danhsachhongheo/update',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    
                    dshn_id = "";
                    $('#btnInsertDSHN').html('Thêm mới');
                    $("#modalAddNewDSHN").modal("hide");
                    $('#txtIndex').val('');
                    GET_INITIAL_NGHILC();
                    loaddataDSHN($('#drPagingDSHN').val(), $('#txtSearchDSHN').val());
                }
                if (data['error'] != "" && data['error'] != undefined) {
                    utility.message("Thông báo",data['error'],null,3000,1);
                }
            }, error: function(data) {
            }
        });
    };

//------------------------------------------------Quản lý người nấu ăn--------------------------------------------------------------------
    function loadComboxTruongHocSingle(id,callback,idchoise = null,choose = 0) {
        GetFromServer('/danh-muc/load/truong-hoc',function(data){
            var dataget = data.truong;
                    $('#'+id).html("");

                    var html_show = "";
                                if(dataget.length > 0){
                                    html_show += "<option value=''>-- Chọn trường --</option>";
                                    for (var i = 0; i < dataget.length; i++) {
                                            if(idchoise != null){
                                                if(idchoise.split('-').length == 1 && parseInt(idchoise) != 0){
                                                    html_show += "<option value='"+dataget[i].schools_id+"' selected>"+dataget[i].schools_name+"</option>";
                                                }else{
                                                    html_show += "<option value='"+dataget[i].schools_id+"'>"+dataget[i].schools_name+"</option>";
                                                }
                                            }
                                       // }
                                    }
                                }    
                        $('#'+id).html(html_show);

                        if (choose > 0) {
                            $('#'+id).val(choose);
                        }
                    if(callback!=null){
                        callback();
                    }
            },function(data){
                console.log("danhmuc: loadComboxTruongHocSingle");
                console.log(data);
            },"","","");

        };
    

    function lapdanhsachDanhSachNauAn(objData) {
        var schools_id = $('#drSchoolTHCD').val();
        var year = $('#sltYear').val();
        PostToServerFormData('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/lapdanhsach',objData,
            function(data){
                if (data['success'] != "" && data['success'] != undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    GET_INITIAL_NGHILC();
                    loaddataNguoinauan($('#drPagingNguoinauan').val(), $('#txtSearchNgna').val());
                    $("#myModalLapDanhSachTHCD").modal("hide");
                }
                if (data['error'] != "" && data['error'] != undefined) {
                    GET_INITIAL_NGHILC();
                    loaddataNguoinauan($('#drPagingNguoinauan').val(), $('#txtSearchNgna').val());
                    utility.message("Thông báo",data['error'],null,3000,1);
                }
            },function(data){
                console.log("lapdanhsachDanhSachNauAn");
                console.log(data);
            },"btnInsertNGNA","loading","");
    };

    function validatePopupTongHopCheDo(){
        var messageValidate = "";
        var capnhan = $('#sltCapNhan').val();
        var chedo = $('#sltChedo').val();

        if (capnhan == null || capnhan == "") {
            messageValidate = "Vui lòng chọn cấp nhận!";
            return messageValidate;
        }

        if (chedo == null || chedo == "") {
            messageValidate = "Vui lòng chọn loại chế độ!";
            return messageValidate;
        }
        return messageValidate;
    }

    function loaddataNguoinauan(row, keySearch="") {
        var html_show = "";
        $('#dataTableNGNA').html("<tr><td colspan='50' class='text-center'>Đang tải dữ liệu...</td></tr>");
        var o = {
            start: (GET_START_RECORD_NGHILC()),
            limit : row,
            schools_id: $('#drSchoolTHCD').val(),
            units: $('#sltKhoiLopNA').val(),
            years: $('#sltYear').val(),
        };
        PostToServer('/danh-muc/nguoinauan/loadNguoinauan',o,function(dataget){
            SETUP_PAGING_NGHILC(dataget, function () {
                        loaddataNguoinauan(row, keySearch);
                    });
                    var v_status = "";
                    
                    data = dataget.data;
                    if(data.length>0){
                        var cl = '';
                        for (var i = 0; i < data.length; i++) {

                            html_show += "<tr><td class='text-center'>"+(i + 1 + (GET_START_RECORD_NGHILC() * row))+"</td>";
                            html_show += "<td>"+ConvertString(data[i].schools_name)+"</td>";
                            // html_show += "<td class='text-center'>"+ConvertString(data[i].unit_name)+"</td>";
                            html_show += "<td class='text-center'>"+ConvertString(data[i].NGNA_HK)+"</td>";
                            html_show += "<td class='text-right'>"+convertNumber(data[i].NGNA_amount)+"</td>";
                            html_show += "<td class='text-right'>"+convertNumber(data[i].NGNA_TW)+"</td>";
                            html_show += "<td class='text-right'>"+convertNumber(data[i].NGNA_DP)+"</td>";
                            html_show += "<td class='text-right'>"+convertNumber(data[i].NGNA_68)+"</td>";
                            html_show += "<td class='text-right'>"+formatter(data[i].TW)+"</td>";
                            html_show += "<td class='text-right'>"+formatter(data[i].DP)+"</td>";
                            html_show += "<td class='text-right'>"+formatter(convertNumber(data[i].TW) + convertNumber(data[i].DP))+"</td>";
                            if(parseInt(data[i].NGNA_Status) > 0){
                                html_show += "<td class='text-center'>Đã gửi lần "+data[i].NGNA_Status+"</td>";
                            }else{
                                html_show += "<td class='text-center'>-</td>";    
                            }
                            
                            html_show += "<td class='text-center'><button onclick='openPopupLapTHCD("+data[i].NGNA_id+","+data[i].NGNA_unit_id+")' class='btn btn-success btn-xs' id='editor_editss'>Lập công văn</button></td>";
                            if(check_Permission_Feature('2')){
                                html_show += "<td class='text-center'>";
                                html_show += "<button onclick='getNGNA("+data[i].NGNA_id+");' class='btn btn-info btn-xs' id='editor_editss'><i class='glyphicon glyphicon-pencil'></i> Sửa</button>";
                                html_show += "</td>";
                            }
                            if(check_Permission_Feature('3')){
                                html_show += "<td class='text-center'>";
                                html_show += "<button onclick='deleteNGNA("+data[i].NGNA_id+");' class='btn btn-danger btn-xs editor_remove'><i class='glyphicon glyphicon-remove'></i> Xóa</button>";
                                html_show += "</td>";
                            }
                            
                            
                            html_show += "</tr>";
                        }
                        
                    } else {
                        html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
                    }
                    $('#dataTableNGNA').html(html_show);
            },function(dataget){
                console.log("loaddataNguoinauan");
                console.log(dataget);
            },"","","");
        };

    function insertNGNA(temp) {
        PostToServer('/danh-muc/nguoinauan/insert',temp,function(data){
            if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    $('#txtHSBT').val(0);
                    $('#txtNVTW').val(0);
                    $('#txtNVDP').val(0);
                    $('#txtNV68').val(0);
                    $('#txtHSTW').val(0);
                    $('#txtHSDP').val(0);
                    ngna_id = "";
                    GET_INITIAL_NGHILC();
                    loaddataNguoinauan($('#drPagingNguoinauan').val(), $('#txtSearchNgna').val());
                }
                if (data['error'] !== "" && data['error'] !== undefined) {
                    utility.message("Thông báo",data['error'],null,3000,1);
                }
        },function(data){
            console.log("insertNGNA");
            console.log(data);
        },"","","");
    };

    function updateNGNA(temp) {
        var objJson = JSON.stringify(temp);
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/nguoinauan/update',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    $('#txtHSBT').val(0);
                    $('#txtNVTW').val(0);
                    $('#txtNVDP').val(0);
                    $('#txtNV68').val(0);
                    $('#txtHSTW').val(0);
                    $('#txtHSDP').val(0);
                    ngna_id = "";
                    $("#modalAddNew").modal("hide");
                    GET_INITIAL_NGHILC();
                    loaddataNguoinauan($('#drPagingNguoinauan').val(), $('#txtSearchNgna').val());
                }
                if (data['error'] != "" && data['error'] != undefined) {
                    utility.message("Thông báo",data['error'],null,3000,1);
                }
            }, error: function(data) {
            }
        });
    };

//Nhóm đối tượng----------------------------------------------------------------------------------------
    function loaddataGroup(row, keySearch) {
        var html_show = "";
        var o = {
            start: (GET_START_RECORD_NGHILC()),
            limit: row,
            key: keySearch
        };
            $.ajax({
                type: "POST",
                url: '/danh-muc/nhomdoituong/loadNhomDoiTuong',
                data: JSON.stringify(o),
                dataType: 'json',
                contentType: 'application/json; charset=utf-8',
                 headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                success: function(dataget) {

                    SETUP_PAGING_NGHILC(dataget, function () {
                        loaddataGroup(row, keySearch);
                    });
                    var v_status = "";
                    $('#dataGroup').html("");
                    data = dataget.data;
                    //console.log(data);
                    if(data.length>0){
                        for (var i = 0; i < data.length; i++) {
                            //console.log(data[i].group_active);
                            if (data[i].group_active == 1) {
                                v_status = "Đang hoạt động";
                            }
                            else {v_status = "Không hoạt động";}

                            html_show += "<tr><td class='text-center'>"+(i + 1 + (GET_START_RECORD_NGHILC() * row))+"</td>";
                            // html_show += "<td>"+data[i].group_code+"</td>";
                            html_show += "<td>"+data[i].group_name+"</td>";
                            html_show += "<td>"+v_status+"</td>";
                            html_show += "<td>"+formatDateTimes(data[i].updated_at)+"</td>";
                            
                            if(check_Permission_Feature('2')){
                                html_show += "<td class='text-center'><button data='"+data[i].group_id+"' onclick='getGroup("+data[i].group_id+");' class='btn btn-info btn-xs' id='editor_editss'><i class='glyphicon glyphicon-pencil'></i> Sửa</button></td>";
                            }
                            if(check_Permission_Feature('3')){
                                html_show += "<td class='text-center'><button  onclick='deleteGroup("+data[i].group_id+");'  class='btn btn-danger btn-xs editor_remove'><i class='glyphicon glyphicon-remove'></i> Xóa</button></td>";
                            }
                            html_show += "</tr>";
                        }
                        
                    } else {
                        html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
                    }
                    $('#dataGroup').html(html_show);
                }, error: function(dataget) {

                }
            });
        };

    function insertNhomDoiTuong(temp) {
        var objJson = JSON.stringify(temp);
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/nhomdoituong/insert',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    
                    $('#txtGroupName').val("");
                    $('#drGroupActive').val(1);
                    loaddataGroup($('#drPagingGroup').val(), $('#txtSearchGroup').val());
                }
                if (data['error'] != "" && data['error'] != undefined) {
                    utility.message("Thông báo",data['error'],null,3000,1);
                }
            }, error: function(data) {
            }
        });
    };

    function getNhomDoiTuongbyID(temp) {
        var objJson = JSON.stringify(temp);
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/nhomdoituong/getbygroupid',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                group_id = data[0]['group_id'];
                // $('#txtGroupCode').val(data[0]['group_code']);
                $('#txtGroupName').val(data[0]['group_name']);
                $('#drGroupActive').val(data[0]['group_active']);
            }, error: function(data) {
            }
        });
    };

    function updateNhomDoiTuong(temp) {
        var objJson = JSON.stringify(temp);
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/nhomdoituong/update',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    
                    $('#txtGroupName').val("");
                    $('#drGroupActive').val(1);
                    // $('#txtGroupCode').attr('readonly', false);
                    $('#btnInsertGroup').html('Thêm mới');
                    $("#modalAddNew").modal("hide");
                    loaddataGroup($('#drPagingGroup').val(), $('#txtSearchGroup').val());
                    group_id = "";
                }
                if (data['error'] != "" && data['error'] != undefined) {
                    utility.message("Thông báo",data['error'],null,3000,1);
                }
            }, error: function(data) {
            }
        });
    };

    function deleteNhomDoiTuong(temp) {
        var objJson = JSON.stringify(temp);
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/nhomdoituong/delete',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    group_id = "";
                    // $('#txtGroupCode').attr('readonly', false);
                    $('#btnInsertGroup').html('Thêm mới');
                    loaddataGroup($('#drPagingGroup').val(), $('#txtSearchGroup').val());
                }
                if (data['error'] != "" && data['error'] != undefined) {
                    utility.message("Thông báo",data['error'],null,3000,1);
                }
            }, error: function(data) {
            }
        });
    };

//Khối----------------------------------------------------------------------------------------
    function loaddataUnit(row, keySearch) {
        var html_show = "";
        $('#dataUnit').html("<tr><td colspan='50' class='text-center'>Đang tải dữ liệu...</td></tr>");
        var o = {
            start: (GET_START_RECORD_NGHILC()),
            limit : row,
            key: keySearch
        };
        PostToServer('/danh-muc/khoi/loadKhoi',o,function(dataget){
            SETUP_PAGING_NGHILC(dataget, function () {
                        loaddataUnit(row, keySearch);
                    });
                    var v_status = "";
                    
                    data = dataget.data;
                    if(data.length>0){
                        for (var i = 0; i < data.length; i++) {
                            if (data[i].unit_active == 1) {
                                v_status = "Đang hoạt động";
                            }
                            else {v_status = "Không hoạt động";}

                            html_show += "<tr><td class='text-center' style='vertical-align:middle'>"+(i + 1 + (GET_START_RECORD_NGHILC() * row))+"</td>";
                            // html_show += "<td class='text-left'>"+ConvertString(data[i].schools_name)+"</td>";
                            html_show += "<td style='vertical-align:middle'>"+ConvertString(data[i].unit_name)+"</td>";
                            html_show += "<td class='text-center' style='vertical-align:middle'>"+v_status+"</td>";
                            html_show += "<td class='text-center' style='vertical-align:middle'>"+formatDateTimes(data[i].updated_at)+"</td>";
                           // html_show += "<td>"
                            if(check_Permission_Feature('2')){
                                html_show += "<td class='text-center' style='vertical-align:middle'><button data='"+data[i].unit_id+"' onclick='getUnit("+data[i].unit_id+");' class='btn btn-info btn-xs' id='editor_editss'><i class='glyphicon glyphicon-pencil'></i> Sửa</button></td>";
                            }
                            if(check_Permission_Feature('3')){
                                html_show += "<td class='text-center' style='vertical-align:middle'><button  onclick='deleteUnit("+data[i].unit_id+");'  class='btn btn-danger btn-xs editor_remove'><i class='glyphicon glyphicon-remove'></i> Xóa</button></td>";
                            }
                            html_show += "</tr>";
                        }
                        
                    } else {
                        html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
                    }
                    $('#dataUnit').html(html_show);
            },function(dataget){
                console.log("loaddataUnit");
                console.log(dataget);
            },"","","");
        };

    function insertKhoi(temp) {
        var objJson = JSON.stringify(temp);
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/khoi/insert',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    $('#txtUnitCode').val("");
                    $('#txtUnitName').val("");
                    $('#drUnitActive').val(1);
                    loaddataUnit($('#drPagingUnit').val(), $('#txtSearchUnit').val());
                }
                if (data['error'] != "" && data['error'] != undefined) {
                    utility.message("Thông báo",data['error'],null,3000);
                }
            }, error: function(data) {
            }
        });
    };

    function getKhoibyID(temp) {
        var objJson = JSON.stringify(temp);
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/khoi/getbyunitid',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                unit_id = data[0]['unit_id'];
                $('#txtUnitCode').val(data[0]['unit_code']);
                $('#txtUnitName').val(data[0]['unit_name']);
                $('#drUnitActive').val(data[0]['unit_active']);
            }, error: function(data) {
            }
        });
    };

    function updateKhoi(temp) {
        var objJson = JSON.stringify(temp);
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/khoi/update',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    unit_id = "";
                    $('#txtUnitCode').val("");
                    $('#txtUnitName').val("");
                    $('#drUnitActive').val(1);
                    $('#btnInsertUnit').html('Thêm mới');
                    $("#modalAddNew").modal("hide");
                    loaddataUnit($('#drPagingUnit').val(), $('#txtSearchUnit').val());
                }
                
                if (data['error'] != "" && data['error'] != undefined) {
                    utility.message("Thông báo",data['error'],null,3000);
                }
            }, error: function(data) {
            }
        });
    };

    function deleteKhoi(temp) {
        var objJson = JSON.stringify(temp);
        alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/khoi/delete',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                alert(data);
                if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    unit_id = "";
                    $('#btnInsertUnit').html('Thêm mới');
                }
                
                if (data['error'] != "" && data['error'] != undefined) {
                    utility.message("Thông báo",data['error'],null,3000);
                }
            }, error: function(data) {
            }
        });
    };

//Dân tộc----------------------------------------------------------------------------------------

    function loaddataNation(row, keySearch) {
        $('#dataNation').html("<tr><td colspan='50' class='text-center'>Đang tải dữ liệu...</td></tr>");
        var html_show = "";
        var o = {
            start: (GET_START_RECORD_NGHILC()),
            limit : row,
            key: keySearch
        };
        PostToServer('/danh-muc/dantoc/loadDantoc',o,function(dataget){
            SETUP_PAGING_NGHILC(dataget, function () {
                        loaddataNation(row, keySearch);
                    });
                    var v_status = "";
                    
                    data = dataget.data;
                    if(data.length>0){
                        for (var i = 0; i < data.length; i++) {
                            if (data[i].nationals_active == 1) {
                                v_status = "Đang hoạt động";
                            }
                            else {v_status = "Không hoạt động";}

                            html_show += "<tr><td class='text-center'>"+(i + 1 + (GET_START_RECORD_NGHILC() * row))+"</td>";
                            // html_show += "<td>"+data[i].nationals_code+"</td>";
                            html_show += "<td>"+data[i].nationals_name+"</td>";
                            if (parseInt(data[i].nationals_type) > 0) {
                                html_show += "<td>Là dân tộc thiểu số</td>";
                            }
                            else {
                                html_show += "<td>Không là dân tộc thiểu số</td>";
                            }
                            html_show += "<td>"+v_status+"</td>";
                            html_show += "<td>"+formatDateTimes(data[i].updated_at)+"</td>";
                            
                            if(check_Permission_Feature('2')){
                                html_show += "<td class='text-center'><button data='"+data[i].nationals_id+"' onclick='getNation("+data[i].nationals_id+");' class='btn btn-info btn-xs' id='editor_editss'><i class='glyphicon glyphicon-pencil'></i> Sửa</button> ";
                                html_show += "</td>";
                            }
                            if(check_Permission_Feature('3')){
                                html_show += "<td class='text-center'><button  onclick='deleteNation("+data[i].nationals_id+");'  class='btn btn-danger btn-xs editor_remove'><i class='glyphicon glyphicon-remove'></i> Xóa</button>";
                                html_show += "</td>";
                            }
                            html_show += "</tr>";
                        }
                        
                    } else {
                        html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
                    }
                    $('#dataNation').html(html_show);
            },function(dataget){
                console.log("loaddataNation");
                console.log(dataget);
            },"","","");
        };

    function insertDantoc(temp) {
        var objJson = JSON.stringify(temp);
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/dantoc/insert',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    nation_id = "";
                    // $('#txtNationCode').val("");
                    $('#txtNationName').val("");
                    $('#drNationActive').val(1);
                    loaddataNation($('#drPagingNation').val(), $('#txtSearchNational').val());
                    $('#cbxType').prop('checked', false);
                }
                if (data['error'] != "" && data['error'] != undefined) {
                    utility.message("Thông báo",data['error'],null,3000);
                }
            }, error: function(data) {
            }
        });
    };

    function getDantocbyID(temp) {
        var objJson = JSON.stringify(temp);
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/dantoc/getbynationid',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                //console.log(data);
                nation_id = data[0]['nationals_id'];
                // $('#txtNationCode').val(data[0]['nationals_code']);
                $('#txtNationName').val(data[0]['nationals_name']);
                $('#drNationActive').val(data[0]['nationals_active']);
            }, error: function(data) {
            }
        });
    };

    function updateDantoc(temp) {
        var objJson = JSON.stringify(temp);
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/dantoc/update',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    // $('#txtNationCode').val("");
                    $('#txtNationName').val("");
                    $('#drNationActive').val(1);
                    nation_id = "";
                    $('#btnInsertNation').html('Thêm mới');
                    // $('#txtNationCode').attr('readonly', false);
                    $("#modalAddNew").modal("hide");
                    loaddataNation($('#drPagingNation').val(), $('#txtSearchNational').val());
                }
                if (data['error'] != "" && data['error'] != undefined) {
                    utility.message("Thông báo",data['error'],null,3000);
                }
            }, error: function(data) {
            }
        });
    };

    function deleteDantoc(temp) {
        var objJson = JSON.stringify(temp);
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/dantoc/delete',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                //alert(data);
                if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    nation_id = "";
                    $('#btnInsertNation').html('Thêm mới');
                    // $('#txtNationCode').attr('readonly', false);
                }
                
                if (data['error'] != "" && data['error'] != undefined) {
                    utility.message("Thông báo",data['error'],null,3000);
                }
            }, error: function(data) {
            }
        });
    };

//Đối tượng----------------------------------------------------------------------------------------------------------------------
    function loaddataSubject(row, keySearch) {
        $('#dataSubject').html("<tr><td colspan='50' class='text-center'>Đang tải dữ liệu...</td></tr>");
        var html_show = "";
        var o = {
            start: (GET_START_RECORD_NGHILC()),
            limit : row,
            key: keySearch
        };
        PostToServer('/danh-muc/doituong/loadDoiTuong',o,function(dataget){
            SETUP_PAGING_NGHILC(dataget, function () {
                        loaddataSubject(row, keySearch);
                    });
                    
                    
                    data = dataget.data;
                 //   console.log(data);
                    if(data.length > 0){
                        var v_status = "";
                        var v_active = -1;
                        // var subject_code = "";
                        var group_name = "";
                        for (var i = 0; i < data.length; i++) {
                            // if (subject_code == data[i].subject_code) {
                            //     group_name =  group_name + ", " + data[i].group_name;
                            // }
                           // else { group_name =  data[i].group_name; }
                           group_name =  data[i].group_name;
                            // subject_code = data[i].subject_code;

                            v_active = data[i].subject_active;
                            if (v_active == 1) {
                                v_status = "Đang hoạt động";
                            }
                            else {v_status = "Không hoạt động";}

                            html_show += "<tr><td class='text-center'>"+(i + 1 + (GET_START_RECORD_NGHILC() * row))+"</td>";
                            // html_show += "<td>"+subject_code+"</td>";
                            html_show += "<td>"+data[i].subject_name+"</td>";
                            html_show += "<td>"+group_name+"</td>";
                            html_show += "<td>"+v_status+"</td>";
                            html_show += "<td>"+formatDateTimes(data[i].updated_at)+"</td>";
                            if(check_Permission_Feature('2')){
                                html_show += "<td><button data='"+data[i].subject_id+"' onclick='getSubject("+data[i].subject_id+");' class='btn btn-info btn-xs' id='editor_editss'><i class='glyphicon glyphicon-pencil'></i> Sửa</button></td>";
                            }
                            if(check_Permission_Feature('3')){
                                html_show += "<td><button  onclick='deleteSubject("+data[i].subject_id+");'  class='btn btn-danger btn-xs editor_remove'><i class='glyphicon glyphicon-remove'></i> Xóa</button>";
                            }
                            html_show += "</td></tr>";
                        }
                        
                    } else {
                        html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
                    }
                    $('#dataSubject').html(html_show);
            },function(dataget){
                console.log("loaddataSubject");
                console.log(dataget);
            },"","","");
        };

    function loadComboNhomDoiTuong() {
        $.ajax({
            type: "get",
            url: '/danh-muc/load/nhom-doi-tuong',
            success: function(data) {
                //console.log(data);
                $('#drGroupSubject').html("");
                var html_show = "";
                if(data.length >0){
                    //html_show += "<option value=''>---Chọn nhóm đối tượng---</option>";
                    for (var i = data.length - 1; i >= 0; i--) {
                        html_show += "<option value='"+data[i].group_id+"'>"+data[i].group_name+"</option>";
                    }
                    $('#drGroupSubject').html(html_show);
                }else {
                    $('#drGroupSubject').html("<option value=''>---Chưa có chế độ---</option>");
                }
            }, error: function(data) {
            }
        });
    };

    var expanded = false;

    function showCheckboxes() {
        var checkboxes = document.getElementById("checkboxes");
        if (!expanded) {
            checkboxes.style.display = "block";
            expanded = true;
        } else {
            checkboxes.style.display = "none";
            expanded = false;
        }
    }

    function popupConfirmDeleteSubject(){
        $("#modalDeleteSubject").modal("show");
    }

    function insertupdateDoiTuong() {
        messageValidate = "";
        validateInput("SUBJECT");
        if (messageValidate !== "") {
            utility.messagehide("group_message", messageValidate, 1, 5000);
            return;
        }

        var arrSubID = [];
        // var $el = $(".multiselect-container");
        // $el.find('li.active input').each(function(){
        //     arrSubID.push({value:$(this).val()});
        // });
        $.each($('#drGroupSubject').val(), function( index, value ) {
          //str += value + ",";
          arrSubID.push({value:value});
        });
        // var arrData = "";
        // var arrGroupID = data['arrGroupID'];
        // for (var i = 0; i < arrGroupID.length; i++) {
        //     arrData += (arrGroupID[i]['subject_history_group_id']) + ",";    
        // }
        // var item = arrData.split(",");

        var objData = {
            SUBJECTID: subject_id,
            // SUBJECTCODE: $('#txtSubjectCode').val(),
            SUBJECTNAME: $('#txtSubjectName').val(),
            SUBJECTACTIVE: $('#drSubjectActive').val(),
            ARRGROUPID: arrSubID
        };
      //  var objJson = JSON.stringify(objData);
        //alert(objJson);
        var url_part = '';
        if (subject_id !== "" && subject_id > 0) {url_part = '/danh-muc/doituong/update'}
        else { url_part = '/danh-muc/doituong/insert'; }
        PostToServer(url_part,objData,function(data){
            if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    $('#drGroupSubject').selectpicker('deselectAll');
                    $('#drGroupSubject').selectpicker('refresh');
                    $('#txtSubjectName').val("");
                    $('#drSubjectActive').selectpicker('val',1);
                    subject_id = "";
                    $('#btnInsertSubject').html('Thêm mới');
                    GET_INITIAL_NGHILC();
                    loaddataSubject($('#drPagingSubject').val(), $('#txtSearchSubject').val());
                }
                if (data['error'] != "" && data['error'] != undefined) {
                    utility.message("Thông báo",data['error'],null,3000);
                }
        },function(data){
            console.log("insertupdateDoiTuong");
            console.log(data);
        },"","","");
    };

    function getDoituongbyID() {
        var objData = {
            SUBJECTID: subject_id
        };
        var objJson = JSON.stringify(objData);
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/doituong/getbysubjectid',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                subject_id = data[0]['subject_id'];
                // $('#txtSubjectCode').val(data[0]['subject_code']);
                $('#txtSubjectName').val(data[0]['subject_name']);
                $('#drSubjectActive').val(data[0]['subject_active']);
            }, error: function(data) {
            }
        });
    };

    function getNhombyDoituongID(callback) {
        var objData = {
            SUBJECTID: subject_id
        };
        var objJson = JSON.stringify(objData);
        //alert(objJson);
        return $.ajax({
            type: "POST",
            url:'/danh-muc/doituong/getlistgroupbysubjectid',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                //Set checked Checkbox Subject-------------------------------------------------------------------------------------
                //console.log(data);
                for (var i = 0; i < data.length; i++) {
                    $('input[type=checkbox]').each(function () {
                        if (data[i]['subject_history_group_id'] == $(this).val()) {
                            $(this).attr('checked', 'checked');
                            //console.log($(this).val());
                        }
                    });
                }

                if(callback != null){
                    callback(data);
                }
            }, error: function(data) {
            }
        });
    };

    function updateDoiTuong() {
        popupAddnewSubject();
        var arrGroupID = [];
        $(':checkbox:checked').each(function(i){
            arrGroupID[i] = $(this).val();
        });

        var objData = {
            SUBJECTID: subject_id,
            SUBJECTNAME: $('#txtSubjectName').val(),
            SUBJECTACTIVE: $('#drSubjectActive').val(),
            ARRGROUPID: arrGroupID
        };
        var objJson = JSON.stringify(objData);
        alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/doituong/update',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    $('#drGroupSubject').multiselect({
                        nonSelectedText:'--- Chọn chế độ ---'
                    });
                    $("#drGroupSubject").val("").multiselect("clearSelection");
                    $('#txtSubjectName').val("");
                    $('#drSubjectActive').val(1);
                    subject_id = "";
                    loaddataSubject($('#drPagingSubject').val(), $('#txtSearchSubject').val());
                }
                if (data['error'] != "" && data['error'] != undefined) {
                    utility.message("Thông báo",data['error'],null,3000);
                }
            }, error: function(data) {
            }
        });
    };

    function deleteDoiTuong() {
        var objData = {
            SUBJECTID: subject_id
        };
        var objJson = JSON.stringify(objData);
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/doituong/delete',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    subject_id = "";
                    $('#btnInsertSubject').html('Thêm mới');
                }
                
                if (data['error'] != "" && data['error'] != undefined) {
                    utility.message("Thông báo",data['error'],null,3000);
                }
            }, error: function(data) {
            }
        });
    };

//Trường----------------------------------------------------------------------------------------
    function loadSchoolType(){
        $.ajax({
            type: "get",
            url:'/danh-muc/truong/loadLoaitruong',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                var html_show = "";
                $("#drSchoolType").html("");
                if (data.length > 0) {
                    html_show += "<option value=''>--- Chọn loại trường ---</option>";
                    for (var i = 0; i < data.length; i++) {
                        html_show += "<option value='"+data[i].school_type_id+"'>"+data[i].school_type_name+"</option>";
                    }

                    $("#drSchoolType").html(html_show);
                }
                else {
                    $("#drSchoolType").html("<option value=''>--- Chưa có loại trường ---</option>");
                }
            }, error: function(data) {
            }
        });
    }

    function loaddataSchool(row, keySearch) {
        $('#dataSchool').html("<tr><td colspan='50' class='text-center'>Đang tải dữ liệu...</td></tr>");
        var html_show = "";
        var o = {
            start: (GET_START_RECORD_NGHILC()),
            limit : row,
            key: keySearch
        };
        PostToServer('/danh-muc/truong/loadTruong',o,function(dataget){
            SETUP_PAGING_NGHILC(dataget, function () {
                        loaddataSchool(row, keySearch);
                    });
                    var v_status = "";
                    
                    data = dataget.data;
                    if(data.length>0){
                        for (var i = 0; i < data.length; i++) {
                            if (data[i].schools_active == 1) {
                                v_status = "Đang hoạt động";
                            }
                            else {v_status = "Không hoạt động";}

                            html_show += "<tr><td class='text-center'>"+(i + 1 + (GET_START_RECORD_NGHILC() * row))+"</td>";
                            // html_show += "<td>"+ConvertString(data[i].schools_code)+"</td>";
                            html_show += "<td>"+ConvertString(data[i].schools_name)+"</td>";
                            // html_show += "<td>"+ConvertString(data[i].unit_name)+"</td>";
                            html_show += "<td>"+ConvertString(data[i].school_type_name)+"</td>";
                            html_show += "<td class='text-right'>"+formatDates(data[i].type_his_startdate)+"</td>";
                            html_show += "<td class='text-right'>"+formatDates(data[i].type_his_enddate)+"</td>";
                            html_show += "<td>"+v_status+"</td>";
                            html_show += "<td class='text-right'>"+formatDateTimes(data[i].type_his_createdate)+"</td>";
                            
                            if(check_Permission_Feature('2')){
                                html_show += "<td class='text-center'>";
                                html_show += "<button data='"+data[i].schools_id+"' onclick='getSchool("+data[i].schools_id+", "+data[i].type_his_id+", 1);' class='btn btn-info btn-xs' id='editor_editss'><i class='glyphicon glyphicon-pencil'></i> Sửa</button> ";
                                html_show += "</td>";
                                if (data[i].type_his_enddate === null || data[i].type_his_enddate === "") {
                                    html_show += "<td class='text-center'>";
                                    html_show += "<button data='"+data[i].schools_id+"' onclick='getSchool("+data[i].schools_id+", "+data[i].type_his_id+");' class='btn btn-info btn-xs' id='editor_editss'><i class='glyphicon glyphicon-pencil'></i> Thay đổi</button> ";
                                    html_show += "</td>";
                                }
                                else {
                                    html_show += "<td class='text-center'>";
                                    html_show += "</td>";
                                }
                            }
                            if(check_Permission_Feature('3')){
                                if (data[i].type_his_enddate === null || data[i].type_his_enddate === "") {
                                    html_show += "<td class='text-center'>";
                                    html_show += "<button  onclick='deleteSchool("+data[i].schools_id+", "+data[i].type_his_id+");'  class='btn btn-danger btn-xs editor_remove'><i class='glyphicon glyphicon-remove'></i> Xóa</button>";
                                    html_show += "</td>";
                                }
                                else {
                                    html_show += "<td class='text-center'>";
                                    html_show += "</td>";
                                }
                            }
                            html_show += "</tr>";
                        }
                        
                    } else {
                        html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
                    }
                    $('#dataSchool').html(html_show);
            },function(dataget){
                console.log("loaddataSchool");
                console.log(dataget);
            },"","","");
        };

    function insertTruong(temp) {
        PostToServer('/danh-muc/truong/insert',temp,function(data){
            if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    // $('#txtSchoolCode').val("");
                    $('#txtSchoolName').val("");
                    $('#txtStartDate').val("");
                    $('#drSchoolActive').val(1);
                    GET_INITIAL_NGHILC();
                    loaddataSchool($('#drPagingSchool').val(), $('#txtSearchSchool').val());
                }
                if (data['error'] !== "" && data['error'] !== undefined) {
                    utility.message("Thông báo",data['error'],null,3000,1);
                }
        },function(data){
            console.log("insertTruong");
            console.log(data);
        },"","","");
    };

    function getTruongbyID(temp) {
        PostToServer('/danh-muc/truong/getbyschoolid',temp,function(data){
            school_id = data[0]['schools_id'];
                // $('#txtSchoolCode').val(data[0]['schools_code']);
                $('#txtSchoolName').val(data[0]['schools_name']);                
                // $('#drUnit').val(data[0]['schools_unit_id']);
                $('#drSchoolActive').val(data[0]['schools_active']);
        },function(data){
            console.log("getTruongbyID");
            console.log(data);
        },"","","");
    };

    function updateTruong(temp) {
        PostToServer('/danh-muc/truong/update',temp,function(data){
            if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    // $('#txtSchoolCode').val("");
                    $('#txtSchoolName').val("");
                    // $('#drUnit').val("");
                    $('#drSchoolActive').val(1);
                    school_id = "";
                    update_type = 0;
                    update_type = 0;
                    school_his_id = 0;
                    $("#modalAddNew").modal("hide");
                    $('#btnInsertSchool').html('Thêm mới');
                    GET_INITIAL_NGHILC();
                    loaddataSchool($('#drPagingSchool').val(), $('#txtSearchSchool').val());
                }
                if (data['error'] !== "" && data['error'] !== undefined) {
                    utility.message("Thông báo",data['error'],null,3000,1);
                }
        },function(data){
            console.log("updateTruong");
            console.log(data);
        },"","","");
    };


//Lớp----------------------------------------------------------------------------------------
    function loaddataClass(row, keySearch) {
        $('#dataClass').html("<tr><td colspan='50' class='text-center'>Đang tải dữ liệu...</td></tr>");
        var html_show = "";
        var o = {
            start: (GET_START_RECORD_NGHILC()),
            limit : row,
            key: keySearch
        };
         PostToServer('/danh-muc/lop/loadLop',o,function(dataget){
            SETUP_PAGING_NGHILC(dataget, function () {
                        loaddataClass(row, keySearch);
                    });
                    var v_status = "";
                    $('#dataClass').html("");
                    data = dataget.data;
                    if(data.length>0){
                        for (var i = 0; i < data.length; i++) {
                            if (data[i].class_active == 1) {
                                v_status = "Đang hoạt động";
                            }
                            else {v_status = "Không hoạt động";}

                            html_show += "<tr><td class='text-center'>"+(i + 1 + (GET_START_RECORD_NGHILC() * row))+"</td>";
                            // html_show += "<td>"+data[i].class_code+"</td>";
                            html_show += "<td>"+ConvertString(data[i].schools_name)+"</td>";
                            html_show += "<td>"+ConvertString(data[i].unit_name)+"</td>";
                            html_show += "<td>"+ConvertString(data[i].level_name)+"</td>";
                            html_show += "<td>"+ConvertString(data[i].class_name)+"</td>";
                            html_show += "<td>"+ConvertString(data[i].name)+"</td>";
                            html_show += "<td>"+v_status+"</td>";
                            html_show += "<td class='text-right'>"+formatDateTimes(data[i].updated_at)+"</td>";
                            html_show += "<td class='text-center'>"
                            if(check_Permission_Feature('2')){
                                html_show += "<button data='"+data[i].class_id+"' onclick='getClass("+data[i].class_id+");' class='btn btn-info btn-xs' id='editor_editss'><i class='glyphicon glyphicon-pencil'></i> Sửa</button> ";
                            }
                            if(check_Permission_Feature('3')){
                                html_show += "<button  onclick='deleteClass("+data[i].class_id+");'  class='btn btn-danger btn-xs editor_remove'><i class='glyphicon glyphicon-remove'></i> Xóa</button>";
                            }
                            html_show += "</td></tr>";
                        }
                        
                    } else {
                        html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
                    }
                    $('#dataClass').html(html_show);
             },function(dataget){
                console.log("loaddataClass");
                console.log(dataget);
             },"btnInsertClass","","");
        };

    function getUnitAll(idchoise = 0,callback=null) {
        $('#sltKhoiDt').html("<option value=''>Đang tải dữ liệu...</option>").selectpicker('refresh');
        PostToServer('/danh-muc/lop/getUnitAll',{},function(data){
                var html_show = "";
                if (data.length > 0) {
                    html_show += "<option value=''>-- Chọn khối --</option>";
                    for (var i = 0; i < data.length; i++) {
                        if(idchoise > 0 && data[i].unit_id === idchoise){
                            html_show += "<option value='"+data[i].unit_id+"' selected>"+data[i].unit_name+"</option>";
                        }else{
                            html_show += "<option value='"+data[i].unit_id+"'>"+data[i].unit_name+"</option>";
                        }
                        
                    }
                    $('#sltKhoiDt').html(html_show);
                }
                else {
                    $('#sltKhoiDt').html("<option value=''>-- Chưa có khối --</option>");
                }
                if(callback!=null){
                   callback();
                }
            $('#sltKhoiDt').selectpicker('refresh');
        },function(data){
            console.log("getUnitAll");
            console.log(data);
        },"btnInsertClass","","");
    };

    function getLevelbyUnitID(objData, idchoise = 0,callback=null) {
        var objJson ={UNITID: objData};
        $('#drLevel').html("<option value=''>Đang tải dữ liệu...</option>").selectpicker('refresh');
        PostToServer('/danh-muc/lop/getLevelbyUnitID',objJson,function(data){

                var html_show = "";
                if (data.length > 0) {
                    html_show += "<option value=''>-- Chọn khối lớp --</option>";
                    for (var i = 0; i < data.length; i++) {
                        if(idchoise > 0 && data[i].level_id === idchoise){
                            html_show += "<option value='"+data[i].level_id+"' selected>"+data[i].level_name+"</option>";
                        }else{
                            html_show += "<option value='"+data[i].level_id+"'>"+data[i].level_name+"</option>";    
                        }
                        
                    }
                    $('#drLevel').html(html_show);
                }
                else {
                    $('#drLevel').html("<option value=''>-- Chưa có khối lớp --</option>");
                }
                $('#drLevel').selectpicker('refresh');
                if(callback!=null){
                    callback();
                }
                
        },function(data){
            console.log("getLevelbyUnitID");
            console.log(data);
        },"btnInsertClass","","");
    };

    function insertLop(temp) {
       // var objJson = JSON.stringify(temp);
        PostToServer('/danh-muc/lop/insert',temp,function(data){
            if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    $('#txtClassCode').val("");
                    $('#txtClassName').val("");
                    $("#sltTruongDt").selectpicker('refresh');
                    $('#sltKhoiDt').selectpicker('val','');
                    $('#drLevel').selectpicker('val','');
                    $('#drClassActive').val(1);
                    $("#drLevel").attr("disabled", true);
                    loaddataClass($('#drPagingClass').val(), $('#txtSearchClass').val());
                    $('#modalAddNew').on('shown.bs.modal', function (e) {
                      $('#txtClassName').focus();
                    }).modal('show');
                }
                if (data['error'] !== "" && data['error'] !== undefined) {
                    utility.message("Thông báo",data['error'],null,3000);
                }
        },function(data){
            console.log("insertLop");
            console.log(data);
        },"btnInsertClass","","")

    };

    function updateLop(temp) {
        PostToServer('/danh-muc/lop/update',temp,function(data){
            if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    class_id = "";
                    $('#txtClassCode').attr('readonly', false);
                    $('#txtClassCode').val("");
                    $('#txtClassName').val("");
                    $("#sltTruongDt").selectpicker('refresh');
                    $('#sltKhoiDt').selectpicker('val','');
                    $('#drLevel').selectpicker('val','');
                    $('#drClassActive').val(1);
                    $('#btnInsertClass').html('Thêm mới');
                    $("#drLevel").attr("disabled", true);
                    $("#modalAddNew").modal("hide");
                    loaddataClass($('#drPagingClass').val(), $('#txtSearchClass').val());
                }
                if (data['error'] !== "" && data['error'] !== undefined) {
                    utility.message("Thông báo",data['error'],null,3000);
                }
        },function(data){
            console.log("updateLop");
            console.log(data);
        },"btnInsertClass","","")
    };

//Xã Phường---------------------------------------------------------------------------------------
    function getSitebyLevel(objData, idchoise = 0) {
        $('#drSiteParents').html("<option value=''>Đang tải dữ liệu...</option>").selectpicker('refresh');
        PostToServer('/danh-muc/xaphuong/loadXaPhuongbyLevel',{SITELEVEL: objData},function(data){
            if (objData > 1) {
                    var datalv1 = data.LEVEL1;
                    var datalv2 = data.LEVEL2;
                    $('#drSiteParents').html("");
                    if (datalv2.length > 0) {
                        $("#drSiteParents").removeAttr("disabled");
                        var html_show = "";
                        html_show += "<option value='0'>-- Chọn cấp trực thuộc --</option>";
                        for (var j = 0; j < datalv2.length; j++) {
                            html_show +="<optgroup label='"+datalv2[j].site_name+"'>";
                            if (datalv1.length > 0) {
                                for (var i = 0; i < datalv1.length; i++) {
                                    if (parseInt(datalv2[j].site_id) == parseInt(datalv1[i].site_parent_id)) {
                                        html_show += "<option value='"+datalv1[i].site_id+"'>"+datalv1[i].site_name+"</option>";
                                    }
                                }
                            }
                            else {
                                $('#drSiteParents').html("<option value=''>-- Chưa có cấp trực thuộc --</option>");
                                return;
                            }
                            html_show +="</optgroup>";
                        }
                        $('#drSiteParents').html(html_show);
                        if (idchoise !== null && idchoise !== "" && idchoise > 0) {
                            $('#drSiteParents').selectpicker('val',idchoise);
                        }
                    }
                    else if (datalv2.length == 0 && datalv1.length > 0) {
                        if (datalv1.length > 0) {
                            $("#drSiteParents").removeAttr("disabled");
                            
                            var html_show = "";
                                 
                            html_show += "<option value='0'>-- Chọn cấp trực thuộc --</option>";
                            for (var i = 0; i < datalv1.length; i++) {
                                html_show += "<option value='"+datalv1[i].site_id+"'>"+datalv1[i].site_name+"</option>";
                            }
                            $('#drSiteParents').html(html_show);

                            if (idchoise !== null && idchoise !== "" && idchoise > 0) {
                                $('#drSiteParents').val(idchoise);
                            }
                        }
                    }
                    else {
                        $('#drSiteParents').html("<option value='0'>-- ROOT --</option>");
                    }
                }
                else {
                    $("#drSiteParents").attr("disabled", true);
                    $('#drSiteParents').html("<option value='0'>-- ROOT --</option>");
                }
                $('#drSiteParents').selectpicker('refresh');
        },function(data){
            console.log("getSitebyLevel");
            console.log(data);
        },"btnInsertSite","","");
    };

    function loadComboXaPhuong(lv=0) {
        $('#drSiteParents').html("<option value=''>Đang tải dữ liệu...</option>").selectpicker('refresh');
        if(parseInt(lv)==1){
            $('#drSiteParents').html("<option value='0'>--ROOT--</option>").selectpicker('refresh');
        }else{
            GetFromServer('/danh-muc/xaphuong/loadcomboXaPhuong/'+(parseInt(lv)-1),function(data){
                var html_show = "";
                html_show += "<option value=''>-- Chọn cấp trực thuộc --</option>";
                for (var i = 0; i < data.length; i++) {
                    html_show += "<option value='"+data[i].site_id+"'>"+data[i].site_name+"</option>";
                }
                $('#drSiteParents').removeAttr('disabled');
                $('#drSiteParents').html(html_show).selectpicker('refresh');
            },function(data){
                console.log("loadComboXaPhuong");
                console.log(data);
            },"","","");
        }
        
    };

    function insertSite(objData) {
        var objJson = JSON.stringify(objData);
        console.log(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/xaphuong/insert',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                // console.log(data);
                if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    resetXaPhuong();
                    $("#using_json_2").jstree("refresh");
                    $('#dvSiteType').hide();
                }
                
                if (data['error'] != "" && data['error'] != undefined) {
                    utility.message("Thông báo",data['error'],null,3000);
                }
            }, error: function(data) {
            }
        });
    };

    function updateSite(objData) {
        var objJson = JSON.stringify(objData);
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/xaphuong/update',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                //alert(data);
                if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    resetXaPhuong();
                    $("#using_json_2").jstree("refresh");
                    $('#dvSiteType').hide();
                }
                if (data['error'] != "" && data['error'] != undefined) {
                    utility.message("Thông báo",data['error'],null,3000);
                }
            }, error: function(data) {
            }
        });
    };

    function deleteSite(objData) {
        var objJson = JSON.stringify(objData);
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/xaphuong/delete',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                //alert(data);
                if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    resetXaPhuong();
                    $("#using_json_2").jstree("refresh");
                }
                
                if (data['error'] != "" && data['error'] != undefined) {
                    utility.message("Thông báo",data['error'],null,3000);
                }
            }, error: function(data) {
            }
        });
    };

    function resetXaPhuong(){
        // $('#txtSiteCode').focus();
        $('#txtSiteID').val("");
        // $('#txtSiteCode').val("");
        $('#txtSiteName').val("");
        $('#drSiteLevel').selectpicker('val',0);
        $("#drSiteParents").attr("disabled", true);
        $("#drSiteParents").html('<option value="0" selected>-- Chọn cấp trực thuộc --</option>').selectpicker('refresh');;
        $('#drSiteActive').selectpicker('val',1);
        site_id = "";
        $("#btnDeleteSite").attr("disabled", true);        
        // $("#txtSiteCode").attr("disabled", false);
    }

//Phân loại xã---------------------------------------------------------------------------------------

    function loadComboWard(callback) {
        $.ajax({
            type: "get",
            url:'/danh-muc/phanloaixa/loadcomboPLXa',
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                $('#drWardParent').html("");
                var html_show = "";
                //console.log(data);
                html_show += "<option value='0'>-- Chọn cấp trực thuộc --</option>";
                for (var i = 0; i < data.length; i++) {
                    // for (var j = 0; j < data.length; j++) {
                    //     if (data[i].wards_id == data[j].wards_parent_id) {
                    //         html_show += "<option data='"+data[i].wards_level+"' value='"+data[i].wards_id+"'>"+data[i].wards_name+"</option>";
                    //     }
                    // }
                    html_show += "<option data='"+data[i].wards_level+"' value='"+data[i].wards_id+"'>"+data[i].wards_name+"</option>";
                }
                $('#drWardParent').html(html_show);

                if(callback != null){
                    callback(data);
                }
            }, error: function(data) {
            }
        });
    };

    function insertWard(objData) {
        var objJson = JSON.stringify(objData);
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/phanloaixa/insert',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                //alert(data);
                if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    $("#using_json_2").jstree(true).refresh();
                    $('#txtWardID').val('');
                    // $('#txtWardCode').val('');
                    $('#txtWardName').val('');
                    loadComboWard();
                    $('#drWardActive').val(1);
                    ward_id = "";
                }
                
                if (data['error'] != "" && data['error'] != undefined) {
                    utility.message("Thông báo",data['error'],null,3000);
                }
            }, error: function(data) {
            }
        });
    };

    function updateWard(objData) {
        var objJson = JSON.stringify(objData);
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/phanloaixa/update',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                //alert(data);
                if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    // $('#txtWardCode').val("");
                    $('#txtWardName').val("");
                    $('#drWardActive').val(1);
                    $('#txtWardID').val('');
                    $("#btnInsertWard").html("Thêm mới");
                    ward_id = "";
                    loadComboWard();
                    $("#using_json_2").jstree("refresh");
                }
                
                if (data['error'] != "" && data['error'] != undefined) {
                    utility.message("Thông báo",data['error'],null,3000);
                }
            }, error: function(data) {
            }
        });
    };

    function deleteWard(objData) {
        var objJson = JSON.stringify(objData);
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/phanloaixa/delete',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                //console.log(data);
                if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    // $('#txtWardCode').val("");
                    $('#txtWardName').val("");                    
                    loadComboWard();
                    $('#drWardActive').val(1);
                    $('#txtWardID').val("");
                    $("#btnInsertWard").html("Thêm mới");
                    ward_id = "";
                    $("#using_json_2").jstree("refresh");
                }
                
                if (data['error'] != "" && data['error'] != undefined) {
                    utility.message("Thông báo",data['error'],null,3000);
                }
            }, error: function(data) {
            }
        });
    };

//Phòng ban---------------------------------------------------------------------------------------

    function loadComboDepartment(callback) {
        return $.ajax({
            type: "get",
            url:'/danh-muc/loadcomboDepartment',
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                $('#drpDepartment').html("");
                var html_show = "";
                     
                html_show += "<option value='0'>-- Chọn cấp trực thuộc --</option>";
                for (var i = 0; i < data.length; i++) {
                    html_show += "<option data='"+data[i].department_level+"' value='"+data[i].department_id+"'>"+data[i].department_name+"</option>";
                }
                $('#drpDepartment').html(html_show);

                if(callback != null){
                    callback(data);
                }
            }, error: function(data) {
            }
        });
    };

    function insertDepartment(objData) {
        var objJson = JSON.stringify(objData);
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/insert',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                //alert(data);
                if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    // $('#txtDepartCode').val("");
                    $('#txtDepartName').val("");                    
                    loadComboDepartment( function(data){
                    });
                    $('#drDepartActive').val(1);
                    depart_id = "";

                    //$("#using_json_2").jstree("loaded");
                    $("#using_json_2").jstree("refresh");
                }
                
                if (data['error'] != "" && data['error'] != undefined) {
                    utility.message("Thông báo",data['error'],null,3000);
                }
            }, error: function(data) {
            }
        });
    };

    function updateDepartment(objData) {
        var objJson = JSON.stringify(objData);
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/update',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                //alert(data);
                if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    // $('#txtDepartCode').val("");
                    $('#txtDepartName').val("");
                    loadComboDepartment( function(data){
                    });
                    $('#drDepartActive').val(1);
                    $("#btnInsertDepartment").html("Thêm mới");
                    $('#txtDepartID').val("");
                    depart_id = "";
                    $("#using_json_2").jstree("refresh");
                }
                
                if (data['error'] != "" && data['error'] != undefined) {
                    utility.message("Thông báo",data['error'],null,3000);
                }
            }, error: function(data) {
            }
        });
    };

    function deleteDepartment(objData) {
        var objJson = JSON.stringify(objData);
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/delete',
            data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                //alert(data);
                if (data['success'] !== "" && data['success'] !== undefined) {
                    utility.message("Thông báo",data['success'],null,3000);
                    // $('#txtDepartCode').val("");
                    $('#txtDepartName').val("");
                    loadComboDepartment( function(data){
                    });
                    $('#drDepartActive').val(1);
                    $("#btnInsertDepartment").html("Thêm mới");
                    $('#txtDepartID').val("");
                    depart_id = "";
                    $("#using_json_2").jstree("refresh");
                }
                
                if (data['error'] != "" && data['error'] != undefined) {
                    utility.message("Thông báo",data['error'],null,3000);
                }
            }, error: function(data) {
            }
        });
    };

//Validate input all form

    var messageValidate = "";

    function validateInput(formname)
    {
        if (formname == "GROUP") {
            // var v_groupcode = $('#txtGroupCode').val();
            var v_groupname = $('#txtGroupName').val();

            // v_groupcode = v_groupcode.replace(/[\n\t\r]/g,"");
            v_groupname = v_groupname.replace(/[\n\t\r]/g,"");

            // if (v_groupcode.trim() == "") {        
            //     messageValidate = "Vui lòng nhập mã chế độ!";
            //     $('#txtGroupCode').focus();
            //     return messageValidate;
            // }else if (v_groupcode.length > 200) {
            //     messageValidate = "Mã chế độ không được vượt quá 200 ký tự!";
            //     $('#txtGroupCode').focus();
            //     $('#txtGroupCode').val("");
            //     return messageValidate;
            // }
            // else{
            //     var specialChars = "!@#$%^&*()+=[]\\\';,./{}|\":<>?";
            //     var unicodeChars = "àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđÁÀẠẢÃÂẤẦẬẨẪĂẮẰẶẲẴÉÈẸẺẼÊẾỀỆỂỄÍÌỊỈĨÓÒỌỎÕÔỐỒỘỔỖƠỚỜỢỞỠÚÙỤỦŨƯỨỪỰỬỮÝỲỴỶỸĐ";

            //     for (var i = 0; i < v_groupcode.length; i++) {
            //         if (specialChars.indexOf(v_groupcode.charAt(i)) != -1) {
            //             messageValidate = "Mã nhập không được chứa ký tự đặc biệt!";
            //             $('#txtGroupCode').focus();
            //             $('#txtGroupCode').val("");
            //             return messageValidate;
            //         }

            //         if (unicodeChars.indexOf(v_groupcode.charAt(i)) != -1) {
            //             messageValidate = "Mã nhập không được chứa ký tự có dấu!";
            //             $('#txtGroupCode').focus();
            //             $('#txtGroupCode').val("");
            //             return messageValidate;
            //         }
            //     }
            //     $('#txtGroupCode').focusout();
            // }        

            //Validate Name----------------------------------------------------------------------------------------
            if (v_groupname.trim() == "") {
                messageValidate = "Vui lòng nhập tên chế độ!";
                $('#txtGroupName').focus();
                return messageValidate;
            }else if (v_groupname.length > 200) {
                messageValidate = "Tên chế độ không được vượt quá 200 ký tự!";
                $('#txtGroupName').focus();
                $('#txtGroupName').val("");
                return messageValidate;
            }
            else{
                var specialChars = "#/|\\";

                for (var i = 0; i < v_groupname.length; i++) {
                    if (specialChars.indexOf(v_groupname.charAt(i)) != -1) {
                        messageValidate = "Tên chế độ không được chứa ký tự #, /, |, \\";
                        $('#txtGroupName').focus();
                        return messageValidate;
                    }
                }

                $('#txtGroupName').focusout();
            }
        }
        if (formname == "UNIT") {
            // var v_unitcode = $('#txtUnitCode').val();
            var v_unitname = $('#txtUnitName').val();
            // var v_schoolid = $('#sltTruongDt').val();
            
            // v_unitcode = v_unitcode.replace(/[\n\t\r]/g,"");
            v_unitname = v_unitname.replace(/[\n\t\r]/g,"");

            // if (v_unitcode.trim() == "") {        
            //     messageValidate = "Vui lòng nhập mã khối!";
            //     $('#txtUnitCode').focus();
            //     return messageValidate;
            // }else if (v_unitcode.length > 200) {
            //     messageValidate = "Mã khối không được vượt quá 200 ký tự!";
            //     $('#txtUnitCode').focus();
            //     $('#txtUnitCode').val("");
            //     return messageValidate;
            // }
            // else{
            //     var specialChars = "!@#$%^&*()+=[]\\\';,./{}|\":<>?";
            //     var unicodeChars = "àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđÁÀẠẢÃÂẤẦẬẨẪĂẮẰẶẲẴÉÈẸẺẼÊẾỀỆỂỄÍÌỊỈĨÓÒỌỎÕÔỐỒỘỔỖƠỚỜỢỞỠÚÙỤỦŨƯỨỪỰỬỮÝỲỴỶỸĐ";

            //     for (var i = 0; i < v_unitcode.length; i++) {
            //         if (specialChars.indexOf(v_unitcode.charAt(i)) != -1) {
            //             messageValidate = "Mã nhập không được chứa ký tự đặc biệt!";
            //             $('#txtUnitCode').focus();
            //             $('#txtUnitCode').val("");
            //             return messageValidate;
            //         }

            //         if (unicodeChars.indexOf(v_unitcode.charAt(i)) != -1) {
            //             messageValidate = "Mã nhập không được chứa ký tự có dấu!";
            //             $('#txtUnitCode').focus();
            //             $('#txtUnitCode').val("");
            //             return messageValidate;
            //         }
            //     }
            //     $('#txtUnitCode').focusout();
            // }        

            //Validate Name----------------------------------------------------------------------------------------
            if (v_unitname.trim() == "") {
                messageValidate = "Vui lòng nhập tên khối!";
                $('#txtUnitName').focus();
                return messageValidate;
            }else if (v_unitname.length > 200) {
                messageValidate = "Tên khối không được vượt quá 200 ký tự!";
                $('#txtUnitName').focus();
                $('#txtUnitName').val("");
                return messageValidate;
            }
            else{
                var specialChars = "#/|\\";

                for (var i = 0; i < v_unitname.length; i++) {
                    if (specialChars.indexOf(v_unitname.charAt(i)) != -1) {
                        messageValidate = "Tên khối không được chứa ký tự #, /, |, \\";
                        $('#txtUnitName').focus();
                        return messageValidate;
                    }
                }

                $('#txtUnitName').focusout();
            }

            // if (v_schoolid === null || v_schoolid === "" || v_schoolid <= 0) {
            //     messageValidate = "Vui lòng chọn trường học!";
            //     return messageValidate;
            // }
        }
        if (formname == "NATION") {
            // var v_nationcode = $('#txtNationCode').val();
            var v_nationname = $('#txtNationName').val();
            
            // v_nationcode = v_nationcode.replace(/[\n\t\r]/g,"");
            v_nationname = v_nationname.replace(/[\n\t\r]/g,"");

            // if (v_nationcode.trim() == "") {        
            //     messageValidate = "Vui lòng nhập mã dân tộc!";
            //     $('#txtNationCode').focus();
            //     return messageValidate;
            // }else if (v_nationcode.length > 200) {
            //     messageValidate = "Mã dân tộc không được vượt quá 200 ký tự!";
            //     $('#txtNationCode').focus();
            //     $('#txtNationCode').val("");
            //     return messageValidate;
            // }
            // else{
            //     var specialChars = "!@#$%^&*()+=[]\\\';,./{}|\":<>?";
            //     var unicodeChars = "àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđÁÀẠẢÃÂẤẦẬẨẪĂẮẰẶẲẴÉÈẸẺẼÊẾỀỆỂỄÍÌỊỈĨÓÒỌỎÕÔỐỒỘỔỖƠỚỜỢỞỠÚÙỤỦŨƯỨỪỰỬỮÝỲỴỶỸĐ";

            //     for (var i = 0; i < v_nationcode.length; i++) {
            //         if (specialChars.indexOf(v_nationcode.charAt(i)) != -1) {
            //             messageValidate = "Mã nhập không được chứa ký tự đặc biệt!";
            //             $('#txtNationCode').focus();
            //             $('#txtNationCode').val("");
            //             return messageValidate;
            //         }

            //         if (unicodeChars.indexOf(v_nationcode.charAt(i)) != -1) {
            //             messageValidate = "Mã nhập không được chứa ký tự có dấu!";
            //             $('#txtNationCode').focus();
            //             $('#txtNationCode').val("");
            //             return messageValidate;
            //         }
            //     }
            //     $('#txtNationCode').focusout();
            // }        

            //Validate Name----------------------------------------------------------------------------------------
            if (v_nationname.trim() == "") {
                messageValidate = "Vui lòng nhập tên dân tộc!";
                $('#txtNationName').focus();
                return messageValidate;
            }else if (v_nationname.length > 200) {
                messageValidate = "Tên dân tộc không được vượt quá 200 ký tự!";
                $('#txtNationName').focus();
                $('#txtNationName').val("");
                return messageValidate;
            }
            else{
                var specialChars = "#/|\\";

                for (var i = 0; i < v_nationname.length; i++) {
                    if (specialChars.indexOf(v_nationname.charAt(i)) != -1) {
                        messageValidate = "Tên dân tộc không được chứa ký tự #, /, |, \\";
                        $('#txtNationName').focus();
                        return messageValidate;
                    }
                }

                $('#txtNationName').focusout();
            }
        }
        if (formname == "SUBJECT") {
            // var v_subjectcode = $('#txtSubjectCode').val();
            var v_subjectname = $('#txtSubjectName').val();
            
            // v_subjectcode = v_subjectcode.replace(/[\n\t\r]/g,"");
            v_subjectname = v_subjectname.replace(/[\n\t\r]/g,"");

            var arrSubID = [];
            var $el = $(".multiselect-container");
            $el.find('li.active input').each(function(){
                arrSubID.push({value:$(this).val()});
            });

            // if (v_subjectcode.trim() == "") {        
            //     messageValidate = "Vui lòng nhập mã nhóm đối tượng!";
            //     $('#txtSubjectCode').focus();
            //     return messageValidate;
            // }else if (v_subjectcode.length > 200) {
            //     messageValidate = "Mã nhóm đối tượng không được vượt quá 200 ký tự!";
            //     $('#txtSubjectCode').focus();
            //     $('#txtSubjectCode').val("");
            //     return messageValidate;
            // }
            // else{
            //     var specialChars = "!@#$%^&*()+=[]\\\';,./{}|\":<>?";
            //     var unicodeChars = "àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđÁÀẠẢÃÂẤẦẬẨẪĂẮẰẶẲẴÉÈẸẺẼÊẾỀỆỂỄÍÌỊỈĨÓÒỌỎÕÔỐỒỘỔỖƠỚỜỢỞỠÚÙỤỦŨƯỨỪỰỬỮÝỲỴỶỸĐ";

            //     for (var i = 0; i < v_subjectcode.length; i++) {
            //         if (specialChars.indexOf(v_subjectcode.charAt(i)) != -1) {
            //             messageValidate = "Mã nhập không được chứa ký tự đặc biệt!";
            //             $('#txtSubjectCode').focus();
            //             $('#txtSubjectCode').val("");
            //             return messageValidate;
            //         }

            //         if (unicodeChars.indexOf(v_subjectcode.charAt(i)) != -1) {
            //             messageValidate = "Mã nhập không được chứa ký tự có dấu!";
            //             $('#txtSubjectCode').focus();
            //             $('#txtSubjectCode').val("");
            //             return messageValidate;
            //         }
            //     }
            //     $('#txtSubjectCode').focusout();
            // }        

            //Validate Name----------------------------------------------------------------------------------------
            if (v_subjectname.trim() == "") {
                messageValidate = "Vui lòng nhập tên nhóm đối tượng!";
                $('#txtSubjectName').focus();
                return messageValidate;
            }else if (v_subjectname.length > 200) {
                messageValidate = "Tên nhóm đối tượng không được vượt quá 200 ký tự!";
                $('#txtSubjectName').focus();
                $('#txtSubjectName').val("");
                return messageValidate;
            }
            else{
                var specialChars = "#/|\\";

                for (var i = 0; i < v_subjectname.length; i++) {
                    if (specialChars.indexOf(v_subjectname.charAt(i)) != -1) {
                        messageValidate = "Tên nhóm đối tượng không được chứa ký tự #, /, |, \\";
                        $('#txtSubjectName').focus();
                        return messageValidate;
                    }
                }

                $('#txtSubjectName').focusout();
            }

            //Validate Group----------------------------------------------------------------------------------------
            // if (arrSubID.length <= 0) {
            //     messageValidate = "Vui lòng chọn nhóm đối tượng!";
            //     return messageValidate;
            // }
        }
        if (formname == "SCHOOL") {
            // var v_schoolcode = $('#txtSchoolCode').val();
            var v_schoolname = $('#txtSchoolName').val();
            // var v_unitid = $('#drUnit').val();
            var v_type = $('#drSchoolType').val();
            var v_startDate = $('#txtStartDate').val();
            
            // v_schoolcode = v_schoolcode.replace(/[\n\t\r]/g,"");
            v_schoolname = v_schoolname.replace(/[\n\t\r]/g,"");

            // if (v_schoolcode.trim() == "") {        
            //     messageValidate = "Vui lòng nhập mã trường!";
            //     $('#txtSchoolCode').focus();
            //     return messageValidate;
            // }else if (v_schoolcode.length > 200) {
            //     messageValidate = "Mã trường không được vượt quá 200 ký tự!";
            //     $('#txtSchoolCode').focus();
            //     $('#txtSchoolCode').val("");
            //     return messageValidate;
            // }
            // else{
            //     var specialChars = "!@#$%^&*()+=[]\\\';,./{}|\":<>?";
            //     var unicodeChars = "àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđÁÀẠẢÃÂẤẦẬẨẪĂẮẰẶẲẴÉÈẸẺẼÊẾỀỆỂỄÍÌỊỈĨÓÒỌỎÕÔỐỒỘỔỖƠỚỜỢỞỠÚÙỤỦŨƯỨỪỰỬỮÝỲỴỶỸĐ";

            //     for (var i = 0; i < v_schoolcode.length; i++) {
            //         if (specialChars.indexOf(v_schoolcode.charAt(i)) != -1) {
            //             messageValidate = "Mã nhập không được chứa ký tự đặc biệt!";
            //             $('#txtSchoolCode').focus();
            //             $('#txtSchoolCode').val("");
            //             return messageValidate;
            //         }

            //         if (unicodeChars.indexOf(v_schoolcode.charAt(i)) != -1) {
            //             messageValidate = "Mã nhập không được chứa ký tự có dấu!";
            //             $('#txtSchoolCode').focus();
            //             $('#txtSchoolCode').val("");
            //             return messageValidate;
            //         }
            //     }
            //     $('#txtSchoolCode').focusout();
            // }        

            //Validate Name----------------------------------------------------------------------------------------
            if (v_schoolname.trim() == "") {
                messageValidate = "Vui lòng nhập tên trường!";
                $('#txtSchoolName').focus();
                return messageValidate;
            }else if (v_schoolname.length > 200) {
                messageValidate = "Tên trường không được vượt quá 200 ký tự!";
                $('#txtSchoolName').focus();
                $('#txtSchoolName').val("");
                return messageValidate;
            }
            else{
                var specialChars = "#/|\\";

                for (var i = 0; i < v_schoolname.length; i++) {
                    if (specialChars.indexOf(v_schoolname.charAt(i)) != -1) {
                        messageValidate = "Tên trường không được chứa ký tự #, /, |, \\";
                        $('#txtSchoolName').focus();
                        return messageValidate;
                    }
                }

                $('#txtSchoolName').focusout();
            }

            // //Validate Unit
            // if (v_unitid == "" || v_unitid == 0) {
            //     messageValidate = "Vui lòng chọn khối!";
            //     return messageValidate;
            // }

            //Validate Type
            if (v_type == "" || v_type == 0) {
                messageValidate = "Vui lòng chọn loại trường!";
                return messageValidate;
            }

            //Validate Unit
            if (v_startDate == "" || v_startDate == 0) {
                messageValidate = "Vui lòng nhập ngày hiệu lực!";
                return messageValidate;
            }
        }
        if (formname == "CLASS") {
            // var v_classcode = $('#txtClassCode').val();
            var v_classname = $('#txtClassName').val();
            var v_schoolid = $('#drSchool').val();
            var v_unitid = $('#sltKhoiDt').val();
            var v_levelid = $('#drLevel').val();
            
            // v_classcode = v_classcode.replace(/[\n\t\r]/g,"");
            v_classname = v_classname.replace(/[\n\t\r]/g,"");

            // if (v_classcode.trim() == "") {        
            //     messageValidate = "Vui lòng nhập mã lớp!";
            //     $('#txtClassCode').focus();
            //     return messageValidate;
            // }else if (v_classcode.length > 200) {
            //     messageValidate = "Mã lớp không được vượt quá 200 ký tự!";
            //     $('#txtClassCode').focus();
            //     $('#txtClassCode').val("");
            //     return messageValidate;
            // }
            // else{
            //     var specialChars = "!@#$%^&*()+=[]\\\';,./{}|\":<>?";
            //     var unicodeChars = "àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđÁÀẠẢÃÂẤẦẬẨẪĂẮẰẶẲẴÉÈẸẺẼÊẾỀỆỂỄÍÌỊỈĨÓÒỌỎÕÔỐỒỘỔỖƠỚỜỢỞỠÚÙỤỦŨƯỨỪỰỬỮÝỲỴỶỸĐ";

            //     for (var i = 0; i < v_classcode.length; i++) {
            //         if (specialChars.indexOf(v_classcode.charAt(i)) != -1) {
            //             messageValidate = "Mã nhập không được chứa ký tự đặc biệt!";
            //             $('#txtClassCode').focus();
            //             $('#txtClassCode').val("");
            //             return messageValidate;
            //         }

            //         if (unicodeChars.indexOf(v_classcode.charAt(i)) != -1) {
            //             messageValidate = "Mã nhập không được chứa ký tự có dấu!";
            //             $('#txtClassCode').focus();
            //             $('#txtClassCode').val("");
            //             return messageValidate;
            //         }
            //     }
            //     $('#txtClassCode').focusout();
            // }        

            //Validate Name----------------------------------------------------------------------------------------
            if (v_classname.trim() == "") {
                messageValidate = "Vui lòng nhập tên lớp!";
                $('#txtClassName').focus();
                return messageValidate;
            }else if (v_classname.length > 200) {
                messageValidate = "Tên lớp không được vượt quá 200 ký tự!";
                $('#txtClassName').focus();
                $('#txtClassName').val("");
                return messageValidate;
            }
            else{
                var specialChars = "#/|\\";

                for (var i = 0; i < v_classname.length; i++) {
                    if (specialChars.indexOf(v_classname.charAt(i)) != -1) {
                        messageValidate = "Tên lớp không được chứa ký tự #, /, |, \\";
                        $('#txtClassName').focus();
                        return messageValidate;
                    }
                }

                $('#txtClassName').focusout();
            }

            //Validate School
            if (v_schoolid == "" || v_schoolid == 0) {
                messageValidate = "Vui lòng chọn trường!";
                return messageValidate;
            }

            //Validate Unit
            if (v_unitid == null || v_unitid == "" || v_unitid == 0) {
                messageValidate = "Vui lòng chọn khối!";
                return messageValidate;
            }

            //Validate Level
            if (v_levelid == "" || v_levelid == 0) {
                messageValidate = "Vui lòng chọn khối lớp!";
                return messageValidate;
            }
        }
        if (formname == "SITE") {
            // var v_sitecode = $('#txtSiteCode').val();
            var v_sitename = $('#txtSiteName').val();
            var v_sitelevel = $('#drSiteLevel').val();
            var v_siteparentid = $('#drSiteParents').val();
            
            // v_sitecode = v_sitecode.replace(/[\n\t\r]/g,"");
            v_sitename = v_sitename.replace(/[\n\t\r]/g,"");

            // if (v_sitecode.trim() == "") {        
            //     messageValidate = "Vui lòng nhập mã địa phương!";
            //     $('#txtSiteCode').focus();
            //     return messageValidate;
            // }else if (v_sitecode.length > 200) {
            //     messageValidate = "Mã địa phương không được vượt quá 200 ký tự!";
            //     $('#txtSiteCode').focus();
            //     $('#txtSiteCode').val("");
            //     return messageValidate;
            // }
            // else{
            //     var specialChars = "!@#$%^&*()+=[]\\\';,./{}|\":<>?";
            //     var unicodeChars = "àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđÁÀẠẢÃÂẤẦẬẨẪĂẮẰẶẲẴÉÈẸẺẼÊẾỀỆỂỄÍÌỊỈĨÓÒỌỎÕÔỐỒỘỔỖƠỚỜỢỞỠÚÙỤỦŨƯỨỪỰỬỮÝỲỴỶỸĐ";

            //     for (var i = 0; i < v_sitecode.length; i++) {
            //         if (specialChars.indexOf(v_sitecode.charAt(i)) != -1) {
            //             messageValidate = "Mã nhập không được chứa ký tự đặc biệt!";
            //             $('#txtSiteCode').focus();
            //             $('#txtSiteCode').val("");
            //             return messageValidate;
            //         }

            //         if (unicodeChars.indexOf(v_sitecode.charAt(i)) != -1) {
            //             messageValidate = "Mã nhập không được chứa ký tự có dấu!";
            //             $('#txtSiteCode').focus();
            //             $('#txtSiteCode').val("");
            //             return messageValidate;
            //         }
            //     }
            //     $('#txtSiteCode').focusout();
            // }        

            //Validate Name----------------------------------------------------------------------------------------
            if (v_sitename.trim() == "") {
                messageValidate = "Vui lòng nhập tên địa phương!";
                $('#txtSiteName').focus();
                return messageValidate;
            }else if (v_sitename.length > 200) {
                messageValidate = "Tên địa phương không được vượt quá 200 ký tự!";
                $('#txtSiteName').focus();
                $('#txtSiteName').val("");
                return messageValidate;
            }
            else{
                var specialChars = "#/|\\";

                for (var i = 0; i < v_sitename.length; i++) {
                    if (specialChars.indexOf(v_sitename.charAt(i)) != -1) {
                        messageValidate = "Tên địa phương không được chứa ký tự #, /, |, \\";
                        $('#txtSiteName').focus();
                        return messageValidate;
                    }
                }

                $('#txtSiteName').focusout();
            }

            //Validate Level and Parent Site
            if (v_sitelevel == "" || v_sitelevel == 0) {
                messageValidate = "Vui lòng chọn cấp hành chính!";
                return messageValidate;
            }
            else if (v_sitelevel == 2 || v_sitelevel == 3) {
                if (v_siteparentid == "" || v_siteparentid == 0) {
                    messageValidate = "Vui lòng chọn địa phương trực thuộc!";
                    return messageValidate;
                }            
            }
        }
        if (formname == "WARD") {
            // var v_wardcode = $('#txtWardCode').val();
            var v_wardname = $('#txtWardName').val();
            
            // v_wardcode = v_wardcode.replace(/[\n\t\r]/g,"");
            v_wardname = v_wardname.replace(/[\n\t\r]/g,"");

            // if (v_wardcode.trim() == "") {        
            //     messageValidate = "Vui lòng nhập mã phân loại xã!";
            //     $('#txtWardCode').focus();
            //     return messageValidate;
            // }else if (v_wardcode.length > 200) {
            //     messageValidate = "Mã phân loại xã không được vượt quá 200 ký tự!";
            //     $('#txtWardCode').focus();
            //     $('#txtWardCode').val("");
            //     return messageValidate;
            // }
            // else{
            //     var specialChars = "!@#$%^&*()+=[]\\\';,./{}|\":<>?";
            //     var unicodeChars = "àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđÁÀẠẢÃÂẤẦẬẨẪĂẮẰẶẲẴÉÈẸẺẼÊẾỀỆỂỄÍÌỊỈĨÓÒỌỎÕÔỐỒỘỔỖƠỚỜỢỞỠÚÙỤỦŨƯỨỪỰỬỮÝỲỴỶỸĐ";

            //     for (var i = 0; i < v_wardcode.length; i++) {
            //         if (specialChars.indexOf(v_wardcode.charAt(i)) != -1) {
            //             messageValidate = "Mã nhập không được chứa ký tự đặc biệt!";
            //             $('#txtWardCode').focus();
            //             $('#txtWardCode').val("");
            //             return messageValidate;
            //         }

            //         if (unicodeChars.indexOf(v_wardcode.charAt(i)) != -1) {
            //             messageValidate = "Mã nhập không được chứa ký tự có dấu!";
            //             $('#txtWardCode').focus();
            //             $('#txtWardCode').val("");
            //             return messageValidate;
            //         }
            //     }
            //     $('#txtWardCode').focusout();
            // }        

            //Validate Name----------------------------------------------------------------------------------------
            if (v_wardname.trim() == "") {
                messageValidate = "Vui lòng nhập tên phân loại xã!";
                $('#txtWardName').focus();
                return messageValidate;
            }else if (v_wardname.length > 200) {
                messageValidate = "Tên phân loại xã không được vượt quá 200 ký tự!";
                $('#txtWardName').focus();
                $('#txtWardName').val("");
                return messageValidate;
            }
            else{
                var specialChars = "#/|\\";

                for (var i = 0; i < v_wardname.length; i++) {
                    if (specialChars.indexOf(v_wardname.charAt(i)) != -1) {
                        messageValidate = "Tên phân loại xã không được chứa ký tự #, /, |, \\";
                        $('#txtWardName').focus();
                        return messageValidate;
                    }
                }

                $('#txtWardName').focusout();
            }
        }
        if (formname == "DEPARTMENT") {
            // var v_departmentcode = $('#txtDepartCode').val();
            var v_departmentname = $('#txtDepartName').val();
            
            // v_departmentcode = v_departmentcode.replace(/[\n\t\r]/g,"");
            v_departmentname = v_departmentname.replace(/[\n\t\r]/g,"");

            // if (v_departmentcode.trim() == "") {        
            //     messageValidate = "Vui lòng nhập mã phòng ban!";
            //     $('#txtDepartCode').focus();
            //     return messageValidate;
            // }else if (v_departmentcode.length > 200) {
            //     messageValidate = "Mã phòng ban không được vượt quá 200 ký tự!";
            //     $('#txtDepartCode').focus();
            //     $('#txtDepartCode').val("");
            //     return messageValidate;
            // }
            // else{
            //     var specialChars = "!@#$%^&*()+=[]\\\';,./{}|\":<>?";
            //     var unicodeChars = "àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđÁÀẠẢÃÂẤẦẬẨẪĂẮẰẶẲẴÉÈẸẺẼÊẾỀỆỂỄÍÌỊỈĨÓÒỌỎÕÔỐỒỘỔỖƠỚỜỢỞỠÚÙỤỦŨƯỨỪỰỬỮÝỲỴỶỸĐ";

            //     for (var i = 0; i < v_departmentcode.length; i++) {
            //         if (specialChars.indexOf(v_departmentcode.charAt(i)) != -1) {
            //             messageValidate = "Mã nhập không được chứa ký tự đặc biệt!";
            //             $('#txtDepartCode').focus();
            //             $('#txtDepartCode').val("");
            //             return messageValidate;
            //         }

            //         if (unicodeChars.indexOf(v_departmentcode.charAt(i)) != -1) {
            //             messageValidate = "Mã nhập không được chứa ký tự có dấu!";
            //             $('#txtDepartCode').focus();
            //             $('#txtDepartCode').val("");
            //             return messageValidate;
            //         }
            //     }
            //     $('#txtDepartCode').focusout();
            // }        

            //Validate Name----------------------------------------------------------------------------------------
            if (v_departmentname.trim() == "") {
                messageValidate = "Vui lòng nhập tên phòng ban!";
                $('#txtDepartName').focus();
                return messageValidate;
            }else if (v_departmentname.length > 200) {
                messageValidate = "Tên phòng ban không được vượt quá 200 ký tự!";
                $('#txtDepartName').focus();
                $('#txtDepartName').val("");
                return messageValidate;
            }
            else{
                var specialChars = "#/|\\";

                for (var i = 0; i < v_departmentname.length; i++) {
                    if (specialChars.indexOf(v_departmentname.charAt(i)) != -1) {
                        messageValidate = "Tên phòng ban không được chứa ký tự #, /, |, \\";
                        $('#txtDepartName').focus();
                        return messageValidate;
                    }
                }

                $('#txtDepartName').focusout();
            }
        }
    };

    function ValidateNGNA(){
        var schools_id = $('#sltTruongDt').val();
        var amount = $('#txtHSBT').val();
        var sltYear = $('#sltYear').val();
        var txtNVDP = $('#txtNVDP').val();
        var txtNVTW = $('#txtNVTW').val();
        var txtHSBT = $('#txtHSBT').val();
        


        if (schools_id.trim() == null || schools_id.trim() == "" || schools_id.trim() == 0) {
            messageValidate = "Vui lòng chọn trường!";
            return messageValidate;
        }

        if (amount.trim() == null || amount.trim() == "" || amount.trim() == 0) {
            messageValidate = "Vui lòng nhập số người nấu ăn!";
            return messageValidate;
        }

        if (sltYear.trim() == null || sltYear.trim() == "") {
            messageValidate = "Yêu cầu chọn năm học";
            return messageValidate;
        }

        if (txtNVDP.trim() == null || txtNVDP.trim() == "") {
            messageValidate = "Chưa có số lượng nhận viên theo Địa phương";
            return messageValidate;
        }
        if (txtNVTW.trim() == null || txtNVTW.trim() == "") {
            messageValidate = "Chưa có số lượng nhân viên theo TW";
            return messageValidate;
        }

        if (txtHSBT.trim() == null || txtHSBT.trim() == "") {
            messageValidate = "Chưa có số học sinh bán trú.";
            return messageValidate;
        }

        // if (endDate.trim() == null || endDate.trim() == "") {
        //     messageValidate = "Vui lòng nhập ngày kết thúc!";
        //     return messageValidate;
        // }
    }

    function ValidateDSHN(){
        var name = $('#txtName').val();
        var birthday = $('#txtBirthday').val();
        var sex = $('#sltSex').val();
        var nation = $('#sltNations').val();
        var type = $('#sltTypeName').val();
        var site2 = $('#sltSite2').val();
        var site1 = $('#sltSite1').val();
        var relation = $('#sltRelationShip').val();
        var index = $('#txtIndex').val();
        //Validate Name----------------------------------------------------------------------------------------
        if (name.trim() == "") {
            messageValidate = "Vui lòng nhập họ tên!";
            $('#txtName').focus();
            return messageValidate;
        }else if (name.length > 200) {
            messageValidate = "Họ tên không được vượt quá 200 ký tự!";
            $('#txtName').focus();
            $('#txtName').val("");
            return messageValidate;
        }
        else{
            var specialChars = "#/|\\";

            for (var i = 0; i < name.length; i++) {
                if (specialChars.indexOf(name.charAt(i)) != -1) {
                    messageValidate = "Họ tên không được chứa ký tự #, /, |, \\";
                    $('#txtName').focus();
                    return messageValidate;
                }
            }

            $('#txtName').focusout();
        }
        //Validate Birhtday----------------------------------------------------------------------------------------
        if (birthday.trim() == null || birthday.trim() == "") {
            messageValidate = "Vui lòng nhập ngày sinh!";
            return messageValidate;
        }
        //Validate Sex----------------------------------------------------------------------------------------
        if (sex.trim() == null || sex.trim() == "") {
            messageValidate = "Vui lòng chọn giới tính!";
            return messageValidate;
        }
        //Validate Nation----------------------------------------------------------------------------------------
        if (nation.trim() == null || nation.trim() == "") {
            messageValidate = "Vui lòng chọn dân tộc!";
            return messageValidate;
        }
        //Validate RelationShip----------------------------------------------------------------------------------------
        if (relation.trim() == null || relation.trim() == "") {
            messageValidate = "Vui lòng chọn quan hệ!";
            return messageValidate;
        }
        //Validate Site1----------------------------------------------------------------------------------------
        if (site1.trim() == null || site1.trim() == "") {
            messageValidate = "Vui lòng chọn xã!";
            return messageValidate;
        }
        //Validate Site2----------------------------------------------------------------------------------------
        if (site2.trim() == null || site2.trim() == "") {
            messageValidate = "Vui lòng chọn thôn!";
            return messageValidate;
        }
        //Validate Type----------------------------------------------------------------------------------------
        if (type.trim() == null || type.trim() == "") {
            messageValidate = "Vui lòng chọn diện chính sách!";
            return messageValidate;
        }
        //Validate Số thứ tự hộ----------------------------------------------------------------------------------------
        if (index.trim() == null || index.trim() == "") {
            messageValidate = "Vui lòng nhập số thứ tự hộ!";
            return messageValidate;
        }
    }

    function ValidateKhoilop(){
        var name = $('#txtTenkhoilop').val();
        var level = $('#drLevel').val();
        // var school = $('#sltTruongDt').val();
        var unit = $('#sltKhoiDt').val();

        //Validate Name----------------------------------------------------------------------------------------
        if (name.trim() == "") {
            messageValidate = "Vui lòng nhập tên!";
            $('#txtTenkhoilop').focus();
            return messageValidate;
        }else if (name.length > 200) {
            messageValidate = "Tên không được vượt quá 200 ký tự!";
            $('#txtTenkhoilop').focus();
            $('#txtTenkhoilop').val("");
            return messageValidate;
        }
        else{
            var specialChars = "#/|\\";

            for (var i = 0; i < name.length; i++) {
                if (specialChars.indexOf(name.charAt(i)) != -1) {
                    messageValidate = "Tên không được chứa ký tự #, /, |, \\";
                    $('#txtTenkhoilop').focus();
                    return messageValidate;
                }
            }

            $('#txtTenkhoilop').focusout();
        }

        if (level == null || level == "" || level == 0) {
            messageValidate = "Vui lòng chọn khối lớp!";
            return messageValidate;
        }

        // if (school == null || school == "" || school == 0) {
        //     messageValidate = "Vui lòng chọn trường!";
        //     return messageValidate;
        // }

        if (unit == null || unit == "" || unit == 0) {
            messageValidate = "Vui lòng chọn khối!";
            return messageValidate;
        }
    }

//Search AutoComplete-----------------------------------------------------------------------------------------------------------------------
    function autocompleteSearch(idControl, formSearch) {
        var keySearch = "";
        if (formSearch == "GROUP") {
            $('#' + idControl).autocomplete({
                source: function (request, response) {
                    keySearch = $.ui.autocomplete.escapeRegex(request.term).replace(/[%\\\-]/g, '');
                    //console.log(keySearch.length);
                    if (keySearch.length >= 4) {
                        GET_INITIAL_NGHILC();
                        loaddataGroup($('#drPagingGroup').val(),keySearch);
                        
                    }else if(keySearch.length < 4){
                        GET_INITIAL_NGHILC();
                        loaddataGroup($('#drPagingGroup').val(), "");
                    }
                },
                minLength: 0,
                delay: 222,
                autofocus: true
            });
        }

        if (formSearch == "UNIT") {
            $('#' + idControl).autocomplete({
                source: function (request, response) {
                    keySearch = $.ui.autocomplete.escapeRegex(request.term).replace(/[%\\\-]/g, '');
                    //console.log(keySearch.length);
                    if (keySearch.length >= 4) {
                        GET_INITIAL_NGHILC();
                        loaddataUnit($('#drPagingUnit').val(),keySearch);
                        
                    }else if(keySearch.length < 4){
                        GET_INITIAL_NGHILC();
                        loaddataUnit($('#drPagingUnit').val(), "");
                    }
                },
                minLength: 0,
                delay: 222,
                autofocus: true
            });
        }

        if (formSearch == "NATION") {
            $('#' + idControl).autocomplete({
                source: function (request, response) {
                    keySearch = $.ui.autocomplete.escapeRegex(request.term).replace(/[%\\\-]/g, '');
                    //console.log(keySearch.length);
                    if (keySearch.length >= 4) {
                        GET_INITIAL_NGHILC();
                        loaddataNation($('#drPagingNation').val(),keySearch);
                        
                    }else if(keySearch.length < 4){
                        GET_INITIAL_NGHILC();
                        loaddataNation($('#drPagingNation').val(), "");
                    }
                },
                minLength: 0,
                delay: 222,
                autofocus: true
            });
        }

        if (formSearch == "SUBJECT") {
            $('#' + idControl).autocomplete({
                source: function (request, response) {
                    keySearch = $.ui.autocomplete.escapeRegex(request.term).replace(/[%\\\-]/g, '');
                    //console.log(keySearch.length);
                    if (keySearch.length >= 4) {
                        GET_INITIAL_NGHILC();
                        loaddataSubject($('#drPagingSubject').val(),keySearch);
                        
                    }else if(keySearch.length < 4){
                        GET_INITIAL_NGHILC();
                        loaddataSubject($('#drPagingSubject').val(), "");
                    }
                },
                minLength: 0,
                delay: 222,
                autofocus: true
            });
        }

        if (formSearch == "SCHOOL") {
            $('#' + idControl).autocomplete({
                source: function (request, response) {
                    keySearch = $.ui.autocomplete.escapeRegex(request.term).replace(/[%\\\-]/g, '');
                    //console.log(keySearch.length);
                    if (keySearch.length >= 4) {
                        GET_INITIAL_NGHILC();
                        loaddataSchool($('#drPagingSchool').val(),keySearch);
                        
                    }else if(keySearch.length < 4){
                        GET_INITIAL_NGHILC();
                        loaddataSchool($('#drPagingSchool').val(), "");
                    }
                },
                minLength: 0,
                delay: 222,
                autofocus: true
            });
        }

        if (formSearch == "CLASS") {
            $('#' + idControl).autocomplete({
                source: function (request, response) {
                    keySearch = $.ui.autocomplete.escapeRegex(request.term).replace(/[%\\\-]/g, '');
                    //console.log(keySearch.length);
                    if (keySearch.length >= 4) {
                        GET_INITIAL_NGHILC();
                        loaddataClass($('#drPagingClass').val(),keySearch);
                        
                    }else if(keySearch.length < 4){
                        GET_INITIAL_NGHILC();
                        loaddataClass($('#drPagingClass').val(), "");
                    }
                },
                minLength: 0,
                delay: 222,
                autofocus: true
            });
        }

        if (formSearch == "DSHN") {
            $('#' + idControl).autocomplete({
                source: function (request, response) {
                    keySearch = $.ui.autocomplete.escapeRegex(request.term).replace(/[%\\\-]/g, '');
                    //console.log(keySearch.length);
                    if (keySearch.length >= 4) {
                        GET_INITIAL_NGHILC();
                        loaddataDSHN($('#drPagingDSHN').val(), keySearch);
                    }else if(keySearch.length < 4){
                        GET_INITIAL_NGHILC();
                        loaddataDSHN($('#drPagingDSHN').val(), "");
                    }
                },
                minLength: 0,
                delay: 222,
                autofocus: true
            });
        }
    };

//Export Excel----------------------------------------------------------------------------------------------------
    
    function exportExcel(formName) {
        alert(formName);
        window.open('/danh-muc/exportExcel/' + formName,'_blank');
        $.ajax({
            type: "get",
            url:'/danh-muc/exportExcel/' + formName,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                console.log(data);
            }, error: function(data) {
            }
        });
    };

    function exportExcelNation(formName) {
        window.open('/danh-muc/dantoc/exportExcelNation','_blank');
        $.ajax({
            type: "get",
            url:'/danh-muc/dantoc/exportExcelNation',
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                console.log(data);
            }, error: function(data) {
            }
        });
    };

    function exportExcelUnit(formName) {
        window.open('/danh-muc/khoi/exportExcelUnit','_blank');
        $.ajax({
            type: "get",
            url:'/danh-muc/khoi/exportExcelUnit',
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                console.log(data);
            }, error: function(data) {
            }
        });
    };

    function exportExcelGroup(formName) {
        window.open('/danh-muc/nhomdoituong/exportExcelGroup','_blank');
        $.ajax({
            type: "get",
            url:'/danh-muc/nhomdoituong/exportExcelGroup',
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                console.log(data);
            }, error: function(data) {
            }
        });
    };

    function exportExcelSubject(formName) {
        window.open('/danh-muc/doituong/exportExcelSubject','_blank');
        $.ajax({
            type: "get",
            url:'/danh-muc/doituong/exportExcelSubject',
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                console.log(data);
            }, error: function(data) {
            }
        });
    };

    function exportExcelSchool(formName) {
        window.open('/danh-muc/truong/exportExcelSchool','_blank');
        $.ajax({
            type: "get",
            url:'/danh-muc/truong/exportExcelSchool',
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                console.log(data);
            }, error: function(data) {
            }
        });
    };

    function exportExcelClass(formName) {
        window.open('/danh-muc/lop/exportExcelClass','_blank');
        $.ajax({
            type: "get",
            url:'/danh-muc/lop/exportExcelClass',
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                console.log(data);
            }, error: function(data) {
            }
        });
    };