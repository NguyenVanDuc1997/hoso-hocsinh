@extends('layouts.auth')

@section('title', 'Đăng nhập - Phần mềm quản lý hồ sơ')
@section('description', 'Login to the admin area')

@section('content')
<style type="text/css">
    .ui-autocomplete {
    position: absolute;
    z-index: 1000;
    cursor: default;
    padding: 0;
    margin-top: 2px;
    list-style: none;
    background-color: #ffffff;
    border: 1px solid #ccc;
    -webkit-border-radius: 5px;
       -moz-border-radius: 5px;
            border-radius: 5px;
    -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
       -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
}
.ui-autocomplete > li {
  padding: 3px 20px;
}
.ui-autocomplete > li.ui-state-focus {
  background-color: #DDD;
}
.ui-helper-hidden-accessible {
  display: none;
}
</style>
<script type="text/javascript">
            $(document).ready(function () {
        $(".help-block").show().delay(5000).fadeOut();
    });
    </script> 
    <div class="container">
<!--         <iframe  id="mainFrame" name="mainFrame" class="ui-layout-center"
  src="http://thuxahoihoa.revosoft.vn/admin/login"></iframe>    -->
        <div class="card card-container">
            <form id="myLogin" class="form-signin" method="POST" action="{{ url('/logins') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="text" id="username" name="username" class="form-control ui-autocomplete-input" placeholder="Tên đăng nhập" value="{{ old('username') }}" required autofocus>
                @if ($errors->has('username'))
                    <span class="help-block">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                @endif
                <input type="password" id="password" name="password" class="form-control" placeholder="Mật khẩu" required>
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
                <div id="remember" class="checkbox">
                    <label>
                        <input type="checkbox" value="remember-me" name="remember"> Nhớ  đăng nhập
                    </label>
                </div>
                <button class="btn btn-lg btn-primary btn-block btn-signin" id="btn-signin" type="submit">Đăng nhập</button>
            </form><!-- /form -->
            <a href="{{ url('/password/reset') }}" class="forgot-password">Quên mật khẩu?</a><!--  or <a href="{{ url('/username/reminder') }}" class="forgot-password">Quên tài khoản đăng nhập?</a> -->
        </div><!-- /card-container -->
        <!-- <div class="card-container text-center">
            <a href="{{ url('/register') }}" class="new-account">Create an account</a> or <a href="{{ url('/activation/resend') }}" class="new-account">Resend activation code</a>
        </div> -->
        
    </div><!-- /container -->
@endsection

@push('scripts')
<script type="text/javascript">
    $(function () {
        login_autocomplete();
        $('form[id="myLogin"]').keypress(function (e) {
            var key = e.which;
            if(key == 13){
                $('#btn-signin').click();
            }
        });

    });
    function my_login() {
        var user =  $('#username').val();
        var pass =  $('#password').val();
        //$.get('http://thuxahoihoa.revosoft.vn/admin/dang-nhap?email=admin@admin.com&password=123456', function( data ) {
            //document.getElementById("myLogin").submit();  
        //});
        //$('#btn-signin').button('loading');
         $.ajax({
             type: "GET",
             url: 'http://thuxahoihoa.revosoft.vn/admin/dang-nhap?email='+user+'&password='+pass,
             xhrFields: {
                withCredentials: true
            },
            crossDomain: true,
            success: function (val) {
                //$('#btn-signin').button('reset');
                document.getElementById("myLogin").submit();                
            },
            error: function (val) {
                console.log(val);
                document.getElementById("myLogin").submit();                
             }
         });


    }
    function login_autocomplete() {
        var lstCustomerForCombobox;
        $('#username').autocomplete({
            source: function (request, response) {
                var cusNameSearch = $.ui.autocomplete.escapeRegex(request.term).replace(/[%\\\-]/g, '');
                if (cusNameSearch.length >= 1) {
                    $.get("/autocomplete/login?q=" + cusNameSearch.toLowerCase(), function (data) {
                        lstCustomerForCombobox = [];
                        var item;
                        if (data.length > 0) {
                            for (var i = 0; i < data.length; i++) {
                                var dl = data[i];
                                if (dl.username != null){
                                    item = dl.username + '-' + dl.last_name;
                                }
                                else{
                                    item = dl.username;
                                }
                                lstCustomerForCombobox.push(item);
                            }
                        } else {
                            $('#username').val('');
                        }
                        var matcher = new RegExp(cusNameSearch, "i");
                        response($.grep(lstCustomerForCombobox, function (item) {
                            return matcher.test(item);
                        }));
                    });
                }
            },
            minLength: 1,
            delay: 222,
            autofocus: true,
            select: function (event, ui) {
                var value = ui.item.value;
                var customerCode = value.split('-')[0];
                $('#username').val(customerCode);
                return false;
            }
        });
    };
    //     var keySearch = "";
    //     $('#username').autocomplete({
    //         source: function (request, response) {
    //             keySearch = $.ui.autocomplete.escapeRegex(request.term).replace(/[%\\\-]/g, '');
    //             GetFromServer('autocomplete/login?q='+keySearch,function(data){
    //                 response($.map($.parseJSON(data.d), function (item) {
    //                     return {
    //                         text: item.username + '-' + item.last_name,
    //                         id: item.username,
    //                         data: item
    //                     };
    //                 }));
    //             },null,"","");
    //         },
    //         minLength: 0,
    //         delay: 222,
    //         autofocus: true,
    //         templateResult: format
    //     });

    // function format(state) {
    //     if (!state.id) { return state.text; }
    //     var $state = $("<div><span></span> <span></span> <br/> <span>-</span> <span>-</span> <br/>"
    //                       + "<span>-</span> <span>-</span><br/><span>-</span>"
    //                 + "<div>");
    //     return $state;
    // }
</script>


@endpush
