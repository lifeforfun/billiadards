var gulp = require('gulp'),
    path = require('path'),
    fs = require('fs'),
    webpack = require('webpack'),
    srcPath = path.join(__dirname, 'src')



gulp.task('default', ['build-backend'], cb => cb())

gulp.task('build-backend', cb => {

})

gulp.task('watch-backend', cb => {

})


function doBuild(platform, cb) {
    deleteDir(path.join(__dirname, 'build', platform))

}

function doDev(platform, cb) {

}


//递归删除目录
function deleteDir(dirpath)
{
    if (!fs.existsSync(dirpath)) {
        return;
    }
    var files = fs.readdirSync(dirpath);
    files.forEach(function(file){
        var filepath = path.join(dirpath, file);
        if (fs.statSync(filepath).isDirectory()) {
            deleteDir(filepath);
            fs.rmdirSync(filepath);
        } else {
            fs.unlinkSync(filepath);
        }
    });
}

function getConfig(platform, opt, DEBUG) {
    var config = {
        context: srcPath,
        entry: platform+'/main',
        output: {
            path: './build/'+platform,
            filename: '[name].js',
            chunkFilename: '[name].js'
        },
        resolve: {
            alias:{
            },
            extensions: [ '.js']
        },
        module:{
            loaders:[
                {
                    test: /\.js$/,
                    loader: 'babel-loader!eslint-loader',
                    include: srcPath
                },
                {
                    test:/\.html$/,
                    loader:'raw!html-minify'
                },
                {
                    test:/\.css$/i,
                    loader:DEBUG ?
                        'vue-style-loader!css-loader?sourceMap' :
                        'vue-style-loader!css-loader!csso-loader'
                },
                {
                    test:/\.(jpg|jpeg|gif|png)$/i,
                    loader:'file?name=img/[name]-[hash].[ext]'
                },
                {
                    test:/\.vue$/i,
                    loader:'vue-loader'
                },
                {
                    test:/\.tmpl$/,
                    loader:'tmodjs-loader'
                }
            ],
        },
        plugins: [
            new webpack.optimize.CommonsChunkPlugin({
                name: 'common',
                minChunks: function (module, count) {
                    if (count > 4)
                        return true
                }
            }),
            new webpack.DefinePlugin({
                SITE:JSON.stringify(site),
                DEBUG:DEBUG,
                PLATFORM:JSON.stringify(platform)
            })
        ]
    };
    return opt?merge(config,opt):config;
}

function merge(a,b) {
    if(a instanceof Array) {
        return a.concat(b);
    }else if(typeof a==='object') {
        for(var i in b) {
            if(!a[i])
                a[i] = b[i];
            else
                a[i] = merge(a[i],b[i]);
        }
        return a;
    }else {
        return b;
    }
}