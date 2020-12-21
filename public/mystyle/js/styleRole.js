$(function () {
    var insert = true;
    var onCloseUI = 0;
        
        $('*').click(function (e) {
            try {
                var sender = (e && e.target) || (window.event && window.event.srcElement);
                        //console.log("sender:" + sender.tagName);
                var tagN = sender.tagName;
                if (tagN.toUpperCase() == 'DIV' || tagN.toUpperCase() == 'LABEL') {
                    ('#phongban_tree').addClass("hidden");
                    onCloses = 0;
                }
            } catch (ex) {
                        console.log("ex: " + ex.message);
            }
        });
        $('#phongban_tree').addClass("hidden");
        $('#phongban_name').click(function () {
            console.log("onCloseUI: " + onCloseUI);
            if (onCloseUI == 0) {
                $('#phongban_tree').removeClass("hidden");
                onCloseUI = 1;
            } else {
                $('#phongban_tree').addClass("hidden");
                onCloseUI = 0;
            }
        });
    var currentGet = 0;
    var currentAdd = 0;
    var currentUpdate = 0;
    var currentDelete = 0;
    var currentBusiness = 0;
    delUser = function (id) {
        utility.confirm("Xóa bản ghi?", "Bạn có chắc chắn muốn xóa?", function () {
           resetGroup();
           GetFromServer( '/he-thong/user/delete/'+id,function(dataget){
                if(dataget.success != null || dataget.success != undefined){
                        $("#myModal").modal("hide");
                        utility.message("Thông báo",dataget.success,null,3000)
                        GET_INITIAL_NGHILC();
                        loadTableUser();
                }else if(dataget.error != null || dataget.error != undefined){
                        utility.message("Thông báo",dataget.error,null,3000)
                }
           },function(dataget){
                console.log("delUser");
                console.log(dataget);
           },"","","");
        });
    }
    delGroup = function (id) {
        utility.confirm("Xóa bản ghi?", "Bạn có chắc chắn muốn xóa?", function () {
           resetGroup();
           GetFromServer('/he-thong/role/delete/'+id,function(dataget){
                if(dataget.success != null || dataget.success != undefined){
                        $("#myModal").modal("hide");
                        utility.message("Thông báo",dataget.success,null,3000)
                        GET_INITIAL_NGHILC();
                         loadTableRole();
                }else if(dataget.error != null || dataget.error != undefined){
                        utility.message("Thông báo",dataget.error,null,3000)
                }
           },function(dataget){
                console.log("delGroup");
                console.log(dataget);
           },"","","");
        });
        
    }
    $('select#sltRoleGroup').change(function() {
        callCheckRoleButton($(this).val(),1);
    });
    $('input.apiCheck').change(function() {
        $('#sltRoleGroup').val('').change();
    });


    $('#CheckAllGroupGet').change(function() {
            if ($('#CheckAllGroupGet').prop('checked'))
            {
                currentGet = 10;
                $('[id*="getCheck"]').prop('checked', true);
            }
            else
            {
                currentGet = 0;
                $('[id*="getCheck"]').prop('checked', false);
            }
        });
        $('#CheckAllGroupAdd').change(function() {
            if ($('#CheckAllGroupAdd').prop('checked'))
            {
                currentAdd = 10;
                $('[id*="addCheck"]').prop('checked', true);
            }
            else
            {
                currentAdd = 0;
                $('[id*="addCheck"]').prop('checked', false);
            }
        });
        $('#CheckAllGroupUpdate').change(function() {
            if ($('#CheckAllGroupUpdate').prop('checked'))
            {
                currentUpdate = 10;
                $('[id*="updateCheck"]').prop('checked', true);
            }
            else
            {
                currentUpdate = 0;
                $('[id*="updateCheck"]').prop('checked', false);
            }
        });
        $('#CheckAllGroupDelete').change(function() {
            if ($('#CheckAllGroupDelete').prop('checked'))
            {
                currentDelete = 10;
                $('[id*="deleteCheck"]').prop('checked', true);
            }
            else
            {
                currentDelete = 0;
                $('[id*="deleteCheck"]').prop('checked', false);
            }
        });
        $('#CheckAllGroupBusiness').change(function() {
            if ($('#CheckAllGroupBusiness').prop('checked'))
            {
                currentBusiness = 10;
                $('[id*="businessCheck"]').prop('checked', true);
            }
            else
            {
                currentBusiness = 0;
                $('[id*="businessCheck"]').prop('checked', false);
            }
        });
        checkEvent = function(evt) {

            var evtId = (evt.id).split('Check');
            if (evt.checked)
            {
                if (evtId[0] == 'get')
                {
                    currentGet += 1;
                    if (currentGet == 28)
                        $('#CheckAllGroupGet').prop('checked', true);
                }
                if (evtId[0] == 'add')
                {
                    currentAdd += 1;
                    if (currentAdd == 28)
                        $('#CheckAllGroupAdd').prop('checked', true);
                }
                if (evtId[0] == 'update')
                {
                    currentUpdate += 1;
                    if (currentUpdate == 28)
                        $('#CheckAllGroupUpdate').prop('checked', true);
                }
                if (evtId[0] == 'delete')
                {
                    currentDelete += 1;
                    if (currentDelete == 28)
                        $('#CheckAllGroupDelete').prop('checked', true);
                }
                if (evtId[0] == 'business')
                {
                    currentBusiness += 1;
                    if (currentBusiness == 28)
                        $('#CheckAllGroupBusiness').prop('checked', true);
                }
            }
            else
            {
                if (evtId[0] == 'get')
                {
                    currentGet -= 1;
                    if (currentGet != 10)
                        $('#CheckAllGroupGet').prop('checked', false);
                }
                if (evtId[0] == 'add')
                {
                    currentAdd -= 1;
                    if (currentAdd != 10)
                        $('#CheckAllGroupAdd').prop('checked', false);
                }
                if (evtId[0] == 'update')
                {
                    currentUpdate -= 1;
                    if (currentUpdate != 10)
                        $('#CheckAllGroupUpdate').prop('checked', false);
                }
                if (evtId[0] == 'delete')
                {
                    currentDelete -= 1;
                    if (currentDelete != 10)
                        $('#CheckAllGroupDelete').prop('checked', false);
                }
                if (evtId[0] == 'business')
                {
                    currentBusiness -= 1;
                    if (currentBusiness != 10)
                        $('#CheckAllGroupBusiness').prop('checked', false);
                }
            }
        };
    phanquyen = function(id){
           $("#myModalPhanQuyen").modal("show");
           $('#RoleId').val(id);
           callCheckRoleButton(id,1);
               };

    phanquyennguoidung = function(id,idnhom){
        $("#myModalPhanQuyenUser").modal("show");
        $('#UserId').val(id);
        
        callCheckRoleButton(id,2);
        if(idnhom!=null){
            //alert(idnhom);
            $('#sltRoleGroup').val(idnhom).change();
        }
    };
    $('button#btnUserSave').click(function(){
        
            if($('#txtUsername').val() == ''){
                utility.messagehide('group_message','Xin mời nhập tài khoản.',1,5000);
                $('#txtUsername').focus();
            }else{
                if($('#txtLastname').val() == ''){
                    utility.messagehide('group_message','Xin mời nhập họ và tên.',1,5000);
                    $('#txtLastname').focus();
                }else{
                        if(insert){
                            if($('#txtPassword').val() == ''){
                                    utility.messagehide('group_message','Xin mời nhập mật khẩu.',1,5000);
                                    $('#txtPassword').focus();
                                }else{
                                    if($('#sltSchool').val() == ''){
                                        utility.messagehide('group_message','Xin mời nhập chọn trường.',1,5000);
                                        $('#sltSchool').focus();
                                    }else{
                                          var temp = {
                                            "id": $('#UsersID').val(),
                                            "username": $('#txtUsername').val(),
                                            "first_name": $('#txtFirstname').val(),
                                            "last_name": $('#txtLastname').val(),
                                            "email": $('#txtEmail').val(),
                                            "password": $('#txtPassword').val(),
                                            "phongban_id": $('#phongban_id').val(),
                                            "cap_bac": $('#sltCapbac').val(),
                                            "truong_id": $('#sltSchool').val()
                                        };
                                        insertUsers(temp);

                                    }      
                                }
                        }
                        else{
                            if($('#sltSchool').val() == ''){
                                utility.messagehide('group_message','Xin mời nhập chọn trường.',1,5000);
                                $('#sltSchool').focus();
                            }else{
                                var temp = {
                                    "id": $('#UsersID').val(),
                                    "username": $('#txtUsername').val(),
                                    "first_name": $('#txtFirstname').val(),
                                    "last_name": $('#txtLastname').val(),
                                    "email": $('#txtEmail').val(),
                                    "phongban_id": $('#phongban_id').val(),
                                    "cap_bac": $('#sltCapbac').val(),
                                    "truong_id": $('#sltSchool').val()
                                    };
                            updateUserSave(temp);
                            }
                        }
                    //}
                }
            }
        
    });
    $("a#btnInsertGroups").click(function(){
        $('#txtIdRoleGroup').val('');
        $('#txtRoleCode').val('');
        $('#txtRoleName').val('');
        $('#txtRoleMota').val('');
        $('#updateRole').text('Thêm mới');
        $('.modal-title').text('Thêm mới nhóm quyền');
        insert = true;
    });
    $("a#btnInsertUser").click(function(){
        resetUser();
        $('#btnUserSave').text('Thêm mới');
        insert = true;
        $('#UsersID').val('');
        $("#myModal").modal("show");
    });
    btnUpdateRole = function(id){
            $.ajax({
                type: "GET",
                url: '/he-thong/role/edit/' + id,
                dataType: 'json',
                contentType: 'application/json; charset=utf-8',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                success: function(dataget) {
                    $('#txtIdRoleGroup').val(dataget[0].id);
                    $('#txtRoleCode').val(dataget[0].name);
                    $('#txtRoleName').val(dataget[0].display_name);
                    $('#txtRoleMota').val(dataget[0].description);
                    $('#updateRole').text('Cập nhật');
                    $('.modal-title').text('Cập nhật nhóm quyền');
                    $("#myModal").modal("show");
                    insert = false;
                },error: function(dataget){

                } 
            })
            
        };
    lockUser = function(id){
          // var $v_id = id;
            $.ajax({
                type: "GET",
                url: '/he-thong/user/lock/' + id,
                dataType: 'json',
                contentType: 'application/json; charset=utf-8',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                success: function(dataget) {
                    utility.message("Thông báo","Đã khóa",null,2000);
                       loadTableUser();
                },error: function(dataget){

                } 
            })
            
        };
    unlockUser = function(id){
          // var $v_id = id;
            $.ajax({
                type: "GET",
                url: '/he-thong/user/unlock/' + id,
                dataType: 'json',
                contentType: 'application/json; charset=utf-8',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                success: function(dataget) {
                  utility.message("Thông báo","Đã mở khóa",null,2000);
                       loadTableUser();
                },error: function(dataget){
                    utility.message("Thông báo",dataget.error,null,2000);
                } 
            })
            
        };
        updateUsers = function(id){
          GetFromServer('/he-thong/user/edit/' + id,function(dataget){
                $('#txtUsername').attr('disabled','disabled');
                    $('#UsersID').val(dataget[0].id);
                    $('#txtUsername').val(dataget[0].username);
                    $('#txtLastname').val(dataget[0].last_name);
                    $('#txtFirstname').val(dataget[0].first_name);
                    $('#txtEmail').val(dataget[0].email);
                    $("#txtPassword").attr('disabled','disabled');
                    $('#txtPassword').val('');
                    $("#sltSchool").selectpicker('refresh');
                    if(parseInt(dataget[0].truong_id) != 0){
                        $("#sltSchool").selectpicker('val',(dataget[0].truong_id).split('-'));
                    }else{
                        $('#sltSchool').selectpicker('deselectAll');
                    }
                    $("#sltCapbac").val(dataget[0].level).change();
                    $('#phongban_id').val(dataget[0].phongban_id);
                    $('#phongban_name').val(dataget[0].department_name);
                    $('#btnUserSave').text('Cập nhật');
                    $('.modal-title').text('Cập nhật người dùng');
                    $("#myModal").modal("show");
                    insert = false;
              },function(dataget){
                console.log("updateUsers");
                console.log(dataget);
              },"","","");
            
        };
    $("button#updateProfile").click(function(){
          var temp = {
                "txtLastName": $('#txtLastName').val(),
                "txtFirstName": $('#txtFirstName').val(),
                "txtEmail": $('#txtEmail').val()
            };
            $.ajax({
                type: "POST",
                url:'update',
                data: JSON.stringify(temp),
                dataType: 'json',
                contentType: 'application/json; charset=utf-8',
                 headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                success: function(dataget) {
                    if(dataget.success != null || dataget.success != undefined){
                        utility.message("Thông báo",dataget.success,null,3000) 
                    }else if(dataget.error != null || dataget.error != undefined){
                        utility.message("Thông báo",dataget.error,null,3000) 
                    }
                }, error: function(dataget) {
                    console.log('Cập nhật thông tin của bạn lỗi:'+dataget);
                }
            });
    });
    $("button#btnChangePass").click(function(){
        if($('#txtPassNew').val() != $('#txtRePassNew').val()){
            utility.messagehide('changepass_message',"Xác nhận mật khẩu mới không giống nhau.",1,5000) 
            $('#txtRePassNew').focus();
        }else{
          var temp = {
                "txtPassOld": $('#txtPassOld').val(),
                "txtPassNew": $('#txtPassNew').val(),
                "txtRePassNew": $('#txtRePassNew').val()
            };
            $.ajax({
                type: "POST",
                url:'pass',
                data: JSON.stringify(temp),
                dataType: 'json',
                contentType: 'application/json; charset=utf-8',
                 headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                success: function(dataget) {
                    if(dataget.success != null || dataget.success != undefined){
                        $("#myModal").modal("hide");
                        utility.message("Thông báo",dataget.success,null,3000) 
                    }else if(dataget.error != null || dataget.error != undefined){
                        $("#myModal").modal("hide");
                        utility.message("Thông báo",dataget.error,null,3000) 
                    }
                }, error: function(dataget) {
                    console.log('Cập nhật mật khẩu của bạn lỗi:'+dataget);
                }
            });
        }
    });
    $('#updateRole').click(function () {
      var idG = $('#txtIdRoleGroup').val();
      var code = $('#txtRoleCode').val();
      var name = $('#txtRoleName').val();
      var des = $('#txtRoleMota').val();
            var temp = {"roleName": name, "roleCode": code, "desciption": des, "adRoleId": idG};
            if(insert){
                    $.ajax({
                    type: "POST",
                    url:'/he-thong/role/insert',
                    //url: '/he-thong/group/update',
                    data: JSON.stringify(temp),
                    dataType: 'json',
                    contentType: 'application/json; charset=utf-8',
                     headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
                    success: function(dataget) {
                      if(dataget=1){
                        resultMessage_show('group_message', 'Thêm mới thành công!', 0, 5000);
                      }else{
                        resultMessage_show('group_message', 'Thêm mới thành công!', 1, 5000);
                      }
                      GET_INITIAL_NGHILC();
                       loadTableRole();
                       resetGroup();
                    }, error: function(dataget) {
                       resultMessage_show('group_message', 'Thêm mới lỗi!', 1, 5000); 
                    }
                });
            }else{
                $.ajax({
                type: "POST",
                url:'/he-thong/role/save',
                //url: '/he-thong/group/update',
                data: JSON.stringify(temp),
                dataType: 'json',
                contentType: 'application/json; charset=utf-8',
                 headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
                success: function(dataget) {
                  if(dataget=1){
                    resultMessage_show('group_message', 'Cập nhật thành công!', 0, 5000);
                  }else{
                    resultMessage_show('group_message', 'Cập nhật thành công!', 1, 5000);
                  }
                   loadTableRole();
                }, error: function(dataget) {
                   resultMessage_show('group_message', 'Cập nhật lỗi!', 1, 5000); 
                }
            });
            }
            
        });

    callCheckRoleButton = function(idG,type) {

            currentGet = 0;
            currentAdd = 0;
            currentUpdate = 0;
            currentDelete = 0;
            currentBusiness = 0;
            var url = '';
            if(parseInt(type)==1){
                url = '/he-thong/group/getrole/' + idG;    
            }else{
                url = '/he-thong/user/getrole/' + idG;
            }
            
            $.ajax({
                type: "GET",
                url: url,
                dataType: 'json',
                contentType: 'application/json; charset=utf-8',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                success: function(dataget) {
                    $('.apiCheck').prop('checked', false);
                    var n = dataget.length;
                    for (var i = 0; i < n; i++) {
                        var dl = dataget[i];

                        if (dl.get === 'x')
                        {
                            $('#getCheck' + dl.featureId).prop('checked', true);
                            currentGet += 1;
                        }
                        if (dl.add === 'x')
                        {
                            $('#addCheck' + dl.featureId).prop('checked', true);
                            currentAdd += 1;
                        }
                        if (dl.update === 'x')
                        {
                            $('#updateCheck' + dl.featureId).prop('checked', true);
                            currentUpdate += 1;
                        }
                        if (dl.delete === 'x')
                        {
                            currentDelete += 1;
                            $('#deleteCheck' + dl.featureId).prop('checked', true);
                        }
                        if (dl.business === 'x')
                        {
                            currentBusiness += 1;
                            $('#businessCheck' + dl.featureId).prop('checked', true);
                        }
                    }
                    if (currentGet == 28)
                        $('#CheckAllGroupGet').prop('checked', true);
                    else
                        $('#CheckAllGroupGet').prop('checked', false);
                    if (currentAdd == 28)
                        $('#CheckAllGroupAdd').prop('checked', true);
                    else
                        $('#CheckAllGroupAdd').prop('checked', false);
                    if (currentUpdate == 28)
                        $('#CheckAllGroupUpdate').prop('checked', true);
                    else
                        $('#CheckAllGroupUpdate').prop('checked', false);
                    if (currentDelete == 28)
                        $('#CheckAllGroupDelete').prop('checked', true);
                    else
                        $('#CheckAllGroupDelete').prop('checked', false);
                    if (currentBusiness == 28)
                        $('#CheckAllGroupBusiness').prop('checked', true);
                    else
                        $('#CheckAllGroupBusiness').prop('checked', false);
                }, error: function(dataget) {
                }
            });
        };
    $("#permission_group_form").submit(function() {
            var o = {};
            var a = $('#permission_group_form').serializeArray();
            $.each(a, function() {
                if (o[this.name] !== undefined) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push((this.value));
                } else {
                    o[this.name] = (this.value);
                }
            });

            $.ajax({
                type: "POST",
                url: '/he-thong/group/config',
                data: JSON.stringify(o),
                dataType: 'json',
                contentType: 'application/json; charset=utf-8',
                 headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
                success: function(dataget) {
                    if (dataget === 1) {
                        resultMessage_show('permission_group_message', 'Cập nhật thành công!', 0, 5000);
                    } else
                        resultMessage_show('permission_group_message', 'Cập nhật thành công!', 0, 5000);
                }, error: function(dataget) {
                }
            });

            return false;
        });
$("#permission_user_form").submit(function() {
            var o = {};
            var a = $('#permission_user_form').serializeArray();
            $.each(a, function() {
                if (o[this.name] !== undefined) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push((this.value));
                } else {
                    o[this.name] = (this.value);
                }
            });

            $.ajax({
                type: "POST",
                url: '/he-thong/user/config',
                data: JSON.stringify(o),
                dataType: 'json',
                contentType: 'application/json; charset=utf-8',
                 headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                success: function(dataget) {
                    // console.log(dataget);
                    if (dataget === 1) {
                        resultMessage_show('permission_user_message', 'Cập nhật thành công!', 0, 5000);
                    } else
                        resultMessage_show('permission_user_message', 'Cập nhật thành công!', 0, 5000);
                }, error: function(dataget) {
                }
            });

            return false;
        });


  });
