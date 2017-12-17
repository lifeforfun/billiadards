import {prevent} from 'global'
import TplList from './index-list.tmpl'

function Search(e) {
    prevent(e)
}

function Page(page_no) {
    Page.param = Page.param || {pagesize:10}
    let param = Page.param,
        layerId = layer.load()
    param.page = page_no
    $.getJSON(createUrl('shop/list'), param)
        .then(ret => {
            if (!ret.status) {
                return layer.alert(ret.msg)
            }
            Page.list = ret.data.list
            $('#list-wrap').html(TplList({
                list:Page.list
            }))
            var pg = $('#page'),
                total = Number(ret.data.total)
            if (pg.data('load')) {
                pg.pagination('updateItemsCount', total, page_no)
            } else {
                pg.data('load', 1).pagination({
                    showCtrl:true,
                    itemsCount:total,
                    pageSize:param.pagesize,
                    currentPage:page_no,
                    onSelect(page) {
                        Page(page)
                    }
                })
            }
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


function Edit(e) {

}

function Del(e) {

}

$(() => {
    $('#content')
        .on('submit', 'form', Search)
        .on('click', '.edit-record', Edit)
        .on('click', '.delete-record', Del)
    Page(1)
})