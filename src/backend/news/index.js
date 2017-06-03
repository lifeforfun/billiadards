import {prevent} from 'global'

function Page(page_no) {
    Page.param = Page.param||{ pagesize:10 }
    let param = Page.param,
        layerId = layer.load()
    param.page = page_no
    console.log(param)
    $.getJSON(createUrl('news/list'), param)
        .then(ret => {
            layer.alert(JSON.stringify(ret))
        })
        .fail(e => {
            if (e && e.status!==200) {
                layer.msg(e.statusText, {icon:2})
            }
        })
        .always(() => {
            layer.close(layerId)
        })
}

function Search(e) {
    prevent(e)
    $.each($(this).serializeArray(), (a, b) => Page.param[b.name] = b.value)
    Page(1)
}

$(function () {
    $('#content')
        .on('submit', 'form', Search)

    Page(1)
})