function loadComboxTruongHoc(id,callback,idchoise = null) {
    //alert(idchoise);
            $.ajax({
                type: "GET",
                url: '/danh-muc/load/truong-hoc',
                success: function(data) {
                    // var dataget = data.truong;
                    // var datakhoi = data.khoi;
                    // // <optgroup label="Cats">
                    // $('#sltSchool').html("");
                    // var html_show = "";
                    // if(datakhoi.length >0){
                    //     html_show += "<option value='0'>-- Tất cả các trường --</option>";
                    //     for (var j = 0; j < datakhoi.length; j++) {
                    //     html_show +="<optgroup label='"+datakhoi[j].unit_name+"'>";
                    //         if(dataget.length > 0){
                    //             for (var i = 0; i < dataget.length; i++) {
                    //                 if(datakhoi[j].unit_id === dataget[i].schools_unit_id){
                    //                     html_show += "<option value='"+dataget[i].schools_id+"'>"+dataget[i].schools_name+"</option>";
                    //                 }
                                    
                    //             }
                    //         }    
                    //     html_show +="</optgroup>"
                    //     }
                    //     $('#sltSchool').html(html_show);
                    // }else{
                    //     $('#sltSchool').html("<option value=''>-- Chưa có trường --</option>");
                    // }
                    // if(callback != null){
                    //     callback();
                    // }
                    var dataget = data.truong;
                    $('#'+id).html("");
                    var html_show = "";
                       // html_show += "<option value=''>-- Chọn trường học --</option>";
                        //alert(dataget.length);
                                if(dataget.length > 0){
                                    for (var i = 0; i < dataget.length; i++) {
                                       // alert(idchoise);
                                            if(idchoise != null){
                                               if((idchoise+'').split('-').length == 1 && parseInt(idchoise) != 0){
                                                    if(parseInt(idchoise)===parseInt(dataget[i].schools_id)){
                                                        html_show += "<option value='"+dataget[i].schools_id+"' selected>"+dataget[i].schools_name+"</option>";
                                                    }else{
                                                        html_show += "<option value='"+dataget[i].schools_id+"'>"+dataget[i].schools_name+"</option>";
                                                    } 
                                                }else{

                                                    html_show += "<option value='"+dataget[i].schools_id+"'>"+dataget[i].schools_name+"</option>";
                                                }
                                            }
                                    }
                                    $('#'+id).html(html_show);
                                }
                                else {
                                  $('#'+id).html("<option value=''>-- Chưa có trường --</option>");
                                } 
                    if(callback!=null){
                        callback();
                    }
                }, error: function(dataget) {
                }
            });
        };
