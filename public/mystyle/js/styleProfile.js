$(function () {
    var counter = 1;
    $('#btnAddNewRow').click(function (event) {

        event.preventDefault();
        counter++;
        var newRow = $('<tr id="trContent"><td style="vertical-align:middle"><label id="idnum">' +
            counter + '</label></td><td style="vertical-align:middle"><button id="btnDeleteDecided" class="btn btn-danger btn-xs editor_remove"><i class="glyphicon glyphicon-remove"></i> </button></td><td class="type"><select name="drDecidedType_' + counter + '" id="drDecidedType" class="form-control"><option value="">--- Chọn loại hồ sơ ---</option><option value="MGHP">Miễn giảm học phí</option><option value="CPHT">Chi phí học tập</option><option value="HTAT">Hỗ trợ ăn trưa</option><option value="HTBT">Hỗ trợ bán trú</option><option value="NGNA">Hỗ trợ người nấu ăn</option><option value="HSKT">Hỗ trợ học sinh khuyết tật</option><option value="HSDTTS">Hỗ trợ học sinh dân tộc thiểu số tại huyện Mù Cang Chải và Trạm tấu</option><option value="TONGHOP">Chế độ chính sách ưu đãi</option></select></td><td class="code"><input class="form-control" type="text" name="txtDecidedCode_' + counter + '" id="txtDecidedCode" /></td><td class="number"><input type="text" class="form-control" name="txtDecidedNumber_' + counter + '" id="txtDecidedNumber" /></td><td class="form-control" class="confirmation"><input type="text" class="form-control" name="txtDecidedConfirmation_' + counter + '" id="txtDecidedConfirmation" /></td><td class="confirmdate"><input type="text" placeholder="ngày-tháng-năm" name="txtDecidedConfirmDate_' + counter + '" class="form-control" id="txtDecidedConfirmDate" value=""/></td><td class="uploadfile"><input type="file" name="txtDecidedFileUpload_' + counter + '" id="txtDecidedFileUpload"></td><td class="oldfile" style="vertical-align:middle"><a style="cursor:pointer" ><label style="cursor:pointer" id="lblOldfile" ></label></a></td></tr>');
        // var newRow = $('<tr id="trContent"><td>' +
        //     counter + '</td><td><button id="btnDeleteDecided"  class="btn btn-danger btn-xs editor_remove"><i class="glyphicon glyphicon-remove"></i> </button></td><td class="code"><input type="text" name="" id="txtDecidedCode' +
        //     counter + '"/></td><td class="name"><input type="text" name="" id="txtDecidedName' +
        //     counter + '"/></td><td class="number"><input type="text" name="" id="txtDecidedNumber' +
        //     counter + '"/></td><td class="confirmation"><input type="text" name="" id="txtDecidedConfirmation' +
        //     counter + '"/></td><td class="confirmdate"><input type="date" name="" id="txtDecidedConfirmDate' +
        //     counter + '"/></td><td class="uploadfile"><input type="file" name="" id="txtDecidedFileUpload' +
        //     counter + '"/></td><td class="oldfile"><label id="lblOldfile' +
        //     counter + '"></label></td></tr>');
        $('#tbDecided').append(newRow);
    });
    $("body").on("click", ".download-option", function () {
        var id = $(this).attr("data-id");
        var url = '/ho-so/hoc-sinh/tong-hop-cong-van-da-lap-theo-truong/download?id=' + id;
        window.open(url, '_blank');
    });
    $("body").on("click", ".remove-option", function () {
        var id = $(this).attr("data-id");
        GetFromServer('/ho-so/hoc-sinh/tong-hop-cong-van-da-lap-theo-truong/delete?id=' + id, function (data) {
            if (data.success != null && data.success != undefined && data.success != '') {
                utility.message("Thông báo", data.success, null, 5000, 0);
            } else if (data.error != null && data.error != undefined && data.error != '') {
                utility.message("Thông báo", data.error, null, 5000, 1);
            }
            if (data['data'] != null && data['data'] != undefined) {
                var html_view = '';
                for (var i = 0; i < data['data'].length; i++) {
                    html_view += '<tr class="option-group">';
                    html_view += '<td class="text-center" style="width:10%">' + (i + 1) + '</td>';
                    html_view += '<td class="text-center" style="width:40%">';
                    html_view += '<a href="javascript:void(0);" class="download-option" data-id="' + data['data'][i].attach_id + '" title="Tải về"><i class="fa fa-download" aria-hidden="true"></i> ' + data['data'][i].attach_name + '</a></td>';
                    html_view += '<td class="text-center" style="width:40%">' + formatDateTimes(data['data'][i].updated_at) + '</td>';
                    html_view += '<td class="text-center" style="width:10%">';
                    html_view += '<button type="button" class="btn btn-xs btn-white remove-option" data-id="' + data['data'][i].attach_id + '" title="Xóa">';
                    html_view += '<i class="glyphicon glyphicon-trash"></i></button>';
                    html_view += '</tr>';
                }
                $('#option_container').html(html_view);
            }
        }, function (data) {
            console.log("remove-option-attach");
            console.log(data);
        }, "", "", "");
    });
    $('#saveFileQD').click(function () {
        var formData = new FormData();
        var len = $("input[name*='fileQuyetDinh']")[0].files.length;
        for (var x = 0; x < len; x++) {
            formData.append('file[]', $("input[name*='fileQuyetDinh']")[0].files[x]);
        }
        formData.append('report_name', $("#txtSoCongVan").val());

        PostToServerFormData('/ho-so/hoc-sinh/tong-hop-cong-van-da-lap-theo-truong/upload', formData, function (data) {
            if (data['success'] != null && data['success'] != undefined) {
                utility.message("Thông báo", data['success'], null, 3000, 0);
                $(":file").filestyle('clear');
            } else if (data['error'] != null && data['error'] != undefined) {
                utility.message("Thông báo", data['error'], null, 3000, 1);
            }

            if (data['data'] != null && data['data'] != undefined) {
                var html_view = '';
                for (var i = 0; i < data['data'].length; i++) {
                    html_view += '<tr class="option-group">';
                    html_view += '<td class="text-center" style="width:10%">' + (i + 1) + '</td>';
                    html_view += '<td class="text-center" style="width:40%">';
                    html_view += '<a href="javascript:void(0);" class="download-option" data-id="' + data['data'][i].attach_id + '" title="Tải về"><i class="fa fa-download" aria-hidden="true"></i> ' + data['data'][i].attach_name + '</a></td>';
                    html_view += '<td class="text-center" style="width:40%">' + formatDateTimes(data['data'][i].updated_at) + '</td>';
                    html_view += '<td class="text-center" style="width:10%">';
                    html_view += '<button type="button" class="btn btn-xs btn-white remove-option" data-id="' + data['data'][i].attach_id + '" title="Xóa">';
                    html_view += '<i class="glyphicon glyphicon-trash"></i></button>';
                    html_view += '</tr>';
                }
                $('#option_container').html(html_view);
            }
        }, function (data) {

        }, "", "", "");
    });
    var success_all = true;
    var unsuccess_all = true;
    var callback_all = true;
    var uncallback_all = true;
    $("#sltHocky").change(function () {
        var namnhaphoc = $('#txtYearProfile').val();
        var str = "";

        if ($('#sltDoituong').val() == null) {
            return;
        }

        if ($('#sltDoituong').val().length < 1) {
            utility.messagehide('messageDangersdivKyHoc', "Xin mời chọn đối tượng!", 1, 5000);
            $('#sltHocky').val('').selectpicker('refresh');
        } else {
            $.each($('#sltDoituong').val(), function (index, value) {
                str += value + ",";
            });
            if (namnhaphoc == null || namnhaphoc == '') {
                utility.messagehide('messageDangersdivKyHoc', "Nhập năm nhập học!", 1, 5000);
                $('#formtest').focus();
                $('#sltHocky').val('').selectpicker('refresh');
                $('#txtYearProfile').focus();
            } else {
                var namhoc = namnhaphoc;
                namhoc = namhoc.substr(3, namhoc.length);
                var hocky = $(this).val();
                var truongId = $('#sltTruong').val();
                var arrSubID = [];
                var lopId = $('#sltLop').val();
                var xaId = $('#sltPhuong').val();
                var bantru = $('#sltBantru').val();

                var o = {
                    SCHOOLID: truongId,
                    NQ57: $('#ckbNQ57').val(),
                    CLASSID: lopId,
                    XAID: xaId,
                    YEAR: namhoc,
                    HOCKY: hocky,
                    BANTRU: bantru,
                    ARRSUBJECT: str
                };
                loadMoneybySubject(o);
            }
        }
    });
    $('#updateMoneyByYear').click(function () {
        if ($('#sltYear').val() != "") {
            var namhoc = parseInt($('#sltYear').val());
            utility.confirm("Cập nhật cho năm học '" + namhoc + "-" + (namhoc + 1) + "'", "Sẽ mất một khoảng thời gian để cập nhật cho toàn bộ học sinh.Xin mời đợi!", function () {
                capnhathotrochedo(null, namhoc, $('#drSchoolTHCD').val());
            });
        } else {
            utility.messagehide("messageValidate", 'Xin mời chọn năm học', 1, 3000);
            $('#sltYear').focus();
            return;
        }
    });
    updateCheDo = function (id, name) {
        if ($('#sltYear').val() != "") {
            var namhoc = $('#sltYear').val();
            utility.confirm("Cập nhật <b>" + name + "</b> cho năm học '" + namhoc + "'", "Sẽ mất một khoảng thời gian để cập nhật cho học sinh này.Xin mời đợi!", function () {
                capnhathotrochedo(id, namhoc, $('#drSchoolTHCD').val());
            });
        } else {
            utility.messagehide("messageValidate", 'Xin mời chọn năm học', 1, 3000);
            $('#sltYear').focus();
            return;
        }
    }
    $('#sltNamHoc').change(function () {
        GET_INITIAL_NGHILC();
        loaddataProfile($('select#viewTableProfile').val(), $('#sltTruongGrid').val(), $('select#sltLopGrid').val(), $('#txtSearchProfile').val());
    });
    $('#updateSiteProfile').click(function () {
        if ($('#sltTinh').val() === null || $('#sltTinh').val() === "") {
            utility.messagehide("messageDangers", 'Vui lòng chọn Tỉnh/ thành', 1, 3000);
            return;
        }
        else {
            if ($('#sltQuan').val() === null || $('#sltQuan').val() === "") {
                utility.messagehide("messageDangers", 'Vui lòng chọn Quận/ Huyện', 1, 3000);
                return;
            }
            else {
                if ($('#sltPhuong').val() === null || $('#sltPhuong').val() === "") {
                    utility.messagehide("messageDangers", 'Vui lòng chọn Xã/ Phường', 1, 3000);
                    return;
                }
                // else {
                //   if ($('#txtThonxom').val() === null || $('#txtThonxom').val() === "") {
                //     utility.messagehide("messageDangers", 'Vui lòng chọn Thôn xóm', 1, 3000);
                //     return;
                //   }
                // }
            }
        }

        var o = {
            HISID: hisSite_id,
            PROFILEID: profile_id_HisSite,
            CLASSID: $('#sltLopSiteHis').val(),
            TENTINH: $('#sltTinh').val(),
            TENHUYEN: $('#sltQuan').val(),
            TENXA: $('#sltPhuong').val(),
            TENTHON: $('#txtThonxom').val(),
            STARTDATE: $('#txtStartDate').val()
        }

        PostToServer('/ho-so/updateSite/profile', o, function (dataget) {
            if (dataget.success != null && dataget.success != '' && dataget.success != undefined) {
                $("#myModalProfile").modal("hide");
                utility.message("Thông báo", dataget.success, null, 3000)
                GET_INITIAL_NGHILC();
                loadDataRegistration($('#drPagingRegistration').val(), $('#txtSearchSiteProfile').val());
            } else if (dataget.error != null && dataget.error != '' && dataget.error != undefined) {
                utility.message("Thông báo", dataget.error, null, 3000, 1)
            }
        }, function (result) {
            console.log('updateSiteProfile changeSite: ');
            console.log(result);
        }, "updateSiteProfile", "", "");
    });
    delProfileByClass = function () {
        if ($('#sltTruongGrid').val() == "") {
            utility.message("Thông báo", "Xin mời chọn trường và lớp cần xóa", null, 3000, 1);
        } else {
            if ($('#sltLopGrid').val() == "") {
                utility.message("Thông báo", "Xin mời chọn lớp cần xóa", null, 3000, 1);
                // utility.confirm('Thông báo','Bạn muốn xóa toàn bộ học sinh trường này?',function(){
                //     var o = {
                //         school_id : $('#sltTruongGrid').val(),
                //         class_id  : $('#sltLopGrid').val()
                //     }
                //     delProfileByClassOrSchool(o);
                // });
            } else {
                utility.confirm('Thông báo', 'Bạn muốn xóa toàn bộ học sinh lớp học này?', function () {
                    var o = {
                        school_id: $('#sltTruongGrid').val(),
                        class_id: $('#sltLopGrid').val(),
                        year: $('#sltNamHoc').val()
                    }
                    delProfileByClassOrSchool(o);
                });
            }
        }
    };
    viewDetailMessage = function (type, school_id, report_name) {
        if (parseInt(type) > 0) {
            window.open("/ho-so/hoc-sinh/danh-sach-phong-tra-lai/" + school_id + "/" + report_name, "_self");
        } else {
            window.open("/ho-so/phe-duyet/danh-sach-truong-de-nghi/" + school_id + "/" + report_name, "_self");
        }
    };
    downloadDemo = function () {
        var url = '/ho-so/downloaddemo';
        window.open(url, '_blank');
    }
    getCongVanBySchool = function (id) {
        $('#drSchoolTHCD').val(id).trigger('change');
    };
    getCongVan = function (id, congvan, type = 0) {
        $('#txtSearchProfile').val('');

        getLvUser();

        if (type == 1) {
            var html_view_header = '<b> Hồ sơ </b> / Danh sách trường gửi';
            $('#title-page-Danhsach').html(html_view_header);
        }
        else if (type == 2) {
            var html_view_header = '<b> Hồ sơ </b> / Danh sách chờ phòng Giáo Dục phê duyệt';
            $('#title-page-Danhsach').html(html_view_header);
        }
        else if (type == 3) {
            var html_view_header = '<b> Hồ sơ </b> / Danh sách phòng Giáo Dục đã phê duyệt';
            $('#title-page-Danhsach').html(html_view_header);
        }
        else if (type == 4) {
            var html_view_header = '<b> Hồ sơ </b> / Danh sách phòng Giáo Dục trả lại';
            $('#title-page-Danhsach').html(html_view_header);
        }
        else if (type == 5) {
            var html_view_header = '<b> Hồ sơ </b> / Danh sách chờ phòng Tài Chính phê duyệt';
            $('#title-page-Danhsach').html(html_view_header);
        }
        else if (type == 6) {
            var html_view_header = '<b> Hồ sơ </b> / Danh sách phòng Tài Chính đã phê duyệt';
            $('#title-page-Danhsach').html(html_view_header);
        }
        else if (type == 7) {
            var html_view_header = '<b> Hồ sơ </b> / Danh sách phòng Tài Chính trả lại';
            $('#title-page-Danhsach').html(html_view_header);
        }
        else if (type == 8) {
            var html_view_header = '<b> Hồ sơ </b> / Danh sách chờ Sở phê duyệt';
            $('#title-page-Danhsach').html(html_view_header);
        }
        else if (type == 9) {
            var html_view_header = '<b> Hồ sơ </b> / Danh sách Sở đã phê duyệt';
            $('#title-page-Danhsach').html(html_view_header);
        }
        else if (type == 10) {
            var html_view_header = '<b> Hồ sơ </b> / Danh sách Sở trả lại';
            $('#title-page-Danhsach').html(html_view_header);
        }
        else if (type == 11) {
            var html_view_header = '<b> Hồ sơ </b> / Danh sách phòng Giáo Dục yêu cầu thu hồi';
            $('#title-page-Danhsach').html(html_view_header);
        }
        else if (type == 12) {
            var html_view_header = '<b> Hồ sơ </b> / Danh sách phòng Tài Chính yêu cầu thu hồi';
            $('#title-page-Danhsach').html(html_view_header);
        }
        //$('#drSchoolTHCD').val(id).trigger('change');
        loadComboxTruongHoc("drSchoolTHCD", function () {
            $('#cmisGridHeaders').html("");
            $('#dataListApproved').html("");
            loadReportBySchool(id, function () {
                $('#sltCongvan').val(congvan).trigger('change');
                // alert(type);
                loadDanhSachTongHop(type);
            }, type);
        }, id);
    };
    getCongVanTraLaiBySchool = function (id) {
        $('#drSchoolTHCD').val(id).trigger('change');
    };
    getCongVanTraLai = function (id, congvan) {
        $('#txtSearchProfile').val('');
        //$('#drSchoolTHCD').val(id).trigger('change');
        loadComboxTruongHoc("drSchoolTHCD", function () { }, id);
        $('#cmisGridHeaders').html("");
        $('#dataListPhongRevert').html("");
        loadUnReportBySchool(id, function () {
            $('#sltCongvan').val(congvan).trigger('change');
            loadDanhSachTraLai();
        });
    };
    delSubject = function (id, time) {
        utility.confirm('Thông báo', 'Bản muốn xóa bản ghi này', function () {
            GetFromServer('/ho-so/hoc-sinh/subject/delByProfile/' + time + '/' + id, function (data) {
                if (data.success != null && data.success != undefined && data.success != '') {
                    utility.message("Thông báo", data.success, null, 5000, 0);

                } else if (data.fall != null && data.fall != undefined && data.fall != '') {
                    utility.message("Thông báo", data.fall, null, 5000, 1);
                } else {
                    utility.message("Thông báo", data.error, null, 5000, 1);
                }
                GET_INITIAL_NGHILC();
                loadDataSubject();
            }, function (result) {
                console.log(result);
            }, "", "", "");
        });
    }
    getSubject = function (id, time, type = 0) {
        GetFromServer('/ho-so/hoc-sinh/subject/getByProfile/' + time + '/' + id, function (result) {
            $('#dataSubjectProfile').html("");
            var dataget = result.data;
            var schooltype = result.type;
            var html_show = "";

            var check7441 = false;
            var check4934 = false;
            if (dataget.length > 0) {
                if (getObjects(dataget, 100)) {
                    check7441 = true;
                } else {
                    check7441 = false;
                }
                if (getObjects(dataget, 101)) {
                    check4934 = true;
                } else {
                    check4934 = false;
                }
                for (var i = 0; i < dataget.length; i++) {
                    if (dataget[i].profile_start_time != null) {
                        $('#txtStart_time').val(dataget[i].profile_start_time);
                    }
                    if (dataget[i].profile_end_time != null) {
                        $('#txtEnd_time').val(dataget[i].profile_end_time);
                    }
                    if (dataget[i].profile_subject_profile_id != null) {
                        $('#txtProfileID').val(dataget[i].profile_subject_profile_id);
                    }
                    if (dataget[i].start_year != null) {
                        $('#start_year').val(dataget[i].start_year);
                    }
                    if (dataget[i].end_year != null) {
                        $('#end_year').val(dataget[i].end_year);
                    }
                    if (dataget[i].start_date != null) {
                        if (type == 0) {
                            var start_date = formatDates(dataget[i].start_date);
                            $('#txtstart_year').val(start_date);
                            $('#txtend_year').val(dataget[i].end_year);
                            $('#txtstart_year').attr('disabled', 'disabled');
                        } else {
                            $('#txtstart_year').val('');
                            $('#txtend_year').val('');
                            $('#txtstart_year').removeAttr('disabled');
                        }
                    }


                    if (parseInt(dataget[i].subject_active) != 0) {
                        html_show += "<tr id='" + dataget[i].profile_subject_id + "'>";
                        if (dataget[i].profile_subject_id != null && dataget[i].profile_subject_id != undefined) {
                            html_show += "<td class='text-center' style='vertical-align:middle'><input type='checkbox' onclick='checkDT73(this)' value='" + dataget[i].subject_id + "' id='checkboxactive_" + dataget[i].subject_id + "' checked class='checkboxactive'/></td>";
                        } else {
                            if (check7441 && (parseInt(dataget[i].subject_id) === 74 || parseInt(dataget[i].subject_id) === 41)) {
                                html_show += "<td class='text-center' style='vertical-align:middle'><input type='checkbox' onclick='checkDT73(this)' value='" + dataget[i].subject_id + "' id='checkboxactive_" + dataget[i].subject_id + "' checked class='checkboxactive'/></td>";
                            }
                            else if (check4934 && (parseInt(dataget[i].subject_id) === 49 || parseInt(dataget[i].subject_id) === 34)) {
                                html_show += "<td class='text-center' style='vertical-align:middle'><input type='checkbox' onclick='checkDT73(this)' value='" + dataget[i].subject_id + "' id='checkboxactive_" + dataget[i].subject_id + "' checked class='checkboxactive'/></td>";
                            } else {
                                html_show += "<td class='text-center' style='vertical-align:middle'><input type='checkbox' onclick='checkDT73(this)' value='" + dataget[i].subject_id + "' id='checkboxactive_" + dataget[i].subject_id + "' class='checkboxactive'/></td>";
                            }

                        }
                        html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='getProfileSubById(" + parseInt(dataget[i].profile_subject_id) + ");'>" + dataget[i].subject_name + "</a> <span style='color: red;font-size: 10px;font-style:italic' id='valid_" + dataget[i].subject_id + "'></span></td>";

                        html_show += "</tr>";
                    }
                }

            }
            else {
                html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
            }
            $('#dataSubjectProfile').html(html_show);

            if (type === 0) {//btnUpdateSubject
                $('#btnChangeSubject').addClass('hidden');
                $('#btnUpdateSubject').removeClass('hidden');
            } else {
                $('#btnUpdateSubject').addClass('hidden');
                $('#btnChangeSubject').removeClass('hidden');
            }
            $('#ModalSubjectProfile').modal('show');
            if (getObjects(dataget, 73)) {
                $('#checkboxactive_41').attr('disabled', 'disabled');
                $('#valid_41').html('(Cảnh báo: Đã sử dụng hộ nghèo)');
            } else if (getObjects(dataget, 41)) {
                $('#checkboxactive_73').attr('disabled', 'disabled');
                $('#valid_73').html('(Cảnh báo: Đã sử dụng hộ cận nghèo)');
            }
            if (parseInt(schooltype['type_his_type_id']) == 4) {
                $('#checkboxactive_46').attr('disabled', 'disabled');
                $('#checkboxactive_46').prop('checked', false);
                $('#valid_46').html('(Cảnh báo: Là trường nội trú không được hưởng)');
            } else {

                $('#checkboxactive_46').removeAttr('disabled');
                $('#valid_46').html('');
                //$('#checkboxactive_46').prop('checked', true);
            }
        }, function (result) {
            console.log(result);
        }, "", "", "");
    };
    var insert = true;
    resetControl(1);


    $('#cbxChooseAll').change(function () {
        if ($('#cbxChooseAll').prop('checked'))
            $('[id*="someCheckbox"]').prop('checked', true);
        else
            $('[id*="someCheckbox"]').prop('checked', false);
    });

    // $('#txtBirthday').datepicker({
    //   format: 'dd-mm-yyyy',
    //   autoclose: true,
    // });
    $('#dateOutProfile').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        language: "vi",
    });
    $('#dateChangeProfile').datepicker({
        format: 'mm-yyyy',
        viewMode: "months", 
        minViewMode: "months",
        language: "vi",
        autoclose: true
    });
    // $('#txtYearProfile').datepicker({
    //   format: 'mm-yyyy',
    //   autoclose: true
    // });
    $('#txtDateNghi').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true
    });
    var counter = 0;
    $('#clearFile').click(function () {
        for (var i = 1; i <= counter; i++) {
            $("input[name*='txtDecidedFileUpload_" + i + "']").filestyle('clear');
        }

    });

    //Xem lịch sử năm học - use
    viewHistory = function (id, name) {
        $('.modal-title-up-to').html('Quá trình năm học của học sinh <b>' + name + '</b>');
        GetFromServer('/ho-so/hoc-sinh/viewhistory/' + id, function (data) {
            var html_show = '';
            for (var i = 0; i < data.length; i++) {
                var _date = formatDates(data[i].history_year.split('-')[0] + '-09-01');
                html_show += '<tr>';
                html_show += '<td class="text-center" style="vertical-align:middle">' + (i + 1) + '</td>';
                if (data[i].class_name == null) {
                    html_show += '<td class="text-center" style="vertical-align:middle">Lớp dự kiến</td>';
                } else {
                    html_show += '<td class="text-center" style="vertical-align:middle">' + ConvertString(data[i].class_name) + '</td>';
                }

                html_show += '<td class="text-center" style="vertical-align:middle">' + ConvertString(data[i].history_year) + '</td>';
                if (parseInt(data[i].history_upto_level) === 1) {
                    html_show += '<td class="text-center" style="vertical-align:middle">Lên lớp</td>';
                } else if (parseInt(data[i].history_upto_level) === 2) {
                    html_show += '<td class="text-center" style="vertical-align:middle">Nghỉ học</td>';
                } else if (parseInt(data[i].history_upto_level) === 3) {
                    html_show += '<td class="text-center" style="vertical-align:middle">Học lại</td>';
                } else if (parseInt(data[i].history_upto_level) === 4) {
                    html_show += '<td class="text-center" style="vertical-align:middle">Chuyển lớp</td>';
                } else {
                    if (i == 0) {
                        html_show += '<td class="text-center" style="vertical-align:middle">Nhập học</td>';
                    } else {
                        html_show += '<td class="text-center" style="vertical-align:middle">Dự kiến</td>';
                    }

                }
                if (_date >= formatDates(data[i].P_His_startdate)) {
                    html_show += '<td class="text-center" style="vertical-align:middle">' + _date + '</td>';
                } else {
                    html_show += '<td class="text-center" style="vertical-align:middle">' + formatDates(data[i].P_His_startdate) + '</td>';
                }

                html_show += '<td class="text-center" style="vertical-align:middle">' + formatDates(data[i].p_His_enddate) + '</td>';
                html_show += '</tr>';
            }
            $('#contentPopupUpto').html(html_show);
            openModalHistory();
        }, function (data) {
            console.log("viewHistory");
            console.log(data);
        }, "", "", "");

    };

    // load danh sach doi tuong - use
    loadDataSubject = function (keyword = null, type = null, order = null) {
        var row = $('#drPagingDanhsachtonghop').val();
        // keyword = $('#txtSearchProfile').val();
        var o = {
            schools_id: $('#drSchoolTHCD').val(),
            class_id: $('#sltLopGrid').val(),
            start: (GET_START_RECORD_NGHILC()),
            limit: row,
            year: $('#sltNamHoc').val(),
            key: keyword,
            TYPE: type,
            ORDER: order
        };
        var or = 0;
        if (order == 0 || order == null) {
            or = 1;
        } else {
            or = 0;
        }
        var html_header = '';
        html_header += '<tr class="success">';
        html_header += '<th  class="text-center" style="vertical-align:middle;width: 5%">STT</th>';
        html_header += '<th  class="text-center class-pointer" style="vertical-align:middle;width: 10%"><span  onclick="GET_INITIAL_NGHILC();loadDataSubject(\'' + keyword + '\',1,' + or + ');">Tên học sinh</span></th>';
        html_header += '<th  class="text-center class-pointer" style="vertical-align:middle;width: 10%"><span  onclick="GET_INITIAL_NGHILC();loadDataSubject(\'' + keyword + '\',2,' + or + ');">Ngày sinh</span></th>';
        html_header += '<th  class="text-center class-pointer" style="vertical-align:middle;width: 10%"><span  onclick="GET_INITIAL_NGHILC();loadDataSubject(\'' + keyword + '\',3,' + or + ');">Lớp học</span></th>';
        html_header += '<th  class="text-center class-pointer" style="vertical-align:middle;width: 40%"><span  onclick="GET_INITIAL_NGHILC();loadDataSubject(\'' + keyword + '\',4,' + or + ');">Thuộc đối tượng</span></th>';
        html_header += '<th  class="text-center class-pointer" style="vertical-align:middle;width: 10%"><span  onclick="GET_INITIAL_NGHILC();loadDataSubject(\'' + keyword + '\',5,' + or + ');">Năm áp dụng</span></th>';
        html_header += '<th  class="text-center " style="vertical-align:middle;width: 10%">Kết thúc áp dụng</th>';
        html_header += '<th  class="text-center" colspan="3" style="vertical-align:middle;width: 10%">Chức năng</th>';
        html_header += '</tr>';
        $('#dataHeadSubject').html(html_header);
        $('#dataSubject').html("<tr><td colspan='50' class='text-center' style='vertical-align:middle'>Đang tải dữ liệu</td></tr>");
        PostToServer('/ho-so/hoc-sinh/subject/load', o, function (result) {
            SETUP_PAGING_NGHILC(result, function () {
                loadDataSubject(keyword, type, order);
            });
            $('#dataSubject').html("");
            var dataget = result.data;
            console.log(dataget);
            var html_show = "";
            if (dataget.length > 0) {
                for (var i = 0; i < dataget.length; i++) {

                    html_show += "<tr><td class='text-center' style='vertical-align:middle'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                    html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='getHoSoHocSinh(" + parseInt(dataget[i].profile_id) + ");'>" + ConvertString(dataget[i].profile_name) + "</a></td>";
                    html_show += "<td class='text-center' style='vertical-align:middle'>" + formatDates(dataget[i].profile_birthday) + "</td>";
                    html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(dataget[i].class_name) + "</td>";
                    html_show += "<td style='vertical-align:middle'>" + ConvertString(dataget[i].subject_name) + "</td>";
                    html_show += "<td class='text-center' style='vertical-align:middle'>" + formatMonth(dataget[i].start_date) + "</td>";
                    html_show += "<td class='text-center' style='vertical-align:middle'>" + formatMonth(dataget[i].end_date) + "</td>";
                    // html_show += "<td class='text-center' style='vertical-align:middle'>"+ConvertTimestamp(dataget[i].profile_end_time)+"</td>";

                    if (check_Permission_Feature("2")) {
                        if (dataget[i].is_finish != null && dataget[i].is_finish != undefined && parseInt(dataget[i].is_finish) == 0) {
                            html_show += "<td class='text-center'  style='vertical-align:middle'><button data='" + dataget[i].profile_id + "' onclick='getSubject(" + dataget[i].profile_id + "," + dataget[i].profile_start_time + ",0);' class='btn btn-info btn-xs' id='editor_editss' title='Cập nhật đối tượng'><i class='glyphicon glyphicon-edit'></i></button></td>";
                            html_show += "<td class='text-center' style='vertical-align:middle'></td>";
                        } else {
                            if (parseInt(dataget[i].profile_status) != 1 && (dataget[i].is_finish == null || dataget[i].is_finish == undefined || parseInt(dataget[i].is_finish) == 1)) {
                                html_show += "<td class='text-center' style='vertical-align:middle'><button data='" + dataget[i].profile_id + "' onclick='getSubject(" + dataget[i].profile_id + "," + dataget[i].profile_start_time + ",0);' class='btn btn-info btn-xs' id='editor_editss' title='Cập nhật đối tượng'><i class='glyphicon glyphicon-edit'></i></button></td>";
                                html_show += "<td class='text-center' style='vertical-align:middle'><button data='" + dataget[i].profile_id + "' onclick='getSubject(" + dataget[i].profile_id + "," + dataget[i].profile_start_time + ",1);' class='btn btn-info btn-xs' id='editor_editss' title='Thay đổi đối tượng'><i class='glyphicon glyphicon-retweet'></i> </button></td>";
                            } else {
                                html_show += "<td class='text-center' style='vertical-align:middle'><button data='" + dataget[i].profile_id + "' onclick='getSubject(" + dataget[i].profile_id + "," + dataget[i].profile_start_time + ",0);' class='btn btn-info btn-xs' id='editor_editss' title='Cập nhật đối tượng'><i class='glyphicon glyphicon-edit'></i> </button></td>";
                                html_show += "<td class='text-center' style='vertical-align:middle'></td>";
                            }
                        }

                    }
                    if (check_Permission_Feature("3")) {
                        if (dataget[i].is_finish == null || dataget[i].is_finish == undefined || parseInt(dataget[i].is_finish) == 1) {
                            html_show += "<td class='text-center' style='vertical-align:middle'><button data='" + dataget[i].profile_id + "' onclick='delSubject(" + dataget[i].profile_id + "," + dataget[i].profile_start_time + ");' class='btn btn-danger btn-xs' title='Xóa đối tượng'><i class='glyphicon glyphicon-trash'></i></button></td>";
                        }
                    }
                    html_show += "</tr>";
                }
            }
            else {
                html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
            }
            $('#dataSubject').html(html_show);
        }, function (result) {
            console.log(result);
        }, "btnLoadDataSubject", "loading", "");
    };
    loadUpdateDataSubject = function () {
        var row = $('#drPagingDanhsachtonghop').val();
        var o = {
            schools_id: $('#drSchoolTHCD').val(),
            class_id: $('#sltLopGrid').val(),
            start: (GET_START_RECORD_NGHILC()),
            limit: row,
        };
        PostToServer('/ho-so/hoc-sinh/subject/loadnew', o, function (result) {
            SETUP_PAGING_NGHILC(result, function () {
                loadUpdateDataSubject();
            });
            $('#dataSubject').html("");
            var dataget = result.data;
            var html_show = "";
            if (dataget.length > 0) {
                for (var i = 0; i < dataget.length; i++) {

                    html_show += "<tr><td class='text-center' style='vertical-align:middle'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                    html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='getProfileSubById(" + parseInt(dataget[i].profile_id) + ");'>" + dataget[i].profile_name + "</a></td>";
                    html_show += "<td class='text-center' style='vertical-align:middle'>" + formatDates(dataget[i].profile_birthday) + "</td>";
                    html_show += "<td class='text-center' style='vertical-align:middle'>" + dataget[i].class_name + "</td>";
                    html_show += "<td style='vertical-align:middle'>" + dataget[i].subject_name + "</td>";
                    html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertTimestamp(dataget[i].profile_start_time) + "</td>";
                    // html_show += "<td class='text-center' style='vertical-align:middle'>"+ConvertString(dataget[i].profile_end_time)+"</td>";

                    if (check_Permission_Feature("2")) {
                        html_show += "<td class='text-center' style='vertical-align:middle'><button data='" + dataget[i].profile_id + "' onclick='getSubject(" + dataget[i].profile_id + ");' class='btn btn-info btn-xs' id='editor_editss'><i class='glyphicon glyphicon-pencil'></i> Cập nhật </button></td>";
                    }

                    html_show += "</tr>";
                }
            }
            else {
                html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
            }
            $('#dataSubject').html(html_show);
        }, function (result) {
            console.log(result);
        }, "btnLoadDataSubject", "", "");
    };
    delHoSoHocSinh = function (id) {
        utility.confirm("Xóa bản ghi?", "Bạn có chắc chắn muốn xóa?", function () {
            resetControl(1);
            var o = { PROFILEID: id };
            PostToServer('/ho-so/hoc-sinh/delete', o, function (data) {
                if (data['success'] != "" && data['success'] != undefined) {
                    utility.message("Xóa hồ sơ học sinh", data['success'], null, 3000);
                    resetControl(1);
                    $('#saveProfile').html("Thêm mới");
                    GET_INITIAL_NGHILC();
                    loaddataProfile($('select#viewTableProfile').val(), $('select#sltTruongGrid').val(), $('select#sltLopGrid').val(), $('#txtSearchProfile').val());
                    loaddataDanhSachTongHop($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
                }

                if (data['error'] != "" && data['error'] != undefined) {
                    utility.message("Thông báo", data['error'], null, 3000, 1);
                }
            }, null, null, "", "");
        });

    }
    download_quyetdinh = function (id) {
        var url = 'download_quyetdinh/' + id;
        window.open(url, '_blank');
    }
    // cập nhật hồ sơ học sinh theo năm học - use
    getHoSoHocSinh = function (id) {
        counter = 0;
        $('#txtHistoryId').val('');
        $('#txtHistoryYear').val('');
        $('#saveProfile').html("Cập nhật");
        var namhoc = ($('#sltNamHoc').val() + '').split('-')[0];
        var o = {
            PROFILEID: id,
            YEAR: $('#sltNamHoc').val()
        }

        PostToServer('/ho-so/hoc-sinh/getprofilebyid', o, function (data) {
            var objProfile = data['objProfile'];
            // tên học sinh
            $('#txtNameProfile').val(objProfile[0]['profile_name']);
            // ngày tháng năm sinh
            var v_birthday = formatDates(objProfile[0]['profile_birthday']);
            $('#txtBirthday').val(v_birthday);
            // dân tộc
            $('#sltDantoc').selectpicker('val', objProfile[0]['nationals_id']);

	    $('#sltDoiTuongAn').selectpicker('val',objProfile[0]['target_eat']);

            // cha mẹ 
            $('#txtParent').val(objProfile[0]['profile_parentname']);
            // người giám hộ
            $('#txtGuardian').val(objProfile[0]['profile_guardian']);
            // số thứ tự hộ nghèo 
            $('#txtSTTHoNgheo').val(objProfile[0]['history_parentstt']);
            // id bản ghi lịch sử
            $('#txtHistoryId').val(objProfile[0]['history_id']);

            $('#txtProfileId').val(objProfile[0]['profile_id']);
            // năm học
            $('#txtHistoryYear').val(objProfile[0]['history_year']);
            // thời điểm vào học của năm học
            var _date = formatMonth(namhoc + '-09-01');
            if (_date >= formatMonth(objProfile[0]['P_His_startdate'])) {
                $('#txtYearProfile').val(_date);
            } else {
                $('#txtYearProfile').val(formatMonth(objProfile[0]['P_His_startdate']));
            }
            // link thay đổi đối tượng
            $('#url_hocsinh').html('<a href="/ho-so/hoc-sinh/change/' + objProfile[0]['profile_id'] + '" id="nextChangeSubject"></a>');
            // link thay đổi  hộ khẩu
            $('#url_hokhau').html('<a href="/ho-so/hoc-sinh/cap-nhat-ho-khau/' + objProfile[0]['profile_id'] + '" id="nextSite"></a>');
            // Khóa id trường
            $('select#sltTruong').attr('disabled', 'disabled');
            loadComboxLop(objProfile[0]['profile_school_id'], 'sltLop', function () {
                if (objProfile[0]['profile_bantru'] != null) {
                    $('#sltBantru').selectpicker('val', objProfile[0]['profile_bantru']);
                } else {
                    $('#sltBantru').selectpicker('val', '-1');
                }
            }, parseInt(objProfile[0]['profile_class_id']));

            $('input#sltTinh').val(objProfile[0].tinhthanh);
            $('input#sltQuan').val(objProfile[0].quanhuyen);
            $('input#sltPhuong').val(objProfile[0].phuongxa);
            $('input#txtThonxom').val(objProfile[0].thonxom);
            $('#tbMoney').attr('hidden', 'hidden');
            $('#sltHocky').selectpicker('val', '').trigger('change');

            var v_status = objProfile[0]['profile_status'];
            //alert(v_status);
            if (v_status == 2) {
                $('#ckbNghihoc').prop('checked', true);
                $('#txtDateNghi').val(formatDates(objProfile[0]['p_His_enddate']));
                $('div#divNgayNghi').removeAttr('hidden');
                $('div#divNgayNghi').removeAttr('disabled');
            }
            else {
                $('#ckbNghihoc').prop('checked', false);
                $('div#divNgayNghi').attr('hidden', 'hidden');
                $('div#divNgayNghi').attr('disabled', 'disabled');
            }

            var v_statusNQ57 = objProfile[0]['profile_statusNQ57'];
            if (v_statusNQ57 == 1 || parseInt(v_statusNQ57) == 1) {
                $('#ckbNQ57').attr("checked", true);
                $('#ckbNQ57').val(1);
                $('.badge').css("text-indent", "0");
            }
            else {
                $('#ckbNQ57').prop('checked', false);
                $('#ckbNQ57').val(0);
                $('.badge').css("text-indent", "-999999px");
            }



            var arrData = "";

            var arrProfileSub = data['arrProfileSub'];

            var countSub = 0;
            for (var i = 0; i < arrProfileSub.length; i++) {
                if (parseInt(arrProfileSub[i]['profile_subject_subject_id']) > 0) {
                    countSub++;
                }
                if (parseInt(arrProfileSub[i]['profile_subject_subject_id']) == 100) {
                    //74-41
                    arrData += "74,41,";
                } else if (parseInt(arrProfileSub[i]['profile_subject_subject_id']) == 101) {
                    //49-34
                    arrData += "49,34,";
                } else {
                    arrData += (arrProfileSub[i]['profile_subject_subject_id']) + ",";
                }

            }

            var item = arrData.split(",");
            if (countSub == 0) {
                $('#sltDoituong').prop('disabled', true);
            } else {
                $('#sltDoituong').prop('disabled', true);
            }
            loadComboxDoiTuong(function () {
                $('#sltDoituong').selectpicker('deselectAll');
                $('#sltDoituong').selectpicker('val', item);
            });
            $('input#txtKhoangcach').val(objProfile[0]['profile_km']);
            $('input#drGiaoThong').val(objProfile[0]['profile_giaothong']);
            var arrProfileDec = data['arrProfileDec'];
            if (arrProfileDec.length > 0) {
                $('#tableMoreProfile').removeAttr('hidden');
                close = false;
            } else {
                $('#tableMoreProfile').attr('hidden', 'hidden');
                close = true;
            }

            for (var i = 0; i < arrProfileDec.length; i++) {
                var dated = arrProfileDec[i]['decided_confirmdate'];
                counter++;
                var newRow = $('<tr id="trContent"><td style="vertical-align:middle"><label id="idnum">' +
                    counter + '</label></td><td style="vertical-align:middle"><button id="btnDeleteDecided" class="btn btn-danger btn-xs editor_remove"><i class="glyphicon glyphicon-remove"></i> </button></td><td class="type"><select name="drDecidedType_' + counter + '" id="drDecidedType" class="form-control"><option value="">--- Chọn loại hồ sơ ---</option><option value="MGHP">Miễn giảm học phí</option><option value="CPHT">Chi phí học tập</option><option value="HTAT">Hỗ trợ ăn trưa</option><option value="HTBT">Hỗ trợ bán trú</option><option value="NGNA">Hỗ trợ người nấu ăn</option><option value="HSKT">Hỗ trợ học sinh khuyết tật</option><option value="HSDTTS">Hỗ trợ học sinh dân tộc thiểu số tại huyện Mù Cang Chải và Trạm tấu</option><option value="TONGHOP">Chế độ chính sách ưu đãi</option></select></td><td class="code"><input class="form-control" type="text" name="txtDecidedCode_' + counter + '" id="txtDecidedCode" value="' + arrProfileDec[i]['decided_code'] + '"/></td><td class="number"><input type="text" class="form-control" name="txtDecidedNumber_' + counter + '" id="txtDecidedNumber" value="' + arrProfileDec[i]['decided_number'] + '"/></td><td class="form-control" class="confirmation"><input type="text" class="form-control" name="txtDecidedConfirmation_' + counter + '" id="txtDecidedConfirmation" value="' + arrProfileDec[i]['decided_confirmation'] + '"/></td><td class="confirmdate"><input type="text" placeholder="ngày-tháng-năm" name="txtDecidedConfirmDate_' + counter + '" class="form-control" id="txtDecidedConfirmDate" value="' + dated + '"/></td><td class="uploadfile"><input type="file" name="txtDecidedFileUpload_' + counter + '" id="txtDecidedFileUpload"></td><td class="oldfile" style="vertical-align:middle"><a style="cursor:pointer" onclick="download_quyetdinh(' + arrProfileDec[i]['decided_id'] + ')"><label style="cursor:pointer" id="lblOldfile" >' + arrProfileDec[i]['decided_filename'] + '</label></a></td></tr>');

                $('#tbDecided').append(newRow);
                $("select[name*='drDecidedType_" + counter + "']").val(arrProfileDec[i]['decided_type']).trigger('change');
                $("input[name*='txtDecidedFileUpload_" + counter + "']").filestyle({
                    buttonText: ' ',
                    buttonName: 'btn-info'
                });
                $("input[name*='txtDecidedConfirmDate_" + counter + "']").datepicker({
                    format: 'dd-mm-yyyy',
                    autoclose: true
                });
                $("input[name*='txtDecidedConfirmDate_" + counter + "']").datepicker('setDate', new Date(arrProfileDec[i]['decided_confirmdate']));
            }
            openModalUpdate();
        }, function (data) {
            console.log("getHoSoHocSinh");
            console.log(data);
        }, "", "loading", "");
    }
    $('select#viewTableProfile').change(function () {
        GET_INITIAL_NGHILC();
        loaddataProfile($(this).val(), $('select#sltTruongGrid').val(), $('select#sltLopGrid').val(), $('#txtSearchProfile').val());
    });
    $('select#sltTruong').change(function () {
        if ($(this).val() == "") {
            $('#sltBantru').removeAttr('disabled');
            $('#sltBantru').selectpicker('val', '-1');
            $('#sltBantru').selectpicker('refresh');
            // $('#txtKhoangcach').val('');
            $('#checkboxactive_46').prop('checked', false);
            $('#ckbNQ57').prop('checked', false);
            $('#txtKhoangcach').removeAttr('disabled');

            $('#drGiaoThong').removeAttr('disabled');

            $('#ckbNQ57').removeAttr('disabled');
            $('#checkboxactive_46').removeAttr('disabled');
            $('#valid_46').html('');
            $('#valid_bantru').html('');
            $('.create_alert').html('');
        }
        if (parseInt($(this).val()) != 0) {
            loading();
            loadComboxLop($(this).val(), 'sltLop', function () {
                closeLoading();
            });
            $('select#sltLop').removeAttr('disabled');
            $('select#txtYearProfile').removeAttr('disabled');
        } else {
            $('select#sltLop').html('<option value="">--Chọn lớp--</option>');
            $('select#sltLop').attr('disabled', 'disabled');
        }
    });
    $('select#sltTruongGrid').change(function () {
        if ($(this).val() != null && $(this).val() != "" && parseInt($(this).val()) != 0) {
            loading();
            loadComboxLop($(this).val(), 'sltLopGrid', function () {
                closeLoading();
                $('#sltLopGrid').selectpicker('refresh');
            });
            $('select#sltLopGrid').removeAttr('disabled');

        } else {
            $('select#sltLopGrid').html('<option value="">--Chọn lớp--</option>');
            $('select#sltLopGrid').attr('disabled', 'disabled');
            $('#sltLopGrid').selectpicker('refresh');
        }
        GET_INITIAL_NGHILC();
        loaddataProfile($('select#viewTableProfile').val(), $(this).val(), $('select#sltLopGrid').val(), $('#txtSearchProfile').val());
    });

    $('#drSchoolUpto').change(function () {
        if ($(this).val() != "") {
            if (parseInt($(this).val()) != 0) {
                loading();
                loadComboxLop($(this).val(), 'drClassUpto', function () {
                    closeLoading();
                });
                $('#drClassUpto').removeAttr('disabled');
            } else {
                $('#drClassUpto').html('<option value="">--Chọn lớp--</option>');
                $('#drClassUpto').attr('disabled', 'disabled');
            }
        } else {
            resetControl();
            $('#drClassUpto').html('<option value="">--Chọn lớp--</option>');
            $('#drClassUpto').attr('disabled', 'disabled');
            $('#drYearUpto').html('<option value="">--Chọn năm học--</option>');
            $('#drYearUpto').attr('disabled', 'disabled');
            // $('#drClassNext').html('<option value="">--Chọn lớp--</option>');
            $('#drClassNext').attr('disabled', 'disabled');


        }
    });
    // Chức năng chọn lớp hiện tại - use
    $('#drClassUpto').change(function () {// chọn năm học
        if ($(this).val() != "") {
            if ($('#drYearUpto').val() != "") {
                $('#drYearUpto').removeAttr('disabled');
                $('#drClassNext').removeAttr('disabled');
                $('#drYearUpto').selectpicker('refresh');
                $('#drClassNext').selectpicker('refresh');
                $('#upClass-select-all').prop('checked', false);
                loadTableUpto($('#drPagingUpto').val());
            }
            loadClassByClass($('#drClassNext').val());
        } else {
            $('#drYearUpto').attr('disabled', 'disabled');
            $('#drClassNext').attr('disabled', 'disabled');
            $('#drClassNext').selectpicker('refresh');
            $('#drYearUpto').selectpicker('refresh');
        }

    });

    $('select#drClassNext').change(function () {

        if (parseInt($(this).val()) === 2) {
            // Nghỉ học
            // $('#btnUpto').html("Thực hiện");
            $('#dateOutProfile').show();
            $('#labelOutProfile').show();
            $('#dateOutProfile').removeAttr('disabled');
            $('#dateOutProfile').datepicker('setDate', new Date());
            // đóng form học lại
            $('#drClassBack').selectpicker('hide');
            $('#labelClassBack').hide();
            $('#drClassBack').addClass('disabled', 'disabled');
            $('#drClassBack').val('').selectpicker('refresh');
            //
            $('#dateChangeProfile').hide();
            $('#labelChangeProfile').hide();
            $('#dateChangeProfile').addClass('disabled', 'disabled');
            // đóng form lên lớp
            $('#labelClassNext').hide();
            $('#StlClassNext').selectpicker('hide');
            $('#StlClassNext').addClass('disabled', 'disabled');
            $('#StlClassNext').val('').selectpicker('refresh');
            // if($('#drYearUpto').val() != $('#timenow').val()){
            //     $('#drYearUpto').selectpicker('val',$('#timenow').val()).trigger('change');
            //     utility.messagehide("message_Upto", "Yêu cầu nghỉ học ở năm học hiện tại "+$('#timenow').val(), 1, 5000);
            // }

        } else if (parseInt($(this).val()) === 3) {
            // Học lại
            // $('#btnUpto').html("Thực hiện");            
            $('#drClassBack').selectpicker('show');
            $('#labelClassBack').show();
            $('#drClassBack').removeAttr('disabled');
            $('#drClassBack').selectpicker('refresh');
            // đóng form nghỉ học
            $('#dateOutProfile').hide();
            $('#labelOutProfile').hide();
            $('#dateOutProfile').addClass('disabled', 'disabled');

            // đóng form lên lớp
            $('#labelClassNext').hide();
            $('#StlClassNext').selectpicker('hide');
            $('#StlClassNext').addClass('disabled', 'disabled');
            $('#StlClassNext').val('').selectpicker('refresh');
            loadClassByClass($(this).val());
        } else if (parseInt($(this).val()) === 1) {
            // Lên lớp
            // $('#btnUpto').html("Lên lớp");
            $('#labelClassNext').show();
            $('#StlClassNext').selectpicker('show');
            //$('#StlClassNext').removeClass('hidden');
            $('#StlClassNext').removeAttr('disabled');
            $('#StlClassNext').selectpicker('refresh');
            // đóng form học lại

            $('#drClassBack').selectpicker('hide');
            $('#labelClassBack').hide();
            $('#drClassBack').addClass('disabled', 'disabled');
            $('#drClassBack').val('').selectpicker('refresh');

            //
            $('#dateChangeProfile').hide();
            $('#labelChangeProfile').hide();
            $('#dateChangeProfile').addClass('disabled', 'disabled');

            // đóng form nghỉ học
            $('#dateOutProfile').hide();
            $('#labelOutProfile').hide();
            $('#dateOutProfile').addClass('disabled', 'disabled');
            loadClassByClass($(this).val());
        } else if( parseInt($(this).val()) === 4 ) {
            $('#drClassBack').selectpicker('show');
            $('#labelClassBack').show();
            $('#drClassBack').removeAttr('disabled');
            $('#drClassBack').selectpicker('refresh');

            $('#dateChangeProfile').removeAttr('disabled');
            $('#dateChangeProfile').show();
            $('#labelChangeProfile').show();

            // đóng form nghỉ học
            $('#dateOutProfile').hide();
            $('#labelOutProfile').hide();
            $('#dateOutProfile').addClass('disabled', 'disabled');

            // đóng form lên lớp
            $('#labelClassNext').hide();
            $('#StlClassNext').selectpicker('hide');
            $('#StlClassNext').addClass('disabled', 'disabled');
            $('#StlClassNext').val('').selectpicker('refresh');
            loadClassByClass($(this).val());
        }
        else {
            // Chưa chọn
            $('#btnUpto').html("Thực hiện");
            $('#labelClassNext').hide();

            $('#StlClassNext').selectpicker('hide');
            $('#StlClassNext').addClass('disabled', 'disabled');
            $('#StlClassNext').val('').selectpicker('refresh');
            // đóng form học lại

            $('#drClassBack').selectpicker('hide');
            $('#labelClassBack').hide();
            $('#drClassBack').addClass('disabled', 'disabled');
            $('#drClassBack').val('').selectpicker('refresh');
            // đóng form nghỉ học
            $('#dateOutProfile').hide();
            $('#labelOutProfile').hide();
            $('#dateOutProfile').addClass('disabled', 'disabled');

            $('#dateChangeProfile').hide();
            $('#labelChangeProfile').hide();
            $('#dateChangeProfile').addClass('disabled', 'disabled');
        }
    });
    $('select#sltLopGrid').change(function () {
        GET_INITIAL_NGHILC();
        loaddataProfile($('select#viewTableProfile').val(), $('select#sltTruongGrid').val(), $(this).val(), $('#txtSearchProfile').val());
    });


    $('select#sltTinh').change(function () {
        if ($(this).val() == "" || $(this).val() == null) {
            $('select#txtThonxom').attr('disabled', 'disabled');
            $('select#sltPhuong').attr('disabled', 'disabled');
            $('select#sltQuan').attr('disabled', 'disabled');
            $('select#sltPhuong').html('<option value="">--Chọn danh mục--</option>');
            $('select#txtThonxom').html('<option value="">--Chọn danh mục--</option>');
            $('select#sltQuan').html('<option value="">--Chọn danh mục--</option>');
        } else {
            loadComboxTinhThanh($(this).val(), 'sltQuan', function () {
                $('select#sltQuan').removeAttr('disabled');
                $('select#sltPhuong').attr('disabled', 'disabled');
                $('select#sltPhuong').html('<option value="">--Chọn danh mục--</option>');
            });
        }
    });
    $('select#sltQuan').change(function () {
        if ($(this).val() == "" || $(this).val() == null) {
            $('select#txtThonxom').attr('disabled', 'disabled');
            $('select#sltPhuong').attr('disabled', 'disabled');
            $('select#sltPhuong').html('<option value="">--Chọn danh mục--</option>');
            $('select#txtThonxom').html('<option value="">--Chọn danh mục--</option>');
            $('select#sltPhuong').selectpicker('refresh');
            $('select#txtThonxom').selectpicker('refresh');
        } else {
            // $('select#sltPhuong').select2({
            //     placeholder: "Đang tải dữ liệu"
            // });
            loadComboxTinhThanh($(this).val(), 'sltPhuong', function () {
                $('select#sltPhuong').removeAttr('disabled');
                // $('select#sltPhuong').select2({
                //    placeholder: "-- Chọn xã/ phường --"
                // });
                // $('select#sltPhuong').focus();
                $('select#txtThonxom').attr('disabled', 'disabled');
                $('select#sltPhuong').selectpicker('refresh');
                $('select#txtThonxom').selectpicker('refresh');
            });
            $('select#txtThonxom').html('<option value="">--Chọn danh mục--</option>');
            $('select#txtThonxom').selectpicker('refresh');
        }

    });
    $('select#sltPhuong').change(function () {
        if ($(this).val() == "" || $(this).val() == null) {
            $('select#txtThonxom').selectpicker('refresh');
        } else {
            // $('select#txtThonxom').select2({
            //     placeholder: "Đang tải dữ liệu"
            // });
            loadComboxTinhThanh($(this).val(), 'txtThonxom', function () {
                // $('select#txtThonxom').select2({
                //     placeholder: "-- Chọn thôn/xóm --"
                // });
                $('select#txtThonxom').removeAttr('disabled');
                //$('select#txtThonxom').focus();
                $('select#txtThonxom').selectpicker('refresh');
            });
        }
    });

    $('input#ckbNghihoc').click(function () {
        if (!$(this).is(':checked')) {
            $('div#divNgayNghi').attr('hidden', 'hidden');
            $('div#divNgayNghi').attr('disabled', 'disabled');

        } else {
            $('div#divNgayNghi').removeAttr('hidden');
            $('div#divNgayNghi').removeAttr('disabled');
        }
        $('input#txtDateNghi').val('');
    });

    $('a#btnInsertKinhPhiDoiTuong').click(function () {
        resetControl(0);
        insert = true;
        $("#btnResetKinhPhiDoiTuong").show();
        $("#btnSaveKinhPhiDoiTuong").html('<i class="glyphicon glyphicon-plus-sign"></i> Lưu');
    });
    $('button#btnCancelKinhPhiDoiTuong').click(function () {

        resetControl(1);
    });

    loadComboxDoiTuong();
    $('button#saveProfile').click(function () {
        var form_datas = new FormData();
        var decided = true;
        //var arrObjDecided = [];
        var num = 0;
        if (counter > 0) {
            $('#tbDecided tr').each(function (i) {
                num = i;
                var idnum = $(this).find("#idnum").text();
                var v_type = $(this).find("#drDecidedType").val();

                var v_code = $(this).find("#txtDecidedCode").val();

                //$(this).find("#txtDecidedName").val();                

                var v_number = $(this).find("#txtDecidedNumber").val();

                var v_confirmation = $(this).find("#txtDecidedConfirmation").val();

                var v_confirmdate = $(this).find("#txtDecidedConfirmDate").val();

                var v_fildOld = $(this).find("#lblOldfile").text();
                var v_name = change_alias(v_type + '_' + v_code + '_' + v_number + '_' + v_confirmdate);
                if (v_type == "" || v_type == undefined) {
                    utility.messagehide("messageDangersQD", "Xin mời chọn loại quyết định", 1, 5000);
                    $("input[name*='drDecidedType_" + (idnum) + "']").focus();
                    decided = false;
                    return;
                } else {
                    if (v_code == "" || v_code == undefined) {
                        utility.messagehide("messageDangersQD", "Xin mời nhập mã quyết định", 1, 5000);
                        $("input[name*='txtDecidedCode_" + (idnum) + "']").focus();
                        decided = false;
                        return;
                    } else {
                        if (v_name == "" || v_name == undefined) {
                            utility.messagehide("messageDangersQD", "Xin mời nhập tên quyết định", 1, 5000);
                            $("input[name*='txtDecidedName_" + (idnum) + "']").focus();
                            decided = false;
                            return;
                        } else {
                            if (v_number == "" || v_number == undefined) {
                                utility.messagehide("messageDangersQD", "Xin mời nhập số quyết định", 1, 5000);
                                $("input[name*='txtDecidedNumber_" + (idnum) + "']").focus();
                                decided = false;
                                return;
                            } else {
                                if (v_confirmation == "" || v_confirmation == undefined) {
                                    utility.messagehide("messageDangersQD", "Xin mời nhập cơ quan xác nhận", 1, 5000);
                                    $("input[name*='txtDecidedConfirmation_" + (idnum) + "']").focus();
                                    decided = false;
                                    return;
                                } else {
                                    if (v_confirmdate == "" || v_confirmdate == undefined) {
                                        utility.messagehide("messageDangersQD", "Xin mời nhập ngày xác nhận", 1, 5000);
                                        $("input[name*='txtDecidedConfirmDate_" + (idnum) + "']").focus();
                                        decided = false;
                                        return;
                                    } else {
                                        if ($(this).find("#txtDecidedFileUpload").prop('files')[0] == undefined && v_fildOld == "") {
                                            utility.messagehide("messageDangersQD", "Xin mời chọn đính kèm", 1, 5000);
                                            $("label[name*='labelAttach_" + (idnum) + "']").removeClass('btn-success').addClass('btn-warning');
                                            decided = false;
                                            return;  //btn-warning // btn-success
                                        } else {
                                            $("label[name*='labelAttach_" + (idnum) + "']").addClass('btn-success').removeClass('btn-warning');
                                            form_datas.append("decided_type_" + i, v_type);
                                            form_datas.append("code_" + i, v_code);
                                            form_datas.append("name_" + i, v_name);
                                            form_datas.append("confirmation_" + i, v_confirmation);
                                            form_datas.append("confirmdate_" + i, v_confirmdate);
                                            form_datas.append("number_" + i, v_number);
                                            form_datas.append("file_" + i, $(this).find("#txtDecidedFileUpload").prop('files')[0]);
                                            form_datas.append("fileold_" + i, v_fildOld);
                                            decided = true;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }


            });
            form_datas.append("decided_number", num + 1);
        }

        var v_status;
        var status = $("#ckbNghihoc").is(":checked");
        if (status == true) { v_status = 1; }
        else { v_status = 0; }
        var v_statusNQ57;
        var status_NQ57 = $("#ckbNQ57").is(":checked");
        if (status_NQ57 == true) { v_statusNQ57 = 1; }
        else { v_statusNQ57 = 0; }

        var arrSubID = []; var str = "";
        var $el = $(".multiselect-container");
        $el.find('li.active input').each(function () {
            str += $(this).val() + "-";
        });

        var year = $('#sltYear').val();
        var numberYear = 0;
        // console.log(year);
        if (year !== null && year !== "" && year !== undefined) {
            var ky = year.split("-");
            numberYear = ky[1];
        }

        form_datas.append('PROFILEID', profile_id);
        form_datas.append('PROFILENAME', $('#txtNameProfile').val());
        form_datas.append('PROFILEBIRTHDAY', $('#txtBirthday').val());
        form_datas.append('PROFILENATIONALID', $('#sltDantoc').val());
        form_datas.append('PROFILESITE1', $('#sltTinh').val());
        form_datas.append('PROFILESITE2', $('#sltQuan').val());
        form_datas.append('PROFILESITE3', $('#sltPhuong').val());
        form_datas.append('PROFILEHOUSEHOLD', $('#txtThonxom').val());
        form_datas.append('PROFILEPARENTNAME', $('#txtParent').val());
        form_datas.append('profile_guardian', $('#txtGuardian').val());
        form_datas.append('PROFILEYEAR', $('#txtYearProfile').val());
        form_datas.append('PROFILESCHOOLID', $('#sltTruong').val());
        form_datas.append('PROFILECLASSID', $('#sltLop').val());
        form_datas.append('PROFILESTATUS', v_status);
        form_datas.append('PROFILESTATUSNQ57', v_statusNQ57);
        form_datas.append('PROFILELEAVESCHOOLDATE', $('#txtDateNghi').val());
        form_datas.append('ARRSUBJECTID', str.substr(0, str.length - 1));
        form_datas.append('ARRDECIDED', "");
        form_datas.append('PROFILEBANTRU', $('#sltBantru').val() != "-1" ? $('#sltBantru').val() : "");
        form_datas.append('PROFILEKM', $('#txtKhoangcach').val());
        form_datas.append('PROFILEGIAOTHONG', $('#drGiaoThong').val());
        form_datas.append('CURRENTYEAR', numberYear);


        messageValidate = "";
        if (messageValidate !== "") {

            utility.messagehide("messageDangers", messageValidate, 1, 5000);

            return;
        }
        else {
            if (decided) {
                if (profile_id == 0) {
                    insertProfile(form_datas);
                }
                else {
                    updateProfile(form_datas);
                }
            }
        }
    });
    //Modify NGHILC - use  Update form hoc sinh
    $('button#saveProfileNew').click(function () {

        var form_datas = new FormData();
        var decided = true;
        var num = 0;
        form_datas.append('targetsId', $('#sltDoiTuongAn').val());
        if (counter > 0) {
            // file đính kèm 
            $('#tbDecided tr').each(function (i) {
                num = i;
                var idnum = $(this).find("#idnum").text();
                var v_type = $(this).find("#drDecidedType").val();
                var v_code = $(this).find("#txtDecidedCode").val();
                var v_number = $(this).find("#txtDecidedNumber").val();
                var v_confirmation = $(this).find("#txtDecidedConfirmation").val();
                var v_confirmdate = $(this).find("#txtDecidedConfirmDate").val();
                var v_fildOld = $(this).find("#lblOldfile").text();
                var v_name = change_alias(v_type + '_' + v_code + '_' + v_number + '_' + v_confirmdate);
                if (v_type == "" || v_type == undefined) {
                    utility.messagehide("messageDangersQD", "Xin mời chọn loại quyết định", 1, 5000);
                    $("input[name*='drDecidedType_" + (idnum) + "']").focus();
                    decided = false;
                    return;
                } else {
                    if (v_code == "" || v_code == undefined) {
                        utility.messagehide("messageDangersQD", "Xin mời nhập mã quyết định", 1, 5000);
                        $("input[name*='txtDecidedCode_" + (idnum) + "']").focus();
                        decided = false;
                        return;
                    } else {
                        if (v_name == "" || v_name == undefined) {
                            utility.messagehide("messageDangersQD", "Xin mời nhập tên quyết định", 1, 5000);
                            $("input[name*='txtDecidedName_" + (idnum) + "']").focus();
                            decided = false;
                            return;
                        } else {
                            if (v_number == "" || v_number == undefined) {
                                utility.messagehide("messageDangersQD", "Xin mời nhập số quyết định", 1, 5000);
                                $("input[name*='txtDecidedNumber_" + (idnum) + "']").focus();
                                decided = false;
                                return;
                            } else {
                                if (v_confirmation == "" || v_confirmation == undefined) {
                                    utility.messagehide("messageDangersQD", "Xin mời nhập cơ quan xác nhận", 1, 5000);
                                    $("input[name*='txtDecidedConfirmation_" + (idnum) + "']").focus();
                                    decided = false;
                                    return;
                                } else {
                                    if (v_confirmdate == "" || v_confirmdate == undefined) {
                                        utility.messagehide("messageDangersQD", "Xin mời nhập ngày xác nhận", 1, 5000);
                                        $("input[name*='txtDecidedConfirmDate_" + (idnum) + "']").focus();
                                        decided = false;
                                        return;
                                    } else {
                                        if ($(this).find("#txtDecidedFileUpload").prop('files')[0] == undefined && v_fildOld == "") {
                                            utility.messagehide("messageDangersQD", "Xin mời chọn đính kèm", 1, 5000);
                                            $("label[name*='labelAttach_" + (idnum) + "']").removeClass('btn-success').addClass('btn-warning');
                                            decided = false;
                                            return;  //btn-warning // btn-success
                                        } else {
                                            $("label[name*='labelAttach_" + (idnum) + "']").addClass('btn-success').removeClass('btn-warning');
                                            form_datas.append("decided_type_" + i, v_type);
                                            form_datas.append("code_" + i, v_code);
                                            form_datas.append("name_" + i, v_name);
                                            form_datas.append("confirmation_" + i, v_confirmation);
                                            form_datas.append("confirmdate_" + i, v_confirmdate);
                                            form_datas.append("number_" + i, v_number);
                                            form_datas.append("file_" + i, $(this).find("#txtDecidedFileUpload").prop('files')[0]);
                                            form_datas.append("fileold_" + i, v_fildOld);
                                            decided = true;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }


            });
            form_datas.append("decided_number", num + 1);
        }
        profile_id = $("#txtProfileId").val();
        var v_status;
        var status = $("#ckbNghihoc").is(":checked");
        if (status == true) { v_status = 1; }
        else { v_status = 0; }
        //nghị định 57
        var v_statusNQ57 = $("#ckbNQ57").val();

        var arrSubID = [];
        var str = "";
        var check7341 = false;
        var check46 = false;
        //$("input[name*='checkboxactive']").each(function () {
        if (profile_id != null && profile_id != '') {
            $("#sltDoituong").each(function () {
                var value = this.value;
                // console.log(value);
                str += value + "-";
                if (parseInt(value) == 73 || parseInt(value) == 41) {
                    check7341 = true;
                    check46 = false;
                } else if (parseInt(value) == 46) {
                    check7341 = false;
                    check46 = true;
                } else {
                    check7341 = false;
                    check46 = false;
                }
            });
        } else {
            $.each($("input[name='checkboxactive']:checked"), function (index, v) {
                var value = $(v).val();
                str += value + "-";
                if (parseInt(value) == 73 || parseInt(value) == 41) {
                    check7341 = true;
                    check46 = false;
                } else if (parseInt(value) == 46) {
                    check7341 = false;
                    check46 = true;
                } else {
                    check7341 = false;
                    check46 = false;
                }
            });
        }

        str = str.substring(0, str.length - 1);
        // console.log(str);
        var year = $('#sltYear').val();
        var numberYear = 0;
        if (year !== null && year !== "" && year !== undefined) {
            var ky = year.split("-");
            numberYear = ky[1];
        }
        if (check46) {
            // console.log($('#ckbNQ57').is(":checked"));
            //console.log($('#ckbNQ57').val());
            if (($('#sltBantru').val() == "" || $('#sltBantru').val() == "-1") && $('#ckbNQ57').val() == 0) {
                utility.messagehide('group_message', "Học sinh bán trú yêu cầu chọn chế độ 116 hoặc nghị quyết 57!", 1, 5000);
                $("#sltBantru").focus();
                return;
            }
        }
        if (check7341) {
            if ($('#txtParent').val() == "") {
                utility.messagehide('group_message', "Là hộ nghèo hoặc cận nghèo yêu cầu nhập chủ hộ!", 1, 5000);
                $("#txtParent").focus();
                return;
            }
        }
        if ($('#txtNameProfile').val() == "") {
            utility.messagehide('group_message', "Yêu cầu nhập tên học sinh!", 1, 5000);
            $("#txtNameProfile").focus();
        } else {
            if ($('#sltTruong').val() == "") {
                utility.messagehide('group_message', "Yêu cầu nhập trường học!", 1, 5000);
                $("#sltTruong").focus();
            } else {
                if ($('#sltLop').val() == "") {
                    utility.messagehide('group_message', "Yêu cầu nhập lớp học!", 1, 5000);
                    $("#sltLop").focus();
                } else {
                    if ($('#txtBirthday').val() == "") {
                        utility.messagehide('group_message', "Yêu cầu nhập ngày sinh!", 1, 5000);
                        $("#txtBirthday").focus();
                    } else {
                        if ($('#txtYearProfile').val() == "") {
                            utility.messagehide('group_message', "Yêu cầu nhập năm nhập học!", 1, 5000);
                            $("#txtYearProfile").focus();
                        } else {//
                            if ($('#sltDantoc').val() == "") {
                                utility.messagehide('group_message', "Yêu cầu nhập dân tộc!", 1, 5000);
                                $("#sltDantoc").focus();
                            } else {
                                if ($('#sltTinh').val() == "") {
                                    utility.messagehide('group_message', "Yêu cầu nhập tỉnh!", 1, 5000);
                                    $("#sltTinh").focus();
                                } else {
                                    if ($('#sltQuan').val() == "") {
                                        utility.messagehide('group_message', "Yêu cầu nhập quận!", 1, 5000);
                                        $("#sltQuan").focus();
                                    } else {
                                        if ($('#sltPhuong').val() == "") {
                                            utility.messagehide('group_message', "Yêu cầu nhập phường!", 1, 5000);
                                            $("#sltPhuong").focus();
                                        } else {

                                            if (str.split('-').length <= 1 && str == "") {
                                                utility.confirm('Thông báo', 'Bạn muốn lưu học sinh không có đối tượng?', function () {
                                                    form_datas.append('PROFILEID', profile_id);
                                                    form_datas.append('PROFILENAME', $('#txtNameProfile').val());
                                                    form_datas.append('PROFILEBIRTHDAY', $('#txtBirthday').val());
                                                    form_datas.append('PROFILENATIONALID', $('#sltDantoc').val());
                                                    form_datas.append('PROFILESITE1', $('#sltTinh').val());
                                                    form_datas.append('PROFILESITE2', $('#sltQuan').val());
                                                    form_datas.append('PROFILESITE3', $('#sltPhuong').val());
                                                    form_datas.append('PROFILEHOUSEHOLD', $('#txtThonxom').val());
                                                    form_datas.append('PROFILEPARENTNAME', $('#txtParent').val());
                                                    form_datas.append('PROFILEPARENTSTT', $('#txtSTTHoNgheo').val());
                                                    form_datas.append('profile_guardian', $('#txtGuardian').val());
                                                    form_datas.append('PROFILEYEAR', $('#txtYearProfile').val());
                                                    form_datas.append('PROFILESCHOOLID', $('#sltTruong').val());
                                                    form_datas.append('PROFILECLASSID', $('#sltLop').val());
                                                    form_datas.append('PROFILESTATUS', v_status);
                                                    form_datas.append('PROFILESTATUSNQ57', v_statusNQ57);
                                                    form_datas.append('PROFILELEAVESCHOOLDATE', $('#txtDateNghi').val());
                                                    form_datas.append('ARRSUBJECTID', str);
                                                    form_datas.append('HISTORYID', $('#txtHistoryId').val());
                                                    form_datas.append('ARRDECIDED', "");
                                                    form_datas.append('PROFILEBANTRU', $('#sltBantru').val() != "-1" ? $('#sltBantru').val() : "");
                                                    form_datas.append('PROFILEKM', $('#txtKhoangcach').val());
                                                    form_datas.append('PROFILEGIAOTHONG', $('#drGiaoThong').val());
                                                    form_datas.append('CURRENTYEAR', numberYear);
                                                    if (decided) {
                                                        if ($('#txtHistoryId').val() == null || $('#txtHistoryId').val() == "") {
                                                            insertProfile(form_datas);
                                                        } else {
                                                            updateProfile(form_datas);
                                                        }
                                                    }
                                                });
                                            } else {
                                                //  utility.confirm('Thông báo','Bạn muốn lưu học sinh này?',function(){
                                                form_datas.append('PROFILEID', profile_id);
                                                form_datas.append('PROFILENAME', $('#txtNameProfile').val());
                                                form_datas.append('PROFILEBIRTHDAY', $('#txtBirthday').val());
                                                form_datas.append('PROFILENATIONALID', $('#sltDantoc').val());
                                                form_datas.append('PROFILESITE1', $('#sltTinh').val());
                                                form_datas.append('PROFILESITE2', $('#sltQuan').val());
                                                form_datas.append('PROFILESITE3', $('#sltPhuong').val());
                                                form_datas.append('PROFILEHOUSEHOLD', $('#txtThonxom').val());
                                                form_datas.append('PROFILEPARENTNAME', $('#txtParent').val());
                                                form_datas.append('profile_guardian', $('#txtGuardian').val());
                                                form_datas.append('PROFILEYEAR', $('#txtYearProfile').val());
                                                form_datas.append('PROFILESCHOOLID', $('#sltTruong').val());
                                                form_datas.append('PROFILECLASSID', $('#sltLop').val());
                                                form_datas.append('PROFILESTATUS', v_status);
                                                form_datas.append('HISTORYID', $('#txtHistoryId').val());
                                                form_datas.append('PROFILEPARENTSTT', $('#txtSTTHoNgheo').val());
                                                form_datas.append('PROFILESTATUSNQ57', v_statusNQ57);
                                                form_datas.append('PROFILELEAVESCHOOLDATE', $('#txtDateNghi').val());
                                                form_datas.append('ARRSUBJECTID', str);
                                                form_datas.append('ARRDECIDED', "");
                                                form_datas.append('PROFILEBANTRU', $('#sltBantru').val() != "-1" ? $('#sltBantru').val() : "");
                                                form_datas.append('PROFILEKM', $('#txtKhoangcach').val());
                                                form_datas.append('PROFILEGIAOTHONG', $('#drGiaoThong').val());
                                                form_datas.append('CURRENTYEAR', numberYear);
                                                if (decided) {
                                                    if ($('#txtHistoryId').val() == null || $('#txtHistoryId').val() == "") {
                                                        insertProfile(form_datas);
                                                    } else {
                                                        updateProfile(form_datas);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    });


    //Get Table for Year Popup--------------------------use------------------------------------------------------------
    $("#drYearUpto").change(function () {
        $('#upClass-select-all').prop('checked', false);
        loadTableUpto($('#drPagingUpto').val());
        if (parseInt($('#drClassNext').val()) == 2) {
            $('#drClassNext').selectpicker('val', '').trigger('change');
            utility.messagehide("message_Upto", "Yêu cầu nghỉ học ở năm học hiện tại " + $('#timenow').val(), 1, 5000);
        }

    });

    $("#cbxChooseAll").click(function () {
        $('input#cbxChooseItem').not(this).prop('checked', this.checked);
    });

    $("#btnUpto").click(function () {
        console.log("OK");
        var arrProfileID = new Array();
        var classID = $('#drClassUpto').val();
        var year = "";
        var classID_next = $('#drClassNext').val();
        $("input#cbxChooseItem").each(function () {
            if ($(this).is(':checked')) {
                var profileId = $(this).attr('data');
                var o = {
                    pro_id: profileId,
                    typeval: $('#drClassNext').val(),
                    s_id: $('#drSchoolUpto').val()
                }
                arrProfileID.push(o);
                year = $(this).attr('data-year');
            }
        });

        var selectedALL = 0;
        if ($('#upClass-select-all').prop('checked')) {
            selectedALL = 1;
        }
        if (arrProfileID.length <= 0) { // Chưa chọn học sinh
            var message = "Vui lòng chọn học sinh !";
            utility.message("Thông báo", message, null, 3000, 1);
        } else {// đã chọn học sinh
            if (parseInt(classID_next) === 1) {
                if ($('#StlClassNext').val() === null || $('#StlClassNext').val() === '') {
                    utility.message("Thông báo", "Vui lòng chọn lớp tiếp theo.", null, 3000, 1);
                    return;
                } else {
                    controlClass("", $('#StlClassNext').val(), "", arrProfileID, classID, year, classID_next, selectedALL);
                }
            } else if (parseInt(classID_next) === 2) {
                if ($('#dateOutProfile').val() === null || $('#dateOutProfile').val() === '') {
                    utility.message("Thông báo", "Vui lòng nhập ngày nghỉ học đúng định dạng.", null, 3000, 1);
                    return;
                } else {
                    controlClass("", "", $('#dateOutProfile').val(), arrProfileID, classID, year, classID_next, selectedALL);
                }
            } else if (parseInt(classID_next) === 3) {
                if ($('#drClassBack').val() === null || $('#drClassBack').val() === '') {
                    utility.message("Thông báo", "Vui lòng chọn lớp.", null, 3000, 1);
                    return;
                } else {
                    controlClass($('#drClassBack').val(), "", "", arrProfileID, classID, year, classID_next, selectedALL);
                }
            } else if (parseInt(classID_next) === 4) {
                if ($('#drClassBack').val() === null || $('#drClassBack').val() === '') {
                    utility.message("Thông báo", "Vui lòng chọn lớp.", null, 3000, 1);
                    return;
                } else {
                    controlClass("", "", "", arrProfileID, classID, year, classID_next, selectedALL, $('#drClassBack').val(),$("#dateChangeProfile").val());
                }
            }
        }
    });


    $("#btnRevert").click(function () {
        //utility.confirmAlert("Thông báo", "Hoàn tác về thao tác mới nhất.Chắn chắn?", function () {
        // var $row = $('table#tablePopup').closest("tr");
        // var $strCode = $row.find("#tdChoosePopup").text();
        var arrProfileID = new Array();
        var classID = "";
        var year = "";
        var classID_next = $('#drClassNext').val();
        $("input#cbxChooseItem").each(function () {
            if ($(this).is(':checked')) {
                var profileId = $(this).attr('data');
                var classId = $(this).attr('data-class');
                arrProfileID.push(profileId);
                classID = classId;
                year = $(this).attr('data-year');
            }
        });
        // console.log(arrProfileID);
        // console.log(classID);
        if (arrProfileID.length <= 0) {
            var message = "Vui lòng chọn học sinh!";
            utility.message("Thông báo", message, null, 3000);
        } else {
            //  if(classID_next == null || classID_next == "" || classID_next == 0){
            //     var message = "Vui lòng chọn lớp tiếp theo!";
            //     utility.message("Thông báo", message, null, 3000,1);
            //} else {
            var v_jsonClass = JSON.stringify({ ARRPROFILEID: arrProfileID, CLASSID: classID, YEAR: year, CLASSIDNEXT: classID_next });
            // console.log(v_jsonClass);
            $.ajax({
                type: "POST",
                url: '/ho-so/hoc-sinh/revertprofile',
                data: v_jsonClass,
                dataType: 'json',
                contentType: 'application/json; charset=utf-8',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                success: function (data) {
                    //console.log(data);
                    if (data['success'] != "" && data['success'] != undefined) {
                        utility.message("Thông báo", data['success'], null, 3000);
                        $('#uptoClass').DataTable().clear().draw().destroy();
                        resetControl();
                        GET_INITIAL_NGHILC();
                        $('#upClass-select-all').prop('checked', false);
                        loaddataProfile($('select#viewTableProfile').val(), $('select#sltTruongGrid').val(), $('select#sltLopGrid').val(), $('#txtSearchProfile').val());
                    }
                    if (data['error'] != "" && data['error'] != undefined) {
                        utility.message("Thông báo", data['error'], null, 3000, 1);
                    }
                }, error: function (data) {
                }
            });
            // }
        }
    });

    //--------------------------------------------------------------Phần danh sách cấp trường----------------------------------------------------
    $('#btnInsertTHCD').click(function () {
        $('#btnInsertTHCD').button('loading');
        var message = validatePopupTongHopCheDo();
        if (message !== "") {
            utility.messagehide("group_message_THCD", message, 1, 5000);
            $('#btnInsertTHCD').button('reset');
            return;
        }
        var form_datas = new FormData();
        form_datas.append('SCHOOLID', $('#drSchoolTHCD').val());
        form_datas.append('YEAR', $('#sltYear').val());
        form_datas.append('UNITNAME', $('#sltKhoiDt').val());//Khối lớp
        form_datas.append('ARRCHEDO', $('#sltChedo').val());//Chế độ lập công văn
        form_datas.append('NAME', $('#txtTenCongVan').val());//tên công văn
        form_datas.append('NUMBERCV', $('#txtSoCongVan').val());//số công văn
        form_datas.append('NOTE', $('#txtGhiChuTHCD').val());//Ghi chú
        form_datas.append('CAPNHAN', $('#sltCapNhan').val());// Cấp nhận
        form_datas.append('LOAICONGVAN', $('#sltTypeCV').val());// Loại công văn

        lapdanhsachDanhSachTongHop(form_datas);
    });

    $('#btnLapDSPhongSo').click(function () {
        if ($('#sltLoaiCVPopup').val() !== null && $('#sltLoaiCVPopup').val() !== "" && $('#sltLoaiCVPopup').val() > 0) {
            if ($('#sltChedo').val() !== null && $('#sltChedo').val() !== "") {
                var l = $('#sltChedo').val();
                var toChoise = false;
                if (l.length > 1) {
                    for (var i = 0; i < l.length; i++) {
                        if (l[i] == 9 || l[i] == '9') {
                            utility.messagehide('group_message_THCD', "Chế độ hỗ trợ người nấu ăn lập riêng đề nghị.", 1, 5000);
                            break;
                        }
                    }
                }
                if ($('#txtTenCVGui').val() == "") {
                    utility.messagehide("group_message_THCD", "Xin mời nhập tên công văn", 1, 5000);
                    $('#btnLapDSPhongSo').button('reset');
                    return;
                }
                if ($('#txtSoCongVan').val() == "") {
                    utility.messagehide("group_message_THCD", "Xin mời nhập số công văn", 1, 5000);
                    $('#btnLapDSPhongSo').button('reset');
                    return;
                }
                var o = {
                    SCHOOLID: $('#drSchoolTHCD').val(),
                    SOCONGVAN: $('#sltCongvan').val(),
                    ARRCHEDO: $('#sltChedo').val(),
                    TENCONGVAN: $('#txtTenCVGui').val(),
                    CONGVAN: $('#txtSoCongVan').val(),
                    GHICHUCV: $('#txtGhiChuCVGui').val(),
                    REPORTSTATUS: $('#sltLoaiCVPopup').val()
                }
                lapdanhsachDanhSachPhongSo(o);
            }
            else {
                utility.messagehide("group_message_THCD", "Mời chọn loại chế độ", 1, 5000);
                $('#btnLapDSPhongSo').button('reset');
                return;
            }
        }
        else {
            utility.messagehide("group_message_THCD", "Mời chọn loại công văn cần lập", 1, 5000);
            $('#btnLapDSPhongSo').button('reset');
            return;
        }
    });

    $('#btnInsertTHCD_PheDuyet').click(function () {
        $('#btnInsertTHCD_PheDuyet').button('loading');
        var message = "";
        message = validatePopupTongHopCheDo();
        if (message !== "") {
            utility.messagehide("group_message_THCD", message, 1, 5000);
            $('#btnInsertTHCD_PheDuyet').button('reset');
            return;
        }

        // console.log($('#sltChedo').val());

        // var file_data = $('input#fileAttack').prop('files')[0];   
        var form_datas = new FormData();
        // form_datas.append('FILE', file_data);
        form_datas.append('SCHOOLID', $('#drSchoolTHCD').val());
        form_datas.append('YEAR', $('#sltYear').val());
        // form_datas.append('REPORTNAME', $('#txtNameDSTHCD').val());
        // form_datas.append('CREATENAME', $('#txtNguoiLapTHCD').val());
        // form_datas.append('SIGNNAME', $('#txtNguoiKyTHCD').val());
        form_datas.append('ARRCHEDO', $('#sltChedo').val());
        form_datas.append('NOTE', $('#txtGhiChuTHCD').val());

        lapdanhsachDanhSachTongHop_PheDuyet(form_datas);
    });

    $('#btnInsertTHCD_ThamDinh').click(function () {
        $('#btnInsertTHCD_ThamDinh').button('loading');
        var message = "";
        message = validatePopupTongHopCheDo();
        if (message !== "") {
            utility.messagehide("group_message_THCD", message, 1, 5000);
            $('#btnInsertTHCD_ThamDinh').button('reset');
            return;
        }

        // console.log($('#sltChedo').val());

        // var file_data = $('input#fileAttack').prop('files')[0];   
        var form_datas = new FormData();
        // form_datas.append('FILE', file_data);
        form_datas.append('SCHOOLID', $('#drSchoolTHCD').val());
        form_datas.append('YEAR', $('#sltYear').val());
        // form_datas.append('REPORTNAME', $('#txtNameDSTHCD').val());
        // form_datas.append('CREATENAME', $('#txtNguoiLapTHCD').val());
        // form_datas.append('SIGNNAME', $('#txtNguoiKyTHCD').val());
        form_datas.append('ARRCHEDO', $('#sltChedo').val());
        form_datas.append('NOTE', $('#txtGhiChuTHCD').val());

        lapdanhsachDanhSachTongHop_ThamDinh(form_datas);
    });
    //xử lý học sinh của trường
    $('#btnApprovedTHCD').click(function () {
        var strData = "";
        var uncheck = false;
        $('input.chilCheck').each(function () {
            if (this.checked) {
                uncheck = false
                var sThisVal = $(this).val();
                strData += (strData == "" ? sThisVal : "|" + sThisVal);
            }
        });
        var note = $('#txtGhiChuTHCD').val();
        approvedChedo(strData, note);
    });
    $('#btnApprovedLapCV').click(function () {
        var type = 0;
        if ($('#CheckProfile').is(":checked") && $('#UnCheckProfile').is(":checked")) {
            utility.messagehide("group_message_approved", "Xin mời chọn 1 loại xét duyệt", 1, 5000);
            return;
        }
        if ($('#CheckProfile').is(":checked")) {
            type = 1;
        }
        if ($('#UnCheckProfile').is(":checked")) {
            type = 2;
        }
        var note = $('#txtGhiChuTHCD').val();
        xetduyethocsinh($('#id_profile').val(), type, $('#years_xetduyet').val(), note);
    });

    $('#btnApprovedCheDoPhongSo').click(function () {
        var strData = "";
        $('input#chilApproveCheck').each(function () {

            if (this.checked) {
                var sThisVal = $(this).val();
                strData += (strData == "" ? sThisVal : "-" + sThisVal);
            }
        });

        var socongvan = $('#sltCongvan').val();
        var note = $('#txtGhiChuTHCD').val();
        // console.log (strData);
        approvedChedoPhongSo(strData, socongvan, _idProfile, note);
    });

    $('#btnRevertedCheDoPhongSo').click(function () {
        var strData = "";
        $('input#chilRevertCheck').each(function () {

            if (this.checked) {
                var sThisVal = $(this).val();
                strData += (strData == "" ? sThisVal : "-" + sThisVal);
            }
        });

        var socongvan = $('#sltCongvan').val();
        var note = $('#txtGhiChuTHCDTraLai').val();
        // console.log (strData);
        revertedChedoPhongSo(strData, socongvan, _idProfile, note);
    });

    $('#btnReloadCheDoPhongSo').click(function () {
        var strData = "";
        $('input#chilReloadCheck').each(function () {

            if (this.checked) {
                var sThisVal = $(this).val();
                strData += (strData == "" ? sThisVal : "-" + sThisVal);
            }
        });

        var socongvan = $('#sltCongvan').val();
        var note = $('#txtGhiChuTHCD').val();
        // console.log (strData);
        reloadChedoPhongSo(strData, socongvan, _idProfile);
    });

    $('#btnDuyetDSChoPD').click(function () {

        $('#btnApprovedDanhSachDuyet').button('loading');
        $('#btnRevertDanhSachDuyet').button('loading');

        $('#btnDuyetDSChoPD').button('loading');
        $('#btnTraLaiDSChoPD').button('loading');

        var truong = $('#drSchoolTHCD').val();
        var socongvan = $('#sltCongvan').val();
        var note = $('#txtGhiChuTHCD').val();

        var o = {
            PROFILEID: _idProfile,
            SCHOOLID: truong,
            SOCONGVAN: socongvan,
            NOTE: note,
            STATUS: loaiDanhSach
        }
        // console.log (strData);
        approvedChedoPheDuyet(o);
    });

    $('#btnTraLaiDSChoPD').click(function () {

        $('#btnApprovedDanhSachDuyet').button('loading');
        $('#btnRevertDanhSachDuyet').button('loading');

        $('#btnDuyetDSChoPD').button('loading');
        $('#btnTraLaiDSChoPD').button('loading');

        var truong = $('#drSchoolTHCD').val();
        var socongvan = $('#sltCongvan').val();
        var note = $('#txtGhiChuTHCD').val();

        var o = {
            PROFILEID: _idProfile,
            SCHOOLID: truong,
            SOCONGVAN: socongvan,
            NOTE: note,
            STATUS: loaiDanhSach
        }
        // console.log (strData);
        revertChedoPheDuyet(o);
    });

    $('#btnTraLaiDSDaPD').click(function () {

        var truong = $('#drSchoolTHCD').val();
        var socongvan = $('#sltCongvan').val();
        var note = $('#txtGhiChuTHCD').val();

        var o = {
            PROFILEID: _idProfile,
            SCHOOLID: truong,
            SOCONGVAN: socongvan,
            NOTE: note,
            STATUS: 1
        }
        // console.log (strData);
        revertChedoPheDuyet(o, 1);
    });

    $('#btnDuyetDSTuChoiPD').click(function () {

        var truong = $('#drSchoolTHCD').val();
        var socongvan = $('#sltCongvan').val();
        var note = $('#txtGhiChuTHCD').val();

        var o = {
            PROFILEID: _idProfile,
            SCHOOLID: truong,
            SOCONGVAN: socongvan,
            NOTE: note,
            STATUS: 1
        }
        // console.log (strData);
        approvedChedoPheDuyet(o, 1);
    });

    $('#btnOkThuHoi').click(function () {

        var truong = $('#drSchoolTHCD').val();
        var socongvan = $('#sltCongvan').val();

        var o = {
            PROFILEID: idhsthuhoi,
            SCHOOLID: truong,
            SOCONGVAN: socongvan
        }
        // console.log (strData);
        thuhoihocsinh(o);
    });

    $('#btnApprovedTHCDThamDinh').click(function () {
        var strData = "";
        $('input#chilCheckTD').each(function () {

            if (this.checked) {
                var sThisVal = $(this).val();
                strData += (strData == "" ? sThisVal : "-" + sThisVal);
            }
        });

        var truong = $('#drSchoolTHCD').val();
        var socongvan = $('#sltCongvan').val();
        var note = $('#txtGhiChuTHCD').val();
        // console.log (strData);
        approvedChedoThamDinh(strData, truong, socongvan, note);
    });


    $("#btnViewDanhSachTongHopGroup").click(function () {
        // console.log("Click");
        GET_INITIAL_NGHILC();
        loaddataDanhSachGroupA($('#drPagingDanhsach').val(), $('#txtSearchProfileLapdanhsach').val());
    });

    $("#btnViewDanhSachTruongLap").click(function () {
        // console.log("Click");
        GET_INITIAL_NGHILC();
        loaddataDanhSachGroupB($('#drPagingDanhsach').val(), $('#txtSearchProfileLapdanhsach').val());
    });

    $("#btnViewDanhSachTruongLapDutoan").click(function () {
        // console.log("Click");
        GET_INITIAL_NGHILC();
        loaddataDutoan($('#drPagingDanhsach').val(), $('#txtSearchProfileLapdanhsach').val());
    });

    $("#btnLoadDataTruong").click(function () {
        var msg_warning = "";

        msg_warning = validateLapDenghi();

        // alert(msg_warning);

        if (msg_warning !== null && msg_warning !== "") {
            utility.messagehide("messageValidate", msg_warning, 1, 5000);
            return;
        }

        var schools_id = $('#drSchoolTHCD').val();
        var year = $('#sltYear').val();

        var nam = 0;

        if (year !== null && year !== "" && year !== undefined) {
            var ky = year.split("-");
            nam = ky[1];
        }

        var form_datas = new FormData();
        form_datas.append('SCHOOLID', schools_id);
        form_datas.append('YEAR', nam);

        updateMoneyNew(form_datas);
        // console.log("Click");
        GET_INITIAL_NGHILC();
        loaddataDanhSachTongHop($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
    });

    $("#btnLoadDataPhong").click(function () {
        // console.log("Click");
        GET_INITIAL_NGHILC();
        loadDanhSachChoPheDuyet($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
    });

    $("#btnLoadDataSo").click(function () {
        // console.log("Click");
        GET_INITIAL_NGHILC();
        loadlistApprovedPheDuyet($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
    });

    $("#btnTralai").click(function () {
        var strData = "";
        $('input#Phongchoose').each(function () {

            if (this.checked) {
                var sThisVal = $(this).val();
                strData += (strData == "" ? sThisVal : "-" + sThisVal);
            }
        });

        var message = "";
        message = validateDanhsachtralai(strData);
        if (message !== "") {
            utility.messagehide("messageValidate", message, 1, 5000);
            return;
        }

        $("#txtNote").val("");
        $("#myModalRevertPhong").modal("show");

        // var form_datas = new FormData();
        // form_datas.append('ARRPROFILEID', strData);
        // form_datas.append('NOTE', $('#txtNote').val());
        // // console.log(strData);
        // danhsachPhongtralai(form_datas);
    });

    $("#btnPhongRevert").click(function () {

        var strData = "";
        $('input#Phongchoose').each(function () {

            if (this.checked) {
                var sThisVal = $(this).val();
                strData += (strData == "" ? sThisVal : "-" + sThisVal);
            }
        });

        var reportName = $('#sltCongvan').val();
        var reportType = $('#sltLoaiChedo').val();

        var form_datas = new FormData();
        form_datas.append('ARRPROFILEID', strData);
        form_datas.append('NOTE', $('#txtNote').val());
        form_datas.append('REPORTNAME', reportName);
        form_datas.append('REPORTTYPE', reportType);
        // console.log(strData);
        danhsachPhongtralai(form_datas);
    });


    // $("#PhongChooseAll").click(function(){
    //     $('input#Phongchoose').not(this).prop('checked', this.checked);
    //     alert("Choose1");
    // });

    // $('#PhongChooseAll').change(function() {
    //     alert("Choose2");
    //         if ($('#PhongChooseAll').prop('checked'))
    //             $('[id*="Phongchoose"]').prop('checked', true);
    //         else
    //             $('[id*="Phongchoose"]').prop('checked', false);
    // });
});

var profile_id = 0;

//Check Permisstion
var CODE_FEATURES;
function permission(callback, moduleId) {
    // console.log(moduleId);
    $.ajax({
        type: "GET",
        url: '/ho-so/hoc-sinh/permission/' + moduleId,
        success: function (data) {
            CODE_FEATURES = data.permission;
            if (callback != null) {
                callback();
            }
        }, error: function (data) {
        }
    });
};

function check_Permission_Feature(featureCode) {
    // console.log(Object.values(CODE_FEATURES));
    // console.log(Object.values(CODE_FEATURES).indexOf("2"));        
    try {
        if (Object.values(CODE_FEATURES).indexOf(featureCode) >= 0) {
            //console.log(Object.values(CODE_FEATURES).indexOf(featureCode));
            return true;
        }

        return false;
    } catch (e) {
        console.log(e);
    }
    return true;
}

function loadComboxDantoc(callback, idchoise = null) {
    $('#sltDantoc').html("<option value=''>Đang tải dữ liệu...</option>").selectpicker('refresh');
    GetFromServer('/danh-muc/load/dan-toc', function (dataget) {
        $('#sltDantoc').html("");
        var html_show = "";
        if (dataget.length > 0) {

            html_show += "<option value=''>-- Chọn dân tộc --</option>";
            for (var i = 0; i < dataget.length; i++) {
                if (dataget[i].nationals_id === idchoise) {
                    html_show += "<option value='" + dataget[i].nationals_id + "' selected>" + dataget[i].nationals_name + "</option>";
                }
                else {
                    html_show += "<option value='" + dataget[i].nationals_id + "'>" + dataget[i].nationals_name + "</option>";
                }
            }
            $('#sltDantoc').html(html_show);
            // $("#sltDantoc").select2('24', "Mông");
        } else {
            $('#sltDantoc').html("<option value=''>-- Chưa có dân tộc --</option>");
        }
        $('#sltDantoc').selectpicker('refresh');
        if (callback != null) {
            callback();
        }
    }, function (dataget) {
        console.log("loadComboxDantoc");
        console.log(dataget);
    }, "", "", "");

};

function loadComboxTruongHoc(id, callback, idchoise = null) {
    $('#' + id).html("<option value=''>Đang tải dữ liệu...</option>").selectpicker('refresh');
    GetFromServer('/danh-muc/load/truong-hoc', function (data) {
        var dataget = data.truong;
        var datakhoi = data.khoi;
        $('#' + id).html("");
        var html_show = "";
        if (dataget.length > 0) {
            html_show += "<option value=''>-- Chọn trường học --</option>";
            for (var i = 0; i < dataget.length; i++) {
                if (idchoise != null) {
                    if ((idchoise + '').split('-').length == 1 && parseInt(idchoise) != 0) {
                        if (parseInt(idchoise) === parseInt(dataget[i].schools_id)) {
                            html_show += "<option value='" + dataget[i].schools_id + "' selected>" + dataget[i].schools_name + "</option>";
                        } else {
                            html_show += "<option value='" + dataget[i].schools_id + "'>" + dataget[i].schools_name + "</option>";
                        }
                    } else {
                        html_show += "<option value='" + dataget[i].schools_id + "'>" + dataget[i].schools_name + "</option>";
                    }
                }

                // }
            }
            //     html_show +="</optgroup>"                           
            // }
            $('#' + id).html(html_show);
            $('#' + id).selectpicker('refresh');
        } else if (dataget.length === 1) {
            html_show += "<option value=''>-- Chọn trường học --</option>";
            for (var i = 0; i < dataget.length; i++) {

                html_show += "<option value='" + dataget[i].schools_id + "'>" + dataget[i].schools_name + "</option>";
                var school_id = dataget[i].schools_id;
                loadComboxLop(school_id, 'sltLop', function () {
                    // if(school_id > 0){
                    //     $('select#sltLop').attr('disabled','disabled');
                    // }else{
                    $('select#sltLop').removeAttr('disabled');

                    // }
                });
                // console.log(school_id);
            }
            $('#' + id).html(html_show);
        } else {
            $('#' + id).html("<option value=''>-- Chưa có trường --</option>");
        }

        if (callback != null) {
            callback();
        }
    }, function (datas) {
        console.log("loadComboxTruongHoc");
        console.log(datas);
    }, "", "", "");

};

function loadComboxTruongHocSingle(id, callback, idchoise = null) {
    $('#' + id).html("<option value=''>Đang tải dữ liệu...</option>").selectpicker('refresh');
    GetFromServer('/danh-muc/load/truong-hoc', function (data) {
        var dataget = data.truong;
        $('#' + id).html("");

        var html_show = "";
        html_show += "<option value=''>-- Chọn trường học --</option>";
        if (dataget.length > 0) {
            for (var i = 0; i < dataget.length; i++) {
                if (idchoise != null) {
                    if ((idchoise + '').split('-').length == 1 && parseInt(idchoise) != 0) {
                        if (parseInt(idchoise) === parseInt(dataget[i].schools_id)) {
                            html_show += "<option value='" + dataget[i].schools_id + "' selected>" + dataget[i].schools_name + "</option>";
                        } else {
                            html_show += "<option value='" + dataget[i].schools_id + "'>" + dataget[i].schools_name + "</option>";
                        }
                    } else {
                        html_show += "<option value='" + dataget[i].schools_id + "'>" + dataget[i].schools_name + "</option>";
                    }
                }
            }
            $('#' + id).html(html_show);
        }
        else {
            $('#' + id).html("<option value=''>-- Chưa có trường --</option>");
        }
        $('#' + id).selectpicker('refresh');
        if (callback != null) {
            callback();
        }
    }, function (data) {
        console.log("loadComboxTruongHocSingle");
        console.log(data);
    }, "", "", "");
};



function loadComboxTruongHocUpto(idchoise = null) {
    //loading();
    $.ajax({
        type: "GET",
        url: '/danh-muc/load/truong-hoc',
        success: function (data) {

            var dataget = data.truong;
            // var datakhoi = data.khoi;

            console.log(dataget);
            $('#drSchoolUpto').html("");

            var html_show = "";
            // if(datakhoi.length > 0){
            html_show += "<option value=''>-- Chọn trường học --</option>";
            // for (var j = 0; j < datakhoi.length; j++) {
            //     html_show +="<optgroup label='"+datakhoi[j].unit_name+"'>";
            if (dataget.length > 0) {
                // for (var i = 0; i < dataget.length; i++) {
                //     if(datakhoi[j].unit_id === dataget[i].schools_unit_id){

                //         html_show += "<option value='"+dataget[i].schools_id+"'>"+dataget[i].schools_name+"</option>";
                //     }
                // }
                for (var i = 0; i < dataget.length; i++) {
                    // if(datakhoi[j].unit_id === dataget[i].schools_unit_id){
                    if (idchoise != null) {
                        if (idchoise.split('-').length == 1 && parseInt(idchoise) != 0) {
                            // if(idchoise===parseInt(dataget[i].schools_id)){
                            html_show += "<option value='" + dataget[i].schools_id + "' selected>" + dataget[i].schools_name + "</option>";
                            // }else{
                            //     html_show += "<option value='"+dataget[i].schools_id+"'>"+dataget[i].schools_name+"</option>";
                            // } 
                        } else {
                            html_show += "<option value='" + dataget[i].schools_id + "'>" + dataget[i].schools_name + "</option>";
                        }
                    }

                    // }
                }
                $('#drSchoolUpto').html(html_show);
            }
            else {
                $('#drSchoolUpto').html("<option value=''>-- Chưa có trường --</option>");
            }
            //     html_show +="</optgroup>"
            // }

            // }else{
            //     
            // }
            if (callback != null) {
                callback();
            }
        }, error: function (dataget) {
        }
    });
};

function loadComboxLop(id, idselect, callback, idchoise = null, level = 0, type = true) {
    if (id != null && id != "") {
        $('#' + idselect).html("<option value=''>Đang tải dữ liệu...</option>").selectpicker('refresh');
        GetFromServer('/danh-muc/load/lop/' + id, function (dataget) {
            $('#' + idselect).html("");
            var html_show = "";
            if (dataget.length > 0) {
                if (parseInt(dataget[0].type_his_type_id) == 4) {
                    $('#sltBantru').attr('disabled', 'disabled');
                    $('#txtKhoangcach').attr('disabled', 'disabled');
                    $('#drGiaoThong').attr('disabled', 'disabled');
                    // $('#ckbNQ57').attr('disabled','disabled');
                    $('#checkboxactive_46').attr('disabled', 'disabled');
                    $('#checkboxactive_46').prop('checked', false);
                    $('#ckbNQ57').prop('checked', false);
                    $('#valid_46').html('(Cảnh báo: Là trường nội trú không được hưởng)');
                    $('#valid_bantru').html('Trường nội trú không hưởng chế độ 116 và nghị quyết 57');
                    $('.create_alert').html('Cảnh báo');
                } else {
                    $('#sltBantru').removeAttr('disabled');
                    $('#txtKhoangcach').removeAttr('disabled');
                    $('#drGiaoThong').removeAttr('disabled');
                    $('#ckbNQ57').removeAttr('disabled');
                    $('#checkboxactive_46').removeAttr('disabled');
                    $('#valid_46').html('');
                    $('#sltBantru').selectpicker('val', '-1');
                    //$('#txtKhoangcach').val('');
                    $('#checkboxactive_46').prop('checked', false);
                    $('#ckbNQ57').prop('checked', false);
                    //$('#drGiaoThong').val('');
                    $('#valid_bantru').html('');
                    $('.create_alert').html('');
                }
                html_show += "<option value=''>-- Chọn lớp --</option>";
                for (var i = 0; i < dataget.length; i++) {
                    if (level == 0) {
                        if (idchoise != null) {
                            if (parseInt(dataget[i].class_id) === parseInt(idchoise)) {
                                html_show += "<option value='" + dataget[i].class_id + "' selected>" + dataget[i].class_name + "</option>";
                            } else {
                                html_show += "<option value='" + dataget[i].class_id + "'>" + dataget[i].class_name + "</option>";
                            }
                        } else {
                            html_show += "<option value='" + dataget[i].class_id + "'>" + dataget[i].class_name + "</option>";
                        }
                    } else {
                        if (idchoise != null) {
                            if (parseInt(dataget[i].class_id) === parseInt(idchoise)) {
                                html_show += "<option value='" + dataget[i].class_id + "' selected>" + dataget[i].class_name + "</option>";
                            } else {
                                html_show += "<option value='" + dataget[i].class_id + "'>" + dataget[i].class_name + "</option>";
                            }
                        } else {
                            if (type) {
                                if (parseInt(level + 1) >= parseInt(dataget[i].level_level)) {
                                    html_show += "<option value='" + dataget[i].class_id + "'>" + dataget[i].class_name + "</option>";
                                }
                            } else {
                                if (parseInt(level) === parseInt(dataget[i].level_level)) {
                                    html_show += "<option value='" + dataget[i].class_id + "'>" + dataget[i].class_name + "</option>";
                                }
                            }
                        }
                    }

                }
                $('#' + idselect).html(html_show);
            } else {
                $('#' + idselect).html("<option value=''>-- Chưa có lớp --</option>");
            }
            $('#' + idselect).selectpicker('refresh');
            if (callback != null) {
                callback(dataget);
            }

        }
            , null, null, null, null);
    } else {
        $('#' + idselect).html('<option value="">--Chọn lớp--</option>');
        $('#' + idselect).attr('disabled', 'disabled');
    }
};
function loadComboxDoiTuong(callback = null) {
    $('#sltDoituong').html("<option value=''>Đang tải dữ liệu...</option>").selectpicker('refresh');
    GetFromServer('/danh-muc/load/doi-tuong', function (dataget) {
        $('#sltDoituong').html("");
        var html_show = "";
        if (dataget.length > 0) {
            // $('.multiselect-selected-text').html('-- Chọn dân tộc --');
            // html_show += "<option value=''>-- Chọn đối tượng --</option>";
            for (var i = dataget.length - 1; i >= 0; i--) {
                html_show += "<option value='" + dataget[i].subject_id + "'>" + dataget[i].subject_name + "</option>";
            }
            $('#sltDoituong').html(html_show);
        } else {
            $('#sltDoituong').html("<option value=''>-- Chưa có đối tượng --</option>");
        }
        $('#sltDoituong').selectpicker('refresh');
        if (callback != null) {
            callback();
        }
    }, function (dataget) {
        console.log("loadComboxDoiTuong");
        console.log(dataget);
    });
};
function loadComboxTinhThanh(id, idselect, callback, idchoise = null) {
    $('#' + idselect).html("<option value=''>Đang tải dữ liệu...</option>").selectpicker('refresh');
    GetFromServer('/danh-muc/load/city/' + id, function (dataget) {
        $('#' + idselect).html("");
        var html_show = "";
        if (dataget.length > 0) {
            //if(id===0){
            html_show += "<option value=''>-- Chọn danh mục --</option>";
            //}
            for (var i = 0; i < dataget.length; i++) {
                if (parseInt(idchoise) === parseInt(dataget[i].site_id)) {
                    html_show += "<option value='" + dataget[i].site_id + "' selected>" + dataget[i].site_name + "</option>";
                } else {
                    html_show += "<option value='" + dataget[i].site_id + "'>" + dataget[i].site_name + "</option>";
                }
            }
            $('#' + idselect).html(html_show);
            //   size = true;
        }
        else {
            $('#' + idselect).html("<option value=''>-- Chưa có danh mục --</option>");
            //size = false;
        }
        $('#' + idselect).selectpicker('refresh');
        if (callback != null) {
            callback();
        }
    }, function (dataget) {
        console.log("loadComboxTinhThanh");
        console.log(dataget);
    }, "", "", "'");
};

function loadTableUpto(row = 10) {
    var school_id = $('#drSchoolUpto').val();
    var class_id = $('#drClassUpto').val();
    var year = $('#drYearUpto').val();
    var nghihoc = $('#ckbHSNghiHoc').is(':checked');
    var d = {
        PROFILESCHOOL: school_id,
        PROFILECLASS: class_id,
        PROFILEYEAR: year,
        OUT: nghihoc
    };
    var v_jsonData = JSON.stringify(d);
    var show_html = "";
    if (year != "" && class_id != "" && school_id != "") {
        var t = $('#uptoClass').DataTable({
            "language": {
                "lengthMenu": "Hiển thị _MENU_ bản ghi",
                "info": String.format("Hiển thị {0} đến {1} trên tổng {2} bản ghi", "_START_", "_END_", "_TOTAL_"),// "Showing page _PAGE_ of _PAGES_",
                "infoEmpty": "",
                "sSearch": "Tìm kiếm: ",
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "Trang sau",
                    "previous": "Trang trước"
                },
                "emptyTable": "Không tìm thấy dữ liệu"
            },
            "bDestroy": true,
            "ajax": {
                'type': 'POST',
                'url': '/ho-so/hoc-sinh/getProfilePopupUpto',
                'data': d,
                'headers': {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                }
            },
            "initComplete": function (settings, json) {
                var info = this.api().page.info();
                if (parseInt(info.recordsTotal) > 0) {
                    $('#drClassNext').removeAttr('disabled');
                    $('#drClassNext').selectpicker('refresh');
                    $('#btnUpto').removeAttr('disabled');
                    $('#btnRevert').removeAttr('disabled');
                } else {
                    $('#drClassNext').attr('disabled', 'disabled');
                    $('#drClassNext').selectpicker('refresh');
                    $('#btnUpto').attr('disabled', 'disabled');
                    $('#btnRevert').attr('disabled', 'disabled');
                }
            },
            'columnDefs': [
                {
                    "searchable": false,
                    "orderable": false,
                    "targets": [0],
                    'className': 'text-center',
                    "width": "3%",
                    "render": function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    'targets': [1],
                    "width": "3%",
                    'className': 'text-center',
                    'render': function (mData, type, full, meta) {
                        return "<input type='checkbox' data='" + full.profile_id + "' data-class='" + full.class_id + "' data-year='" + full.history_year + "' id='cbxChooseItem'>";
                    }
                },
                {
                    'targets': [2],
                    "width": "15%",
                    'data': "profile_name"
                },
                {
                    'targets': [3],
                    "width": "7%",
                    'className': 'text-center',
                    'data': "profile_birthday",
                    "render": function (data) {
                        var date = new Date(data);
                        var month = (date.getMonth() + 1) + '';
                        return ((date.getDate() + '').length > 1 ? date.getDate() : "0" + date.getDate()) + "-" + (month.length > 1 ? month : "0" + month) + "-" + date.getFullYear();
                    }
                },
                {
                    'targets': [4],
                    "width": "7%",
                    'className': 'text-center',
                    'data': "nationals_name"
                },
                {
                    'targets': [5],
                    "width": "15%",
                    'data': "profile_household"
                },
                {
                    'targets': [6],
                    "width": "20%",
                    'data': "profile_parentname"
                }
                // ,
                // {
                //      'targets': [7],
                //      "width" : "10%",
                //      'className': 'text-center',
                //      'data': "history_upto_level",
                //      "render": function (data) {
                //         if(parseInt(data) == 0){
                //             return "Dự kiến lên lớp";
                //         }else if(parseInt(data) == 1){
                //             return "Đã lên lớp";
                //         }else if(parseInt(data) == 2){
                //             return "Đã nghỉ học";
                //         }else if(parseInt(data) == 3){
                //             return "Đã học lại";
                //         }else if(parseInt(data) == 4){
                //             return "Đã chuyển lớp";
                //         }

                //      }  
                // }
            ],
        });

        $('#upClass-select-all').on('click', function () {
            var rows = t.rows({ 'search': 'applied' }).nodes();
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });

        $('#uptoClass tbody').on('change', 'input[type="checkbox"]', function () {
            if (!this.checked) {
                var el = $('#upClass-select-all').get(0);
                if (el && el.checked && ('indeterminate' in el)) {
                    el.indeterminate = true;
                }
            }
        });
    } else {
        $('#drClassNext').attr('disabled', 'disabled');
        $('#drClassBack').hide();
        $('#labelClassBack').hide();
        $('#drClassBack').attr('disabled', 'disabled');

        $('#dateOutProfile').hide();
        $('#dateOutProfile').val('');
        $('#labelOutProfile').hide();
        $('#dateOutProfile').attr('disabled', 'disabled');
        $('#uptoClass').DataTable().clear().draw().destroy();
    }

};

function loaddataProfile(row, truong, lop, keysearch, type = null, order = null) {
    if (lop === null || lop == "") lop = 0;

    var html_show = "";
    var o = {
        start: (GET_START_RECORD_NGHILC()),
        limit: row,
        id_truong: truong,
        id_lop: lop,
        year: $('#sltNamHoc').val(),
        key: keysearch,
        TYPE: type,
        ORDER: order
    };
    var or = 0;
    if (order == 0 || order == null) {
        or = 1;
    } else {
        or = 0;
    }
    var html_header = '';
    html_header += '<tr class="success">';
    html_header += '<th class="text-center class-pointer" style="vertical-align:middle;width: 5%">STT</th>';
    html_header += '<th class="text-center class-pointer" style="vertical-align:middle;width: 12%"><span  onclick="GET_INITIAL_NGHILC();loaddataProfile(' + row + ',' + truong + ',' + lop + ',\'' + keysearch + '\',1,' + or + ');">Họ và tên</span></th>';
    html_header += '<th class="text-center class-pointer" style="vertical-align:middle;width: 7%"><span  onclick="GET_INITIAL_NGHILC();loaddataProfile(' + row + ',' + truong + ',' + lop + ',\'' + keysearch + '\',2,' + or + ');">Năm sinh</span></th>';
    html_header += '<th class="text-center class-pointer" style="vertical-align:middle;width: 6%"><span  onclick="GET_INITIAL_NGHILC();loaddataProfile(' + row + ',' + truong + ',' + lop + ',\'' + keysearch + '\',3,' + or + ');">Dân tộc</span></th>';
    html_header += '<th class="text-center class-pointer" style="vertical-align:middle;width: 20%"><span  onclick="GET_INITIAL_NGHILC();loaddataProfile(' + row + ',' + truong + ',' + lop + ',\'' + keysearch + '\',4,' + or + ');">Hộ khẩu thường trú</span></th>';
    html_header += '<th class="text-center class-pointer" style="vertical-align:middle;width: 15%"><span  onclick="GET_INITIAL_NGHILC();loaddataProfile(' + row + ',' + truong + ',' + lop + ',\'' + keysearch + '\',5,' + or + ');">Cha mẹ - người giám hộ</span></th>';
    html_header += '<th class="text-center class-pointer" style="vertical-align:middle;width: 8%"><span  onclick="GET_INITIAL_NGHILC();loaddataProfile(' + row + ',' + truong + ',' + lop + ',\'' + keysearch + '\',6,' + or + ');">Lớp học </span></th>';
    html_header += '<th class="text-center class-pointer" style="vertical-align:middle;width: 9%"><span  onclick="GET_INITIAL_NGHILC();loaddataProfile(' + row + ',' + truong + ',' + lop + ',\'' + keysearch + '\',7,' + or + ');">Năm học </span></th>';
    html_header += '<th class="text-center class-pointer" style="vertical-align:middle;width: 6%"><span  onclick="GET_INITIAL_NGHILC();loaddataProfile(' + row + ',' + truong + ',' + lop + ',\'' + keysearch + '\',8,' + or + ');">Nhập học</span></th>';
    html_header += '<th class="text-center class-pointer" style="vertical-align:middle;width: 6%"><span  onclick="GET_INITIAL_NGHILC();loaddataProfile(' + row + ',' + truong + ',' + lop + ',\'' + keysearch + '\',9,' + or + ');">Nghỉ học</span></th>';
    html_header += '<th class="text-center" colspan="2" style="vertical-align:middle;width: 8%">Chức năng</th>';
    html_header += '</tr>';

    $('#dataHeadProfile').html(html_header);
    $('#dataProfile').html("<tr><td colspan='50' class='text-center' style='vertical-align:middle'>Đang tải dữ liệu</td></tr>");
    PostToServer('/ho-so/hoc-sinh/load', o, function (dataget) {
        SETUP_PAGING_NGHILC(dataget, function () {
            loaddataProfile(row, truong, lop, keysearch, type, order);
        });
        var leaveDate = "";
        $('#dataProfile').html("");
        data = dataget.data;
        if (data.length > 0) {
            for (var i = 0; i < data.length; i++) {
                var _date = formatDates(data[i].history_year.split('-')[0] + '-09-01');
                if (data[i].profile_status == 1) {
                    leaveDate = data[i].profile_leaveschool_date;
                }
                else { leaveDate = ""; }
                html_show += "<tr><td class='text-center'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                // html_show += "<td><a style='cursor:pointer' onclick='viewHistory("+data[i].profile_id+")'>"+ConvertString(data[i].profile_code)+"</a></td>";
                html_show += "<td style='vertical-align:middle'><a style='cursor:pointer' title='Xem lịch sử học sinh' onclick='viewHistory(" + data[i].profile_id + ",\"" + ConvertString(data[i].profile_name) + "\")'>" + ConvertString(data[i].profile_name) + "</a></td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + formatDates(data[i].profile_birthday) + "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(data[i].nationals_name) + "</td>";
                html_show += "<td style='vertical-align:middle'>" + ConvertString(data[i].profile_household) + " - " + ConvertString(data[i].phuong) + " - " + ConvertString(data[i].huyen) + "</td>";
                html_show += "<td style='vertical-align:middle'>" + ConvertString(data[i].profile_guardian) + "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(data[i].class_name) + "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(data[i].history_year) + "</td>";
                if (_date >= formatDates(data[i].P_His_startdate)) {
                    html_show += "<td class='text-center' style='vertical-align:middle'>" + formatMonth(data[i].history_year.split('-')[0] + '-09-01') + "</td>";
                } else {
                    html_show += "<td class='text-center' style='vertical-align:middle'>" + formatMonth(data[i].P_His_startdate) + "</td>";
                }
                if (parseInt(data[i].history_upto_level) === 2) {
                    html_show += "<td class='text-center' style='vertical-align:middle'>" + formatDates(data[i].p_His_enddate) + "</td>";
                } else {
                    html_show += "<td class='text-center' style='vertical-align:middle'>-</td>";
                }
                if (check_Permission_Feature("2")) {
                    html_show += "<td class='text-center' style='vertical-align:middle'><button data='" + data[i].profile_id + "' onclick='getHoSoHocSinh(" + data[i].profile_id + ");' class='btn btn-info btn-xs' id='editor_editss' title='Cập nhật hồ sơ'><i class='glyphicon glyphicon-edit'></i> </button> </td>";
                }
                if (check_Permission_Feature("3")) {
                    html_show += "<td class='text-center' style='vertical-align:middle'><button  onclick='delHoSoHocSinh(" + data[i].profile_id + ");'  class='btn btn-danger btn-xs editor_remove' title='Xóa hồ sơ'><i class='glyphicon glyphicon-trash'></i></button></td>";
                }
                html_show += "</tr>";
            }

        } else {
            html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
        }
        $('#dataProfile').html(html_show);
    }, function (dataget) {
        console.log("loadHocSinh");
        console.log(dataget);
    }, "", "", "");
};

function insertKinhPhiDoiTuong(temp) {
    //console.log(temp);
    $.ajax({
        type: "POST",
        url: 'insert',
        data: JSON.stringify(temp),
        dataType: 'json',
        contentType: 'application/json; charset=utf-8',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
        success: function (dataget) {
            if (dataget.success != null || dataget.success != undefined) {
                $("#myModal").modal("hide");
                utility.message("Thông báo", dataget.success, null, 3000)
                resetControl(1);
                GET_INITIAL_NGHILC();
                loadKinhPhiDoiTuong($('select#viewTableDT').val());
            } else if (dataget.error != null || dataget.error != undefined) {
                //$("#myModal").modal("hide");
                utility.message("Thông báo", dataget.error, null, 5000)
                //resetControl(1);
                //loadKinhPhiDoiTuong($('select#viewTableDT').val()); 
            }
            // utility.message("Thông báo","Lưu bản ghi thành công",null,5000)


        }, error: function (dataget) {
        }
    });
};
function updateKinhPhiDoiTuong(temp) {
    //PostToServer('update',temp,);
    $.ajax({
        type: "POST",
        url: 'update',
        data: JSON.stringify(temp),
        dataType: 'json',
        contentType: 'application/json; charset=utf-8',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
        success: function (dataget) {
            if (dataget.success != null || dataget.success != undefined) {
                $("#myModal").modal("hide");
                utility.message("Thông báo", dataget.success, null, 3000)
                resetControl(1);
                GET_INITIAL_NGHILC();
                loadKinhPhiDoiTuong($('select#viewTableDT').val());
            } else if (dataget.error != null || dataget.error != undefined) {
                //$("#myModal").modal("hide");
                utility.message("Thông báo", dataget.error, null, 5000)
                //resetControl(1);
                //loadKinhPhiDoiTuong($('select#viewTableDT').val()); 
            }


        }, error: function (dataget) {
        }
    });
};

function insertProfile(objData, exist = 0) {
    objData.append('value_exist', exist);
    PostToServerFormData('/ho-so/hoc-sinh/insert', objData, function (data) {
        if (data['success'] != "" && data['success'] != undefined) {
            var valueTruong = $('#sltTruong').val();
            var valueNamhoc = $('#txtYearProfile').val();
            var valueHocky = $('#sltHocky').val();
            valueNamhoc = valueNamhoc.substr(3);

            if (valueHocky === null || valueHocky === "") {
                valueHocky = "CA";
            }

            valueHocky = valueHocky + '-' + valueNamhoc;

            $('#drSchoolTHCD').val(valueTruong);
            $('#sltYear').val(valueHocky);

            resetControl();

            if (parseInt($('#school-per').val()) != 0 && ($('#school-per').val() + '').split('-').length == 1) {
                loadComboxLop($('#school-per').val(), 'sltLop', function () {
                    $('select#sltLop').removeAttr('disabled');
                });
            }
            $(':checkbox').each(function () {
                this.checked = false;
            });
            loaddataProfile($('select#viewTableProfile').val(), $('select#sltTruongGrid').val(), $('select#sltLopGrid').val(), $('#txtSearchProfile').val());
            utility.message("Thông báo", data['success'], null);
        }
        if (data['error'] != "" && data['error'] != undefined) {
            utility.message("Thông báo", data['error'], null);
        }
        if (data['warning'] != "" && data['warning'] != undefined) {
            utility.confirm("Học sinh đã tồn tại?", "Bạn có chắc chắn muốn thêm không?", function () {
                insertProfile(objData, 1);
            });
            // utility.message("Thông báo",data['error'],null);
        }

    }, null, "saveProfile", "", "");
};
function updateProfile(objData) {
    PostToServerFormData('/ho-so/hoc-sinh/update', objData, function (data) {
        if (data["success"] != "" && data["success"] != undefined) {
            utility.message("Thông báo", data["success"], null, 5000, 0);
            $("#myModalProfile").modal("hide");
            loaddataProfile($('select#viewTableProfile').val(), $('select#sltTruongGrid').val(), $('select#sltLopGrid').val(), $('#txtSearchProfile').val());
            //loaddataDanhSachTongHop($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
        }
        if (data['error'] != "" && data['error'] != undefined) {
            utility.message("Thông báo", data['error'], null, 3000);
        }
    }, null, "saveProfile", "", "");

}

//Export Excel--------------------------------------------------------------------------------------------------
function exportExcelProfile() {
    var schools_id = $('#sltTruongGrid').val();
    var class_id = $('#sltLopGrid').val();
    var keysearch = $('#txtSearchProfile').val();
    var year = $('#sltNamHoc').val();
    var objJson = JSON.stringify({ SCHOOLID: schools_id, CLASSID: class_id, KEY: keysearch, year: year });
    window.open('/ho-so/hoc-sinh/exportExcel/' + objJson, '_blank');
}

// var $dateDDMMYYYY = $('#txtBirthday, #txtDateNghi, #dateOutProfile').datepicker({
//       format: 'dd-mm-yyyy',
//       autoclose: true
//     });

// var $dateMMYYYY = $('#txtYearProfile').datepicker({
//       format: 'mm-yyyy',
//       autoclose: true
//     });

function resetControl() {
    profile_id = "";
    //$('#txtNameProfile').focus();
    setTimeout(function () {
        $("#txtNameProfile").focus();
    }, 100);

    // $("#txtNameProfile").focus();
    // / $('#saveProfile').html("Thêm mới");
    $('.modal-title').html('Thêm mới hồ sơ học sinh');
    $("#txtIdProfile").val('');
    // $("#txtCodeProfile").val('');
    // $('#txtCodeProfile').removeAttr('disabled');
    $("#txtNameProfile").val('');
    $("#txtBirthday").val('');
    //$("#sltDantoc").val('').select2();//.trigger('change');
    $("#sltDantoc option").removeAttr('selected');
    $("#sltDantoc").selectpicker('refresh');
    $("#txtParent").val('');
    // $("#sltTruong").val('').select2();
    $("#sltTruong").removeAttr('disabled');
    //$("#sltTruong option").removeAttr('selected');
    //$("#sltLop").html('<option value="">-- Chọn trường --</option>');
    //$("#sltLop").val('').select2();//.trigger("change");//.select2('val', '');
    //$("#sltLop").attr('disabled','disabled');
    //$("#sltLop").html('<option value="">-- Chọn lớp --</option>');
    $("#txtYearProfile").val('');
    $("#txtYearProfile").removeAttr('disabled');
    $("#txtThonxom").val('');
    $("#sltQuan").val('').trigger("change");
    $("#sltQuan").selectpicker('refresh');
    $("#txtThonxom").selectpicker('refresh');
    // $("#sltTinh").val('').select2();//.trigger("change");//.select2('val', '');
    //$("#sltTinh option").removeAttr('selected');
    //$("#sltQuan").val('').select2();//.trigger("change");//.select2('val', '');
    //$("#sltQuan").attr('disabled','disabled');
    //$("#sltQuan").html('<option value="">-- Chọn huyện/ quận --</option>');

    $("#sltPhuong").attr('disabled', 'disabled');
    $("#sltPhuong").html('<option value="">-- Chọn xã/ phường --</option>');
    $("#sltPhuong").selectpicker('refresh');//.trigger("change");//.select2('val', '');
    //$("#sltDoituong").val("").multiselect("clearSelection");

    $('input#ckbNghihoc').removeAttr('checked');
    $('input#ckbNQ57').removeAttr('checked');
    $('div#divNgayNghi').attr('hidden', 'hidden');
    $('div#divNgayNghi').attr('disabled', 'disabled');
    // $('#txtKhoangcach').val('');
    // $('#drGiaoThong').val('');
    // $('#txtGuardian').val('');
    $('#sltHocky').val('0').trigger('change');
    $('#sltBantru').selectpicker('val', '-1');
    $("#ckbNQ57").prop('checked', false);
    //-------------------------Clear date-----------------------------------------------------
    // $dateDDMMYYYY.datepicker('setDate', new Date());
    // $dateMMYYYY.datepicker('setDate', new Date());

    //-------------------------Quyết định-----------------------------------------------------
    $("#tbDecided tr").remove();
    counter = 0;

    //Lên lớp---------------------------------------------------------------------------------    
    // $("#drSchoolUpto").val('').selectpicker('refresh');
    // $("#drClassUpto").val('').selectpicker('refresh');
    $("#drClassUpto").attr('disabled', 'disabled');
    // $("#drYearUpto").val('');
    $("#drYearUpto").attr('disabled', 'disabled');
    $('#drClassNext').val('');
    $('#drClassNext').attr('disabled', 'disabled');
    // $('#StlClassNext').html('');
    // $('#StlClassNext').addClass('hidden');
    //$('#StlClassNext').removeClass('hidden');
    $('#labelClassNext').hide();
    $('#drClassBack').hide();

    $('#btnUpto').attr('disabled', 'disabled');
    $('#btnRevert').attr('disabled', 'disabled');
    $('#contentPopupUpto').html('');
    $('#dateOutProfile').hide();
    $('#dateOutProfile').html('');
    $('#labelOutProfile').hide();
    $('#dateOutProfile').addClass('disabled', 'disabled');
    $('#drClassBack').hide();
    $('#labelClassBack').hide();
    $('#drClassBack').addClass('disabled', 'disabled');
    $('#drClassBack').html('<option value="">--Chọn lớp--</option>');

    //Form mới------------------------------------------------------------------------------------
    $('#tbMoney').attr('hidden', 'hidden');

    $('#checkboxactive_41').removeAttr('disabled');
    $('#valid_41').html('');
    $('#checkboxactive_73').removeAttr('disabled');
    $('#valid_73').html('');
};

var messageValidate = "";

function validateInput() {
    // var v_profileCode = $("#txtCodeProfile").val();
    var v_profileName = $("#txtNameProfile").val();
    var v_profileBirthday = $("#txtBirthday").val();
    var v_profileNation = $("#sltDantoc").val();
    var v_profileParent = $("#txtParent").val();
    var v_profileSchool = $("#sltTruong").val();
    var v_profileClass = $("#sltLop").val();
    var v_profileYear = $("#txtYearProfile").val();
    var v_profileSite1 = $("#sltTinh").val();
    var v_profileSite2 = $("#sltQuan").val();

    var v_status = $("#ckbNghihoc").is(":checked");
    var v_leaveDate = $("#txtDateNghi").val();

    v_profileName = v_profileName.replace(/[\n\t\r]/g, "");
    v_profileParent = v_profileParent.replace(/[\n\t\r]/g, "");


    //Validate Name----------------------------------------------------------------------------------------
    if (v_profileName.trim() == "") {
        messageValidate = "Vui lòng nhập tên học sinh!";
        $('#txtNameProfile').focus();
        return messageValidate;
    } else if (v_profileName.length > 200) {
        messageValidate = "Tên học sinh không được vượt quá 200 ký tự!";
        $('#txtNameProfile').focus();
        $('#txtNameProfile').val("");
        return messageValidate;
    }
    else {
        var specialChars = "#/|\\";

        for (var i = 0; i < v_profileName.length; i++) {
            if (specialChars.indexOf(v_profileName.charAt(i)) != -1) {
                messageValidate = "Tên học sinh không được chứa ký tự #, /, |, \\";
                $('#txtNameProfile').focus();
                return messageValidate;
            }
        }

        $('#txtNameProfile').focusout();
    }

    //Validate Birthday----------------------------------------------------------------------------------------
    if (v_profileBirthday == "") {
        messageValidate = "Vui lòng nhập ngày sinh!";
        $('#txtBirthday').focus();
        return messageValidate;
    }
    else {
        $('#txtBirthday').focusout();
    }

    //Validate National----------------------------------------------------------------------------------------
    if (v_profileNation == "") {
        messageValidate = "Vui lòng chọn dân tộc!";
        return messageValidate;
    }

    //Validate ParentName----------------------------------------------------------------------------------------
    if (v_profileParent.trim() == "") {
        messageValidate = "Vui lòng nhập họ tên cha/ mẹ hoặc người giám hộ!";
        $('#txtParent').focus();
        return messageValidate;
    } else if (v_profileParent.length > 200) {
        messageValidate = "Họ tên cha/ mẹ không được vượt quá 200 ký tự!";
        $('#txtParent').focus();
        $('#txtParent').val("");
        return messageValidate;
    }
    else {
        $('#txtParent').focusout();
        var specialChars = "#/|\\";

        for (var i = 0; i < v_profileParent.length; i++) {
            if (specialChars.indexOf(v_profileParent.charAt(i)) != -1) {
                messageValidate = "Họ tên cha/ mẹ không được chứa ký tự #, /, |, \\!";
                $('#txtParent').focus();
                $('#txtParent').val("");
                return messageValidate;
            }
        }
    }

    //Validate School----------------------------------------------------------------------------------------
    if (v_profileSchool == "") {
        messageValidate = "Vui lòng chọn trường học!";
        return messageValidate;
    }

    //Validate Class----------------------------------------------------------------------------------------
    if (v_profileClass == "") {
        messageValidate = "Vui lòng chọn lớp học!";
        return messageValidate;
    }

    //Validate Year----------------------------------------------------------------------------------------
    if (v_profileYear == "") {
        messageValidate = "Vui lòng nhập năm học!";
        $('#txtYearProfile').focus();
        return messageValidate;
    }

    //Validate Tỉnh-Thành phố----------------------------------------------------------------------------------------
    if (v_profileSite1 == "") {
        messageValidate = "Vui lòng chọn Tỉnh/ Thành phố!";
        return messageValidate;
    }
    else {
        //Validate Quận-Huyện----------------------------------------------------------------------------------------
        if (v_profileSite2 == "") {
            messageValidate = "Vui lòng chọn Huyện/ Quận!";
            return messageValidate;
        }
    }

    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();

    if (dd < 10) {
        dd = '0' + dd;
    }

    if (mm < 10) {
        mm = '0' + mm;
    }

    today = dd + '-' + mm + '-' + yyyy;

    // var v_newBirthday = v_profileBirthday.substring(3, v_profileBirthday.length);
    // var v_newYear = '28' + '-' + v_profileYear;

    //console.log(v_newBirthday);console.log(v_profileYear);

    var v_birthdayDate = v_profileBirthday.substring(0, 2);
    var v_birthdayMonth = v_profileBirthday.substring(3, 5);
    var v_birthdayYear = v_profileBirthday.substring(6, v_profileBirthday.length);
    //console.log(v_birthdayDate);console.log(v_birthdayMonth);console.log(v_birthdayYear);

    var v_yearMonth = v_profileYear.substring(0, 2);
    var v_yearYear = v_profileYear.substring(3, v_profileYear.length);
    //console.log(v_yearMonth);console.log(v_yearYear);

    // if (v_birthdayYear > yyyy || (v_birthdayYear == yyyy && v_birthdayMonth > mm) || (v_birthdayYear == yyyy && v_birthdayMonth == mm && v_birthdayDate > dd)) {
    //     messageValidate = "Ngày sinh không được lớn hơn ngày hiện tại!";
    //     $('#txtBirthday').focus();
    //     return messageValidate;
    // }

    // if (v_birthdayYear > v_yearYear || (v_birthdayYear == v_yearYear && v_birthdayMonth > v_yearMonth)) {
    //     messageValidate = "Ngày nhập học không được nhỏ hơn ngày sinh!";
    //     $('#txtYearProfile').focus();
    //     return messageValidate;
    // }

    // if (v_status == true) {
    //     var v_newLeaveDate = v_leaveDate.substring(0, 2);
    //     var v_newLeaveMonth = v_leaveDate.substring(3, 5);
    //     var v_newLeaveYear = v_leaveDate.substring(6, v_leaveDate.length);
    //     //console.log(v_newLeaveDate);console.log(v_newLeaveMonth);console.log(v_newLeaveYear);
    //     if (v_yearYear > v_newLeaveYear || (v_yearYear == v_newLeaveYear && v_yearMonth > v_newLeaveMonth)) {
    //         messageValidate = "Ngày nghỉ không được nhỏ hơn ngày vào học!";
    //         $('#txtDateNghi').focus(); 
    //         return messageValidate;
    //     }
    // }
};

//Search-------------------------------------------------------------------------------
function autocompleteSearch(idControl, number = null) {
    var keySearch = "";
    $('#' + idControl).autocomplete({
        source: function (request, response) {
            keySearch = $.ui.autocomplete.escapeRegex(request.term).replace(/[%\\\-]/g, '');
            //console.log(keySearch.length);
            if (keySearch.length >= 2) {
                GET_INITIAL_NGHILC();
                if (number == null || number == "") {
                    loaddataProfile($('select#viewTableProfile').val(), $('select#sltTruongGrid').val(), $('select#sltLopGrid').val(), keySearch);
                }

                if (number == 1) {//Danh sách học sinh phê duyệt cấp trường
                    loaddataDanhSachTongHop($('#drPagingDanhsachtonghop').val(), keySearch, $('#sltTrangthai').val());
                }
                if (number == 9) {//Danh sách học sinh lâp công văn
                    loaddataDanhSachLapCV($('#drPagingDanhsachtonghop').val(), keySearch, $('#sltTrangthai').val());
                }

                // if (number == 2) {
                //     if($('#sltCongvan').val() == '' || $('#sltCongvan').val() == null){
                //         loadDanhSachSoCongVan(keySearch);
                //     }else{
                //         loadDanhSachByCongVan($('#drPagingDanhsachtonghop').val(), keySearch, loaiDanhSach);
                //     }
                // }
                if (number == 3) {
                    if (($('#drSchoolTHCD').val() !== null && $('#drSchoolTHCD').val() !== "")
                        && ($('#sltLoaiCongvan').val() !== null && $('#sltLoaiCongvan').val() !== "")
                        && ($('#sltCongvan').val() !== null && $('#sltCongvan').val() !== "")) {
                        GET_INITIAL_NGHILC();
                        loadDanhSachHSDaLap($('#drPagingDanhsachtonghop').val(), keySearch);
                    }
                    else {
                        loadDanhSachByPhongSo($('#drPagingDanhsachtonghop').val(), $('#drSchoolTHCD').val(), $('#sltLoaiCongvan').val(), keySearch);
                    }
                }
                // if (number == 4) {
                //     if($('#sltCongvan').val() == '' || $('#sltCongvan').val() == null){
                //         loadSoCongVanDanhSachTraLai(keySearch);
                //     }else{
                //         loadlistUnApproved($('#drPagingDanhsachtonghop').val(), keySearch);
                //     }

                // }
                // if (number == 5) {
                //     loadlistUnApprovedThamdinh($('#drPagingDanhsachtonghop').val(), keySearch);
                // }

            } else if (keySearch.length < 2) {
                GET_INITIAL_NGHILC();
                if (number == null || number == "") {
                    loaddataProfile($('select#viewTableProfile').val(), $('select#sltTruongGrid').val(), $('select#sltLopGrid').val(), "");
                }

                if (number == 1) {//Danh sách học sinh phê duyệt cấp trường
                    loaddataDanhSachTongHop($('#drPagingDanhsachtonghop').val(), "", $('#sltTrangthai').val());
                }
                if (number == 9) {// Danh sach học sinh lap cong van
                    loaddataDanhSachLapCV($('#drPagingDanhsachtonghop').val(), "", $('#sltTrangthai').val());
                }
                if (number == 3) {
                    if (($('#drSchoolTHCD').val() !== null && $('#drSchoolTHCD').val() !== "")
                        && ($('#sltLoaiCongvan').val() !== null && $('#sltLoaiCongvan').val() !== "")
                        && ($('#sltCongvan').val() !== null && $('#sltCongvan').val() !== "")) {
                        GET_INITIAL_NGHILC();
                        loadDanhSachHSDaLap($('#drPagingDanhsachtonghop').val(), "");
                    }
                    else {
                        loadDanhSachByPhongSo($('#drPagingDanhsachtonghop').val(), $('#drSchoolTHCD').val(), $('#sltLoaiCongvan').val(), "");
                    }
                }
                // if (number == 4) {
                //   if($('#sltCongvan').val() == '' || $('#sltCongvan').val() == null){
                //         loadSoCongVanDanhSachTraLai("");
                //     }else{
                //         loadlistUnApproved($('#drPagingDanhsachtonghop').val(), "");
                //     }

                // }
                // if (number == 5) {
                //     loadlistUnApprovedThamdinh($('#drPagingDanhsachtonghop').val(), "");
                // }
            }
        },
        minLength: 0,
        delay: 222,
        autofocus: true
    });
};

function autocompleteSearchDSPheDuyet(idControl) {
    var keySearch = "";
    $('#' + idControl).autocomplete({
        source: function (request, response) {
            keySearch = $.ui.autocomplete.escapeRegex(request.term).replace(/[%\\\-]/g, '');
            //console.log(keySearch.length);
            if (keySearch.length >= 2) {
                GET_INITIAL_NGHILC();

                if ($('#sltCongvan').val() == '' || $('#sltCongvan').val() == null) {
                    loadDanhSachSoCongVan(keySearch);
                } else {
                    loadDanhSachByCongVan($('#drPagingDanhsachtonghop').val(), keySearch, loaiDanhSach);
                }
            } else if (keySearch.length < 2) {
                GET_INITIAL_NGHILC();

                if ($('#sltCongvan').val() == '' || $('#sltCongvan').val() == null) {
                    loadDanhSachSoCongVan('');
                } else {
                    loadDanhSachByCongVan($('#drPagingDanhsachtonghop').val(), '', loaiDanhSach);
                }
            }
        },
        minLength: 0,
        delay: 222,
        autofocus: true
    });
};

function loadComboDecidedType() {
    var html_show = "";
    html_show += "<option value='MGHP'>Miễn giảm học phí</option>";
    html_show += "<option value='CPHT'>Chi phí học tập</option>";
    html_show += "<option value='HTAT'>Hỗ trợ ăn trưa</option>";
    html_show += "<option value='HTBT'>Hỗ trợ bán trú</option>";
    html_show += "<option value='NGNA'>Hỗ trợ người nấu ăn</option>";
    html_show += "<option value='HSKT'>Hỗ trợ học sinh khuyết tật</option>";
    html_show += "<option value='HSDTTS'>Hỗ trợ học sinh dân tộc thiểu số tại huyện Mù Cang Chải và Trạm tấu</option>";
    html_show += "<option value='TONGHOP'>Chế độ chính sách ưu đãi</option>";

    $('#drDecidedType').html(html_show);
}
function change_alias(alias) {
    var str = alias;
    str = str.toLowerCase();
    str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ  |ặ|ẳ|ẵ/g, "a");
    str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
    str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
    str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ  |ợ|ở|ỡ/g, "o");
    str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
    str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
    str = str.replace(/đ/g, "d");
    str = str.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'| |\"|\&|\#|\[|\]|~|$|_/g, "-");
    /* tìm và thay thế các kí tự đặc biệt trong chuỗi sang kí tự - */
    str = str.replace(/-+-/g, "-"); //thay thế 2- thành 1-
    str = str.replace(/^\-+|\-+$/g, "");
    //cắt bỏ ký tự - ở đầu và cuối chuỗi 
    return str;
}

function controlClass(valback, valnext, dateout, arrProfileID, classID, year, classID_next, selectedALL, valchange = null, dateChange = null) {
    var v_jsonClass = {
        CLASSBACK: valback,
        CLASSCHANGE: valchange,
        CLASSNEXT: valnext,
        DATEOUTPROFIEL: dateout,
        ARRPROFILEID: arrProfileID,
        CLASSID: classID,
        YEAR: year,
        CLASSIDNEXT: classID_next,
        SELECTEDALL: selectedALL,
        OUT: $('#ckbHSNghiHoc').is(':checked'),
        DATECHANGE: dateChange
    };
    PostToServer('/ho-so/hoc-sinh/uptoprofile', v_jsonClass, function (data) {
        if (data['success'] != "" && data['success'] != undefined) {
            utility.message("Thông báo", data['success'], null, 3000);
            //$('#uptoClass').DataTable().clear().draw().destroy();
            //resetControl();
            GET_INITIAL_NGHILC();
            $('#upClass-select-all').prop('checked', false);
            loaddataProfile($('select#viewTableProfile').val(), $('select#sltTruongGrid').val(), $('select#sltLopGrid').val(), $('#txtSearchProfile').val());
        }
        if (data['error'] != "" && data['error'] != undefined) {
            utility.message("Thông báo", data['error'], null, 3000, 1);
        }
    }, function (data) {
        console.log("controlClass");
        console.log(data);
    }, "btnUpto", "", "");

}
function loadNamhocByClass(classID) {
    var form_datas = new FormData();
    form_datas.append('CLASSID', classID);
    $.ajax({
        type: "POST",
        url: '/ho-so/hoc-sinh/getYearHisByClassID',
        data: form_datas,
        contentType: false,
        cache: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
        success: function (data) {
            console.log(data);
            var html_show = "";
            $('#drYearUpto').html("");
            // console.log(data);
            if (data.length > 0) {
                html_show += '<option value="">--Chọn năm học--</option>';
                for (var i = 0; i < data.length; i++) {
                    html_show += '<option value="' + data[i].his_year + '">' + data[i].his_year + '</option>';
                }
            }
            else {
                $('#drYearUpto').html('<option value="">--Chưa có năm học--</option>');
            }
        }, error: function (data) {
            $('#btnInsertTHCD').button('reset');
        }
    });
}

function getUnitAll(idchoise = 0) {
    //   var list   = [];
    PostToServer('/danh-muc/lop/getUnitAll', {}, function (data) {
        $('#sltKhoiDt').html("");
        var html_show = "";
        if (data.length > 0) {
            html_show += "<option selected value=''>-- Chọn khối --</option>";
            for (var i = 0; i < data.length; i++) {
                html_show += "<option value='" + data[i].unit_id + "'>" + data[i].unit_name + "</option>";
            }
            $('#sltKhoiDt').html(html_show);
        }
        else {
            $('#sltKhoiDt').html("<option value=''>-- Chưa có khối --</option>");
        }

        if (idchoise > 0) {
            $('#sltKhoiDt').val(idchoise);
        }
        $('#sltKhoiDt').selectpicker('refresh');
    }, function (data) {
        console.log("getUnitAll");
        console.log(data);
    }, "", "", "");
};

//----------------------------------------------------------Danh sách hỗ trợ tổng hợp-----------------------------------------------------------
function updateMoneyNew(objData) {
    // PostToServer('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/updatemoneynew',objData,"","","","","");
    $.ajax({
        type: "POST",
        url: '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/updatemoneynew',
        data: objData,
        contentType: false,
        cache: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
        success: function (data) {
            // console.log(data);
        }, error: function (data) {
            console.log(data);
        }
    });
}

var _year = '';
function loaddataDanhSachTongHop(row, keySearch = "", status = $('#sltStatus').val(), type = null, order = null) {
    var schools_id = $('#drSchoolTHCD').val();
    var year = $('#sltYear').val();
    var classes = $('#sltKhoiLop').val();
    _year = year;
    var number = 0;
    var nam = 0;

    if (year !== null && year !== "" && year !== undefined) {
        var ky = year.split("-");
        nam = ky[1];
        if (ky[0] == 'HK1') {
            number = 1;
        }
        else if (ky[0] == 'HK2') {
            number = 2;
        }
        else if (ky[0] == 'CA') {
            number = 3;
        }
    }
    var or = 0;
    if (order == 0 || order == null) {
        or = 1;
    } else {
        or = 0;
    }

    var html_show = "";
    var o = {
        start: (GET_START_RECORD_NGHILC()),
        limit: row,
        SCHOOLID: schools_id,
        CLASS_TYPE: classes,
        YEAR: year,
        KEY: keySearch,
        STATUS: status,
        TYPE: type,
        ORDER: order
    };
    var html_header = '';
    html_header += '<tr class="success" id="cmisGridHeader">';
    html_header += '<th  class="text-center" rowspan="2" style="vertical-align:middle;width: 3%">STT</th>';
    html_header += '<th  class="text-center class-pointer" rowspan="2" style="vertical-align:middle;width: 12%"><span onclick="GET_INITIAL_NGHILC();loaddataDanhSachTongHop(' + row + ',\'' + keySearch + '\',\'' + status + '\',14,' + or + ');">Tên học sinh</span></th>';
    html_header += '<th  class="text-center" rowspan="2" style="vertical-align:middle;width: 6%">Ngày sinh</th>';
    html_header += '<th  class="text-center" rowspan="2" style="vertical-align:middle;width: 7%">Cấp học</th>';
    html_header += '<th  class="text-center class-pointer"  rowspan="2" style="vertical-align:middle;width: 7%"><span onclick="GET_INITIAL_NGHILC();loaddataDanhSachTongHop(' + row + ',\'' + keySearch + '\',\'' + status + '\',13,' + or + ');">Lớp học</span></th>';
    html_header += '<th  class="text-center" colspan="11" style="vertical-align:middle;">Hỗ trợ</th>';
    html_header += '<th  class="text-center class-pointer" rowspan="2" style="vertical-align:middle;width: 7%"><span onclick="GET_INITIAL_NGHILC();loaddataDanhSachTongHop(' + row + ',\'' + keySearch + '\',\'' + status + '\',12,' + or + ');">Tổng tiền</span></th>';
    html_header += '<th  class="text-center" rowspan="2" style="vertical-align:middle;">';
    html_header += '<button type="button" title="Duyệt tất cả" onclick="btnAllCheDoHocKy()" style="font-size: 10px !important" class="btn btn-success css-label" id=""><i class="glyphicon glyphicon-ok"></i></button>';
    html_header += '</th></tr>';
    html_header += '<tr class="success">';
    html_header += '<th  class="text-center  class-pointer" title="Miễn giảm học phí" style="vertical-align:middle;width: 5%"><span onclick="GET_INITIAL_NGHILC();loaddataDanhSachTongHop(' + row + ',\'' + keySearch + '\',\'' + status + '\',1,' + or + ');">MGHP</span></th>';
    html_header += '<th  class="text-center  class-pointer" title="Chi phí học tập" style="vertical-align:middle;width: 5%"><span onclick="GET_INITIAL_NGHILC();loaddataDanhSachTongHop(' + row + ',\'' + keySearch + '\',\'' + status + '\',2,' + or + ');">CPHT</span></th>';
    html_header += '<th  class="text-center  class-pointer" title="Hỗ trợ ăn trưa cho trẻ em mẫu giáo" style="vertical-align:middle;width: 5%"><span onclick="GET_INITIAL_NGHILC();loaddataDanhSachTongHop(' + row + ',\'' + keySearch + '\',\'' + status + '\',3,' + or + ');">AT TEMG</span></th>';
    html_header += '<th  class="text-center  class-pointer" title="Hỗ trợ bán trú tiền ăn" style="vertical-align:middle;width: 5%"><span onclick="GET_INITIAL_NGHILC();loaddataDanhSachTongHop(' + row + ',\'' + keySearch + '\',\'' + status + '\',4,' + or + ');">BT TA</span></th>';
    html_header += '<th  class="text-center  class-pointer" title="Hỗ trợ bán trú tiền ở" style="vertical-align:middle;width: 5%"><span onclick="GET_INITIAL_NGHILC();loaddataDanhSachTongHop(' + row + ',\'' + keySearch + '\',\'' + status + '\',5,' + or + ');">BT TO</span></th>';
    html_header += '<th  class="text-center  class-pointer" title="Hỗ trợ bán trú văn hóa tủ thuốc" style="vertical-align:middle;width: 5%"><span onclick="GET_INITIAL_NGHILC();loaddataDanhSachTongHop(' + row + ',\'' + keySearch + '\',\'' + status + '\',6,' + or + ');">BT VHTT</span></th>'
    html_header += '<th  class="text-center  class-pointer" title="Hỗ trợ tiền ăn học sinh" style="vertical-align:middle;width: 5%"><span onclick="GET_INITIAL_NGHILC();loaddataDanhSachTongHop(' + row + ',\'' + keySearch + '\',\'' + status + '\',7,' + or + ');">TA HS</span></th>';
    html_header += '<th  class="text-center  class-pointer" title="Học sinh khuyết tật - học bổng" style="vertical-align:middle;width: 5%"><span onclick="GET_INITIAL_NGHILC();loaddataDanhSachTongHop(' + row + ',\'' + keySearch + '\',\'' + status + '\',8,' + or + ');">HSKT HB</span></th>';
    html_header += '<th  class="text-center  class-pointer" title="Học sinh khuyết tật - dụng cụ học tập" style="vertical-align:middle;width: 5%"><span onclick="GET_INITIAL_NGHILC();loaddataDanhSachTongHop(' + row + ',\'' + keySearch + '\',\'' + status + '\',9,' + or + ');">HSKT DDHT</span></th>';
    html_header += '<th  class="text-center  class-pointer" title="Học sinh dân tộc nội trú - học bổng" style="vertical-align:middle;width: 5%"><span onclick="GET_INITIAL_NGHILC();loaddataDanhSachTongHop(' + row + ',\'' + keySearch + '\',\'' + status + '\',10,' + or + ');">HSDTNT</span></th>';
    html_header += '<th  class="text-center  class-pointer" title="Học sinh dân tộc thiểu số" style="vertical-align:middle;width: 5%"><span onclick="GET_INITIAL_NGHILC();loaddataDanhSachTongHop(' + row + ',\'' + keySearch + '\',\'' + status + '\',11,' + or + ');">HSDTTS</span></th></tr>';
    $('#headerDanhSachTongHop').html(html_header);
    $('#dataDanhsachTonghop').html("<tr><td colspan='50' class='text-center'>Đang tải dữ liệu...</td></tr>");
    $('#cbxAllChedoID').attr("disabled", "disabled");
    $('#cbxAllChedo').prop('checked', false);
    PostToServer('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/load', o, function (datas) {
        SETUP_PAGING_NGHILC(datas, function () {
            loaddataDanhSachTongHop(row, keySearch, status, type, order);
        });


        var dataget = datas.data;

        if (dataget.length > 0) {
            for (var i = 0; i < dataget.length; i++) {

                html_show += "<tr><td class='text-center' style='vertical-align:middle'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                if (check_Permission_Feature("2")) {
                    html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='getHoSoHocSinh(" + dataget[i].profile_id + ");'>" + dataget[i].profile_name + "</a></td>";
                } else {
                    html_show += "<td style='vertical-align:middle'>" + dataget[i].profile_name + "</td>";
                }

                html_show += "<td class='text-center' style='vertical-align:middle'>" + formatDates(dataget[i].profile_birthday) + "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(dataget[i].unit_name) + "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(dataget[i].class_name) + "</td>";
                if (parseInt(dataget[i].update_profile) == 1) {
                    html_show += "<td colspan='15' style='vertical-align:middle;color:blue' class='text-center'>Học sinh đang cập nhật.Xin mời đợi!</td>";
                } else {
                    html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].MGHP) + "</td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].CPHT) + "</td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].HTAT) + "</td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].HTBT_TA) + "</td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].HTBT_TO) + "</td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].HTBT_VHTT) + "</td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].HTATHS) + "</td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].HSKT_HB) + "</td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].HSKT_DDHT) + "</td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].HBHSDTNT) + "</td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].HSDTTS) + "</td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(dataget[i].TONGTIEN) + "</b></td>";

                    if (parseInt(dataget[i].TRANGTHAI) == 0) {
                        html_show += "<td class='text-center' style='vertical-align:middle'><button class='btn btn-primary btn-xs' onclick='openPopupDuyetChedo(" + dataget[i].profile_id + ", " + dataget[i].rppst_id + ", " + number + ", " + nam + ", \"" + dataget[i].profile_name + "\")' title='Chưa xử lý'><i class='glyphicon glyphicon-unchecked'></i></button></td>";
                    } else if (parseInt(dataget[i].TRANGTHAI) == 1) {
                        $('#cbxAllChedo').prop('checked', true);
                        $('#checkedAllChedo').prop('checked', true);
                        html_show += "<td class='text-center' style='vertical-align:middle'><button class='btn btn-success btn-xs' onclick='openPopupDuyetChedo(" + dataget[i].profile_id + ", " + dataget[i].rppst_id + ", " + number + ", " + nam + ", \"" + dataget[i].profile_name + "\")' title='Đã xử lý'><i class='glyphicon glyphicon-check'></i></button></td>";
                    }

                    // }
                    // else {
                    //     html_show += "<td class='text-center' style='vertical-align:middle'><button class='btn btn-primary btn-xs'> Đã phê duyệt</button></td>";
                    // }

                    //if(check_Permission_Feature("2")){
                    //  html_show += "<td class='text-center' style='vertical-align:middle'><button data='"+dataget[i].profile_id+"' onclick='getProfileSubById("+parseInt(dataget[i].profile_id)+", "+number+", "+nam+",\"" + dataget[i].profile_name + "\");' class='btn btn-info btn-xs' id='editor_editss' title='Thông tin chi tiết'><i class='glyphicon glyphicon-info-sign'></i></button></td>";
                    //}
                    // if(check_Permission_Feature("3")){
                    //     html_show += "<td class='text-center' style='vertical-align:middle'><button  onclick='delHoSoHocSinh("+dataget[i].profile_id+");'  class='btn btn-danger btn-xs editor_remove'><i class='glyphicon glyphicon-remove'></i> Xóa</button></td>";
                    // }
                }


                html_show += "</tr>";
            }
            $('#cbxAllChedoID').removeAttr("disabled");
        }
        else {
            html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
        }
        $('#dataDanhsachTonghop').html(html_show);
    }, function (result) {
        console.log(result);
    }, "btnLoadDataTruong", "", "");

};

function loaddataDanhSachLapCV(row, keySearch = "", status = $('#sltStatus').val(), type = null, order = null) {
    var schools_id = $('#drSchoolTHCD').val();
    var year = $('#sltYear').val();
    var number = 0;
    var nam = year;
    var html_show = "";
    var or = 0;
    if (order == 0 || order == null) {
        or = 1;
    } else {
        or = 0;
    }
    var o = {
        start: (GET_START_RECORD_NGHILC()),
        limit: row,
        SCHOOLID: schools_id,
        YEAR: year,
        CLASS_TYPE: $('#sltKhoiLop').val(),
        KEY: keySearch,
        STATUS: status,
        ORDER: order,
        TYPE: type
    };
    var html_header = '';
    html_header += '<tr class="success" id="cmisGridHeader">';
    html_header += '<th  class="text-center" rowspan="2" style="vertical-align:middle;width: 3%">STT</th>';
    html_header += '<th  class="text-center class-pointer" rowspan="2" style="vertical-align:middle;width: 12%"><span onclick="GET_INITIAL_NGHILC();loaddataDanhSachLapCV(' + row + ',\'' + keySearch + '\',\'' + status + '\',14,' + or + ');">Tên học sinh</span></th>';
    html_header += '<th  class="text-center" rowspan="2" style="vertical-align:middle;width: 6%">Ngày sinh</th>';
    html_header += '<th  class="text-center" rowspan="2" style="vertical-align:middle;width: 7%">Khối học</th>';
    html_header += '<th  class="text-center class-pointer" rowspan="2" style="vertical-align:middle;width: 7%"><span onclick="GET_INITIAL_NGHILC();loaddataDanhSachLapCV(' + row + ',\'' + keySearch + '\',\'' + status + '\',13,' + or + ');">Lớp học</span></th>';
    html_header += '<th  class="text-center" colspan="11" style="vertical-align:middle;">Hỗ trợ</th>';
    html_header += '<th  class="text-center class-pointer" rowspan="2" style="vertical-align:middle;width: 7%"><span onclick="GET_INITIAL_NGHILC();loaddataDanhSachLapCV(' + row + ',\'' + keySearch + '\',\'' + status + '\',12,' + or + ');">Tổng tiền</span></th>';
    html_header += '<th  class="text-center" rowspan="2" colspan="3" style="vertical-align:middle;">';
    html_header += '<button type="button" title="Duyệt tất cả" onclick="btnAllCheDoCV()" style="font-size: 10px !important" class="btn btn-success css-label" id=""><i class="glyphicon glyphicon-ok"></i></button>';
    html_header += '</th></tr>';
    html_header += '<tr class="success">';
    html_header += '<th  class="text-center  class-pointer" title="Miễn giảm học phí" style="vertical-align:middle;width: 5%"><span onclick="GET_INITIAL_NGHILC();loaddataDanhSachLapCV(' + row + ',\'' + keySearch + '\',\'' + status + '\',1,' + or + ');">MGHP</span></th>';
    html_header += '<th  class="text-center  class-pointer" title="Chi phí học tập" style="vertical-align:middle;width: 5%"><span onclick="GET_INITIAL_NGHILC();loaddataDanhSachLapCV(' + row + ',\'' + keySearch + '\',\'' + status + '\',2,' + or + ');">CPHT</span></th>';
    html_header += '<th  class="text-center  class-pointer" title="Hỗ trợ ăn trưa cho trẻ em mẫu giáo" style="vertical-align:middle;width: 5%"><span onclick="GET_INITIAL_NGHILC();loaddataDanhSachLapCV(' + row + ',\'' + keySearch + '\',\'' + status + '\',3,' + or + ');">AT TEMG</span></th>';
    html_header += '<th  class="text-center  class-pointer" title="Hỗ trợ bán trú tiền ăn" style="vertical-align:middle;width: 5%"><span onclick="GET_INITIAL_NGHILC();loaddataDanhSachLapCV(' + row + ',\'' + keySearch + '\',\'' + status + '\',4,' + or + ');">BT TA</span></th>';
    html_header += '<th  class="text-center  class-pointer" title="Hỗ trợ bán trú tiền ở" style="vertical-align:middle;width: 5%"><span onclick="GET_INITIAL_NGHILC();loaddataDanhSachLapCV(' + row + ',\'' + keySearch + '\',\'' + status + '\',5,' + or + ');">BT TO</span></th>';
    html_header += '<th  class="text-center  class-pointer" title="Hỗ trợ bán trú văn hóa tủ thuốc" style="vertical-align:middle;width: 5%"><span onclick="GET_INITIAL_NGHILC();loaddataDanhSachLapCV(' + row + ',\'' + keySearch + '\',\'' + status + '\',6,' + or + ');">BT VHTT</span></th>'
    html_header += '<th  class="text-center  class-pointer" title="Hỗ trợ tiền ăn học sinh" style="vertical-align:middle;width: 5%"><span onclick="GET_INITIAL_NGHILC();loaddataDanhSachLapCV(' + row + ',\'' + keySearch + '\',\'' + status + '\',7,' + or + ');">TA HS</span></th>';
    html_header += '<th  class="text-center  class-pointer" title="Học sinh khuyết tật - học bổng" style="vertical-align:middle;width: 5%"><span onclick="GET_INITIAL_NGHILC();loaddataDanhSachLapCV(' + row + ',\'' + keySearch + '\',\'' + status + '\',8,' + or + ');">HSKT HB</span></th>';
    html_header += '<th  class="text-center  class-pointer" title="Học sinh khuyết tật - dụng cụ học tập" style="vertical-align:middle;width: 5%"><span onclick="GET_INITIAL_NGHILC();loaddataDanhSachLapCV(' + row + ',\'' + keySearch + '\',\'' + status + '\',9,' + or + ');">HSKT DDHT</span></th>';
    html_header += '<th  class="text-center  class-pointer" title="Học sinh dân tộc nội trú - học bổng" style="vertical-align:middle;width: 5%"><span onclick="GET_INITIAL_NGHILC();loaddataDanhSachLapCV(' + row + ',\'' + keySearch + '\',\'' + status + '\',10,' + or + ');">HSDTNT</span></th>';
    html_header += '<th  class="text-center  class-pointer" title="Học sinh dân tộc thiểu số" style="vertical-align:middle;width: 5%"><span onclick="GET_INITIAL_NGHILC();loaddataDanhSachLapCV(' + row + ',\'' + keySearch + '\',\'' + status + '\',11,' + or + ');">HSDTTS</span></th></tr>';

    $('#headerDataLapCongVan').html(html_header);
    $('#dataDanhsachTonghop').html("<tr><td colspan='50' class='text-center'>Đang tải dữ liệu...</td></tr>");
    $('#cbxAllChedoID').attr("disabled", "disabled");
    $('#cbxAllChedo').prop('checked', false);
    PostToServer('/ho-so/lap-danh-sach/lap-cong-van/load', o, function (datas) {
        SETUP_PAGING_NGHILC(datas, function () {
            loaddataDanhSachLapCV(row, keySearch, status, type, order);
        });


        var dataget = datas.data;

        if (dataget.length > 0) {
            for (var i = 0; i < dataget.length; i++) {

                html_show += "<tr><td class='text-center' style='vertical-align:middle'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                if (check_Permission_Feature("2")) {
                    if (parseInt(dataget[i].TRANGTHAI) == 2) {
                        html_show += "<td style='vertical-align:middle'><a class='primary' title='Học sinh điều chỉnh' href='javascript:;' onclick='getHoSoHocSinh(" + dataget[i].profile_id + ");'>" + dataget[i].profile_name + "</a></td>";
                    } else {
                        html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='getHoSoHocSinh(" + dataget[i].profile_id + ");'>" + dataget[i].profile_name + "</a></td>";
                    }
                } else {
                    if (parseInt(dataget[i].TRANGTHAI) == 2) {
                        html_show += "<td style='vertical-align:middle'><span class='primary' title='Học sinh điều chỉnh'>" + dataget[i].profile_name + "</span></td>";
                    } else {
                        html_show += "<td style='vertical-align:middle'>" + dataget[i].profile_name + "</td>";
                    }

                }

                html_show += "<td class='text-center' style='vertical-align:middle'>" + formatDates(dataget[i].profile_birthday) + "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(dataget[i].unit_name) + "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(dataget[i].class_name) + "</td>";
                if (parseInt(dataget[i].update_profile) == 1) {
                    html_show += "<td colspan='15' style='vertical-align:middle;color:blue' class='text-center'>Học sinh đang cập nhật.Xin mời đợi!</td>";
                } else {
                    if (parseInt(dataget[i].ttMGHP) > 0) {
                        html_show += "<td class='text-right green' style='vertical-align:middle' title='Đã xử lý'>" + formatter(dataget[i].MGHP) + "</td>";
                    } else {
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].MGHP) + "</td>";
                    }

                    if (parseInt(dataget[i].ttCPHT) > 0) {
                        html_show += "<td class='text-right green' style='vertical-align:middle' title='Đã xử lý'>" + formatter(dataget[i].CPHT) + "</td>";
                    } else {
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].CPHT) + "</td>";
                    }

                    if (parseInt(dataget[i].ttHTAT) > 0) {
                        html_show += "<td class='text-right green' style='vertical-align:middle' title='Đã xử lý'>" + formatter(dataget[i].HTAT) + "</td>";
                    } else {
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].HTAT) + "</td>";
                    }

                    if (parseInt(dataget[i].ttHTBT_TA) > 0) {
                        html_show += "<td class='text-right green' style='vertical-align:middle' title='Đã xử lý'>" + formatter(dataget[i].HTBT_TA) + "</td>";
                    } else {
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].HTBT_TA) + "</td>";
                    }

                    if (parseInt(dataget[i].ttHTBT_T0) > 0) {
                        html_show += "<td class='text-right green' style='vertical-align:middle' title='Đã xử lý'>" + formatter(dataget[i].HTBT_TO) + "</td>";
                    } else {
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].HTBT_TO) + "</td>";
                    }

                    if (parseInt(dataget[i].ttHTBT_VHTT) > 0) {
                        html_show += "<td class='text-right green' style='vertical-align:middle' title='Đã xử lý'>" + formatter(dataget[i].HTBT_VHTT) + "</td>";
                    } else {
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].HTBT_VHTT) + "</td>";
                    }

                    if (parseInt(dataget[i].ttHTATHS) > 0) {
                        html_show += "<td class='text-right green' style='vertical-align:middle' title='Đã xử lý'>" + formatter(dataget[i].HTATHS) + "</td>";
                    } else {
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].HTATHS) + "</td>";
                    }

                    if (parseInt(dataget[i].ttHSKT_HB) > 0) {
                        html_show += "<td class='text-right green' style='vertical-align:middle' title='Đã xử lý'>" + formatter(dataget[i].HSKT_HB) + "</td>";
                    } else {
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].HSKT_HB) + "</td>";
                    }

                    if (parseInt(dataget[i].ttHSKT_DDHT) > 0) {
                        html_show += "<td class='text-right green' style='vertical-align:middle' title='Đã xử lý'>" + formatter(dataget[i].HSKT_DDHT) + "</td>";
                    } else {
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].HSKT_DDHT) + "</td>";
                    }

                    if (parseInt(dataget[i].ttHBHSDTNT) > 0) {
                        html_show += "<td class='text-right green' style='vertical-align:middle' title='Đã xử lý'>" + formatter(dataget[i].HBHSDTNT) + "</td>";
                    } else {
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].HBHSDTNT) + "</td>";
                    }

                    if (parseInt(dataget[i].ttHSDTTS) > 0) {
                        html_show += "<td class='text-right green' style='vertical-align:middle' title='Đã xử lý'>" + formatter(dataget[i].HSDTTS) + "</td>";
                    } else {
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].HSDTTS) + "</td>";
                    }

                    html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(dataget[i].TONGTIEN) + "</b></td>";
                    //if(parseInt(dataget[i].status) == 0){
                    if (parseInt(dataget[i].TRANGTHAIPHEDUYET) == 0) {
                        html_show += "<td class='text-center' style='vertical-align:middle'><button class='btn btn-primary btn-xs' onclick='openPopupDuyetLapCV(" + dataget[i].profile_id + " , " + parseInt(dataget[i].TRANGTHAIPHEDUYET) + ", \"" + dataget[i].profile_name + "\",\"" + year + "\",\"" + ConvertString(dataget[i].note) + "\")' title='Chưa xử lý'><i class='glyphicon glyphicon-unchecked'></i></button></td>";
                    } else if (parseInt(dataget[i].TRANGTHAIPHEDUYET) == 1 || parseInt(dataget[i].TRANGTHAIPHEDUYET) == 2) {
                        $('input#cbxAllLapCongVan').prop('checked', true);
                        html_show += "<td class='text-center' style='vertical-align:middle'><button class='btn btn-success btn-xs' onclick='openPopupDuyetLapCV(" + dataget[i].profile_id + ", " + parseInt(dataget[i].TRANGTHAIPHEDUYET) + ", \"" + dataget[i].profile_name + "\",\"" + year + "\",\"" + ConvertString(dataget[i].note) + "\")' title='Đã xử lý'><i class='glyphicon glyphicon-check'></i></button></td>";
                    }
                    html_show += "<td class='text-center' style='vertical-align:middle'><button onclick='updateCheDo(" + parseInt(dataget[i].profile_id) + ",\"" + dataget[i].profile_name + "\");' class='btn btn-info btn-xs' id='editor_editss' title='Cập nhật chế độ học sinh'><i class='glyphicon glyphicon-refresh'></i></button></td>";
                    // }else{
                    //     html_show += "<td class='text-center' style='vertical-align:middle' title='Đang cập nhật'><img alt='' src='/images/loading.gif' class='menu_Icon' width='20px' height='20px'></td>";
                    // }
                    // }
                    // else {
                    //     html_show += "<td class='text-center' style='vertical-align:middle'><button class='btn btn-primary btn-xs'> Đã phê duyệt</button></td>";
                    // }

                    //if(check_Permission_Feature("2")){

                    html_show += "<td class='text-center' style='vertical-align:middle'><button data='" + dataget[i].profile_id + "' onclick='getProfileSubById(" + parseInt(dataget[i].profile_id) + ", " + number + ",\"" + nam + "\",\"" + dataget[i].profile_name + "\");' class='btn btn-info btn-xs' id='editor_editss' title='Thông tin chi tiết'><i class='glyphicon glyphicon-info-sign'></i></button></td>";
                    //}
                    // if(check_Permission_Feature("3")){
                    //     html_show += "<td class='text-center' style='vertical-align:middle'><button  onclick='delHoSoHocSinh("+dataget[i].profile_id+");'  class='btn btn-danger btn-xs editor_remove'><i class='glyphicon glyphicon-remove'></i> Xóa</button></td>";
                    // }
                }


                html_show += "</tr>";
            }
            $('#cbxAllChedoID').removeAttr("disabled");
        }
        else {
            html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
        }
        $('#dataDanhsachTonghop').html(html_show);
    }, function (result) {
        console.log(result);
    }, "btnLoadDataTruong", "", "");

};

function loadComboboxHocky(level, callback) {
    GetFromServer('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/loadhocky', function (dataget) {
        // console.log(dataget['HOCKY']);

        // $('#sltYear').html("");
        //     var html_show = "";
        //     if(dataget.length >0){
        //         html_show += "<option selected='selected' value=''>-- Chọn năm học --</option>";
        //         for (var i = dataget.length - 1; i >= 0; i--) {
        //             html_show += "<option value='"+dataget[i].qlhs_hocky_value+"'>"+dataget[i].qlhs_hocky_name+"</option>";
        //         }
        //         $('#sltYear').html(html_show);
        //     }else{
        //         $('#sltYear').html("<option value=''>-- Chưa có năm học --</option>");
        //     }
        var hocky = dataget['HOCKY'];
        var namhoc = dataget['NAMHOC'];
        // <optgroup label="Cats">
        $('#sltYear').html("");
        //$('#sltTruongGrid').html("");
        var html_show = "";
        if (namhoc.length > 0) {
            html_show += "<option value='0'>-- Chọn học kỳ --</option>";
            for (var j = 0; j < namhoc.length; j++) {
                html_show += "<optgroup label='Năm học " + namhoc[j].name + "'>";
                if (hocky.length > 0) {
                    for (var i = 0; i < hocky.length; i++) {
                        if (namhoc[j].code === hocky[i].qlhs_hocky_code) {
                            html_show += "<option value='" + hocky[i].qlhs_hocky_value + "'>" + hocky[i].qlhs_hocky_name + "</option>";
                        }
                    }
                }
                html_show += "</optgroup>"
            }
            //alert((new Date()).getFullYear() - 1);
            //$('#sltTruong').html(html_show);
            $('#sltYear').html(html_show);
            $('#sltYear').val('HK1-' + ((new Date()).getFullYear()));
            //Danh sách Trường
            if (level == 1) {
                if ($('#drSchoolTHCD').val() != '') {
                    GET_INITIAL_NGHILC();
                    loaddataDanhSachTongHop($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
                }
            }
            //Danh sách Phòng
            if (level == 2) {
                GET_INITIAL_NGHILC();
                // loadDanhSachChoPheDuyet($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
            }
            //Danh sách Sở
            if (level == 3) {
                GET_INITIAL_NGHILC();
                // loadlistApprovedPheDuyet($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
            }
            //Danh sách Phòng trả lại
            if (level == 4) {
                GET_INITIAL_NGHILC();
                // loadlistUnApproved($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val());
            }
            //Danh sách Sở trả lại
            if (level == 5) {
                GET_INITIAL_NGHILC();
                // loadlistUnApprovedThamdinh($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val());
            }
        } else {
            //$('#sltTruongGrid').html("<option value=''>-- Chưa có trường --</option>");
            $('#sltYear').html("<option value=''>-- Chưa có học kỳ --</option>");
        }

        if (callback != null) { callback(dataget); }
    }, function (dataget) {
        console.log("loadComboboxHocky styleProfile: " + dataget);
    }, "", "", "");
};

var _id = 0;
var _idProfile = 0;
function openPopupDuyetLapCV(id, number, name, year, note) {
    $('#id_profile').val(id);
    $('#txtGhiChuTHCD').val(note);
    $('#years_xetduyet').val(year);
    $('#checkedAllChedo').prop('checked', false);
    $('#txtProfileName').html(name);
    var html_show = "";
    html_show += "<tr><td class='text-center' style='vertical-align:middle;'>1</td>";
    if (parseInt(number) == 1) {
        html_show += "<td class='text-center'><input type='checkbox' onclick='onclick_checksubject(this)' id='CheckProfile' name='unchoose' value='1' checked='checked'></td>";
    } else if (parseInt(number) == 2) {
        html_show += "<td class='text-center'><input type='checkbox' onclick='onclick_checksubject(this)' id='CheckProfile' name='unchoose' value='1' disabled='disabled'></td>";
    } else {
        html_show += "<td class='text-center'><input type='checkbox' onclick='onclick_checksubject(this)' id='CheckProfile' name='unchoose' value='1'></td>";
    }
    html_show += "<td style='vertical-align:middle;'>Duyệt hoặc điều chỉnh học sinh</td>";
    html_show += "<td style='vertical-align:middle;'>Thay đổi chi phí (tăng,giảm)</td></tr>";
    html_show += "<tr><td class='text-center' style='vertical-align:middle;'>2</td>";
    if (parseInt(number) == 2) {
        html_show += "<td class='text-center'><input type='checkbox' onclick='onclick_unchecksubject(this)' id='UnCheckProfile' name='unchoose' value='2' checked='checked'></td>";
    } else if (parseInt(number) == 1) {
        html_show += "<td class='text-center'><input type='checkbox' onclick='onclick_unchecksubject(this)' id='UnCheckProfile' name='unchoose' value='2' disabled='disabled'></td>";
    } else {
        html_show += "<td class='text-center'><input type='checkbox' onclick='onclick_unchecksubject(this)' id='UnCheckProfile' name='unchoose' value='2'></td>";
    }
    html_show += "<td style='vertical-align:middle;'>Điều chỉnh học sinh</td>";
    html_show += "<td style='vertical-align:middle;'>Cắt giảm học sinh</td></tr>";
    // $('#txtGhiChuTHCD').val(data['CHEDO'][0]['GHICHU']);        
    $('#dataDanhsachCheDo').html(html_show);
    $("#myModalApproved").modal("show");

}
function openPopupDuyetChedo(id, idTHCD, number, nam, name) {
    _id = idTHCD;
    _idProfile = id;

    id = id + '-' + number + '-' + _year;

    $('#txtGhiChuTHCD').val('');
    $('#checkedAllChedo').prop('checked', false);
    GetFromServer('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/getProfileSubById/' + id,
        function (data) {
            var html_show = "";
            var groupId = 0;
            if (data !== null && data !== "") {
                for (var j = 0; j < data['SUBJECT'].length; j++) {
                    html_show += "<tr>";
                    html_show += "<td class='text-center'>" + (j + 1 + (GET_START_RECORD_NGHILC() * 10)) + "</td>";
                    html_show += "<td class='text-center'>";
                    if (parseInt(data['SUBJECT'][j].status) > 0) {
                        html_show += "<input type='checkbox' class='chilCheck' name='choose' onclick='onclick_checksubject(this)' value='" + data['SUBJECT'][j].subject_history_group_id + "' checked='checked'>";
                    } else {
                        html_show += "<input type='checkbox' class='chilCheck' name='choose'  value='" + data['SUBJECT'][j].subject_history_group_id + "'>";
                    }

                    html_show += "</td>";

                    html_show += "<td>" + data['SUBJECT'][j].group_name + "</td>";
                    html_show += "<td>" + data['SUBJECT'][j].subject_name + "</td>";
                    html_show += "</tr>";

                }
                $('#txtGhiChuTHCD').val(data['GHICHU']);
            }
            $('#txtProfileName').html(name);

            $('#dataDanhsachCheDo').html(html_show);
            $("#myModalApproved").modal("show");
        }, function (data) {
            console.log("openPopupDuyetChedo");
            console.log(data);
        }, "btnApprovedTHCD", "loading", "");
}
// kiểm tra chế đội đối tượng xử lý của trường
onclick_checksubject = function (choose) {
    if ($(choose).is(':checked')) {
        $('#UnCheckProfile').attr('disabled', 'disabled');
        $('#UnCheckProfile').prop('checked', false);
    } else {
        $('#UnCheckProfile').removeAttr('disabled');
        $('#UnCheckProfile').prop('checked', false);
    }
}
onclick_unchecksubject = function (choose) {
    if ($(choose).is(':checked')) {
        $('#CheckProfile').attr('disabled', 'disabled');
        $('#CheckProfile').prop('checked', false);
    } else {
        $('#CheckProfile').removeAttr('disabled');
        $('#CheckProfile').prop('checked', false);
    }
}
//end
// Xử lý học sinh của trường
function approvedChedo(objData, note) {
    var o = {
        ID: _id,
        year: _year,
        IDPROFILE: _idProfile,
        note: note,
        objData: objData,
        schoolid: $('#drSchoolTHCD').val()
    };
    PostToServer('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/approved', o, function (data) {
        if (data['success'] != "" && data['success'] != undefined) {
            utility.message("Thông báo", data['success'], null, 3000);
            //GET_INITIAL_NGHILC();
            loaddataDanhSachTongHop($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
            $("#myModalApproved").modal("hide");
        }
        if (data['error'] != "" && data['error'] != undefined) {
            // GET_INITIAL_NGHILC();
            loaddataDanhSachTongHop($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
            utility.message("Thông báo", data['error'], null, 3000, 1);
        }
    }, function (data) {
        console.log("approvedChedo");
        console.log(data);
    }, "", "loading", "");
}
function xetduyethocsinh(_id, _type, _year, _note) {
    var o = {
        id: _id,
        trangthai: _type,
        namhoc: _year,
        note: _note,
    };
    PostToServer('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/xetduyetchedo', o, function (data) {
        if (data['success'] != "" && data['success'] != undefined) {
            utility.message("Thông báo", data['success'], null, 3000);
            GET_INITIAL_NGHILC();
            loaddataDanhSachLapCV($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
            $("#myModalApproved").modal("hide");
        }
        if (data['error'] != "" && data['error'] != undefined) {
            GET_INITIAL_NGHILC();
            loaddataDanhSachLapCV($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
            utility.message("Thông báo", data['error'], null, 3000, 1);
        }
    }, function (data) {
        console.log("approvedChedo");
        console.log(data);
    }, "btnApprovedLapCV", "loading", "");
}
// end
function approvedChedoNew(chedoID, profileID, type) {
    var hocky = _year.split('-');

    var o = {
        CHEDOID: chedoID,
        PROFILEID: profileID,
        YEAR: hocky[0],
        TYPE: type
    };
    // console.log(o);
    $.ajax({
        type: "POST",
        url: '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/approvedNew',
        data: JSON.stringify(o),
        dataType: 'json',
        contentType: 'application/json; charset=utf-8',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
        success: function (data) {
            console.log(data);
            if (data['success'] != "" && data['success'] != undefined) {
                utility.message("Thông báo", data['success'], null, 3000);
                // resetFormTHCD();
                GET_INITIAL_NGHILC();
                loaddataDanhSachTongHop($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
                $("#myModalApproved").modal("hide");
                closeLoading();
            }
            if (data['error'] != "" && data['error'] != undefined) {
                // resetFormTHCD();
                GET_INITIAL_NGHILC();
                loaddataDanhSachTongHop($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
                utility.message("Thông báo", data['error'], null, 3000, 2);
                closeLoading();
            }
        }, error: function (data) {
            closeLoading();
        }
    });
    // });
}

function approvedChedoPhongSo(arrSubitem, socongvan, profileID, note) {
    var o = {
        SOCONGVAN: socongvan,
        PROFILEID: profileID,
        NOTE: note,
        ARRSUBJECTID: arrSubitem
    };
    PostToServer('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/approvedPhongSo', o, function (data) {
        if (data['success'] != "" && data['success'] != undefined) {
            utility.message("Thông báo", data['success'], null, 3000);
            // GET_INITIAL_NGHILC();
            loadDanhSachHSDaLap($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfilePhongSo').val());
            $("#myModalApproved").modal("hide");
        }
        if (data['error'] != "" && data['error'] != undefined) {
            // GET_INITIAL_NGHILC();
            loadDanhSachHSDaLap($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfilePhongSo').val());
            utility.message("Thông báo", data['error'], null, 3000, 2);
        }
    }, function (data) {
        console.log("approvedChedoPhongSo");
        console.log(data);
    }, "btnApprovedCheDoPhongSo", "loading", "")

}

function revertedChedoPhongSo(arrSubitem, socongvan, profileID, note) {
    var o = {
        SOCONGVAN: socongvan,
        PROFILEID: profileID,
        NOTE: note,
        ARRSUBJECTID: arrSubitem
    };
    PostToServer('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/revertedPhongSo', o, function (data) {
        if (data['success'] != "" && data['success'] != undefined) {
            utility.message("Thông báo", data['success'], null, 3000);
            // GET_INITIAL_NGHILC();
            loadDanhSachHSDaLap($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfilePhongSo').val());
            $("#myModalRevert").modal("hide");
        }
        if (data['error'] != "" && data['error'] != undefined) {
            // resetFormTHCD();
            // GET_INITIAL_NGHILC();
            loadDanhSachHSDaLap($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfilePhongSo').val());
            utility.message("Thông báo", data['error'], null, 3000, 2);
        }
    }, function (data) {
        console.log("revertedChedoPhongSo");
        console.log(data);
    }, "", "loading", "")
}

function reloadChedoPhongSo(arrSubitem, socongvan, profileID) {
    var o = {
        SOCONGVAN: socongvan,
        PROFILEID: profileID,
        ARRSUBJECTID: arrSubitem
    };
    // console.log(o);
    $.ajax({
        type: "POST",
        url: '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/reloadPhongSo',
        data: JSON.stringify(o),
        dataType: 'json',
        contentType: 'application/json; charset=utf-8',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
        success: function (data) {
            // console.log(data);
            if (data['success'] != "" && data['success'] != undefined) {
                utility.message("Thông báo", data['success'], null, 3000);
                // resetFormTHCD();
                GET_INITIAL_NGHILC();
                loadDanhSachHSDaLap($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfilePhongSo').val());
                $("#myModalRevert").modal("hide");
                closeLoading();
            }
            if (data['error'] != "" && data['error'] != undefined) {
                // resetFormTHCD();
                GET_INITIAL_NGHILC();
                loadDanhSachHSDaLap($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfilePhongSo').val());
                utility.message("Thông báo", data['error'], null, 3000, 2);
                closeLoading();
            }
        }, error: function (data) {
            closeLoading();
        }
    });
    // });
}

function revertapprovedChedo(id) {
    var strData = id + '-' + _year;
    // console.log(id);
    utility.confirm("Hủy duyệt cấp kinh phí?", "Bạn có chắc chắn muốn hủy duyệt?", function () {
        $.ajax({
            type: "get",
            url: '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/revertApproved/' + strData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function (data) {
                // console.log(data);
                if (data['success'] != "" && data['success'] != undefined) {
                    utility.message("Thông báo", data['success'], null, 3000);
                    // resetFormTHCD();
                    GET_INITIAL_NGHILC();
                    loaddataDanhSachTongHop($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
                    $("#myModalLapDanhSachTHCD").modal("hide");
                    closeLoading();
                }
                if (data['error'] != "" && data['error'] != undefined) {
                    // resetFormTHCD();
                    GET_INITIAL_NGHILC();
                    loaddataDanhSachTongHop($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
                    utility.message("Thông báo", data['error'], null, 3000, 1);
                    closeLoading();
                }
            }, error: function (data) {
                closeLoading();
            }
        });
    });
}

function getProfileSubById(id, number, _year = 0, name = '') {

    id = id + '-' + number + '-' + _year;
    GetFromServer('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/getProfileSubById/' + id, function (data) {
        $('#lblProfileName').html(name);
        var html_show = "";

        var groupId = 0;
        if (data['SUBJECT'] !== null && data['SUBJECT'] !== "" && data['SUBJECT'].length > 0) {
            var html_show = "";
            var stt = 0;
            for (var i = 0; i < data['SUBJECT'].length; i++) {
                if (parseInt(data['SUBJECT'][i]['status']) > 0) {
                    stt++;
                    html_show += "<tr><td class='text-center' style='vertical-align:middle;'>" + (stt) + " </td>";
                    html_show += "<td style='vertical-align:middle;'>" + data['SUBJECT'][i]['group_name'] + " </td>";
                    html_show += "<td style='vertical-align:middle;'>" + data['SUBJECT'][i]['subject_name'] + " </td></tr>";
                }

            }

        }
        $('#viewDanhsachCheDo').html(html_show);
        $("#myModalTHCD").modal("show");
    }, function (data) {
        console.log('getProfileSubById: ');
        console.log(data);
    }, "", "loading", "");
}

function openPopupLapTHCD() {
    var msg_warning = "";

    $('#sltTypeCV').selectpicker('val', 1);
    $('#sltKhoiDt').selectpicker('val', $('#sltKhoiLop').val());
    $('#sltCapNhan').selectpicker('val', 2);
    $('#sltChedo').selectpicker('deselectAll');
    $('.selectpicker').selectpicker('refresh');
    msg_warning = validateTHCDTruong();

    if (msg_warning !== null && msg_warning !== "") {
        utility.messagehide("messageValidate", msg_warning, 1, 5000);
        return;
    }

    $('#txtTenCongVan').val("");
    $('#txtSoCongVan').val("");
    $('#txtGhiChuTHCD').val("");
    $('#titleCVTruong').html($('#sltYear option:selected').text());

    $("#myModalLapDanhSachTHCD").modal("show");
}

function lapdanhsachDanhSachTongHop(objData) {
    var schools_id = $('#drSchoolTHCD').val();
    var year = $('#sltYear').val();
    PostToServerFormData('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/lapdanhsach', objData,
        function (data) {
            if (data['success'] != "" && data['success'] != undefined) {
                utility.message("Thông báo", data['success'], null, 3000);
                GET_INITIAL_NGHILC();
                loaddataDanhSachLapCV($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
                $("#myModalLapDanhSachTHCD").modal("hide");
            }
            if (data['error'] != "" && data['error'] != undefined) {
                GET_INITIAL_NGHILC();
                loaddataDanhSachLapCV($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
                utility.message("Thông báo", data['error'], null, 3000, 1);
            }
        }, function (data) {
            console.log("lapdanhsachDanhSachTongHop");
            console.log(data);
        }, "btnInsertTHCD", "loading", "");
};

function lapdanhsachDanhSachTongHop_PheDuyet(objData) {

    var schools_id = $('#drSchoolTHCD').val();
    var year = $('#sltYear').val();

    // console.log(objData);


    $.ajax({
        type: "POST",
        url: '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/lapdanhsach_PD',
        data: objData,
        contentType: false,
        cache: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
        success: function (data) {
            console.log(data);
            if (data['success'] != "" && data['success'] != undefined) {
                utility.message("Thông báo", data['success'], null, 3000);
                // resetFormTHCD();
                GET_INITIAL_NGHILC();
                loadDanhSachChoPheDuyet($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
                $("#myModalLapDanhSachTHCD").modal("hide");
                $('#btnInsertTHCD_PheDuyet').button('reset');
            }
            if (data['error'] != "" && data['error'] != undefined) {
                // resetFormTHCD();
                GET_INITIAL_NGHILC();
                loadDanhSachChoPheDuyet($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
                utility.message("Thông báo", data['error'], null, 3000, 1);
                $('#btnInsertTHCD_PheDuyet').button('reset');
            }
        }, error: function (data) {
            $('#btnInsertTHCD_PheDuyet').button('reset');
        }
    });
};

function lapdanhsachDanhSachTongHop_ThamDinh(objData) {

    var schools_id = $('#drSchoolTHCD').val();
    var year = $('#sltYear').val();

    // console.log(objData);


    $.ajax({
        type: "POST",
        url: '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/lapdanhsach_TD',
        data: objData,
        contentType: false,
        cache: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
        success: function (data) {
            console.log(data);
            if (data['success'] != "" && data['success'] != undefined) {
                utility.message("Thông báo", data['success'], null, 3000);
                // resetFormTHCD();
                GET_INITIAL_NGHILC();
                loadlistApprovedPheDuyet($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
                $("#myModalLapDanhSachTHCD").modal("hide");
                $('#btnInsertTHCD_ThamDinh').button('reset');
            }
            if (data['error'] != "" && data['error'] != undefined) {
                // resetFormTHCD();
                GET_INITIAL_NGHILC();
                loadlistApprovedPheDuyet($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
                utility.message("Thông báo", data['error'], null, 3000, 1);
                $('#btnInsertTHCD_ThamDinh').button('reset');
            }
        }, error: function (data) {
            $('#btnInsertTHCD_ThamDinh').button('reset');
        }
    });
};

function validateTHCDTruong() {
    var messageValidate = "";
    var schools_id = $('#drSchoolTHCD').val();
    var year = $('#sltYear').val();

    if (schools_id == null || schools_id == "" || schools_id == 0) {
        messageValidate = "Vui lòng chọn trường!";
        return messageValidate;
    }
    if (year == null || year == "" || year == 0) {
        messageValidate = "Vui lòng chọn năm học!";
        return messageValidate;
    }

    return messageValidate;
}

function validateTHCD() {
    var messageValidate = "";
    var schools_id = $('#drSchoolTHCD').val();
    // var socongvan = $('#sltCongvan').val();

    if (schools_id == null || schools_id == "" || schools_id == 0) {
        messageValidate = "Vui lòng chọn trường!";
        return messageValidate;
    }

    return messageValidate;
}

function validateLapDenghi() {
    var messageValidate = "";
    var schools_id = $('#drSchoolTHCD').val();
    var year = $('#sltYear').val();

    if (schools_id == null || schools_id == "" || schools_id == 0) {
        messageValidate = "Vui lòng chọn trường!";
        return messageValidate;
    }
    if (year == null || year == "") {
        messageValidate = "Vui lòng chọn năm học!";
        return messageValidate;
    }

    return messageValidate;
}



function validatePopupTongHopCheDo() {
    var messageValidate = "";
    // var unit = $('#sltKhoiDt').val();
    var capnhan = $('#sltCapNhan').val();
    var chedo = $('#sltChedo').val();

    // if (reportName.trim() == "") {
    //     messageValidate = "Vui lòng nhập tên danh sách!";
    //     $('#txtNameDSTHCD').focus(); 
    //     return messageValidate;
    // }else if (reportName.length > 200) {
    //     messageValidate = "Tên danh sách không được vượt quá 200 ký tự!";
    //     $('#txtNameDSTHCD').focus();
    //     $('#txtNameDSTHCD').val("");
    //     return messageValidate;
    // }
    // else{
    //     $('#txtNameDSTHCD').focusout();
    //     var specialChars = "#/|\\";

    //     for (var i = 0; i < reportName.length; i++) {
    //         if (specialChars.indexOf(reportName.charAt(i)) != -1) {
    //             messageValidate = "Tên danh sách không được chứa ký tự #, /, |, \\!";
    //             $('#txtNameDSTHCD').focus();
    //             $('#txtNameDSTHCD').val("");
    //             return messageValidate;
    //         }
    //     }
    // }

    // if (unit == null || unit == "") {
    //     messageValidate = "Vui lòng chọn khối!";
    //     return messageValidate;
    // }

    if (capnhan == null || capnhan == "") {
        messageValidate = "Vui lòng chọn cấp nhận!";
        return messageValidate;
    }

    if (chedo == null || chedo == "") {
        messageValidate = "Vui lòng chọn loại chế độ!";
        return messageValidate;
    }

    // if (tennguoilap.trim() == "") {
    //     messageValidate = "Vui lòng nhập tên người lập!";
    //     $('#txtNguoiLapTHCD').focus(); 
    //     return messageValidate;
    // }else if (tennguoilap.length > 200) {
    //     messageValidate = "Tên người lập không được vượt quá 200 ký tự!";
    //     $('#txtNguoiLapTHCD').focus();
    //     $('#txtNguoiLapTHCD').val("");
    //     return messageValidate;
    // }
    // else{
    //     $('#txtNguoiLapTHCD').focusout();
    //     var specialChars = "#/|\\";

    //     for (var i = 0; i < tennguoilap.length; i++) {
    //         if (specialChars.indexOf(tennguoilap.charAt(i)) != -1) {
    //             messageValidate = "Tên người lập không được chứa ký tự #, /, |, \\!";
    //             $('#txtNguoiLapTHCD').focus();
    //             $('#txtNguoiLapTHCD').val("");
    //             return messageValidate;
    //         }
    //     }
    // }

    // if (tennguoiky.trim() == "") {
    //     messageValidate = "Vui lòng nhập tên người ký!";
    //     $('#txtNguoiKyTHCD').focus(); 
    //     return messageValidate;
    // }else if (tennguoiky.length > 200) {
    //     messageValidate = "Tên người ký không được vượt quá 200 ký tự!";
    //     $('#txtNguoiKyTHCD').focus();
    //     $('#txtNguoiKyTHCD').val("");
    //     return messageValidate;
    // }
    // else{
    //     $('#txtNguoiKyTHCD').focusout();
    //     var specialChars = "#/|\\";

    //     for (var i = 0; i < tennguoiky.length; i++) {
    //         if (specialChars.indexOf(tennguoiky.charAt(i)) != -1) {
    //             messageValidate = "Tên người ký không được chứa ký tự #, /, |, \\!";
    //             $('#txtNguoiKyTHCD').focus();
    //             $('#txtNguoiKyTHCD').val("");
    //             return messageValidate;
    //         }
    //     }
    // }

    return messageValidate;
}

function resetFormTHCD() {
    $('#formtonghopchedo')[0].reset();
    $('#dataProfile').html("");
}

function loadMoneybySubject(o) {
    PostToServer('/ho-so/hoc-sinh/loadMoneybySub', o, function (data) {
        var html_show = "";
        var total = 0;
        if (data.length > 0) {
            $('#tbMoney').removeAttr('hidden');
            for (var i = 0; i < data.length; i++) {

                html_show += "<tr>";
                html_show += "<td class='text-center'>" + (i + 1 + (GET_START_RECORD_NGHILC() * 10)) + "</td>";
                html_show += "<td>" + ConvertString(data[i]['group_name']) + "</td>";
                html_show += "<td class='text-center'>" + formatter(data[i]['money']) + "</td>";
                html_show += "</tr>";

                total = total + parseInt(data[i]['money']);
            }
            html_show += "<tr>";
            html_show += "<td class='text-center'></td>";
            html_show += "<td class='text-center'>Tổng</td>";
            html_show += "<td class='text-center'>" + formatter(total) + "</td>";
            html_show += "</tr>";
        }

        $('#tbMoneyContent').html(html_show);
    }, function (data) {
        console.log("loadMoneybySubject");
        console.log(data);
    }, "", "", "");
}

function loaddataBaocaoTongHop(row) {

    var msg_warning = "";

    msg_warning = validateTHCD();

    // alert(msg_warning);

    if (msg_warning !== null && msg_warning !== "") {
        utility.messagehide("messageValidate", msg_warning, 1, 5000);
        return;
    }
    GET_INITIAL_NGHILC();
    var schools_id = $('#drSchoolTHCD').val();
    var year = $('#sltYear').val();


    var html_show = "";
    var o = {
        // start: (GET_START_RECORD_NGHILC()),
        // limit: row,
        SCHOOLID: schools_id,
        YEAR: year
    };
    $.ajax({
        type: "POST",
        url: '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/loadDanhsachbaocao',
        data: JSON.stringify(o),
        // dataType: 'json',
        contentType: 'application/json; charset=utf-8',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
        success: function (datas) {
            // console.log(datas);

            // SETUP_PAGING_NGHILC(datas, function () {
            //     loaddataBaocaoTongHop(row);
            // });

            $('#contentPopupModalDanhsach').html("");

            if (datas.length > 0) {
                for (var i = 0; i < datas.length; i++) {

                    html_show += "<tr><td class='text-center'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                    if (datas[i].report === "MGHP") {
                        html_show += "<td><a href='javascript:;' onclick='export_file(" + datas[i].report_id + ", 1);'>" + ConvertString(datas[i].report_name) + "</a></td>";
                        html_show += "<td class=''>Hỗ trợ miễn giảm học phí</td>";
                    }
                    else if (datas[i].report === "CPHT") {
                        html_show += "<td><a href='javascript:;' onclick='export_file(" + datas[i].report_id + ", 2);'>" + ConvertString(datas[i].report_name) + "</a></td>";
                        html_show += "<td class=''>Hỗ trợ chi phí học tập</td>";
                    }
                    else if (datas[i].report === "HTAT") {
                        html_show += "<td><a href='javascript:;' onclick='export_file(" + datas[i].report_id + ", 3);'>" + ConvertString(datas[i].report_name) + "</a></td>";
                        html_show += "<td class=''>Hỗ trợ ăn trưa cho trẻ em mẫu giáo</td>";
                    }
                    else if (datas[i].report === "HTBT") {
                        html_show += "<td><a href='javascript:;' onclick='export_file(" + datas[i].report_id + ", 4);'>" + ConvertString(datas[i].report_name) + "</a></td>";
                        html_show += "<td class=''>Hỗ trợ học sinh bán trú</td>";
                    }
                    else if (datas[i].report === "HSDTTS") {
                        html_show += "<td><a href='javascript:;' onclick='export_file(" + datas[i].report_id + ", 5);'>" + ConvertString(datas[i].report_name) + "</a></td>";
                        html_show += "<td class=''>Hỗ trợ học sinh dân tộc thiểu số tại huyện Mù Cang Chải và Trạm Tấu</td>";
                    }
                    else if (datas[i].report === "HSKT") {
                        html_show += "<td><a href='javascript:;' onclick='export_file(" + datas[i].report_id + ", 6);'>" + ConvertString(datas[i].report_name) + "</a></td>";
                        html_show += "<td class=''>Hỗ trợ học sinh khuyết tật</td>";
                    }
                    else if (datas[i].report === "NGNA") {
                        html_show += "<td><a href='javascript:;' onclick='export_file(" + datas[i].report_id + ", 8);'>" + ConvertString(datas[i].report_name) + "</a></td>";
                        html_show += "<td class=''>Hỗ trợ người nấu ăn</td>";
                    }
                    else if (datas[i].report === "HTATHS") {
                        html_show += "<td><a href='javascript:;' onclick='export_file(" + datas[i].report_id + ", 9);'>" + ConvertString(datas[i].report_name) + "</a></td>";
                        html_show += "<td class=''>Hỗ trợ ăn trưa cho học sinh</td>";
                    }
                    else if (datas[i].report === "HBHSDTNT") {
                        html_show += "<td><a href='javascript:;' onclick='export_file(" + datas[i].report_id + ", 10);'>" + ConvertString(datas[i].report_name) + "</a></td>";
                        html_show += "<td class=''>Hỗ trợ học bổng cho học sinh dân tộc nội trú</td>";
                    }

                    // html_show += "<td>"+ConvertString(datas[i].report + '-' + datas[i].report_name)+"</td>";
                    if (parseInt(datas[i].report_status) === 0) {
                        html_show += "<td class='text-center'>Chưa gửi</td>";
                    }
                    else if (parseInt(datas[i].report_status) === 1) {
                        html_show += "<td class='text-center'>Đã gửi</td>";
                    }
                    else if (parseInt(datas[i].report_status) === 2) {
                        html_show += "<td class='text-center'>Trả lại</td>";
                    }
                    else if (parseInt(datas[i].report_status) === 3) {
                        html_show += "<td class='text-center'>Đã duyệt</td>";
                    }
                    else {
                        html_show += "<td class='text-center'>---</td>";
                    }
                    html_show += "<td class='text-center'>" + formatDates(datas[i].report_date) + "</td>";
                    html_show += "<td class=''>" + ConvertString(datas[i].first_name + ' ' + datas[i].last_name) + "</td>";
                    html_show += "</tr>";
                }
            }
            else {
                html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
            }
            $('#contentPopupModalDanhsach').html(html_show);

            $("#modalDanhsachBaocao").modal("show");
        }, error: function (datas) {

        }
    });
};
function checkedAllCreateDoc(level = 0) {
    if ($('#drSchoolTHCD').val() == "") {
        utility.messagehide("messageValidate", "Xin mời chọn trường", 1, 5000);
        return;
    }
    if ($('#sltYear').val() == "") {
        utility.messagehide("messageValidate", "Xin mời chọn năm lập công văn", 1, 5000);
        return;
    }

    var o = {
        SCHOOLID: $('#drSchoolTHCD').val(),
        YEAR: $('#sltYear').val(),
        level: level
    };
    PostToServer('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/approvedAllCreate', o, function (data) {
        if (data['success'] != "" && data['success'] != undefined) {
            utility.message("Thông báo", data['success'], null, 3000);
            GET_INITIAL_NGHILC();
            loaddataDanhSachLapCV($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val());
        }
        if (data['error'] != "" && data['error'] != undefined) {
            GET_INITIAL_NGHILC();
            loaddataDanhSachLapCV($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val());
        }
    }, function (data) {
        console.log("approvedAll");
        console.log(data);
    }, "", "loading", "");
};
function approvedAll(level = 0) {

    var msg_warning = "";

    msg_warning = validateTHCDTruong();

    // alert(msg_warning);

    if (msg_warning !== null && msg_warning !== "") {
        utility.messagehide("messageValidate", msg_warning, 1, 5000);
        return;
    }

    var schools_id = $('#drSchoolTHCD').val();
    var year = $('#sltYear').val();
    var classes = $('#sltYear').val();

    var ky = year.split("-");

    var o = {
        LEVEL: level,
        SCHOOLID: schools_id,
        CLASS_TYPE: classes,
        YEAR: ky[1],
        HOCKY: ky[0]
    };
    PostToServer('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/approvedAll', o, function (data) {
        if (data['success'] != "" && data['success'] != undefined) {
            utility.message("Thông báo", data['success'], null, 3000);
            GET_INITIAL_NGHILC();
            if (level == 1) {
                loaddataDanhSachTongHop($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
            }
            if (level == 2) {
                loadDanhSachChoPheDuyet($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
            }
            if (level == 3) {
                loadlistApprovedPheDuyet($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
            }

        }
        if (data['error'] != "" && data['error'] != undefined) {
            GET_INITIAL_NGHILC();
            if (level == 1) {
                loaddataDanhSachTongHop($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
            }
            if (level == 2) {
                loadDanhSachChoPheDuyet($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
            }
            if (level == 3) {
                loadlistApprovedPheDuyet($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
            }

            utility.message("Thông báo", data['error'], null, 3000, 1);
        }
    }, function (data) {
        console.log("approvedAll");
        console.log(data);
    }, "", "", "");
};

function approvedUnAll(level = 0) {

    var msg_warning = "";

    msg_warning = validateTHCDTruong();

    // alert(msg_warning);

    if (msg_warning !== null && msg_warning !== "") {
        utility.messagehide("messageValidate", msg_warning, 1, 5000);
        return;
    }

    var schools_id = $('#drSchoolTHCD').val();
    var year = $('#sltYear').val();

    var ky = year.split("-");

    var o = {
        LEVEL: level,
        SCHOOLID: schools_id,
        YEAR: ky[1],
        HOCKY: ky[0]
    };
    PostToServer('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/unApprovedAll', o, function (data) {
        // console.log(data);
        if (data['success'] != "" && data['success'] != undefined) {
            utility.message("Thông báo", data['success'], null, 3000);
            GET_INITIAL_NGHILC();
            if (level == 1) {
                loaddataDanhSachTongHop($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
            }
            if (level == 2) {
                loadDanhSachChoPheDuyet($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
            }
            if (level == 3) {
                loadlistApprovedPheDuyet($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
            }

            closeLoading();
        }
        if (data['error'] != "" && data['error'] != undefined) {
            GET_INITIAL_NGHILC();
            if (level == 1) {
                loaddataDanhSachTongHop($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
            }
            if (level == 2) {
                loadDanhSachChoPheDuyet($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
            }
            if (level == 3) {
                loadlistApprovedPheDuyet($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
            }

            utility.message("Thông báo", data['error'], null, 3000, 1);
            closeLoading();
        }
    }, function (data) {
        console.log("approvedUnAll");
        console.log(data);
    }, "", "", "");
};


function export_file(id, number) {
    var url_export = '';
    if (number == 1) {
        url_export = '/ho-so/lap-danh-sach/mien-giam-hoc-phi/downloadfileExport/';
    }
    if (number == 2) {
        url_export = '/ho-so/lap-danh-sach/chi-phi-hoc-tap/downloadfileExport/';
    }
    if (number == 3) {
        url_export = '/ho-so/lap-danh-sach/ho-tro-an-trua-tre-em/downloadfileExport/';
    }
    if (number == 4) {
        url_export = '/ho-so/lap-danh-sach/hoc-sinh-ban-tru/downloadfileExport/';
    }
    if (number == 5) {
        url_export = '/ho-so/lap-danh-sach/hoc-sinh-dan-toc-thieu-so/downloadfileExport/';
    }
    if (number == 6) {
        url_export = '/ho-so/lap-danh-sach/hoc-sinh-khuyet-tat/downloadfileExport/';
    }
    if (number == 7) {
        url_export = '/ho-so/lap-danh-sach/chinh-sach-uu-dai/downloadfileExport/';
    }
    if (number == 8) {
        url_export = '/ho-so/lap-danh-sach/nguoi-nau-an/downloadfileExport/';
    }
    if (number == 9) {
        url_export = '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/downloadfileExportHTATHS/';
    }
    if (number == 10) {
        url_export = '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/downloadfileExportHBHSDTNT/';
    }
    // console.log(number + "-------------------");
    // console.log(url_export + "-------------------");

    window.open(url_export + id, '_blank');
}

//-----------------------------------------------------------------Phê duyệt---------------------------------------------------------------
function loadDanhSachChoPheDuyet(row, keySearch = "", status = "") {

    var msg_warning = "";
    if ($('#sltCongvan').val() == '') {
        GET_INITIAL_NGHILC();
        loadSoCongVanDanhSachChoPheDuyet(keySearch);
    } else {
        //  alert($('#school-per').val());
        var schools_id = $('#drSchoolTHCD').val();
        //if(parseInt($('#school-per').val()) == 0){
        msg_warning = validateTHCD();
        //}else{
        //  schools_id = 0;
        //}



        if (msg_warning !== null && msg_warning !== "") {
            utility.messagehide("messageValidate", msg_warning, 1, 5000);
            return;
        }


        var socongvan = $('#sltCongvan').val();


        var number = 3;

        var html_show = "";
        var o = {
            start: (GET_START_RECORD_NGHILC()),
            limit: row,
            SCHOOLID: schools_id,
            //  YEAR: year,
            SOCONGVAN: socongvan,
            KEY: keySearch,
            STATUS: status
        };
        PostToServer('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/loadDanhSachChoPheDuyet', o, function (datas) {
            SETUP_PAGING_NGHILC(datas, function () {
                loadDanhSachChoPheDuyet(row, keySearch, status);
            });

            $('#dataListApproved').html("");
            var dataget = datas.data;
            // console.log(dataget);

            if (dataget.length > 0) {
                $('#btnApprovedDanhSachDaDuyet').show();
                $('#btnRevertDanhSachDaDuyet').show();
                for (var i = 0; i < dataget.length; i++) {
                    var totalMoney = 0;
                    totalMoney = parseInt(dataget[i].MGHP) + parseInt(dataget[i].CPHT) + parseInt(dataget[i].HTAT) + parseInt(dataget[i].HTBT_TA) + parseInt(dataget[i].HTBT_TO) + parseInt(dataget[i].HTBT_VHTT) + parseInt(dataget[i].HTATHS) + parseInt(dataget[i].HSKT_HB) + parseInt(dataget[i].HSKT_DDHT) + parseInt(dataget[i].HBHSDTNT) + parseInt(dataget[i].HSDTTS);

                    html_show += "<tr><td style='vertical-align:middle' class='text-center'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                    html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='getProfileSubById(" + parseInt(dataget[i].profile_id) + ", " + number + ");'>" + dataget[i].profile_name + "</a></td>";
                    html_show += "<td style='vertical-align:middle' class='text-center'>" + formatDates(dataget[i].profile_birthday) + "</td>";
                    //html_show += "<td>"+dataget[i].schools_name+"</td>";
                    html_show += "<td style='vertical-align:middle' class='text-center'>" + dataget[i].class_name + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].MGHP) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].CPHT) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HTAT) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HTBT_TA) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HTBT_TO) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HTBT_VHTT) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HTATHS) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HSKT_HB) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HSKT_DDHT) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HBHSDTNT) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HSDTTS) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(totalMoney) + "</td>";
                    html_show += "<td class='text-center' style='vertical-align:middle'><button data='" + dataget[i].profile_id + "' onclick='openPopupPheDuyetChedoNew(" + dataget[i].profile_id + ", \"" + socongvan + "\", \"" + dataget[i].profile_name + "\")' class='btn btn-info btn-xs' id='editor_editss'><i class='glyphicon glyphicon-info-sign'></i> Thông tin </button></td>";
                    html_show += "</tr>";
                }
            }
            else {
                $('#btnApprovedDanhSachDaDuyet').hide();
                $('#btnRevertDanhSachDaDuyet').hide();
                html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
            }
            $('#dataListApproved').html(html_show);
        }, function (result) {
            console.log("loadlistApproved: " + result);
        }, "btnLoadDataPhong", "", "");
    }

};

function loadDanhSachDaPheDuyet(row, keySearch = "", status = "") {

    var msg_warning = "";
    if ($('#sltCongvan').val() == '') {
        GET_INITIAL_NGHILC();
        loadSoCongVanDanhSachChoPheDuyet(keySearch);
    } else {
        //  alert($('#school-per').val());
        var schools_id = $('#drSchoolTHCD').val();
        //if(parseInt($('#school-per').val()) == 0){
        msg_warning = validateTHCD();
        //}else{
        //  schools_id = 0;
        //}



        if (msg_warning !== null && msg_warning !== "") {
            utility.messagehide("messageValidate", msg_warning, 1, 5000);
            return;
        }


        var socongvan = $('#sltCongvan').val();


        var number = 3;

        var html_show = "";
        var o = {
            start: (GET_START_RECORD_NGHILC()),
            limit: row,
            SCHOOLID: schools_id,
            //  YEAR: year,
            SOCONGVAN: socongvan,
            KEY: keySearch,
            STATUS: status
        };
        PostToServer('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/loadDanhSachDaPheDuyet', o, function (datas) {
            SETUP_PAGING_NGHILC(datas, function () {
                loadDanhSachDaPheDuyet(row, keySearch, status);
            });

            $('#dataListApproved').html("");
            var dataget = datas.data;
            // console.log(dataget);

            if (dataget.length > 0) {
                $('#btnRevertDanhSachDaDuyet').show();
                for (var i = 0; i < dataget.length; i++) {
                    var totalMoney = 0;
                    totalMoney = parseInt(dataget[i].MGHP) + parseInt(dataget[i].CPHT) + parseInt(dataget[i].HTAT) + parseInt(dataget[i].HTBT_TA) + parseInt(dataget[i].HTBT_TO) + parseInt(dataget[i].HTBT_VHTT) + parseInt(dataget[i].HTATHS) + parseInt(dataget[i].HSKT_HB) + parseInt(dataget[i].HSKT_DDHT) + parseInt(dataget[i].HBHSDTNT) + parseInt(dataget[i].HSDTTS);

                    html_show += "<tr><td style='vertical-align:middle' class='text-center'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                    if (parseInt(dataget[i].his_trangthai_thuhoi) > 0) {
                        html_show += "<td style='vertical-align: middle; background: red;'><a href='javascript:;' onclick='openpopupthuhoihocsinh(" + parseInt(dataget[i].profile_id) + ", \"" + socongvan + "\", " + schools_id + ");'>" + dataget[i].profile_name + "</a></td>";
                    }
                    else {
                        html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick=''>" + dataget[i].profile_name + "</a></td>";
                    }
                    html_show += "<td style='vertical-align:middle' class='text-center'>" + formatDates(dataget[i].profile_birthday) + "</td>";
                    //html_show += "<td>"+dataget[i].schools_name+"</td>";
                    html_show += "<td style='vertical-align:middle' class='text-center'>" + dataget[i].class_name + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].MGHP) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].CPHT) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HTAT) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HTBT_TA) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HTBT_TO) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HTBT_VHTT) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HTATHS) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HSKT_HB) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HSKT_DDHT) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HBHSDTNT) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HSDTTS) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(totalMoney) + "</td>";
                    html_show += "<td class='text-center' style='vertical-align:middle'><button data='" + dataget[i].profile_id + "' onclick='openPopupPheDuyetChedoNew(" + dataget[i].profile_id + ", \"" + socongvan + "\", \"" + dataget[i].profile_name + "\", 2)' class='btn btn-info btn-xs' id='editor_editss'><i class='glyphicon glyphicon-info-sign'></i> Thông tin </button></td>";

                    html_show += "</tr>";
                }
            }
            else {
                $('#btnRevertDanhSachDaDuyet').hide();
                html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
            }
            $('#dataListApproved').html(html_show);
        }, function (result) {
            console.log("loadlistApproved: " + result);
        }, "btnLoadDataPhong", "", "");
    }

};

function loadDanhSachTraLai(row, keySearch = "", status = "") {

    var msg_warning = "";
    if ($('#sltCongvan').val() == '') {
        GET_INITIAL_NGHILC();
        loadSoCongVanDanhSachChoPheDuyet(keySearch);
    } else {
        //  alert($('#school-per').val());
        var schools_id = $('#drSchoolTHCD').val();
        //if(parseInt($('#school-per').val()) == 0){
        msg_warning = validateTHCD();
        //}else{
        //  schools_id = 0;
        //}



        if (msg_warning !== null && msg_warning !== "") {
            utility.messagehide("messageValidate", msg_warning, 1, 5000);
            return;
        }


        var socongvan = $('#sltCongvan').val();


        var number = 3;

        var html_show = "";
        var o = {
            start: (GET_START_RECORD_NGHILC()),
            limit: row,
            SCHOOLID: schools_id,
            //  YEAR: year,
            SOCONGVAN: socongvan,
            KEY: keySearch,
            STATUS: status
        };
        PostToServer('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/loadDanhSachTraLai', o, function (datas) {
            SETUP_PAGING_NGHILC(datas, function () {
                loadDanhSachTraLai(row, keySearch, status);
            });

            $('#dataListApproved').html("");
            var dataget = datas.data;
            // console.log(dataget);

            if (dataget.length > 0) {
                $('#btnApprovedDanhSachDaDuyet').show();
                for (var i = 0; i < dataget.length; i++) {
                    var totalMoney = 0;
                    totalMoney = parseInt(dataget[i].MGHP) + parseInt(dataget[i].CPHT) + parseInt(dataget[i].HTAT) + parseInt(dataget[i].HTBT_TA) + parseInt(dataget[i].HTBT_TO) + parseInt(dataget[i].HTBT_VHTT) + parseInt(dataget[i].HTATHS) + parseInt(dataget[i].HSKT_HB) + parseInt(dataget[i].HSKT_DDHT) + parseInt(dataget[i].HBHSDTNT) + parseInt(dataget[i].HSDTTS);

                    html_show += "<tr><td style='vertical-align:middle' class='text-center'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                    html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='getProfileSubById(" + parseInt(dataget[i].profile_id) + ", " + number + ");'>" + dataget[i].profile_name + "</a></td>";
                    html_show += "<td style='vertical-align:middle' class='text-center'>" + formatDates(dataget[i].profile_birthday) + "</td>";
                    //html_show += "<td>"+dataget[i].schools_name+"</td>";
                    html_show += "<td style='vertical-align:middle' class='text-center'>" + dataget[i].class_name + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].MGHP) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].CPHT) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HTAT) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HTBT_TA) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HTBT_TO) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HTBT_VHTT) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HTATHS) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HSKT_HB) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HSKT_DDHT) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HBHSDTNT) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HSDTTS) + "</td>";
                    html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(totalMoney) + "</td>";
                    html_show += "<td class='text-center' style='vertical-align:middle'><button data='" + dataget[i].profile_id + "' onclick='openPopupPheDuyetChedoNew(" + dataget[i].profile_id + ", \"" + socongvan + "\", \"" + dataget[i].profile_name + "\", 3)' class='btn btn-info btn-xs' id='editor_editss'><i class='glyphicon glyphicon-info-sign'></i> Thông tin </button></td>";

                    html_show += "</tr>";
                }
            }
            else {
                $('#btnApprovedDanhSachDaDuyet').hide();
                html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
            }
            $('#dataListApproved').html(html_show);
        }, function (result) {
            console.log("loadlistApproved: " + result);
        }, "btnLoadDataPhong", "", "");
    }

};

function loadDanhSachByCongVan(row, keySearch = "", typeDS = 0) {

    var socongvan = $('#sltCongvan').val();
    var type = typeDS;

    var html_show = "";
    var o = {
        start: (GET_START_RECORD_NGHILC()),
        limit: row,
        SCHOOLID: schools_id,
        SOCONGVAN: socongvan,
        KEY: keySearch,
        TYPE: typeDS
    };
    // console.log(o);
    PostToServer('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/loadDanhSach', o, function (datas) {
        SETUP_PAGING_NGHILC(datas, function () {
            loadDanhSachByCongVan(row, keySearch, typeDS);
        });
        // console.log(datas);
        $('#dataListApproved').html("");
        var dataget = datas.data;
        // console.log(dataget);
        //----------------------23/12/2017---------------------------------------------
        if (dataget.length > 0) {
            if (typeDS === 1 && parseInt(lvUser) === 1) {
                $('#btnApprovedDanhSachDuyet').hide();
                $('#btnRevertDanhSachDuyet').hide();
            }
            if (typeDS === 2 && parseInt(lvUser) === 2) {
                $('#btnApprovedDanhSachDuyet').show();
                $('#btnRevertDanhSachDuyet').show();
            }
            if (typeDS === 3 && parseInt(lvUser) === 2) {
                $('#btnApprovedDanhSachDuyet').hide();
                $('#btnRevertDanhSachDuyet').show();
            }
            if (typeDS === 4 && parseInt(lvUser) === 2) {
                $('#btnApprovedDanhSachDuyet').show();
                $('#btnRevertDanhSachDuyet').hide();
            }
            else if (typeDS === 5 && parseInt(lvUser) === 3) {
                $('#btnApprovedDanhSachDuyet').show();
                $('#btnRevertDanhSachDuyet').show();
            }
            else if (typeDS === 6 && parseInt(lvUser) === 3) {
                $('#btnApprovedDanhSachDuyet').hide();
                $('#btnRevertDanhSachDuyet').show();
            }
            else if (typeDS === 7 && parseInt(lvUser) === 3) {
                $('#btnApprovedDanhSachDuyet').show();
                $('#btnRevertDanhSachDuyet').hide();
            }
            else if (typeDS === 11 && parseInt(lvUser) === 3) {
                $('#btnApprovedDanhSachDuyet').hide();
                $('#btnRevertDanhSachDuyet').hide();
            }

            for (var i = 0; i < dataget.length; i++) {
                var totalMoney = 0;
                totalMoney = formatterNull(dataget[i].MGHP) + formatterNull(dataget[i].CPHT) + formatterNull(dataget[i].HTAT) + formatterNull(dataget[i].HTBT_TA) + formatterNull(dataget[i].HTBT_TO) + formatterNull(dataget[i].HTBT_VHTT) + formatterNull(dataget[i].HTATHS) + formatterNull(dataget[i].HSKT_HB) + formatterNull(dataget[i].HSKT_DDHT) + formatterNull(dataget[i].HBHSDTNT) + formatterNull(dataget[i].HSDTTS);

                html_show += "<tr><td style='vertical-align:middle' class='text-center'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                if (parseInt(dataget[i].his_trangthai_thuhoi_phongGD) > 0 || parseInt(dataget[i].his_trangthai_thuhoi_phongTC) > 0) {
                    html_show += "<td style='vertical-align: middle;'><a href='javascript:;' style='color:red' onclick='openpopupthuhoihocsinh(" + parseInt(dataget[i].profile_id) + ", \"" + socongvan + "\", " + schools_id + ");'><i style='color:#6d6de6' title='Thu hồi' class='fa fa-question-circle '></i> " + dataget[i].profile_name + "</a></td>";
                }
                else {
                    html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick=''>" + ConvertString(dataget[i].profile_name) + "</a></td>";
                }
                html_show += "<td style='vertical-align:middle' class='text-center'>" + formatDates(dataget[i].profile_birthday) + "</td>";
                //html_show += "<td>"+dataget[i].schools_name+"</td>";
                html_show += "<td style='vertical-align:middle' class='text-center'>" + ConvertString(dataget[i].class_name) + "</td>";
                html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].MGHP) + "</td>";
                html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].CPHT) + "</td>";
                html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HTAT) + "</td>";
                html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HTBT_TA) + "</td>";
                html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HTBT_TO) + "</td>";
                html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HTBT_VHTT) + "</td>";
                html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HTATHS) + "</td>";
                html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HSKT_HB) + "</td>";
                html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HSKT_DDHT) + "</td>";
                html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HBHSDTNT) + "</td>";
                html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(dataget[i].HSDTTS) + "</td>";
                html_show += "<td style='vertical-align:middle' class='text-right' >" + formatter(totalMoney) + "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'><button data='" + dataget[i].profile_id + "' onclick='openPopupPheDuyetChedoNew(" + dataget[i].profile_id + ", \"" + socongvan + "\", \"" + dataget[i].profile_name + "\", " + loaiDanhSach + ")' class='btn btn-info btn-xs' id='editor_editss'><i class='glyphicon glyphicon-info-sign'></i> Thông tin </button></td>";

                html_show += "</tr>";

                if (parseInt(lvUser) === 2 && (parseInt(dataget[i].phongTC_dapheduyet) > 0 || parseInt(dataget[i].phongTC_tralai) > 0)) {
                    $('#btnApprovedDanhSachDuyet').hide();
                    $('#btnRevertDanhSachDuyet').hide();
                }

                if (parseInt(lvUser) === 3 && (parseInt(dataget[i].So_dapheduyet) > 0 || parseInt(dataget[i].So_tralai) > 0)) {
                    $('#btnApprovedDanhSachDuyet').hide();
                    $('#btnRevertDanhSachDuyet').hide();
                }
            }

            if (typeDS === 8 && parseInt(lvUser) === 4) {
                $('#btnApprovedDanhSachDuyet').show();
                $('#btnRevertDanhSachDuyet').show();
            }
            else if (typeDS === 9 && parseInt(lvUser) === 4) {
                $('#btnApprovedDanhSachDuyet').hide();
                $('#btnRevertDanhSachDuyet').show();
            }
            else if (typeDS === 10 && parseInt(lvUser) === 4) {
                $('#btnApprovedDanhSachDuyet').show();
                $('#btnRevertDanhSachDuyet').hide();
            }
            else if (typeDS === 12 && parseInt(lvUser) === 4) {
                $('#btnApprovedDanhSachDuyet').hide();
                $('#btnRevertDanhSachDuyet').hide();
            }
        }
        else {

            html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
        }
        $('#dataListApproved').html(html_show);
    }, function (result) {
        console.log("loadlistApproved: " + result);
    }, "btnLoadDataPhong", "", "");
};

function openPopupPheDuyetChedoNew(id, socongvan, name, type) {
    // alert(loaiDanhSach);
    _idProfile = id;
    // id = id + '-' + socongvan + '-' + _year;

    var objJson = JSON.stringify({ PROFILEID: id, SOCONGVAN: socongvan });
    // console.log(objJson);
    $('#txtGhiChuTHCD').val('');
    $('#checkedAllChedo').prop('checked', false);

    $.ajax({
        type: "get",
        url: '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/getProfileSubjectByIdPhongSo/' + objJson,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
        success: function (data) {
            // console.log(data);

            // console.log(data['GROUP']);
            // console.log(data['SUBJECT']);
            // console.log(data['CHEDO']);

            var html_show = "";

            var groupId = 0;

            if (data !== null && data !== "") {
                // for (var i = 0; i < data['GROUP'].length; i++) {
                var stt = 0;
                if (data['SUBJECT'].length > 0) {
                    for (var i = 0; i < data['SUBJECT'].length; i++) {
                        var arrSub = [];
                        arrSub = data['SUBJECT'][i].subject_ds_group_sub_name.split(',');
                        for (var j = 0; j < arrSub.length; j++) {
                            var arrSubitem = [];
                            arrSubitem = arrSub[j].split('-');
                            if ((arrSubitem[0] !== null && arrSubitem[0] !== '' && arrSubitem[0] !== undefined)
                                && (arrSubitem[1] !== null && arrSubitem[1] !== '' && arrSubitem[1] !== undefined)) {
                                html_show += "<tr>";
                                html_show += "<td class='text-center'>" + (j + 1) + "</td>";
                                html_show += "<td>" + arrSubitem[0] + "</td>";
                                html_show += "<td>" + arrSubitem[1] + "</td>";
                                html_show += "</tr>";
                            }
                        }
                    }
                }
                //     for (var j = 0; j < data['SUBJECT'].length; j++) {
                //         // if (data['GROUP'][i].group_id == data['SUBJECT'][j].subject_history_group_id) {
                //             html_show += "<tr>";
                //           //  html_show += "<td class='text-center'>"+(j + 1 + (GET_START_RECORD_NGHILC() * 10))+"</td>";
                //            // html_show += "<td class='text-center'>";

                //             // console.log(data['CHEDO'][0]['TRANGTHAIHK1']);
                //             // console.log(data['CHEDO'][0]['TRANGTHAIHK2']);

                //             // if (data['CHEDO'][0]['TRANGTHAI_PHEDUYET'] == 1) {

                //                 if ((parseInt(data['SUBJECT'][j].subject_history_group_id) === 89 
                //                         || parseInt(data['SUBJECT'][j].subject_history_group_id) === 90
                //                         || parseInt(data['SUBJECT'][j].subject_history_group_id) === 91)
                //                     && ((parseInt(data['GROUP'][0].MGHP_nhucau) + parseInt(data['GROUP'][0].MGHP_dutoan)) > 0)) {
                //                     html_show += "<td class='text-center'>"+(stt+1)+"</td>";
                //                     // html_show += "<td class='text-center'><input type='checkbox' id='chilCheckPD' name='choose' value='"+data['SUBJECT'][j].subject_history_group_id+"' checked='checked'></td>";
                //                     html_show += "<td>"+data['SUBJECT'][j].group_name+"</td>";
                //                     html_show += "<td>"+data['SUBJECT'][j].subject_name+"</td>";
                //                 }
                //                 else if ((parseInt(data['SUBJECT'][j].subject_history_group_id) === 92)
                //                     && (parseInt(data['GROUP'][0].CPHT_nhucau) > 0 || parseInt(data['GROUP'][0].CPHT_dutoan) > 0)) {
                //                     html_show += "<td class='text-center'>"+(stt+1)+"</td>";
                //                     // html_show += "<td class='text-center'><input type='checkbox' id='chilCheckPD' name='choose' value='"+data['SUBJECT'][j].subject_history_group_id+"' checked='checked'></td>";
                //                     html_show += "<td>"+data['SUBJECT'][j].group_name+"</td>";
                //                     html_show += "<td>"+data['SUBJECT'][j].subject_name+"</td>";
                //                 }
                //                 else if ((parseInt(data['SUBJECT'][j].subject_history_group_id) === 93)
                //                     && (parseInt(data['GROUP'][0].HTAT_nhucau) > 0 || parseInt(data['GROUP'][0].HTAT_dutoan) > 0)) {
                //                     html_show += "<td class='text-center'>"+(stt+1)+"</td>";
                //                     // html_show += "<td class='text-center'><input type='checkbox' id='chilCheckPD' name='choose' value='"+data['SUBJECT'][j].subject_history_group_id+"' checked='checked'></td>";
                //                     html_show += "<td>"+data['SUBJECT'][j].group_name+"</td>";
                //                     html_show += "<td>"+data['SUBJECT'][j].subject_name+"</td>";
                //                 }
                //                 else if ((parseInt(data['SUBJECT'][j].subject_history_group_id) === 94)
                //                     && (parseInt(data['GROUP'][0].HTBT_nhucau_TA) > 0 || parseInt(data['GROUP'][0].HTBT_dutoan_TA) > 0)) {
                //                     html_show += "<td class='text-center'>"+(stt+1)+"</td>";
                //                     // html_show += "<td class='text-center'><input type='checkbox' id='chilCheckPD' name='choose' value='"+data['SUBJECT'][j].subject_history_group_id+"' checked='checked'></td>";
                //                     html_show += "<td>"+data['SUBJECT'][j].group_name+"</td>";
                //                     html_show += "<td>"+data['SUBJECT'][j].subject_name+"</td>";
                //                 }
                //                 else if ((parseInt(data['SUBJECT'][j].subject_history_group_id) === 98)
                //                     && (parseInt(data['GROUP'][0].HTBT_nhucau_TO) > 0 || parseInt(data['GROUP'][0].HTBT_dutoan_TO) > 0)) {
                //                     html_show += "<td class='text-center'>"+(stt+1)+"</td>";
                //                     // html_show += "<td class='text-center'><input type='checkbox' id='chilCheckPD' name='choose' value='"+data['SUBJECT'][j].subject_history_group_id+"' checked='checked'></td>";
                //                     html_show += "<td>"+data['SUBJECT'][j].group_name+"</td>";
                //                     html_show += "<td>"+data['SUBJECT'][j].subject_name+"</td>";
                //                 }
                //                 else if ((parseInt(data['SUBJECT'][j].subject_history_group_id) === 115)
                //                     && (parseInt(data['GROUP'][0].HTBT_nhucau_VHTT) > 0 || parseInt(data['GROUP'][0].HTBT_dutoan_VHTT) > 0)) {
                //                     html_show += "<td class='text-center'>"+(stt+1)+"</td>";
                //                     // html_show += "<td class='text-center'><input type='checkbox' id='chilCheckPD' name='choose' value='"+data['SUBJECT'][j].subject_history_group_id+"' checked='checked'></td>";
                //                     html_show += "<td>"+data['SUBJECT'][j].group_name+"</td>";
                //                     html_show += "<td>"+data['SUBJECT'][j].subject_name+"</td>";
                //                 }
                //                 else if ((parseInt(data['SUBJECT'][j].subject_history_group_id) === 95)
                //                     && (parseInt(data['GROUP'][0].HSKT_nhucau_HB) > 0 || parseInt(data['GROUP'][0].HSKT_dutoan_HB) > 0)) {
                //                     html_show += "<td class='text-center'>"+(stt+1)+"</td>";
                //                     // html_show += "<td class='text-center'><input type='checkbox' id='chilCheckPD' name='choose' value='"+data['SUBJECT'][j].subject_history_group_id+"' checked='checked'></td>";
                //                     html_show += "<td>"+data['SUBJECT'][j].group_name+"</td>";
                //                     html_show += "<td>"+data['SUBJECT'][j].subject_name+"</td>";
                //                 }
                //                 else if ((parseInt(data['SUBJECT'][j].subject_history_group_id) === 100)
                //                     && (parseInt(data['GROUP'][0].HSKT_nhucau_DDHT) > 0 || parseInt(data['GROUP'][0].HSKT_dutoan_DDHT) > 0)) {
                //                     html_show += "<td class='text-center'>"+(stt+1)+"</td>";
                //                     // html_show += "<td class='text-center'><input type='checkbox' id='chilCheckPD' name='choose' value='"+data['SUBJECT'][j].subject_history_group_id+"' checked='checked'></td>";
                //                     html_show += "<td>"+data['SUBJECT'][j].group_name+"</td>";
                //                     html_show += "<td>"+data['SUBJECT'][j].subject_name+"</td>";
                //                 }
                //                 else if ((parseInt(data['SUBJECT'][j].subject_history_group_id) === 99)
                //                     && (parseInt(data['GROUP'][0].HSDTTS_nhucau) > 0 || parseInt(data['GROUP'][0].HSDTTS_dutoan) > 0)) {
                //                     html_show += "<td class='text-center'>"+(stt+1)+"</td>";
                //                     // html_show += "<td class='text-center'><input type='checkbox' id='chilCheckPD' name='choose' value='"+data['SUBJECT'][j].subject_history_group_id+"' checked='checked'></td>";
                //                     html_show += "<td>"+data['SUBJECT'][j].group_name+"</td>";
                //                     html_show += "<td>"+data['SUBJECT'][j].subject_name+"</td>";
                //                 }
                //                 else if ((parseInt(data['SUBJECT'][j].subject_history_group_id) === 118)
                //                     && (parseInt(data['GROUP'][0].HTATHS_nhucau) > 0 || parseInt(data['GROUP'][0].HTATHS_dutoan) > 0)) {
                //                     html_show += "<td class='text-center'>"+(stt+1)+"</td>";
                //                     // html_show += "<td class='text-center'><input type='checkbox' id='chilCheckPD' name='choose' value='"+data['SUBJECT'][j].subject_history_group_id+"' checked='checked'></td>";
                //                     html_show += "<td>"+data['SUBJECT'][j].group_name+"</td>";
                //                     html_show += "<td>"+data['SUBJECT'][j].subject_name+"</td>";
                //                 }
                //                 else if ((parseInt(data['SUBJECT'][j].subject_history_group_id) === 119)
                //                     && ((parseInt(data['GROUP'][0].HBHSDTNT_nhucau) + parseInt(data['GROUP'][0].HBHSDTNT_dutoan)) > 0)) {
                //                     // console.log(parseInt(data['SUBJECT'][j].subject_history_group_id));
                //                     // console.log((parseInt(data['GROUP'][0].HBHSDTNT_nhucau) + parseInt(data['GROUP'][0].HBHSDTNT_dutoan)));
                //                     html_show += "<td class='text-center'>"+(stt+1)+"</td>";
                //                     // html_show += "<td class='text-center'><input type='checkbox' id='chilCheckPD' name='choose' value='"+data['SUBJECT'][j].subject_history_group_id+"' checked='checked'></td>";
                //                     html_show += "<td>"+data['SUBJECT'][j].group_name+"</td>";
                //                     html_show += "<td>"+data['SUBJECT'][j].subject_name+"</td>";
                //                 }
                //                     //else{
                //                     //    html_show += "<input type='checkbox' id='chilCheckPD' name='choose' value='"+data['SUBJECT'][j].subject_history_group_id+"'>";
                //                     //}
                //             // }
                //             // else{
                //             //     html_show += "<input type='checkbox' id='chilCheckPD' name='choose' value='"+data['SUBJECT'][j].subject_history_group_id+"'>";
                //             // }

                //             //html_show += "</td>";

                //             // html_show += "<td>"+data['SUBJECT'][j].group_name+"</td>";
                //             // html_show += "<td>"+data['SUBJECT'][j].subject_name+"</td>";
                //             html_show += "</tr>";
                //         // }
                //     }
                // // }

                if (parseInt(data['LEVELUSER']) === 1) {
                    $('#btnDuyetDSChoPD').hide();
                    $('#btnTraLaiDSChoPD').hide();

                    $('#btnTraLaiDSDaPD').hide();
                    $('#btnDuyetDSTuChoiPD').hide();

                    // $('#btnRevertDanhSachDaDuyet').hide();
                }
                else {
                    if (loaiDanhSach == 2 && parseInt(data['LEVELUSER']) === 2) {
                        $('#btnDuyetDSChoPD').show();
                        $('#btnTraLaiDSChoPD').show();
                    }
                    else if (loaiDanhSach == 5 && parseInt(data['LEVELUSER']) === 3) {
                        $('#btnDuyetDSChoPD').show();
                        $('#btnTraLaiDSChoPD').show();
                    }
                    else if (loaiDanhSach == 8 && parseInt(data['LEVELUSER']) === 4) {
                        $('#btnDuyetDSChoPD').show();
                        $('#btnTraLaiDSChoPD').show();
                    }
                    else if (loaiDanhSach == 3 && parseInt(data['LEVELUSER']) === 2) {
                        $('#btnDuyetDSChoPD').hide();
                        $('#btnTraLaiDSChoPD').show();
                    }
                    else if (loaiDanhSach == 6 && parseInt(data['LEVELUSER']) === 3) {
                        $('#btnDuyetDSChoPD').hide();
                        $('#btnTraLaiDSChoPD').show();
                    }
                    else if (loaiDanhSach == 9 && parseInt(data['LEVELUSER']) === 4) {
                        $('#btnDuyetDSChoPD').hide();
                        $('#btnTraLaiDSChoPD').show();
                    }
                    else if (loaiDanhSach == 4 && parseInt(data['LEVELUSER']) === 2) {
                        $('#btnDuyetDSChoPD').show();
                        $('#btnTraLaiDSChoPD').hide();
                    }
                    else if (loaiDanhSach == 7 && parseInt(data['LEVELUSER']) === 3) {
                        $('#btnDuyetDSChoPD').show();
                        $('#btnTraLaiDSChoPD').hide();
                    }
                    else if (loaiDanhSach == 10 && parseInt(data['LEVELUSER']) === 4) {
                        $('#btnDuyetDSChoPD').show();
                        $('#btnTraLaiDSChoPD').hide();
                    }
                    else {
                        $('#btnDuyetDSChoPD').hide();
                        $('#btnTraLaiDSChoPD').hide();
                    }
                }

                // if ((number > 0) || (number === 0 && data['LEVELUSER'] > 1)) {
                if (data['APPROVED'].length > 0) {
                    var show_html = '';
                    // if (data['LEVELUSER'] === 1) {
                    //     show_html += '<table class="table table-striped table-bordered table-hover dataTable no-footer" style="max-width: 40%;">';
                    //     show_html += '<thead>';
                    //     show_html += '<tr class="success">';
                    //     show_html += '<th class="text-center" style="vertical-align:middle" colspan="2">Trường</th>';
                    //     show_html += '</tr>';
                    //     show_html += '<tr class="success">';
                    //     show_html += '<th class="text-center" style="vertical-align:middle">Người duyệt</th>';
                    //     show_html += '<th class="text-center" style="vertical-align:middle">Ngày duyệt duyệt</th>';
                    //     show_html += '</tr>';
                    //     show_html += '</thead>';
                    //     show_html += '<tbody>';
                    //     for (var i = 0; i < data['APPROVED'].length; i++) {
                    //         show_html += '<tr>';
                    //         if (data['APPROVED'][i].truong_user_MGHP !== null && data['APPROVED'][i].truong_user_MGHP !== "") {
                    //             show_html += '<td>'+ConvertString(data['APPROVED'][i].truong_user_MGHP)+'</td>';
                    //         }
                    //         else if (data['APPROVED'][i].truong_user_CPHT !== null && data['APPROVED'][i].truong_user_CPHT !== "") {
                    //             show_html += '<td>'+ConvertString(data['APPROVED'][i].truong_user_CPHT)+'</td>';
                    //         }
                    //         else if (data['APPROVED'][i].truong_user_HTAT !== null && data['APPROVED'][i].truong_user_HTAT !== "") {
                    //             show_html += '<td>'+ConvertString(data['APPROVED'][i].truong_user_HTAT)+'</td>';
                    //         }
                    //         else if (data['APPROVED'][i].truong_user_HTBT !== null && data['APPROVED'][i].truong_user_HTBT !== "") {
                    //             show_html += '<td>'+ConvertString(data['APPROVED'][i].truong_user_HTBT)+'</td>';
                    //         }
                    //         else if (data['APPROVED'][i].truong_user_HSKT !== null && data['APPROVED'][i].truong_user_HSKT !== "") {
                    //             show_html += '<td>'+ConvertString(data['APPROVED'][i].truong_user_HSKT)+'</td>';
                    //         }
                    //         else if (data['APPROVED'][i].truong_user_HSDTTS !== null && data['APPROVED'][i].truong_user_HSDTTS !== "") {
                    //             show_html += '<td>'+ConvertString(data['APPROVED'][i].truong_user_HSDTTS)+'</td>';
                    //         }
                    //         else if (data['APPROVED'][i].truong_user_HTATHS !== null && data['APPROVED'][i].truong_user_HTATHS !== "") {
                    //             show_html += '<td>'+ConvertString(data['APPROVED'][i].truong_user_HTATHS)+'</td>';
                    //         }
                    //         else if (data['APPROVED'][i].truong_user_HBHSDTNT !== null && data['APPROVED'][i].truong_user_HBHSDTNT !== "") {
                    //             show_html += '<td>'+ConvertString(data['APPROVED'][i].truong_user_HBHSDTNT)+'</td>';
                    //         }
                    //         else {
                    //             show_html += '<td></td>';
                    //         }

                    //         if (data['APPROVED'][i].truong_update_date_MGHP !== null && data['APPROVED'][i].truong_update_date_MGHP !== "") {
                    //             show_html += '<td>'+formatDateTimes(data['APPROVED'][i].truong_update_date_MGHP)+'</td>';
                    //         }
                    //         else if (data['APPROVED'][i].truong_update_date_CPHT !== null && data['APPROVED'][i].truong_update_date_CPHT !== "") {
                    //             show_html += '<td>'+formatDateTimes(data['APPROVED'][i].truong_update_date_CPHT)+'</td>';
                    //         }
                    //         else if (data['APPROVED'][i].truong_update_date_HTAT !== null && data['APPROVED'][i].truong_update_date_HTAT !== "") {
                    //             show_html += '<td>'+formatDateTimes(data['APPROVED'][i].truong_update_date_HTAT)+'</td>';
                    //         }
                    //         else if (data['APPROVED'][i].truong_update_date_HTBT !== null && data['APPROVED'][i].truong_update_date_HTBT !== "") {
                    //             show_html += '<td>'+formatDateTimes(data['APPROVED'][i].truong_update_date_HTBT)+'</td>';
                    //         }
                    //         else if (data['APPROVED'][i].truong_update_date_HSKT !== null && data['APPROVED'][i].truong_update_date_HSKT !== "") {
                    //             show_html += '<td>'+formatDateTimes(data['APPROVED'][i].truong_update_date_HSKT)+'</td>';
                    //         }
                    //         else if (data['APPROVED'][i].truong_update_date_HSDTTS !== null && data['APPROVED'][i].truong_update_date_HSDTTS !== "") {
                    //             show_html += '<td>'+formatDateTimes(data['APPROVED'][i].truong_update_date_HSDTTS)+'</td>';
                    //         }
                    //         else if (data['APPROVED'][i].truong_update_date_HTATHS !== null && data['APPROVED'][i].truong_update_date_HTATHS !== "") {
                    //             show_html += '<td>'+formatDateTimes(data['APPROVED'][i].truong_update_date_HTATHS)+'</td>';
                    //         }
                    //         else if (data['APPROVED'][i].truong_update_date_HBHSDTNT !== null && data['APPROVED'][i].truong_update_date_HBHSDTNT !== "") {
                    //             show_html += '<td>'+formatDateTimes(data['APPROVED'][i].truong_update_date_HBHSDTNT)+'</td>';
                    //         }
                    //         else {
                    //             show_html += '<td></td>';
                    //         }

                    //         show_html += '</tr>';
                    //     }
                    //     show_html += '</tbody>';
                    //     show_html += '</table>';
                    // }
                    // else 
                    //----------------------23/12/2017---------------------------------------------
                    var titleNguoiDuyet = 'Người duyệt';
                    var titleNgayDuyet = 'Ngày duyệt';

                    if (loaiDanhSach == 4 || loaiDanhSach == 7 || loaiDanhSach == 10) {
                        titleNguoiDuyet = 'Người trả lại';
                        titleNgayDuyet = 'Ngày trả lại';
                    }

                    if (parseInt(data['LEVELUSER']) === 2) {

                        for (var i = 0; i < data['APPROVED'].length; i++) {

                            if (data['APPROVED'][i].his_note_duyet_phongGD !== null && data['APPROVED'][i].his_note_duyet_phongGD !== "") {
                                $('#txtGhiChuTHCD').val(data['APPROVED'][i].his_note_duyet_phongGD);
                            }
                            else if (data['APPROVED'][i].his_note_tralai_phongGD !== null && data['APPROVED'][i].his_note_tralai_phongGD !== "") {
                                $('#txtGhiChuTHCD').val(data['APPROVED'][i].his_note_tralai_phongGD);
                            }

                            if (parseInt(data['APPROVED'][i].phongTC_dapheduyet) > 0 || parseInt(data['APPROVED'][i].So_dapheduyet) > 0 || parseInt(data['APPROVED'][i].phongTC_tralai) > 0 || parseInt(data['APPROVED'][i].So_tralai) > 0) {
                                $('#btnTraLaiDSChoPD').html("Thu hồi");
                            }
                            else {
                                $('#btnTraLaiDSChoPD').html("Trả lại");
                            }
                        }
                    }
                    else if (parseInt(data['LEVELUSER']) === 3) {

                        for (var i = 0; i < data['APPROVED'].length; i++) {

                            if (data['APPROVED'][i].his_note_duyet_phongTC !== null && data['APPROVED'][i].his_note_duyet_phongTC !== "") {
                                $('#txtGhiChuTHCD').val(data['APPROVED'][i].his_note_duyet_phongTC);
                            }
                            else if (data['APPROVED'][i].his_note_tralai_phongTC !== null && data['APPROVED'][i].his_note_tralai_phongTC !== "") {
                                $('#txtGhiChuTHCD').val(data['APPROVED'][i].his_note_tralai_phongTC);
                            }

                            if (parseInt(data['APPROVED'][i].his_trangthai_thuhoi) === 1) {
                                $('#btnDuyetDSChoPD').hide();
                                $('#btnTraLaiDSChoPD').hide();
                            }

                            if (parseInt(data['APPROVED'][i].So_dapheduyet) > 0 || parseInt(data['APPROVED'][i].So_tralai) > 0) {
                                $('#btnTraLaiDSChoPD').html("Thu hồi");
                            }
                            else {
                                $('#btnTraLaiDSChoPD').html("Trả lại");
                            }
                        }
                    }
                    else if (parseInt(data['LEVELUSER']) === 4 || parseInt(data['LEVELUSER']) === 1) {
                        for (var i = 0; i < data['APPROVED'].length; i++) {
                            if (parseInt(data['LEVELUSER']) === 4) {
                                // $('#txtGhiChuTHCD').show();
                                if (data['APPROVED'][i].his_note_duyet_So !== null && data['APPROVED'][i].his_note_duyet_So !== "") {
                                    $('#txtGhiChuTHCD').val(data['APPROVED'][i].his_note_duyet_So);
                                }
                                else if (data['APPROVED'][i].his_note_tralai_So !== null && data['APPROVED'][i].his_note_tralai_So !== "") {
                                    $('#txtGhiChuTHCD').val(data['APPROVED'][i].his_note_tralai_So);
                                }
                            }

                            if (parseInt(data['APPROVED'][i].his_trangthai_thuhoi) === 1) {
                                $('#btnDuyetDSChoPD').hide();
                                $('#btnTraLaiDSChoPD').hide();
                                // $('#txtGhiChuTHCD').hide();
                            }
                        }
                    }

                    show_html += '<table class="table table-striped table-bordered table-hover dataTable no-footer" style="max-width: 100%;">';
                    show_html += '<thead>';
                    show_html += '<tr class="success">';
                    // show_html += '<th class="text-center" style="vertical-align:middle" colspan="2">Trường</th>';
                    show_html += '<th class="text-center" style="vertical-align:middle" colspan="2">Phòng Giáo Dục</th>';
                    show_html += '<th class="text-center" style="vertical-align:middle" colspan="2">Phòng Tài Chính</th>';
                    show_html += '<th class="text-center" style="vertical-align:middle" colspan="2">Sở</th>';
                    show_html += '</tr>';
                    show_html += '<tr class="success">';
                    show_html += '<th class="text-center" style="vertical-align:middle">' + titleNguoiDuyet + '</th>';
                    show_html += '<th class="text-center" style="vertical-align:middle">' + titleNgayDuyet + '</th>';
                    show_html += '<th class="text-center" style="vertical-align:middle">' + titleNguoiDuyet + '</th>';
                    show_html += '<th class="text-center" style="vertical-align:middle">' + titleNgayDuyet + '</th>';
                    show_html += '<th class="text-center" style="vertical-align:middle">' + titleNguoiDuyet + '</th>';
                    show_html += '<th class="text-center" style="vertical-align:middle">' + titleNgayDuyet + '</th>';
                    // show_html += '<th class="text-center" style="vertical-align:middle">Người duyệt</th>';
                    // show_html += '<th class="text-center" style="vertical-align:middle">Ngày duyệt</th>';
                    show_html += '</tr>';
                    show_html += '</thead>';
                    show_html += '<tbody>';

                    for (var i = 0; i < data['APPROVED'].length; i++) {
                        show_html += '<tr>';
                        //Cấp phòng Giáo Dục
                        if (data['APPROVED'][i].phongGD_user_MGHP !== null && data['APPROVED'][i].phongGD_user_MGHP !== "") {
                            show_html += '<td>' + ConvertString(data['APPROVED'][i].phongGD_user_MGHP) + '</td>';
                        }
                        else if (data['APPROVED'][i].phongGD_user_CPHT !== null && data['APPROVED'][i].phongGD_user_CPHT !== "") {
                            show_html += '<td>' + ConvertString(data['APPROVED'][i].phongGD_user_CPHT) + '</td>';
                        }
                        else if (data['APPROVED'][i].phongGD_user_HTAT !== null && data['APPROVED'][i].phongGD_user_HTAT !== "") {
                            show_html += '<td>' + ConvertString(data['APPROVED'][i].phongGD_user_HTAT) + '</td>';
                        }
                        else if (data['APPROVED'][i].phongGD_user_HTBT !== null && data['APPROVED'][i].phongGD_user_HTBT !== "") {
                            show_html += '<td>' + ConvertString(data['APPROVED'][i].phongGD_user_HTBT) + '</td>';
                        }
                        else if (data['APPROVED'][i].phongGD_user_HSKT !== null && data['APPROVED'][i].phongGD_user_HSKT !== "") {
                            show_html += '<td>' + ConvertString(data['APPROVED'][i].phongGD_user_HSKT) + '</td>';
                        }
                        else if (data['APPROVED'][i].phongGD_user_HSDTTS !== null && data['APPROVED'][i].phongGD_user_HSDTTS !== "") {
                            show_html += '<td>' + ConvertString(data['APPROVED'][i].phongGD_user_HSDTTS) + '</td>';
                        }
                        else if (data['APPROVED'][i].phongGD_user_HTATHS !== null && data['APPROVED'][i].phongGD_user_HTATHS !== "") {
                            show_html += '<td>' + ConvertString(data['APPROVED'][i].phongGD_user_HTATHS) + '</td>';
                        }
                        else if (data['APPROVED'][i].phongGD_user_HBHSDTNT !== null && data['APPROVED'][i].phongGD_user_HBHSDTNT !== "") {
                            show_html += '<td>' + ConvertString(data['APPROVED'][i].phongGD_user_HBHSDTNT) + '</td>';
                        }
                        else {
                            show_html += '<td></td>';
                        }

                        if (data['APPROVED'][i].phongGD_update_date_MGHP !== null && data['APPROVED'][i].phongGD_update_date_MGHP !== "") {
                            show_html += '<td>' + formatDateTimes(data['APPROVED'][i].phongGD_update_date_MGHP) + '</td>';
                        }
                        else if (data['APPROVED'][i].phongGD_update_date_CPHT !== null && data['APPROVED'][i].phongGD_update_date_CPHT !== "") {
                            show_html += '<td>' + formatDateTimes(data['APPROVED'][i].phongGD_update_date_CPHT) + '</td>';
                        }
                        else if (data['APPROVED'][i].phongGD_update_date_HTAT !== null && data['APPROVED'][i].phongGD_update_date_HTAT !== "") {
                            show_html += '<td>' + formatDateTimes(data['APPROVED'][i].phongGD_update_date_HTAT) + '</td>';
                        }
                        else if (data['APPROVED'][i].phongGD_update_date_HTBT !== null && data['APPROVED'][i].phongGD_update_date_HTBT !== "") {
                            show_html += '<td>' + formatDateTimes(data['APPROVED'][i].phongGD_update_date_HTBT) + '</td>';
                        }
                        else if (data['APPROVED'][i].phongGD_update_date_HSKT !== null && data['APPROVED'][i].phongGD_update_date_HSKT !== "") {
                            show_html += '<td>' + formatDateTimes(data['APPROVED'][i].phongGD_update_date_HSKT) + '</td>';
                        }
                        else if (data['APPROVED'][i].phongGD_update_date_HSDTTS !== null && data['APPROVED'][i].phongGD_update_date_HSDTTS !== "") {
                            show_html += '<td>' + formatDateTimes(data['APPROVED'][i].phongGD_update_date_HSDTTS) + '</td>';
                        }
                        else if (data['APPROVED'][i].phongGD_update_date_HTATHS !== null && data['APPROVED'][i].phongGD_update_date_HTATHS !== "") {
                            show_html += '<td>' + formatDateTimes(data['APPROVED'][i].phongGD_update_date_HTATHS) + '</td>';
                        }
                        else if (data['APPROVED'][i].phongGD_update_date_HBHSDTNT !== null && data['APPROVED'][i].phongGD_update_date_HBHSDTNT !== "") {
                            show_html += '<td>' + formatDateTimes(data['APPROVED'][i].phongGD_update_date_HBHSDTNT) + '</td>';
                        }
                        else {
                            show_html += '<td></td>';
                        }

                        //Cấp phòng Tài Chính
                        if (data['APPROVED'][i].phongTC_user_MGHP !== null && data['APPROVED'][i].phongTC_user_MGHP !== "") {
                            show_html += '<td>' + ConvertString(data['APPROVED'][i].phongTC_user_MGHP) + '</td>';
                        }
                        else if (data['APPROVED'][i].phongTC_user_CPHT !== null && data['APPROVED'][i].phongTC_user_CPHT !== "") {
                            show_html += '<td>' + ConvertString(data['APPROVED'][i].phongTC_user_CPHT) + '</td>';
                        }
                        else if (data['APPROVED'][i].phongTC_user_HTAT !== null && data['APPROVED'][i].phongTC_user_HTAT !== "") {
                            show_html += '<td>' + ConvertString(data['APPROVED'][i].phongTC_user_HTAT) + '</td>';
                        }
                        else if (data['APPROVED'][i].phongTC_user_HTBT !== null && data['APPROVED'][i].phongTC_user_HTBT !== "") {
                            show_html += '<td>' + ConvertString(data['APPROVED'][i].phongTC_user_HTBT) + '</td>';
                        }
                        else if (data['APPROVED'][i].phongTC_user_HSKT !== null && data['APPROVED'][i].phongTC_user_HSKT !== "") {
                            show_html += '<td>' + ConvertString(data['APPROVED'][i].phongTC_user_HSKT) + '</td>';
                        }
                        else if (data['APPROVED'][i].phongTC_user_HSDTTS !== null && data['APPROVED'][i].phongTC_user_HSDTTS !== "") {
                            show_html += '<td>' + ConvertString(data['APPROVED'][i].phongTC_user_HSDTTS) + '</td>';
                        }
                        else if (data['APPROVED'][i].phongTC_user_HTATHS !== null && data['APPROVED'][i].phongTC_user_HTATHS !== "") {
                            show_html += '<td>' + ConvertString(data['APPROVED'][i].phongTC_user_HTATHS) + '</td>';
                        }
                        else if (data['APPROVED'][i].phongTC_user_HBHSDTNT !== null && data['APPROVED'][i].phongTC_user_HBHSDTNT !== "") {
                            show_html += '<td>' + ConvertString(data['APPROVED'][i].phongTC_user_HBHSDTNT) + '</td>';
                        }
                        else {
                            show_html += '<td></td>';
                        }

                        if (data['APPROVED'][i].phongTC_update_date_MGHP !== null && data['APPROVED'][i].phongTC_update_date_MGHP !== "") {
                            show_html += '<td>' + formatDateTimes(data['APPROVED'][i].phongTC_update_date_MGHP) + '</td>';
                        }
                        else if (data['APPROVED'][i].phongTC_update_date_CPHT !== null && data['APPROVED'][i].phongTC_update_date_CPHT !== "") {
                            show_html += '<td>' + formatDateTimes(data['APPROVED'][i].phongTC_update_date_CPHT) + '</td>';
                        }
                        else if (data['APPROVED'][i].phongTC_update_date_HTAT !== null && data['APPROVED'][i].phongTC_update_date_HTAT !== "") {
                            show_html += '<td>' + formatDateTimes(data['APPROVED'][i].phongTC_update_date_HTAT) + '</td>';
                        }
                        else if (data['APPROVED'][i].phongTC_update_date_HTBT !== null && data['APPROVED'][i].phongTC_update_date_HTBT !== "") {
                            show_html += '<td>' + formatDateTimes(data['APPROVED'][i].phongTC_update_date_HTBT) + '</td>';
                        }
                        else if (data['APPROVED'][i].phongTC_update_date_HSKT !== null && data['APPROVED'][i].phongTC_update_date_HSKT !== "") {
                            show_html += '<td>' + formatDateTimes(data['APPROVED'][i].phongTC_update_date_HSKT) + '</td>';
                        }
                        else if (data['APPROVED'][i].phongTC_update_date_HSDTTS !== null && data['APPROVED'][i].phongTC_update_date_HSDTTS !== "") {
                            show_html += '<td>' + formatDateTimes(data['APPROVED'][i].phongTC_update_date_HSDTTS) + '</td>';
                        }
                        else if (data['APPROVED'][i].phongTC_update_date_HTATHS !== null && data['APPROVED'][i].phongTC_update_date_HTATHS !== "") {
                            show_html += '<td>' + formatDateTimes(data['APPROVED'][i].phongTC_update_date_HTATHS) + '</td>';
                        }
                        else if (data['APPROVED'][i].phongTC_update_date_HBHSDTNT !== null && data['APPROVED'][i].phongTC_update_date_HBHSDTNT !== "") {
                            show_html += '<td>' + formatDateTimes(data['APPROVED'][i].phongTC_update_date_HBHSDTNT) + '</td>';
                        }
                        else {
                            show_html += '<td></td>';
                        }

                        //Cấp sỞ
                        if (data['APPROVED'][i].so_user_MGHP !== null && data['APPROVED'][i].so_user_MGHP !== "") {
                            show_html += '<td>' + ConvertString(data['APPROVED'][i].so_user_MGHP) + '</td>';
                        }
                        else if (data['APPROVED'][i].so_user_CPHT !== null && data['APPROVED'][i].so_user_CPHT !== "") {
                            show_html += '<td>' + ConvertString(data['APPROVED'][i].so_user_CPHT) + '</td>';
                        }
                        else if (data['APPROVED'][i].so_user_HTAT !== null && data['APPROVED'][i].so_user_HTAT !== "") {
                            show_html += '<td>' + ConvertString(data['APPROVED'][i].so_user_HTAT) + '</td>';
                        }
                        else if (data['APPROVED'][i].so_user_HTBT !== null && data['APPROVED'][i].so_user_HTBT !== "") {
                            show_html += '<td>' + ConvertString(data['APPROVED'][i].so_user_HTBT) + '</td>';
                        }
                        else if (data['APPROVED'][i].so_user_HSKT !== null && data['APPROVED'][i].so_user_HSKT !== "") {
                            show_html += '<td>' + ConvertString(data['APPROVED'][i].so_user_HSKT) + '</td>';
                        }
                        else if (data['APPROVED'][i].so_user_HSDTTS !== null && data['APPROVED'][i].so_user_HSDTTS !== "") {
                            show_html += '<td>' + ConvertString(data['APPROVED'][i].so_user_HSDTTS) + '</td>';
                        }
                        else if (data['APPROVED'][i].so_user_HTATHS !== null && data['APPROVED'][i].so_user_HTATHS !== "") {
                            show_html += '<td>' + ConvertString(data['APPROVED'][i].so_user_HTATHS) + '</td>';
                        }
                        else if (data['APPROVED'][i].so_user_HBHSDTNT !== null && data['APPROVED'][i].so_user_HBHSDTNT !== "") {
                            show_html += '<td>' + ConvertString(data['APPROVED'][i].so_user_HBHSDTNT) + '</td>';
                        }
                        else {
                            show_html += '<td></td>';
                        }

                        if (data['APPROVED'][i].so_update_date_MGHP !== null && data['APPROVED'][i].so_update_date_MGHP !== "") {
                            show_html += '<td>' + formatDateTimes(data['APPROVED'][i].so_update_date_MGHP) + '</td>';
                        }
                        else if (data['APPROVED'][i].so_update_date_CPHT !== null && data['APPROVED'][i].so_update_date_CPHT !== "") {
                            show_html += '<td>' + formatDateTimes(data['APPROVED'][i].so_update_date_CPHT) + '</td>';
                        }
                        else if (data['APPROVED'][i].so_update_date_HTAT !== null && data['APPROVED'][i].so_update_date_HTAT !== "") {
                            show_html += '<td>' + formatDateTimes(data['APPROVED'][i].so_update_date_HTAT) + '</td>';
                        }
                        else if (data['APPROVED'][i].so_update_date_HTBT !== null && data['APPROVED'][i].so_update_date_HTBT !== "") {
                            show_html += '<td>' + formatDateTimes(data['APPROVED'][i].so_update_date_HTBT) + '</td>';
                        }
                        else if (data['APPROVED'][i].so_update_date_HSKT !== null && data['APPROVED'][i].so_update_date_HSKT !== "") {
                            show_html += '<td>' + formatDateTimes(data['APPROVED'][i].so_update_date_HSKT) + '</td>';
                        }
                        else if (data['APPROVED'][i].so_update_date_HSDTTS !== null && data['APPROVED'][i].so_update_date_HSDTTS !== "") {
                            show_html += '<td>' + formatDateTimes(data['APPROVED'][i].so_update_date_HSDTTS) + '</td>';
                        }
                        else if (data['APPROVED'][i].so_update_date_HTATHS !== null && data['APPROVED'][i].so_update_date_HTATHS !== "") {
                            show_html += '<td>' + formatDateTimes(data['APPROVED'][i].so_update_date_HTATHS) + '</td>';
                        }
                        else if (data['APPROVED'][i].so_update_date_HBHSDTNT !== null && data['APPROVED'][i].so_update_date_HBHSDTNT !== "") {
                            show_html += '<td>' + formatDateTimes(data['APPROVED'][i].so_update_date_HBHSDTNT) + '</td>';
                        }
                        else {
                            show_html += '<td></td>';
                        }

                        show_html += '</tr>';
                    }
                    show_html += '</tbody>';
                    show_html += '</table>';

                    $('#tbApproved').html(show_html);
                    $('#tbApproved').show();
                }
                else {
                    $('#tbApproved').hide();
                }
                // }
                // else {
                //     $('#tbApproved').hide();
                // }
            }

            // $('#txtGhiChuTHCD').val(data['CHEDO'][0]['GHICHU_PHEDUYET']);
            $('#txtProfileName').html(name);
            $('#dataDanhsachCheDo').html(html_show);
            $("#myModalApproved").modal("show");
        }, error: function (data) {
            closeLoading();
        }
    });
}

function openPopupPheDuyetChedo(id, idTHCD, number) {
    _id = idTHCD;
    _idProfile = id;
    id = id + '-' + number + '-' + _year;

    $('#txtGhiChuTHCD').val('');
    $('#checkedAllChedo').prop('checked', false);
    GetFromServer('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/getProfileSubById/' + id, function (data) {
        var html_show = "";

        var groupId = 0;

        if (data !== null && data !== "") {
            // for (var i = 0; i < data['GROUP'].length; i++) {

            for (var j = 0; j < data['SUBJECT'].length; j++) {
                // if (data['GROUP'][i].group_id == data['SUBJECT'][j].subject_history_group_id) {
                html_show += "<tr>";
                html_show += "<td class='text-center'>" + (j + 1 + (GET_START_RECORD_NGHILC() * 10)) + "</td>";
                html_show += "<td class='text-center'>";

                // console.log(data['CHEDO'][0]['TRANGTHAIHK1']);
                // console.log(data['CHEDO'][0]['TRANGTHAIHK2']);

                if (data['CHEDO'][0]['TRANGTHAI_PHEDUYET'] == 1) {

                    if ((parseInt(data['SUBJECT'][j].subject_history_group_id) === 89
                        || parseInt(data['SUBJECT'][j].subject_history_group_id) === 90
                        || parseInt(data['SUBJECT'][j].subject_history_group_id) === 91) && parseInt(data['CHEDO'][0]['MGHP_PHEDUYET']) == 1) {
                        html_show += "<input type='checkbox' id='chilCheckPD' name='choose' value='" + data['SUBJECT'][j].subject_history_group_id + "' checked='checked'>";
                    }
                    else if ((parseInt(data['SUBJECT'][j].subject_history_group_id) === 92) && parseInt(data['CHEDO'][0]['CPHT_PHEDUYET']) == 1) {
                        html_show += "<input type='checkbox' id='chilCheckPD' name='choose' value='" + data['SUBJECT'][j].subject_history_group_id + "' checked='checked'>";
                    }
                    else if ((parseInt(data['SUBJECT'][j].subject_history_group_id) === 93) && parseInt(data['CHEDO'][0]['HTAT_PHEDUYET']) == 1) {
                        html_show += "<input type='checkbox' id='chilCheckPD' name='choose' value='" + data['SUBJECT'][j].subject_history_group_id + "' checked='checked'>";
                    }
                    else if ((parseInt(data['SUBJECT'][j].subject_history_group_id) === 94) && parseInt(data['CHEDO'][0]['HTBT_TA_PHEDUYET']) == 1) {
                        html_show += "<input type='checkbox' id='chilCheckPD' name='choose' value='" + data['SUBJECT'][j].subject_history_group_id + "' checked='checked'>";
                    }
                    else if ((parseInt(data['SUBJECT'][j].subject_history_group_id) === 98) && parseInt(data['CHEDO'][0]['HTBT_TO_PHEDUYET']) == 1) {
                        html_show += "<input type='checkbox' id='chilCheckPD' name='choose' value='" + data['SUBJECT'][j].subject_history_group_id + "' checked='checked'>";
                    }
                    else if ((parseInt(data['SUBJECT'][j].subject_history_group_id) === 115) && parseInt(data['CHEDO'][0]['HTBT_VHTT_PHEDUYET']) == 1) {
                        html_show += "<input type='checkbox' id='chilCheckPD' name='choose' value='" + data['SUBJECT'][j].subject_history_group_id + "' checked='checked'>";
                    }
                    else if ((parseInt(data['SUBJECT'][j].subject_history_group_id) === 95) && parseInt(data['CHEDO'][0]['HSKT_HB_PHEDUYET']) == 1) {
                        html_show += "<input type='checkbox' id='chilCheckPD' name='choose' value='" + data['SUBJECT'][j].subject_history_group_id + "' checked='checked'>";
                    }
                    else if ((parseInt(data['SUBJECT'][j].subject_history_group_id) === 100) && parseInt(data['CHEDO'][0]['HSKT_DDHT_PHEDUYET']) == 1) {
                        html_show += "<input type='checkbox' id='chilCheckPD' name='choose' value='" + data['SUBJECT'][j].subject_history_group_id + "' checked='checked'>";
                    }
                    else if ((parseInt(data['SUBJECT'][j].subject_history_group_id) === 99) && parseInt(data['CHEDO'][0]['HSDTTS_PHEDUYET']) == 1) {
                        html_show += "<input type='checkbox' id='chilCheckPD' name='choose' value='" + data['SUBJECT'][j].subject_history_group_id + "' checked='checked'>";
                    }
                    else if ((parseInt(data['SUBJECT'][j].subject_history_group_id) === 118) && parseInt(data['CHEDO'][0]['HTATHS_PHEDUYET']) == 1) {
                        html_show += "<input type='checkbox' id='chilCheckPD' name='choose' value='" + data['SUBJECT'][j].subject_history_group_id + "' checked='checked'>";
                    }
                    else if ((parseInt(data['SUBJECT'][j].subject_history_group_id) === 119) && parseInt(data['CHEDO'][0]['HBHSDTNT_PHEDUYET']) == 1) {
                        html_show += "<input type='checkbox' id='chilCheckPD' name='choose' value='" + data['SUBJECT'][j].subject_history_group_id + "' checked='checked'>";
                    }
                    else {
                        html_show += "<input type='checkbox' id='chilCheckPD' name='choose' value='" + data['SUBJECT'][j].subject_history_group_id + "'>";
                    }
                }
                else {
                    html_show += "<input type='checkbox' id='chilCheckPD' name='choose' value='" + data['SUBJECT'][j].subject_history_group_id + "'>";
                }

                html_show += "</td>";

                html_show += "<td>" + data['SUBJECT'][j].group_name + "</td>";
                html_show += "<td>" + data['SUBJECT'][j].subject_name + "</td>";
                html_show += "</tr>";
                // }
            }

            // }
        }

        $('#txtGhiChuTHCD').val(data['CHEDO'][0]['GHICHU_PHEDUYET']);
        $('#dataDanhsachCheDo').html(html_show);
        $("#myModalApproved").modal("show");
    }, function (data) {

    }, "", "loading", "");
}

function approvedChedoPheDuyet(o, number = 0) {
    $.ajax({
        type: "POST",
        url: '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/approvedchedoPD',
        data: JSON.stringify(o),
        dataType: 'json',
        contentType: 'application/json; charset=utf-8',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
        success: function (data) {
            // console.log(data);
            if (data['success'] != "" && data['success'] != undefined) {
                utility.message("Thông báo", data['success'], null, 3000);
                GET_INITIAL_NGHILC();
                loadDanhSachByCongVan($('#drPagingDanhsachtonghop').val(), $('#txtSearchPheDuyet').val(), loaiDanhSach);

                $("#myModalApproved").modal("hide");
                closeLoading();
                $('#btnApprovedDanhSachDuyet').button('reset');
                $('#btnRevertDanhSachDuyet').button('reset');

                $('#btnDuyetDSChoPD').button('reset');
                $('#btnTraLaiDSChoPD').button('reset');
            }
            if (data['error'] != "" && data['error'] != undefined) {
                GET_INITIAL_NGHILC();
                loadDanhSachByCongVan($('#drPagingDanhsachtonghop').val(), $('#txtSearchPheDuyet').val(), loaiDanhSach);

                utility.message("Thông báo", data['error'], null, 3000, 1);
                closeLoading();
                $('#btnApprovedDanhSachDuyet').button('reset');
                $('#btnRevertDanhSachDuyet').button('reset');

                $('#btnDuyetDSChoPD').button('reset');
                $('#btnTraLaiDSChoPD').button('reset');
            }
        }, error: function (data) {
            closeLoading();
        }
    });
}

function revertChedoPheDuyet(o, number = 0) {
    $.ajax({
        type: "POST",
        url: '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/revertchedoPD',
        data: JSON.stringify(o),
        dataType: 'json',
        contentType: 'application/json; charset=utf-8',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
        success: function (data) {
            // console.log(data);
            if (data['success'] != "" && data['success'] != undefined) {
                utility.message("Thông báo", data['success'], null, 3000);
                GET_INITIAL_NGHILC();
                loadDanhSachByCongVan($('#drPagingDanhsachtonghop').val(), $('#txtSearchPheDuyet').val(), loaiDanhSach);

                $("#myModalApproved").modal("hide");
                closeLoading();

                $('#btnApprovedDanhSachDuyet').button('reset');
                $('#btnRevertDanhSachDuyet').button('reset');

                $('#btnDuyetDSChoPD').button('reset');
                $('#btnTraLaiDSChoPD').button('reset');
            }
            if (data['error'] != "" && data['error'] != undefined) {
                GET_INITIAL_NGHILC();
                loadDanhSachByCongVan($('#drPagingDanhsachtonghop').val(), $('#txtSearchPheDuyet').val(), loaiDanhSach);

                utility.message("Thông báo", data['error'], null, 3000, 1);
                closeLoading();

                $('#btnApprovedDanhSachDuyet').button('reset');
                $('#btnRevertDanhSachDuyet').button('reset');

                $('#btnDuyetDSChoPD').button('reset');
                $('#btnTraLaiDSChoPD').button('reset');
            }
        }, error: function (data) {
            closeLoading();
        }
    });
}

function loadlistUnApproved(row, keySearch = "") {

    var msg_warning = "";
    if ($('#sltCongvan').val() == '') {
        GET_INITIAL_NGHILC();
        loadSoCongVanDanhSachTraLai(keySearch);
    } else {
        var schools_id = $('#drSchoolTHCD').val();
        if (parseInt($('#school-per').val()) != 0) {
            msg_warning = validateTHCD();
        } else {
            schools_id = 0;
        }


        // alert(msg_warning);

        if (msg_warning !== null && msg_warning !== "") {
            utility.messagehide("messageValidate", msg_warning, 1, 5000);
            return;
        }


        // var year = $('#sltYear').val();
        var socongvan = $('#sltCongvan').val();

        // _year = year;

        // var ky = year.split("-");
        var number = 3;

        // if (ky[0] == 'HK1') {
        //     number = 1;
        // }
        // else if (ky[0] == 'HK2') {
        //     number = 2;
        // }
        // else if (ky[0] == 'CA') {
        //     number = 3;
        // }

        var html_show = "";
        var o = {
            start: (GET_START_RECORD_NGHILC()),
            limit: row,
            SCHOOLID: schools_id,
            //  YEAR: year,
            SOCONGVAN: socongvan,
            KEY: keySearch
        };
        $.ajax({
            type: "POST",
            url: '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/loadListUnApproved',
            data: JSON.stringify(o),
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            success: function (datas) {
                loadDanhSachTraLai();
                SETUP_PAGING_NGHILC(datas, function () {
                    loadlistUnApproved(row, keySearch, status);
                });

                $('#dataListPhongRevert').html("");
                var dataget = datas.data;
                //console.log(dataget);

                if (dataget.length > 0) {
                    for (var i = 0; i < dataget.length; i++) {
                        var totalMoney = 0;
                        totalMoney = parseInt(dataget[i].MGHP) + parseInt(dataget[i].CPHT) + parseInt(dataget[i].HTAT) + parseInt(dataget[i].HTBT_TA) + parseInt(dataget[i].HTBT_TO) + parseInt(dataget[i].HTBT_VHTT) + parseInt(dataget[i].HTATHS) + parseInt(dataget[i].HSKT_HB) + parseInt(dataget[i].HSKT_DDHT) + parseInt(dataget[i].HBHSDTNT) + parseInt(dataget[i].HSDTTS);

                        html_show += "<tr><td style='vertical-align:middle' class='text-center'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                        html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='getProfileSubById(" + parseInt(dataget[i].profile_id) + ", " + number + ");'>" + dataget[i].profile_name + "</a></td>";
                        html_show += "<td class='text-center' style='vertical-align:middle'>" + formatDates(dataget[i].profile_birthday) + "</td>";
                        //  html_show += "<td>"+dataget[i].schools_name+"</td>";
                        html_show += "<td class='text-center' style='vertical-align:middle'>" + dataget[i].class_name + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].MGHP) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].CPHT) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].HTAT) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].HTBT_TA) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].HTBT_TO) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].HTBT_VHTT) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].HTATHS) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].HSKT_HB) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].HSKT_DDHT) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].HBHSDTNT) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].HSDTTS) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(totalMoney) + "</td>";
                        html_show += "<td>" + ConvertString(dataget[i].Note) + "</td>";


                        html_show += "</tr>";
                    }
                }
                else {
                    html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
                }
                $('#dataListPhongRevert').html(html_show);
            }, error: function (dataget) {

            }
        });
    }
};

function approvedAllPheDuyet() {
    $('#btnApprovedDanhSachDuyet').button('loading');
    $('#btnRevertDanhSachDuyet').button('loading');

    $('#btnDuyetDSChoPD').button('loading');
    $('#btnTraLaiDSChoPD').button('loading');

    var msg_warning = "";

    msg_warning = validateTHCD();

    // alert(msg_warning);

    if (msg_warning !== null && msg_warning !== "") {
        utility.messagehide("messageValidate", msg_warning, 1, 5000);
        return;
    }

    var schools_id = $('#drSchoolTHCD').val();
    var socongvan = $('#sltCongvan').val();

    var url = '';

    if (loaiDanhSach == 2 || loaiDanhSach == 5 || loaiDanhSach == 8) {
        url = '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/approvedAllPheDuyet';
    }
    else if (loaiDanhSach == 3 || loaiDanhSach == 6 || loaiDanhSach == 9) {

    }
    else if (loaiDanhSach == 4 || loaiDanhSach == 7 || loaiDanhSach == 10) {
        url = '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/approvedAllDaPheDuyet';
    }

    var o = {
        SCHOOLID: schools_id,
        SOCONGVAN: socongvan
    };
    // console.log(o);
    $.ajax({
        type: "POST",
        url: url,
        data: JSON.stringify(o),
        dataType: 'json',
        contentType: 'application/json; charset=utf-8',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
        success: function (data) {
            // console.log(data);
            if (data['success'] != "" && data['success'] != undefined) {
                utility.message("Thông báo", data['success'], null, 3000);
                GET_INITIAL_NGHILC();

                loadDanhSachByCongVan($('#drPagingDanhsachtonghop').val(), $('#txtSearchPheDuyet').val(), loaiDanhSach);

                closeLoading();
                $('#btnApprovedDanhSachDuyet').button('reset');
                $('#btnRevertDanhSachDuyet').button('reset');

                $('#btnDuyetDSChoPD').button('reset');
                $('#btnTraLaiDSChoPD').button('reset');
            }
            if (data['error'] != "" && data['error'] != undefined) {
                GET_INITIAL_NGHILC();

                loadDanhSachByCongVan($('#drPagingDanhsachtonghop').val(), $('#txtSearchPheDuyet').val(), loaiDanhSach);

                utility.message("Thông báo", data['error'], null, 3000, 1);
                closeLoading();
                $('#btnApprovedDanhSachDuyet').button('reset');
                $('#btnRevertDanhSachDuyet').button('reset');

                $('#btnDuyetDSChoPD').button('reset');
                $('#btnTraLaiDSChoPD').button('reset');
            }
        }, error: function (data) {

        }
    });
};

function approvedAllDaPheDuyet() {

    var msg_warning = "";

    msg_warning = validateTHCD();

    // alert(msg_warning);

    if (msg_warning !== null && msg_warning !== "") {
        utility.messagehide("messageValidate", msg_warning, 1, 5000);
        return;
    }

    var schools_id = $('#drSchoolTHCD').val();
    var socongvan = $('#sltCongvan').val();

    var o = {
        SCHOOLID: schools_id,
        SOCONGVAN: socongvan
    };
    // console.log(o);
    $.ajax({
        type: "POST",
        url: '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/approvedAllDaPheDuyet',
        data: JSON.stringify(o),
        dataType: 'json',
        contentType: 'application/json; charset=utf-8',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
        success: function (data) {
            // console.log(data);
            if (data['success'] != "" && data['success'] != undefined) {
                utility.message("Thông báo", data['success'], null, 3000);
                GET_INITIAL_NGHILC();

                loadDanhSachTraLai($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());

                closeLoading();
            }
            if (data['error'] != "" && data['error'] != undefined) {
                GET_INITIAL_NGHILC();

                loadDanhSachTraLai($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());

                utility.message("Thông báo", data['error'], null, 3000, 1);
                closeLoading();
            }
        }, error: function (data) {

        }
    });
};

function revertAllPheDuyet() {
    $('#btnApprovedDanhSachDuyet').button('loading');
    $('#btnRevertDanhSachDuyet').button('loading');

    $('#btnDuyetDSChoPD').button('loading');
    $('#btnTraLaiDSChoPD').button('loading');

    var msg_warning = "";

    msg_warning = validateTHCD();

    // alert(msg_warning);

    if (msg_warning !== null && msg_warning !== "") {
        utility.messagehide("messageValidate", msg_warning, 1, 5000);
        return;
    }

    var schools_id = $('#drSchoolTHCD').val();
    var socongvan = $('#sltCongvan').val();

    var url = '';

    if (loaiDanhSach == 2 || loaiDanhSach == 5 || loaiDanhSach == 8) {
        url = '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/revertAllPheDuyet';
    }
    else if (loaiDanhSach == 3 || loaiDanhSach == 6 || loaiDanhSach == 9) {
        url = '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/revertAllDaPheDuyet';
    }
    else if (loaiDanhSach == 4 || loaiDanhSach == 7 || loaiDanhSach == 10) {
        // url = '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/approvedAllDaPheDuyet';
    }

    var o = {
        SCHOOLID: schools_id,
        SOCONGVAN: socongvan
    };
    // console.log(o);
    $.ajax({
        type: "POST",
        url: url,
        data: JSON.stringify(o),
        // dataType: 'json',
        contentType: 'application/json; charset=utf-8',
        // cache: false,    
        // processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
        success: function (data) {
            console.log(data);
            if (data['success'] != "" && data['success'] != undefined) {
                utility.message("Thông báo", data['success'], null, 3000);
                GET_INITIAL_NGHILC();
                loadDanhSachByCongVan($('#drPagingDanhsachtonghop').val(), $('#txtSearchPheDuyet').val(), loaiDanhSach);

                closeLoading();
                $('#btnApprovedDanhSachDuyet').button('reset');
                $('#btnRevertDanhSachDuyet').button('reset');

                $('#btnDuyetDSChoPD').button('reset');
                $('#btnTraLaiDSChoPD').button('reset');
            }
            if (data['error'] != "" && data['error'] != undefined) {
                GET_INITIAL_NGHILC();
                loadDanhSachByCongVan($('#drPagingDanhsachtonghop').val(), $('#txtSearchPheDuyet').val(), loaiDanhSach);

                utility.message("Thông báo", data['error'], null, 3000, 1);
                closeLoading();
                $('#btnApprovedDanhSachDuyet').button('reset');
                $('#btnRevertDanhSachDuyet').button('reset');

                $('#btnDuyetDSChoPD').button('reset');
                $('#btnTraLaiDSChoPD').button('reset');
            }
        }, error: function (data) {

        }
    });
};

function revertAllDaPheDuyet() {

    var msg_warning = "";

    msg_warning = validateTHCD();

    // alert(msg_warning);

    if (msg_warning !== null && msg_warning !== "") {
        utility.messagehide("messageValidate", msg_warning, 1, 5000);
        return;
    }

    var schools_id = $('#drSchoolTHCD').val();
    var socongvan = $('#sltCongvan').val();

    var o = {
        SCHOOLID: schools_id,
        SOCONGVAN: socongvan
    };
    console.log(o);
    $.ajax({
        type: "POST",
        url: '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/revertAllDaPheDuyet',
        data: JSON.stringify(o),
        // dataType: 'json',
        contentType: 'application/json; charset=utf-8',
        // cache: false,    
        // processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
        success: function (data) {
            console.log(data);
            if (data['success'] != "" && data['success'] != undefined) {
                utility.message("Thông báo", data['success'], null, 3000);
                GET_INITIAL_NGHILC();
                loadDanhSachDaPheDuyet($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());

                closeLoading();
            }
            if (data['error'] != "" && data['error'] != undefined) {
                GET_INITIAL_NGHILC();
                loadDanhSachDaPheDuyet($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());

                utility.message("Thông báo", data['error'], null, 3000, 1);
                closeLoading();
            }
        }, error: function (data) {

        }
    });
};

//--------------------------------------------------------Lập danh sách cấp Phòng Sở---------------------------------------------------
function loadDanhSachByPhongSo(row, schools_id = 0, loaicongvan = "", keySearch = "") {

    var o = {
        start: (GET_START_RECORD_NGHILC()),
        limit: row,
        SCHOOLID: schools_id,
        LOAICONGVAN: loaicongvan,
        SOCONGVAN: $('#sltCongvan').val(),
        YEAR: $('#sltYear').val(),
        KEY: keySearch
    };
    var html_header = '';
    html_header += '<tr class="success">';
    html_header += '<th  class="text-center" rowspan="2" style="vertical-align:middle;width: 3%">STT</th>';
    html_header += '<th  class="text-center" rowspan="2" style="vertical-align:middle;width: 12%">Tên trường</th>';
    html_header += '<th  class="text-center" colspan="12" style="vertical-align:middle">Công văn</th>';

    html_header += '</tr>';
    html_header += '<tr class="success">';
    html_header += '<th  class="text-center" style="vertical-align:middle;width: 10%">Trường gửi</th>';
    html_header += '<th  class="text-center" style="vertical-align:middle;width: 4%">Tổng</th>';
    html_header += '<th  class="text-center" style="vertical-align:middle;width: 5%">Trạng thái</th>';

    html_header += '<th  class="text-center" style="vertical-align:middle;width: 10%">Phòng GD gửi</th>';
    html_header += '<th  class="text-center" style="vertical-align:middle;width: 4%">Tổng</th>';
    html_header += '<th  class="text-center" style="vertical-align:middle;width: 5%">Trạng thái</th>';

    html_header += '<th  class="text-center" style="vertical-align:middle;width: 10%">Phòng TC gửi</th>';
    html_header += '<th  class="text-center" style="vertical-align:middle;width: 4%">Tổng</th>';
    html_header += '<th  class="text-center" style="vertical-align:middle;width: 5%">Trạng thái</th>';

    html_header += '<th  class="text-center" style="vertical-align:middle;width: 10%">Sở Tài chính</th>';
    html_header += '<th  class="text-center" style="vertical-align:middle;width: 4%">Tổng</th>';
    html_header += '<th  class="text-center" style="vertical-align:middle;width: 5%">Trạng thái</th>';
    html_header += '</tr>';
    $('#cmisGridHeader').html(html_header);
    $('#tbListDanhSach').html("<tr><td colspan='50' class='text-center'>Đang tải dữ liệu</td></tr>");
    PostToServer('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/loadDanhSachByPhongSo', o,
        function (datas) {
            SETUP_PAGING_NGHILC(datas, function () {
                loadDanhSachByPhongSo(row, schools_id, loaicongvan, keySearch);
            });


            var dataget = datas.data;

            if (dataget.length > 0) {


                var html_show = "";

                for (var i = 0; i < dataget.length; i++) {
                    html_show += "<tr><td class='text-center'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                    // Tên trường
                    html_show += "<td><a href='javascript:;' onclick='clickLoadShool(" + dataget[i].schools_id + ")'>" + ConvertString(dataget[i].schools_name) + "</a></td>";
                    // Tên công văn trường gửi
                    if (parseInt(dataget[i].s1) == 2) {
                        html_show += "<td><a class='tooltip-toggle cvbs' data-tooltip='Công văn bổ sung: " + ConvertString(dataget[i].date_1) + "' href='/ho-so/lap-danh-sach/loc-danh-sach/5-" + ConvertString(dataget[i].truong) + "' ><i class='cvdc glyphicon glyphicon-flag'></i>  " + ConvertString(dataget[i].truong) + "</a></td>";
                    } else if (parseInt(dataget[i].s1) == 0) {
                        html_show += "<td><a class='tooltip-toggle' data-tooltip='Công văn: " + ConvertString(dataget[i].date_1) + "' href='/ho-so/lap-danh-sach/loc-danh-sach/5-" + ConvertString(dataget[i].truong) + "' ><i class='cvbt glyphicon glyphicon-flag'></i>  " + ConvertString(dataget[i].truong) + "</a></td>";
                    } else if (parseInt(dataget[i].s1) == 1) {
                        html_show += "<td><a class='tooltip-toggle' data-tooltip='Công văn: " + ConvertString(dataget[i].date_1) + "' href='/ho-so/lap-danh-sach/loc-danh-sach/5-" + ConvertString(dataget[i].truong) + "' ><i class='cvth glyphicon glyphicon-flag'></i>  " + ConvertString(dataget[i].truong) + "</a></td>";
                    }
                    // Tổng số học sinh từ cấp trường
                    if (parseInt(datas.level) === parseInt(dataget[i].nhan_1)) {
                        if (dataget[i].report_type == 'NGNA') {
                            html_show += "<td class='text-center'><a class='tooltip-toggle btn btn-block btn-primary btn-xs' data-tooltip='Công văn: " + ConvertString(dataget[i].truong) + "' href='/ho-so/lap-danh-sach/danh-sach-de-nghi/" + ConvertString(dataget[i].truong) + "' ><i class='fa fa-eye' aria-hidden='true'></i></a></td>";
                        } else {
                            html_show += "<td class='text-center'><a class='tooltip-toggle btn btn-block btn-primary btn-xs' data-tooltip='Công văn: " + ConvertString(dataget[i].truong) + "' href='/ho-so/lap-danh-sach/danh-sach-de-nghi/" + ConvertString(dataget[i].truong) + "' >" + formatDate(dataget[i].c1) + "</a></td>";
                        }

                    } else {
                        if (dataget[i].report_type == 'NGNA') {
                            html_show += "<td class='text-center'><a class='tooltip-toggle' data-tooltip='Công văn: " + ConvertString(dataget[i].truong) + "' href='/ho-so/lap-danh-sach/danh-sach-de-nghi/" + ConvertString(dataget[i].truong) + "' ><i class='fa fa-eye' aria-hidden='true'></i></a></td>";
                        } else {
                            html_show += "<td class='text-center'><a class='tooltip-toggle' data-tooltip='Công văn: " + ConvertString(dataget[i].truong) + "' href='/ho-so/lap-danh-sach/danh-sach-de-nghi/" + ConvertString(dataget[i].truong) + "' >" + formatDate(dataget[i].c1) + "</a></td>";
                        }

                    }
                    // Trạng thái từ cấp trường
                    if (parseInt(dataget[i].report_cap_status_1) == 0) { // xin thu hồi trường
                        if (parseInt(datas.level) == 1) {
                            html_show += "<td class='text-center'><a class='btn btn-block btn-warning btn-xs' title='Xin thu hồi' onclick='thuhoi_cv(\"" + ConvertString(dataget[i].truong) + "\")' href='javascript:void(0)'>Chờ xử lý</a></td>";
                        } else {
                            html_show += "<td class='text-center'><span class='primary' title='Đang chờ xử lý'>Chờ xử lý</span></td>";
                        }

                    } else if (parseInt(dataget[i].report_cap_status_1) == 1) {// XIn thu hồi trường
                        if (parseInt(datas.level) == 1) {
                            html_show += "<td class='text-center'><a class='btn btn-block btn-success btn-xs' title='Xin thu hồi' onclick='thuhoi_cv(\"" + ConvertString(dataget[i].truong) + "\")' href='javascript:void(0)'>Đã xử lý</a></td>";
                        } else {
                            html_show += "<td class='text-center'><span class='success' title='Đã xử lý'>Đã xử lý</span></td>";
                        }
                    } else if (parseInt(dataget[i].report_cap_status_1) == 2) {// Trả lại trường
                        //if(parseInt(datas.level) == 2){
                        //    html_show += "<td class='text-center'><a class='btn btn-block btn-danger btn-xs' title='Xác nhận hủy' onclick='huycongvan_tralai(\""+ConvertString(dataget[i].truong)+"\")' href='javascript:void(0)'>Trả lại</a></td>";
                        //}else{
                        html_show += "<td class='text-center'><span class='danger'>Trả lại</span></td>";
                        //}
                    } else if (parseInt(dataget[i].report_cap_status_1) == 3) {// Thu hồi trường
                        html_show += "<td class='text-center'><span class='danger'>Thu hồi</span></td>";
                    } else if (parseInt(dataget[i].report_cap_status_1) == 4) {// Xác nhận thu hồi
                        if (parseInt(datas.level) == parseInt(dataget[i].nhan_1)) {
                            html_show += "<td class='text-center'><a class='btn btn-block btn-danger btn-xs' title='Xác nhận thu hồi' onclick='xacnhanthuhoi_cv(\"" + ConvertString(dataget[i].truong) + "\")' href='javascript:void(0)'>Xin thu hồi</a></td>";
                        } else {
                            html_show += "<td class='text-center'><span class='danger'>Xin thu hồi</span></td>";
                        }
                    } else {
                        html_show += "<td class='text-center'>-</td>";
                    }

                    // Cấp phòng GD gửi
                    html_show += "<td class='text-center'><a class='tooltip-toggle' data-tooltip='Công văn: " + ConvertString(dataget[i].date_2) + "' href='/ho-so/lap-danh-sach/loc-danh-sach/6-" + ConvertString(dataget[i].pgd) + "' >" + ConvertString(dataget[i].pgd) + "</a></td>";
                    if (parseInt(datas.level) === parseInt(dataget[i].nhan_2)) {
                        if (dataget[i].report_type == 'NGNA') {
                            if (dataget[i].pgd != null) {
                                html_show += "<td class='text-center'><a class='tooltip-toggle btn btn-block btn-primary btn-xs' data-tooltip='Công văn: " + ConvertString(dataget[i].truong) + "' href='/ho-so/lap-danh-sach/danh-sach-de-nghi/" + ConvertString(dataget[i].pgd) + "' ><i class='fa fa-eye' aria-hidden='true'></i></a></td>";
                            } else {
                                html_show += "<td class='text-center'><a class='tooltip-toggle' data-tooltip='Công văn: " + ConvertString(dataget[i].truong) + "' >-</a></td>";
                            }
                        } else {
                            html_show += "<td class='text-center'><a class='tooltip-toggle btn btn-block btn-primary btn-xs' data-tooltip='Công văn: " + ConvertString(dataget[i].truong) + "' href='/ho-so/lap-danh-sach/danh-sach-de-nghi/" + ConvertString(dataget[i].pgd) + "' >" + formatDate(dataget[i].c2) + "</a></td>";
                        }

                    } else {
                        if (dataget[i].report_type == 'NGNA') {
                            if (dataget[i].pgd != null) {
                                html_show += "<td class='text-center'><a class='tooltip-toggle' data-tooltip='Công văn: " + ConvertString(dataget[i].truong) + "' href='/ho-so/lap-danh-sach/danh-sach-de-nghi/" + ConvertString(dataget[i].pgd) + "' ><i class='fa fa-eye' aria-hidden='true'></i></a></td>";
                            } else {
                                html_show += "<td class='text-center'><a class='tooltip-toggle' data-tooltip='Công văn: " + ConvertString(dataget[i].truong) + "' >-</a></td>";
                            }
                        } else {
                            html_show += "<td class='text-center'><a class='tooltip-toggle' data-tooltip='Công văn: " + ConvertString(dataget[i].truong) + "' href='/ho-so/lap-danh-sach/danh-sach-de-nghi/" + ConvertString(dataget[i].pgd) + "' >" + formatDate(dataget[i].c2) + "</a></td>";
                        }

                    }
                    //  html_show += "<td class='text-center'><a class='tooltip-toggle' data-tooltip='Công văn: "+ConvertString(dataget[i].truong)+"' href='/ho-so/lap-danh-sach/danh-sach-de-nghi/"+ConvertString(dataget[i].pgd)+"' >"+formatDate(dataget[i].c2)+"</a></td>";
                    if (parseInt(dataget[i].report_cap_status_2) == 0) {
                        if (parseInt(datas.level) == parseInt(dataget[i].nhan_1)) {
                            html_show += "<td class='text-center'><a class='btn btn-block btn-warning btn-xs' title='Xin thu hồi' onclick='thuhoi_cv(\"" + ConvertString(dataget[i].pgd) + "\")' href='javascript:void(0)'>Chờ xử lý</a></td>";
                        } else {
                            html_show += "<td class='text-center'><span class='primary' title='Đang chờ xử lý'>Chờ xử lý</span></td>";
                        }
                    } else if (parseInt(dataget[i].report_cap_status_2) == 1) {
                        if (parseInt(datas.level) == parseInt(dataget[i].nhan_1)) {
                            html_show += "<td class='text-center'><a class='btn btn-block btn-success btn-xs' title='Xin thu hồi' onclick='thuhoi_cv(\"" + ConvertString(dataget[i].pgd) + "\")' href='javascript:void(0)'>Đã xử lý</a></td>";
                        } else {
                            html_show += "<td class='text-center'><span class='success' title='Đã xử lý'>Đã xử lý</span></td>";
                        }
                        //html_show += "<td class='text-center'><span class='success'>Đã xử lý</span></td>";
                    } else if (parseInt(dataget[i].report_cap_status_2) == 2) {
                        if (parseInt(datas.level) == parseInt(dataget[i].nhan_1)) {
                            html_show += "<td class='text-center'><a class='btn btn-block btn-danger btn-xs' title='Xác nhận hủy' onclick='huycongvan_tralai(\"" + ConvertString(dataget[i].pgd) + "\")' href='javascript:void(0)'>Trả lại</a></td>";
                        } else {
                            html_show += "<td class='text-center'><span class='danger'>Trả lại</span></td>";
                        }
                    } else if (parseInt(dataget[i].report_cap_status_2) == 3) {
                        html_show += "<td class='text-center'><span class='danger'>Thu hồi</span></td>";
                    } else if (parseInt(dataget[i].report_cap_status_2) == 4) {
                        if (parseInt(datas.level) == parseInt(dataget[i].nhan_2)) {
                            html_show += "<td class='text-center'><a class='btn btn-block btn-danger btn-xs' title='Xác nhận thu hồi' onclick='xacnhanthuhoi_cv(\"" + ConvertString(dataget[i].pgd) + "\")' href='javascript:void(0)'>Xin thu hồi</a></td>";
                        } else {
                            html_show += "<td class='text-center'><span class='danger'>Xin thu hồi</span></td>";
                        }

                    } else {
                        html_show += "<td class='text-center'>-</td>";
                    }
                    // Cấp phòng tài chính gửi
                    html_show += "<td class='text-center'><a class='tooltip-toggle' data-tooltip='Công văn: " + ConvertString(dataget[i].date_3) + "' href='/ho-so/lap-danh-sach/loc-danh-sach/7-" + ConvertString(dataget[i].ptc) + "' >" + ConvertString(dataget[i].ptc) + "</a></td>";
                    //html_show += "<td class='text-center'><a class='tooltip-toggle' data-tooltip='Công văn: "+ConvertString(dataget[i].ptc)+"' href='/ho-so/lap-danh-sach/danh-sach-de-nghi/"+ConvertString(dataget[i].ptc)+"' >"+formatDate(dataget[i].c3)+"</a></td>";
                    if (parseInt(datas.level) === parseInt(dataget[i].nhan_3)) {
                        if (dataget[i].report_type == 'NGNA') {
                            if (dataget[i].ptc != null) {
                                html_show += "<td class='text-center'><a class='tooltip-toggle btn btn-block btn-primary btn-xs' data-tooltip='Công văn: " + ConvertString(dataget[i].truong) + "' href='/ho-so/lap-danh-sach/danh-sach-de-nghi/" + ConvertString(dataget[i].ptc) + "' ><i class='fa fa-eye' aria-hidden='true'></i></a></td>";
                            } else {
                                html_show += "<td class='text-center'><a class='tooltip-toggle' data-tooltip='Công văn: " + ConvertString(dataget[i].truong) + "' >-</a></td>";
                            }
                        } else {
                            html_show += "<td class='text-center'><a class='tooltip-toggle btn btn-block btn-primary btn-xs' data-tooltip='Công văn: " + ConvertString(dataget[i].truong) + "' href='/ho-so/lap-danh-sach/danh-sach-de-nghi/" + ConvertString(dataget[i].ptc) + "' >" + formatDate(dataget[i].c3) + "</a></td>";
                        }

                    } else {
                        if (dataget[i].report_type == 'NGNA') {
                            if (dataget[i].ptc != null) {
                                html_show += "<td class='text-center'><a class='tooltip-toggle' data-tooltip='Công văn: " + ConvertString(dataget[i].truong) + "' href='/ho-so/lap-danh-sach/danh-sach-de-nghi/" + ConvertString(dataget[i].ptc) + "' ><i class='fa fa-eye' aria-hidden='true'></i></a></td>";
                            } else {
                                html_show += "<td class='text-center'><a class='tooltip-toggle' data-tooltip='Công văn: " + ConvertString(dataget[i].truong) + "' >-</a></td>";
                            }
                        } else {
                            html_show += "<td class='text-center'><a class='tooltip-toggle' data-tooltip='Công văn: " + ConvertString(dataget[i].truong) + "' href='/ho-so/lap-danh-sach/danh-sach-de-nghi/" + ConvertString(dataget[i].ptc) + "' >" + formatDate(dataget[i].c3) + "</a></td>";
                        }

                    }

                    if (parseInt(dataget[i].report_cap_status_3) == 0) {
                        if ((parseInt(datas.level) == parseInt(dataget[i].nhan_1) && dataget[i].nhan_2 == null) || parseInt(datas.level) == parseInt(dataget[i].nhan_2)) {
                            html_show += "<td class='text-center'><a class='btn btn-block btn-warning btn-xs' title='Xin thu hồi' onclick='thuhoi_cv(\"" + ConvertString(dataget[i].ptc) + "\")' href='javascript:void(0)'>Chờ xử lý</a></td>";
                        } else {
                            html_show += "<td class='text-center'><span class='primary' title='Đang chờ xử lý'>Chờ xử lý</span></td>";
                        }
                    }
                    else if (parseInt(dataget[i].report_cap_status_3) == 1) {
                        if ((parseInt(datas.level) == parseInt(dataget[i].nhan_1) && dataget[i].nhan_2 == null) || parseInt(datas.level) == parseInt(dataget[i].nhan_2)) {
                            html_show += "<td class='text-center'><a class='btn btn-block btn-success btn-xs' title='Xin thu hồi' onclick='thuhoi_cv(\"" + ConvertString(dataget[i].ptc) + "\")' href='javascript:void(0)'>Đã xử lý</a></td>";
                        } else {
                            html_show += "<td class='text-center'><span class='success' title='Đã xử lý'>Đã xử lý</span></td>";
                        }
                        // html_show += "<td class='text-center'><span class='success'>Đã xử lý</span></td>";
                    }
                    else if (parseInt(dataget[i].report_cap_status_3) == 2) {
                        if ((parseInt(datas.level) == parseInt(dataget[i].nhan_1) && dataget[i].nhan_2 == null) || parseInt(datas.level) == parseInt(dataget[i].nhan_2)) {
                            html_show += "<td class='text-center'><a class='btn btn-block btn-danger btn-xs' title='Xác nhận hủy' onclick='huycongvan_tralai(\"" + ConvertString(dataget[i].ptc) + "\")' href='javascript:void(0)'>Trả lại</a></td>";
                        } else {
                            html_show += "<td class='text-center'><span class='danger'>Trả lại</span></td>";
                        }
                    } else if (parseInt(dataget[i].report_cap_status_3) == 3) {
                        html_show += "<td class='text-center'><span class='danger'>Thu hồi</span></td>";
                    } else if (parseInt(dataget[i].report_cap_status_3) == 4) {
                        if (parseInt(datas.level) == parseInt(dataget[i].nhan_2)) {
                            html_show += "<td class='text-center'><a class='btn btn-block btn-danger btn-xs' title='Xác nhận thu hồi' onclick='xacnhanthuhoi_cv(\"" + ConvertString(dataget[i].ptc) + "\")' href='javascript:void(0)'>Xin thu hồi</a></td>";
                        } else {
                            html_show += "<td class='text-center'><span class='danger'>Xin thu hồi</span></td>";
                        }
                    } else {
                        html_show += "<td class='text-center'>-</td>";
                    }
                    // Cấp sở
                    //if(loaicongvan == 1 || loaicongvan == 2 || loaicongvan == 5 || loaicongvan == 6 || loaicongvan == 7){
                    html_show += "<td class='text-center'><a class='tooltip-toggle' data-tooltip='Công văn: " + ConvertString(dataget[i].date_4) + "' href='/ho-so/lap-danh-sach/loc-danh-sach/8-" + ConvertString(dataget[i].stc) + "' >" + ConvertString(dataget[i].stc) + "</a></td>";
                    if (dataget[i].report_type == 'NGNA') {
                        if (dataget[i].stc != null) {
                            html_show += "<td class='text-center'><a class='tooltip-toggle' data-tooltip='Công văn: " + ConvertString(dataget[i].truong) + "' href='/ho-so/lap-danh-sach/danh-sach-de-nghi/" + ConvertString(dataget[i].stc) + "' ><i class='fa fa-eye' aria-hidden='true'></i></a></td>";
                        } else {
                            html_show += "<td class='text-center'><a class='tooltip-toggle' data-tooltip='Công văn: " + ConvertString(dataget[i].truong) + "' >-</a></td>";
                        }
                    } else {
                        html_show += "<td class='text-center'><a class='tooltip-toggle' data-tooltip='Công văn: " + ConvertString(dataget[i].stc) + "' href='/ho-so/lap-danh-sach/danh-sach-de-nghi/" + ConvertString(dataget[i].stc) + "' >" + formatDate(dataget[i].c4) + "</a></td>";
                    }


                    if (parseInt(dataget[i].report_cap_status_4) == 0) {
                        if (parseInt(datas.level) == parseInt(dataget[i].nhan_2)) {
                            html_show += "<td class='text-center'><a class='btn btn-block btn-warning btn-xs' title='Xin thu hồi' onclick='thuhoi_cv(\"" + ConvertString(dataget[i].stc) + "\")' href='javascript:void(0)'>Chờ xử lý</a></td>";
                        } else {
                            html_show += "<td class='text-center'><span class='primary' title='Đang chờ xử lý'>Chờ xử lý</span></td>";
                        }
                    }
                    else if (parseInt(dataget[i].report_cap_status_4) == 1) {
                        if (parseInt(datas.level) == parseInt(dataget[i].nhan_2)) {
                            html_show += "<td class='text-center'><a class='btn btn-block btn-success btn-xs' title='Thu hồi' onclick='thuhoi_cv(\"" + ConvertString(dataget[i].stc) + "\",1)' href='javascript:void(0)'>Đã duyệt</a></td>";
                        } else {
                            html_show += "<td class='text-center'><span class='success' title='Đã xử lý'>Đã duyệt</span></td>";
                        }
                        //html_show += "<td class='text-center'><span class='success'>Đã xử lý</span></td>";
                    }
                    else if (parseInt(dataget[i].report_cap_status_4) == 2) {
                        if (parseInt(datas.level) == parseInt(dataget[i].nhan_3)) {
                            html_show += "<td class='text-center'><a class='btn btn-block btn-danger btn-xs' title='Xác nhận hủy' onclick='huycongvan_tralai(\"" + ConvertString(dataget[i].stc) + "\")' href='javascript:void(0)'>Trả lại</a></td>";
                        } else {
                            html_show += "<td class='text-center'><span class='danger'>Trả lại</span></td>";
                        }

                    } else if (parseInt(dataget[i].report_cap_status_4) == 3) {

                        html_show += "<td class='text-center'><span class='danger'>Thu hồi</span></td>";
                    } else if (parseInt(dataget[i].report_cap_status_4) == 4) {
                        if (parseInt(datas.level) == parseInt(dataget[i].nhan_3)) {
                            html_show += "<td class='text-center'><a class='btn btn-block btn-danger btn-xs' title='Xác nhận thu hồi' onclick='xacnhanthuhoi_cv(\"" + ConvertString(dataget[i].stc) + "\")' href='javascript:void(0)'>Xin thu hồi</a></td>";
                        } else {
                            html_show += "<td class='text-center'><span class='danger'>Xin thu hồi</span></td>";
                        }
                        //html_show += "<td class='text-center'><span class='danger'>Xin thu hồi</span></td>";
                    } else {
                        html_show += "<td class='text-center'>-</td>";
                    }
                    // }
                    html_show += "</tr>";
                }
            }
            else {

                html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
            }

            $('#tbListDanhSach').html(html_show);
        }, function (datas) {
            console.log("loadDanhSachByPhongSo");
            console.log(datas);
        }, "", "", "");
}

function clickLoadShool(schoolid) {
    $('#drSchoolTHCD').val(schoolid).change();
}

function clickLoadShoolAndCongvan(schoolid, loaicongvan, socongvan) {
    $('#drSchoolTHCD').val(schoolid).change();
    $('#sltLoaiCongvan').val(loaicongvan).change();
    $('#sltCongvan').val(socongvan).change();
}
function active_NGNA(value, socongvan = "", id) {
    GetFromServer('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/active_ngna?id=' + id + '&socongvan=' + socongvan + '&value=' + value, function (dataget) {
        GET_INITIAL_NGHILC();
        if (dataget.success != null || dataget.success != undefined) {
            utility.message("Thông báo", dataget.success, null, 3000);

        } else if (dataget.error != null || dataget.error != undefined) {
            utility.message("Thông báo", dataget.error, null, 3000, 1);
        }
        loadDanhSachHSDaLap(10, "", null, null);
    }, function (result) {
        console.log("active_NGNA");
        console.log(result);
    }, "", "", "");
}
// Use - Danh sách chi tiết các công văn của cấp trên
function loadDanhSachHSDaLap(row, keySearch = "", type = null, order = null) {
    $('#tbListDanhSach').html("<tr><td colspan='50' class='text-center'>Đang tải dữ liệu</td></tr>");
    var schools_id = $('#drSchoolTHCD').val();
    var loaicongvan = $('#sltLoaiCongvan').val();
    var socongvan = $('#sltCongvan').val();

    var number = 3;

    var o = {
        start: (GET_START_RECORD_NGHILC()),
        limit: row,
        SCHOOLID: schools_id,
        LOAICONGVAN: loaicongvan,
        SOCONGVAN: socongvan,
        KEY: keySearch,
        TYPE: type,
        ORDER: order
    };
    PostToServer('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/loadDanhSachHSDaLap', o,
        function (datas) {
            SETUP_PAGING_NGHILC(datas, function () {
                loadDanhSachHSDaLap(row, keySearch, type, order);
            });

            loadLoaiCongVan(loaicongvan, parseInt(datas.LEVELUSER), parseInt(datas.LEVELVIEW));
            var dataget = datas.data;
            var or = 0;
            if (order == 0 || order == null) {
                or = 1;
            } else {
                or = 0;
            }
            var html_header = '';
            html_header += '<tr class="success">';
            html_header += '<th  class="text-center" rowspan="2" style="vertical-align:middle;width: 3%">STT</th>';
            html_header += '<th  class="text-center class-pointer" rowspan="2" style="vertical-align:middle;width: 10%"><span  onclick="GET_INITIAL_NGHILC();loadDanhSachHSDaLap(' + row + ',\'' + keySearch + '\',14,' + or + ');">Tên học sinh</span></th>';
            html_header += '<th  class="text-center" rowspan="2" style="vertical-align:middle;width: 5%">Ngày sinh</th>';

            html_header += '<th  class="text-center class-pointer" rowspan="2" style="vertical-align:middle;width: 5%"><span  onclick="GET_INITIAL_NGHILC();loadDanhSachHSDaLap(' + row + ',\'' + keySearch + '\',13,' + or + ');">Lớp học</span></th>';
            html_header += '<th  class="text-center" colspan="11" style="vertical-align:middle">Hỗ trợ</th>';

            html_header += '<th  class="text-center class-pointer" rowspan="2" style="vertical-align:middle"><span  onclick="GET_INITIAL_NGHILC();loadDanhSachHSDaLap(' + row + ',\'' + keySearch + '\',12,' + or + ');">Tổng tiền</span></th>';

            if (parseInt(datas.LEVELUSER) != parseInt(datas.LEVELVIEW) || parseInt($('#sltLoaiCongvan').val()) == 2 || parseInt(datas.TYPE) == 2) {
                html_header += '<th  class="text-center" rowspan="2" style="vertical-align:middle">Thông tin</th>';
            } else {
                if (!datas.LEVELSEND || parseInt(loaicongvan) == 3) {
                    html_header += '<th  class="text-center" rowspan="2" style="vertical-align:middle">Thông tin</th>';
                } else {
                    html_header += '<th  class="text-center" rowspan="2" style="vertical-align:middle"><button type="button" title="Duyệt tất cả" onclick="approvedAllChedoPhongSo(\'' + socongvan + '\')" style="font-size: 11px !important" class="btn btn-success" id ="" title="Duyệt danh sách"><i class="glyphicon glyphicon-ok"></i></button></th>';
                    html_header += '<th  class="text-center" rowspan="2" style="vertical-align:middle"><button type="button" title="Trả lại tất cả" onclick="revertedAllChedoPhongSo(\'' + socongvan + '\')" style="font-size: 11px !important" class="btn btn-danger" id ="" title="Duyệt trả lại"><i class="glyphicon glyphicon-share-alt"></i></button></th>';
                }
            }
            html_header += '</tr>';

            html_header += '<tr class="success">';
            html_header += '<th  class="text-center class-pointer" style="vertical-align:middle;width: 5%"><span  onclick="GET_INITIAL_NGHILC();loadDanhSachHSDaLap(' + row + ',\'' + keySearch + '\',1,' + or + ');">MGHP</span></th>';
            html_header += '<th  class="text-center class-pointer" style="vertical-align:middle;width: 5%"><span  onclick="GET_INITIAL_NGHILC();loadDanhSachHSDaLap(' + row + ',\'' + keySearch + '\',2,' + or + ');">CPHT</span></th>';
            html_header += '<th  class="text-center class-pointer" style="vertical-align:middle;width: 6%"><span  onclick="GET_INITIAL_NGHILC();loadDanhSachHSDaLap(' + row + ',\'' + keySearch + '\',3,' + or + ');">AT TEMG</span></th>';
            html_header += '<th  class="text-center class-pointer" style="vertical-align:middle;width: 5%"><span  onclick="GET_INITIAL_NGHILC();loadDanhSachHSDaLap(' + row + ',\'' + keySearch + '\',4,' + or + ');">BT TA</span></th>';
            html_header += '<th  class="text-center class-pointer" style="vertical-align:middle;width: 5%"><span  onclick="GET_INITIAL_NGHILC();loadDanhSachHSDaLap(' + row + ',\'' + keySearch + '\',5,' + or + ');">BT TO</span></th>';
            html_header += '<th  class="text-center class-pointer" style="vertical-align:middle;width: 6%"><span  onclick="GET_INITIAL_NGHILC();loadDanhSachHSDaLap(' + row + ',\'' + keySearch + '\',6,' + or + ');">BT VHTT</span></th>';
            html_header += '<th  class="text-center class-pointer" style="vertical-align:middle;width: 5%"><span  onclick="GET_INITIAL_NGHILC();loadDanhSachHSDaLap(' + row + ',\'' + keySearch + '\',7,' + or + ');">TA HS</span></th>';
            html_header += '<th  class="text-center class-pointer" style="vertical-align:middle;width: 7%"><span  onclick="GET_INITIAL_NGHILC();loadDanhSachHSDaLap(' + row + ',\'' + keySearch + '\',8,' + or + ');">HSKT HB</span></th>';
            html_header += '<th  class="text-center class-pointer" style="vertical-align:middle;width: 7%"><span  onclick="GET_INITIAL_NGHILC();loadDanhSachHSDaLap(' + row + ',\'' + keySearch + '\',9,' + or + ');">HSKT DDHT</span></th>';
            html_header += '<th  class="text-center class-pointer" style="vertical-align:middle;width: 7%"><span  onclick="GET_INITIAL_NGHILC();loadDanhSachHSDaLap(' + row + ',\'' + keySearch + '\',10,' + or + ');">HB HSDTNT</span></th>';
            html_header += '<th  class="text-center class-pointer" style="vertical-align:middle;width: 6%"><span  onclick="GET_INITIAL_NGHILC();loadDanhSachHSDaLap(' + row + ',\'' + keySearch + '\',11,' + or + ');">HSDTTS</span></th>';
            html_header += '</tr>';
            var html_header_ngna = '';
            html_header_ngna += '<tr class="success">';
            html_header_ngna += '<th class="text-center" rowspan="2" style="vertical-align:middle">Năm học</th>';
            html_header_ngna += '<th class="text-center" colspan="3" style="vertical-align:middle">Số học sinh được tính hỗ trợ NVDD</th>';
            html_header_ngna += '<th class="text-center" colspan="3" style="vertical-align:middle">Số nhân viên DD</th>';
            html_header_ngna += '<th class="text-center" colspan="4" style="vertical-align:middle">Nhu cầu kinh phí</th>';
            if (parseInt(datas.LEVELUSER) != parseInt(datas.LEVELVIEW) || parseInt($('#sltLoaiCongvan').val()) == 2 || parseInt(datas.TYPE) == 2) {
                html_header_ngna += '<th  class="text-center" rowspan="2" style="vertical-align:middle">Thông tin</th>';
            } else {
                if (!datas.LEVELSEND || parseInt(loaicongvan) == 3) {
                    html_header_ngna += '<th  class="text-center" rowspan="2" style="vertical-align:middle">Thông tin</th>';
                } else {
                    html_header_ngna += '<th  class="text-center" rowspan="2" style="vertical-align:middle"><i class="glyphicon glyphicon-ok"></i> Duyệt</th>';
                    html_header_ngna += '<th  class="text-center" rowspan="2" style="vertical-align:middle"><i class="glyphicon glyphicon-share-alt"></i> Trả lại</th>';
                }
            }
            html_header_ngna += '</tr><tr class="success">';
            html_header_ngna += '<th class="text-center" style="vertical-align:middle">Tổng số</th>';
            html_header_ngna += '<th class="text-center" style="vertical-align:middle">Sử dụng KPTW</th>';
            html_header_ngna += '<th class="text-center" style="vertical-align:middle">Sử dụng KPĐP</th>';

            html_header_ngna += '<th class="text-center" style="vertical-align:middle">Tổng số</th>';
            html_header_ngna += '<th class="text-center" style="vertical-align:middle">TW</th>';
            html_header_ngna += '<th class="text-center" style="vertical-align:middle">ĐP</th>';
            html_header_ngna += '<th class="text-center" style="vertical-align:middle">HĐ 68</th>';

            html_header_ngna += '<th class="text-center" style="vertical-align:middle">TW</th>';
            html_header_ngna += '<th class="text-center" style="vertical-align:middle">ĐP</th>';
            html_header_ngna += '<th class="text-center" style="vertical-align:middle">Tổng</th>';
            html_header_ngna += '</tr>';
            if (dataget.length > 0) {
                if (parseInt(loaicongvan) == 1 && parseInt(datas.TYPE) != 2) {
                    var html_show = "";
                    for (var i = 0; i < dataget.length; i++) {
                        if (parseInt(datas.NGNA) == 1) {
                            html_show += "<tr><td class='text-center'>" + ConvertString(dataget[i].hsbc_HK) + "</td>";
                            html_show += "<td class='text-right'>" + ConvertString(dataget[i].hsbc_amount) + "</td>";
                            html_show += "<td class='text-right'>" + ConvertString(dataget[i].hsbc_HSTW) + "</td>";
                            html_show += "<td class='text-right'>" + ConvertString(dataget[i].hsbc_HSDP) + "</td>";
                            html_show += "<td class='text-right'>" + ConvertString(dataget[i].hsbc_Total) + "</td>";
                            html_show += "<td class='text-right'>" + ConvertString(dataget[i].hsbc_TW) + "</td>";
                            html_show += "<td class='text-right'>" + ConvertString(dataget[i].hsbc_DP) + "</td>";
                            html_show += "<td class='text-right'>" + ConvertString(dataget[i].hsbc_68) + "</td>";

                            html_show += "<td class='text-right'>" + formatter(dataget[i].hsbc_amount_TW) + "</td>";
                            html_show += "<td class='text-right'>" + formatter(dataget[i].hsbc_amount_DP) + "</td>";
                            html_show += "<td class='text-right'>" + formatter(convertNumber(dataget[i].hsbc_amount_TW) + convertNumber(dataget[i].hsbc_amount_DP)) + "</td>";
                            if ((parseInt(datas.LEVELUSER) != parseInt(datas.LEVELVIEW)) || !datas.LEVELSEND) {
                                if (convertNumber(dataget[i].hsbc_status) == 0) {
                                    html_show += "<td class='text-center' style='vertical-align:middle'>Chưa duyệt</td>";
                                } else {
                                    html_show += "<td class='text-center' style='vertical-align:middle'>Xet</td>";
                                }

                            } else {
                                if (parseInt(dataget[i].hsbc_active) === 1) {
                                    html_show += "<td class='text-center' style='vertical-align:middle'>";
                                    html_show += "<button class='btn btn-success btn-xs' onclick='active_NGNA(0,\"" + dataget[i].report_name + "\"," + dataget[i].hsbc_id + ")'  title='Đã xử lý'> <i class='glyphicon glyphicon-check'></i></button> ";
                                    html_show += "</td>";
                                } else {
                                    html_show += "<td class='text-center' style='vertical-align:middle'>";
                                    html_show += "<button class='btn btn-primary btn-xs' onclick='active_NGNA(1,\"" + dataget[i].report_name + "\"," + dataget[i].hsbc_id + ")' title='Chưa xử lý'> <i class='glyphicon glyphicon-unchecked'></i></button> ";
                                    html_show += "</td>";
                                }

                                if (parseInt(dataget[i].hsbc_active) === 2) {
                                    html_show += "<td class='text-center' style='vertical-align:middle'>"
                                    html_show += "<button class='btn btn-danger btn-xs' onclick='active_NGNA(0,\"" + dataget[i].report_name + "\"," + dataget[i].hsbc_id + ")' title='Đã xử lý'> <i class='glyphicon glyphicon-check'></i></button> ";
                                    html_show += "</td>";
                                }
                                else {
                                    html_show += "<td class='text-center' style='vertical-align:middle'>"
                                    html_show += "<button class='btn btn-primary btn-xs' onclick='active_NGNA(2,\"" + dataget[i].report_name + "\"," + dataget[i].hsbc_id + ")' title='Chưa xử lý'> <i class='glyphicon glyphicon-unchecked'></i></button> ";
                                    html_show += "</td>";
                                }
                            }
                        } else {
                            html_show += "<tr><td class='text-center'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";

                            html_show += "<td><a href='javascript:;' onclick='getHoSoHocSinh(" + parseInt(dataget[i].profile_id) + ");'>" + ConvertString(dataget[i].profile_name) + "</a></td>";

                            html_show += "<td class='text-center'>" + formatDates(dataget[i].profile_birthday) + "</td>";

                            html_show += "<td  class='text-center'>" + ConvertString(dataget[i].class_name) + "</td>";
                            if (parseInt(dataget[i].MGHP) > 0) {
                                if (parseInt(dataget[i].STATUS_MGHP) == 1 || parseInt(dataget[i].STATUS_MGHP) == 4) {
                                    html_show += "<td class='text-right' style='color: green' title='Đã được duyệt'>" + formatter(dataget[i].MGHP) + "</td>";
                                }
                                else if (parseInt(dataget[i].STATUS_MGHP) == 2 || parseInt(dataget[i].STATUS_MGHP) == 3) {
                                    html_show += "<td class='text-right' style='color: red' title='Đã trả về cấp dưới'>" + formatter(dataget[i].MGHP) + "</td>";
                                }
                                else {
                                    html_show += "<td class='text-right primary'  title='Chưa được duyệt'>" + formatter(dataget[i].MGHP) + "</td>";
                                }
                            }
                            else {
                                html_show += "<td class='text-right'>" + formatter(dataget[i].MGHP) + "</td>";
                            }

                            if (parseInt(dataget[i].CPHT) > 0) {
                                if (parseInt(dataget[i].STATUS_CPHT) == 1 || parseInt(dataget[i].STATUS_CPHT) == 4) {
                                    html_show += "<td class='text-right' style='color: green' title='Đã được duyệt'>" + formatter(dataget[i].CPHT) + "</td>";
                                }
                                else if (parseInt(dataget[i].STATUS_CPHT) == 2 || parseInt(dataget[i].STATUS_CPHT) == 3) {
                                    html_show += "<td class='text-right' style='color: red' title='Đã trả về cấp dưới'>" + formatter(dataget[i].CPHT) + "</td>";
                                }
                                else {
                                    html_show += "<td class='text-right primary'  title='Chưa được duyệt'>" + formatter(dataget[i].CPHT) + "</td>";
                                }
                            }
                            else {
                                html_show += "<td class='text-right'>" + formatter(dataget[i].CPHT) + "</td>";
                            }

                            if (parseInt(dataget[i].HTAT) > 0) {
                                if (parseInt(dataget[i].STATUS_HTAT) == 1 || parseInt(dataget[i].STATUS_HTAT) == 4) {
                                    html_show += "<td class='text-right' style='color: green' title='Đã được duyệt'>" + formatter(dataget[i].HTAT) + "</td>";
                                }
                                else if (parseInt(dataget[i].STATUS_HTAT) == 2 || parseInt(dataget[i].STATUS_HTAT) == 3) {
                                    html_show += "<td class='text-right' style='color: red' title='Đã trả về cấp dưới'>" + formatter(dataget[i].HTAT) + "</td>";
                                }
                                else {
                                    html_show += "<td class='text-right primary'  title='Chưa được duyệt'>" + formatter(dataget[i].HTAT) + "</td>";
                                }
                            }
                            else {
                                html_show += "<td class='text-right'>" + formatter(dataget[i].HTAT) + "</td>";
                            }

                            if (parseInt(dataget[i].HTBT_TA) > 0) {
                                if (parseInt(dataget[i].STATUS_HTBT_TA) == 1 || parseInt(dataget[i].STATUS_HTBT_TA) == 4) {
                                    html_show += "<td class='text-right' style='color: green' title='Đã được duyệt'>" + formatter(dataget[i].HTBT_TA) + "</td>";
                                }
                                else if (parseInt(dataget[i].STATUS_HTBT_TA) == 2 || parseInt(dataget[i].STATUS_HTBT_TA) == 3) {
                                    html_show += "<td class='text-right' style='color: red' title='Đã trả về cấp dưới'>" + formatter(dataget[i].HTBT_TA) + "</td>";
                                }
                                else {
                                    html_show += "<td class='text-right primary' title='Chưa được duyệt'>" + formatter(dataget[i].HTBT_TA) + "</td>";
                                }
                            }
                            else {
                                html_show += "<td class='text-right'>" + formatter(dataget[i].HTBT_TA) + "</td>";
                            }

                            if (parseInt(dataget[i].HTBT_TO) > 0) {
                                if (parseInt(dataget[i].STATUS_HTBT_TO) == 1 || parseInt(dataget[i].STATUS_HTBT_TO) == 4) {
                                    html_show += "<td class='text-right' style='color: green' title='Đã được duyệt'>" + formatter(dataget[i].HTBT_TO) + "</td>";
                                }
                                else if (parseInt(dataget[i].STATUS_HTBT_TO) == 2 || parseInt(dataget[i].STATUS_HTBT_TO) == 3) {
                                    html_show += "<td class='text-right' style='color: red' title='Đã trả về cấp dưới'>" + formatter(dataget[i].HTBT_TO) + "</td>";
                                }
                                else {
                                    html_show += "<td class='text-right primary' title='Chưa được duyệt'>" + formatter(dataget[i].HTBT_TO) + "</td>";
                                }
                            }
                            else {
                                html_show += "<td class='text-right'>" + formatter(dataget[i].HTBT_TO) + "</td>";
                            }

                            if (parseInt(dataget[i].HTBT_VHTT) > 0) {
                                if (parseInt(dataget[i].STATUS_HTBT_VHTT) == 1 || parseInt(dataget[i].STATUS_HTBT_VHTT) == 4) {
                                    html_show += "<td class='text-right' style='color: green' title='Đã được duyệt'>" + formatter(dataget[i].HTBT_VHTT) + "</td>";
                                }
                                else if (parseInt(dataget[i].STATUS_HTBT_VHTT) == 2 || parseInt(dataget[i].STATUS_HTBT_VHTT) == 3) {
                                    html_show += "<td class='text-right' style='color: red' title='Đã trả về cấp dưới'>" + formatter(dataget[i].HTBT_VHTT) + "</td>";
                                }
                                else {
                                    html_show += "<td class='text-right primary' title='Chưa được duyệt'>" + formatter(dataget[i].HTBT_VHTT) + "</td>";
                                }
                            }
                            else {
                                html_show += "<td class='text-right'>" + formatter(dataget[i].HTBT_VHTT) + "</td>";
                            }
                            if (parseInt(dataget[i].HTATHS) > 0) {
                                if (parseInt(dataget[i].STATUS_HTATHS) == 1 || parseInt(dataget[i].STATUS_HTATHS) == 4) {
                                    html_show += "<td class='text-right' style='color: green' title='Đã được duyệt'>" + formatter(dataget[i].HTATHS) + "</td>";
                                }
                                else if (parseInt(dataget[i].STATUS_HTATHS) == 2 || parseInt(dataget[i].STATUS_HTATHS) == 3) {
                                    html_show += "<td class='text-right' style='color: red' title='Đã trả về cấp dưới'>" + formatter(dataget[i].HTATHS) + "</td>";
                                }
                                else {
                                    html_show += "<td class='text-right primary' title='Chưa được duyệt'>" + formatter(dataget[i].HTATHS) + "</td>";
                                }
                            }
                            else {
                                html_show += "<td class='text-right'>" + formatter(dataget[i].HTATHS) + "</td>";
                            }
                            if (parseInt(dataget[i].HSKT_HB) > 0) {
                                if (parseInt(dataget[i].STATUS_HSKT_HB) == 1 || parseInt(dataget[i].STATUS_HSKT_HB) == 4) {
                                    html_show += "<td class='text-right' style='color: green' title='Đã được duyệt'>" + formatter(dataget[i].HSKT_HB) + "</td>";
                                }
                                else if (parseInt(dataget[i].STATUS_HSKT_HB) == 2 || parseInt(dataget[i].STATUS_HSKT_HB) == 3) {
                                    html_show += "<td class='text-right' style='color: red' title='Đã trả về cấp dưới'>" + formatter(dataget[i].HSKT_HB) + "</td>";
                                }
                                else {
                                    html_show += "<td class='text-right primary' title='Chưa được duyệt'>" + formatter(dataget[i].HSKT_HB) + "</td>";
                                }
                            }
                            else {
                                html_show += "<td class='text-right'>" + formatter(dataget[i].HSKT_HB) + "</td>";
                            }

                            if (parseInt(dataget[i].HSKT_DDHT) > 0) {
                                if (parseInt(dataget[i].STATUS_HSKT_DDHT) == 1 || parseInt(dataget[i].STATUS_HSKT_DDHT) == 4) {
                                    html_show += "<td class='text-right' style='color: green' title='Đã được duyệt'>" + formatter(dataget[i].HSKT_DDHT) + "</td>";
                                }
                                else if (parseInt(dataget[i].STATUS_HSKT_DDHT) == 2 || parseInt(dataget[i].STATUS_HSKT_DDHT) == 3) {
                                    html_show += "<td class='text-right' style='color: red' title='Đã trả về cấp dưới'>" + formatter(dataget[i].HSKT_DDHT) + "</td>";
                                }
                                else {
                                    html_show += "<td class='text-right primary' title='Chưa được duyệt'>" + formatter(dataget[i].HSKT_DDHT) + "</td>";
                                }
                            }
                            else {
                                html_show += "<td class='text-right'>" + formatter(dataget[i].HSKT_DDHT) + "</td>";
                            }
                            if (parseInt(dataget[i].HBHSDTNT) > 0) {
                                if (parseInt(dataget[i].STATUS_HBHSDTNT) == 1 || parseInt(dataget[i].STATUS_HBHSDTNT) == 4) {
                                    html_show += "<td class='text-right' style='color: green' title='Đã được duyệt'>" + formatter(dataget[i].HBHSDTNT) + "</td>";
                                }
                                else if (parseInt(dataget[i].STATUS_HBHSDTNT) == 2 || parseInt(dataget[i].STATUS_HBHSDTNT) == 3) {
                                    html_show += "<td class='text-right' style='color: red' title='Đã trả về cấp dưới'>" + formatter(dataget[i].HBHSDTNT) + "</td>";
                                }
                                else {
                                    html_show += "<td class='text-right primary' title='Chưa được duyệt'>" + formatter(dataget[i].HBHSDTNT) + "</td>";
                                }
                            }
                            else {
                                html_show += "<td class='text-right'>" + formatter(dataget[i].HBHSDTNT) + "</td>";
                            }
                            if (parseInt(dataget[i].HSDTTS) > 0) {
                                if (parseInt(dataget[i].STATUS_HSDTTS) == 1 || parseInt(dataget[i].STATUS_HSDTTS) == 4) {
                                    html_show += "<td class='text-right' style='color: green' title='Đã được duyệt'>" + formatter(dataget[i].HSDTTS) + "</td>";
                                }
                                else if (parseInt(dataget[i].STATUS_HSDTTS) == 2 || parseInt(dataget[i].STATUS_HSDTTS) == 3) {
                                    html_show += "<td class='text-right' style='color: red' title='Đã trả về cấp dưới'>" + formatter(dataget[i].HSDTTS) + "</td>";
                                }
                                else {
                                    html_show += "<td class='text-right primary' title='Chưa được duyệt'>" + formatter(dataget[i].HSDTTS) + "</td>";
                                }
                            }
                            else {
                                html_show += "<td class='text-right'>" + formatter(dataget[i].HSDTTS) + "</td>";
                            }


                            html_show += "<td class='text-right'><b>" + formatter(dataget[i].TONG) + "</b></td>";

                            if ((parseInt(datas.LEVELUSER) != parseInt(datas.LEVELVIEW)) || !datas.LEVELSEND) {
                                html_show += "<td class='text-center' style='vertical-align:middle'><button onclick='openPopupThongtinChedoPhongSo(" + dataget[i].profile_id + ", \"" + socongvan + "\", \"" + dataget[i].profile_name + "\")' class=' btn btn-info btn-xs' id='editor_editss' title='Thông tin chi tiết'><i class='glyphicon glyphicon-info-sign'></i> </button></td>";
                            } else {
                                if (parseInt(dataget[i].ACTIVE_MGHP) === 1 || parseInt(dataget[i].ACTIVE_CPHT) === 1 || parseInt(dataget[i].ACTIVE_HTAT) === 1 ||
                                    parseInt(dataget[i].ACTIVE_HTBT_TO) === 1 || parseInt(dataget[i].ACTIVE_HTBT_TA) === 1 ||
                                    parseInt(dataget[i].ACTIVE_HTBT_VHTT) === 1 || parseInt(dataget[i].ACTIVE_HTKT_HB) === 1 ||
                                    parseInt(dataget[i].ACTIVE_HTKT_DDHT) === 1 || parseInt(dataget[i].ACTIVE_HSDTTS) === 1 ||
                                    parseInt(dataget[i].ACTIVE_HTATHS) === 1 || parseInt(dataget[i].ACTIVE_HBHSDTNT) === 1) {
                                    html_show += "<td class='text-center' style='vertical-align:middle'>";
                                    html_show += "<button class='btn btn-success btn-xs' onclick='openPopupDuyetChedoPhongSo(" + dataget[i].profile_id + ", \"" + socongvan + "\", \"" + dataget[i].profile_name + "\")' title='Đã xử lý'> <i class='glyphicon glyphicon-check'></i></button> ";
                                    html_show += "</td>";
                                }
                                else {
                                    html_show += "<td class='text-center' style='vertical-align:middle'>";
                                    html_show += "<button class='btn btn-primary btn-xs' onclick='openPopupDuyetChedoPhongSo(" + dataget[i].profile_id + ", \"" + socongvan + "\", \"" + dataget[i].profile_name + "\")' title='Chưa xử lý'> <i class='glyphicon glyphicon-unchecked'></i></button> ";
                                    html_show += "</td>";
                                }

                                if (parseInt(dataget[i].ACTIVE_MGHP) === 2 || parseInt(dataget[i].ACTIVE_CPHT) === 2 || parseInt(dataget[i].ACTIVE_HTAT) === 2 ||
                                    parseInt(dataget[i].ACTIVE_HTBT_TO) === 2 || parseInt(dataget[i].ACTIVE_HTBT_TA) === 2 ||
                                    parseInt(dataget[i].ACTIVE_HTBT_VHTT) === 2 || parseInt(dataget[i].ACTIVE_HTKT_HB) === 2 ||
                                    parseInt(dataget[i].ACTIVE_HTKT_DDHT) === 2 || parseInt(dataget[i].ACTIVE_HSDTTS) === 2 ||
                                    parseInt(dataget[i].ACTIVE_HTATHS) === 2 || parseInt(dataget[i].ACTIVE_HBHSDTNT) === 2) {
                                    html_show += "<td class='text-center' style='vertical-align:middle'>"
                                    html_show += "<button class='btn btn-danger btn-xs' onclick='openPopupTralaiChedoPhongSo(" + dataget[i].profile_id + ", \"" + socongvan + "\", \"" + dataget[i].profile_name + "\")' title='Đã xử lý'> <i class='glyphicon glyphicon-check'></i></button> ";
                                    html_show += "</td>";
                                }
                                else {
                                    html_show += "<td class='text-center' style='vertical-align:middle'>"
                                    html_show += "<button class='btn btn-primary btn-xs' onclick='openPopupTralaiChedoPhongSo(" + dataget[i].profile_id + ", \"" + socongvan + "\", \"" + dataget[i].profile_name + "\")' title='Chưa xử lý'> <i class='glyphicon glyphicon-unchecked'></i></button> ";
                                    html_show += "</td>";
                                }
                            }
                        }
                        html_show += "</tr>";
                    }

                } else {
                    var html_show = "";
                    for (var i = 0; i < dataget.length; i++) {
                        if (parseInt(datas.NGNA) == 1) {
                            html_show += "<tr><td class='text-center'>" + ConvertString(dataget[i].hsbc_HK) + "</td>";
                            html_show += "<td class='text-right'>" + ConvertString(dataget[i].hsbc_amount) + "</td>";
                            html_show += "<td class='text-right'>" + ConvertString(dataget[i].hsbc_HSTW) + "</td>";
                            html_show += "<td class='text-right'>" + ConvertString(dataget[i].hsbc_HSDP) + "</td>";
                            html_show += "<td class='text-right'>" + ConvertString(dataget[i].hsbc_Total) + "</td>";
                            html_show += "<td class='text-right'>" + ConvertString(dataget[i].hsbc_TW) + "</td>";
                            html_show += "<td class='text-right'>" + ConvertString(dataget[i].hsbc_DP) + "</td>";
                            html_show += "<td class='text-right'>" + ConvertString(dataget[i].hsbc_68) + "</td>";

                            html_show += "<td class='text-right'>" + formatter(dataget[i].hsbc_amount_TW) + "</td>";
                            html_show += "<td class='text-right'>" + formatter(dataget[i].hsbc_amount_DP) + "</td>";
                            html_show += "<td class='text-right'>" + formatter(convertNumber(dataget[i].hsbc_amount_TW) + convertNumber(dataget[i].hsbc_amount_DP)) + "</td>";
                            html_show += "<td class='text-center' style='vertical-align:middle'>Trả lại</td>";
                        } else {
                            var totalMoney = 0;
                            var classa = '';
                            if (parseInt(dataget[i].rp_status) === 1 && parseInt(datas.TYPE) == 2) {
                                classa = ' blue ';
                            } else if (parseInt(dataget[i].rp_status) === 2 && parseInt(datas.TYPE) == 2) {
                                classa = ' red ';
                            }
                            totalMoney = parseInt(dataget[i].MGHP) + parseInt(dataget[i].CPHT) + parseInt(dataget[i].HTAT) + parseInt(dataget[i].HTBT_TA) + parseInt(dataget[i].HTBT_TO) + parseInt(dataget[i].HTBT_VHTT) + parseInt(dataget[i].HTATHS) + parseInt(dataget[i].HSKT_HB) + parseInt(dataget[i].HSKT_DDHT) + parseInt(dataget[i].HBHSDTNT) + parseInt(dataget[i].HSDTTS);

                            html_show += "<tr><td class='text-center' style='vertical-align:middle'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";

                            html_show += "<td class='text-center' style='vertical-align:middle'><a class='" + classa + "' href='javascript:;' onclick='getProfileSubById(" + parseInt(dataget[i].profile_id) + ", " + number + ");'>" + ConvertString(dataget[i].profile_name) + "</a></td>";

                            html_show += "<td class='text-center" + classa + "' style='vertical-align:middle'>" + formatDates(dataget[i].profile_birthday) + "</td>";

                            html_show += "<td class='text-center" + classa + "' style='vertical-align:middle'>" + ConvertString(dataget[i].class_name) + "</td>";
                            html_show += "<td class='text-right" + classa + "' style='vertical-align:middle'>" + formatter(dataget[i].MGHP) + "</td>";
                            html_show += "<td class='text-right" + classa + "' style='vertical-align:middle'>" + formatter(dataget[i].CPHT) + "</td>";
                            html_show += "<td class='text-right" + classa + "' style='vertical-align:middle'>" + formatter(dataget[i].HTAT) + "</td>";
                            html_show += "<td class='text-right" + classa + "' style='vertical-align:middle'>" + formatter(dataget[i].HTBT_TA) + "</td>";
                            html_show += "<td class='text-right" + classa + "' style='vertical-align:middle'>" + formatter(dataget[i].HTBT_TO) + "</td>";
                            html_show += "<td class='text-right" + classa + "' style='vertical-align:middle'>" + formatter(dataget[i].HTBT_VHTT) + "</td>";
                            html_show += "<td class='text-right" + classa + "' style='vertical-align:middle'>" + formatter(dataget[i].HTATHS) + "</td>";
                            html_show += "<td class='text-right" + classa + "' style='vertical-align:middle'>" + formatter(dataget[i].HSKT_HB) + "</td>";
                            html_show += "<td class='text-right" + classa + "' style='vertical-align:middle'>" + formatter(dataget[i].HSKT_DDHT) + "</td>";
                            html_show += "<td class='text-right" + classa + "' style='vertical-align:middle'>" + formatter(dataget[i].HBHSDTNT) + "</td>";
                            html_show += "<td class='text-right" + classa + "' style='vertical-align:middle'>" + formatter(dataget[i].HSDTTS) + "</td>";
                            html_show += "<td class='text-right" + classa + "' style='vertical-align:middle'><b>" + formatter(totalMoney) + "</b></td>";
                            html_show += "<td class='text-center' style='vertical-align:middle'><button onclick='openPopupThongtinChedoPhongSo(" + dataget[i].profile_id + ", \"" + socongvan + "\", \"" + dataget[i].profile_name + "\")' class='btn btn-info btn-xs' id='editor_editss'><i class='glyphicon glyphicon-info-sign'></i></button></td>";
                            html_show += "</tr>";
                        }
                    }

                }
            } else {
                html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
            }
            if (parseInt(datas.NGNA) == 1) {
                $('#cmisGridHeader').html(html_header_ngna);
            } else {
                $('#cmisGridHeader').html(html_header);
            }

            $('#tbListDanhSach').html(html_show);
        }, function (data) {
            console.log("loadDanhSachHSDaLap");
            console.log(data);
        }, "", "", "");
};
success_all = true;
unsuccess_all = true;
callback_all = true;
uncallback_all = true;
function approvedAllChedoPhongSo(socongvan) {
    var type = 1;
    if (success_all && unsuccess_all) {
        type = 0;
        success_all = false;
        unsuccess_all = false;
        callback_all = false;
        uncallback_all = false;
    } else {
        type = 1;
        success_all = true;
        unsuccess_all = true;
        callback_all = false;
        uncallback_all = false;
    }
    var o = { SOCONGVAN: socongvan, loai: type }
    PostToServer('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/approvedAllPhongSo', o, function (data) {
        if (data['success'] != "" && data['success'] != undefined) {
            utility.message("Thông báo", data['success'], null, 3000);
            GET_INITIAL_NGHILC();
            loadDanhSachHSDaLap($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfilePhongSo').val());
        }
        if (data['error'] != "" && data['error'] != undefined) {
            GET_INITIAL_NGHILC();
            loadDanhSachHSDaLap($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfilePhongSo').val());
            utility.message("Thông báo", data['error'], null, 3000, 2);
        }
    }, function (data) {
        console.log("approvedAllChedoPhongSo");
        console.log(data);
    }, "", "loading", "");
}

function revertedAllChedoPhongSo(socongvan) {
    var type = 1;
    if (callback_all && uncallback_all) {
        type = 0;
        callback_all = false;
        uncallback_all = false;
        success_all = false;
        unsuccess_all = false;
    } else {
        type = 2;
        callback_all = true;
        uncallback_all = true;
        success_all = false;
        unsuccess_all = false;
    }

    var o = { SOCONGVAN: socongvan, loai: type }
    PostToServer('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/revertedAllPhongSo', o, function (data) {
        if (data['success'] != "" && data['success'] != undefined) {
            utility.message("Thông báo", data['success'], null, 3000);
            GET_INITIAL_NGHILC();
            loadDanhSachHSDaLap($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfilePhongSo').val());
        }
        if (data['error'] != "" && data['error'] != undefined) {
            GET_INITIAL_NGHILC();
            loadDanhSachHSDaLap($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfilePhongSo').val());
            utility.message("Thông báo", data['error'], null, 3000, 2);
        }
    }, function (data) {
        console.log("revertedAllChedoPhongSo");
        console.log(data);
    }, "", "loading", "");

}

function reloadAllChedoPhongSo(socongvan) {
    var o = { SOCONGVAN: socongvan }
    PostToServer('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/reloadAllPhongSo', o, function (data) {
        if (data['success'] != "" && data['success'] != undefined) {
            utility.message("Thông báo", data['success'], null, 3000);
            GET_INITIAL_NGHILC();
            loadDanhSachHSDaLap($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfilePhongSo').val());
        }
        if (data['error'] != "" && data['error'] != undefined) {
            GET_INITIAL_NGHILC();
            loadDanhSachHSDaLap($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfilePhongSo').val());
            utility.message("Thông báo", data['error'], null, 3000, 2);
        }
    }, function (data) {
        console.log("reloadAllChedoPhongSo");
        console.log(data);
    }, "", "loading", "");

}

function openPopupDuyetChedoPhongSo(id, socongvan, name = "") {

    _idProfile = id;
    var objJson = JSON.stringify({ PROFILEID: id, SOCONGVAN: socongvan });

    $('label#txtProfileName').html(name);
    $('#txtGhiChuTHCD').val('');
    $('#txtNoteProfileCheDo').html('');
    GetFromServer('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/getProfileSubjectByIdPhongSo/' + objJson, function (data) {
        var html_show = "";

        var groupId = 0;
        if (data !== null && data !== "") {
            if (data['SUBJECT'].length > 0) {
                for (var i = 0; i < data['SUBJECT'].length; i++) {
                    var arrSub = [];
                    arrSub = data['SUBJECT'][i].bckp_danhsach_chedo.split(';');

                    for (var j = 0; j < arrSub.length; j++) {
                        var arrSubitem = [];
                        arrSubitem = arrSub[j].split('|');
                        if ((arrSubitem[0] !== null && arrSubitem[0] !== '' && arrSubitem[0] !== undefined)
                            && (arrSubitem[1] !== null && arrSubitem[1] !== '' && arrSubitem[1] !== undefined)) {
                            html_show += "<tr>";
                            html_show += "<td class='text-center'>" + (j + 1) + "</td>";
                            if ((parseInt(arrSubitem[0]) === 89 || parseInt(arrSubitem[0]) === 90 || parseInt(arrSubitem[0]) === 91)) {
                                var STATUS_MGHP = " id='chilApproveCheck' ";
                                if (parseInt(data['SUBJECT'][0]['STATUS_MGHP']) == 3) {
                                    STATUS_MGHP = ' disabled ';
                                }
                                if (parseInt(data['SUBJECT'][0]['MGHP']) === 1 && (parseInt(data['SUBJECT'][0]['STATUS_MGHP']) === 1 || parseInt(data['SUBJECT'][0]['STATUS_MGHP']) === 4)) {
                                    STATUS_MGHP += ' checked ';
                                }
                                html_show += "<td class='text-center'><input type='checkbox' " + STATUS_MGHP + "  name='choose' value='" + arrSubitem[0] + "' ></td>";
                            } else if ((parseInt(arrSubitem[0]) === 92)) {
                                var STATUS_CPHT = " id='chilApproveCheck' ";
                                if (parseInt(data['SUBJECT'][0]['STATUS_CPHT']) == 3) {
                                    STATUS_CPHT = ' disabled ';
                                }
                                if (parseInt(data['SUBJECT'][0]['CPHT']) === 1 && (parseInt(data['SUBJECT'][0]['STATUS_CPHT']) === 1 || parseInt(data['SUBJECT'][0]['STATUS_CPHT']) === 4)) {
                                    STATUS_CPHT += ' checked ';
                                }
                                html_show += "<td class='text-center'><input type='checkbox' " + STATUS_CPHT + "  name='choose' value='" + arrSubitem[0] + "' ></td>";
                            } else if ((parseInt(arrSubitem[0]) === 93)) {
                                var STATUS_HTAT = " id='chilApproveCheck' ";
                                if (parseInt(data['SUBJECT'][0]['STATUS_HTAT']) == 3) {
                                    STATUS_HTAT = ' disabled ';
                                }
                                if (parseInt(data['SUBJECT'][0]['HTAT']) === 1 && (parseInt(data['SUBJECT'][0]['STATUS_HTAT']) === 1 || parseInt(data['SUBJECT'][0]['STATUS_HTAT']) === 4)) {
                                    STATUS_HTAT += ' checked ';
                                }
                                html_show += "<td class='text-center'><input type='checkbox' " + STATUS_HTAT + "  name='choose' value='" + arrSubitem[0] + "' ></td>";
                            } else if ((parseInt(arrSubitem[0]) === 94)) {
                                var STATUS_HTBT = " id='chilApproveCheck' ";
                                if (parseInt(data['SUBJECT'][0]['STATUS_HTBT_TA']) == 3) {
                                    STATUS_HTBT = ' disabled ';
                                }
                                if (parseInt(data['SUBJECT'][0]['HTBT_TA']) === 1 && (parseInt(data['SUBJECT'][0]['STATUS_HTBT_TA']) === 1 || parseInt(data['SUBJECT'][0]['STATUS_HTBT_TA']) === 4)) {
                                    STATUS_HTBT += ' checked ';
                                }
                                html_show += "<td class='text-center'><input type='checkbox' " + STATUS_HTBT + "  name='choose' value='" + arrSubitem[0] + "' ></td>";
                            } else if ((parseInt(arrSubitem[0]) === 98)) {
                                var STATUS_HTBT = " id='chilApproveCheck' ";
                                if (parseInt(data['SUBJECT'][0]['STATUS_HTBT_TO']) == 3) {
                                    STATUS_HTBT = ' disabled ';
                                }
                                if (parseInt(data['SUBJECT'][0]['HTBT_TO']) === 1 && (parseInt(data['SUBJECT'][0]['STATUS_HTBT_TO']) === 1 || parseInt(data['SUBJECT'][0]['STATUS_HTBT_TO']) === 4)) {
                                    STATUS_HTBT += ' checked ';
                                }
                                html_show += "<td class='text-center'><input type='checkbox' " + STATUS_HTBT + "  name='choose' value='" + arrSubitem[0] + "' ></td>";
                            } else if ((parseInt(arrSubitem[0]) === 115)) {
                                var STATUS_HTBT = "  id='chilApproveCheck' ";
                                if (parseInt(data['SUBJECT'][0]['STATUS_HTBT_VHTT']) == 3) {
                                    STATUS_HTBT = ' disabled ';
                                }
                                if (parseInt(data['SUBJECT'][0]['HTBT_VHTT']) === 1 && (parseInt(data['SUBJECT'][0]['STATUS_HTBT_VHTT']) === 1 || parseInt(data['SUBJECT'][0]['STATUS_HTBT_VHTT']) === 4)) {
                                    STATUS_HTBT += ' checked ';
                                }
                                html_show += "<td class='text-center'><input type='checkbox' " + STATUS_HTBT + " name='choose' value='" + arrSubitem[0] + "' ></td>";
                            } else if ((parseInt(arrSubitem[0]) === 95)) {
                                var STATUS_HSKT = " id='chilApproveCheck' ";
                                if (parseInt(data['SUBJECT'][0]['STATUS_HSKT_HB']) == 3) {
                                    STATUS_HSKT = ' disabled ';
                                }
                                if (parseInt(data['SUBJECT'][0]['HSKT_HB']) === 1 && (parseInt(data['SUBJECT'][0]['STATUS_HSKT_HB']) === 1 || parseInt(data['SUBJECT'][0]['STATUS_HSKT_HB']) === 4)) {
                                    STATUS_HSKT += ' checked ';
                                }
                                html_show += "<td class='text-center'><input type='checkbox' " + STATUS_HSKT + "  name='choose' value='" + arrSubitem[0] + "' ></td>";
                            } else if ((parseInt(arrSubitem[0]) === 100)) {
                                var STATUS_HSKT = " id='chilApproveCheck' ";
                                if (parseInt(data['SUBJECT'][0]['STATUS_HSKT_DDHT']) == 3) {
                                    STATUS_HSKT = ' disabled ';
                                }
                                if (parseInt(data['SUBJECT'][0]['HSKT_DDHT']) === 1 && (parseInt(data['SUBJECT'][0]['STATUS_HSKT_DDHT']) === 1 || parseInt(data['SUBJECT'][0]['STATUS_HSKT_DDHT']) === 4)) {
                                    STATUS_HSKT += ' checked ';
                                }
                                html_show += "<td class='text-center'><input type='checkbox' " + STATUS_HSKT + "  name='choose' value='" + arrSubitem[0] + "'></td>";
                            } else if ((parseInt(arrSubitem[0]) === 99)) {
                                var STATUS_HSDTTS = " id='chilApproveCheck' ";
                                if (parseInt(data['SUBJECT'][0]['STATUS_HSDTTS']) == 3) {
                                    STATUS_HSDTTS = ' disabled ';
                                }
                                if (parseInt(data['SUBJECT'][0]['HSDTTS']) === 1 && (parseInt(data['SUBJECT'][0]['STATUS_HSDTTS']) === 1 || parseInt(data['SUBJECT'][0]['STATUS_HSDTTS']) === 4)) {
                                    STATUS_HSDTTS += ' checked ';
                                }
                                html_show += "<td class='text-center'><input type='checkbox' " + STATUS_HSDTTS + "  name='choose' value='" + arrSubitem[0] + "'></td>";
                            } else if ((parseInt(arrSubitem[0]) === 118)) {
                                var STATUS_HTATHS = " id='chilApproveCheck' ";
                                if (parseInt(data['SUBJECT'][0]['STATUS_HTATHS']) == 3) {
                                    STATUS_HTATHS = ' disabled ';
                                }
                                if (parseInt(data['SUBJECT'][0]['HTATHS']) === 1 && (parseInt(data['SUBJECT'][0]['STATUS_HTATHS']) === 1 || parseInt(data['SUBJECT'][0]['STATUS_HTATHS']) === 4)) {
                                    STATUS_HTATHS += ' checked ';
                                }
                                html_show += "<td class='text-center'><input type='checkbox' " + STATUS_HTATHS + "  name='choose' value='" + arrSubitem[0] + "' ></td>";
                            } else if ((parseInt(arrSubitem[0]) === 119)) {
                                var STATUS_HBHSDTNT = " id='chilApproveCheck' ";
                                if (parseInt(data['SUBJECT'][0]['STATUS_HBHSDTNT']) == 3) {
                                    STATUS_HBHSDTNT = ' disabled ';
                                }
                                if (parseInt(data['SUBJECT'][0]['HBHSDTNT']) === 1 && (parseInt(data['SUBJECT'][0]['STATUS_HBHSDTNT']) === 1 || parseInt(data['SUBJECT'][0]['STATUS_HBHSDTNT']) === 4)) {
                                    STATUS_HBHSDTNT += ' checked ';
                                }
                                html_show += "<td class='text-center'><input type='checkbox' " + STATUS_HBHSDTNT + "  name='choose' value='" + arrSubitem[0] + "' ></td>";
                            }
                            else {
                                html_show += "<td class='text-center'><input type='checkbox' id='chilApproveCheck' name='choose' value='" + arrSubitem[0] + "'></td>";
                            }

                            html_show += "<td>" + ConvertString(arrSubitem[1]) + "</td>";
                            html_show += "<td>" + ConvertString(arrSubitem[2]) + "</td>";
                            html_show += "</tr>";
                        }
                    }
                }

                if (parseInt(data['SUBJECT'][0]['MGHP']) === 1 || parseInt(data['SUBJECT'][0]['CPHT']) === 1 ||
                    parseInt(data['SUBJECT'][0]['HTAT']) === 1 || parseInt(data['SUBJECT'][0]['STATUS_HTBT_TA']) === 1 ||
                    parseInt(data['SUBJECT'][0]['HTBT_TO']) === 1 || parseInt(data['SUBJECT'][0]['HTBT_VHTT']) === 4 ||
                    parseInt(data['SUBJECT'][0]['HSKT_HB']) === 1 || parseInt(data['SUBJECT'][0]['HSKT_DDHT']) === 4 ||
                    parseInt(data['SUBJECT'][0]['HSDTTS']) === 1 || parseInt(data['SUBJECT'][0]['HTATHS']) === 4 ||
                    parseInt(data['SUBJECT'][0]['HBHSDTNT']) === 1) {
                    $('#checkedAllApproved').prop('checked', true);
                }
                else {
                    $('#checkedAllApproved').prop('checked', false);
                }
            }
        }

        $('#txtNoteProfileCheDo').html('Nội dung: <b>' + ConvertString(data['SUBJECT'][0]['bckp_ghichu']) + '</b>');
        $('#txtGhiChuTHCD').val(ConvertString(data['SUBJECT'][0]['bckp_traloi']));
        $('#dataListApproveCheDo').html(html_show);
        $("#myModalApproved").modal("show");
    }, function (data) {
        console.log("openPopupDuyetChedoPhongSo");
        console.log(data);
    }, "", "loading", "");

}
//use
function openPopupTralaiChedoPhongSo(id, socongvan, name = "") {

    _idProfile = id;
    var objJson = JSON.stringify({ PROFILEID: id, SOCONGVAN: socongvan });
    // console.log(objJson);
    $('label#txtProfileNameTraLai').html(name);
    $('#txtGhiChuTHCDTraLai').val('');
    GetFromServer('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/getProfileSubjectByIdPhongSo/' + objJson, function (data) {
        var html_show = "";

        var groupId = 0;

        if (data !== null && data !== "") {
            if (data['SUBJECT'].length > 0) {
                for (var i = 0; i < data['SUBJECT'].length; i++) {
                    var arrSub = [];
                    arrSub = data['SUBJECT'][i].bckp_danhsach_chedo.split(';');
                    for (var j = 0; j < arrSub.length; j++) {
                        var arrSubitem = [];
                        arrSubitem = arrSub[j].split('|');

                        if ((arrSubitem[0] !== null && arrSubitem[0] !== '' && arrSubitem[0] !== undefined)
                            && (arrSubitem[1] !== null && arrSubitem[1] !== '' && arrSubitem[1] !== undefined)) {
                            html_show += "<tr>";
                            html_show += "<td class='text-center'>" + (j + 1) + "</td>";
                            if ((parseInt(arrSubitem[0]) === 89 || parseInt(arrSubitem[0]) === 90 || parseInt(arrSubitem[0]) === 91)
                                && (parseInt(data['SUBJECT'][0]['STATUS_MGHP']) != 0)) {
                                var STATUS_MGHP = " id='chilRevertCheck' ";
                                if (parseInt(data['SUBJECT'][0]['STATUS_MGHP']) == 4) {
                                    STATUS_MGHP = ' disabled ';
                                }
                                if (parseInt(data['SUBJECT'][0]['MGHP']) === 2 && (parseInt(data['SUBJECT'][0]['STATUS_MGHP']) === 2 || parseInt(data['SUBJECT'][0]['STATUS_MGHP']) === 3)) {
                                    STATUS_MGHP += ' checked ';
                                }
                                html_show += "<td class='text-center'><input type='checkbox' " + STATUS_MGHP + "  name='choose' value='" + arrSubitem[0] + "'></td>";
                            }
                            else if ((parseInt(arrSubitem[0]) === 92) && (parseInt(data['SUBJECT'][0]['STATUS_CPHT']) != 0)) {
                                var STATUS_CPHT = " id='chilRevertCheck' ";
                                if (parseInt(data['SUBJECT'][0]['STATUS_CPHT']) == 4) {
                                    STATUS_CPHT = ' disabled ';
                                }
                                if (parseInt(data['SUBJECT'][0]['CPHT']) === 2 && (parseInt(data['SUBJECT'][0]['STATUS_CPHT']) === 2 || parseInt(data['SUBJECT'][0]['STATUS_CPHT']) === 3)) {
                                    STATUS_CPHT += ' checked ';
                                }
                                html_show += "<td class='text-center'><input type='checkbox' " + STATUS_CPHT + "  name='choose' value='" + arrSubitem[0] + "'></td>";
                            }
                            else if ((parseInt(arrSubitem[0]) === 93) && (parseInt(data['SUBJECT'][0]['STATUS_HTAT']) != 0)) {
                                var STATUS_HTAT = " id='chilRevertCheck' ";
                                if (parseInt(data['SUBJECT'][0]['STATUS_HTAT']) == 4) {
                                    STATUS_HTAT = ' disabled ';
                                }
                                if (parseInt(data['SUBJECT'][0]['HTAT']) === 2 && (parseInt(data['SUBJECT'][0]['STATUS_HTAT']) === 2 || parseInt(data['SUBJECT'][0]['STATUS_HTAT']) === 3)) {
                                    STATUS_HTAT += ' checked ';
                                }
                                html_show += "<td class='text-center'><input type='checkbox' " + STATUS_HTAT + "  name='choose' value='" + arrSubitem[0] + "'></td>";
                            }
                            else if ((parseInt(arrSubitem[0]) === 94) && (parseInt(data['SUBJECT'][0]['STATUS_HTBT_TA']) != 0)) {
                                var STATUS_HTBT = " id='chilRevertCheck' ";
                                if (parseInt(data['SUBJECT'][0]['STATUS_HTBT_TA']) == 4) {
                                    STATUS_HTBT = ' disabled ';
                                }
                                if (parseInt(data['SUBJECT'][0]['HTBT_TA']) === 2 && (parseInt(data['SUBJECT'][0]['STATUS_HTBT_TA']) === 2 || parseInt(data['SUBJECT'][0]['STATUS_HTBT_TA']) === 3)) {
                                    STATUS_HTBT += ' checked ';
                                }
                                html_show += "<td class='text-center'><input type='checkbox' " + STATUS_HTBT + "  name='choose' value='" + arrSubitem[0] + "'></td>";
                            }
                            else if ((parseInt(arrSubitem[0]) === 98) && (parseInt(data['SUBJECT'][0]['STATUS_HTBT_TO']) != 0)) {
                                var STATUS_HTBT = "  id='chilRevertCheck' ";
                                if (parseInt(data['SUBJECT'][0]['STATUS_HTBT_TO']) == 4) {
                                    STATUS_HTBT = ' disabled ';
                                }
                                if (parseInt(data['SUBJECT'][0]['HTBT_TO']) === 2 && (parseInt(data['SUBJECT'][0]['STATUS_HTBT_TO']) === 2 || parseInt(data['SUBJECT'][0]['STATUS_HTBT_TO']) === 3)) {
                                    STATUS_HTBT += ' checked ';
                                }
                                html_show += "<td class='text-center'><input type='checkbox' " + STATUS_HTBT + " name='choose' value='" + arrSubitem[0] + "'></td>";
                            }
                            else if ((parseInt(arrSubitem[0]) === 115) && (parseInt(data['SUBJECT'][0]['STATUS_HTBT_VHTT']) != 0)) {
                                var STATUS_HTBT = " id='chilRevertCheck' ";
                                if (parseInt(data['SUBJECT'][0]['STATUS_HTBT_VHTT']) == 4) {
                                    STATUS_HTBT = ' disabled ';
                                }
                                if (parseInt(data['SUBJECT'][0]['HTBT_VHTT']) === 2 && (parseInt(data['SUBJECT'][0]['STATUS_HTBT_VHTT']) === 2 || parseInt(data['SUBJECT'][0]['STATUS_HTBT_VHTT']) === 3)) {
                                    STATUS_HTBT += ' checked ';
                                }
                                html_show += "<td class='text-center'><input type='checkbox' " + STATUS_HTBT + "  name='choose' value='" + arrSubitem[0] + "'></td>";
                            }
                            else if ((parseInt(arrSubitem[0]) === 95) && (parseInt(data['SUBJECT'][0]['STATUS_HSKT_HB']) != 0)) {
                                var STATUS_HSKT = " id='chilRevertCheck' ";
                                if (parseInt(data['SUBJECT'][0]['STATUS_HSKT_HB']) == 4) {
                                    STATUS_HSKT = ' disabled ';
                                }
                                if (parseInt(data['SUBJECT'][0]['HTKT_HB']) === 2 && (parseInt(data['SUBJECT'][0]['STATUS_HSKT_HB']) === 2 || parseInt(data['SUBJECT'][0]['STATUS_HSKT_HB']) === 3)) {
                                    STATUS_HSKT += ' checked ';
                                }
                                html_show += "<td class='text-center'><input type='checkbox' " + STATUS_HSKT + "  name='choose' value='" + arrSubitem[0] + "' ></td>";
                            }
                            else if ((parseInt(arrSubitem[0]) === 100) && (parseInt(data['SUBJECT'][0]['STATUS_HSKT_DDHT']) != 0)) {
                                var STATUS_HSKT = " id='chilRevertCheck' ";
                                if (parseInt(data['SUBJECT'][0]['STATUS_HSKT_DDHT']) == 4) {
                                    STATUS_HSKT = ' disabled ';
                                }
                                if (parseInt(data['SUBJECT'][0]['HTKT_DDHT']) === 2 && (parseInt(data['SUBJECT'][0]['STATUS_HSKT_DDHT']) === 2 || parseInt(data['SUBJECT'][0]['STATUS_HSKT_DDHT']) === 3)) {
                                    STATUS_HSKT += ' checked ';
                                }
                                html_show += "<td class='text-center'><input type='checkbox' " + STATUS_HSKT + " name='choose' value='" + arrSubitem[0] + "'></td>";
                            }
                            else if ((parseInt(arrSubitem[0]) === 99) && (parseInt(data['SUBJECT'][0]['STATUS_HSDTTS']) != 0)) {
                                var STATUS_HSDTTS = "  id='chilRevertCheck' ";
                                if (parseInt(data['SUBJECT'][0]['STATUS_HSDTTS']) == 4) {
                                    STATUS_HSDTTS = ' disabled ';
                                }
                                if (parseInt(data['SUBJECT'][0]['HSDTTS']) === 2 && (parseInt(data['SUBJECT'][0]['STATUS_HSDTTS']) === 2 || parseInt(data['SUBJECT'][0]['STATUS_HSDTTS']) === 3)) {
                                    STATUS_HSDTTS += ' checked ';
                                }
                                html_show += "<td class='text-center'><input type='checkbox' " + STATUS_HSDTTS + " name='choose' value='" + arrSubitem[0] + "'></td>";
                            }
                            else if ((parseInt(arrSubitem[0]) === 118) && (parseInt(data['SUBJECT'][0]['STATUS_HTATHS']) != 0)) {
                                var STATUS_HTATHS = " id='chilRevertCheck' ";
                                if (parseInt(data['SUBJECT'][0]['STATUS_HTATHS']) == 4) {
                                    STATUS_HTATHS = ' disabled ';
                                }
                                if (parseInt(data['SUBJECT'][0]['HTATHS']) === 2 && (parseInt(data['SUBJECT'][0]['STATUS_HTATHS']) === 2 || parseInt(data['SUBJECT'][0]['STATUS_HTATHS']) === 3)) {
                                    STATUS_HTATHS += ' checked ';
                                }
                                html_show += "<td class='text-center'><input type='checkbox' " + STATUS_HTATHS + " name='choose' value='" + arrSubitem[0] + "'></td>";
                            }
                            else if ((parseInt(arrSubitem[0]) === 119) && (parseInt(data['SUBJECT'][0]['STATUS_HBHSDTNT']) != 0)) {
                                var STATUS_HBHSDTNT = " id='chilRevertCheck' ";
                                if (parseInt(data['SUBJECT'][0]['STATUS_HBHSDTNT']) == 4) {
                                    STATUS_HBHSDTNT = ' disabled ';
                                }
                                if (parseInt(data['SUBJECT'][0]['HBHSDTNT']) === 2 && (parseInt(data['SUBJECT'][0]['STATUS_HBHSDTNT']) === 2 || parseInt(data['SUBJECT'][0]['STATUS_HBHSDTNT']) === 3)) {
                                    STATUS_HBHSDTNT += ' checked ';
                                }
                                html_show += "<td class='text-center'><input type='checkbox' " + STATUS_HTATHS + "  name='choose' value='" + arrSubitem[0] + "'></td>";
                            }
                            else {

                                html_show += "<td class='text-center'><input type='checkbox' id='chilRevertCheck' name='choose' value='" + arrSubitem[0] + "'></td>";
                            }

                            html_show += "<td>" + ConvertString(arrSubitem[1]) + "</td>";
                            html_show += "<td>" + ConvertString(arrSubitem[2]) + "</td>";
                            html_show += "</tr>";
                        }
                    }
                }

                if ((parseInt(data['SUBJECT'][0]['STATUS_MGHP']) === 2 || parseInt(data['SUBJECT'][0]['STATUS_MGHP']) === 3) &&
                    (parseInt(data['SUBJECT'][0]['STATUS_CPHT']) === 2 || parseInt(data['SUBJECT'][0]['STATUS_CPHT']) === 3) &&
                    (parseInt(data['SUBJECT'][0]['STATUS_HTAT']) === 2 || parseInt(data['SUBJECT'][0]['STATUS_HTAT']) === 3) &&
                    (parseInt(data['SUBJECT'][0]['STATUS_HTBT_TA']) === 2 || parseInt(data['SUBJECT'][0]['STATUS_HTBT_TA']) === 3) &&
                    (parseInt(data['SUBJECT'][0]['STATUS_HTBT_TO']) === 2 || parseInt(data['SUBJECT'][0]['STATUS_HTBT_TO']) === 3) &&
                    (parseInt(data['SUBJECT'][0]['STATUS_HTBT_VHTT']) === 2 || parseInt(data['SUBJECT'][0]['STATUS_HTBT_VHTT']) === 3) &&
                    (parseInt(data['SUBJECT'][0]['STATUS_HSKT_HB']) === 2 || parseInt(data['SUBJECT'][0]['STATUS_HSKT_HB']) === 3) &&
                    (parseInt(data['SUBJECT'][0]['STATUS_HSKT_DDHT']) === 2 || parseInt(data['SUBJECT'][0]['STATUS_HSKT_DDHT']) === 3) &&
                    (parseInt(data['SUBJECT'][0]['STATUS_HSDTTS']) === 2 || parseInt(data['SUBJECT'][0]['STATUS_HSDTTS']) === 3) &&
                    (parseInt(data['SUBJECT'][0]['STATUS_HTATHS']) === 2 || parseInt(data['SUBJECT'][0]['STATUS_HTATHS']) === 3) &&
                    (parseInt(data['SUBJECT'][0]['STATUS_HBHSDTNT']) === 2 || parseInt(data['SUBJECT'][0]['STATUS_HBHSDTNT']) === 2)) {
                    $('#checkedAllReverted').prop('checked', true);
                }
                else {
                    $('#checkedAllReverted').prop('checked', false);
                }
            }
        }

        $('#txtNoteProfileTraLai').html('Nội dung: <b>' + ConvertString(data['SUBJECT'][0]['bckp_ghichu']) + '</b>');
        $('#txtGhiChuTHCDTraLai').val(ConvertString(data['SUBJECT'][0]['bckp_traloi']));
        $('#dataListRevertCheDo').html(html_show);
        $("#myModalRevert").modal("show");
    }, function (data) {

    }, "", "loading", "");

}

function openPopupThuhoiChedoPhongSo(id, socongvan) {

    _idProfile = id;
    // id = id + '-' + socongvan + '-' + _year;

    var objJson = JSON.stringify({ PROFILEID: id, SOCONGVAN: socongvan });
    $('#txtGhiChuTHCD').val('');
    GetFromServer('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/getProfileSubjectByIdPhongSo/' + objJson, function (data) {
        var html_show = "";

        var groupId = 0;

        if (data !== null && data !== "") {
            if (data['SUBJECT'].length > 0) {
                for (var i = 0; i < data['SUBJECT'].length; i++) {
                    var arrSub = [];
                    arrSub = data['SUBJECT'][i].subject_ds_group_sub_name.split(',');
                    for (var j = 0; j < arrSub.length; j++) {
                        var arrSubitem = [];
                        arrSubitem = arrSub[j].split('-');
                        if ((arrSubitem[0] !== null && arrSubitem[0] !== '' && arrSubitem[0] !== undefined)
                            && (arrSubitem[1] !== null && arrSubitem[1] !== '' && arrSubitem[1] !== undefined)) {
                            html_show += "<tr>";
                            html_show += "<td class='text-center'>" + (j + 1) + "</td>";
                            if ((parseInt(arrSubitem[0]) === 89 || parseInt(arrSubitem[0]) === 90 || parseInt(arrSubitem[0]) === 91)
                                && parseInt(data['APPROVED'][0]['STATUS_MGHP']) === 3) {
                                html_show += "<td class='text-center'><input type='checkbox' id='chilReloadCheck' name='choose' value='" + arrSubitem[0] + "' checked='checked'></td>";
                            }
                            else if ((parseInt(arrSubitem[0]) === 92) && parseInt(data['APPROVED'][0]['STATUS_CPHT']) === 3) {
                                html_show += "<td class='text-center'><input type='checkbox' id='chilReloadCheck' name='choose' value='" + arrSubitem[0] + "' checked='checked'></td>";
                            }
                            else if ((parseInt(arrSubitem[0]) === 93) && parseInt(data['APPROVED'][0]['STATUS_HTAT']) === 3) {
                                html_show += "<td class='text-center'><input type='checkbox' id='chilReloadCheck' name='choose' value='" + arrSubitem[0] + "' checked='checked'></td>";
                            }
                            else if ((parseInt(arrSubitem[0]) === 94) && parseInt(data['APPROVED'][0]['STATUS_HTBT_TA']) === 3) {
                                html_show += "<td class='text-center'><input type='checkbox' id='chilReloadCheck' name='choose' value='" + arrSubitem[0] + "' checked='checked'></td>";
                            }
                            else if ((parseInt(arrSubitem[0]) === 98) && parseInt(data['APPROVED'][0]['STATUS_HTBT_TO']) === 3) {
                                html_show += "<td class='text-center'><input type='checkbox' id='chilReloadCheck' name='choose' value='" + arrSubitem[0] + "' checked='checked'></td>";
                            }
                            else if ((parseInt(arrSubitem[0]) === 115) && parseInt(data['APPROVED'][0]['STATUS_HTBT_VHTT']) === 3) {
                                html_show += "<td class='text-center'><input type='checkbox' id='chilReloadCheck' name='choose' value='" + arrSubitem[0] + "' checked='checked'></td>";
                            }
                            else if ((parseInt(arrSubitem[0]) === 95) && parseInt(data['APPROVED'][0]['STATUS_HSKT_HB']) === 3) {
                                html_show += "<td class='text-center'><input type='checkbox' id='chilReloadCheck' name='choose' value='" + arrSubitem[0] + "' checked='checked'></td>";
                            }
                            else if ((parseInt(arrSubitem[0]) === 100) && parseInt(data['APPROVED'][0]['STATUS_HSKT_DDHT']) === 3) {
                                html_show += "<td class='text-center'><input type='checkbox' id='chilReloadCheck' name='choose' value='" + arrSubitem[0] + "' checked='checked'></td>";
                            }
                            else if ((parseInt(arrSubitem[0]) === 99) && parseInt(data['APPROVED'][0]['STATUS_HSDTTS']) === 3) {
                                html_show += "<td class='text-center'><input type='checkbox' id='chilReloadCheck' name='choose' value='" + arrSubitem[0] + "' checked='checked'></td>";
                            }
                            else if ((parseInt(arrSubitem[0]) === 118) && parseInt(data['APPROVED'][0]['STATUS_HTATHS']) === 3) {
                                html_show += "<td class='text-center'><input type='checkbox' id='chilReloadCheck' name='choose' value='" + arrSubitem[0] + "' checked='checked'></td>";
                            }
                            else if ((parseInt(arrSubitem[0]) === 119) && parseInt(data['APPROVED'][0]['STATUS_HBHSDTNT']) === 3) {
                                html_show += "<td class='text-center'><input type='checkbox' id='chilReloadCheck' name='choose' value='" + arrSubitem[0] + "' checked='checked'></td>";
                            }
                            else {
                                html_show += "<td class='text-center'><input type='checkbox' id='chilReloadCheck' name='choose' value='" + arrSubitem[0] + "'></td>";
                            }

                            html_show += "<td>" + ConvertString(arrSubitem[1]) + "</td>";
                            html_show += "<td>" + ConvertString(arrSubitem[2]) + "</td>";
                            html_show += "</tr>";
                        }
                    }
                }

                if (parseInt(data['APPROVED'][0]['STATUS_MGHP']) === 3 && parseInt(data['APPROVED'][0]['STATUS_CPHT']) === 3
                    && parseInt(data['APPROVED'][0]['STATUS_HTAT']) === 3 && parseInt(data['APPROVED'][0]['STATUS_HTBT_TA']) === 3
                    && parseInt(data['APPROVED'][0]['STATUS_HTBT_TO']) === 3 && parseInt(data['APPROVED'][0]['STATUS_HTBT_VHTT']) === 3
                    && parseInt(data['APPROVED'][0]['STATUS_HSKT_HB']) === 3 && parseInt(data['APPROVED'][0]['STATUS_HSKT_DDHT']) === 3
                    && parseInt(data['APPROVED'][0]['STATUS_HSDTTS']) === 3 && parseInt(data['APPROVED'][0]['STATUS_HTATHS']) === 3
                    && parseInt(data['APPROVED'][0]['STATUS_HBHSDTNT']) === 3) {
                    $('#checkedAllReload').prop('checked', true);
                }
                else {
                    $('#checkedAllReload').prop('checked', false);
                }
            }
        }

        $('#txtGhiChuTHCD').val(data['SUBJECT'][0]['ghi_chu']);
        $('#dataListReloadCheDo').html(html_show);
        $("#myModalReload").modal("show");
    }, function (data) {

    }, "", "loading", "");

}

function openPopupThongtinChedoPhongSo(id, socongvan, name = "") {
    _idProfile = id;
    var objJson = JSON.stringify({ PROFILEID: id, SOCONGVAN: socongvan });
    $('label#txtProfileNameThongtin').text(name);
    GetFromServer('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/getProfileSubjectByIdPhongSo/' + objJson, function (data) {
        var html_show = "";

        var groupId = 0;

        if (data !== null && data !== "") {
            if (data['SUBJECT'].length > 0) {
                for (var i = 0; i < data['SUBJECT'].length; i++) {
                    var arrSub = [];
                    arrSub = data['SUBJECT'][i].bckp_danhsach_chedo.split(';');
                    for (var j = 0; j < arrSub.length; j++) {
                        var arrSubitem = [];
                        arrSubitem = arrSub[j].split('|');
                        if ((arrSubitem[0] !== null && arrSubitem[0] !== '' && arrSubitem[0] !== undefined)
                            && (arrSubitem[1] !== null && arrSubitem[1] !== '' && arrSubitem[1] !== undefined)) {
                            html_show += "<tr>";
                            html_show += "<td class='text-center'>" + (j + 1) + "</td>";

                            html_show += "<td>" + ConvertString(arrSubitem[1]) + "</td>";
                            html_show += "<td>" + ConvertString(arrSubitem[2]) + "</td>";
                            html_show += "</tr>";
                        }
                    }
                }
            }
        }
        $('#txtNoteProfile').html('Nội dung: <b>' + ConvertString(data['SUBJECT'][0]['ghi_chu']) + '</b>');
        $('#dataListThongtinCheDo').html(html_show);
        $("#myModalThongtin").modal("show");
    }, function (data) {

    }, "", "loading", "");

}
// Lập danh sách từ cấp phòng giáo dục hoặc phòng tài chính
function lapdanhsachDanhSachPhongSo(o) {
    PostToServer('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/lapdanhsachPhongSo', o, function (data) {
        if (data['success'] != "" && data['success'] != undefined) {
            utility.message("Thông báo", data['success'], null, 3000);
            GET_INITIAL_NGHILC();
            loadDanhSachHSDaLap($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfilePhongSo').val());
            $("#myModalLapDanhSachPhongSo").modal("hide");
        }
        if (data['error'] != "" && data['error'] != undefined) {
            GET_INITIAL_NGHILC();
            loadDanhSachHSDaLap($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfilePhongSo').val());
            utility.message("Thông báo", data['error'], null, 3000, 1);
        }
    }, function (data) {
        console.log("lapdanhsachDanhSachPhongSo");
        console.log(data);
    }, "btnLapDSPhongSo", "", "")
};

function thuhoicongvanlap() {
    if ($('#sltCongvan').val() !== null && $('#sltCongvan').val() !== "") {
        utility.confirm("Đồng ý công văn thu hồi học sinh?", "Bạn có chắc chắn đồng ý?", function () {
            var o = {
                SOCONGVAN: $('#sltCongvan').val()
            }

            $.ajax({
                type: "POST",
                url: '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/thuhoicongvanlap',
                data: JSON.stringify(o),
                // dataType: 'json',
                contentType: 'application/json; charset=utf-8',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                success: function (data) {
                    // console.log(data);
                    if (data['success'] != "" && data['success'] != undefined) {
                        utility.message("Thông báo", data['success'], null, 3000);
                        // resetFormTHCD();
                        GET_INITIAL_NGHILC();
                        loadDanhSachHSDaLap($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfilePhongSo').val());
                    }
                    if (data['error'] != "" && data['error'] != undefined) {
                        GET_INITIAL_NGHILC();
                        loadDanhSachHSDaLap($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfilePhongSo').val());
                        utility.message("Thông báo", data['error'], null, 3000, 1);
                    }
                }, error: function (data) {
                }
            });
        });
    }
    else {
        utility.messagehide("messageValidateForm", "Mời chọn số công văn", 1, 3000);
        return;
    }
};

function approvedChedoThamDinh(objData, truong, socongvan, note) {
    // var strData = 'ID' + _id + '-' + _year + '-' + 'IDPROFILE' + _idProfile + '-' + note + '.' + '-' + objData;
    // console.log(strData);
    // utility.confirm("Duyệt cấp kinh phí?", "Bạn có chắc chắn muốn Duyệt?", function () {
    var objJson = JSON.stringify({ PROFILEID: _idProfile, SCHOOLID: truong, SOCONGVAN: socongvan, ARRSUBJECTID: objData, NOTE: note });
    $.ajax({
        type: "get",
        url: '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/approvedchedoTD/' + objJson,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
        success: function (data) {
            console.log(data);
            if (data['success'] != "" && data['success'] != undefined) {
                utility.message("Thông báo", data['success'], null, 3000);
                // resetFormTHCD();
                loadlistApprovedPheDuyet($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
                $("#myModalApproved").modal("hide");
                closeLoading();
            }
            if (data['error'] != "" && data['error'] != undefined) {
                // resetFormTHCD();
                loadlistApprovedPheDuyet($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
                utility.message("Thông báo", data['error'], null, 3000, 1);
                closeLoading();
            }
        }, error: function (data) {
            closeLoading();
        }
    });
    // });
}


function loadlistUnApprovedThamdinh(row, keySearch = "") {

    var msg_warning = "";

    msg_warning = validateTHCD();

    // alert(msg_warning);

    if (msg_warning !== null && msg_warning !== "") {
        utility.messagehide("messageValidate", msg_warning, 1, 5000);
        return;
    }

    var schools_id = $('#drSchoolTHCD').val();
    // var year = $('#sltYear').val();
    var socongvan = $('#sltCongvan').val();

    // _year = year;

    // var ky = year.split("-");
    var number = 3;

    // if (ky[0] == 'HK1') {
    //     number = 1;
    // }
    // else if (ky[0] == 'HK2') {
    //     number = 2;
    // }
    // else if (ky[0] == 'CA') {
    //     number = 3;
    // }

    var html_show = "";
    var o = {
        start: (GET_START_RECORD_NGHILC()),
        limit: row,
        SCHOOLID: schools_id,
        //  YEAR: year,
        SOCONGVAN: socongvan,
        KEY: keySearch
    };
    $.ajax({
        type: "POST",
        url: '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/loadListUnApprovedThamDinh',
        data: JSON.stringify(o),
        dataType: 'json',
        contentType: 'application/json; charset=utf-8',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
        success: function (datas) {

            SETUP_PAGING_NGHILC(datas, function () {
                loadlistUnApprovedThamdinh(row, keySearch, status);
            });

            $('#dataListUnApprovedSo').html("");
            var dataget = datas.data;
            //console.log(dataget);

            if (dataget.length > 0) {
                for (var i = 0; i < dataget.length; i++) {
                    var totalMoney = 0;
                    totalMoney = parseInt(dataget[i].MGHP) + parseInt(dataget[i].CPHT) + parseInt(dataget[i].HTAT) + parseInt(dataget[i].HTBT_TA) + parseInt(dataget[i].HTBT_TO) + parseInt(dataget[i].HTBT_VHTT) + parseInt(dataget[i].HTATHS) + parseInt(dataget[i].HSKT_HB) + parseInt(dataget[i].HSKT_DDHT) + parseInt(dataget[i].HBHSDTNT) + parseInt(dataget[i].HSDTTS);

                    html_show += "<tr><td class='text-center'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                    html_show += "<td><a href='javascript:;' onclick='getProfileSubById(" + parseInt(dataget[i].profile_id) + ", " + number + ");'>" + dataget[i].profile_name + "</a></td>";
                    html_show += "<td>" + formatDates(dataget[i].profile_birthday) + "</td>";
                    html_show += "<td>" + dataget[i].schools_name + "</td>";
                    html_show += "<td>" + dataget[i].class_name + "</td>";
                    html_show += "<td>" + formatter(dataget[i].MGHP) + "</td>";
                    html_show += "<td>" + formatter(dataget[i].CPHT) + "</td>";
                    html_show += "<td>" + formatter(dataget[i].HTAT) + "</td>";
                    html_show += "<td>" + formatter(dataget[i].HTBT_TA) + "</td>";
                    html_show += "<td>" + formatter(dataget[i].HTBT_TO) + "</td>";
                    html_show += "<td>" + formatter(dataget[i].HTBT_VHTT) + "</td>";
                    html_show += "<td>" + formatter(dataget[i].HTATHS) + "</td>";
                    html_show += "<td>" + formatter(dataget[i].HSKT_HB) + "</td>";
                    html_show += "<td>" + formatter(dataget[i].HSKT_DDHT) + "</td>";
                    html_show += "<td>" + formatter(dataget[i].HBHSDTNT) + "</td>";
                    html_show += "<td>" + formatter(dataget[i].HSDTTS) + "</td>";
                    html_show += "<td>" + formatter(totalMoney) + "</td>";
                    html_show += "<td>" + ConvertString(dataget[i].Note) + "</td>";


                    html_show += "</tr>";
                }
            }
            else {
                html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
            }
            $('#dataListUnApprovedSo').html(html_show);
        }, error: function (dataget) {

        }
    });
};

function approvedAllThamDinh() {

    var msg_warning = "";

    msg_warning = validateTHCD();

    // alert(msg_warning);

    if (msg_warning !== null && msg_warning !== "") {
        utility.messagehide("messageValidate", msg_warning, 1, 5000);
        return;
    }

    var schools_id = $('#drSchoolTHCD').val();
    var socongvan = $('#sltCongvan').val();

    var o = {
        SCHOOLID: schools_id,
        SOCONGVAN: socongvan
    };
    // console.log(o);
    $.ajax({
        type: "POST",
        url: '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/approvedAllThamDinh',
        data: JSON.stringify(o),
        dataType: 'json',
        contentType: 'application/json; charset=utf-8',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
        success: function (data) {
            // console.log(data);
            if (data['success'] != "" && data['success'] != undefined) {
                utility.message("Thông báo", data['success'], null, 3000);
                GET_INITIAL_NGHILC();

                loadlistApprovedPheDuyet($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());

                closeLoading();
            }
            if (data['error'] != "" && data['error'] != undefined) {
                GET_INITIAL_NGHILC();

                loadlistApprovedPheDuyet($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());

                utility.message("Thông báo", data['error'], null, 3000, 1);
                closeLoading();
            }
        }, error: function (data) {

        }
    });
};

function unApprovedAllThamDinh() {

    var msg_warning = "";

    msg_warning = validateTHCD();

    // alert(msg_warning);

    if (msg_warning !== null && msg_warning !== "") {
        utility.messagehide("messageValidate", msg_warning, 1, 5000);
        return;
    }

    var schools_id = $('#drSchoolTHCD').val();
    var socongvan = $('#sltCongvan').val();

    var o = {
        SCHOOLID: schools_id,
        SOCONGVAN: socongvan
    };
    // console.log(o);
    $.ajax({
        type: "POST",
        url: '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/unApprovedAllThamDinh',
        data: JSON.stringify(o),
        dataType: 'json',
        contentType: 'application/json; charset=utf-8',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
        success: function (data) {
            // console.log(data);
            if (data['success'] != "" && data['success'] != undefined) {
                utility.message("Thông báo", data['success'], null, 3000);
                GET_INITIAL_NGHILC();
                loadlistApprovedPheDuyet($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());

                closeLoading();
            }
            if (data['error'] != "" && data['error'] != undefined) {
                GET_INITIAL_NGHILC();
                loadlistApprovedPheDuyet($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());

                utility.message("Thông báo", data['error'], null, 3000, 1);
                closeLoading();
            }
        }, error: function (data) {

        }
    });
};

//--------------------------------------------------------------------Danh sách đề nghị đã lập--------------------------------------------------
function loadReport() {
    $.ajax({
        type: "GET",
        url: '/ho-so/hoc-sinh/loadComboReport',
        success: function (dataget) {
            var dataschool = dataget['SCHOOL'];
            var datareport = dataget.REPORT;

            //console.log(dataget);
            $('#sltCongvan').html("");
            var html_show = "";

            if (dataschool.length > 0) {

                html_show += "<option value=''>--- Chọn số công văn ---</option>";
                for (var j = 0; j < dataschool.length; j++) {
                    var html = "";
                    var check = 0;
                    html += "<optgroup label='" + dataschool[j].schools_name + "'>";
                    if (datareport.length > 0) {
                        for (var i = 0; i < datareport.length; i++) {
                            if (dataschool[j].schools_id === datareport[i].report_id_truong) {
                                check = 1;
                                html += "<option value='" + datareport[i].report_name + "'>" + datareport[i].report_name + "</option>";
                            }
                        }
                    } else {
                        check = 0;
                    }
                    html += "</optgroup>";
                    if (check == 1) {
                        html_show += html;
                    }
                }
                $('#sltCongvan').html(html_show);
            } else {
                $('#sltCongvan').html("<option value=''>-- Chưa có công văn nào --</option>");
            }



            // console.log(dataget);
            // $('#sltCongvan').html("");
            // var html_show = "";

            // if(dataget.length > 0){
            //     html_show += "<option value=''>--- Chọn số công văn ---</option>";
            //     for (var i = dataget.length - 1; i >= 0; i--) {
            //         html_show += "<option value='"+dataget[i].report_name+"'>"+dataget[i].report_name+"</option>";
            //     }
            //     $('#sltCongvan').html(html_show);
            // }else{
            //     $('#sltCongvan').html("<option value=''>-- Chưa có công văn nào --</option>");
            // }
        }, error: function (dataget) {
        }
    });
};

function loadReportType(reportName) {
    GetFromServer('/ho-so/hoc-sinh/loadComboReportType/' + reportName, function (dataget) {
        $('#sltLoaiChedo').html("");
        var html_show = "";
        if (dataget.length > 0) {
            html_show += "<option value=''>--- Chọn chế độ ---</option>";
            html_show += "<option value='ALL'>Tất cả</option>";
            for (var i = dataget.length - 1; i >= 0; i--) {
                if (dataget[i].report == "MGHP") {
                    html_show += "<option value='" + dataget[i].report + "'>Hỗ trợ miễn giảm học phí</option>";
                }
                else if (dataget[i].report == "CPHT") {
                    html_show += "<option value='" + dataget[i].report + "'>Hỗ trợ chi phí học tập</option>";
                }
                else if (dataget[i].report == "HTAT") {
                    html_show += "<option value='" + dataget[i].report + "'>Hỗ trợ ăn trưa trẻ em mẫu giáo</option>";
                }
                else if (dataget[i].report == "HTBT") {
                    html_show += "<option value='" + dataget[i].report + "'>Hỗ trợ học sinh bán trú</option>";
                }
                else if (dataget[i].report == "HSKT") {
                    html_show += "<option value='" + dataget[i].report + "'>Hỗ trợ học sinh khuyết tật, tàn tật</option>";
                }
                else if (dataget[i].report == "HTATHS") {
                    html_show += "<option value='" + dataget[i].report + "'>Hỗ trợ ăn trưa cho học sinh theo NQ57</option>";
                }
                else if (dataget[i].report == "HSDTTS") {
                    html_show += "<option value='" + dataget[i].report + "'>Hỗ trợ học sinh dân tộc thiểu số</option>";
                }
                else if (dataget[i].report == "HBHSDTNT") {
                    html_show += "<option value='" + dataget[i].report + "'>Hỗ trợ học bổng cho học sinh dân tộc nội trú</option>";
                }
                else if (dataget[i].report == "NGNA") {
                    html_show += "<option value='" + dataget[i].report + "'>Hỗ trợ người nấu ăn</option>";
                }
            }

            $('#sltLoaiChedo').html(html_show);
        } else {
            $('#sltLoaiChedo').html("<option value=''>-- Chưa có chế độ --</option>");
        }
    }, function (dataget) {
        console.log("loadReportType");
        console.log(dataget);
    }, "", "loading", "");
};


function loadReportBySchool(school_id, callback, status = 0) {
    $('#drSchoolTHCD').html("<option value='' selected>Đang tải dữ liệu...</option>");
    $('#drSchoolTHCD').selectpicker('refresh');
    if (school_id != null && school_id > 0 && school_id != '') {

        GetFromServer('/ho-so/hoc-sinh/loadComboReportBySchool/' + school_id, function (dataget) {
            $('#sltCongvan').html("");
            var html_show = "";

            if (dataget.length > 0) {

                html_show += "<option value=''>--- Chọn số công văn ---</option>";
                for (var i = 0; i < dataget.length; i++) {
                    html_show += "<option value='" + dataget[i].report_value + "'>" + dataget[i].report_name + "</option>";
                    // if (status === 1 && parseInt(dataget[i].report_waiting) > 0) {
                    //     html_show += "<option value='"+dataget[i].report_name+"'>"+dataget[i].report_name+"</option>";
                    // }
                    // if (status === 2 && parseInt(dataget[i].report_approved) > 0) {
                    //     html_show += "<option value='"+dataget[i].report_name+"'>"+dataget[i].report_name+"</option>";
                    // }
                    // if (status === 3 && parseInt(dataget[i].report_reverted) > 0) {
                    //     html_show += "<option value='"+dataget[i].report_name+"'>"+dataget[i].report_name+"</option>";
                    // }
                }
                $('#sltCongvan').html(html_show);
            } else {
                $('#sltCongvan').html("<option value=''>-- Chưa có công văn nào --</option>");
            }

            if (callback != null) { callback(dataget); }
        }, function (dataget) {
            console.log("loadReportBySchool: " + dataget);
        }, "", "", "");
    } else {
        $('#sltCongvan').html("");
    }
};

function loadReportBySchoolAndStatus(school_id, callback, status = 0, cv = '') {
    if (school_id != null && school_id > 0 && school_id != '') {
        $('#sltCongvan').html("<option value='' selected>Đang tải dữ liệu</option>").selectpicker('refresh');
        var o = { SCHOOLID: school_id, STATUS: status }
        PostToServer('/ho-so/hoc-sinh/loadComboReportBySchoolStatus', o, function (dataget) {
            $('#sltCongvan').html("");
            var html_show = "";

            if (dataget.length > 0) {
                if (cv == '') {
                    html_show += "<option value='' selected>--- Chọn số công văn ---</option>";
                } else {
                    html_show += "<option value=''>--- Chọn số công văn ---</option>";
                }

                for (var i = 0; i < dataget.length; i++) {
                    if (cv == dataget[i].report_name) {
                        html_show += "<option value='" + dataget[i].report_name + "' selected>" + dataget[i].report_name + "</option>";
                    } else {
                        html_show += "<option value='" + dataget[i].report_name + "'>" + dataget[i].report_name + "</option>";
                    }

                    // if (status === 1 && parseInt(dataget[i].report_waiting) > 0) {
                    //     html_show += "<option value='"+dataget[i].report_name+"'>"+dataget[i].report_name+"</option>";
                    // }
                    // if (status === 2 && parseInt(dataget[i].report_approved) > 0) {
                    //     html_show += "<option value='"+dataget[i].report_name+"'>"+dataget[i].report_name+"</option>";
                    // }
                    // if (status === 3 && parseInt(dataget[i].report_reverted) > 0) {
                    //     html_show += "<option value='"+dataget[i].report_name+"'>"+dataget[i].report_name+"</option>";
                    // }
                }
                $('#sltCongvan').html(html_show);
            } else {
                $('#sltCongvan').html("<option value=''>-- Chưa có công văn nào --</option>");
            }
            $('#sltCongvan').selectpicker('refresh');
            if (callback != null) { callback(dataget); }
        }, function (dataget) {
            console.log("loadReportBySchool: " + dataget);
        }, "", "", "");
    } else {
        $('#sltCongvan').html("");
    }
};


function loadReportThuhoi(socongvan) {
    if (socongvan !== null && socongvan !== '') {
        GetFromServer('/ho-so/hoc-sinh/loadCongVanThuHoi/' + socongvan, function (dataget) {
            $('#tbThuhoicongvang').html("");
            var html_show = "";
            // console.log(dataget);
            if (dataget.length > 0) {

                for (var i = 0; i < dataget.length; i++) {

                    html_show += "<tr>";
                    html_show += "<td class='text-center'>" + ConvertString(dataget[i].report_name) + "</td>";
                    html_show += "<td class='text-center'>Chờ phê duyệt</td>";
                    html_show += "<td class='text-center' style='vertical-align:middle'><button  onclick='thuhoicongvan(" + dataget[i].report_id + ");'  class='btn btn-danger btn-xs editor_remove'><i class='glyphicon glyphicon-remove'></i> Thu hồi</button></td>";
                    html_show += "</tr>";
                }
                $('#tbThuhoicongvang').html(html_show);
                $('#dvThuhoi').show();
            } else {
                $('#dvThuhoi').hide();
            }
        }, function (dataget) {
            console.log("loadReportThuhoi: " + dataget);
        }, "", "", "");
    } else {
        $('#dvThuhoi').hide();
    }
};

function thuhoicongvan(report_id) {
    var o = {
        REPORTID: report_id
    };
    $.ajax({
        type: "POST",
        url: '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/thuhoicongvan',
        data: JSON.stringify(o),
        dataType: 'json',
        contentType: 'application/json; charset=utf-8',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
        success: function (datas) {
            if (datas.success != null || datas.success != undefined) {
                utility.message("Thông báo", datas.success, null, 3000);
                $('#dvThuhoi').hide();
            } else if (datas.error != null || datas.error != undefined) {
                utility.message("Thông báo", datas.error, null, 5000, 1);
            }
        },
        error: function (datas) {

        }
    });
}

function loaddataDanhSachGroupA(row, keySearch = "", group = "") {

    // var msg_warning = "";

    // msg_warning = validateDanhsachdenghi();

    // // alert(msg_warning);

    // if (msg_warning !== null && msg_warning !== "") {
    //     utility.messagehide("messageValidate", msg_warning, 1, 5000);
    //     return;
    // }

    var reportName = $('#sltCongvan').val();
    var reportType = $('#sltLoaiChedo').val();


    var html_show = "";
    var o = {
        start: (GET_START_RECORD_NGHILC()),
        limit: row,
        REPORTNAME: reportName,
        REPORTTYPE: reportType,
        KEY: keySearch,
        GROUP: group
    };
    $.ajax({
        type: "POST",
        url: '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/loadDataGroupA',
        data: JSON.stringify(o),
        dataType: 'json',
        contentType: 'application/json; charset=utf-8',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
        success: function (datas) {

            SETUP_PAGING_NGHILC(datas, function () {
                loaddataDanhSachGroupA(row, keySearch, group);
            });

            // $('#divSearch').html("");
            $('#headerTable').html("");
            var html_search = "";
            var html_header = '';
            var html_show = "";
            var html_paging = "";
            $('#dataLapDanhsachHS').html("");
            // $('#divPaging').html("");
            var dataget = datas.data;
            // console.log(dataget);
            if (dataget.length > 0) {

                html_search += '<input id="txtSearchProfile" type="text" class="form-control input-sm" placeholder="Tìm kiếm ">';
                html_search += '<span class="glyphicon glyphicon-search form-control-feedback"></span>';

                if (reportType == "MGHP" || reportType == "CPHT" || reportType == "HTAT") {
                    html_header += '<th class="text-center" style="vertical-align:middle">STT</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle">Số công văn</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle">Tên chế độ</th>';
                    //html_header += '<th class="text-center" style="vertical-align:middle">Nhóm học sinh</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle">';
                    // html_header += '<select name="sltGroupHS" id="sltGroupHS">';
                    // html_header += '<option value="">-Tìm kiếm theo nhóm-</option>';
                    // html_header += '<option value="GROUPA">Đang có mặt tại trường</option>';
                    // html_header += '<option value="GROUPB">Chuẩn bị nhập học</option>';
                    // html_header += '<option value="GROUPC">Dự kiến tuyển mới</option>';
                    // html_header += '</select></th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Tên học sinh</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Ngày sinh</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Dân tộc</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Cha mẹ</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Thôn/ xóm</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Xã/ phường</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Huyện/ quận</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Trường</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Lớp học</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Ngày nhập học</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Nhu cầu</th>';
                    //html_header += '<th class="text-center" style="vertical-align:middle">Dự toán</th>';


                    for (var i = 0; i < dataget.length; i++) {

                        html_show += "<tr><td class='text-center' style='vertical-align:middle'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                        // html_show += "<td class='text-left' style='vertical-align:middle'>"+ConvertString(reportName)+"</td>";
                        // if (reportType == "MGHP") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ miễn giảm học phí</td>";
                        // }
                        // else if (reportType == "CPHT") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ chi phí học tập</td>";
                        // }
                        // else if (reportType == "HTAT") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ ăn trưa trẻ em mẫu giáo</td>";
                        // }
                        // else if (reportType == "HTBT") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ học sinh bán trú</td>";
                        // }
                        // else if (reportType == "HSKT") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ học sinh khuyết tật</td>";
                        // }
                        // else if (reportType == "HTATHS") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ ăn trưa cho học sinh theo NQ57</td>";
                        // }
                        // else if (reportType == "HSDTTS") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ học sinh dân tộc thiểu số</td>";
                        // }
                        // else if (reportType == "HBHSDTNT") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ học bổng học sinh dân tộc nội trú</td>";
                        // }

                        // if (parseInt(dataget[i].type) === 1) {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Học sinh đang học tại trường</td>";
                        // }
                        // else if (parseInt(dataget[i].type) === 2) {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Học sinh chuẩn bị nhập học</td>";
                        // }
                        // else if (parseInt(dataget[i].type) === 3) {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Học sinh dự kiến tuyển mới</td>";
                        // }
                        html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='getProfileSubById(" + dataget[i].profile_id + ", 3);'>" + dataget[i].profile_name + "</a></td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatDates(dataget[i].profile_birthday) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].nationals_name) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].profile_parentname) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].profile_household) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].tenxa) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].tenhuyen) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].schools_name) + "</td>";
                        if (parseInt(dataget[i].type) === 1 || parseInt(dataget[i].type) === 2) {
                            html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].old_level_cur) + "</td>";
                        }
                        else if (parseInt(dataget[i].type) === 3) {
                            html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].new_level_cur) + "</td>";
                        }
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatDates(dataget[i].profile_year) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatNumber_2(dataget[i].nhu_cau) + "</td>";


                        html_show += "</tr>";
                    }
                }
                if (reportType == "HTBT") {
                    html_header += '<th class="text-center" style="vertical-align:middle">STT</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle">Số công văn</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle">Tên chế độ</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle">Nhóm học sinh</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Tên học sinh</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Ngày sinh</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Dân tộc</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Cha mẹ</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Thôn/ xóm</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Xã/ phường</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Huyện/ quận</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Trường</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Lớp học</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Ngày nhập học</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Nhu cầu tiền ăn</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Nhu cầu tiền ở</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Nhu cầu VHTT</th>';
                    //   html_header += '<th class="text-center" style="vertical-align:middle">Dự toán tiền ăn</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle">Dự toán tiền ở</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle">Dự toán VHTT</th>';

                    for (var i = 0; i < dataget.length; i++) {
                        // console.log(dataget[i].type);
                        html_show += "<tr><td class='text-center' style='vertical-align:middle'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                        // html_show += "<td class='text-left' style='vertical-align:middle'>"+ConvertString(reportName)+"</td>";
                        // if (reportType == "MGHP") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ miễn giảm học phí</td>";
                        // }
                        // else if (reportType == "CPHT") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ chi phí học tập</td>";
                        // }
                        // else if (reportType == "HTAT") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ ăn trưa trẻ em mẫu giáo</td>";
                        // }
                        // else if (reportType == "HTBT") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ học sinh bán trú</td>";
                        // }
                        // else if (reportType == "HSKT") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ học sinh khuyết tật</td>";
                        // }
                        // else if (reportType == "HTATHS") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ ăn trưa cho học sinh theo NQ57</td>";
                        // }
                        // else if (reportType == "HSDTTS") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ học sinh dân tộc thiểu số</td>";
                        // }
                        // else if (reportType == "HBHSDTNT") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ học bổng học sinh dân tộc nội trú</td>";
                        // }

                        // if (parseInt(dataget[i].type) === 1) {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Học sinh đang học tại trường</td>";
                        // }
                        // else if (parseInt(dataget[i].type) === 2) {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Học sinh chuẩn bị nhập học</td>";
                        // }
                        // else if (parseInt(dataget[i].type) === 3) {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Học sinh dự kiến tuyển mới</td>";
                        // }
                        html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='getProfileSubById(" + dataget[i].profile_id + ", 3);'>" + dataget[i].profile_name + "</a></td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatDates(dataget[i].profile_birthday) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].nationals_name) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].profile_parentname) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].profile_household) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].tenxa) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].tenhuyen) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].schools_name) + "</td>";
                        if (parseInt(dataget[i].type) === 1 || parseInt(dataget[i].type) === 2) {
                            html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].old_level_cur) + "</td>";
                        }
                        else if (parseInt(dataget[i].type) === 3) {
                            html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].new_level_cur) + "</td>";
                        }
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatDates(dataget[i].profile_year) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatNumber_2(dataget[i].nhucau_hotrotienan) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatNumber_2(dataget[i].nhucau_hotrotieno) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatNumber_2(dataget[i].nhucau_VHTT) + "</td>";


                        html_show += "</tr>";
                    }
                }
                if (reportType == "HSKT") {
                    html_header += '<th class="text-center" style="vertical-align:middle">STT</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle">Số công văn</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle">Tên chế độ</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle">Nhóm học sinh</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Tên học sinh</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Ngày sinh</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Dân tộc</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Cha mẹ</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Thôn/ xóm</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Xã/ phường</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Huyện/ quận</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Trường</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Lớp học</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Ngày nhập học</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Nhu cầu học bổng</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Nhu cầu mua đồ dùng</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle">Dự toán học bổng</th>';
                    ///html_header += '<th class="text-center" style="vertical-align:middle">Dự toán mua đồ dùng</th>';

                    for (var i = 0; i < dataget.length; i++) {

                        html_show += "<tr><td class='text-center' style='vertical-align:middle'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                        // html_show += "<td class='text-left' style='vertical-align:middle'>"+ConvertString(reportName)+"</td>";
                        // if (reportType == "MGHP") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ miễn giảm học phí</td>";
                        // }
                        // else if (reportType == "CPHT") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ chi phí học tập</td>";
                        // }
                        // else if (reportType == "HTAT") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ ăn trưa trẻ em mẫu giáo</td>";
                        // }
                        // else if (reportType == "HTBT") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ học sinh bán trú</td>";
                        // }
                        // else if (reportType == "HSKT") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ học sinh khuyết tật</td>";
                        // }
                        // else if (reportType == "HTATHS") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ ăn trưa cho học sinh theo NQ57</td>";
                        // }
                        // else if (reportType == "HSDTTS") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ học sinh dân tộc thiểu số</td>";
                        // }
                        // else if (reportType == "HBHSDTNT") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ học bổng học sinh dân tộc nội trú</td>";
                        // }

                        // if (parseInt(dataget[i].type) === 1) {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Học sinh đang học tại trường</td>";
                        // }
                        // else if (parseInt(dataget[i].type) === 2) {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Học sinh chuẩn bị nhập học</td>";
                        // }
                        // else if (parseInt(dataget[i].type) === 3) {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Học sinh dự kiến tuyển mới</td>";
                        // }
                        html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='getProfileSubById(" + dataget[i].profile_id + ", 3);'>" + dataget[i].profile_name + "</a></td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatDates(dataget[i].profile_birthday) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].nationals_name) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].profile_parentname) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].profile_household) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].tenxa) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].tenhuyen) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].schools_name) + "</td>";
                        if (parseInt(dataget[i].type) === 1 || parseInt(dataget[i].type) === 2) {
                            html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].old_level_cur) + "</td>";
                        }
                        else if (parseInt(dataget[i].type) === 3) {
                            html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].new_level_cur) + "</td>";
                        }
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatDates(dataget[i].profile_year) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatNumber_2(dataget[i].nhucau_hocbong) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatNumber_2(dataget[i].nhucau_muadodung) + "</td>";


                        html_show += "</tr>";
                    }
                }
                if (reportType == "HTATHS" || reportType == "HSDTTS" || reportType == "HBHSDTNT") {
                    html_header += '<th class="text-center" style="vertical-align:middle">STT</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle">Số công văn</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle">Tên chế độ</th>';
                    //html_header += '<th class="text-center" style="vertical-align:middle">Nhóm học sinh</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle">';
                    // html_header += '<select name="sltGroupHS" id="sltGroupHS">';
                    // html_header += '<option value="">-Tìm kiếm theo nhóm-</option>';
                    // html_header += '<option value="GROUPA">Đang có mặt tại trường</option>';
                    // html_header += '<option value="GROUPB">Chuẩn bị nhập học</option>';
                    // html_header += '<option value="GROUPC">Dự kiến tuyển mới</option>';
                    // html_header += '</select></th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Tên học sinh</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Ngày sinh</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Dân tộc</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Cha mẹ</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Thôn/ xóm</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Xã/ phường</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Huyện/ quận</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Trường</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Lớp học</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Ngày nhập học</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Nhu cầu</th>';
                    //html_header += '<th class="text-center" style="vertical-align:middle">Dự toán</th>';


                    for (var i = 0; i < dataget.length; i++) {

                        html_show += "<tr><td class='text-center' style='vertical-align:middle'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                        // html_show += "<td class='text-left' style='vertical-align:middle'>"+ConvertString(reportName)+"</td>";
                        // if (reportType == "MGHP") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ miễn giảm học phí</td>";
                        // }
                        // else if (reportType == "CPHT") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ chi phí học tập</td>";
                        // }
                        // else if (reportType == "HTAT") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ ăn trưa trẻ em mẫu giáo</td>";
                        // }
                        // else if (reportType == "HTBT") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ học sinh bán trú</td>";
                        // }
                        // else if (reportType == "HSKT") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ học sinh khuyết tật</td>";
                        // }
                        // else if (reportType == "HTATHS") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ ăn trưa cho học sinh theo NQ57</td>";
                        // }
                        // else if (reportType == "HSDTTS") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ học sinh dân tộc thiểu số</td>";
                        // }
                        // else if (reportType == "HBHSDTNT") {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Hỗ trợ học bổng học sinh dân tộc nội trú</td>";
                        // }

                        // if (parseInt(dataget[i].type) === 1) {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Học sinh đang học tại trường</td>";
                        // }
                        // else if (parseInt(dataget[i].type) === 2) {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Học sinh chuẩn bị nhập học</td>";
                        // }
                        // else if (parseInt(dataget[i].type) === 3) {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Học sinh dự kiến tuyển mới</td>";
                        // }
                        html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='getProfileSubById(" + dataget[i].profile_id + ", 3);'>" + dataget[i].profile_name + "</a></td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatDates(dataget[i].profile_birthday) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].nationals_name) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].profile_parentname) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].profile_household) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].tenxa) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].tenhuyen) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].schools_name) + "</td>";
                        if (parseInt(dataget[i].type) === 1 || parseInt(dataget[i].type) === 2) {
                            html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].old_level_cur) + "</td>";
                        }
                        else if (parseInt(dataget[i].type) === 3) {
                            html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].new_level_cur) + "</td>";
                        }
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatDates(dataget[i].profile_year) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatNumber_2(dataget[i].nhucau) + "</td>";


                        html_show += "</tr>";
                    }
                }
                if (reportType == "NGNA") {
                    html_header += '<th class="text-center" style="vertical-align:middle">STT</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle">Nhóm học sinh</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Tên học sinh</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Ngày sinh</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Dân tộc</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Cha mẹ</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Hộ khẩu</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Trường</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Lớp học</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Ngày nhập học</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Nhu cầu</th>';
                    //  html_header += '<th class="text-center" style="vertical-align:middle">Dự toán</th>';
                    html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
                }

                html_paging += '<div class="row">';
                html_paging += '<div class="col-md-2">';
                html_paging += '<label class="text-right col-md-9 control-label">Tổng </label>';
                html_paging += '<label class="col-md-3 control-label g_countRowsPaging">0</label>';
                html_paging += '</div>';
                html_paging += '<div class="col-md-3">';
                html_paging += '<label class="col-md-6 control-label text-right">Trang </label>';
                html_paging += '<div class="col-md-6">';
                html_paging += '<select class="form-control input-sm g_selectPaging">';
                html_paging += '<option value="0">0 / 20 </option>';
                html_paging += '</select>';
                html_paging += '</div>';
                html_paging += '</div>';
                html_paging += '<div class="col-md-3">';
                html_paging += '<label  class="col-md-6 control-label">Hiển thị: </label>';
                html_paging += '<div class="col-md-6">';
                html_paging += '<select name="drPagingDanhsach" id="drPagingDanhsach"  class="form-control input-sm pagination-show-row">';
                html_paging += '<option value="10">10</option>';
                html_paging += '<option value="15">15</option>';
                html_paging += '<option value="20">20</option>';
                html_paging += '</select>';
                html_paging += '</div>';
                html_paging += '</div>';
                html_paging += '<div class="col-md-4">';
                html_paging += '<label  class="col-md-2 control-label"></label>';
                html_paging += '<div class="col-md-10">';
                html_paging += '<ul class="pagination pagination-sm no-margin pull-right g_clickedPaging">';
                html_paging += '<li><a>&laquo;</a></li>';
                html_paging += '<li><a>0</a></li>';
                html_paging += '<li><a>&raquo;</a></li>';
                html_paging += '</ul>';
                html_paging += '</div>';
                html_paging += '</div>';
                html_paging += '</div>';
            }
            else {
                html_header += '<th class="text-center" style="vertical-align:middle">STT</th>';
                // html_header += '<th class="text-center" style="vertical-align:middle">Nhóm học sinh</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Tên học sinh</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Ngày sinh</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Dân tộc</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Cha mẹ</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Hộ khẩu</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Trường</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Lớp học</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Ngày nhập học</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Nhu cầu</th>';
                //  html_header += '<th class="text-center" style="vertical-align:middle">Dự toán</th>';
                html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
            }

            if (reportName === null || reportName === "" || reportType === null || reportType === "") {
                html_header += '<th class="text-center" style="vertical-align:middle">STT</th>';
                //  html_header += '<th class="text-center" style="vertical-align:middle">Nhóm học sinh</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Tên học sinh</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Ngày sinh</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Dân tộc</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Cha mẹ</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Hộ khẩu</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Trường</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Lớp học</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Ngày nhập học</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Nhu cầu</th>';
                //  html_header += '<th class="text-center" style="vertical-align:middle">Dự toán</th>';
                html_header += '<th class="text-center" style="vertical-align:middle"><button class="btn btn-info btn-xs">Chọn tất cả</button></th>';
                html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
            }

            // $('#divSearch').html(html_search);
            $('#headerTable').html(html_header);
            $('#dataLapDanhsachHS').html(html_show);
            // $('#divPaging').html(html_paging);
        }, error: function (dataget) {

        }
    });
};

// $("#PhongChooseAll").click(function(){
//     $('input#Phongchoose').not(this).prop('checked', this.checked);
//     alert("Choose1");
// });

// $('#PhongChooseAll').change(function() {
//     alert("Choose2");
//         if ($('#PhongChooseAll').prop('checked'))
//             $('[id*="Phongchoose"]').prop('checked', true);
//         else
//             $('[id*="Phongchoose"]').prop('checked', false);
// });

function clickChooseAll() {
    // $('input#PhongChooseAll').change(function() {
    //         if ($('input#PhongChooseAll').prop('checked'))
    //             $('[id*="Phongchoose"]').prop('checked', true);
    //         else
    //             $('[id*="Phongchoose"]').prop('checked', false);
    // });
    $('input#Phongchoose').each(function () {

        if (this.checked) {
            $(this).prop('checked', false);
        }
        else {
            $(this).prop('checked', true);
        }
    });
};

function loaddataDanhSachGroupB(row, keySearch = "", group = "", type = 0) {
    $('#typeCV').val(1);
    //0 nhu cau 1 du toan
    var msg_warning = "";

    msg_warning = validateDanhsachdenghi();


    if (msg_warning !== null && msg_warning !== "") {
        utility.messagehide("messageValidate", msg_warning, 1, 5000);
        return;
    }

    var reportName = $('#sltCongvan').val();
    var reportType = $('#sltLoaiChedo').val();


    var html_show = "";
    var o = {
        start: (GET_START_RECORD_NGHILC()),
        limit: row,
        REPORTNAME: reportName,
        REPORTTYPE: reportType,
        KEY: keySearch,
        GROUP: group
    };
    PostToServer('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/loadDataGroupA', o, function (datas) {

        SETUP_PAGING_NGHILC(datas, function () {
            loaddataDanhSachGroupB(row, keySearch, group);
        });




        $('#dataHeaderCVDL').html("");
        var html_search = "";
        var html_header = '';
        html_header += '<tr class="success">';
        var html_show = "";
        var html_paging = "";
        $('#dataLapDanhsachHS').html("");
        var dataget = datas.data;
        //console.log(dataget);
        if (dataget.length > 0) {

            html_search += '<input id="txtSearchProfile" type="text" class="form-control input-sm" placeholder="Tìm kiếm ">';
            html_search += '<span class="glyphicon glyphicon-search form-control-feedback"></span>';

            if (reportType == "MGHP" || reportType == "CPHT" || reportType == "HTAT") {
                html_header += '<th class="text-center" style="vertical-align:middle;width: 3%">STT</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 12%;">Tên học sinh</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 7%;">Ngày sinh</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 7%;">Dân tộc</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 10%;">Cha mẹ</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 24%;">Hộ khẩu thường trú</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 15%;">Trường</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 8%;">Lớp học</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 7%;">Ngày nhập học</th>';
                if (type == 0) {
                    html_header += '<th class="text-center" style="vertical-align:middle;width: 7%;">Nhu cầu</th>';
                } else {
                    html_header += '<th class="text-center" style="vertical-align:middle;width: 7%;">Dự toán</th>';
                }


                for (var i = 0; i < dataget.length; i++) {

                    html_show += "<tr><td class='text-center' style='vertical-align:middle'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                    html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='getProfileSubById(" + dataget[i].profile_id + ", 3);'>" + dataget[i].profile_name + "</a></td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'>" + formatDates(dataget[i].profile_birthday) + "</td>";
                    html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(dataget[i].nationals_name) + "</td>";
                    html_show += "<td style='vertical-align:middle'>" + ConvertString(dataget[i].profile_parentname) + "</td>";
                    html_show += "<td style='vertical-align:middle'>" + ConvertString(dataget[i].profile_household) + " - " + ConvertString(dataget[i].tenxa) + " - " + ConvertString(dataget[i].tenhuyen) + "</td>";
                    html_show += "<td style='vertical-align:middle'>" + ConvertString(dataget[i].schools_name) + "</td>";
                    if (parseInt(dataget[i].type) === 1 || parseInt(dataget[i].type) === 2) {
                        if (dataget[i].old_level_cur !== null && dataget[i].old_level_cur !== "") {
                            html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].old_level_cur) + "</td>";
                        }
                        else {
                            html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].old_level_old) + "</td>";
                        }
                    }
                    else if (parseInt(dataget[i].type) === 3) {
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].new_level_cur) + "</td>";
                    }
                    html_show += "<td class='text-right' style='vertical-align:middle'>" + formatDates(dataget[i].profile_year) + "</td>";
                    if (type == 0) {
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].nhu_cau) + "</td>";
                    } else {
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].du_toan) + "</td>";
                    }
                    html_show += "</tr>";
                }
            }
            if (reportType == "HTBT") {
                html_header += '<th class="text-center" style="vertical-align:middle;width:3%">STT</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 10%;">Tên học sinh</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 7%;">Ngày sinh</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 7%;">Dân tộc</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 8%;">Cha mẹ</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 20%;">Hộ khẩu thường trú</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 10%;">Trường</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 7%;">Lớp học</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 7%;">Ngày nhập học</th>';
                if (type == 0) {
                    html_header += '<th class="text-center" style="vertical-align:middle;width: 7%">Nhu cầu tiền ăn</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle;width: 7%">Nhu cầu tiền ở</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle;width: 7%">Nhu cầu VHTT</th>';
                } else {
                    html_header += '<th class="text-center" style="vertical-align:middle;width: 7%">Dự toán tiền ăn</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle;width: 7%">Dự toán tiền ở</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle;width: 7%">Dự toán VHTT</th>';
                }


                for (var i = 0; i < dataget.length; i++) {
                    html_show += "<tr><td class='text-center' style='vertical-align:middle'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                    html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='getProfileSubById(" + dataget[i].profile_id + ", 3);'>" + dataget[i].profile_name + "</a></td>";
                    html_show += "<td class='text-center' style='vertical-align:middle'>" + formatDates(dataget[i].profile_birthday) + "</td>";
                    html_show += "<td style='vertical-align:middle'>" + ConvertString(dataget[i].nationals_name) + "</td>";
                    html_show += "<td style='vertical-align:middle'>" + ConvertString(dataget[i].profile_parentname) + "</td>";
                    html_show += "<td cstyle='vertical-align:middle'>" + ConvertString(dataget[i].profile_household) + " - " + ConvertString(dataget[i].tenxa) + " - " + ConvertString(dataget[i].tenhuyen) + "</td>";
                    html_show += "<td style='vertical-align:middle'>" + ConvertString(dataget[i].schools_name) + "</td>";
                    if (parseInt(dataget[i].type) === 1 || parseInt(dataget[i].type) === 2) {
                        if (dataget[i].old_level_cur !== null && dataget[i].old_level_cur !== "") {
                            html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].old_level_cur) + "</td>";
                        }
                        else {
                            html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].old_level_old) + "</td>";
                        }
                    }
                    else if (parseInt(dataget[i].type) === 3) {
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].new_level_cur) + "</td>";
                    }
                    html_show += "<td class='text-right' style='vertical-align:middle'>" + formatDates(dataget[i].profile_year) + "</td>";
                    if (type == 0) {
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].nhucau_hotrotienan) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].nhucau_hotrotieno) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].nhucau_VHTT) + "</td>";
                    } else {
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].dutoan_hotrotienan) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].dutoan_hotrotieno) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].dutoan_VHTT) + "</td>";
                    }

                    html_show += "</tr>";
                }
            }
            if (reportType == "HSKT") {
                html_header += '<th class="text-center" style="vertical-align:middle;width: 3%">STT</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 10%;">Tên học sinh</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 7%;">Ngày sinh</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 8%">Dân tộc</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 8%;">Cha mẹ</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 24%">Hộ khẩu thường trú</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 10%;">Trường</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 5%;">Lớp học</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 7%;">Ngày nhập học</th>';
                if (type == 0) {
                    html_header += '<th class="text-center" style="vertical-align:middle;width: 8%">Nhu cầu học bổng</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle;width: 8%">Nhu cầu mua đồ dùng</th>';
                } else {
                    html_header += '<th class="text-center" style="vertical-align:middle;width: 8%">Dự toán học bổng</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle;width: 8%">Dự toán mua đồ dùng</th>';
                }


                for (var i = 0; i < dataget.length; i++) {

                    html_show += "<tr><td class='text-center' style='vertical-align:middle'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                    html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='getProfileSubById(" + dataget[i].profile_id + ", 3);'>" + dataget[i].profile_name + "</a></td>";
                    html_show += "<td class='text-center' style='vertical-align:middle'>" + formatDates(dataget[i].profile_birthday) + "</td>";
                    html_show += "<td style='vertical-align:middle'>" + ConvertString(dataget[i].nationals_name) + "</td>";
                    html_show += "<td style='vertical-align:middle'>" + ConvertString(dataget[i].profile_parentname) + "</td>";
                    html_show += "<td style='vertical-align:middle'>" + ConvertString(dataget[i].profile_household) + " - " + ConvertString(dataget[i].tenxa) + " - " + ConvertString(dataget[i].tenhuyen) + "</td>";
                    html_show += "<td style='vertical-align:middle'>" + ConvertString(dataget[i].schools_name) + "</td>";
                    if (parseInt(dataget[i].type) === 1 || parseInt(dataget[i].type) === 2) {
                        if (dataget[i].old_level_cur !== null && dataget[i].old_level_cur !== "") {
                            html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].old_level_cur) + "</td>";
                        }
                        else {
                            html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].old_level_old) + "</td>";
                        }
                    }
                    else if (parseInt(dataget[i].type) === 3) {
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].new_level_cur) + "</td>";
                    }
                    html_show += "<td class='text-right' style='vertical-align:middle'>" + formatDates(dataget[i].profile_year) + "</td>";
                    if (type == 0) {
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].nhucau_hocbong) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].nhucau_muadodung) + "</td>";
                    } else {
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].dutoan_hocbong) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].dutoan_muadodung) + "</td>";
                    }

                    html_show += "</tr>";
                }
            }
            if (reportType == "HTATHS" || reportType == "HSDTTS" || reportType == "HBHSDTNT") {
                html_header += '<th class="text-center" style="vertical-align:middlewidth: 5%">STT</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 10%;">Tên học sinh</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 7%;">Ngày sinh</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 8%">Dân tộc</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 8%;">Cha mẹ</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 25%">Hộ khẩu thường trú</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 15%;">Trường</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 7%;">Lớp học</th>';
                html_header += '<th class="text-center" style="vertical-align:middle;width: 8%;">Ngày nhập học</th>';
                if (type == 0) {
                    html_header += '<th class="text-center" style="vertical-align:middle;width: 8%">Nhu cầu</th>';
                } else {
                    html_header += '<th class="text-center" style="vertical-align:middle;width: 8%">Dự toán</th>';
                }


                for (var i = 0; i < dataget.length; i++) {

                    html_show += "<tr><td class='text-center' style='vertical-align:middle'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                    html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='getProfileSubById(" + dataget[i].profile_id + ", 3);'>" + dataget[i].profile_name + "</a></td>";
                    html_show += "<td class='text-center' style='vertical-align:middle'>" + formatDates(dataget[i].profile_birthday) + "</td>";
                    html_show += "<td style='vertical-align:middle'>" + ConvertString(dataget[i].nationals_name) + "</td>";
                    html_show += "<td style='vertical-align:middle'>" + ConvertString(dataget[i].profile_parentname) + "</td>";
                    html_show += "<td style='vertical-align:middle'>" + ConvertString(dataget[i].profile_household) + " - " + ConvertString(dataget[i].tenxa) + " - " + ConvertString(dataget[i].tenhuyen) + "</td>";
                    html_show += "<td style='vertical-align:middle'>" + ConvertString(dataget[i].schools_name) + "</td>";
                    if (parseInt(dataget[i].type) === 1 || parseInt(dataget[i].type) === 2) {
                        if (dataget[i].old_level_cur !== null && dataget[i].old_level_cur !== "") {
                            html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].old_level_cur) + "</td>";
                        }
                        else {
                            html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].old_level_old) + "</td>";
                        }
                    }
                    else if (parseInt(dataget[i].type) === 3) {
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].new_level_cur) + "</td>";
                    }
                    html_show += "<td class='text-right' style='vertical-align:middle'>" + formatDates(dataget[i].profile_year) + "</td>";
                    if (type == 0) {
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].nhucau) + "</td>";
                    } else {
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].dutoan) + "</td>";
                    }

                    html_show += "</tr>";
                }
            }
            if (reportType == "NGNA") {
                var year_NGNA = datas.year;
                html_header += '<th class="text-center" style="vertical-align:middle; width: 5%">STT</th>';
                html_header += '<th class="text-center" style="vertical-align:middle; width: 15%;">Tên trường</th>';
                html_header += '<th class="text-center" style="vertical-align:middle; width: 9%;">Số hs học kỳ 2 năm ' + (parseInt(year_NGNA) - 1) + '-' + year_NGNA + '</th>';
                html_header += '<th class="text-center" style="vertical-align:middle; width: 9%;">Số hs học kỳ 1 năm ' + year_NGNA + '-' + (parseInt(year_NGNA) + 1) + '</th>';
                html_header += '<th class="text-center" style="vertical-align:middle; width: 9%;">Số hs học kỳ 2 năm ' + year_NGNA + '-' + (parseInt(year_NGNA) + 1) + '</th>';
                html_header += '<th class="text-center" style="vertical-align:middle; width: 9%;">Số hs học kỳ 1 năm ' + (parseInt(year_NGNA) + 1) + '-' + (parseInt(year_NGNA) + 2) + '</th>';
                html_header += '<th class="text-center" style="vertical-align:middle; width: 9%;">Số người nấu ăn học kỳ 2 năm ' + (parseInt(year_NGNA) - 1) + '-' + year_NGNA + '</th>';
                html_header += '<th class="text-center" style="vertical-align:middle; width: 9%;">Số người nấu ăn học kỳ 1 năm ' + year_NGNA + '-' + (parseInt(year_NGNA) + 1) + '</th>';
                html_header += '<th class="text-center" style="vertical-align:middle; width: 9%;">Số người nấu ăn học kỳ 2 năm ' + year_NGNA + '-' + (parseInt(year_NGNA) + 1) + '</th>';
                html_header += '<th class="text-center" style="vertical-align:middle; width: 9%;">Số người nấu ăn học kỳ 1 năm ' + (parseInt(year_NGNA) + 1) + '-' + (parseInt(year_NGNA) + 2) + '</th>';
                if (type == 0) {
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 8%;">Nhu cầu</th>';
                } else {
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 8%;">Dự toán</th>';
                }


                for (var i = 0; i < dataget.length; i++) {
                    html_show += "<tr><td class='text-center' style='vertical-align:middle'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                    html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].schools_name) + "</td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'>" + (dataget[i].sohocsinhhocky2_old) + "</td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'>" + (dataget[i].sohocsinhhocky1_cur) + "</td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'>" + (dataget[i].sohocsinhhocky2_cur) + "</td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'>" + (dataget[i].sohocsinhhocky1_new) + "</td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'>" + (dataget[i].nguoinauanhocky2_old) + "</td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'>" + (dataget[i].nguoinauanhocky1_cur) + "</td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'>" + (dataget[i].nguoinauanhocky2_cur) + "</td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'>" + (dataget[i].nguoinauanhocky1_new) + "</td>";
                    if (type == 0) {
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].nhucau) + "</td>";
                    } else {
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].dutoan) + "</td>";
                    }

                }
            }

            html_paging += '<div class="row">';
            html_paging += '<div class="col-md-2">';
            html_paging += '<label class="text-right col-md-9 control-label">Tổng </label>';
            html_paging += '<label class="col-md-3 control-label g_countRowsPaging">0</label>';
            html_paging += '</div>';
            html_paging += '<div class="col-md-3">';
            html_paging += '<label class="col-md-6 control-label text-right">Trang </label>';
            html_paging += '<div class="col-md-6">';
            html_paging += '<select class="form-control input-sm g_selectPaging">';
            html_paging += '<option value="0">0 / 20 </option>';
            html_paging += '</select>';
            html_paging += '</div>';
            html_paging += '</div>';
            html_paging += '<div class="col-md-3">';
            html_paging += '<label  class="col-md-6 control-label">Hiển thị: </label>';
            html_paging += '<div class="col-md-6">';
            html_paging += '<select name="drPagingDanhsach" id="drPagingDanhsach"  class="form-control input-sm pagination-show-row">';
            html_paging += '<option value="10">10</option>';
            html_paging += '<option value="15">15</option>';
            html_paging += '<option value="20">20</option>';
            html_paging += '</select>';
            html_paging += '</div>';
            html_paging += '</div>';
            html_paging += '<div class="col-md-4">';
            html_paging += '<label  class="col-md-2 control-label"></label>';
            html_paging += '<div class="col-md-10">';
            html_paging += '<ul class="pagination pagination-sm no-margin pull-right g_clickedPaging">';
            html_paging += '<li><a>&laquo;</a></li>';
            html_paging += '<li><a>0</a></li>';
            html_paging += '<li><a>&raquo;</a></li>';
            html_paging += '</ul>';
            html_paging += '</div>';
            html_paging += '</div>';
            html_paging += '</div>';
        }
        else {
            html_header += '<th class="text-center" style="vertical-align:middle">STT</th>';
            html_header += '<th class="text-center" style="vertical-align:middle">Tên học sinh</th>';
            html_header += '<th class="text-center" style="vertical-align:middle">Ngày sinh</th>';
            html_header += '<th class="text-center" style="vertical-align:middle">Dân tộc</th>';
            html_header += '<th class="text-center" style="vertical-align:middle">Cha mẹ</th>';
            html_header += '<th class="text-center" style="vertical-align:middle">Hộ khẩu</th>';
            html_header += '<th class="text-center" style="vertical-align:middle">Trường</th>';
            html_header += '<th class="text-center" style="vertical-align:middle">Lớp học</th>';
            html_header += '<th class="text-center" style="vertical-align:middle">Ngày nhập học</th>';
            if (type == 0) {
                html_header += '<th class="text-center" style="vertical-align:middle">Nhu cầu</th>';
            } else {
                html_header += '<th class="text-center" style="vertical-align:middle">Dự toán</th>';
            }

            html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
        }

        if (reportName === null || reportName === "" || reportType === null || reportType === "") {
            html_header += '<th class="text-center" style="vertical-align:middle">STT</th>';
            html_header += '<th class="text-center" style="vertical-align:middle">Tên học sinh</th>';
            html_header += '<th class="text-center" style="vertical-align:middle">Ngày sinh</th>';
            html_header += '<th class="text-center" style="vertical-align:middle">Dân tộc</th>';
            html_header += '<th class="text-center" style="vertical-align:middle">Cha mẹ</th>';
            html_header += '<th class="text-center" style="vertical-align:middle">Hộ khẩu</th>';
            html_header += '<th class="text-center" style="vertical-align:middle">Trường</th>';
            html_header += '<th class="text-center" style="vertical-align:middle">Lớp học</th>';
            html_header += '<th class="text-center" style="vertical-align:middle">Ngày nhập học</th>';
            if (type == 0) {
                html_header += '<th class="text-center" style="vertical-align:middle">Nhu cầu</th>';
            } else {
                html_header += '<th class="text-center" style="vertical-align:middle">Dự toán</th>';
            }
            html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
        }
        html_header += '</tr>';
        $('#dataHeaderCVDL').html(html_header);
        //$('#headerTable').html(html_header);
        $('#dataLapDanhsachHS').html(html_show);
    }, function (datas) {
        console.log("loaddataDanhSachGroupB");
        console.log(datas);
    }, "", "", "");
};
function danhsachPhongtralai(objData) {

    $.ajax({
        type: "POST",
        url: '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/insertPhongtralai',
        data: objData,
        // dataType: 'json',
        contentType: false,//'application/json; charset=utf-8',
        cache: false,             // To unable request pages to be cached
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
        success: function (datas) {
            if (datas.success != null || datas.success != undefined) {
                utility.message("Thông báo", datas.success, null, 3000);
                $("#myModalRevertPhong").modal("hide");
                loaddataDanhSachGroupB($('#drPagingDanhsach').val(), $('#txtSearchProfileLapdanhsach').val());
            } else if (datas.error != null || datas.error != undefined) {
                utility.message("Thông báo", datas.error, null, 5000, 1);
            }
        }, error: function (datas) {

        }
    });
};

function exportExcelTruongDeNghi() {
    var message = "";
    message = validateDanhsachdenghi();
    if (message !== "") {
        utility.messagehide("messageValidate", message, 1, 5000);
        return;
    }

    var reportType = $('#sltLoaiChedo').val();
    var reportName = $('#sltCongvan').val();
    var objJson = JSON.stringify({ REPORTNAME: reportName, REPORTTYPE: reportType });
    //alert(objJson);
    window.open('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/exportExcel/' + objJson, '_blank');
    // $.ajax({
    //     type: "get",
    //     url:'/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/exportExcel/' + objJson,
    //     //data: objJson,
    //     //dataType: 'json',
    //     contentType: 'application/json; charset=utf-8',
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
    //     },
    //     success: function(data) {
    //         console.log(data);
    //     }, error: function(data) {
    //     }
    // });
}

function validateDanhsachdenghi() {
    var messageValidate = "";
    var reportType = $('#sltLoaiChedo').val();
    var reportName = $('#sltCongvan').val();

    if (reportName == null || reportName == "") {
        messageValidate = "Vui lòng chọn số công văn!";
        return messageValidate;
    }
    if (reportType == null || reportType == "") {
        messageValidate = "Vui lòng chọn chế độ!";
        return messageValidate;
    }

    return messageValidate;
}

function validateDanhsachtralai(arrData) {
    var messageValidate = "";
    var reportType = $('#sltLoaiChedo').val();
    var reportName = $('#sltCongvan').val();

    if (reportName == null || reportName == "") {
        messageValidate = "Vui lòng chọn số công văn!";
        return messageValidate;
    }
    if (reportType == null || reportType == "") {
        messageValidate = "Vui lòng chọn chế độ!";
        return messageValidate;
    }
    if (arrData == null || arrData == "") {
        messageValidate = "Vui lòng chọn học sinh!";
        return messageValidate;
    }

    return messageValidate;
}

//----------------------------------------------------------------Quản lý dự toán, chi trả--------------------------------------------------------------


function loaddataDutoan(row, keySearch = "", group = "") {

    var msg_warning = "";

    msg_warning = validateDanhsachdenghi();

    // alert(msg_warning);

    if (msg_warning !== null && msg_warning !== "") {
        utility.messagehide("messageValidate", msg_warning, 1, 5000);
        return;
    }

    var reportName = $('#sltCongvan').val();
    var reportType = $('#sltLoaiChedo').val();


    var html_show = "";
    var o = {
        start: (GET_START_RECORD_NGHILC()),
        limit: row,
        REPORTNAME: reportName,
        REPORTTYPE: reportType,
        KEY: keySearch,
        GROUP: group
    };
    $.ajax({
        type: "POST",
        url: '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/loadDataGroupB',
        data: JSON.stringify(o),
        dataType: 'json',
        contentType: 'application/json; charset=utf-8',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
        success: function (datas) {

            SETUP_PAGING_NGHILC(datas, function () {
                loaddataDutoan(row, keySearch, group);
            });

            // $('#divSearch').html("");
            $('#headerTable').html("");
            var html_search = "";
            var html_header = '';
            var html_show = "";
            var html_paging = "";
            $('#dataLapDutoan').html("");
            // $('#divPaging').html("");
            var dataget = datas.data;
            //console.log(dataget);
            if (dataget.length > 0) {

                html_search += '<input id="txtSearchProfile" type="text" class="form-control input-sm" placeholder="Tìm kiếm ">';
                html_search += '<span class="glyphicon glyphicon-search form-control-feedback"></span>';

                if (reportType == "MGHP" || reportType == "CPHT" || reportType == "HTAT") {
                    html_header += '<th class="text-center" style="vertical-align:middle">STT</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle"><input type="checkbox"  onclick="clickChooseAll()" /></th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle; width: 12%;">Nhóm học sinh</th>';

                    html_header += '<th class="text-center" style="vertical-align:middle; width: 8%;">Tên học sinh</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 7%;">Ngày sinh</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Dân tộc</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 8%;">Cha mẹ</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Thôn/ xóm</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Xã/ phường</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Huyện/ quận</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 12%;">Trường</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 5%;">Lớp học</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 7%;">Ngày nhập học</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle">Nhu cầu</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Dự toán</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle"><button class="btn btn-info btn-xs" onclick="clickB()">Chọn tất cả</button></th>';


                    for (var i = 0; i < dataget.length; i++) {

                        html_show += "<tr><td class='text-center' style='vertical-align:middle'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                        // html_show += "<td class='text-center' style='vertical-align:middle'><input type='checkbox' id='Phongchoose' value='"+dataget[i].profile_id+"' data-choose='"+dataget[i].profile_id+"' /></td>";

                        // if (parseInt(dataget[i].type) === 1) {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Học sinh đang học tại trường</td>";
                        // }
                        // else if (parseInt(dataget[i].type) === 2) {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Học sinh chuẩn bị nhập học</td>";
                        // }
                        // else if (parseInt(dataget[i].type) === 3) {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Học sinh dự kiến tuyển mới</td>";
                        // }
                        html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='getProfileSubById(" + dataget[i].profile_id + ", 3);'>" + dataget[i].profile_name + "</a></td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatDates(dataget[i].profile_birthday) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].nationals_name) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].profile_parentname) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].profile_household) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].tenxa) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].tenhuyen) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].schools_name) + "</td>";
                        if (parseInt(dataget[i].type) === 1 || parseInt(dataget[i].type) === 2) {
                            if (dataget[i].old_level_cur !== null && dataget[i].old_level_cur !== "") {
                                html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].old_level_cur) + "</td>";
                            }
                            else {
                                html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].old_level_old) + "</td>";
                            }
                        }
                        else if (parseInt(dataget[i].type) === 3) {
                            html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].new_level_cur) + "</td>";
                        }
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatDates(dataget[i].profile_year) + "</td>";
                        // html_show += "<td class='text-right' style='vertical-align:middle'>"+formatter(dataget[i].nhu_cau)+"</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].du_toan) + "</td>";

                        html_show += "</tr>";
                    }
                }
                if (reportType == "HTBT") {
                    html_header += '<th class="text-center" style="vertical-align:middle">STT</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle"><input type="checkbox"  onclick="clickChooseAll()" /></th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle; width: 10%;">Nhóm học sinh</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 8%;">Tên học sinh</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 7%;">Ngày sinh</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Dân tộc</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 8%;">Cha mẹ</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Thôn/ xóm</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Xã/ phường</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Huyện/ quận</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 10%;">Trường</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 5%;">Lớp học</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 7%;">Ngày nhập học</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle">Nhu cầu tiền ăn</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle">Nhu cầu tiền ở</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle">Nhu cầu VHTT</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Dự toán tiền ăn</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Dự toán tiền ở</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Dự toán VHTT</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle"><button class="btn btn-info btn-xs">Chọn tất cả</button></th>';

                    for (var i = 0; i < dataget.length; i++) {
                        // console.log(dataget[i].type);
                        html_show += "<tr><td class='text-center' style='vertical-align:middle'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                        // html_show += "<td class='text-center' style='vertical-align:middle'><input type='checkbox' id='Phongchoose' value='"+dataget[i].profile_id+"' data-choose='"+dataget[i].profile_id+"' /></td>";

                        // if (parseInt(dataget[i].type) === 1) {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Học sinh đang học tại trường</td>";
                        // }
                        // else if (parseInt(dataget[i].type) === 2) {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Học sinh chuẩn bị nhập học</td>";
                        // }
                        // else if (parseInt(dataget[i].type) === 3) {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Học sinh dự kiến tuyển mới</td>";
                        // }
                        html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='getProfileSubById(" + dataget[i].profile_id + ", 3);'>" + dataget[i].profile_name + "</a></td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatDates(dataget[i].profile_birthday) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].nationals_name) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].profile_parentname) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].profile_household) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].tenxa) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].tenhuyen) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].schools_name) + "</td>";
                        if (parseInt(dataget[i].type) === 1 || parseInt(dataget[i].type) === 2) {
                            if (dataget[i].old_level_cur !== null && dataget[i].old_level_cur !== "") {
                                html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].old_level_cur) + "</td>";
                            }
                            else {
                                html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].old_level_old) + "</td>";
                            }
                        }
                        else if (parseInt(dataget[i].type) === 3) {
                            html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].new_level_cur) + "</td>";
                        }
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatDates(dataget[i].profile_year) + "</td>";
                        // html_show += "<td class='text-right' style='vertical-align:middle'>"+formatter(dataget[i].nhucau_hotrotienan)+"</td>";
                        // html_show += "<td class='text-right' style='vertical-align:middle'>"+formatter(dataget[i].nhucau_hotrotieno)+"</td>";
                        // html_show += "<td class='text-right' style='vertical-align:middle'>"+formatter(dataget[i].nhucau_VHTT)+"</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].dutoan_hotrotienan) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].dutoan_hotrotieno) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].dutoan_VHTT) + "</td>";

                        html_show += "</tr>";
                    }
                }
                if (reportType == "HSKT") {
                    html_header += '<th class="text-center" style="vertical-align:middle">STT</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle"><input type="checkbox"  onclick="clickChooseAll()" /></th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle; width: 10%;">Nhóm học sinh</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 8%;">Tên học sinh</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 7%;">Ngày sinh</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Dân tộc</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 8%;">Cha mẹ</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Thôn/ xóm</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Xã/ phường</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Huyện/ quận</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 10%;">Trường</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 5%;">Lớp học</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 7%;">Ngày nhập học</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle">Nhu cầu học bổng</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle">Nhu cầu mua đồ dùng</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Dự toán học bổng</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Dự toán mua đồ dùng</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle"><button class="btn btn-info btn-xs">Chọn tất cả</button></th>';

                    for (var i = 0; i < dataget.length; i++) {

                        html_show += "<tr><td class='text-center' style='vertical-align:middle'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                        // html_show += "<td class='text-center' style='vertical-align:middle'><input type='checkbox' id='Phongchoose' value='"+dataget[i].profile_id+"' data-choose='"+dataget[i].profile_id+"' /></td>";

                        // if (parseInt(dataget[i].type) === 1) {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Học sinh đang học tại trường</td>";
                        // }
                        // else if (parseInt(dataget[i].type) === 2) {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Học sinh chuẩn bị nhập học</td>";
                        // }
                        // else if (parseInt(dataget[i].type) === 3) {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Học sinh dự kiến tuyển mới</td>";
                        // }
                        html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='getProfileSubById(" + dataget[i].profile_id + ", 3);'>" + dataget[i].profile_name + "</a></td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatDates(dataget[i].profile_birthday) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].nationals_name) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].profile_parentname) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].profile_household) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].tenxa) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].tenhuyen) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].schools_name) + "</td>";
                        if (parseInt(dataget[i].type) === 1 || parseInt(dataget[i].type) === 2) {
                            if (dataget[i].old_level_cur !== null && dataget[i].old_level_cur !== "") {
                                html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].old_level_cur) + "</td>";
                            }
                            else {
                                html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].old_level_old) + "</td>";
                            }
                        }
                        else if (parseInt(dataget[i].type) === 3) {
                            html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].new_level_cur) + "</td>";
                        }
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatDates(dataget[i].profile_year) + "</td>";
                        // html_show += "<td class='text-right' style='vertical-align:middle'>"+formatter(dataget[i].nhucau_hocbong)+"</td>";
                        // html_show += "<td class='text-right' style='vertical-align:middle'>"+formatter(dataget[i].nhucau_muadodung)+"</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].dutoan_hocbong) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].dutoan_muadodung) + "</td>";

                        html_show += "</tr>";
                    }
                }
                if (reportType == "HTATHS" || reportType == "HSDTTS" || reportType == "HBHSDTNT") {
                    html_header += '<th class="text-center" style="vertical-align:middle">STT</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle"><input type="checkbox"  onclick="clickChooseAll()" /></th>';
                    //  html_header += '<th class="text-center" style="vertical-align:middle; width: 12%;">Nhóm học sinh</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 8%;">Tên học sinh</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 7%;">Ngày sinh</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Dân tộc</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 8%;">Cha mẹ</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Thôn/ xóm</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Xã/ phường</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Huyện/ quận</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 12%;">Trường</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 5%;">Lớp học</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 7%;">Ngày nhập học</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle">Nhu cầu</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Dự toán</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle"><button class="btn btn-info btn-xs">Chọn tất cả</button></th>';


                    for (var i = 0; i < dataget.length; i++) {

                        html_show += "<tr><td class='text-center' style='vertical-align:middle'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                        // html_show += "<td class='text-center' style='vertical-align:middle'><input type='checkbox' id='Phongchoose' value='"+dataget[i].profile_id+"' data-choose='"+dataget[i].profile_id+"' /></td>";
                        // if (parseInt(dataget[i].type) === 1) {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Học sinh đang học tại trường</td>";
                        // }
                        // else if (parseInt(dataget[i].type) === 2) {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Học sinh chuẩn bị nhập học</td>";
                        // }
                        // else if (parseInt(dataget[i].type) === 3) {
                        //     html_show += "<td class='text-left' style='vertical-align:middle'>Học sinh dự kiến tuyển mới</td>";
                        // }
                        html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='getProfileSubById(" + dataget[i].profile_id + ", 3);'>" + dataget[i].profile_name + "</a></td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatDates(dataget[i].profile_birthday) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].nationals_name) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].profile_parentname) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].profile_household) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].tenxa) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].tenhuyen) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].schools_name) + "</td>";
                        if (parseInt(dataget[i].type) === 1 || parseInt(dataget[i].type) === 2) {
                            if (dataget[i].old_level_cur !== null && dataget[i].old_level_cur !== "") {
                                html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].old_level_cur) + "</td>";
                            }
                            else {
                                html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].old_level_old) + "</td>";
                            }
                        }
                        else if (parseInt(dataget[i].type) === 3) {
                            html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].new_level_cur) + "</td>";
                        }
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatDates(dataget[i].profile_year) + "</td>";
                        // html_show += "<td class='text-right' style='vertical-align:middle'>"+formatter(dataget[i].nhucau)+"</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].dutoan) + "</td>";

                        html_show += "</tr>";
                    }
                }
                if (reportType == "NGNA") {
                    var year_NGNA = datas.year;
                    html_header += '<th class="text-center" style="vertical-align:middle">STT</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 15%;">Tên trường</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 10%;">Số hs học kỳ 2 năm ' + (parseInt(year_NGNA) - 1) + '-' + year_NGNA + '</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 10%;">Số hs học kỳ 1 năm ' + year_NGNA + '-' + (parseInt(year_NGNA) + 1) + '</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 10%;">Số hs học kỳ 2 năm ' + year_NGNA + '-' + (parseInt(year_NGNA) + 1) + '</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 10%;">Số hs học kỳ 1 năm ' + (parseInt(year_NGNA) + 1) + '-' + (parseInt(year_NGNA) + 2) + '</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 10%;">Số người nấu ăn học kỳ 2 năm ' + (parseInt(year_NGNA) - 1) + '-' + year_NGNA + '</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 10%;">Số người nấu ăn học kỳ 1 năm ' + year_NGNA + '-' + (parseInt(year_NGNA) + 1) + '</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 10%;">Số người nấu ăn học kỳ 2 năm ' + year_NGNA + '-' + (parseInt(year_NGNA) + 1) + '</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle; width: 10%;">Số người nấu ăn học kỳ 1 năm ' + (parseInt(year_NGNA) + 1) + '-' + (parseInt(year_NGNA) + 2) + '</th>';
                    // html_header += '<th class="text-center" style="vertical-align:middle">Nhu cầu</th>';
                    html_header += '<th class="text-center" style="vertical-align:middle">Dự toán</th>';

                    for (var i = 0; i < dataget.length; i++) {
                        html_show += "<tr><td class='text-center' style='vertical-align:middle'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                        html_show += "<td class='text-left' style='vertical-align:middle'>" + ConvertString(dataget[i].schools_name) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + (dataget[i].sohocsinhhocky2_old) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + (dataget[i].sohocsinhhocky1_cur) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + (dataget[i].sohocsinhhocky2_cur) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + (dataget[i].sohocsinhhocky1_new) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + (dataget[i].nguoinauanhocky2_old) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + (dataget[i].nguoinauanhocky1_cur) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + (dataget[i].nguoinauanhocky2_cur) + "</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + (dataget[i].nguoinauanhocky1_new) + "</td>";
                        // html_show += "<td class='text-right' style='vertical-align:middle'>"+formatter(dataget[i].nhucau)+"</td>";
                        html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(dataget[i].dutoan) + "</td>";
                    }
                }

                html_paging += '<div class="row">';
                html_paging += '<div class="col-md-2">';
                html_paging += '<label class="text-right col-md-9 control-label">Tổng </label>';
                html_paging += '<label class="col-md-3 control-label g_countRowsPaging">0</label>';
                html_paging += '</div>';
                html_paging += '<div class="col-md-3">';
                html_paging += '<label class="col-md-6 control-label text-right">Trang </label>';
                html_paging += '<div class="col-md-6">';
                html_paging += '<select class="form-control input-sm g_selectPaging">';
                html_paging += '<option value="0">0 / 20 </option>';
                html_paging += '</select>';
                html_paging += '</div>';
                html_paging += '</div>';
                html_paging += '<div class="col-md-3">';
                html_paging += '<label  class="col-md-6 control-label">Hiển thị: </label>';
                html_paging += '<div class="col-md-6">';
                html_paging += '<select name="drPagingDanhsach" id="drPagingDanhsach"  class="form-control input-sm pagination-show-row">';
                html_paging += '<option value="10">10</option>';
                html_paging += '<option value="15">15</option>';
                html_paging += '<option value="20">20</option>';
                html_paging += '</select>';
                html_paging += '</div>';
                html_paging += '</div>';
                html_paging += '<div class="col-md-4">';
                html_paging += '<label  class="col-md-2 control-label"></label>';
                html_paging += '<div class="col-md-10">';
                html_paging += '<ul class="pagination pagination-sm no-margin pull-right g_clickedPaging">';
                html_paging += '<li><a>&laquo;</a></li>';
                html_paging += '<li><a>0</a></li>';
                html_paging += '<li><a>&raquo;</a></li>';
                html_paging += '</ul>';
                html_paging += '</div>';
                html_paging += '</div>';
                html_paging += '</div>';
            }
            else {
                html_header += '<th class="text-center" style="vertical-align:middle">STT</th>';
                // html_header += '<th class="text-center" style="vertical-align:middle"><input type="checkbox"  onclick="clickChooseAll()" /></th>';
                // html_header += '<th class="text-center" style="vertical-align:middle">Nhóm học sinh</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Tên học sinh</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Ngày sinh</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Dân tộc</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Cha mẹ</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Hộ khẩu</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Trường</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Lớp học</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Ngày nhập học</th>';
                // html_header += '<th class="text-center" style="vertical-align:middle">Nhu cầu</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Dự toán</th>';
                // html_header += '<th class="text-center" style="vertical-align:middle"><button class="btn btn-info btn-xs">Chọn tất cả</button></th>';
                html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
            }

            if (reportName === null || reportName === "" || reportType === null || reportType === "") {
                html_header += '<th class="text-center" style="vertical-align:middle">STT</th>';
                // html_header += '<th class="text-center" style="vertical-align:middle"><input type="checkbox"  onclick="clickChooseAll()" /></th>';
                // html_header += '<th class="text-center" style="vertical-align:middle">Nhóm học sinh</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Tên học sinh</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Ngày sinh</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Dân tộc</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Cha mẹ</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Hộ khẩu</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Trường</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Lớp học</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Ngày nhập học</th>';
                // html_header += '<th class="text-center" style="vertical-align:middle">Nhu cầu</th>';
                html_header += '<th class="text-center" style="vertical-align:middle">Dự toán</th>';
                // html_header += '<th class="text-center" style="vertical-align:middle"><button class="btn btn-info btn-xs">Chọn tất cả</button></th>';
                html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
            }

            // $('#divSearch').html(html_search);
            $('#headerTable').html(html_header);
            $('#dataLapDutoan').html(html_show);
            // $('#divPaging').html(html_paging);
        }, error: function (dataget) {

        }
    });
};

//----------------------------------------------------------------Search Danh sách đề nghị đã lập------------------------------------------------------------------
function autocompleteSearchDenghidalap(id, level, type = 0) {
    var keySearch = "";
    $('#' + id).autocomplete({
        source: function (request, response) {
            keySearch = $.ui.autocomplete.escapeRegex(request.term).replace(/[%\\\-]/g, '');
            GET_INITIAL_NGHILC();
            if (keySearch.length >= 2) {

                if (level == 0) {
                    if (parseInt($('#school-per').val()) != 0 && ($('#school-per').val() + '').split('-').length == 1) {
                        loadTongHopCongVanBySchool($('#school-per').val(), keySearch, type);
                    } else {
                        loadTongHopCongVanBySchool(null, keySearch, type);
                    }
                } else if (level == 1) {
                    loadDanhSachByPhongSo($('#drPagingDanhsachtonghop').val(), $('#drSchoolTHCD').val(), $('#sltLoaiCongvan').val(), keySearch);
                } else if (level == 2) {
                    loadDanhSachHSDaLap($('#drPagingDanhsachtonghop').val(), keySearch);
                } else if (level == 3) {
                    danhsachdenghi($('#sltSchools').val(), keySearch);
                }


            } else if (keySearch.length < 2) {
                if (level == 0) {
                    if (parseInt($('#school-per').val()) != 0 && ($('#school-per').val() + '').split('-').length == 1) {
                        loadTongHopCongVanBySchool($('#school-per').val(), "", type);
                    } else {
                        loadTongHopCongVanBySchool(null, "", type);
                    }
                } else if (level == 1) {
                    loadDanhSachByPhongSo($('#drPagingDanhsachtonghop').val(), $('#drSchoolTHCD').val(), $('#sltLoaiCongvan').val(), keySearch);
                } else if (level == 2) {
                    loadDanhSachHSDaLap($('#drPagingDanhsachtonghop').val(), keySearch);
                } else if (level == 3) {
                    danhsachdenghi($('#sltSchools').val(), keySearch);
                }

            }
        },
        minLength: 0,
        delay: 222,
        autofocus: true
    });
};

function autocomChangeSubProfile(idControl, number = null) {
    var keySearch = "";
    $('#' + idControl).autocomplete({
        source: function (request, response) {
            keySearch = $.ui.autocomplete.escapeRegex(request.term).replace(/[%\\\-]/g, '');
            //console.log(keySearch.length);
            if (keySearch.length >= 2) {
                GET_INITIAL_NGHILC();


            } else if (keySearch.length < 2) {
                GET_INITIAL_NGHILC();
                loadDataSubject();

            }
        },
        minLength: 0,
        delay: 222,
        autofocus: true
    });
};

function autocomUpdateSubProfile(idControl, number = null) {
    var keySearch = "";
    $('#' + idControl).autocomplete({
        source: function (request, response) {
            keySearch = $.ui.autocomplete.escapeRegex(request.term).replace(/[%\\\-]/g, '');
            //console.log(keySearch.length);
            if (keySearch.length >= 2) {
                GET_INITIAL_NGHILC();
                loadDataSubject(keySearch);

            } else if (keySearch.length < 2) {
                GET_INITIAL_NGHILC();
                loadDataSubject();

            }
        },
        minLength: 0,
        delay: 222,
        autofocus: true
    });
};


/////// NGHỊ LÊ ////////

function loadSoCongVanDanhSachChoPheDuyet(keySearch = null) {

    var row = $('#drPagingDanhsachtonghop').val();
    var o = {}
    if (keySearch != null && keySearch != '') {
        o = {
            id_school: $('#drSchoolTHCD').val(),
            start: (GET_START_RECORD_NGHILC()),
            limit: row,
            keyword: keySearch
        }
    } else {
        o = {
            id_school: $('#drSchoolTHCD').val(),
            start: (GET_START_RECORD_NGHILC()),
            limit: row,

        }
    }

    PostToServer('/ho-so/phe-duyet/cong-van-cho-phe-duyet', o, function (result) {
        $('#cmisGridHeaders').html('');
        var html_header = '';
        html_header += '<th class="text-center" style="vertical-align:middle">STT</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">Tên trường</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">Số công văn</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">Số HS chờ duyệt</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">Số HS đã duyệt</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">Số HS trả lại</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">Ngày đề nghị</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">Ngày kết thúc</th>';
        $('#cmisGridHeaders').html(html_header);
        SETUP_PAGING_NGHILC(result, function () {
            loadSoCongVanDanhSachChoPheDuyet();
        });
        $('#dataListApproved').html("");
        var dataget = result.data;
        var html_show = "";
        if (dataget.length > 0) {
            for (var i = 0; i < dataget.length; i++) {
                html_show += "<tr><td class='text-center' style='vertical-align:middle'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='getCongVanBySchool(" + parseInt(dataget[i].report_id_truong) + ");'>" + dataget[i].schools_name + "</a></td>";
                html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='getCongVan(" + parseInt(dataget[i].report_id_truong) + ",\"" + dataget[i].report_name + "\", 1);'>" + dataget[i].report_name + "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(dataget[i].report_waiting) + "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(dataget[i].report_approved) + "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(dataget[i].report_reverted) + "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(dataget[i].report_date) + "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + formatDates(dataget[i].report_enddate) + "</td>";
                html_show += "</tr>";
            }
        }
        else {
            html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
        }
        $('#dataListApproved').html(html_show);
    }, function (result) {
        console.log("loadSoCongVanDanhSachChoPheDuyet: " + result);
    }, "btnLoadDataPhong", "", "");
};

loadDanhSachChoDuyet = function () {
    var show_html = "";
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 3%">STT</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 10%">Tên học sinh</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Ngày sinh</th>';
    //show_html += '<th  class="text-center" style="vertical-align:middle;width: 8%">Trường học</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Lớp học</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ MGHP</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ CPHT</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ AT TEMG</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ BT TA</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ BT TO</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ BT VHTT</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ TA HS</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ HSKT HB</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ HSKT DDHT</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ HB HSDTNT</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ HSDTTS</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Tổng tiền</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 8%">Chức năng</th>';
    // show_html += '<th  class="text-center" colspan="2" style="vertical-align:middle;width: 10%">';
    // show_html += '<select name="sltTrangthai" id="sltTrangthai">';
    // show_html += '<option value="">---Tất cả---</option>';
    // show_html += '<option value="CHO">Chờ phê duyệt</option>';
    // show_html += '<option value="DA">Đã phê duyệt</option>';
    // show_html += '</select></th>';
    $('#cmisGridHeaders').html(show_html);
    $('#dataListApproved').html("");
    GET_INITIAL_NGHILC();
    loadDanhSachChoPheDuyet($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
};

function loadSoCongVanDanhSachDaPheDuyet(keySearch = null) {

    var row = $('#drPagingDanhsachtonghop').val();
    var o = {}
    if (keySearch != null && keySearch != '') {
        o = {
            id_school: $('#drSchoolTHCD').val(),
            start: (GET_START_RECORD_NGHILC()),
            limit: row,
            keyword: keySearch
        }
    } else {
        o = {
            id_school: $('#drSchoolTHCD').val(),
            start: (GET_START_RECORD_NGHILC()),
            limit: row,

        }
    }

    PostToServer('/ho-so/phe-duyet/cong-van-da-phe-duyet', o, function (result) {
        $('#cmisGridHeaders').html('');
        var html_header = '';
        html_header += '<th class="text-center" style="vertical-align:middle">STT</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">Tên trường</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">Số công văn</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">Số HS chờ duyệt</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">Số HS đã duyệt</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">Số HS trả lại</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">Ngày đề nghị</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">Ngày kết thúc</th>';
        $('#cmisGridHeaders').html(html_header);
        SETUP_PAGING_NGHILC(result, function () {
            loadSoCongVanDanhSachDaPheDuyet();
        });
        $('#dataListApproved').html("");
        var dataget = result.data;
        var html_show = "";
        if (dataget.length > 0) {
            for (var i = 0; i < dataget.length; i++) {
                html_show += "<tr><td class='text-center' style='vertical-align:middle'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='getCongVanBySchool(" + parseInt(dataget[i].report_id_truong) + ");'>" + dataget[i].schools_name + "</a></td>";
                html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='getCongVan(" + parseInt(dataget[i].report_id_truong) + ",\"" + dataget[i].report_name + "\", 2);'>" + dataget[i].report_name + "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(dataget[i].report_waiting) + "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(dataget[i].report_approved) + "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(dataget[i].report_reverted) + "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(dataget[i].report_date) + "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + formatDates(dataget[i].report_enddate) + "</td>";
                html_show += "</tr>";
            }
        }
        else {
            html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
        }
        $('#dataListApproved').html(html_show);
    }, function (result) {
        console.log("loadSoCongVanDanhSachDaPheDuyet: " + result);
    }, "btnLoadDataPhong", "", "");
};

loadDanhSachDaDuyet = function () {
    var show_html = "";
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 3%">STT</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 10%">Tên học sinh</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Ngày sinh</th>';
    //show_html += '<th  class="text-center" style="vertical-align:middle;width: 8%">Trường học</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Lớp học</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ MGHP</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ CPHT</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ AT TEMG</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ BT TA</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ BT TO</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ BT VHTT</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ TA HS</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ HSKT HB</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ HSKT DDHT</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ HB HSDTNT</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ HSDTTS</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Tổng tiền</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 8%">Chức năng</th>';
    $('#cmisGridHeaders').html(show_html);
    $('#dataListApproved').html("");
    GET_INITIAL_NGHILC();
    loadDanhSachDaPheDuyet($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
};

loadDanhSachDaTraLai = function () {
    var show_html = "";
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 3%">STT</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 10%">Tên học sinh</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Ngày sinh</th>';
    //show_html += '<th  class="text-center" style="vertical-align:middle;width: 8%">Trường học</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Lớp học</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ MGHP</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ CPHT</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ AT TEMG</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ BT TA</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ BT TO</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ BT VHTT</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ TA HS</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ HSKT HB</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ HSKT DDHT</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ HB HSDTNT</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ HSDTTS</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Tổng tiền</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 8%">Chức năng</th>';
    $('#cmisGridHeaders').html(show_html);
    $('#dataListApproved').html("");
    GET_INITIAL_NGHILC();
    loadDanhSachTraLai($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
};

var loaiDanhSach = 0;
loadDanhSachTongHop = function (type = 0) {
    loaiDanhSach = type;
    var show_html = "";
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 3%">STT</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 10%">Tên học sinh</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Ngày sinh</th>';
    //show_html += '<th  class="text-center" style="vertical-align:middle;width: 8%">Trường học</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Lớp học</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ MGHP</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ CPHT</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ AT TEMG</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ BT TA</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ BT TO</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ BT VHTT</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ TA HS</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ HSKT HB</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ HSKT DDHT</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ HB HSDTNT</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ HSDTTS</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 5%">Tổng tiền</th>';
    show_html += '<th  class="text-center" style="vertical-align:middle;width: 8%">Chức năng</th>';
    $('#cmisGridHeaders').html(show_html);
    $('#dataListApproved').html("");
    // alert(type);
    GET_INITIAL_NGHILC();
    loadDanhSachByCongVan($('#drPagingDanhsachtonghop').val(), $('#txtSearchPheDuyet').val(), type);
};
function loadUnReportBySchool(school_id, callback) {
    if (school_id != null && school_id > 0 && school_id != '') {
        GetFromServer('/ho-so/hoc-sinh/loadComboUnReportBySchool/' + school_id, function (dataget) {
            $('#sltCongvan').html("");
            var html_show = "";

            if (dataget.length > 0) {
                html_show += "<option value=''>--- Chọn số công văn ---</option>";
                for (var i = 0; i < dataget.length; i++) {
                    html_show += "<option value='" + dataget[i].report_name + "'>" + dataget[i].report_name + "</option>";
                }
                $('#sltCongvan').html(html_show);
            } else {
                $('#sltCongvan').html("<option value=''>-- Chưa có công văn nào --</option>");
            }

            if (callback != null) { callback(dataget); }
        }, function (dataget) {
            console.log("loadReportBySchool: " + dataget);
        }, "", "", "");
    } else {
        $('#sltCongvan').html("");
    }
};


function loadSoCongVanDanhSachTraLai(keySearch = null) {
    var row = $('#drPagingDanhsachtonghop').val();
    var o = {}
    if (keySearch != null && keySearch != '') {
        o = {
            id_school: $('#drSchoolTHCD').val(),
            start: (GET_START_RECORD_NGHILC()),
            limit: row,
            keyword: keySearch
        }
    } else {
        o = {
            id_school: $('#drSchoolTHCD').val(),
            start: (GET_START_RECORD_NGHILC()),
            limit: row,

        }
    }

    PostToServer('/ho-so/phe-duyet/cong-van-tra-lai', o, function (result) {
        $('#cmisGridHeaders').html('');
        var html_header = '';
        html_header += '<th class="text-center" style="vertical-align:middle">STT</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">Tên trường</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">Số công văn</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">Số HS chờ duyệt</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">Số HS đã duyệt</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">Số HS trả lại</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">Ngày đề nghị</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">Ngày kết thúc</th>';
        $('#cmisGridHeaders').html(html_header);
        SETUP_PAGING_NGHILC(result, function () {
            loadSoCongVanDanhSachDaPheDuyet();
        });
        $('#dataListApproved').html("");
        var dataget = result.data;
        var html_show = "";
        if (dataget.length > 0) {
            for (var i = 0; i < dataget.length; i++) {
                html_show += "<tr><td class='text-center' style='vertical-align:middle'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='getCongVanBySchool(" + parseInt(dataget[i].report_id_truong) + ");'>" + dataget[i].schools_name + "</a></td>";
                html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='getCongVan(" + parseInt(dataget[i].report_id_truong) + ",\"" + dataget[i].report_name + "\", 3);'>" + dataget[i].report_name + "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(dataget[i].report_waiting) + "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(dataget[i].report_approved) + "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(dataget[i].report_reverted) + "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(dataget[i].report_date) + "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + formatDates(dataget[i].report_enddate) + "</td>";
                html_show += "</tr>";
            }
        }
        else {
            html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
        }
        $('#dataListApproved').html(html_show);
    }, function (result) {
        console.log("loadSoCongVanDanhSachTraLai: " + result);
    }, "btnLoadDataPhong", "", "");
};


function loadDanhSachSoCongVan(keySearch = null) {

    var row = $('#drPagingDanhsachtonghop').val();
    var o = {}
    if (keySearch != null && keySearch != '') {
        o = {
            id_school: $('#drSchoolTHCD').val(),
            start: (GET_START_RECORD_NGHILC()),
            limit: row,
            keyword: keySearch
        }
    } else {
        o = {
            id_school: $('#drSchoolTHCD').val(),
            start: (GET_START_RECORD_NGHILC()),
            limit: row,

        }
    }

    PostToServer('/ho-so/phe-duyet/cong-van', o, function (result) {
        $('#cmisGridHeaders').html('');
        var html_header = '';
        html_header += '<th class="text-center" style="vertical-align:middle">STT</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">Tên trường</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">Số công văn</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">HS trường gửi</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">HS chờ PGD duyệt</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">HS PGD đã duyệt</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">HS PGD trả lại</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">HS PGD yêu cầu thu hồi</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">HS chờ PTC duyệt</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">HS PTC đã duyệt</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">HS PTC trả lại</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">HS PTC yêu cầu thu hồi</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">HS chờ Sở duyệt</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">HS Sở đã duyệt</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">HS Sở trả lại</th>';
        html_header += '<th class="text-center" style="vertical-align:middle">Ngày đề nghị</th>';
        //html_header += '<th class="text-center" style="vertical-align:middle">Ngày kết thúc</th>';
        $('#cmisGridHeaders').html(html_header);
        SETUP_PAGING_NGHILC(result, function () {
            loadDanhSachSoCongVan();
        });
        $('#dataListApproved').html("");
        var dataget = result.data;
        var html_show = "";
        if (dataget.length > 0) {
            for (var i = 0; i < dataget.length; i++) {
                html_show += "<tr><td class='text-center' style='vertical-align:middle'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='getCongVanBySchool(" + parseInt(dataget[i].report_school_id) + ");'>" + dataget[i].schools_name + "</a></td>";
                html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick=''>" + dataget[i].name + "</td>";

                if (parseInt(dataget[i].report_approved) > 0) {
                    html_show += "<td class='text-center' style='vertical-align:middle'><a href='javascript:;' onclick='getCongVan(" + parseInt(dataget[i].report_school_id) + ",\"" + dataget[i].name + "\", 1);'>" + ConvertString(dataget[i].report_approved) + "</td>";
                }
                else {
                    html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(dataget[i].report_approved) + "</td>";
                }

                if (parseInt(dataget[i].report_waiting_phongGD) > 0) {
                    html_show += "<td class='text-center' style='vertical-align:middle'><a href='javascript:;' onclick='getCongVan(" + parseInt(dataget[i].report_school_id) + ",\"" + dataget[i].name + "\", 2);'>" + ConvertString(dataget[i].report_waiting_phongGD) + "</td>";
                }
                else {
                    html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(dataget[i].report_waiting_phongGD) + "</td>";
                }

                if (parseInt(dataget[i].report_approved_phongGD) > 0) {
                    html_show += "<td class='text-center' style='vertical-align:middle'><a href='javascript:;' onclick='getCongVan(" + parseInt(dataget[i].report_school_id) + ",\"" + dataget[i].name + "\", 3);'>" + ConvertString(dataget[i].report_approved_phongGD) + "</td>";
                }
                else {
                    html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(dataget[i].report_approved_phongGD) + "</td>";
                }

                if (parseInt(dataget[i].report_reverted_phongGD) > 0) {
                    html_show += "<td class='text-center' style='vertical-align:middle'><a href='javascript:;' onclick='getCongVan(" + parseInt(dataget[i].report_school_id) + ",\"" + dataget[i].name + "\", 4);'>" + ConvertString(dataget[i].report_reverted_phongGD) + "</td>";
                }
                else {
                    html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(dataget[i].report_reverted_phongGD) + "</td>";
                }

                if (parseInt(dataget[i].report_reload_phongGD) > 0) {
                    html_show += "<td class='text-center' style='vertical-align:middle'><a href='javascript:;' onclick='getCongVan(" + parseInt(dataget[i].report_school_id) + ",\"" + dataget[i].name + "\", 11);'>" + ConvertString(dataget[i].report_reload_phongGD) + "</td>";
                }
                else {
                    html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(dataget[i].report_reload_phongGD) + "</td>";
                }

                if (parseInt(dataget[i].report_waiting_phongTC) > 0) {
                    html_show += "<td class='text-center' style='vertical-align:middle'><a href='javascript:;' onclick='getCongVan(" + parseInt(dataget[i].report_school_id) + ",\"" + dataget[i].name + "\", 5);'>" + ConvertString(dataget[i].report_waiting_phongTC) + "</td>";
                }
                else {
                    html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(dataget[i].report_waiting_phongTC) + "</td>";
                }

                if (parseInt(dataget[i].report_approved_phongTC) > 0) {
                    html_show += "<td class='text-center' style='vertical-align:middle'><a href='javascript:;' onclick='getCongVan(" + parseInt(dataget[i].report_school_id) + ",\"" + dataget[i].name + "\", 6);'>" + ConvertString(dataget[i].report_approved_phongTC) + "</td>";
                }
                else {
                    html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(dataget[i].report_approved_phongTC) + "</td>";
                }

                if (parseInt(dataget[i].report_reverted_phongTC) > 0) {
                    html_show += "<td class='text-center' style='vertical-align:middle'><a href='javascript:;' onclick='getCongVan(" + parseInt(dataget[i].report_school_id) + ",\"" + dataget[i].name + "\", 7);'>" + ConvertString(dataget[i].report_reverted_phongTC) + "</td>";
                }
                else {
                    html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(dataget[i].report_reverted_phongTC) + "</td>";
                }

                if (parseInt(dataget[i].report_reload_phongTC) > 0) {
                    html_show += "<td class='text-center' style='vertical-align:middle'><a href='javascript:;' onclick='getCongVan(" + parseInt(dataget[i].report_school_id) + ",\"" + dataget[i].name + "\", 12);'>" + ConvertString(dataget[i].report_reload_phongTC) + "</td>";
                }
                else {
                    html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(dataget[i].report_reload_phongTC) + "</td>";
                }

                if (parseInt(dataget[i].report_waiting_So) > 0) {
                    html_show += "<td class='text-center' style='vertical-align:middle'><a href='javascript:;' onclick='getCongVan(" + parseInt(dataget[i].report_school_id) + ",\"" + dataget[i].name + "\", 8);'>" + ConvertString(dataget[i].report_waiting_So) + "</td>";
                }
                else {
                    html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(dataget[i].report_waiting_So) + "</td>";
                }

                if (parseInt(dataget[i].report_approved_So) > 0) {
                    html_show += "<td class='text-center' style='vertical-align:middle'><a href='javascript:;' onclick='getCongVan(" + parseInt(dataget[i].report_school_id) + ",\"" + dataget[i].name + "\", 9);'>" + ConvertString(dataget[i].report_approved_So) + "</td>";
                }
                else {
                    html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(dataget[i].report_approved_So) + "</td>";
                }

                if (parseInt(dataget[i].report_reverted_So) > 0) {
                    html_show += "<td class='text-center' style='vertical-align:middle'><a href='javascript:;' onclick='getCongVan(" + parseInt(dataget[i].report_school_id) + ",\"" + dataget[i].name + "\", 10);'>" + ConvertString(dataget[i].report_reverted_So) + "</td>";
                }
                else {
                    html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(dataget[i].report_reverted_So) + "</td>";
                }

                html_show += "<td class='text-center' style='vertical-align:middle'>" + formatDates(dataget[i].report_startdate) + "</td>";
                //html_show += "<td class='text-center' style='vertical-align:middle'>"+formatDates(dataget[i].report_enddate)+"</td>";
                html_show += "</tr>";
            }
        }
        else {
            html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
        }
        $('#dataListApproved').html(html_show);
    }, function (result) {
        console.log("loadDanhSachSoCongVan: " + result);
    }, "btnLoadDataPhong", "", "");
};

function loadDataDSDNPD() {
    var msg_warning = "";

    msg_warning = validateLapDenghi();

    // alert(msg_warning);

    if (msg_warning !== null && msg_warning !== "") {
        utility.messagehide("messageValidate", msg_warning, 1, 5000);
        return;
    }

    var schools_id = $('#drSchoolTHCD').val();
    var year = $('#sltYear').val();

    var nam = 0;

    if (year !== null && year !== "" && year !== undefined) {
        var ky = year.split("-");
        nam = ky[1];
    }

    var form_datas = new FormData();
    form_datas.append('SCHOOLID', schools_id);
    form_datas.append('YEAR', nam);

    //updateMoneyNew(form_datas);
    // console.log("Click");
    GET_INITIAL_NGHILC();
    loaddataDanhSachTongHop($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
};


loadListMessage = function (keySearch = null) {
    var row = $('#drPagingMessage').val();
    var o = {};
    if (keySearch != null && keySearch != '') {
        o = {
            id_school: $('#sltSchool').val(),
            start: (GET_START_RECORD_NGHILC()),
            limit: row,
            start_time: $('#txtStartTime').val(),
            end_time: $('#txtEndTime').val(),
            status: $('#sltTrangThai').val(),
        }
    } else {
        o = {
            id_school: $('#sltSchool').val(),
            start: (GET_START_RECORD_NGHILC()),
            limit: row,
            start_time: $('#txtStartTime').val(),
            end_time: $('#txtEndTime').val(),
            status: $('#sltTrangThai').val(),
        }
    }
    var html_show = "";
    $('#dataMessage').html("");
    PostToServer('/ho-so/load-list-message', o, function (result) {
        SETUP_PAGING_NGHILC(result, function () {
            loadListMessage();
        });
        var dl = result.data;
        if (dl.length > 0) {
            for (var i = 0; i < dl.length; i++) {
                html_show += "<tr><td class='text-center' style='vertical-align:middle'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                html_show += "<td style='vertical-align:middle'>" + ConvertString(dl[i].schools_name) + "</td>";
                html_show += "<td style='vertical-align:middle'>" + ConvertString(dl[i].message_text) + "</td>";
                html_show += "<td style='vertical-align:middle'>" + ConvertString(dl[i].report_name) + "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + formatDateTimes(dl[i].updated_at) + "</td>";
                if (parseInt(dl[i].status) == 0) {
                    html_show += "<td class='text-center' style='vertical-align:middle;color:green'>Đang thông báo</td>";
                    html_show += "<td class='text-center' style='vertical-align:middle;'><button class='btn btn-success btn-xs' onclick='viewDetailMessage(" + dl[i].type + "," + dl[i].school_id + ", \"" + dl[i].report_name + "\")'>Xem chi tiết</button></td></tr>";
                } else if (parseInt(dl[i].status) == 1) {
                    html_show += "<td class='text-center' style='vertical-align:middle;color:blue'>Hết thông báo</td></tr>";
                }

            }
        } else {
            html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
        }
        $('#dataMessage').html(html_show);
    }, function (result) {
        console.log("loadListMessage: " + result);
    }, "btnViewMessage", "", "");
};


function autocompleteGiamHo() {
    var keySearch = "";
    $('#txtParent').autocomplete({
        search: function () { $(this).addClass('working'); },
        open: function () { $(this).removeClass('working'); },
        source: function (request, response) {
            keySearch = $.ui.autocomplete.escapeRegex(request.term).replace(/[%\\\-]/g, '');
            if (keySearch.length >= 2) {
                var o = {
                    name: keySearch
                };
                PostToServer('/danh-muc/kiem-tra-chu-ho', o, function (data) {
                    lstCustomerForCombobox = [];
                    var item;
                    var typename;
                    if (data.length > 0) {
                        for (var i = 0; i < data.length; i++) {
                            var dl = data[i];
                            if (dl.DShongheo_name != null) {
                                item = {
                                    label: dl.DShongheo_name + '-' + dl.DShongheo_birthday + '- Địa chỉ: ' + dl.DShongheo_typename,
                                    value: dl.DShongheo_name
                                }
                            } else {
                                item = {
                                    label: dl.DShongheo_typename + '-' + dl.DShongheo_birthday
                                }

                            }
                            lstCustomerForCombobox.push(item);
                        }
                    }

                    var matcher = new RegExp(keySearch, "i");
                    response($.grep(lstCustomerForCombobox, function (item) {
                        if (!matcher.test(item.label)) {
                            $('#tbMessage').attr('hidden', 'hidden');
                            $('.chuho_id').remove();
                        }
                        return (matcher.test(item.label));
                    }));
                }, function (result) {
                    console.log("autocompleteGiamHo: " + result);
                }, "saveProfile", "", "");
            }
            // }
        },
        minLength: 3,
        delay: 222,
        autofocus: true,
        select: function (event, ui) {

            $('#txtParent').val(ui.item.label.split('-')[0]);
            $('#tbMessage').removeAttr('hidden');
            $('.chuho_id').remove();
            $('#txtParent').removeClass('working');
            var html_show = '';
            html_show += '<tr class="chuho_id text-center"><td>Chủ hộ</td>';
            html_show += '<td>' + ui.item.label.split('-')[0] + " - " + ui.item.label.split('-')[1] + '</td>';
            html_show += '<td colspan="2">' + ui.item.label.split('-')[2] + '</td></tr>';
            $('#tbMessageContent').append(html_show);
            $("#txtGuardian").focus();
        },
        change: function (event, ui) {
            $("#txtGuardian").focus();
            $('#txtParent').removeClass('working');
        }
    });
};

//----------------------------------------------------Cập nhập và thay đổi hộ khẩu----------------------------------------------------------
function loadLopBySchoolIDCapNhatHoKhau(schoolid) {
    GetFromServer('/danh-muc/load/lop/' + schoolid, function (dataget) {
        $('#sltLopSiteHis').html("");
        var html_show = "";
        if (dataget.length > 0) {
            html_show += "<option value='0'>-- Chọn lớp --</option>";
            for (var i = 0; i < dataget.length; i++) {
                html_show += "<option value='" + dataget[i].class_id + "'>" + dataget[i].class_name + "</option>";
            }
            $('#sltLopSiteHis').html(html_show);
        } else {
            $('#sltLopSiteHis').html("<option value=''>-- Chưa có lớp --</option>");
        }
    }, function (dataget) {
        console.log("loadLopBySchoolIDCapNhatHoKhau");
        console.log(dataget);
    }, "", "", "");
}

var hisSite_id = 0;
var profile_id_HisSite = 0;
function getHoSoHSCapNhatHoKhau(id, profileid, type = 0) {
    //resetControl();
    counter = 0;
    hisSite_id = id;
    profile_id_HisSite = profileid;
    loading();
    var o = {
        HISID: id,
        PROFILEID: profileid
    }
    var test;
    PostToServer('/ho-so/getDatabyProfileid', o, function (data) {
        $('#tbMessage').attr('hidden', 'hidden');
        $('.chuho_id').remove();
        var objProfile = data['objProfile'];
        $('#txtNameProfile').val(objProfile[0]['profile_name']);
        var v_birthday = formatDates(objProfile[0]['profile_birthday']);
        $('#txtBirthday').val(v_birthday);

        var v_startdate = formatDates(objProfile[0]['start_date']);
        $('#txtStartDate').val(v_startdate);

        loadComboxDantoc(function () {
            $('#sltDantoc').val(objProfile[0]['profile_nationals_id']).trigger('change');
        });

        $('#txtParent').val(objProfile[0]['profile_parentname']);

        $('#url_hocsinh').html('<a href="/ho-so/hoc-sinh/change/' + objProfile[0]['profile_id'] + '" id="nextChangeSubject"></a>');
        loadComboxTruongHoc('sltTruong', function () {

            loadComboxLop(objProfile[0]['profile_school_id'], 'sltLop', function () {

            }, parseInt(objProfile[0]['profile_class_id']));
        }, parseInt(objProfile[0]['profile_school_id']));

        loadComboxTinhThanh(0, 'sltTinh', function () {
            loadComboxTinhThanh(parseInt(objProfile[0]['site_tinh']), 'sltQuan', function () {
                $('select#sltQuan').removeAttr('disabled');
                $('select#sltQuan').selectpicker('refresh');
                if (objProfile[0]['site_phuongxa'] != null) {
                    loadComboxTinhThanh(parseInt(objProfile[0]['site_quanhuyen']), 'sltPhuong', function () {
                        $('select#sltPhuong').removeAttr('disabled');
                        $('select#sltPhuong').selectpicker('refresh');
                        if (objProfile[0]['site_phuongxa'] != null) {
                            loadComboxTinhThanh(parseInt(objProfile[0]['site_phuongxa']), 'txtThonxom', function () {
                                $('select#txtThonxom').removeAttr('disabled');
                                $('select#txtThonxom').selectpicker('refresh');
                            }, parseInt(objProfile[0]['site_thon']));
                        }
                    }, parseInt(objProfile[0]['site_phuongxa']));
                }
                //$('#saveProfile').button('reset');
                closeLoading();
                // $('#saveProfile').button('reset').html("Cập nhật");
                openModalUpdateSite(type);

                //$('#saveProfile').removeAttr('disabled');
            }, parseInt(objProfile[0]['site_quanhuyen']));
        }, parseInt(objProfile[0]['site_tinh']));

    }, function (data) {
        console.log("getHoSoHocSinh");
        console.log(data);
    }, "", "", "");
}

function deleteHisSiteById(id, profileid) {
    utility.confirm('Thông báo', 'Bạn có thực sự muốn xóa dữ liệu không?', function () {
        // var o = {
        //     HISID: id,
        //     PROFILEID: profileid
        // }

        GetFromServer('/ho-so/deleteSiteProfile?HISID=' + id + '&PROFILEID=' + profileid, function (dataget) {
            if (dataget.success != null && dataget.success != '' && dataget.success != undefined) {
                $("#myModalProfile").modal("hide");
                utility.message("Thông báo", dataget.success, null, 3000)
                GET_INITIAL_NGHILC();
                loadDataRegistration($('#drPagingRegistration').val(), $('#txtSearchSiteProfile').val());
            } else if (dataget.error != null && dataget.error != '' && dataget.error != undefined) {
                utility.message("Thông báo", dataget.error, null, 3000, 1)
            }
        }, function (result) {
            console.log('deleteHisSiteById: ' + result);
        }, "deleteHisSiteById", "", "");
    });
}

function loadDataRegistration(row, keysearch = null, type = null, order = null) {
    var truong = $('#sltSchoolSiteHis').val();
    var lop = $('#sltLopSiteHis').val();
    var html_show = "";
    var o = {
        start: (GET_START_RECORD_NGHILC()),
        limit: row,
        SCHOOLID: truong,
        CLASSID: lop,
        KEY: keysearch,
        year: $('#sltNamHoc').val(),
        TYPE: type,
        ORDER: order
    };
    var or = 0;
    if (order == 0 || order == null) {
        or = 1;
    } else {
        or = 0;
    }
    var html_header = '';
    html_header += '<tr class="success">';
    html_header += '<th  class="text-center" style="vertical-align:middle;width: 2%">STT</th>';
    html_header += '<th  class="text-center class-pointer" style="vertical-align:middle;width: 15%"><span  onclick="GET_INITIAL_NGHILC();loadDataRegistration(' + row + ',\'' + keysearch + '\',1,' + or + ');">Tên học sinh</span></th>';
    html_header += '<th  class="text-center class-pointer" style="vertical-align:middle;width: 8%"><span  onclick="GET_INITIAL_NGHILC();loadDataRegistration(' + row + ',\'' + keysearch + '\',2,' + or + ');">Ngày sinh</span></th>';
    //html_header += '<th  class="text-center class-pointer" style="vertical-align:middle;width: 15%">Trường học</th>';
    html_header += '<th  class="text-center class-pointer" style="vertical-align:middle;width: 8%"><span  onclick="GET_INITIAL_NGHILC();loadDataRegistration(' + row + ',\'' + keysearch + '\',3,' + or + ');">Lớp học</span></th>';
    html_header += '<th  class="text-center class-pointer" style="vertical-align:middle;width: 45%"><span  onclick="GET_INITIAL_NGHILC();loadDataRegistration(' + row + ',\'' + keysearch + '\',4,' + or + ');">Hộ khẩu</span></th>';
    html_header += '<th  class="text-center class-pointer" style="vertical-align:middle;width: 8%"><span  onclick="GET_INITIAL_NGHILC();loadDataRegistration(' + row + ',\'' + keysearch + '\',5,' + or + ');">Ngày hiệu lực</span></th>';
    html_header += '<th  class="text-center" style="vertical-align:middle;width: 8%">Ngày kết thúc</th>';
    html_header += '<th  class="text-center" colspan="3" style="vertical-align:middle;width: 10%">Chức năng</th>';
    html_header += '</tr>';
    $('#dataHeadSite').html(html_header);
    $('#dataSite').html("<tr><td colspan='50' class='text-center'>Đang tải dữ liệu</td></tr>");
    PostToServer('/ho-so/hoc-sinh/loadSiteHistory', o, function (dataget) {
        SETUP_PAGING_NGHILC(dataget, function () {
            loadDataRegistration(row, keysearch, type, order);
        });

        var leaveDate = "";
        data = dataget.data;
        // console.log(data);
        if (data.length > 0) {
            for (var i = 0; i < data.length; i++) {
                if (data[i].profile_status == 1) {
                    leaveDate = data[i].profile_leaveschool_date;
                }
                else { leaveDate = ""; }
                html_show += "<tr><td class='text-center'>" + (i + 1 + (GET_START_RECORD_NGHILC() * row)) + "</td>";
                // html_show += "<td><a style='cursor:pointer' onclick='viewHistory("+data[i].profile_id+")'>"+ConvertString(data[i].profile_code)+"</a></td>";
                html_show += "<td style='vertical-align:middle'><a >" + ConvertString(data[i].profile_name) + "</a></td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + formatDates(data[i].profile_birthday) + "</td>";
                // html_show += "<td style='vertical-align:middle'>"+ConvertString(data[i].schools_name)+"</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(data[i].class_name) + "</td>";
                html_show += "<td style='vertical-align:middle'>" + ConvertString(data[i].tenthon) + ' - ' + ConvertString(data[i].tenxa) + ' - ' + ConvertString(data[i].tenhuyen) + ' - ' + ConvertString(data[i].tentinh) + "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + formatMonth(data[i].start_date) + "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + formatMonth(data[i].end_date) + "</td>";

                if (check_Permission_Feature("2")) {
                    html_show += "<td class='text-center' style='vertical-align:middle'><button data='" + data[i].profile_id + "' onclick='getHoSoHSCapNhatHoKhau(" + data[i].id + ", " + data[i].profile_id + ");' class='btn btn-info btn-xs' id='editor_editss' title='Sửa hộ khẩu'><i class='glyphicon glyphicon-edit'></i></button> </td>";
                    if (data[i].end_date === null || data[i].end_date === "") {
                        html_show += "<td class='text-center' style='vertical-align:middle'><button data='" + data[i].profile_id + "' onclick='getHoSoHSCapNhatHoKhau(" + data[i].id + ", " + data[i].profile_id + ", 1);' class='btn btn-info btn-xs' id='editor_editss' title='Cập nhật hộ khẩu'><i class='glyphicon glyphicon-retweet'></i></button> </td>";
                    }
                    else {
                        html_show += "<td class='text-center' style='vertical-align:middle'></td>";
                    }
                }
                if (check_Permission_Feature("3")) {
                    if (data[i].end_date === null || data[i].end_date === "") {
                        html_show += "<td class='text-center' style='vertical-align:middle'><button  onclick='deleteHisSiteById(" + data[i].id + ", " + data[i].profile_id + ");'  class='btn btn-danger btn-xs editor_remove' title='Xóa hộ khẩu'><i class='glyphicon glyphicon-trash'></i></button></td>";
                    }
                    else {
                        html_show += "<td class='text-center' style='vertical-align:middle'></td>";
                    }
                }
                html_show += "</tr>";
            }

        } else {
            html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
        }
        $('#dataSite').html(html_show);
    }, function (dataget) {
        console.log("loadDataRegistration");
        console.log(dataget);
    }, "", "", "");

};

function autocomUpdateSiteProfile() {
    var keySearch = "";
    $('#txtSearchSiteProfile').autocomplete({
        source: function (request, response) {
            keySearch = $.ui.autocomplete.escapeRegex(request.term).replace(/[%\\\-]/g, '');
            // console.log(keySearch);
            if (keySearch.length >= 2) {
                GET_INITIAL_NGHILC();
                loadDataRegistration($('#drPagingRegistration').val(), keySearch);

            } else if (keySearch.length < 2) {
                GET_INITIAL_NGHILC();
                loadDataRegistration($('#drPagingRegistration').val(), "");

            }
        },
        minLength: 0,
        delay: 222,
        autofocus: true
    });
};

var idhsthuhoi = 0;
function openpopupthuhoihocsinh(profileid, socongvan, schoolid) {
    idhsthuhoi = profileid;
    var o = {
        PROFILEID: profileid,
        SOCONGVAN: socongvan,
        SCHOOLID: schoolid
    };
    // console.log(o);
    $.ajax({
        type: "POST",
        url: '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/loadthuhoihocsinh',
        data: JSON.stringify(o),
        // dataType: 'json',
        contentType: 'application/json; charset=utf-8',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
        success: function (datas) {
            console.log(datas);
            if (datas !== null && datas !== "") {

                var html_show = '';
                if (datas['PROFILE'].length > 0) {

                    for (var i = 0; i < datas['PROFILE'].length; i++) {
                        html_show += '<tr>';
                        html_show += '<td>' + ConvertString(datas['PROFILE'][i].profile_name) + '</td>';
                        html_show += '<td class="text-right">' + formatDates(datas['PROFILE'][i].profile_birthday) + '</td>';
                        html_show += '<td>' + ConvertString(datas['PROFILE'][i].first_name + ' ' + datas['PROFILE'][i].last_name) + '</td>';
                        html_show += '<td class="text-right">' + formatDates(datas['PROFILE'][i].his_date) + '</td>';
                        html_show += '<td>' + ConvertString(datas['PROFILE'][i].his_note_thuhoi) + '</td>';
                        html_show += '</tr>';

                        if (parseInt(datas['LEVELUSER']) === 3 && (parseInt(loaiDanhSach) === 6 || parseInt(loaiDanhSach) === 11)) {
                            if (parseInt(datas['SOAPPROVED']) === 1) {
                                $('#btnOkThuHoi').hide();
                            }
                            else if (parseInt(datas['TCAPPROVED']) === 1 && parseInt(datas['SOAPPROVED']) === 0) {
                                $('#btnOkThuHoi').show();
                            }
                        }
                        else if (parseInt(datas['LEVELUSER']) === 4 && (parseInt(loaiDanhSach) === 9 || parseInt(loaiDanhSach) === 12)) {
                            if (parseInt(datas['SOAPPROVED']) === 1) {
                                $('#btnOkThuHoi').show();
                            }
                            else {
                                $('#btnOkThuHoi').hide();
                            }
                        }
                        else {
                            $('#btnOkThuHoi').hide();
                        }
                    }
                    $('#tbThuHoiHocSinh').html(html_show);
                    $("#myModalThuHoiHS").modal("show");
                }
            }
        },
        error: function (datas) {

        }
    });
}

function thuhoihocsinh(o) {

    $.ajax({
        type: "POST",
        url: '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/thuhoihocsinh',
        data: JSON.stringify(o),
        dataType: 'json',
        contentType: 'application/json; charset=utf-8',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
        success: function (datas) {
            if (datas.success != null || datas.success != undefined) {
                utility.message("Thông báo", datas.success, null, 3000);
                $("#myModalThuHoiHS").modal("hide");
                GET_INITIAL_NGHILC();
                loadDanhSachByCongVan($('#drPagingDanhsachtonghop').val(), $('#txtSearchPheDuyet').val(), loaiDanhSach);
            } else if (datas.error != null || datas.error != undefined) {
                utility.message("Thông báo", datas.error, null, 5000, 1);
            }
        },
        error: function (datas) {

        }
    });
}

var lvUser = 0;
function getLvUser() {

    $.ajax({
        type: "POST",
        url: '/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/getLevelUser',
        contentType: 'application/json; charset=utf-8',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
        success: function (datas) {
            lvUser = parseInt(datas);
            // console.log(lvUser);
        },
        error: function (datas) {

        }
    });
}

function uploadHoSoHocSinh(objData) {
    PostToServerFormData('/ho-so/importHoSo', objData, function (data) {
        if (data.success != null && data.success != undefined && data.success != '') {
            utility.message("Thông báo", data.success, null, 5000, 0);
            var url = '/ho-so/downloadFile/' + data.file;
            GET_INITIAL_NGHILC();
            loaddataProfile($('select#viewTableProfile').val(), $('select#sltTruongGrid').val(), $('select#sltLopGrid').val(), $('#txtSearchProfile').val());
            window.open(url, '_blank');

        } else if (data.fail != null && data.fail != undefined && data.fail != '') {
            utility.message("Thông báo", data.fail, null, 5000, 1);
        } else {
            utility.message("Thông báo", data.error, null, 5000, 1);
        }
    }, function (data) {
        console.log(data);
    }, "btnImportHS", "", "");
}
function loadTongHopCongVanDaLap(congvan, keysearch = "", type = 0) {
    $('#typeCV').val(2);
    //0 nhu cau , 1 du toan
    var html_header = '';
    html_header += '<tr class="success"><th class="text-center" style="vertical-align:middle;width: 5%">STT</th>';
    html_header += '<th class="text-center" style="vertical-align:middle;width: 10%">Họ và tên</th>';
    html_header += '<th class="text-center" style="vertical-align:middle;width: 8%">Ngày sinh</th>';
    html_header += '<th class="text-center" style="vertical-align:middle;width: 8%">Khối học</th>';
    html_header += '<th class="text-center" style="vertical-align:middle;width: 7%">Lớp học</th>';
    html_header += '<th class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ MGHP</th>';
    html_header += '<th class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ CPHT</th>';
    html_header += '<th class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ AT TEMG</th>';
    html_header += '<th class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ BT TA</th>';
    html_header += '<th class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ BT TO</th>';
    html_header += '<th class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ BT VHTT</th>';
    html_header += '<th class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ TA HS</th>';
    html_header += '<th class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ HSKT HB</th>';
    html_header += '<th class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ HSKT DDHT</th>';
    html_header += '<th class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ HB HSDTNT</th>';
    html_header += '<th class="text-center" style="vertical-align:middle;width: 5%">Hỗ trợ HSDTTS</th>';
    html_header += '<th class="text-center" style="vertical-align:middle;width: 5%">Tổng tiền</th>';
    $('#dataHeaderCVDL').html(html_header);
    $('#dataLapDanhsachHS').html("<tr><td colspan='50' class='text-center'>Đang tải dữ liệu</td></tr>");
    var o = {
        start: (GET_START_RECORD_NGHILC()),
        limit: $('#drPagingDanhsach').val(),
        socongvan: congvan,
        keysearch: keysearch,
        type: type
    };
    PostToServer('/ho-so/hoc-sinh/tong-hop-cong-van-da-lap', o, function (dataget) {
        SETUP_PAGING_NGHILC(dataget, function () {
            loadTongHopCongVanDaLap(congvan, keysearch);
        });

        var html_show = "";
        data = dataget.data;
        if (data.length > 0) {
            for (var i = 0; i < data.length; i++) {
                var tongtien = formatterNull(data[i].MGHP) + formatterNull(data[i].CPHT) + formatterNull(data[i].HTAT) + formatterNull(data[i].HTBT_TA) + formatterNull(data[i].HTBT_TO) + formatterNull(data[i].HTBT_VHTT) + formatterNull(data[i].HTATHS) + formatterNull(data[i].HSKT_HB) + formatterNull(data[i].HSKT_DDHT) + formatterNull(data[i].HBHSDTNT) + formatterNull(data[i].HSDTTS);
                //if (parseInt(tongtien) > 0) {
                html_show += "<tr><td class='text-center' style='vertical-align:middle'>" + (i + 1 + (GET_START_RECORD_NGHILC() * $('#drPagingDanhsach').val())) + "</td>";
                if (check_Permission_Feature("2")) {
                    html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='getHoSoHocSinh(" + data[i].profile_id + ");'>" + data[i].profile_name + "</a></td>";
                } else {
                    html_show += "<td style='vertical-align:middle'>" + data[i].profile_name + "</td>";
                }
                html_show += "<td class='text-center' style='vertical-align:middle'>" + formatDates(data[i].profile_birthday) + "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(data[i].unit_name) + "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + ConvertString(data[i].class_name) + "</td>";
                html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(data[i].MGHP) + "</td>";
                html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(data[i].CPHT) + "</td>";
                html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(data[i].HTAT) + "</td>";
                html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(data[i].HTBT_TA) + "</td>";
                html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(data[i].HTBT_TO) + "</td>";
                html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(data[i].HTBT_VHTT) + "</td>";
                html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(data[i].HTATHS) + "</td>";
                html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(data[i].HSKT_HB) + "</td>";
                html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(data[i].HSKT_DDHT) + "</td>";
                html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(data[i].HBHSDTNT) + "</td>";
                html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(data[i].HSDTTS) + "</td>";
                html_show += "<td class='text-right' style='vertical-align:middle'>" + formatter(tongtien) + "</td>";
                //}
            }

        } else {
            html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
        }
        $('#dataLapDanhsachHS').html(html_show);
    }, function (dataget) {
        console.log("loadTongHopCongVanDaLap");
        console.log(dataget);
    }, "", "", "");
}

function delProfileByClassOrSchool(o) {
    PostToServer('/ho-so/hoc-sinh/delete-by-class', o, function (data) {
        if (data.success != null && data.success != undefined && data.success != '') {
            utility.message("Thông báo", data.success, null, 5000, 0);
        } else if (data.fall != null && data.fall != undefined && data.fall != '') {
            utility.message("Thông báo", data.fall, null, 5000, 1);
        } else {
            utility.message("Thông báo", data.error, null, 5000, 1);
        }
        GET_INITIAL_NGHILC();
        loaddataProfile($('select#viewTableProfile').val(), $('select#sltTruongGrid').val(), $('select#sltLopGrid').val(), $('#txtSearchProfile').val());
    }, function (data) {
        console.log("delProfile");
        console.log(data);
    }, "delProfile", "", "");
};
function loadCongVanDaLap(congvan) {
    $('#sltCongvan').val(congvan).trigger('change');
}
function loadCongVanDaLapBySchool(schools_id) {
    GET_INITIAL_NGHILC();
    loadTongHopCongVanBySchool(schools_id);
}
function openPopupDetail(schools_id, keysearch = "", type = 0, val = 0) {
    $('#typeCV').val(0);
    $(":file").filestyle('clear');
    //0 nhu cau 1 du toan
    var html_header = '';
    html_header += '<tr class="success">';
    html_header += '<th class="text-center" rowspan="2" style="vertical-align:middle;width: 10%">Trường học</th>';
    html_header += '<th class="text-center" rowspan="2" style="vertical-align:middle;width: 10%">Số công văn</th>';
    html_header += '<th class="text-center" colspan="11" style="vertical-align:middle;width: 10%">Tổng chi phí</th>';
    html_header += '<th class="text-center" rowspan="2" style="vertical-align:middle;width: 5%">Nhu cầu</th>';
    html_header += '</tr><tr class="success">';
    html_header += '<th class="text-center" style="vertical-align:middle;width: 5%">MGHP</th>';
    html_header += '<th class="text-center" style="vertical-align:middle;width: 5%">CPHT</th>';
    html_header += '<th class="text-center" style="vertical-align:middle;width: 5%">AT TEMG</th>';
    html_header += '<th class="text-center" style="vertical-align:middle;width: 5%">BT TA</th>';
    html_header += '<th class="text-center" style="vertical-align:middle;width: 5%">BT TO</th>';
    html_header += '<th class="text-center" style="vertical-align:middle;width: 5%">BT VHTT</th>';
    html_header += '<th class="text-center" style="vertical-align:middle;width: 5%">TA HS</th>';
    html_header += '<th class="text-center" style="vertical-align:middle;width: 5%">HSKT HB</th>';
    html_header += '<th class="text-center" style="vertical-align:middle;width: 5%">HSKT DDHT</th>';
    html_header += '<th class="text-center" style="vertical-align:middle;width: 5%">HB HSDTNT</th>';
    html_header += '<th class="text-center" style="vertical-align:middle;width: 5%">HSDTTS</th>';
    html_header += '</tr>';

    $('#tbodyrDetail').html("<tr><td colspan='50' class='text-center'>Đang tải dữ liệu</td></tr>");
    var html_header_ngna = '';
    html_header_ngna += '<tr class="success">';
    html_header_ngna += '<th class="text-center" rowspan="2" style="vertical-align:middle;">Tên trường</th>';
    html_header_ngna += '<th class="text-center" rowspan="2" style="vertical-align:middle;">Số công văn</th>';
    html_header_ngna += '<th class="text-center" rowspan="2" style="vertical-align:middle">Năm học</th>';
    html_header_ngna += '<th class="text-center" rowspan="2" style="vertical-align:middle">Tổng học sinh</th>';
    html_header_ngna += '<th class="text-center" colspan="3" style="vertical-align:middle">Số lượng nhân viên</th>';
    html_header_ngna += '<th class="text-center" colspan="3" style="vertical-align:middle">Nhu cầu kinh phí</th>';
    html_header_ngna += '</tr><tr class="success">';
    html_header_ngna += '<th class="text-center" style="vertical-align:middle">TW</th>';
    html_header_ngna += '<th class="text-center" style="vertical-align:middle">ĐP</th>';
    html_header_ngna += '<th class="text-center" style="vertical-align:middle">HĐ 68</th>';
    html_header_ngna += '<th class="text-center" style="vertical-align:middle">TW</th>';
    html_header_ngna += '<th class="text-center" style="vertical-align:middle">ĐP</th>';
    html_header_ngna += '<th class="text-center" style="vertical-align:middle">Tổng</th></tr>';
    var o = {
        school_id: schools_id,
        type_cv: type,
        namhoc: $('#sltYear').val(),
        keysearch: keysearch

    };
    PostToServer('/ho-so/hoc-sinh/tong-hop-cong-van-da-lap-theo-truong', o, function (dataget) {
        if (dataget.type == 'NGNA') {
            $('#headerDetail').html(html_header_ngna);
        } else {
            $('#headerDetail').html(html_header); //dataHeaderCVDL     
        }

        var html_show = "";
        data = dataget.data;
        if (data.length > 0) {
            for (var i = 0; i < data.length; i++) {//schools_id 
                html_show += "<tr>";
                html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='loadCongVanDaLapBySchool(" + data[i].schools_id + ");'>" + data[i].schools_name + "</a></td>";
                html_show += "<td style='vertical-align:middle'><a class='tooltip-toggle' data-tooltip='Công văn: " + ConvertString(data[i].report_name_text) + ". Ngày gửi:" + data[i].created_at + "' href='/ho-so/lap-danh-sach/danh-sach-de-nghi/" + data[i].report_name + "'>" + ConvertString(data[i].report_name) + "</a></td>";
                if (dataget.type == 'NGNA') {
                    html_show += "<td class='text-right' style='vertical-align:middle'><b>" + ConvertString(data[i].hsbc_HK) + "</b></td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(data[i].hsbc_amount) + "</b></td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(data[i].hsbc_TW) + "</b></td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(data[i].hsbc_DP) + "</b></td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(data[i].hsbc_68) + "</b></td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(data[i].hsbc_amount_TW) + "</b></td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(data[i].hsbc_amount_DP) + "</b></td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(formatterNull(data[i].hsbc_amount_TW) + formatterNull(data[i].hsbc_amount_DP)) + "</b></td>";
                } else {
                    html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(data[i].MGHP) + "</b></td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(data[i].CPHT) + "</b></td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(data[i].TATEMG) + "</b></td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(data[i].BTTA) + "</b></td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(data[i].BTTO) + "</b></td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(data[i].BTVHTT) + "</b></td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(data[i].TAHS) + "</b></td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(data[i].HSKTHB) + "</b></td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(data[i].HSKTDDHT) + "</b></td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(data[i].HSDTNT) + "</b></td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(data[i].HSDTTS) + "</b></td>";
                    html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(formatterNull(data[i].MGHP)
                        + formatterNull(data[i].CPHT)
                        + formatterNull(data[i].TATEMG)
                        + formatterNull(data[i].BTTA)
                        + formatterNull(data[i].BTTO)
                        + formatterNull(data[i].BTVHTT)
                        + formatterNull(data[i].TAHS)
                        + formatterNull(data[i].HSKTHB)
                        + formatterNull(data[i].HSKTDDHT)
                        + formatterNull(data[i].HSDTNT)
                        + formatterNull(data[i].HSDTTS)) + "</b></td>";
                }
            }
            total_sum = dataget.total_sum;
            if (total_sum != undefined && total_sum != null) {
                html_show += "<tr class='info'>";
                html_show += "<td class='text-center' style='vertical-align:middle' colspan='2'><b>Tổng</b></td>";
                html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(total_sum[0].MGHP) + "</b></td>";
                html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(total_sum[0].CPHT) + "</b></td>";
                html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(total_sum[0].TATEMG) + "</b></td>";
                html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(total_sum[0].BTTA) + "</b></td>";
                html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(total_sum[0].BTTO) + "</b></td>";
                html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(total_sum[0].BTVHTT) + "</b></td>";
                html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(total_sum[0].TAHS) + "</b></td>";
                html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(total_sum[0].HSKTHB) + "</b></td>";
                html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(total_sum[0].HSKTDDHT) + "</b></td>";
                html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(total_sum[0].HSDTNT) + "</b></td>";
                html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(total_sum[0].HSDTTS) + "</b></td>";
                html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(formatterNull(total_sum[0].MGHP)
                    + formatterNull(total_sum[0].CPHT)
                    + formatterNull(total_sum[0].TATEMG)
                    + formatterNull(total_sum[0].BTTA)
                    + formatterNull(total_sum[0].BTTO)
                    + formatterNull(total_sum[0].BTVHTT)
                    + formatterNull(total_sum[0].TAHS)
                    + formatterNull(total_sum[0].HSKTHB)
                    + formatterNull(total_sum[0].HSKTDDHT)
                    + formatterNull(total_sum[0].HSDTNT)
                    + formatterNull(total_sum[0].HSDTTS)) + "</b></td>";
                html_show += "</tr>";
            }


        } else {
            html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
        }
        attach = dataget.attach;
        var html_view = '';
        if (attach.length > 0) {

            for (var i = 0; i < attach.length; i++) {
                html_view += '<tr class="option-group">';
                html_view += '<td class="text-center" style="width:10%">' + (i + 1) + '</td>';
                html_view += '<td class="text-center" style="width:40%">';
                html_view += '<a href="javascript:void(0);" class="download-option" data-id="' + attach[i].attach_id + '" title="Tải về"><i class="fa fa-download" aria-hidden="true"></i> ' + attach[i].attach_name + '</a></td>';
                html_view += '<td class="text-center" style="width:40%">' + formatDateTimes(attach[i].updated_at) + '</td>';
                if (val === 0) {
                    html_view += '<td class="text-center" style="width:10%">';
                    html_view += '<button type="button" class="btn btn-xs btn-white remove-option" data-id="' + attach[i].attach_id + '" title="Xóa">';
                    html_view += '<i class="glyphicon glyphicon-trash"></i></button>';
                }

                html_view += '</tr>';
            }
        }
        $('#option_container').html(html_view);
        $('#tbodyrDetail').html(html_show);
        $('#txtSoCongVan').val(keysearch);
        $('#txtNoteHSBC').html('Ghi chú: <b>' + ConvertString(data[0].report_note) + '</b>');
        $('#myModalDetail').modal('show');

    }, function (dataget) {
        console.log("loadTongHopCongVanBySchool");
        console.log(dataget);
    }, "", "loading", "");
};
function loadTongHopCongVanBySchool(schools_id, keysearch = "") {

    $('#dataLapDanhsachHS').html("<tr><td colspan='50' class='text-center'>Đang tải dữ liệu</td></tr>");

    var o = {
        start: (GET_START_RECORD_NGHILC()),
        limit: $('#drPagingDanhsach').val(),
        school_id: schools_id,
        type_cv: $('#sltLoaiCV').val(),
        namhoc: $('#sltYear').val(),
        keysearch: keysearch

    };
    PostToServer('/ho-so/hoc-sinh/cong-van-phong-da-lap', o, function (dataget) {
        SETUP_PAGING_NGHILC(dataget, function () {
            loadTongHopCongVanBySchool(schools_id, keysearch);
        });

        var html_show = "";
        data = dataget.data;
        if (data.length > 0) {

            for (var i = 0; i < data.length; i++) {//schools_id 
                var arrType = data[i].report_type.split('-');
                html_show += "<tr><td class='text-center' style='vertical-align:middle'>" + (i + 1 + (GET_START_RECORD_NGHILC() * $('#drPagingDanhsach').val())) + "</td>";
                //html_show += "<td class='text-center' style='vertical-align:middle'>"+ConvertString(data[i].schools_name)+"</td>";
                // html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='loadCongVanDaLapBySchool("+data[i].schools_id+");'>"+data[i].schools_name+"</a></td>";    
                html_show += "<td style='vertical-align:middle'><a class='tooltip-toggle' data-tooltip='Công văn: " + ConvertString(data[i].report_name_text) + ".' href='/ho-so/lap-danh-sach/danh-sach-de-nghi/" + data[i].report_name + "'>" + ConvertString(data[i].report_name) + "</a></td>";
                html_show += "<td  style='vertical-align:middle'>" + ConvertString(data[i].report_name_text) + "</td>";
                html_show += "<td  style='vertical-align:middle'>";
                var html = '';
                for (var j = 0; j < arrType.length; j++) {
                    var dl = arrType[j];
                    if (dl == "MGHP") {
                        html += "Cấp bù học phí,";
                    } else if (dl == "CPHT") {
                        html += "Chi phí học tập,";
                    } else if (dl == "HTAT") {
                        html += "Hỗ trợ ăn trưa,";
                    } else if (dl == "HTBT") {
                        html += "Hỗ trợ bán trú,";
                    } else if (dl == "HSKT") {
                        html += "Học sinh khuyết tật,";
                    } else if (dl == "HTATHS") {
                        html += "Hỗ trợ ăn trưa học sinh,";
                    } else if (dl == "HSDTTS") {
                        html += "Học sinh dân tộc thiểu số,";
                    } else if (dl == "HBHSDTNT") {
                        html += "Học sinh dân tọc nội trú,";
                    } else if (dl == "NGNA") {
                        html += "Báo cáo nhân viên cấp dưỡng,";
                    }

                }
                html_show += html.substr(0, html.length - 1);
                html_show += "</td>";

                html_show += "<td class='text-center' style='vertical-align:middle'>" + convertNumber(data[i].report_total) + "</td>";
                if (parseInt(data[i].report_cap_status) == 0) {
                    html_show += "<td class='text-center' style='vertical-align:middle'>Chờ phê duyệt</td>";
                } else if (parseInt(data[i].report_cap_status) == 1) {
                    html_show += "<td class='text-center' style='vertical-align:middle'>Đã xử lý</td>";
                } else if (parseInt(data[i].report_cap_status) == 3) {
                    html_show += "<td class='text-center' style='vertical-align:middle'>Thu hồi</td>";
                }
                else {
                    html_show += "<td class='text-center' style='vertical-align:middle'>-</td>";
                }

                html_show += "<td class='text-center' style='vertical-align:middle'><b>" + formatDateTimes(data[i].created_at) + "</b></td>";
                html_show += "<td class='text-center' style='vertical-align:middle'><a class='btn btn-block btn-primary btn-xs' href='javascript:;' onclick='openPopupDetail(\"" + data[i].schools_id + "\",\"" + ConvertString(data[i].report_name) + "\"," + data[i].report_cap_status + ")'><i class='glyphicon glyphicon-eye-open'></i> Chi tiết</a></td></td>";
            }

        } else {
            html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
        }
        $('#dataLapDanhsachHS').html(html_show);
    }, function (dataget) {
        console.log("loadTongHopCongVanBySchool");
        console.log(dataget);
    }, "", "", "");
};
// openPopupDetail = function(id,congvan){
//     loadDetailCongVanBySchool(id,congvan);
// }
/// Cập nhật tiền cho năm học
capnhathotrochedo = function (id, year, schoolid) {
    var o = {
        profileid: id,
        schoolid: schoolid,
        years: year
    }
    PostToServer('/ho-so/lap-danh-sach/tong-hop-che-do-ho-tro/update', o, function (data) {
        if (data['success'] != "" && data['success'] != undefined) {
            utility.message("Thông báo ", data['success'], null, 3000);
            $('#dataDanhsachTonghop').html("<tr><td colspan='50' class='text-center'>Đang cập nhật...</td></tr>");
            setTimeout(function () {
                GET_INITIAL_NGHILC();
                loaddataDanhSachLapCV($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val());
            }, 5000);

            //loaddataDanhSachTongHop($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltStatus').val());
        }
        if (data['error'] != "" && data['error'] != undefined) {
            utility.message("Thông báo", data['error'], null, 3000, 1);
        }

    }, function (data) {
        console.log("capnhathotrochedo");
        console.log(data);
    }, "updateMoneyByYear", "", "");

}

///Quản lý phê duyệt //// Danh sách dề nghị

loadLoaiCongVan = function (type, user = 0, level = 0) {
    var val = parseInt(type);

    if ((val === 1 || val === 0) && user == level) {
        $('#event-thcd').show();
        $('.event-thcd').show();
        //$('#event-thcd-thuhoi').hide();
    }
    else if (val === 2 || val === 3 || user != level) {
        $('#event-thcd').hide();
        $('.event-thcd').hide();
        // $('#event-thcd-thuhoi').show();
    } else {
        $('#event-thcd').hide();
        $('.event-thcd').hide();
    }

    if (val === 0 || val === 1) {
        var html_show = "";
        // html_show += "<option value=''>-- Chọn loại công văn lập --</option>";
        html_show += "<option value='1' selected>Công văn phê duyệt</option>";
        html_show += "<option value='2'>Công văn trả lại</option>";
        $('#sltLoaiCVPopup').html(html_show);
        $('#sltLoaiCVPopup').selectpicker('refresh');
    } else if ((val === 0 || val === 1) && user == 4) {
        var html_show = "";
        // html_show += "<option value=''>-- Chọn loại công văn lập --</option>";
        html_show += "<option value='1' selected>Duyệt công văn</option>";
        html_show += "<option value='2' >Công văn trả lại</option>";
        $('#sltLoaiCVPopup').html(html_show);
        $('#sltLoaiCVPopup').selectpicker('refresh');
    }
}

// Xin thu hồi

thuhoi_cv = function (cv, type = 0) {
    if (type == 0) {
        utility.confirm("Xác nhận", "Bạn chắc chắn muốn thu hồi công văn này?", function () {
            GetFromServer('/ho-so/lap-danh-sach/thuhoi_cv/' + cv, function (data) {
                if (data['success'] != "" && data['success'] != undefined) {
                    utility.message("Thông báo ", data['success'], null, 3000);
                    GET_INITIAL_NGHILC();
                    loadDanhSachByPhongSo($('#drPagingDanhsachtonghop').val(), $('#drSchoolTHCD').val(), $('#sltLoaiCongvan').val(), $('#txtSearchProfilePhongSo').val());
                }
                if (data['error'] != "" && data['error'] != undefined) {
                    utility.message("Thông báo", data['error'], null, 3000, 1);
                }
            }, function (data) {
                console.log("thuhoi_cv");
                console.log(data);
            }, "", "loading", "");
        });
    } else {
        utility.choose("Xác nhận ", "Bạn muốn thu hồi hoặc khóa dữ liệu", "Khóa dữ liệu", "Thu hồi", function () {
            GetFromServer('/ho-so/lap-danh-sach/lock_cv/' + cv, function (data) {
                if (data['success'] != "" && data['success'] != undefined) {
                    utility.message("Thông báo ", data['success'], null, 3000);
                    GET_INITIAL_NGHILC();
                    loadDanhSachByPhongSo($('#drPagingDanhsachtonghop').val(), $('#drSchoolTHCD').val(), $('#sltLoaiCongvan').val(), $('#txtSearchProfilePhongSo').val());
                }
                if (data['error'] != "" && data['error'] != undefined) {
                    utility.message("Thông báo", data['error'], null, 3000, 1);
                }
            }, function (data) {
                console.log("lock_cv");
                console.log(data);
            }, "", "loading", "");
        }, function () {
            GetFromServer('/ho-so/lap-danh-sach/thuhoi_cv/' + cv, function (data) {
                if (data['success'] != "" && data['success'] != undefined) {
                    utility.message("Thông báo ", data['success'], null, 3000);
                    GET_INITIAL_NGHILC();
                    loadDanhSachByPhongSo($('#drPagingDanhsachtonghop').val(), $('#drSchoolTHCD').val(), $('#sltLoaiCongvan').val(), $('#txtSearchProfilePhongSo').val());
                }
                if (data['error'] != "" && data['error'] != undefined) {
                    utility.message("Thông báo", data['error'], null, 3000, 1);
                }
            }, function (data) {
                console.log("thuhoi_cv");
                console.log(data);
            }, "", "loading", "");
        });
    }


}
huycongvan_tralai = function (cv) {
    utility.confirm("Xác nhận", "Bạn chắc chắn muốn hủy thông báo công văn này?", function () {
        GetFromServer('/ho-so/lap-danh-sach/huycongvan_tralai/' + cv, function (data) {
            if (data['success'] != "" && data['success'] != undefined) {
                utility.message("Thông báo ", data['success'], null, 3000);
                GET_INITIAL_NGHILC();
                loadDanhSachByPhongSo($('#drPagingDanhsachtonghop').val(), $('#drSchoolTHCD').val(), $('#sltLoaiCongvan').val(), $('#txtSearchProfilePhongSo').val());
            }
            if (data['error'] != "" && data['error'] != undefined) {
                utility.message("Thông báo", data['error'], null, 3000, 1);
            }
        }, function (data) {
            console.log("huycongvan_tralai");
            console.log(data);
        }, "", "loading", "");
    });
}
xacnhanthuhoi_cv = function (cv) {
    utility.choose("Xác nhận", "Đồng ý cho phép thu hồi công văn?", "Đồng ý", "Không đồng ý", function () {
        GetFromServer('/ho-so/lap-danh-sach/accept_congvan/' + cv, function (data) {
            if (data['success'] != "" && data['success'] != undefined) {
                utility.message("Thông báo ", data['success'], null, 3000);
                GET_INITIAL_NGHILC();
                loadDanhSachByPhongSo($('#drPagingDanhsachtonghop').val(), $('#drSchoolTHCD').val(), $('#sltLoaiCongvan').val(), $('#txtSearchProfilePhongSo').val());
            }
            if (data['error'] != "" && data['error'] != undefined) {
                utility.message("Thông báo", data['error'], null, 3000, 1);
            }
        }, function (data) {
            console.log("thuhoi_cv");
            console.log(data);
        }, "", "loading", "");
    }, function () {
        GetFromServer('/ho-so/lap-danh-sach/cancel_congvan/' + cv, function (data) {
            if (data['success'] != "" && data['success'] != undefined) {
                utility.message("Thông báo ", data['success'], null, 3000);
                GET_INITIAL_NGHILC();
                loadDanhSachByPhongSo($('#drPagingDanhsachtonghop').val(), $('#drSchoolTHCD').val(), $('#sltLoaiCongvan').val(), $('#txtSearchProfilePhongSo').val());
            }
            if (data['error'] != "" && data['error'] != undefined) {
                utility.message("Thông báo", data['error'], null, 3000, 1);
            }
        }, function (data) {
            console.log("thuhoi_cv");
            console.log(data);
        }, "", "loading", "");
    });

}

loadCongVanTongHop = function (idgoc, iddc, schoolid, year, chedo) {
    var o = {
        id: schoolid,
        year: year,
        chedo: chedo
    }
    PostToServer('/ho-so/lap-danh-sach/load_congvan_tonghop', o, function (data) {
        $('#' + idgoc).html('<option value="" selected>Đang tải dữ liệu...</option>').selectpicker('refresh');;
        $('#' + iddc).html('<option value="" selected>Đang tải dữ liệu...</option>').selectpicker('refresh');;
        var goc = data.root;
        var dieuchinh = data.change;
        if (goc.length > 0) {
            var html_1 = "";
            html_1 += '<option value="">--Chọn công văn gốc</option>';
            for (var i = 0; i < goc.length; i++) {
                html_1 += '<option value="' + goc[i].report_name + '">' + goc[i].report_name + '</option>';
            }
            $('#' + idgoc).html(html_1);
        } else {
            $('#' + idgoc).html('<option value="" selected>Không có công văn gốc</option>');
        }
        if (dieuchinh.length > 0) {
            var html_2 = "";
            html_2 += '<option value="">--Chọn công văn điều chỉnh</option>';
            for (var i = 0; i < dieuchinh.length; i++) {
                html_2 += '<option value="' + dieuchinh[i].report_name + '">' + dieuchinh[i].report_name + '</option>';
            }
            $('#' + iddc).html(html_2);
        } else {
            $('#' + iddc).html('<option value="" selected>Không có công văn điều chỉnh</option>');
        }
        $('#' + idgoc).selectpicker('refresh');
        $('#' + iddc).selectpicker('refresh');
    }, function (data) {
        console.log("loadCongVanTongHop");
        console.log(data);
    }, "btnTongHopCongVan", "loading", "");
}

tonghopcongvan = function (name, school_id, cvGoc, cvDC, chedo, year, note, number_cv) {
    var o = {
        name: name,
        school_id: school_id,
        year: year,
        note: note,
        cvGoc: cvGoc,
        cvDC: cvDC,
        chedo: chedo,
        socongvan: number_cv,
    }
    PostToServer('/ho-so/lap-danh-sach/tong-hop-cong-van', o, function (data) {
        if (data['success'] != "" && data['success'] != undefined) {
            utility.message("Thông báo ", data['success'], null, 3000);
            GET_INITIAL_NGHILC();
            loadDanhSachByPhongSo($('#drPagingDanhsachtonghop').val(), $('#drSchoolTHCD').val(), $('#sltLoaiCongvan').val(), $('#txtSearchProfilePhongSo').val());
            $('#txtTenCV').val('');
            $('#txtGhiChuTongHop').val('');
            $('#sltChedoTH').selectpicker('deselectAll');
            $('#sltCVGoc').selectpicker('val', '');
            $('#sltCVDieuChinh').selectpicker('val', '');

            loadCongVanTongHop("sltCVGoc", "sltCVDieuChinh", $('#drSchoolTHCD').val(), $('#sltYear').val(), $('#sltChedoTH').val());
            $('#myModalTongHopCongVan').modal('hide');
        }
        if (data['error'] != "" && data['error'] != undefined) {
            utility.message("Thông báo", data['error'], null, 3000, 1);
        }

    }, function (data) {
        console.log("tonghopcongvan");
        console.log(data);
    }, "btnTongHopCongVan", "loading", "");
}

function loadBaoCaoCongVan(schools_id, keysearch = "") {

    $('#dataLapDanhsachHS').html("<tr><td colspan='50' class='text-center'>Đang tải dữ liệu</td></tr>");

    var o = {
        start: (GET_START_RECORD_NGHILC()),
        limit: $('#drPagingDanhsach').val(),
        school_id: schools_id,
        type_cv: $('#sltLoaiCV').val(),
        namhoc: $('#sltYear').val(),
        keysearch: keysearch

    };
    PostToServer('/bao-cao/cong-van-phong-da-lap', o, function (dataget) {
        SETUP_PAGING_NGHILC(dataget, function () {
            loadBaoCaoCongVan(schools_id, keysearch);
        });

        var html_show = "";
        data = dataget.data;
        if (data.length > 0) {

            for (var i = 0; i < data.length; i++) {//schools_id 
                var arrType = data[i].report_type.split('-');
                html_show += "<tr><td class='text-center' style='vertical-align:middle'>" + (i + 1 + (GET_START_RECORD_NGHILC() * $('#drPagingDanhsach').val())) + "</td>";
                //html_show += "<td class='text-center' style='vertical-align:middle'>"+ConvertString(data[i].schools_name)+"</td>";
                html_show += "<td style='vertical-align:middle'><a href='javascript:;' onclick='loadCongVanDaLapBySchool(" + data[i].schools_id + ");'>" + data[i].schools_name + "</a></td>";
                html_show += "<td style='vertical-align:middle'><a class='tooltip-toggle' data-tooltip='Công văn: " + ConvertString(data[i].report_name_text) + ". Ngày gửi:" + data[i].created_at + "' href='/ho-so/lap-danh-sach/danh-sach-de-nghi/" + data[i].report_name + "'>" + ConvertString(data[i].report_name) + "</a></td>";
                html_show += "<td  style='vertical-align:middle'>" + ConvertString(data[i].report_name_text) + "</td>";
                html_show += "<td  style='vertical-align:middle'>";
                var html = '';
                for (var j = 0; j < arrType.length; j++) {
                    var dl = arrType[j];
                    if (dl == "MGHP") {
                        html += "Cấp bù học phí,";
                    } else if (dl == "CPHT") {
                        html += "Chi phí học tập,";
                    } else if (dl == "HTAT") {
                        html += "Hỗ trợ ăn trưa,";
                    } else if (dl == "HTBT") {
                        html += "Hỗ trợ bán trú,";
                    } else if (dl == "HSKT") {
                        html += "Học sinh khuyết tật,";
                    } else if (dl == "HTATHS") {
                        html += "Hỗ trợ ăn trưa học sinh,";
                    } else if (dl == "HSDTTS") {
                        html += "Học sinh dân tộc thiểu số,";
                    } else if (dl == "HBHSDTNT") {
                        html += "Học sinh dân tọc nội trú,";
                    }

                }
                html_show += html.substr(0, html.length - 1);
                html_show += "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>";
                if (convertNumber(data[i].report_cap_status) == 0) {
                    html_show += "Chưa xử lý";
                } else if (convertNumber(data[i].report_cap_status) == 1) {
                    html_show += "Đã xử lý";
                } else if (convertNumber(data[i].report_cap_status) == 2) {
                    html_show += "Trả lại";
                } else if (convertNumber(data[i].report_cap_status) == 3) {
                    html_show += "Thu hồi";
                }
                html_show += "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'><b>" + convertNumber(data[i].report_total) + "</b></td>";
                html_show += "<td class='text-center' style='vertical-align:middle'><a class='btn btn-block btn-primary btn-xs' href='javascript:;' onclick='openPopupDetail(\"" + data[i].schools_id + "\",\"" + ConvertString(data[i].report_name) + "\"," + data[i].report_cap_status + ")' title='Tổng chi phí'><i class='glyphicon glyphicon-eye-open'></i> Chi tiết</a></td></td>";
                html_show += "<td class='text-center' style='vertical-align:middle'><a class='btn btn-block btn-primary btn-xs' href='javascript:;' onclick='openPopupDetail(\"" + data[i].schools_id + "\",\"" + ConvertString(data[i].report_name) + "\"," + data[i].report_cap_status + ")' title='Xuất excel'><i class='glyphicon glyphicon-export'></i></a></td></td>";
            }

        } else {
            html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
        }
        $('#dataLapDanhsachHS').html(html_show);
    }, function (dataget) {
        console.log("loadTongHopCongVanBySchool");
        console.log(dataget);
    }, "", "", "");
};

// tải danh sách lớp trong chức năng học sinh
loadClassByClass = function (type) {
    if (type != null || type != "") {
        $('#drClassBack').html("<option value=''>Đang tải dữ liệu...</option>").selectpicker('refresh');
        $('#StlClassNext').html("<option value=''>Đang tải dữ liệu...</option>").selectpicker('refresh');
        GetFromServer('/danh-muc/load/class/' + $('#drSchoolUpto').val() + '/' + type + '-' + $('#drClassUpto').val(), function (data) {
            var html_show = '';
            html_show += '<option value="">-- Chọn lớp --</option>';
            for (var i = 0; i < data.length; i++) {
                html_show += '<option value="' + data[i].class_id + '">' + data[i].class_name + '</option>';
            }
            if (type == 1) {
                $('#StlClassNext').html(html_show).selectpicker('refresh');
            } else {
                $('#drClassBack').html(html_show).selectpicker('refresh');
            }
        }, function (data) {
            console.log("loadClassByClass");
            console.log(data);
        }, "btnUpto", "", "");
    }
}

// tesst thu

function danhsachdenghi(schools_id, keysearch = "") {

    $('#dataLapDanhsachHS').html("<tr><td colspan='50' class='text-center'>Đang tải dữ liệu</td></tr>");

    var o = {
        start: (GET_START_RECORD_NGHILC()),
        limit: $('#drPagingDanhsach').val(),
        school_id: schools_id,
        type_cv: $('#sltLoaiCV').val(),
        namhoc: $('#sltYear').val(),
        keysearch: keysearch

    };
    PostToServer('/danh-sach/de-nghi', o, function (dataget) {
        SETUP_PAGING_NGHILC(dataget, function () {
            danhsachdenghi(schools_id, keysearch);
        });

        var html_show = "";
        data = dataget.data;
        if (data.length > 0) {
            var school = 0;
            for (var i = 0; i < data.length; i++) { //schools_id 
                var arrType = data[i].report_type.split('-');
                if (school == 0 || school != data[i].schools_id) {
                    html_show += "<tr><td colspan='8'><b>" + data[i].schools_name + "</b></td></tr>";
                    school = data[i].schools_id;
                }
                html_show += "<tr><td class='text-center' style='vertical-align:middle'>" + (i + 1 + (GET_START_RECORD_NGHILC() * $('#drPagingDanhsach').val())) + "</td>";
                html_show += "<td style='vertical-align:middle'><a class='tooltip-toggle' data-tooltip='Công văn: " + ConvertString(data[i].report_name_text) + ".' href='/ho-so/lap-danh-sach/danh-sach-de-nghi/" + data[i].report_name + "'>" + ConvertString(data[i].report_name) + "</a></td>";
                html_show += "<td  style='vertical-align:middle'>" + ConvertString(data[i].report_name_text) + "</td>";
                html_show += "<td  style='vertical-align:middle'>";
                var html = '';
                for (var j = 0; j < arrType.length; j++) {
                    var dl = arrType[j];
                    if (dl == "MGHP") {
                        html += "Cấp bù học phí,";
                    } else if (dl == "CPHT") {
                        html += "Chi phí học tập,";
                    } else if (dl == "HTAT") {
                        html += "Hỗ trợ ăn trưa,";
                    } else if (dl == "HTBT") {
                        html += "Hỗ trợ bán trú,";
                    } else if (dl == "HSKT") {
                        html += "Học sinh khuyết tật,";
                    } else if (dl == "HTATHS") {
                        html += "Hỗ trợ ăn trưa học sinh,";
                    } else if (dl == "HSDTTS") {
                        html += "Học sinh dân tộc thiểu số,";
                    } else if (dl == "HBHSDTNT") {
                        html += "Học sinh dân tọc nội trú,";
                    }

                }
                html_show += html.substr(0, html.length - 1);
                html_show += "</td>";

                html_show += "<td class='text-center' style='vertical-align:middle'>" + parseInt(data[i].report_total) + "</td>";
                if (parseInt(data[i].report_status) == 0) {
                    html_show += "<td class='text-center' style='vertical-align:middle'>Bình thường</td>";
                } else if (parseInt(data[i].report_status) == 1) {
                    html_show += "<td class='text-center' style='vertical-align:middle'>Tổng hợp</td>";
                } else {
                    html_show += "<td class='text-center' style='vertical-align:middle'>Điều chỉnh</td>";
                }
                if (parseInt(data[i].report_cap_status) == 0) {
                    html_show += "<td class='text-center' style='vertical-align:middle'>Chờ phê duyệt</td>";
                } else if (parseInt(data[i].report_cap_status) == 1) {
                    html_show += "<td class='text-center' style='vertical-align:middle'>Đã xử lý</td>";
                } else {
                    html_show += "<td class='text-center' style='vertical-align:middle'>-</td>";
                }

                html_show += "<td class='text-center' style='vertical-align:middle'><b>" + formatDateTimes(data[i].created_at) + "</b></td>";
                html_show += "<td class='text-center' style='vertical-align:middle'><a class='btn btn-block btn-primary btn-xs' href='javascript:;' onclick='openPopupDetail(\"" + data[i].schools_id + "\",\"" + ConvertString(data[i].report_name) + "\"," + data[i].report_cap_status + ",1)'><i class='glyphicon glyphicon-eye-open'></i> Chi tiết</a></td></td>";
            }

        } else {
            html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
        }
        $('#dataLapDanhsachHS').html(html_show);
    }, function (dataget) {
        console.log("danhsachdenghi");
        console.log(dataget);
    }, "", "", "");
};

function danhsachkinhphi(schools_id, year, user_id) {

    $('#dataBodyDetailKP').html("<tr><td colspan='50' class='text-center'>Đang tải dữ liệu</td></tr>");

    var o = {
        start: (GET_START_RECORD_NGHILC()),
        limit: $('#drPagingDanhsach').val(),
        school_id: schools_id,
        user_id: user_id,
        year: year,
        created_by: $('#sltUserBy').val(),
        chedo: $('#sltLoaiChedo_fill').val()

    };
    PostToServer('/du-toan-chi-tra/danh-sach-kinh-phi', o, function (dataget) {
        SETUP_PAGING_NGHILC(dataget, function () {
            danhsachkinhphi(schools_id, year, user_id);
        });

        var html_show = "";
        data = dataget.data;
        if (data.length > 0) {
            var school = 0;
            for (var i = 0; i < data.length; i++) {

                html_show += "<tr><td class='text-center' style='vertical-align:middle'>" + (i + 1 + (GET_START_RECORD_NGHILC() * $('#drPagingDanhsach').val())) + "</td>";
                html_show += "<td style='vertical-align:middle'>" + data[i].schools_name + "</td>";
                html_show += "<td class='text-right' style='vertical-align:middle'><b>" + formatter(data[i].expense) + "</b></td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + (data[i].year) + "</td>";
                if (parseInt(data[i].using) == 0) {
                    html_show += "<td class='text-center' style='vertical-align:middle'>Tạo mới</td>";
                } else if (parseInt(data[i].using) == 1) {
                    html_show += "<td class='text-center' style='vertical-align:middle'>Đã cấp</td>";
                } else if (parseInt(data[i].using) == 2) {
                    html_show += "<td class='text-center' style='vertical-align:middle'>Đang chi trả</td>";
                } else if (parseInt(data[i].using) == 3) {
                    html_show += "<td class='text-center' style='vertical-align:middle'>Đã chi trả</td>";
                }
                var cd = data[i].chedo.split(",");
                if (cd.length > 0) {
                    html_show += "<td class='text-center' style='vertical-align:middle'>";
                    for (var j = 0; j < cd.length; j++) {
                        if (parseInt(cd[j]) == 1) {
                            html_show += "- Miễn giảm học phí -";
                        } else if (parseInt(cd[j]) == 2) {
                            html_show += "- Chi phí học tập -";
                        } else if (parseInt(cd[j]) == 3) {
                            html_show += "- HTAT trẻ em mẫu giáo -";
                        } else if (parseInt(cd[j]) == 4) {
                            html_show += "- HTHS bán trú -";
                        } else if (parseInt(cd[j]) == 5) {
                            html_show += "- HTHS khuyết tật -";
                        } else if (parseInt(cd[j]) == 6) {
                            html_show += "- HT ăn trưa HS theo NQ57 -";
                        } else if (parseInt(cd[j]) == 7) {
                            html_show += "- HTHS dân tộc thiểu số -";
                        } else if (parseInt(cd[j]) == 8) {
                            html_show += "- HTHB cho HSDT nội trú -";
                        }
                    }
                    html_show += "</td>";
                } else {
                    html_show += "<td class='text-center' style='vertical-align:middle'>-</td>";
                }
                // if(parseInt(cd) == 1){
                //     html_show += "<td class='text-center' style='vertical-align:middle'>Miễn giảm học phí</td>";
                // }else if(parseInt(data[i].chedo) == 2){
                //     html_show += "<td class='text-center' style='vertical-align:middle'>Chi phí học tập</td>";
                // }else if(parseInt(data[i].chedo) == 3){
                //     html_show += "<td class='text-center' style='vertical-align:middle'>HTAT trẻ em mẫu giáo</td>";
                // }else if(parseInt(data[i].chedo) == 4){
                //     html_show += "<td class='text-center' style='vertical-align:middle'>HTHS bán trú</td>";
                // }else if(parseInt(data[i].chedo) == 5){
                //     html_show += "<td class='text-center' style='vertical-align:middle'>HTHS khuyết tật</td>";
                // }else if(parseInt(data[i].chedo) == 6){
                //     html_show += "<td class='text-center' style='vertical-align:middle'>HT ăn trưa HS theo NQ57</td>";
                // }else if(parseInt(data[i].chedo) == 7){
                //     html_show += "<td class='text-center' style='vertical-align:middle'>HTHS dân tộc thiểu số</td>";
                // }else if(parseInt(data[i].chedo) == 8){
                //     html_show += "<td class='text-center' style='vertical-align:middle'>HTHB cho HSDT nội trú</td>";
                // }else{
                //     html_show += "<td class='text-center' style='vertical-align:middle'>-</td>";
                // }


                html_show += "<td class='text-right' style='vertical-align:middle'><a href='/du-toan-chi-tra/cap-nhat-kinh-phi/download?id=" + data[i].id + "' target='_blank'>" + ConvertString(data[i].attach_name) + "</a></td>";
                html_show += "<td style='vertical-align:middle'>" + ConvertString(data[i].note) + "</td>";
                html_show += "<td class='text-center' style='vertical-align:middle'>" + formatDateTimes(data[i].created_at) + "</td>";
                if (parseInt(data[i].created_by) == parseInt(data[i].us)) {
                    html_show += "<td class='text-center' style='vertical-align:middle'><a class='btn btn-info btn-xs' href='javascript:;' onclick='capnhatkinhphi(" + data[i].id + ")' ><i class='glyphicon glyphicon-edit'></i></a></td></td>";
                } else {
                    html_show += "<td class='text-center'>-</td>";
                }
                if (parseInt(data[i].using) == 0 && (parseInt(data[i].created_by) == parseInt(data[i].us))) {
                    html_show += "<td class='text-center' style='vertical-align:middle'><a class='btn btn-danger btn-xs editor_remove' href='javascript:;' ><i class='glyphicon glyphicon-trash'></i></a></td></td>";
                }
                html_show += "</tr>";
            }

        } else {
            html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
        }
        $('#dataBodyDetailKP').html(html_show);
    }, function (dataget) {
        console.log("danhsachdenghi");
        console.log(dataget);
    }, "", "", "");
};

function capnhatkinhphi(id) {
    $('#expense').val('');
    $('#using').selectpicker('val', 0).attr('disabled', 'disabled').selectpicker('refresh');
    $('#note').val('');
    GetFromServer('/du-toan-chi-tra/cap-nhat-kinh-phi?id=' + id, function (data) {
        $('#expense').val(formatter(data['expense']));
        // if(parseInt(data['using']) < 2){
        //     $('#using').selectpicker('val',0).removeAttr('disabled').selectpicker('refresh');
        // }else{
        $('#using').selectpicker('val', 2).attr('disabled', 'disabled').selectpicker('refresh');
        // }
        if (data['user_id'] != null) {
            $('#user_id').selectpicker('val', data['user_id']).selectpicker('refresh');
        }
        if (data['school_id'] != null) {
            $('#sltTruong').selectpicker('val', data['school_id']).selectpicker('refresh');
        }
        $('#note').val(data['note']);
        $('#txtId').val(data['id']);
        $('#myModalDetail').modal('show');
    }, function (data) {
        console.log("capnhatkinhphi");
        console.log(data);
    }, "", "", "");
}
function themmoicapkinhphi() {
    var formData = new FormData();
    var len = $("input[name*='fileQuyetDinh']")[0].files.length;
    for (var x = 0; x < len; x++) {
        formData.append('file[]', $("input[name*='fileQuyetDinh']")[0].files[x]);
    }
    formData.append('expense', $("#expense").val().replaceAlls('.', ''));
    formData.append('using', $("#using").val());
    if ($("#user_id").val() != '' && $("#user_id").val() != undefined) {
        formData.append('user_id', $("#user_id").val());
    }
    if ($("#sltTruong").val() != '' && $("#sltTruong").val() != undefined) {
        formData.append('school_id', $("#sltTruong").val());
    }

    if ($("#txtId").val() != '' && $("#txtId").val() != undefined) {
        formData.append('id', $("#txtId").val());
    }


    formData.append('chedo', $("#sltLoaiChedo").val());
    formData.append('note', $("#note").val());

    formData.append('year', $("#sltYear").val());

    PostToServerFormData('/du-toan-chi-tra/cap-nhat-kinh-phi/insert', formData, function (data) {
        if (data['success'] != null && data['success'] != undefined) {
            utility.message("Thông báo", data['success'], null, 3000, 0);
            $(":file").filestyle('clear');
        } else if (data['error'] != null && data['error'] != undefined) {
            utility.message("Thông báo", data['error'], null, 3000, 1);
        }

        // if (data['data'] != null && data['data'] != undefined) {
        //     var html_view = '';
        //     for (var i = 0; i < data['data'].length; i++) {
        //         html_view += '<tr class="option-group">';
        //         html_view += '<td class="text-center" style="width:10%">'+ (i+1) +'</td>';
        //         html_view += '<td class="text-center" style="width:40%">';
        //         html_view += '<a href="javascript:void(0);" class="download-option" data-id="'+data['data'][i].attach_id+'" title="Tải về"><i class="fa fa-download" aria-hidden="true"></i> '+ data['data'][i].attach_name +'</a></td>';
        //         html_view += '<td class="text-center" style="width:40%">'+ formatDateTimes(data['data'][i].updated_at) +'</td>';
        //         html_view += '<td class="text-center" style="width:10%">';
        //         html_view += '<button type="button" class="btn btn-xs btn-white remove-option" data-id="'+data['data'][i].attach_id+'" title="Xóa">';
        //         html_view += '<i class="glyphicon glyphicon-trash"></i></button>';
        //         html_view += '</tr>';
        //     }
        //     $('#option_container').html(html_view);
        // }
        danhsachkinhphi($('#sltTruong').val(), $('#sltYear').val(), $('#user_id').val());
        $('#myModalDetail').modal('hide');
    }, function (data) {
        console.log('LuuCapKinhPhi.click');
        console.log(data);
    }, "", "", "");
}


