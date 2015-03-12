/* jshint node:true */
module.exports = function(grunt) {
    'use strict';

    grunt.initConfig({
        // setting folder templates
        dirs: {
            sass: 'app/assets/scss',
            css: 'dist/css',
            js: 'dist/js'
        },
        sass: {
            dist: {
                files: [{
                    expand: true,
                    cwd: '<%= dirs.sass %>/',
                    src: [
                        '*.scss',
                        '!mixins.scss'
                    ],
                    dest: '<%= dirs.css %>/',
                    ext: '.css'
                }]
            }
        },
        // Watch changes for assets
        watch: {
            sass: {
                files: [
                    '<%= dirs.sass %>/*.scss',
                ],
                tasks: ['sass'],
            }
        }
    });

    // Load NPM tasks to be used here
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');

    // Register tasks
    grunt.registerTask('default', [
        'sass'
    ]);

};
