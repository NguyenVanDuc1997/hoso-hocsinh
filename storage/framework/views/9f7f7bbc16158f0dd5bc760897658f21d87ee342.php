

<?php $__env->startSection('title', 'This is a blank page'); ?>
<?php $__env->startSection('description', 'This is a blank page that needs to be implemented'); ?>

<?php $__env->startSection('content'); ?>
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<section class="content">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script src="<?php echo asset('/js/myScript.js'); ?>"></script>
<script type="text/javascript" src="<?php echo asset('mystyle/js/jsDanhMuc.js'); ?>"></script>

<script type="text/javascript">

    function message()
    {
      $(".delete_message").slideDown();
    }

    function hide()
    {
      $(".delete_message").slideUp();
    }

  $(document).ready(function () {

    // $('#txtDepartCode').focus();

        $("#cancel").click(function(){
            hide();
          });
          
          $("#btnDeleteConfirm").click(function(){
            var v_id = $('#txtname').attr( "data" );
            //alert(v_id);            
            var v_jsonData = JSON.stringify({ DEPARTMENTID: v_id });
            console.log(v_jsonData);
            $.ajax({
                url: "/deleteDepartment/" + v_jsonData,
                type: "get",
                //data: v_jsonData,
                contentType: "application/json, charset=utf-8",
                success: function (data) {
                    alert(data);
                            window.location.reload();
                },
                error : function (){
                            alert('Có lỗi xảy ra trong quá trình xử lý');
                        }
            });
          });

        $('#txtcode').focus();
        $('#imgValidDepartment').attr("hidden", "hidden");
        $('#imgValidCode').attr("hidden", "hidden");
        $('#imgValidName').attr("hidden", "hidden");
        $('#btnUpdate').attr("hidden", "hidden");
        $('#btnDelete').attr("hidden", "hidden");

        $('#btnInsert').click(function() {
            var v_code = $('#txtcode').val();
            if (v_code.trim() == "") {
                $('#imgValidCode').removeAttr("hidden");
                $('#lblValidCode').html("Vui lòng nhập mã!");
                $('#txtcode').focus();
                return;
            }
            else{
                var specialChars = "!@#$%^&*()+=[]\\\';,./{}|\":<>?";
                var unicodeChars = "àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđÁÀẠẢÃÂẤẦẬẨẪĂẮẰẶẲẴÉÈẸẺẼÊẾỀỆỂỄÍÌỊỈĨÓÒỌỎÕÔỐỒỘỔỖƠỚỜỢỞỠÚÙỤỦŨƯỨỪỰỬỮÝỲỴỶỸĐ";

                for (var i = 0; i < v_code.length; i++) {
                    if (specialChars.indexOf(v_code.charAt(i)) != -1) {
                        $('#imgValidCode').removeAttr("hidden");
                        $('#lblValidCode').html("Mã nhập không được chứa ký tự đặc biệt!");
                        $('#txtcode').focus();
                        $('#txtcode').val("");
                        return;
                    }

                    if (unicodeChars.indexOf(v_code.charAt(i)) != -1) {
                        $('#imgValidCode').removeAttr("hidden");
                        $('#lblValidCode').html("Mã nhập không được chứa ký tự có dấu!");
                        $('#txtcode').focus();
                        $('#txtcode').val("");
                        return;
                    }
                }

                $('#imgValidCode').remove();
                $('#lblValidCode').remove();
                $('#txtcode').focusout();
            }
          
            var v_objData = getData();
            var v_jsonData = JSON.stringify(v_objData);
                alert(v_jsonData);
                         
                    $.ajax({
                        url : "/insertDepartment/" + v_jsonData,
                        type : "get",
                        //data : v_jsonData,
                        contentType: "application/json, charset=utf-8",
                        success : function (data){
                            var my_array = JSON.parse(data);

                                if (my_array['code'] == '1') {
                                    alert(my_array['message']);
                                    window.location.reload();
                                }
                                if (my_array['code'] == '0') {
                                    alert(my_array['message']);
                                    $('#txtcode').focus();
                                }
                        },
                        error : function (){
                            alert('Có lỗi xảy ra trong quá trình xử lý');
                        }
                    });
            });
        
        $('#btnSave').click(function () {
            
            var v_objData = getData();
            var v_jsonData = JSON.stringify(v_objData);
            //alert(v_jsonData);
            $.ajax({
                url: "/updateDepartment/" + v_jsonData,
                type: "get",
                //data: v_jsonData,
                contentType: "application/json, charset=utf-8",
                success: function (data) {
                    alert(data);
                            window.location.reload();
                },
                        error : function (){
                            alert('Có lỗi xảy ra trong quá trình xử lý');
                        }
            });
        });

        $("#btnDelete").click(function(){
            message();
        });
    });
 

    function getData(){
        //Tao doi tuong de gui len Controler
        var v_id = $('#txtname').attr( "data" );
        var v_code = $('#txtcode').val();
        var v_name = $('#txtname').val();
        var v_parent_id = $('#drpDepartment').val();
        var v_active = $('#drActive').val();

        if (v_name.trim() == "") {
            $('#imgValidName').removeAttr("hidden");
            $('#lblValidName').html("Vui lòng nhập tên!");
            $('#txtname').focus(); 
            return null;
        }
        else{
            var specialChars = "#";
            //"!@#$%^&*()+=[]\\\';,./{}|\":<>?";

            for (var i = 0; i < v_name.length; i++) {
                if (specialChars.indexOf(v_name.charAt(i)) != -1) {
                    $('#imgValidName').removeAttr("hidden");
                    $('#lblValidName').html("Tên không được chứa ký tự #!");
                    $('#txtname').focus();
                    //$('#txtname').val("");
                    return;
                }
            }

            $('#imgValidName').remove();
            $('#lblValidName').remove();
            $('#txtname').focusout();
        }

        if (v_parent_id == 0) {
            $('#imgValidDepartment').removeAttr("hidden");
            $('#lblValidDepartment').html("Vui lòng chọn!");
            return null;
        }
        else{
            $('#imgValidDepartment').remove();
            $('#lblValidDepartment').remove();
        }

        if (v_name == "") {return null;}
            
        return ({ DEPARTMENTID: v_id, CODE: v_code, NAME: v_name, DEPARTMENT_PARENT_ID: v_parent_id, ACTIVE: v_active });

    }
