$(function () {
    // $('.sltSelect').select2({
    //     allowClear: true,
    //   });
      validDate = function(val){
        if($(val).val() != ""){
            var key = replaceAll(($(val).val()+""),'/','-');
            var ngay='',thang='',nam='';
            
            if(key.split('-').length > 2){
                ngay = key.split('-')[0];
                thang = key.split('-')[1];
                nam = key.split('-')[2];
                
                  if(ngay.length == 1){
                    ngay = '0'+ngay;
                  }
                  if(thang.length == 1){
                    thang = '0'+thang;
                  }
                  if(nam.length == 2){
                    nam = '20'+nam;
                  }
                  key = ngay+'-'+thang+'-'+nam;
                var check = moment(key, 'DD-MM-YYYY',true).isValid();
                if(check){
                  $(val).val(key); 
                }else{
                  
                  utility.messagehide('group_message',"Ngày tháng không đúng định dạng!",1,5000);
                  $('#datepicker1').focus();
                }
            }else{
                utility.messagehide('group_message',"Ngày tháng không đúng định dạng!",1,5000);
                $('#datepicker1').focus();
                
            }
            
        }      
    };
    var insert = true;
    $('#ex-insert-kpdoituong').html('');
    insertUpdate(1);
    permission(function(){
        var html_view  = '<b> Quản lý kinh phí </b> / Cập nhật mức hỗ trợ theo đối tượng';
        
        if(check_Permission_Feature('1')){
            html_view += '<a style="margin-left: 10px" id="btnInsertKinhPhiDoiTuong" onclick="btnInsertKinhPhiDoiTuong();"  class=" btn btn-success btn-xs pull-right"  ><i class="glyphicon glyphicon-plus"></i> Tạo mới </a > ';
        }
        // if(check_Permission_Feature('4')){
        //     html_view += '<a class="btn btn-success btn-xs pull-right"  href="#"><i class="glyphicon glyphicon-print"></i> Xuất excel</a>';
        // }
        $('#ex-insert-kpdoituong').html(html_view);
    });
    // $('#datepicker1').datepicker({
    //   format: 'dd-mm-yyyy',
    //   autoclose: true
    // });
    // $('#datepicker2').datepicker({
    //   format: 'dd-mm-yyyy',
    //   autoclose: true
    // });
    delBanGhi = function (id) {
        utility.confirm("Xóa bản ghi?", "Bạn có chắc chắn muốn xóa?", function () {
            insertUpdate(1);
            GetFromServer('/kinh-phi/muc-ho-tro-doi-tuong/delId/'+id,function(dataget){
                if(dataget.success != null || dataget.success != undefined){
                        $("#myModal").modal("hide");
                        utility.message("Thông báo",dataget.success,null,3000)
                        GET_INITIAL_NGHILC();
                        loadKinhPhiDoiTuong($('select#viewTableDT').val());
                    }else if(dataget.error != null || dataget.error != undefined){
                        utility.message("Thông báo",dataget.error,null,5000,1)
                    }
                },function(dataget){
                    console.log("delBanGhi Kinh phi doi tuong");
                    console.log(dataget);
                },"","","");
        });
        
    }
    updateBanGhi = function (id) {
        GetFromServer('/kinh-phi/muc-ho-tro-doi-tuong/getId/'+id,function(dataget){
                if(dataget.length >0){
                        insertUpdate(0);
                        insert=false;
                        $("#btnSaveKinhPhiDoiTuong").html('<i class="glyphicon glyphicon-edit"></i> Cập nhật');
                        $("#txtCodeKinhPhi1").attr('disabled','disabled');
                        $('#txtIdKinhPhi').val(dataget[0].id);
                        $('#txtCodeKinhPhi1').val(dataget[0].code);
                        $("#sltTruongDt").selectpicker('val',dataget[0].idLoaiTruong);
                        $("#sltSubject").selectpicker('val',dataget[0].doituong_id);
                        //$("#sltSubject option[value='" + dataget[0].doituong_id + "']").attr('selected', 'selected');
                        // $("#sltTruongDt option[value='" + dataget[0].idLoaiTruong + "']").attr('selected', 'selected');
                        //$("#sltSubject").val(dataget[0].doituong_id).change();
                        // $("#sltTruongDt").val(dataget[0].idLoaiTruong).change();
                       // loadSchoolType(dataget[0].idLoaiTruong);
                        //$("#sltSubject option[value='" + dataget[0].doituong_id + "']").attr('selected', 'selected');
                        $('#txtMoney1').val(dataget[0].money);
                        if(dataget[0].start_date != null){
                            var key = replaceAll((dataget[0].start_date+""),'/','-');
                            $('#datepicker1').val(moment(key).format('DD-MM-YYYY'));
                        }else{
                            $('#datepicker1').val('');
                        }

                        $('#sltTruongDt').attr('disabled', 'disabled').selectpicker('refresh');
                        $('#sltSubject').attr('disabled', 'disabled').selectpicker('refresh');
                        $('#datepicker1').attr('disabled', 'disabled');
                        //$('html,body').scrollTop(0);
                        $('html, body').animate({ scrollTop: 0 }, "slow");
                    }
        },function(dataget){
            console.log("updateBanGhi KPDT");
            console.log(dataget);
        },"","","");
    }
    $('select#viewTableDT').change(function() {
       GET_INITIAL_NGHILC();
       loadKinhPhiDoiTuong($(this).val(),$('#txtSearchDT').val());
    });
    autocompleteSearch("txtSearchDT");
    //$('a#btnInsertKinhPhiDoiTuong').click(function(){
    btnInsertKinhPhiDoiTuong = function(){

        $('#btnSaveKinhPhiDoiTuong').button('reset');
        $('#datepicker1').removeAttr('disabled');
        insertUpdate(0);
        $('#sltTruongDt').focus();
        insert=true;
        $("#btnResetKinhPhiDoiTuong").show();
        $("#btnSaveKinhPhiDoiTuong").html('<i class="glyphicon glyphicon-plus-sign"></i> Thêm mới');
    };
    $('button#btnCancelKinhPhiDoiTuong').click(function(){
        
        insertUpdate(1);
    });
   // loadSchoolType();
  // loadComboxDoiTuong();
    loadKinhPhiDoiTuong($('select#viewTableDT').val(),$('#txtSearchDT').val());
    $('button#btnSaveKinhPhiDoiTuong').click(function(){
        // alert($('#sltTruongDt').val());
        if($('#sltTruongDt').val() !== '' && $('#sltTruongDt').val() > 0){
            if($('#sltSubject').val() !== '' && $('#sltSubject').val() > 0){
                if($('#txtMoney1').val() !== ''){
                    if($('#datepicker1').val() !== ''){
                        $('#btnSaveKinhPhiDoiTuong').button('loading');
                        // if($('#datepicker2').val() !== ''){
                            var v_start = $('#datepicker1').val();
                            // var v_end = $('#datepicker2').val();

                            var v_startDate = v_start.substring(0, 2);
                            var v_startMonth = v_start.substring(3, 5);
                            var v_startYear = v_start.substring(6, v_start.length);

                            // var v_endDate = v_end.substring(0, 2);
                            // var v_endMonth = v_end.substring(3, 5);
                            // var v_endYear = v_end.substring(6, v_end.length);

                            // if ((v_startYear > v_endYear)
                            //     || (v_startYear == v_endYear && v_startMonth > v_endMonth)
                            //     || (v_startYear == v_endYear && v_startMonth == v_endMonth && v_startDate > v_endDate)) {
                            //     utility.message("Thông báo","Ngày hiệu lực không được lớn hơn ngày hết hiệu lực!", null, 5000);
                            // }
                            // else {
                                var temp = {
                                    "id": $('#txtIdKinhPhi').val(),
                                    "idLoaiTruong": $('#sltTruongDt').val(),
                                    "code": $('#txtCodeKinhPhi1').val(),
                                    "idDoiTuong": $('#sltSubject').val(),
                                    "money": $('#txtMoney1').val(),
                                    "startDate": $('#datepicker1').val()
                                };
                                    // "endDate": $('#datepicker2').val()

                                if(insert){
                                    insertKinhPhiDoiTuong(temp);
                                }else{
                                    updateKinhPhiDoiTuong(temp);
                                }
                            // }
                        // }else{
                        //     utility.message("Thông báo","Xin mời nhập ngày hết hiệu lực",null,3000)
                        //     $('#datepicker2').focus();
                        // }
                    }else{
                        utility.message("Thông báo","Xin mời nhập ngày hiệu lực",null,3000,1)
                        $('#datepicker1').focus();
                    }
                }else{
                    utility.message("Thông báo","Xin mời nhập số tiền",null,3000,1)
                    $('#txtMoney1').focus();
                }
            }else{
                utility.message("Thông báo","Xin mời chọn đối tượng",null,3000,1)
                $('#sltSubject').focus();
            }
        }else{
            utility.message("Thông báo","Xin mời chọn loại trường",null,3000,1)
            $('#sltTruongDt').focus();
        }
    });

  });

    function loadSchoolType(idChoice = 0){
        GetFromServer('/danh-muc/truong/loadLoaitruong',function(data){
            var html_show = "";
                $("#sltTruongDt").html("");
                if (data.length > 0) {
                    html_show += "<option value=''>--- Chọn loại trường ---</option>";
                    for (var i = 0; i < data.length; i++) {
                        html_show += "<option value='"+data[i].school_type_id+"'>"+data[i].school_type_name+"</option>";
                    }

                    $("#sltTruongDt").html(html_show);

                    if (idChoice > 0) { $("#sltTruongDt").val(idChoice); }
                }
                else {
                    $("#sltTruongDt").html("<option value=''>--- Chưa có loại trường ---</option>");
                }
            },function(data){
                console.log("loadSchoolType KPĐT");
                console.log(data);
            },"","","");
    }

    function loadComboxDoiTuong() {
        GetFromServer('/kinh-phi/muc-ho-tro-doi-tuong/load-nhom-doi-tuong',function(dataget){
            $('#sltSubject').html("");
                    var html_show = "";
                    if(dataget.length >0){
                      //  $.fn.dataTable.render.number( '.', ',', 0, '' ) 
                        html_show += "<option value=''>-- Chọn nhóm đối tượng --</option>";
                        for (var i = dataget.length - 1; i >= 0; i--) {
                            html_show += "<option value='"+dataget[i].group_id+"'>"+dataget[i].group_name+"</option>";
                        }
                        $('#sltSubject').html(html_show);
                    }else{
                        $('#sltSubject').html("<option value=''>-- Chưa có nhóm đối tượng --</option>");
                    }
                },function(data){
                    console.log("loadComboxDoiTuong KPĐT");
                    console.log(data);
                },"","","");
        };
