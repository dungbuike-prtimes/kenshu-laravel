let gulp = require('gulp');
let sass = require('gulp-sass');

gulp.task('sass', function() {
    return gulp.src('./resources/sass/*.scss')
        .pipe(sass({outputStyle: 'expanded'}))
        .pipe(gulp.dest('./public/css'));
})

gulp.task('sass:watch', function () {
    gulp.watch('./resources/sass/*.scss', ['sass']);
});
