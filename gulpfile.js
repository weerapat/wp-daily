var gulp = require('gulp');
var browserSync = require('browser-sync').create();

// Static server
// gulp.task('browser-sync', function() {
//     browserSync.init({
//         server: {
//             baseDir: "./"
//         }
//     });
// });

// or...

gulp.task('browser-sync', function() {
    browserSync.init({
        proxy: "dev.daily.rabbit.co.th"
    });
});

gulp.task('default',['browser-sync']);
