import {prevent, redirect} from 'global'
import 'vendor/jquery-form/src/jquery.form'


function Save(e) {
    prevent(e)
    $(this).ajaxSubmit({
        type:'POST',
        dataType:'json',
        error(e) {
            layer.msg(e.message||e.statusText, {icon:2})
        },
        success(ret) {
            if (!ret.status) {
                return layer.msg(ret.msg, {icon:0})
            }
            layer.msg('已保存', {icon:1}, () => {
                redirect(createUrl('news/index'))
            })
        }
    })
}

$(function () {

    $('#content')
        .on('submit', '#edit-form', Save)
})