function loadComboxNhomQuyen() {
            $.ajax({
                type: "GET",
                url: '/he-thong/role/nhom-quyen',
                success: function(dataget) {
                    $('#sltRoleGroup').html("");
                    var html_show = "";
                    if(dataget.length >0){
                      //  $.fn.dataTable.render.number( '.', ',', 0, '' ) 
                        html_show += "<option value=''>-- Chọn nhóm quyền --</option>";
                        for (var i = dataget.length - 1; i >= 0; i--) {
                            html_show += "<option value='"+dataget[i].id+"'>"+dataget[i].display_name+"</option>";
                        }
                        $('#sltRoleGroup').html(html_show);
                    }else{
                        $('#sltRoleGroup').html("<option value=''>-- Chưa có nhóm quyền --</option>");
                    }
                }, error: function(dataget) {
                }
            });
        };
function loaduserinfo($id) {
            $('#lbStatus').html('');
            $.ajax({
                type: "GET",
                url: '/he-thong/user/info/'+$id,
                 headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
                success: function(dataget) {
                    data = dataget[0];
                        $('#txtLastName').val(data.last_name);
                        $('#txtFirstName').val(data.first_name);
                        $('#txtEmail').val(data.email);

                    if(parseInt(data.activated) === 1 || data.activated === "1"){
                        $('#lbStatus').html('<span style="color: green;">Kích hoạt</span>');
                    }else{
                        $('#lbStatus').html('<span style="color: red;">Chưa kích hoạt '+data.activated+'</span>');
                    }
                }, error: function(dataget) {
                }
            });

            return false;
        };

