import 'global'

$(() => {
    $('#save').click(() => {
        $.post(createUrl('about/save'), {
            about:$('#about').val()
        }, 'json')
            .then(ret => {
                if (!ret.status) {
                    return layer.msg(ret.msg, {icon:0})
                }
                layer.msg('已保存', {icon:1})
            })
            .fail(e => {
                layer.alert(e.message||e.statusText)
            })
    })
})