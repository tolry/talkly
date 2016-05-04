var gulp = require('gulp'),
    sass = require('gulp-sass'),
    concat = require('gulp-concat'),
    minifyCSS = require('gulp-clean-css'),
    browserSync = require('browser-sync').create()
    ;

gulp.task('default', ['css', 'js', 'fonts']);

gulp.task('css', function () {
    return gulp.src(['src/scss/app.scss'])
        .pipe(concat('app.scss'))
        .pipe(sass({includePaths: [
            'bower_components/foundation/scss/',
            'bower_components/font-awesome/scss/'
        ], errLogToConsole: true}))
        .pipe(minifyCSS())
        .pipe(gulp.dest('web/css/'))
        .pipe(browserSync.stream());
});

gulp.task('js', function () {
    return gulp.src([
            'bower_components/modernizr/modernizr.js',
            'bower_components/angular/angular.min.js',
            'bower_components/angular-resource/angular-resource.min.js',
            'bower_components/moment/min/moment.min.js',
            'bower_components/angular-moment/angular-moment.min.js',
            'bower_components/jquery/dist/jquery.min.js',
            'bower_components/foundation/js/foundation.min.js',
            'bower_components/headroom.js/dist/headroom.js',
            'bower_components/headroom.js/dist/angular.headroom.js',
            'src/js/services.js',
            'src/js/directives.js',
            'src/js/app.js',
            'src/js/app.markdown-preview.js'
        ])
        .pipe(concat('app.js'))
        .pipe(gulp.dest('web/js/'))
        .pipe(browserSync.stream());
});

gulp.task('fonts', function () {
    return gulp.src('bower_components/font-awesome/fonts/*')
        .pipe(gulp.dest('web/fonts/font-awesome'));
});

gulp.task('watch', ['default'], function () {
    browserSync.init({
        proxy: 'localhost',
        open: false
    });

    gulp.watch('src/scss/*.scss', ['css']);
    gulp.watch('src/js/*.js', ['js']);
    gulp.watch('src/**/*.html.twig').on('change', browserSync.reload);
});