function loadTableUser(keysearch) {
    var html_show = "";
    var o 
    if(keysearch != null && keysearch != ""){
        o = {
            start: (GET_START_RECORD_NGHILC()),
            limit : $('#viewUser').val(),
            key: keysearch
        };
    }else{
        o = {
            start: (GET_START_RECORD_NGHILC()),
            limit : $('#viewUser').val()
        };
    }
    PostToServer('/he-thong/user/load',o,function(dataget){
        SETUP_PAGING_NGHILC(dataget, function () {
            loadTableUser(keysearch);
        });
        $('#dataUsers').html("");
        data = dataget.data;
        if(data.length>0){
            for (var i = 0; i < data.length; i++){
                html_show += "<tr><td class='text-center' style='vertical-align:middle'>"+(i + 1 + (GET_START_RECORD_NGHILC() * $('#viewUser').val()))+"</td>";
                html_show += "<td style='vertical-align:middle'>"+data[i].username+"</td>";
                html_show += "<td style='vertical-align:middle'>"+data[i].last_name + "</td>";
                html_show += "<td style='vertical-align:middle'>"+(data[i].email)+"</td>";
                html_show += "<td style='vertical-align:middle'>"+formatDateTimes(data[i].updated_at)+"</td>";
                html_show += "<td style='vertical-align:middle'>"+ConvertString(data[i].username_up)+"</td>";
                if(parseInt(data[i].activated) === 1 || data[i].activated === "1"){
                    html_show += "<td style='vertical-align:middle'><button onclick='lockUser("+data[i].id+");' type='button' class='btn btn-success btn-xs'>Đã kích hoạt</button></td>";
                }else{
                    html_show += "<td style='vertical-align:middle'><button onclick='unlockUser("+data[i].id+");' type='button' class='btn btn-warning btn-xs'>Chưa kích hoạt</button></td>";
                }
                html_show += "<td style='vertical-align:middle'><button data='"+data[i].id+"' onclick='updateUsers("+data[i].id+");' class='btn btn-info btn-xs' id='editor_editss'><i class='glyphicon glyphicon-pencil'></i> Sửa</button><button data='"+data[i].nhomquyen_id+"'  onclick='phanquyennguoidung("+data[i].id+","+data[i].nhomquyen_id+");' class='btn btn-info btn-xs' style='margin-left: 5px'><i class='glyphicon glyphicon-pushpin'></i> Phân quyền</button>  <button  onclick='delUser("+data[i].id+");' data='"+data[i].id+"' class='btn btn-danger btn-xs editor_remove'><i class='glyphicon glyphicon-remove'></i> Xóa</button></td></tr>";
            }
            $('#dataUsers').html(html_show);
        }
    },function(dataget){
        console.log("loadTableUser");
        console.log(dataget);
    },"","","");
};

