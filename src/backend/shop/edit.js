import {prevent, redirect} from "global"
import "vendor/jquery-form/src/jquery.form"

let BMap = window.BMap

function Submit(e) {
    prevent(e)
    let data = {
        longitude: e.data.bmap.longitude,
        latitude: e.data.bmap.latitude
    }
    $.each($(this).serializeArray(), (i, item) => {
        data[item.name] = item.value
    })
    $(this).ajaxSubmit({
        type:'POST',
        dataType:'json',
        error(e) {
            layer.msg(e.message||e.statusText, {icon:2})
        },
        success(resp) {
            if (!resp.status) {
                return layer.msg(resp.msg, {icon: 0})
            }
            layer.msg('已保存', {icon: 1}, () => {
                redirect(createUrl('shop/index'))
            })
        }
    })
}

function Map(longitude, latitude) {
    var map = new BMap.Map('poi')
    this.map = map
    this.longitude = longitude && Number(longitude) || 116.331398
    this.latitude = latitude && Number(latitude) || 39.897445

    map.centerAndZoom(new BMap.Point(this.longitude, this.latitude), 19);
    map.enableScrollWheelZoom()
    map.enableDoubleClickZoom()
    map.enableInertialDragging()
    map.addControl(new BMap.GeolocationControl({
        anchor: window.BMAP_ANCHOR_BOTTOM_RIGHT
    }))

    if (!latitude) {
        this.getLocation(r => {
            this.center(r.point)
        })
    }

    map.addEventListener('click', e => {
        let point = e.point
        this.longitude = point.lng
        this.latitude = point.lat
        this.marker.setPosition(point)
    })
}

let MapProto = Map.prototype
MapProto.getLocation = function (cb) {
    let geolocation = new BMap.Geolocation()
    geolocation.getCurrentPosition(function (r) {
        console.log(r)
        if (this.getStatus() != window.BMAP_STATUS_SUCCESS) {
            return alert('定位失败,请手动调整地图')
        }
        cb(r)
    }, {
        enableHighAccuracy: true
    })
}
MapProto.center = function (point) {
    let marker = new BMap.Marker(point)
    this.map.addOverlay(marker)
    this.map.panTo(point)
    this.marker = marker
}

$(function () {
    let bmap = new Map();
    $('#content')
        .on('submit', 'form', {bmap: bmap}, Submit)
})