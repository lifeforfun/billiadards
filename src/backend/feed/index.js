import TplList from './index-list.tmpl'
import {prevent} from 'global'

function Search(e) {
    prevent(e)
    $.each($(this).serializeArray(), (i, item) => {
        Page.param[item.name] = item.value
    })
    Page(1)
}

function Page(num) {
    Page.param = Page.param || {pagesize:10}
    var param = Page.param
    param.page = num
    $.getJSON(createUrl('feedback/list'), param)
        .then(ret => {
            if (!ret.status) {
                return layer.msg(ret.msg,{icon:0})
            }
            $('#list-wrap').html(TplList({
                list:ret.data.list
            }))

            var page = $('#page'),
                total = Number(ret.data.total)
            if (page.data('load')) {
                page.pagination('updateItemsCount', total, num)
            } else {
                $('#page').data('load', 1).pagination({
                    showCtrl:true,
                    currentPage:num,
                    itemsCount:total,
                    pageSize:param.pagesize,
                    onSelect(p) {
                        Page(p)
                    }
                })
            }
        })
        .fail(e => {
            layer.alert(e.message||e.statusText)
        })
}

$(() => {
    $('#content')
        .on('submit', 'form', Search)

    Page(1)
})