import {prevent} from "global"

function Submit(e) {
    prevent(e)
}

$(() => {
    $('#content')
        .on('submit', 'form', Submit)
})