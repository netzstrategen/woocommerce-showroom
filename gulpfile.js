var autoprefixer = require('gulp-autoprefixer');
var eol = require('gulp-eol');
var eslint = require('gulp-eslint');
var gulp = require('gulp');
var runSequence = require('run-sequence');
var sass = require('gulp-sass');
var uglify = require('gulp-uglify');

gulp.task('styles', function () {
  gulp.src('assets/styles/**/*.scss')
  .pipe(sass({outputStyle: 'expanded'}).on('error', sass.logError))
  .pipe(autoprefixer({
    browsers: ['last 2 versions']
  }))
  .pipe(eol('\n'))
  .pipe(gulp.dest('./dist/styles'));
});

gulp.task('eslint', function () {
  return gulp.src(['**/*.js', '!node_modules/**', '!gulpfile.js'])
    .pipe(eslint())
    .pipe(eslint.format());
});

gulp.task('scripts', ['eslint'], function () {
  gulp.src('assets/scripts/**/*.js')
  .pipe(uglify({
    output: { beautify: true }
  }))
  .pipe(eol('\n'))
  .pipe(gulp.dest('./dist/scripts'));
});

gulp.task('build', function(callback) {
  runSequence('styles', 'scripts', callback);
});

gulp.task('watch', function () {
  gulp.watch('assets/styles/**/*.scss', ['styles']);
  gulp.watch('assets/scripts/**/*.js',['scripts']);
});

gulp.task('default', function () {
  gulp.start('build');
});