</script>
<script type="text/javascript">
$(function () {

    //loadComboDepartment(null);
    $('#using_json_2').jstree({
        'core' : {
            'data' : 
            {
                'url' : function (node) {
                //console.log(node);
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
    $('#using_json_2').on("changed.jstree", function (e, data) {
            var currentIdSelected = data.selected[0];            
            PostToServer('/danh-muc/getDepartmentbyID',{ DEPARTMENTID: currentIdSelected },function(results){
                var level = 0;
                var item = '';
                $('#txtDepartID').val(results[0]['department_id']);
                // $('#txtDepartCode').val(results[0]['department_code']);
                $('#txtDepartName').val(results[0]['department_name']);
                $('#drDepartActive').selectpicker('val',results[0]['department_active']);

                //loadComboDepartment( function(data){
                    $('#drpDepartment').selectpicker('val',results[0]['department_parent_id']);
               //});

                $('#btnInsertDepartment').html('Lưu');
                $("#btnDeleteDepartment").attr("disabled", false);
            },function(results){
                console.log("phong ban");
                console.log(results);
            },"btnInsertDepartment","","");
    });
  
});
</script>


<!-- Phòng ban -->
<div class="panel panel-default">
    <div class="panel-heading">
       <b> Danh mục </b> / Phòng ban
        <!-- <a data-toggle="modal" data-target="#myModal" style="margin-left: 10px" class="btn btn-success btn-xs pull-right"  >
            <i class="glyphicon glyphicon-plus"></i> Tạo mới
        </a >  -->
        <!-- <a class="btn btn-success btn-xs pull-right" href="#" onclick="exportExcel('DEPARTMENT')">
            <i class="glyphicon glyphicon-print"></i> Xuất excel
        </a> -->
    </div>
    </div>
          <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-5">
          <!-- general form elements -->
          <div id="changeTree" class="right-side strech row box box-primary" style="overflow: auto ; max-height: 600px">
            <div class="box-header with-border">
              <h3 class="box-title">Phòng ban</h3>
            </div>
            <!-- /.box-header -->
            
   <div id="using_json_2">
        
    </div>

          </div>
          <!-- /.box -->

          

        </div>
        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-7">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Thông tin</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal">
            <input type="hidden" name="" id="txtDepartID">
              <div class="box-body">
                <!-- <div class="form-group">
                  <label for="inputPassword3" class="col-sm-3 control-label">Mã phòng ban<font style="color: red">*</font></label>

                  <div class="col-sm-9">

                     <input type="text" name="" id="txtDepartCode" maxlength="100" placeholder="Mã phòng ban" class="form-control" accept="charset">
    <img src="../images/Image_valid.png" id="imgValidCode"><label id="lblValidCode" class="valid"></label>
                  </div>
                </div> -->
                <div class="form-group">
                  <label for="inputPassword3" class="col-sm-3 control-label">Tên phòng ban<font style="color: red">*</font></label>

                  <div class="col-sm-9">

                    <input type="text" name="" class="form-control" id="txtDepartName" maxlength="100" placeholder="Tên phòng ban">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-3 control-label">Trực thuộc</label>

                  <div class="col-sm-9">
                    <select name='department' class="form-control selectpicker" data-live-search="true" id='drpDepartment'>
                        <option value="0" selected="selected">-- ROOT --</option>
                        <?php 
                            $departments = DB::table('qlhs_department')->select('department_id', 'department_name', 'department_level')->get();
                        ?>
                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($val->department_id); ?>"><?php echo e($val->department_name); ?></option>
                         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                  </div>
                </div>
                 <div class="form-group">
                  <label for="inputPassword3" class="col-sm-3 control-label">Trạng thái</label>

                  <div class="col-sm-9">
        <select id="drDepartActive" class="form-control selectpicker" data-live-search="true">
            <option value="1">Kích hoạt</option>
            <option value="0">Chưa kích hoạt</option>
        </select>
                  </div>
                </div>
              </div>

                 <div class="modal-footer">
                        <div class="row text-center">
                            <button type="button" class="btn btn-primary" data-loading-text="Đang tải dữ liệu" id="btnInsertDepartment">Lưu</button>
                            <button type="button" class="btn btn-primary" id="btnDeleteDepartment" disabled="true">Xóa</button>
                            <button type="button" class="btn btn-primary" id="btnResetDepartment" data-dismiss="modal">Làm mới</button>
                        </div>
                    </div>

            </form>
          </div>
          <!-- /.box -->
          
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.front', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>