var ajaxHttp = function (url, type, data, success, error) {
    if (error == undefined) {
        var error = function () {
            toastr.options.timeOut = 5000;
            toastr.error('ajax请求失败！')
        };
    }
    $.ajax({
        url: url,
        type: type,
        data: data,
        dataType: "json",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: success,
        error: error
    });
}

var ajaxForm = function (dom) {
    ajaxHttp(
        dom.attr("action"),
        dom.attr("method"),
        dom.serializeArray(),
        function (result) {
            dom.trigger("success", result);
        }
    );
}

var ajaxDelete = function (dom) {
    ajaxHttp(
        dom.attr("href"),
        dom.attr("method"),
        [],
        function (result) {
            if (result.state == 'success') {
                swal(result.message, "", "success")
                setTimeout("window.location.reload()", 1000);
            } else {
                toastr.options.timeOut = 5000;
                toastr.error(result.message);
            }
        }
    );
}

$(document).on("submit", ".ajax-form", function () {
    var thisDom = $(this);
    if (thisDom.attr("confirm")) {
        swal({
            title: thisDom.attr("confirm"),
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定",
            cancelButtonText: "取消",
            closeOnConfirm: false
        }, function () {
            if(window.beforeAjax){
                window.beforeAjax();
            }
            ajaxForm(thisDom);
        });
    } else {
        if(window.beforeAjax){
            window.beforeAjax();
        }
        ajaxForm(thisDom);
    }
    return false;
});

$(document).on("click", ".ajax-delete", function () {
    var thisDom = $(this);
    if (thisDom.attr("confirm")) {
        swal({
            title: thisDom.attr("confirm"),
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定",
            cancelButtonText: "取消",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function () {
            if(window.beforeDelete){
                window.beforeAjax();
            }
            ajaxDelete(thisDom);
        });
    } else {
        if(window.beforeDelete){
            window.beforeAjax();
        }
        ajaxDelete(thisDom);
    }
    return false;
});