function GET_START_RECORD_NGHILC_ROLE() {
    return parseInt(startRecord);
};
/////////
function loadTableRole(keysearch) {
    var html_show = "";
    var o = {};
    if(keysearch != null && keysearch != ""){
        o = {
            start: (GET_START_RECORD_NGHILC_ROLE()),
            limit : 10,
            key: keysearch
        };
    }else{
        o = {
            start: (GET_START_RECORD_NGHILC_ROLE()),
            limit : 10
        };
    }
    $('#dataRoles').html("<tr><td colspan='50' class='text-center'>Đang tải dữ liệu...</td></tr>");
    PostToServer('/he-thong/role/load',o,function(dataget){
        SETUP_PAGING_NGHILC(dataget, function () {
                        loadTableRole();
                    });
                    data = dataget.data;
                    if(data.length>0){
                        for (var i = 0; i < data.length; i++) {

                            html_show += "<tr><td class='text-center' style='vertical-align:middle'>"+(i + 1 + (GET_START_RECORD_NGHILC() * 5))+"</td>";
                            html_show += "<td style='vertical-align:middle'>"+data[i].name+"</td>";
                            html_show += "<td style='vertical-align:middle'>"+data[i].display_name +"</td>";
                            html_show += "<td style='vertical-align:middle'>"+(data[i].description)+"</td>";
                            html_show += "<td class='text-center' style='vertical-align:middle'>"+formatDateTimes(data[i].updated_at)+"</td>";
                            html_show += "<td class='text-center' style='vertical-align:middle'>"+ConvertString(data[i].username_up)+"</td>";
                            html_show += "<td class='text-center' style='vertical-align:middle'><button data='"+data[i].id+"' onclick='btnUpdateRole("+data[i].id+");' class='btn btn-info btn-xs' id='editor_editss'><i class='glyphicon glyphicon-pencil'></i> Sửa</button></td>";
                            html_show += "<td class='text-center' style='vertical-align:middle'><button data='"+data[i].id+"' onclick='phanquyen("+data[i].id+");' class='btn btn-info btn-xs' style='margin-left: 5px'><i class='glyphicon glyphicon-pushpin'></i> Phân quyền</button> </td>";
                            html_show += "<td class='text-center' style='vertical-align:middle'><button  onclick='delGroup("+data[i].id+");' data='"+data[i].id+"' class='btn btn-danger btn-xs editor_remove'><i class='glyphicon glyphicon-remove'></i> Xóa</button></td></tr>";
                        }
                       
                    } else {
                        html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
                    }
         $('#dataRoles').html(html_show);
    },function(dataget){

    },"","","");
};

