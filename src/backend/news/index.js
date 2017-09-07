import {prevent} from 'global'
import TplList from './index-list.tmpl'

function Page(page_no) {
    Page.param = Page.param||{ pagesize:10 }
    let param = Page.param,
        layerId = layer.load()
    param.page = page_no
    console.log(param)
    $.getJSON(createUrl('news/list'), param)
        .then(ret => {
            if (!ret.status) {
                return layer.alert(ret.msg)
            }
            Page.list = ret.data.list
            $('#list-wrap').html(TplList({
                list:Page.list
            }))
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

function Audit(e) {
    prevent(e)
    let id = $(this).data('id'),
        parent = $(this).parents('tr'),
        list = Page.list,
        item
    $.each(list, (i, innerItem) => {
        if (innerItem.id==id) {
            item = innerItem
        }
    })
    $.get(createUrl('news/audit'), {
        id
    }, ret => {
        if (!ret.status) {
            return layer.msg(ret.msg, {icon:0})
        }
        if (item) {
            item.status = 1
            parent.replaceWith(TplList({
                list:[item]
            }))
        }
    })
        .fail(e => {
            layer.alert(e.message||e.statusText)
        })
}

function Delete(e) {
    prevent(e)
    let id = $(this).data('id'),
        parent = $(this).parents('tr')
    $.Deferred(function () {
        layer.confirm('确定要删除此条信息?', index => {
            layer.close(index)
            this.resolve()
        }, () => {
            this.reject()
        })
    })
        .then(() => {
            return $.getJSON(createUrl('news/delete'), {
                id
            })
        })
        .then(ret => {
            if (!ret.status) {
                return layer.msg(ret.msg, {icon:0})
            }
            parent.remove()
        })
        .fail(e => {
            if (e) {
                layer.msg(e.message||e.statusText, {icon:2})
            }
        })
}

$(function () {
    $('#content')
        .on('submit', 'form', Search)
        .on('click', '.audit', Audit)
        .on('click', '.del', Delete)

    Page(1)
})

