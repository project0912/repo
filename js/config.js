define({
    app_name: "App", //Rename to Verily?
    shim: {
        ember: {
            deps: [
                'handlebars',
                'jquery'
            ],
            exports: 'Ember'
        },
        tinyPubSub: {
            deps: ['jquery']
        },
        jqueryUI: {
            deps: ['jquery']
        },
        tagit: {
            deps: [
                'jquery',
                'jqueryUI'
            ]
        }
    },
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
    }
});
