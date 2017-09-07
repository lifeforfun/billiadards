import template from 'tmodjs-loader/runtime'

template.helper('url', (query, route) => {
    return createUrl(route, query)
})