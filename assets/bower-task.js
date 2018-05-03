var gulp = require('gulp');
var clean = require('gulp-clean');
var cleanCss = require('gulp-clean-css');
var sequence = require('gulp-sequence');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');
var path = require('path');

module.exports = function () {
    var bower_path = path.join(__dirname, 'components');
    var dist_path = path.join(__dirname, '../public/components');

    gulp.task('bower:jquery', function () {
        return gulp.src(path.join(bower_path, 'jquery/dist/jquery.min.js'))
            .pipe(gulp.dest(path.join(dist_path, 'jquery')));
    });

    gulp.task('bower:font-awesome', function () {
        return gulp.src(path.join(bower_path, 'font-awesome/{css/*min.css,fonts/*}'))
            .pipe(gulp.dest(path.join(dist_path, 'font-awesome')));
    })

    gulp.task('bower:jquery-ui', function () {
        return gulp.src(path.join(bower_path, 'jquery-ui/jquery-ui.min.js'))
            .pipe(gulp.dest(path.join(dist_path, 'jquery-ui/')));
    });

    gulp.task('bower:bootstrap-css', function () {
        return gulp.src(path.join(bower_path, 'bootstrap/dist/css/*[^min].css'))
            .pipe(cleanCss())
            .pipe(rename({
                suffix: '.min'
            }))
            .pipe(gulp.dest(path.join(dist_path, 'bootstrap/css/')));
    });

    gulp.task('bower:bootstrap-js', function () {
        return gulp.src(path.join(bower_path, 'bootstrap/dist/js/bootstrap.js'))
            .pipe(uglify())
            .pipe(rename({
                suffix: '.min'
            }))
            .pipe(gulp.dest(path.join(dist_path, 'bootstrap/js/')));
    });

    gulp.task('bower:bootstrap-datepicker', function () {
        return gulp.src(path.join(bower_path, 'bootstrap-datepicker/dist/{js/bootstrap-datepicker.min.js,locales/bootstrap-datepicker.zh-CN.min.js}'))
            .pipe(gulp.dest(path.join(dist_path, 'bootstrap-datepicker/')));
    });
    gulp.task('bower:bootstrap-datepicker-css', function () {
        return gulp.src(path.join(bower_path, 'bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css'))
            .pipe(gulp.dest(path.join(dist_path, 'bootstrap-datepicker/')));
    });
    gulp.task('bower:bootstrap-datepicker-range', function () {
        return gulp.src(path.join(bower_path, 'bootstrap-daterangepicker/daterangepicker.js'))
            .pipe(uglify())
            .pipe(rename({
                suffix: '.min'
            }))
            .pipe(gulp.dest(path.join(dist_path, 'bootstrap-daterangepicker/')));
    });
    gulp.task('bower:bootstrap-datepicker-range-css', function () {
        return gulp.src(path.join(bower_path, 'bootstrap-daterangepicker/daterangepicker.css'))
            .pipe(cleanCss())
            .pipe(rename({
                suffix: '.min'
            }))
            .pipe(gulp.dest(path.join(dist_path, 'bootstrap-daterangepicker/')));
    });

    gulp.task('bower:moment', function () {
        return gulp.src(path.join(bower_path, 'moment/moment.js'))
            .pipe(uglify())
            .pipe(rename({
                suffix: '.min'
            }))
            .pipe(gulp.dest(path.join(dist_path, 'moment/')));
    });

    gulp.task('bower:bootstrap-font', function () {
        return gulp.src(path.join(bower_path, 'bootstrap/dist/fonts/*'))
            .pipe(gulp.dest(path.join(dist_path, 'bootstrap/fonts/')));
    });

    gulp.task('bower:iconicons-css', function () {
        return gulp.src(path.join(bower_path, 'Ionicons/css/*[^min].css'))
            .pipe(cleanCss())
            .pipe(rename({
                suffix: '.min'
            }))
            .pipe(gulp.dest(path.join(dist_path, 'ionicons/css/')));
    });

    gulp.task('bower:iconicons-font', function () {
        return gulp.src(path.join(bower_path, 'Ionicons/fonts/*'))
            .pipe(gulp.dest(path.join(dist_path, 'ionicons/fonts/')));
    });

    gulp.task('bower:fastclick', function () {
        return gulp.src(path.join(bower_path, 'fastclick/lib/fastclick.js'))
            .pipe(uglify())
            .pipe(rename({
                suffix: '.min'
            }))
            .pipe(gulp.dest(path.join(dist_path, 'fastclick/')));
    });

    gulp.task('bower:slimscroll', function () {
        return gulp.src(path.join(bower_path, 'jquery-slimscroll/jquery.slimscroll.min.js'))
            .pipe(gulp.dest(path.join(dist_path, 'jquery-slimscroll/')));
    });

    gulp.task('bower:summernote', function () {
        return gulp.src(path.join(bower_path, 'summernote/dist/{font/*,lang/summernote-zh-CN.js,summernote.js,summernote.css}'))
            .pipe(gulp.dest(path.join(dist_path, 'summernote/')));
    });

    gulp.task('bower:datatables', function () {
        return gulp.src(path.join(bower_path, 'datatables.net-bs/css/dataTables.bootstrap.min.css'))
            .pipe(gulp.dest(path.join(dist_path, 'datatables.net-bs/')));
    });

    gulp.task('bower:jq-validate', function () {
        return gulp.src(path.join(bower_path, 'jquery-validation/dist/{jquery.validate.min.js,additional-methods.min.js}'))
            .pipe(gulp.dest(path.join(dist_path, 'jquery-validation/')));
    });

    gulp.task('bower:jquery-upload', function () {
        return gulp.src(path.join(bower_path, 'blueimp-file-upload/js/{jquery.fileupload.js,vendor/jquery.ui.widget.js,' +
            'jquery.iframe-transport.js,jquery.fileupload-ui.js,jquery.fileupload-process.js}'))
            .pipe(uglify())
            .pipe(rename({
                suffix: '.min'
            }))
            .pipe(gulp.dest(path.join(dist_path, 'blueimp-file-upload/')));
    });
    gulp.task('bower:jquery-upload-tmp', function () {
        return gulp.src(path.join(bower_path, 'blueimp-tmpl/js/tmpl.min.js'))
            .pipe(gulp.dest(path.join(dist_path, 'blueimp-file-upload/')));
    });
    gulp.task('bower:jquery-upload-css', function () {
        return gulp.src(path.join(bower_path, 'blueimp-file-upload/css/*'))
            .pipe(cleanCss())
            .pipe(rename({
                suffix: '.min'
            }))
            .pipe(gulp.dest(path.join(dist_path, 'blueimp-file-upload/css/')));
    });
    gulp.task('bower:jquery-upload-img', function () {
        return gulp.src(path.join(bower_path, 'blueimp-file-upload/img/*'))
            .pipe(gulp.dest(path.join(dist_path, 'blueimp-file-upload/img/')));
    });

    gulp.task('bower:clean', function () {
        return gulp.src(dist_path)
            .pipe(clean());
    });

    gulp.task('bower:build', ['bower:clean'], sequence([
        'bower:jquery',
        'bower:font-awesome',
        'bower:jquery-ui',
        'bower:bootstrap-css',
        'bower:bootstrap-js',
        'bower:bootstrap-font',
        'bower:bootstrap-datepicker',
        'bower:bootstrap-datepicker-css',
        'bower:bootstrap-datepicker-range',
        'bower:bootstrap-datepicker-range-css',
        'bower:iconicons-css',
        'bower:iconicons-font',
        'bower:fastclick',
        'bower:slimscroll',
        'bower:summernote',
        'bower:datatables',
        'bower:jq-validate',
        'bower:jquery-upload',
        'bower:jquery-upload-tmp',
        'bower:jquery-upload-css',
        'bower:jquery-upload-img',
        'bower:moment',
    ]))
};

