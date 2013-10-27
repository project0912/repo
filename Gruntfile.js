module.exports = function(grunt) {
    "use strict";
    grunt.initConfig({
        jsdoc: {
            dist: {
                src: ['js/controllers/*.js', 'js/routes/*.js', 'js/utils/*.js', 'js/*.js'],
                options: {
                    destination: 'doc'
                }
            }
        },
        emberTemplates: {
            compile: {
                options: {
                    templateName: function(sourceFile) {
                        sourceFile = sourceFile.replace('.hbs', '');
                        var aux = sourceFile.split('/');
                        sourceFile = aux[aux.length - 1].replace('.', '/');

                        return sourceFile;
                    }
                },
                files: {
                    "js/templates/precompiled/templates.js": ['js/templates/*/*.hbs', 'js/templates/*.hbs']
                }
            }
        },
        concat: {
            options: {
                banner: '/*Precompiled on:<%= grunt.template.today("dddd, mmmm dS, yyyy, h:MM:ss TT") %> */\n' +
                        "define(['ember','handlebars'],function(){\n",
                footer: "\n});"
            },
            dist: {
                src: ["js/templates/precompiled/templates.js"],
                dest: "js/templates/precompiled/templatesModule.js"
            }
        },
        watch: {
            precompile: {
                options: {
                    livereload: true
                },
                files: ['js/templates/**/*.hbs'],
                tasks: ['precompile']
            }
        },
        linter: {// configure the task
            files: [// some example files
                'grunt.js',
                'js/**/*.js'
            ],
            exclude: [
                'js/libs/*',
                'js/templates/**/*.js',
                'js/dunkey.py'
            ],
            directives: {// example directives
                browser: true
            },
            globals: {
                jQuery: true,
                google: true
            },
            options: {
                junit: 'out/junit.xml', // write the output to a JUnit XML
                log: 'out/lint.log',
                errorsOnly: true // only display errors
            }
        },
        requirejs: {
            compile: {
                // !! You can drop your app.build.js config wholesale into 'options'
                options: {
                    appDir: "js/",
                    baseUrl: "./",
                    dir: "target/",
                    optimize: 'uglify',
                    modules: [
                        {
                            name: 'main'
                        }
                    ],
                    logLevel: 1,
                    findNestedDependencies: true,
                    fileExclusionRegExp: /^\./,
                    inlineText: true,
                    paths: {
                        App: 'app',
                        jquery: 'libs/jquery.min',
                        jqueryUI: 'libs/jquery.ui.min',
                        handlebars: 'libs/handlebars',
                        ember: 'libs/ember',
                        text: 'libs/text',
                        utils: 'utils/',
                        tinyPubSub: "libs/pubsub",
                        tagit: "libs/tagit",
                        dropzone: "libs/dropzone"
                    },
                    shim: {
                        'ember': {
                            deps: [
                                'handlebars',
                                'jquery'
                            ],
                            exports: 'Ember'
                        },
                        'tinyPubSub': {
                            deps: ['jquery']
                        },
                        'jqueryUI': {
                            deps: ['jquery']
                        },
                        'tagit': {
                            deps: [
                                'jquery',
                                'jqueryUI'
                            ]
                        }
                    }
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-ember-templates');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-jsdoc');
    grunt.loadNpmTasks('grunt-linter');
    grunt.loadNpmTasks('grunt-contrib-requirejs');

    grunt.registerTask('compile', ['emberTemplates:compile']);

    grunt.registerTask('makeModule', 'My "precompile" task.', function() {
        grunt.task.requires('compile'); // make sure bar was run and did not fail
        grunt.task.run('concat');
    });

    grunt.registerTask('precompile', 'My "precompile" sequence.', ['compile', 'makeModule']);

    grunt.registerTask('createDoc', 'jsdoc');

    grunt.registerTask('lint', 'linter');

    grunt.registerTask('production', 'requirejs');
};