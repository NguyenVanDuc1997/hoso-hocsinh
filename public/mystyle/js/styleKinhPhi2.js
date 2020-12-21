$(function () {
    // $("#sltXa").select2({
    //   placeholder: "-- Chọn xã --",
    //   allowClear: true,
    //   focus: open
    // });
    $('#sltYear').datepicker({
      format: 'dd-mm-yyyy',
      autoclose: true
    });
    var insert = true;
    insertUpdate(1);
    autocompleteSearch("txtSearchNH");
    $('#datepicker3').datepicker({
      format: 'dd-mm-yyyy',
      autoclose: true
    });
    $('#datepicker4').datepicker({
      format: 'dd-mm-yyyy',
      autoclose: true
    });
    delBanGhi = function (id) {
        utility.confirm("Xóa bản ghi?", "Bạn có chắc chắn muốn xóa?", function () {
            insertUpdate(1);
            $.ajax({
                    type: "GET",
                    url: '/kinh-phi/muc-ho-tro-hoc-phi/delId/'+id,
                    success: function(dataget) {
                    	if(dataget.success != null || dataget.success != undefined){
                        $("#myModal").modal("hide");
                        utility.message("Thông báo",dataget.success,null,3000)
                        GET_INITIAL_NGHILC();
                        loadKinhPhiNamHoc($('select#viewTableNH').val());
                    }else if(dataget.error != null || dataget.error != undefined){
                        //$("#myModal").modal("hide");
                        utility.message("Thông báo",dataget.error,null,5000,1)
                        //insertUpdate(1);
                        //loadKinhPhiDoiTuong($('select#viewTableDT').val()); 
                    }
                        
                    }, error: function(dataget) {
                }
            });
        });
        
    }
    updateBanGhi = function (id) {
        GetFromServer('/kinh-phi/muc-ho-tro-hoc-phi/getId/'+id,function(dataget){
            if(dataget.length > 0){
                        insertUpdate(0);
                        insert = false;
                        $("#btnSaveKinhPhiNamHoc").html('<i class="glyphicon glyphicon-edit"></i> Cập nhật');
                        $("#txtCodeKinhPhi2").attr('disabled','disabled');
                        $('#txtIdKinhPhi2').val(dataget[0].id);
                        $('#txtCodeKinhPhi2').val(dataget[0].kpcode);
                        var v_startdate = formatDates(dataget[0].start_date);
                        $("#sltYear").val(v_startdate);
                        $("#sltKhoiDt").selectpicker('val',dataget[0].idKhoi);
                        $("#sltXa").selectpicker('val',dataget[0].idXa);
                       // loadComboxXa(parseInt(dataget[0].idXa));
                        $('#txtMoney2').val(dataget[0].money);

                        $("#sltYear").attr('disabled','disabled');
                        $("#sltXa").attr('disabled','disabled');
                        $("html, body").animate({ scrollTop: 0 }, "slow");
                    }
        },function(dataget){
            console.log("updateBanGhi");
            console.log(dataget);
        });
    }
    $('select#viewTableNH').change(function() {
       GET_INITIAL_NGHILC();
       loadKinhPhiNamHoc($(this).val());
    });
    $('a#btnInsertKinhPhiDoiTuong').click(function(){
        insertUpdate(0);
        insert=true;
        $("#btnResetKinhPhiNamHoc").show();
        $("#btnSaveKinhPhiNamHoc").html('<i class="glyphicon glyphicon-plus-sign"></i> Lưu');
    });
    $('button#btnCancelKinhPhiNamHoc').click(function(){
        
        insertUpdate(1);
    });
   // loadComboxTruongHoc();
    //loadComboxXa();
   // getUnitbySchoolID();
   // getUnitAll();
    // loadComboxNamHoc();
    loadKinhPhiNamHoc($('select#viewTableNH').val());
    $('button#btnSaveKinhPhiNamHoc').click(function(){
        if ($('#sltXa').val() !== null && $('#sltXa').val() !== "" && $('#sltXa').val() > 0) {
            if ($('#sltKhoiDt').val() !== null && $('#sltKhoiDt').val() !== "" && $('#sltKhoiDt').val() > 0) {
                if ($('#sltYear').val() !== null && $('#sltYear').val() !== "") {
                    if ($('#txtMoney2').val() !== null && $('#txtMoney2').val() !== "") {
                        // if ($('#datepicker3').val() !== null && $('#datepicker3').val() !== "") {
                        //     if ($('#datepicker4').val() !== null && $('#datepicker4').val() !== "") {
                                var temp = {
                                        "id": $('#txtIdKinhPhi2').val(),
                                        "idXa": $('#sltXa').val(),
                                        "idKhoi": $('#sltKhoiDt').val(),
                                        "startDate": $('#sltYear').val(),
                                        "money": $('#txtMoney2').val(),
                                        // "startDate": $('#datepicker3').val(),
                                        // "endDate": $('#datepicker4').val()
                                    };
                                if(insert){
                                    insertKinhPhiNamHoc(temp);
                                }else{
                                    updateKinhPhiNamHoc(temp);
                                }
                        //     }else{
                        //         utility.message("Thông báo","Xin mời chọn ngày kết thúc",null,3000)
                        //         $('#sltTruong').focus();
                        //     }
                        // }else{
                        //     utility.message("Thông báo","Xin mời chọn ngày hiệu lực",null,3000)
                        //     $('#sltTruong').focus();
                        // }
                    }else{
                        utility.message("Thông báo","Xin mời nhập số tiền",null,3000,1)
                        $('#txtMoney2').focus();
                    }
                }else{
                    utility.message("Thông báo","Xin mời chọn ngày bắt đầu",null,3000,1)
                    $('#sltYear').focus();
                }
            }
            else {
                utility.message("Thông báo","Xin mời chọn khối",null,3000,1)
                $('#sltKhoiDt').focus();
            }
        }else{
            utility.message("Thông báo","Xin mời chọn xã",null,3000,1)
            $('#sltXa').focus();
        }
    });
  
  });
    // function loadComboxNamHoc() {
    //         $.ajax({
    //             type: "GET",
    //             url: '/danh-muc/load/nam-hoc',
    //             success: function(dataget) {
    //                 $('#sltSubject').html("");
    //                 var html_show = "";
    //                 if(dataget.length >0){
    //                   //  $.fn.dataTable.render.number( '.', ',', 0, '' ) 
    //                     html_show += "<option selected='selected' value=''>-- Chọn năm học --</option>";
    //                     for (var i = dataget.length - 1; i >= 0; i--) {
    //                         html_show += "<option value='"+dataget[i].code+"'>"+dataget[i].name+"</option>";
    //                     }
    //                     $('#sltYear').html(html_show);
    //                 }else{
    //                     $('#sltYear').html("<option value=''>-- Chưa có năm học --</option>");
    //                 }
    //             }, error: function(dataget) {
    //             }
    //         });
    //     };
