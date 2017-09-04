var gulp = require('gulp'),
    gutil = require('gulp-util'),
    path = require('path'),
    fs = require('fs'),
    webpack = require('webpack'),
    webpackStream = require('webpack-stream'),
    UglifyJSPlugin = require('uglifyjs-webpack-plugin'),
    srcPath = path.join(__dirname, 'src')


gulp.add('default', ['build-backend'], function (cb) {
    cb()
})
    .on('error', e => console.log(e))

gulp.add('build-backend', function () {
    return doBuild('backend')
})

gulp.add('watch-backend', function () {
    return doDev('backend')
})


function doBuild(platform) {
    return webpackStream(getConfig(platform, {
        devtool: 'source-map',
        plugins: [
            new UglifyJSPlugin({
                sourceMap: true
            })
        ]
    }, false), webpack)
        .on('error', function () {
            this.emit('end')
        })
        .pipe(gulp.dest('./' + platform + '/web/dist/'))
}

function doDev(platform) {
    var config = getConfig(platform, {
        watch: true,
        devtool: 'eval-source-map',
    }, true)
    return webpackStream(config, webpack)
        .on('error', function (err) {
            gutil.log('WEBPACK ERROR', err)
            this.emit('end')
        })
        .pipe(gulp.dest(platform + '/web/dist'))
}


//递归删除目录
function deleteDir(dirpath) {
    if (!fs.existsSync(dirpath)) {
        return;
    }
    var files = fs.readdirSync(dirpath);
    files.forEach(function (file) {
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
    var distPath = path.join(__dirname, platform, 'web', 'dist')
    deleteDir(distPath)
    var config = {
        context: srcPath,
        entry: getEntry(path.join(srcPath, platform), './' + platform, ''),
        output: {
            path: distPath,
            filename: '[name].js',
            chunkFilename: '[name].js',
            publicPath: './dist/'
        },
        resolve: {
            modules: [srcPath, "node_modules"],
            alias: {},
            extensions: ['.js']
        },
        externals: {
            jquery: "jQuery"
        },
        module: {
            rules: [
                {
                    test: /\.js$/,
                    enforce: "pre",
                    use: 'eslint-loader',
                    include: srcPath
                },
                {
                    test: /\.js$/,
                    use: 'babel-loader',
                    include: srcPath
                },
                {
                    test: /\.html$/,
                    use: [
                        'raw-loader',
                        'html-minify-loader'
                    ],
                },
                {
                    test: /\.css$/i,
                    use: [
                        'style-loader',
                        {
                            loader: 'css-loader',
                            options: {
                                sourceMap: true,
                            }
                        }
                    ]
                },
                {
                    test: /\.less/i,
                    use: [
                        'style-loader',
                        {
                            loader: 'css-loader',
                            options: {
                                sourceMap: true,
                            }
                        },
                        'less-loader',
                    ]
                },
                {
                    test: /\.(jpg|jpeg|gif|png)$/i,
                    use: [
                        {
                            loader: 'file-loader',
                            options: {
                                name: 'img/[name]-[hash:6].[ext]'
                            }
                        }
                    ],
                },
                {
                    test: /\.tmpl$/,
                    use: ['tmodjs-loader'],
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
            // new webpack.DefinePlugin({
            // })
        ]
    };
    return opt ? merge(config, opt) : config;
}

function merge(a, b) {
    if (a instanceof Array) {
        return a.concat(b);
    } else if (typeof a === 'object') {
        for (var i in b) {
            if (!a[i])
                a[i] = b[i];
            else
                a[i] = merge(a[i], b[i]);
        }
        return a;
    } else {
        return b;
    }
}

function getEntry(dir, pathPrefix, entryPrefix) {
    pathPrefix = pathPrefix && pathPrefix !== '/' ? pathPrefix.replace(/\/$/, '') + '/' : ''
    entryPrefix = entryPrefix && entryPrefix !== '/' ? entryPrefix.replace(/\/$/, '') + '/' : ''
    var handle = fs.readdirSync(dir),
        tmp = {};
    handle.forEach(function (filename, index) {
        var stats = fs.statSync(path.join(dir, filename));
        if (stats.isDirectory()) {
            //合并entry
            tmp = merge(getEntry(path.join(dir, filename), pathPrefix + filename, entryPrefix + filename), tmp);
        } else if (stats.isFile() && /\.js$/.test(filename)) {
            if (/^_/.test(filename))
                return;
            filename = filename.substr(0, filename.length - 3);
            tmp[entryPrefix + filename] = pathPrefix + filename;
        }
    });
    return tmp;
}