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
        'bower:iconicons-css',
        'bower:iconicons-font',
        'bower:fastclick',
        'bower:slimscroll',
        'bower:summernote',
        'bower:datatables',
    ]))
};

