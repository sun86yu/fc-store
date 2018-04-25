var gulp = require('gulp');
var clean = require('gulp-clean');
var cleanCss = require('gulp-clean-css');
var sequence = require('gulp-sequence');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');
var path = require('path');

module.exports = function () {
    var adminlte_path = path.join(__dirname, 'AdminLTE');
    var dist_path = path.join(__dirname, '../public/admin');

    gulp.task('adminlte:css', function () {
        return gulp.src(path.join(adminlte_path, 'css/*.css'))
            .pipe(cleanCss())
            .pipe(rename({
                suffix: '.min'
            }))
            .pipe(gulp.dest(path.join(dist_path, '/css/')));
    });

    gulp.task('adminlte:clean', function () {
        return gulp.src(dist_path)
            .pipe(clean());
    });

    gulp.task('adminlte:img', function () {
        return gulp.src(path.join(adminlte_path, 'img/*'))
            .pipe(gulp.dest(path.join(dist_path, '/img/')));
    })

    gulp.task('adminlte:js', function () {
        return gulp.src(path.join(adminlte_path, 'js/*.js'))
            .pipe(uglify())
            .pipe(rename({
                suffix: '.min'
            }))
            .pipe(gulp.dest(path.join(dist_path, '/js/')));
    });

    gulp.task('adminlte:build', ['adminlte:clean'], sequence([
        'adminlte:css',
        'adminlte:img',
        'adminlte:js',
    ]))
};

