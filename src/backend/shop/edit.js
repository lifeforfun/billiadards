import {prevent} from "global"

let BMap = window.BMap

function Submit(e) {
    prevent(e)
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
MapProto.click = function (cb) {
    let listener = e => {
        $(document).on('mouseup', )
    }
    this.map.addEventListener('mousedown', listener)
}

$(function () {
    let bmap = new Map();
    $('#content')
        .on('submit', 'form', Submit)
})