function loadComboxTruongHoc(idchoise = null) {
            $.ajax({
                type: "GET",
                url: '/danh-muc/load/truong-hoc',
                success: function(data) {
                	var dataget = data.truong;
                	var datakhoi = data.khoi;
                	// <optgroup label="Cats">
                    $('#sltTruong').html("");
                    var html_show = "";
                    if(datakhoi.length >0){
                    	html_show += "<option value='0'>-- Chọn trường học --</option>";
                    	for (var j = 0; j < datakhoi.length; j++) {
                      	html_show +="<optgroup label='"+datakhoi[j].unit_name+"'>";
                        	if(dataget.length > 0){
		                        // for (var i = 0; i < dataget.length; i++) {
		                        // 	if(datakhoi[j].unit_id === dataget[i].schools_unit_id){
		                        //     	html_show += "<option value='"+dataget[i].schools_id+"'>"+dataget[i].schools_name+"</option>";
		                        // 	}
		                        // }
                                for (var i = 0; i < dataget.length; i++) {
                                        if(datakhoi[j].unit_id === dataget[i].schools_unit_id){
                                            if(idchoise != null){
                                                if(idchoise.split('-').length == 1 && parseInt(idchoise) != 0){
                                                   // if(idchoise===parseInt(dataget[i].schools_id)){
                                                    html_show += "<option value='"+dataget[i].schools_id+"' selected>"+dataget[i].schools_name+"</option>";
                                                   // }else{
                                                   //     html_show += "<option value='"+dataget[i].schools_id+"'>"+dataget[i].schools_name+"</option>";
                                                   // } 
                                                }else{
                                                    html_show += "<option value='"+dataget[i].schools_id+"'>"+dataget[i].schools_name+"</option>";
                                                }
                                            }
                                        }
                                    }
		                    }    
						html_show +="</optgroup>"
	                    }
                        $('#sltTruong').html(html_show);
                    }else{
                        $('#sltTruong').html("<option value=''>-- Chưa có trường --</option>");
                    }
                }, error: function(dataget) {
                }
            });
        };

        function loadComboxXa(idchoise = 0) {
            $.ajax({
                type: "GET",
                url: '/kinh-phi/muc-ho-tro-hoc-phi/loadXa',
                success: function(data) {
                    // console.log(data);
                    var dataget = data.XA;
                    var datakhoi = data.HUYEN;
                    // <optgroup label="Cats">
                    $('#sltXa').html("");
                    var html_show = "";
                    if(datakhoi.length > 0){
                        html_show += "<option value=''>-- Chọn xã --</option>";
                        for (var j = 0; j < datakhoi.length; j++) {
                        html_show +="<optgroup label='"+datakhoi[j].site_name+"'>";
                            if(dataget.length > 0){
                                for (var i = 0; i < dataget.length; i++) {
                                    if(datakhoi[j].site_id === dataget[i].site_parent_id){
                                        html_show += "<option value='"+dataget[i].site_id+"'>"+dataget[i].site_name+"</option>";
                                    }
                                }
                            }    
                        html_show +="</optgroup>"
                        }
                        $('#sltXa').html(html_show);

                        if (idchoise > 0) { $('#sltXa').val(idchoise); }
                    }else{
                        $('#sltXa').html("<option value=''>-- Chưa có xã nào --</option>");
                    }
                }, error: function(dataget) {
                }
            });
        };

    function getUnitbySchoolID(idchoise = 0) {
        // var objJson = JSON.stringify({SCHOOLID: objData});
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/lop/getUnitbySchoolID',
            // data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                $('#sltKhoiDt').html("");
                var html_show = "";
                if (data.length > 0) {
                    html_show += "<option value=''>-- Chọn khối --</option>";
                    for (var i = 0; i < data.length; i++) {
                        if (parseInt(data[i].unit_id) !== 2) {
                            html_show += "<option value='"+data[i].unit_id+"'>"+data[i].unit_name+"</option>";
                        }
                    }
                    $('#sltKhoiDt').html(html_show);
                }
                else {
                    $('#sltKhoiDt').html("<option value=''>-- Chưa có khối --</option>");
                }

                if(idchoise > 0){
                    $('#sltKhoiDt').val(idchoise);
                }
            }, error: function(data) {
            }
        });
    };

    function getUnitAll(idchoise = 0) {
        // var objJson = JSON.stringify({SCHOOLID: objData});
        //alert(objJson);
        $.ajax({
            type: "POST",
            url:'/danh-muc/lop/getUnitAll',
            // data: objJson,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function(data) {
                $('#sltKhoiDt').html("");
                var html_show = "";
                if (data.length > 0) {
                    html_show += "<option value=''>-- Chọn khối --</option>";
                    for (var i = 0; i < data.length; i++) {
                        html_show += "<option value='"+data[i].unit_id+"'>"+data[i].unit_name+"</option>";
                    }
                    $('#sltKhoiDt').html(html_show);
                }
                else {
                    $('#sltKhoiDt').html("<option value=''>-- Chưa có khối --</option>");
                }

                if(idchoise > 0){
                    $('#sltKhoiDt').val(idchoise);
                }
            }, error: function(data) {
            }
        });
    };

