// This is a default example. Edit it according to each template and remove this line.
(function() {
  'use strict';

  module.exports = function(grunt) {
    grunt.initConfig({
      pkg: grunt.file.readJSON('package.json'),
      concat: {
        options: {
          separator: ';',
          banner: '/*! <%= pkg.name %> - v<%= pkg.version %> - ' + '<%= grunt.template.today("yyyy-mm-dd") %> */'
        },
        dist: {
          files: {
            'wp-content/themes/cw/js/app.js': ['wp-content/themes/yellowcat_2013/js/jquery-2.0.3.min.js', 'wp-content/themes/cw/js/foundation.min.js', 'wp-content/themes/cw/js/plugins.js', 'wp-content/themes/cw/js/main.js']
          }
        }
      },
      uglify: {
        options: {
          banner: '/*! <%= pkg.name %> <%= grunt.template.today("dd-mm-yyyy") %> */\n',
          sourceMap: 'wp-content/themes/cw/js/source-map.js.map'
        },
        dist: {
          files: {
            'wp-content/themes/cw/js/app.min.js': ['wp-content/themes/cw/js/app.js']
          }
        }
      },
      jshint: {
        files: ['gruntfile.js'],
        // configure JSHint (documented at http://www.jshint.com/docs/)
        options: {
          globals: {
            jQuery: true,
            console: true,
            module: true
          }
        }
      },
      less: {
        development: {
          files: {
            'wp-content/themes/cw/css/style.css': 'wp-content/themes/cw/css/style.less'
          }
        },
        production: {
          options: {
            yuicompress: true
          },
          files: {
            'wp-content/themes/cw/css/style.min.css': 'wp-content/themes/cw/css/style.less'
          }
        }
      },
      watch: {
        files: ['<%= jshint.files %>', 'wp-content/themes/cw/css/style.less', 'wp-content/themes/cw/js/main.js'],
        tasks: ['jshint', 'concat', 'uglify', 'less']
      }
    });

    // Load libs
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-devtools');

    // Register the default tasks
    grunt.registerTask('default', ['jshint', 'concat', 'uglify', 'less']);
  };
}());