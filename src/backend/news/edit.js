import {prevent} from 'global'
import 'vendor/jquery-form/src/jquery.form'

function Save(e) {
    prevent(e)
    $(this).ajaxSubmit({
        type:'POST'
    })
}

$(function () {
    $('#content')
        .on('submit', '#edit-form', Save)
})