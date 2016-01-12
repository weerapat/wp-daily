var gulp = require('gulp');
var browserSync = require('browser-sync').create();

gulp.task('browser-sync', function() {
    browserSync.init({
        proxy: "dev.daily.rabbit.co.th"
    });
});

// Running default task.
gulp.task('default',['browser-sync']);
