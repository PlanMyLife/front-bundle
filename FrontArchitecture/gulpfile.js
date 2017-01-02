// Requis
var path = require( 'path' ),
	gulp = require( 'gulp' ),
	del = require( 'del' ),
	compass = require( 'gulp-compass' ),
	scsslint = require( 'gulp-scss-lint' ),
	concat = require( 'gulp-concat' ),
	plumber = require( 'gulp-plumber' ),
	notify = require( 'gulp-notify' ),
	postcss = require( 'gulp-postcss' ),
	autoprefixer = require( 'autoprefixer' ),
	svgstore = require( 'gulp-svgstore' ),
	svgmin = require( 'gulp-svgmin' ),
	rename = require( 'gulp-rename' ),
	gif = require( 'gulp-if' ),
	glob = require( 'glob' ),
	through = require( 'through2' ),
	vinylSource = require( 'vinyl-source-stream' ),
	buffer = require( 'vinyl-buffer' ),
	browserify = require( 'browserify' ),
	babelify = require( 'babelify' ),
	babel = require( 'gulp-babel' ),
	sourcemap = require( 'gulp-sourcemaps' ),
	uglify = require( 'gulp-uglify' ),
	minifier = require( 'gulp-minify' ),
	cleanCSS = require( 'gulp-clean-css' ),
	runner = require( 'run-sequence' ),
	js = require( './gulp-config/simple.linter.js' ),
	iconfont = require( 'gulp-iconfont' ),
	runTimestamp = Math.round( Date.now() / 1000 ),
	err = require( './gulp-config/.gulp-libs/tools/err' ),
	argv = require('yargs').argv;

//Name
var name = 'icons';

// Variables de chemins
var source = 'src/PlanMyLife/MainBundle/Resources/'; // dossier de travail
var destination = 'web/'; // dossier à livrer

var ENV = {
	config: './gulp-config/',
	all: {
		optimise: true,
		relax: false
	}
};

var COMMON = {
	base: 'common-design',
	css: 'common-design/scss/**/*.scss'
};

var DEST = {
	css: destination + 'css',
	js: destination + 'js',
	svg: destination + 'fonts',
	svgStore: destination + 'svg'
};

var APX_CONF = {
	browsers: ['> 4%', 'ie >= 8']
};

// LINTER CONFIGURATION
// ----------------------------------------------------------------------------
var LINT = {
	bundleExec: true,
	config: ENV.config + '.scss-lint.yml'
};

var SRC, SASS, W;
function initConfig() {
	SRC = {
		css: source + 'scss/**/*.scss',
		js: {
			generic: source + 'js/generic/**/*.js',
			pages: source + 'js/pages/**/*.js'
		},
		svg: COMMON.base + 'svg/**.svg',
		svgStore: source + 'svg/*.svg'
	};


	SASS = {
		bundle_exec: true,
		config_file: ENV.config + 'config.rb',
		sass: source + 'scss',
		css: destination + 'css',
		relative: false,
		comments: !ENV.all.optimise
	};

	// WATCH CONFIGURATION
	// ----------------------------------------------------------------------------
	W = [
		{tasks: ['compass', 'test:compass'], files: [SRC.css, COMMON.css]},
		{tasks: ['js:generic', 'test:js:generic'], files: SRC.js.generic},
		{tasks: ['js:pages', 'test:js:pages'], files: SRC.js.pages},
		{tasks: ['svgstore'], files: SRC.svgstore}
	];
}
initConfig();

gulp.task( 'default', function () {
	// Default task code
});

gulp.task( 'compass', function () {
	return gulp.src( SRC.css )
		.pipe( plumber({errorHandler: err}) )
		.pipe( compass( SASS ) )
		.pipe( postcss( [
			autoprefixer( APX_CONF )
		] ) )
		.pipe( gulp.dest( DEST.css ) );
});

// $ gulp test:css
// ----------------------------------------------------------------------------
// Lint les fichiers source pour les CSS
gulp.task( 'test:compass', function () {
	return gulp.src( source + 'scss/**/*.scss' )
		.pipe( plumber({errorHandler: err}) )
		.pipe( scsslint( LINT ) );
});

//JS
gulp.task( 'js', function () {
	runner( 'js:generic', 'js:pages' );
});

