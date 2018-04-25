var path = require('path');
var gulp = require('gulp');
var sequence = require('gulp-sequence');

process.setMaxListeners(0);

var assets_path = path.join(__dirname, 'assets');

require(path.join(assets_path, 'adminlte-task.js'))();
require(path.join(assets_path, 'bower-task.js'))();

gulp.task('app:build', sequence(['adminlte:build', 'bower:build']));