resetGroup = function(){
    $('#txtIdRoleGroup').val('');
    $('#txtRoleCode').val('');
    $('#txtRoleName').val('');
    $('#txtRoleMota').val('');
}
resetUser = function(){
    $('#txtUsername').val('');
    $('#txtPassword').val('');
    $('#txtLastname').val('');
    $('#txtFirstname').val('');
    $("#txtUsername").removeAttr('disabled');
    $("#txtPassword").removeAttr('disabled');
    $('#phongban_tree').addClass("hidden");
    onCloses = 0;
    $('#txtEmail').val('');
   // $("#sltRoleGroup option").removeAttr('selected');
    //$('#sltRoleGroup').val('').change();
    // $("#sltSchool option").removeAttr('selected');
    // $("#sltSchool").val(null).multiselect("refresh");
    
   // $('#sltSchool').selectpicker('deselectAll');
    $("#sltSchool").selectpicker('refresh');
                    
   // $("#sltSchool").multiselect("refresh");
    $('#phongban_id').val('');
    $('#phongban_name').val('Bấm vào để chọn phòng ban');
}
loadTreePhongBan = function () {

        var idTree = 'phongban_tree';

       // $('#groupOrganizationInfo').modal('show');
        //   function loadTreeNoneCheckBoxAll(idTree, idStoreValue, idStoreName, check) {
        $('#phongban_tree').jstree({
        'core' : {
            'data' : 
            {
                'url' : function (node) {
                
                return node.id === '#' ?
                '/danh-muc/deptdepartment' :
                '/danh-muc/childdepartment';
                },
                'data' : function (node) {
                //alert(node.id);
                //console.log(node);
                    return { 'id' : node.id };
                }
            }
        }
    });
        $('#' + idTree).on("changed.jstree", function (e, data) {
            var currentIdSelected = data.selected;
          //  var iNode = (currentIdSelected + '').split("-");
           // alert(currentIdSelected);
            $('#phongban_id').val(currentIdSelected);
            $('#' + idTree).addClass("hidden");
            check = 0;
        });
        $('#' + idTree).on("select_node.jstree",
                function (evt, data) {
                    $('#phongban_name').val(data.node.text);
                }
        );

    };

    function insertUsers(temp) {
            PostToServer('insert',temp,function(dataget){
                if(dataget.success != null || dataget.success != undefined){
                        $("#myModal").modal("hide");
                        utility.message("Thông báo",dataget.success,null,3000)
                        resetUser();
                        GET_INITIAL_NGHILC();
                        loadTableUser();
                    }else if(dataget.error != null || dataget.error != undefined){
                        utility.message("Thông báo",dataget.error,null,5000)
                    }
            },function(dataget){
                console.log("insertUsers");
                console.log(dataget);
            },"","","");
        };
        autocompleteSearchs = function (idSearch) {
        var lstCustomerForCombobox;
        $('#' + idSearch).autocomplete({
            source: function (request, response) {
                var cusNameSearch = $.ui.autocomplete.escapeRegex(request.term).replace(/[%\\\-]/g, '');
                //alert(cusNameSearch.length);
                if (cusNameSearch.length >= 4) {
                    GET_INITIAL_NGHILC();
                    loadTableUser(cusNameSearch);
                    
                }else if(cusNameSearch.length == 0){
                    GET_INITIAL_NGHILC();
                    loadTableUser();
                }
            },
            minLength: 0,
            delay: 222,
            autofocus: true
        });
    };
        function updateUserSave(temp) {
            //console.log(temp);
            $.ajax({
                type: "POST",
                url:'update',
                data: JSON.stringify(temp),
                dataType: 'json',
                contentType: 'application/json; charset=utf-8',
                 headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                success: function(dataget) {
                    if(dataget.success != null || dataget.success != undefined){
                        $("#myModal").modal("hide");
                        utility.message("Thông báo",dataget.success,null,3000)
                        //resetUser();
                        //GET_INITIAL_NGHILC();
                        loadTableUser();    
                    }else if(dataget.error != null || dataget.error != undefined){
                        //$("#myModal").modal("hide");
                        utility.message("Thông báo",dataget.error,null,5000)
                        //insertUpdate(1);
                        //loadKinhPhiDoiTuong($('select#viewTableDT').val()); 
                    }
                    
                         
                }, error: function(dataget) {
                }
            });
        };


