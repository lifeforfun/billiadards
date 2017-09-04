/*************** datepicker ****************/
import 'bootstrap-datepicker'
import 'bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN'
import 'bootstrap-datepicker/dist/css/bootstrap-datepicker.css'

$.fn.datepicker.defaults.language = 'zh-CN'
$.fn.datepicker.dates['zh-CN'].format = {
    toDisplay(date) {
        return dateFmt(date, 'yyyy-mm-dd')
    },
    toValue(date) {
        return parseDate(date)
    }
}

/*************** layer *********************/
import 'vendor/layer-dialog/skin/layer.css'
window.layer = require('vendor/layer-dialog')


export function redirect(url) {
    window.location = url
}

/**
 *
 * @param dateStr string
 * @return Date
 */
export function parseDate(dateStr) {
    var date = new Date(),
        toInt = Number;
    dateStr = dateStr.split(' ');
    if (dateStr.length < 2)
        dateStr.push('00:00:00');
    var dateList = dateStr[0].split('-'),
        timeList = dateStr[1].split(':');
    date.setFullYear(toInt(dateList[0]), dateList[1] ? toInt(dateList[1]) - 1 : 0, dateList[2] ? toInt(dateList[2]) : 1);
    date.setHours(toInt(timeList[0]), timeList[1] ? toInt(timeList[1]) : 0, timeList[2] ? toInt(timeList[2]) : 0, 0);
    return date;
}
/**
 *
 * @param date Date
 * @param fmt string
 * @return string
 */
export function dateFmt(date, fmt) {
    if (!fmt)
        fmt = 'yyyy-mm-dd hh:ii:ss';
    var obj = {
        yyyy: date.getFullYear().toString(),
        yy: date.getFullYear().toString().substring(2, 4),
        mm: (date.getMonth() + 1) < 10 ? '0' + (date.getMonth() + 1).toString() : (date.getMonth() + 1).toString(),
        dd: date.getDate() < 10 ? '0' + (date.getDate()).toString() : (date.getDate()).toString(),
        hh: date.getHours() < 10 ? '0' + (date.getHours()).toString() : (date.getHours()).toString(),
        ii: date.getMinutes() < 10 ? '0' + (date.getMinutes()).toString() : (date.getMinutes()).toString(),
        ss: date.getSeconds() < 10 ? '0' + (date.getSeconds()).toString() : (date.getSeconds()).toString()
    };
    for (var i in obj) {
        fmt = fmt.replace(new RegExp(i, 'g'), obj[i]);
    }
    return fmt;
}

/**
 * prevent default
 * @param e
 */
export function prevent(e) {
    if (e&&e.preventDefault) {
        e.preventDefault()
    } else {
        window.event.returnValue = false
    }
}