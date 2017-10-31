/**
 * Created by Administrator on 2017/8/26.
 */

$('body').on('mouseover', '.category-has-children', function () {
    $(this).children('ul').show();
});

$('body').on('mouseout', '.category-has-children', function () {
    $(this).children('ul').hide();
});

$('#login-logo').on('click', '#qq-login-logo', function () {
    window.location.href = "/qq";
});

$('body').on('mouseover', '#login-out', function () {
    var title = "是否退出登录？ <a href='/qq/loginout' style='color: #43ff25'>退出</a>";
    layer.tips(title, $(this).children('i'), {tips: [1, '#3595CC'], time: 5000});
});