var CODE_FEATURES ;
function permission(callback) {
    GetFromServer('/kinh-phi/muc-ho-tro-doi-tuong/permission/info',function(dataget){
         CODE_FEATURES = dataget.permission;
                    if(callback!=null){
                        callback();
                    }
    },function(dataget){
       console.log("permission KPĐT");
       console.log(dataget);
    },"","","");
        };
function check_Permission_Feature(featureCode) {
    try {
        if (Object.values(CODE_FEATURES).indexOf(featureCode) >=0) {
            return true;
        }           
        return false;
    } catch (e) {
        console.log(e);
    }
    return true;
}

function loadKinhPhiDoiTuong(row,key = '') {
    $('#dataKinhPhiDoiTuong').html("<tr><td colspan='50' class='text-center'>Đang tải dữ liệu...</td></tr>");
    var html_show = "";
    var o = {};
    if(key!=null){
        o = {
                key : key,
                start: (GET_START_RECORD_NGHILC()),
                limit : row
            };
    }else{
        o = {
                start: (GET_START_RECORD_NGHILC()),
                limit : row
            };
    }
    PostToServer('/kinh-phi/muc-ho-tro-doi-tuong/load',o,function(dataget){

                    SETUP_PAGING_NGHILC(dataget, function () {
                        loadKinhPhiDoiTuong(row,key);
                    });
                    
                    data = dataget.data;
                    //permission = dataget.permission;
                    if(data.length>0){
                        for (var i = 0; i < data.length; i++) {

                            html_show += "<tr><td class='text-center' style='vertical-align:middle'>"+(i + 1 + (GET_START_RECORD_NGHILC() * row))+"</td>";
                            // html_show += "<td>"+data[i].code+"</td>";
                            html_show += "<td style='vertical-align:middle'>"+ConvertString(data[i].school_type_name)+"</td>";
                            html_show += "<td style='vertical-align:middle'>"+ConvertString(data[i].group_name)+"</td>";
                            html_show += "<td style='vertical-align:middle' class='text-right'>"+(data[i].money).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")+"</td>";
                            html_show += "<td style='vertical-align:middle' class='text-center'>"+formatMonth(data[i].start_date)+"</td>";
                            html_show += "<td style='vertical-align:middle' class='text-center'>"+formatMonth(data[i].end_date)+"</td>";
                            html_show += "<td style='vertical-align:middle' class='text-center'>"+formatDates(data[i].updated_at)+"</td>";
                            html_show += "<td style='vertical-align:middle'>"+ConvertString(data[i].username)+"</td>";
               
                            if(check_Permission_Feature('2')){
                                html_show += "<td class='text-center' style='vertical-align:middle'><button data='"+data[i].id+"' onclick='updateBanGhi("+data[i].id+");' class='btn btn-info btn-xs' id='editor_editss'><i class='glyphicon glyphicon-pencil'></i> Sửa</button> </td>";
                            }
                            if(check_Permission_Feature('3')){
                                html_show += "<td class='text-center' style='vertical-align:middle'><button  onclick='delBanGhi("+data[i].id+");' data='"+data[i].id+"' class='btn btn-danger btn-xs editor_remove'><i class='glyphicon glyphicon-remove'></i> Xóa</button></td>";
                            }
                            html_show += "</tr>";
                        }
                        
                    } else {
                        html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
                    }
                    $('#dataKinhPhiDoiTuong').html(html_show);
    },function(dataget){
        console.log("loadKinhPhiDoiTuong KPDT");
        console.log(dataget);
    },"","","");
        };

        function insertKinhPhiDoiTuong(temp) {
            PostToServer('/kinh-phi/muc-ho-tro-doi-tuong/insert',temp,function(dataget){
                if(dataget.success != null || dataget.success != undefined){
                        $("#myModal").modal("hide");
                        utility.message("Thông báo",dataget.success,null,3000)
                        insertUpdate(1);
                        GET_INITIAL_NGHILC();
                        loadKinhPhiDoiTuong($('select#viewTableDT').val());
                        $('#btnSaveKinhPhiDoiTuong').button('reset');
                        $('#datepicker1').removeAttr('disabled');
                    }else if(dataget.error != null || dataget.error != undefined){
                        //$("#myModal").modal("hide");
                        utility.message("Thông báo",dataget.error,null,5000,1)
                        $('#btnSaveKinhPhiDoiTuong').button('reset');
                        $('#datepicker1').removeAttr('disabled');
                        //insertUpdate(1);
                        //loadKinhPhiDoiTuong($('select#viewTableDT').val()); 
                    }
                },function(dataget){
                    console.log("insertKinhPhiDoiTuong KPDT");
                    console.log(dataget);
                },"btnSaveKinhPhiDoiTuong","","");
        };
        function updateKinhPhiDoiTuong(temp) {
            PostToServer('/kinh-phi/muc-ho-tro-doi-tuong/update',temp,function(dataget){
                if(dataget.success != null || dataget.success != undefined){
                        $("#myModal").modal("hide");
                        utility.message("Thông báo",dataget.success,null,3000)
                        insertUpdate(1);
                        GET_INITIAL_NGHILC();
                        loadKinhPhiDoiTuong($('select#viewTableDT').val());
                        $('#btnSaveKinhPhiDoiTuong').button('reset');
                        $('#datepicker1').removeAttr('disabled');
                    }else if(dataget.error != null || dataget.error != undefined){
                        //$("#myModal").modal("hide");
                        utility.message("Thông báo",dataget.error,null,5000,1)
                        $('#btnSaveKinhPhiDoiTuong').button('reset');
                        //insertUpdate(1);
                        //loadKinhPhiDoiTuong($('select#viewTableDT').val()); 
                    }
            },function(dataget){
                console.log("updateKinhPhiDoiTuong KPDT");
                console.log(dataget);
            },"btnSaveKinhPhiDoiTuong","","");
        };

        // var $dateDDMMYYYY = $('#datepicker1').datepicker({
        //     format: 'dd-mm-yyyy',
        //     autoclose: true
        // });

        function insertUpdate(type){
                $("#txtIdKinhPhi").val('');      
                $("#txtCodeKinhPhi1").val('');
                $("#sltSubject").val('');
                $("#txtMoney1").val('');
                $("#datepicker1").val('');
                // $("#datepicker2").val('');

                //-------------------------Clear date-----------------------------------------------------
               // $dateDDMMYYYY.datepicker('setDate', null);
                
                
                $("#sltTruongDt").val('');
                $("#sltSubject").val('');
                //$("#sltTruongDt option").removeAttr('selected');
            if(type===1){
                $("#txtCodeKinhPhi1").attr('disabled','disabled');
                $("#sltSubject").attr('disabled','disabled');
                $("#sltTruongDt").attr('disabled','disabled');
                $("#txtMoney1").attr('disabled','disabled');
                $("#datepicker1").attr('disabled','disabled');
                // $("#datepicker2").attr('disabled','disabled');
                $("#btnSaveKinhPhiDoiTuong").hide();
                $("#btnCancelKinhPhiDoiTuong").hide();
                $("#btnResetKinhPhiDoiTuong").hide();
            }else{
                $("#sltTruongDt").removeAttr('disabled').selectpicker('refresh');
                $("#txtCodeKinhPhi1").removeAttr('disabled');
                $("#sltSubject").removeAttr('disabled').selectpicker('refresh');
                $("#txtMoney1").removeAttr('disabled');
                $("#datepicker1").removeAttr('disabled');
                // $("#datepicker2").removeAttr('disabled');
                $("#btnSaveKinhPhiDoiTuong").removeAttr('disabled');
                $("#btnSaveKinhPhiDoiTuong").show();
                $("#btnResetKinhPhiDoiTuong").hide();
                $("#btnCancelKinhPhiDoiTuong").show();
            }
           
        };

autocompleteSearch = function (idSearch) {
        var lstCustomerForCombobox;
        $('#' + idSearch).autocomplete({
            source: function (request, response) {
                var cusNameSearch = $.ui.autocomplete.escapeRegex(request.term).replace(/[%\\\-]/g, '');
                //alert(cusNameSearch.length);
                if (cusNameSearch.length >= 4) {
                    GET_INITIAL_NGHILC();
                    loadKinhPhiDoiTuong($('select#viewTableDT').val(),cusNameSearch);
                    
                }else if(cusNameSearch.length == 0){
                    GET_INITIAL_NGHILC();
                    loadKinhPhiDoiTuong($('select#viewTableDT').val());
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