gulp.task( 'js:generic', function () {
	var buildStream = through();

	buildStream
		.pipe( vinylSource( 'planmylife.js' ) )
		.pipe( buffer() )
		.pipe( sourcemap.init({loadMaps: true}) )
		.pipe( gif( ENV.all.optimize, uglify() ) )
		.pipe( sourcemap.write( '.' ) )
		.pipe( gulp.dest( DEST.js ) );

	glob( SRC.js.generic, {}, function ( err, files ) {
		if ( err ) {
			buildStream.emit( 'error', err );
			return;
		}

		var b = browserify({
			entries: files,
			debug: true/*,
			 transform: [babelify]*/
		});

		b.transform( babelify, {
			presets: ['es2015']
		}).bundle()
			.pipe( buildStream );
	});

	return buildStream;
});

gulp.task( 'js:pages', function () {
	return gulp.src( SRC.js.pages )
		.pipe( babel({presets: ['es2015']}) )
		.pipe( gulp.dest( DEST.js + '/pages' ) );
});

gulp.task( 'iconfont', function () {
	return gulp.src( [SRC.svg] )
		.pipe( iconfont({
			fontName: 'kedgeIcon', // required
			prependUnicode: true, // recommended option
			formats: ['ttf', 'svg', 'woff', 'woff2'], // default, 'woff2' and 'svg' are available
			timestamp: runTimestamp // recommended to get consistent builds when watching files
		}) )
		.on( 'glyphs', function ( glyphs, options ) {
			// CSS templating, e.g.
			console.log( glyphs, options );
		})
		.pipe( gulp.dest( DEST.svg ) );
});

gulp.task('svgstore', function(){
	return gulp.src(SRC.svgStore)
			.pipe(svgmin(function (file) {
	            var prefix = path.basename(file.relative, path.extname(file.relative));
	            return {
	                plugins: [{
	                    cleanupIDs: {
	                        prefix: prefix + '-',
	                        minify: true
	                    }
	                }]
	            }
	        }))
	        .pipe(svgstore())
	        .pipe(rename(function(path) {
	        	path.basename = name;
	        }))
	        .pipe(gulp.dest(DEST.svgStore));
});

// $ gulp test:js
// ----------------------------------------------------------------------------
// Lint les fichiers source pour les CSS
gulp.task( 'test:js', function () {
	runner( 'test:js:generic', 'test:js:pages' );
});

gulp.task( 'test:js:generic', function () {
	return gulp.src( SRC.js.generic )
		.pipe( plumber({errorHandler: err}) )

		// En mode relax, on ignore les tests (c'est mal)
		.pipe( gif( !ENV.all.relax, js() ) );
});

gulp.task( 'test:js:pages', function () {
	return gulp.src( SRC.js.pages )
		.pipe( plumber({errorHandler: err}) )

		// En mode relax, on ignore les tests (c'est mal)
		.pipe( gif( !ENV.all.relax, js() ) );
});

//REMOVE GENERATED FILES
// ----------------------------------------------------------------------------
gulp.task( 'clean', function () {
	return del( [DEST.css, DEST.js, DEST.svg, DEST.svgStore], {force: true} );
});

gulp.task( 'minify', function () {
	return runner( 'minify:css'/*, 'minify:js'*/ );
});

gulp.task( 'minify:css', function () {
	return gulp.src( DEST.css + '/**/*.css' )
		.pipe( cleanCSS( {compatibility: 'ie8'} ) )
		.pipe( gulp.dest( DEST.css ) );
});

gulp.task( 'minify:js', function () {
	return gulp.src( DEST.js + '/**/*.js' )
		.pipe( minifier() )
		.pipe( gulp.dest( DEST.js ) );
});

// $ gulp compile
// ----------------------------------------------------------------------------
// Génère les fichiers pour le projet pour la prod
function buildTask(path, build) {
	console.log('Run build on : ' + path);

	source = path;

	if(argv.name !== 'undefined') {
		name = argv.name;
	}

	initConfig();

	if (build) {
		runner( ['compass', 'js', 'iconfont', 'svgstore'], 'minify' );
	} else {
		runner( ['compass', 'js', 'iconfont', 'svgstore'] );
	}
}

gulp.task( 'watch', function () {
	if(argv.path !== 'undefined') {
		runner( 'clean', function() {
			if( typeof argv.path === 'object') {
				argv.path.forEach( function(path){
					buildTask(path, false);
				});
			} else {
				buildTask(argv.path, false);
			}

			W.forEach( function ( obj ) {
				gulp.watch( obj.files, obj.tasks );
			});
		} );
	}
});

gulp.task( 'build', function () {
	if(argv.path !== 'undefined') {
		if( typeof argv.path === 'object') {
			argv.path.forEach( function(path){
				buildTask(path, true);
			});
		} else {
			buildTask(argv.path, true);
		}
	}
});
