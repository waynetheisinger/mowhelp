// Initialize modules
// Importing specific gulp API functions lets us write them below as series() instead of gulp.series()
const { src, dest, watch, series } = require('gulp');
// Importing all the Gulp-related packages we want to use
const sourcemaps = require('gulp-sourcemaps');
const sass = require('gulp-sass');
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');



// File paths
const files = { 
    finalPath: 'assets/css/style.scss',
    scssPath: 'assets/scss/**/*.scss'
}

// Sass task: compiles the style.scss file into style.css
function scssTask(){    
    return src(files.finalPath)
        .pipe(sourcemaps.init()) // initialize sourcemaps first
        .pipe(sass()) // compile SCSS to CSS
        .pipe(postcss([ autoprefixer(), cssnano() ])) // PostCSS plugins
        .pipe(sourcemaps.write('.')) // write sourcemaps file in current directory
        .pipe(dest('assets/css')
    ); // put final CSS in dist folder
}

// Watch task: watch SCSS and JS files for changes
// If any change, run scss and js tasks simultaneously
function watchTask(){
    //watch([files.scssPath, files.jsPath],
    watch([files.scssPath],
        {interval: 1000, usePolling: true}, //Makes docker work
        series(
            scssTask
        )
    );    
}

// Export the default Gulp task so it can be run
// Runs the scss and js tasks simultaneously
// then runs cacheBust, then watch task
exports.default = series(
    scssTask, 
    watchTask
);