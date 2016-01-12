var gulp = require('gulp');
var sass = require('gulp-sass');
var browserSync = require('browser-sync').create();
var sourcemaps = require('gulp-sourcemaps');
var concat = require('gulp-concat');
var clean = require('gulp-clean');

var paths = {
    theme: './wp-content/themes/Newspaper-child/',
    css_public: './wp-content/themes/Newspaper-child/css/',
    js_public: '/public/js/',
    sass: './wp-content/themes/Newspaper-child/assets/sass/',
    build: './wp-content/themes/Newspaper-child/style.css'
};

gulp.task('sass', function() {
    console.log('Compiling SCSS to CSS, adding sourcemaps...');
    return gulp.src(paths.sass + '**/*.scss')
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(paths.css_public));
});

gulp.task('concat', function() {
    gulp.src(paths.css_public + '**/*.css')
        .pipe(concat('style.css'))
        .pipe(gulp.dest(paths.theme));
});

gulp.task('watch', function() {
    gulp.watch([
        paths.sass + '**/*.scss'
    ], ['default']);
});

gulp.task('clean-compiled', function() {
    console.log('Cleaning build folder...');
    return gulp.src([
            paths.theme + 'style.css',
        ], {
            read: false
        })
        .pipe(clean());
});

// Sync browser improvement mobile workflow.
gulp.task('browser-sync', function() {
    browserSync.init({
        proxy: "dev.daily.rabbit.co.th"
    });
});

// Running default task.
gulp.task('default', [

    'sass',
    'clean-compiled',
    'concat'
]);