function loadKinhPhiNamHoc(row, keysearch) {
	$('#dataKinhPhiNamHoc').html("<tr><td colspan='50' class='text-center'>Đang tải dữ liệu...</td></tr>");
    var html_show = "";
    var o = {
        start: (GET_START_RECORD_NGHILC()),
        limit: row,
        keysearch: keysearch
    };
    PostToServer('/kinh-phi/muc-ho-tro-hoc-phi/load',o,function(dataget){
        SETUP_PAGING_NGHILC(dataget, function () {
                        loadKinhPhiNamHoc(row, keysearch);
                    });
                    
                    data = dataget.data;
                    // console.log(data);
                    if(data.length > 0){
                        for (var i = 0; i < data.length; i++) {

                            html_show += "<tr><td class='text-center'>"+(i + 1 + (GET_START_RECORD_NGHILC() * 2))+"</td>";
                            html_show += "<td>"+ConvertString(data[i].site_name)+"</td>";
                            html_show += "<td>"+ConvertString(data[i].unit_name)+"</td>";
                            html_show += "<td class='text-right'>"+(data[i].money).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")+"</td>";
                            html_show += "<td class='text-right'>"+formatDates(data[i].start_date)+"</td>";
                            html_show += "<td class='text-right'>"+formatDates(data[i].end_date)+"</td>";
                            html_show += "<td class='text-right'>"+formatDateTimes(data[i].updated_at)+"</td>";
                            html_show += "<td>"+ConvertString(data[i].username)+"</td>";
                            html_show += "<td class='text-center'><button data='"+data[i].id+"' onclick='updateBanGhi("+data[i].id+");' class='btn btn-info btn-xs' id='editor_editss'><i class='glyphicon glyphicon-pencil'></i> Sửa</button> <button  onclick='delBanGhi("+data[i].id+");' data='"+data[i].id+"' class='btn btn-danger btn-xs editor_remove'><i class='glyphicon glyphicon-remove'></i> Xóa</button></td></tr>";
                        }
                        
                    } else {
                        html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
                    }
                    $('#dataKinhPhiNamHoc').html(html_show);
            },function(dataget){
                console.log("loadKinhPhiNamHoc");
                console.log(dataget);
            },"","","");

        };

        function insertKinhPhiNamHoc(temp) {
            PostToServer('/kinh-phi/muc-ho-tro-hoc-phi/insert',temp,function(dataget){
                if(dataget.success != null || dataget.success != undefined){
                        $("#myModal").modal("hide");
                        utility.message("Thông báo",dataget.success,null,3000)
                        insertUpdate(10);
                        GET_INITIAL_NGHILC();
                        loadKinhPhiNamHoc($('select#viewTableNH').val());
                    }else if(dataget.error != null || dataget.error != undefined){
                        //$("#myModal").modal("hide");
                        utility.message("Thông báo",dataget.error,null,5000,1)
                        //insertUpdate(1);
                        //loadKinhPhiDoiTuong($('select#viewTableDT').val()); 
                    }
                },function(dataget){
                    console.log("insertKinhPhiNamHoc");
                    console.log(dataget);
                },"btnSaveKinhPhiNamHoc","","");
        };
        function updateKinhPhiNamHoc(temp) {
            PostToServer('/kinh-phi/muc-ho-tro-hoc-phi/update',temp,function(dataget){
                if(dataget.success != null || dataget.success != undefined){
                        $("#myModal").modal("hide");
                        utility.message("Thông báo",dataget.success,null,3000)
                        insertUpdate(1);
                        GET_INITIAL_NGHILC();
                        loadKinhPhiNamHoc($('select#viewTableNH').val());  
                    }else if(dataget.error != null || dataget.error != undefined){
                        //$("#myModal").modal("hide");
                        utility.message("Thông báo",dataget.error,null,5000,1)
                        //insertUpdate(1);
                        //loadKinhPhiDoiTuong($('select#viewTableDT').val()); 
                    }
            },function(dataget){
                console.log("updateKinhPhiNamHoc");
                console.log(dataget);
            },"btnSaveKinhPhiNamHoc","","");
        };

        //-------------------------Clear date-----------------------------------------------------
        // var $dateDDMMYYYY = $('#datepicker3, #datepicker4').datepicker({
        //     format: 'dd-mm-yyyy',
        //     autoclose: true
        // });

        function insertUpdate(type){
                $("#txtIdKinhPhi2").val('');      
                $("#txtCodeKinhPhi2").val('');
                $("#sltYear").val('');
                $("#txtMoney2").val('');
                $("#datepicker3").val('');
                $("#datepicker4").val('');

                $("#sltXa").selectpicker('val','');//.trigger("change");//.select2('val', '');
                $("#sltKhoiDt").selectpicker('val','');

                //-------------------------Clear date-----------------------------------------------------
                //$dateDDMMYYYY.datepicker('setDate', null);

                // $("#sltYear option").removeAttr('selected');
                // $("#sltTruong option").removeAttr('selected');
                // $("#sltXa option").removeAttr('selected');
            if(type===1){
                $("#txtCodeKinhPhi2").attr('disabled','disabled');
                $("#sltYear").attr('disabled','disabled');
                $("#sltXa").attr('disabled','disabled');
                $("#sltKhoiDt").attr('disabled','disabled');
                $("#txtMoney2").attr('disabled','disabled');
                $("#datepicker3").attr('disabled','disabled');
                $("#datepicker4").attr('disabled','disabled');
                $("#btnSaveKinhPhiNamHoc").hide();
                $("#btnCancelKinhPhiNamHoc").hide();
                $("#btnResetKinhPhiNamHoc").hide();
                $("#sltTruong").attr('disabled','disabled');
                // $("#sltXa").attr('disabled','disabled');
            }else{
                $("#txtCodeKinhPhi2").removeAttr('disabled');
                $("#sltYear").removeAttr('disabled');
                $("#sltXa").removeAttr('disabled').selectpicker('refresh');
                $("#sltKhoiDt").removeAttr('disabled').selectpicker('refresh');
                $("#txtMoney2").removeAttr('disabled');
                $("#datepicker3").removeAttr('disabled');
                $("#datepicker4").removeAttr('disabled');
                $("#btnSaveKinhPhiNamHoc").removeAttr('disabled');
                $("#btnSaveKinhPhiNamHoc").show();
                $("#btnResetKinhPhiNamHoc").hide();
                $("#btnCancelKinhPhiNamHoc").show();
                $("#sltTruong").removeAttr('disabled');
                // $("#sltXa").removeAttr('disabled');
            }

            if (type===10) {
                $("#txtCodeKinhPhi2").removeAttr('disabled');
                $("#sltYear").removeAttr('disabled');
                $("#txtMoney2").removeAttr('disabled');
                $("#datepicker3").removeAttr('disabled');
                $("#datepicker4").removeAttr('disabled');
                $("#btnSaveKinhPhiNamHoc").removeAttr('disabled');
                $("#btnSaveKinhPhiNamHoc").show();
                $("#btnResetKinhPhiNamHoc").show();
                $("#btnCancelKinhPhiNamHoc").show();
                $("#sltTruong").removeAttr('disabled');
                $("#sltXa").removeAttr('disabled');
                $("#sltKhoiDt").removeAttr('disabled');
            }
        }


    autocompleteSearch = function (idSearch) {
        var lstCustomerForCombobox;
        $('#' + idSearch).autocomplete({
            source: function (request, response) {
                var cusNameSearch = $.ui.autocomplete.escapeRegex(request.term).replace(/[%\\\-]/g, '');
                //alert(cusNameSearch.length);
                if (cusNameSearch.length >= 4) {
                    GET_INITIAL_NGHILC();
                    loadKinhPhiNamHoc($('select#viewTableNH').val(),cusNameSearch);
                    
                }else if(cusNameSearch.length == 0){
                    GET_INITIAL_NGHILC();
                    loadKinhPhiNamHoc($('select#viewTableNH').val());
                }
            },
            minLength: 0,
            delay: 222,
            autofocus: true
            // select: function (event, ui) {
            //     var value = ui.item.value;
            //     var customerCode = value.split('-')[0];
            //     var customerName = value.split('-')[1];
            //     //$('#' + idCusCode).val(customerCode);
            //     //$('#' + idCusName).val(customerName);
            //    // $('#' + idCusId).val('');
            //     return false;
            // }
        });
    };