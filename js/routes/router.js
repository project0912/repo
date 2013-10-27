define(["ember"], function(Ember) {
    "use strict";
    var Router = Ember.Router.extend();

    Router.map(function() {
        

        this.route("index", {path: "/"});

        //Users resource group
        this.resource('user', {path: '/user'}, function() {
            this.route('login');
            this.route('register');
            this.route('profile', {path: "/profile/:profile_id"});
        });
        
        
        //Crises resource group
        this.resource('crises', {path: '/crises'}, function() {
            this.route('create');
        });

        //Crisis resource group
        this.resource('crisis', {path: '/crisis/:crisis_id'}, function() {
            this.route('makeclaim');
            this.route('edit');
            this.route('delete');
            this.route('history');

            //Nested claim resource group
            this.resource('claim', {path: 'claim/:claim_id'}, function() {
                this.route('addevidence');
                this.route('edit');
                this.route('delete');
                this.route('history');

                //Nested evidence resource group
                this.resource('evidence', {path: '/evidence/:evidence_id'}, function() {
                    this.route('comment');
                    this.route('edit');
                    this.route('delete');
                    this.route('history');
                });
            });
        });

        //Share route
        this.route('resolveshare', { path: 's/*linkPath' });
    });



    return Router;
});
