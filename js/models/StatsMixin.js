/**
 * The StatsMixin is a container for the common statistics and methods that 
 * Crisis, Claim and Evidence objects have.
 * 
 * @class StatsMixin
 */

define(['ember', 'utils/utils','jquery'],
        function(Ember, Utils,$) {
            "use strict";

            //Mixins are not defined on the App object because there is no need to do so
            //it is better to encapsulate this information.

            var StatsMixin = Ember.Mixin.create({
                agreesCount: '',
                bookmarksCount: '',
                claimsCount: '',
                commentsCount: '',
                creationTime: '',
                disagreesCount: '',
                evidencesCount: '',
                lastActive: '',
                lastEdit: '',
                severityIndex: '',
                viewsCount: '',
                castedSeverity: '',
                castedSeverityVote: '',
                numSeverity: '',
                numAgrees: Ember.computed.alias('agreesCount'),
                numBookmarks: Ember.computed.alias('bookmarksCount'),
                numClaims: Ember.computed.alias('claimsCount'),
                numComments: Ember.computed.alias('commentsCount'),
                numDisagrees: Ember.computed.alias('disagreesCount'),
                numEvidences: Ember.computed.alias('evidencesCount'),
                numViews: Ember.computed.alias('viewsCount'),
                sumSeverity: Ember.computed.alias('severityIndex'),
                created: function() {
                    var that = this;
                    $.subscribe('minute', function() { //set up subscription to 60s updates

                        var timeAgo = Utils.timeAgo(that.get('creationTime'));
                        that.set('created', timeAgo);

                    });

                    return Utils.timeAgo(this.get('creationTime'));
                }.property('creationTime'),
                active: function() {

                    var that = this;
                    $.subscribe('minute', function() { //set up subscription to 60s updates

                        var timeAgo = Utils.timeAgo(that.get('lastActive'));
                        that.set('active', timeAgo);

                    });

                    return Utils.timeAgo(this.get('lastActive'));
                }.property('lastActive'),
                edit: function(){
                    var that = this;
                    $.subscribe('minute', function() { //set up subscription to 60s updates

                        var timeAgo = Utils.timeAgo(that.get('lastEdit'));
                        that.set('edit', timeAgo);

                    });

                    return Utils.timeAgo(this.get('lastEdit'));
                }.property('lastEdit'),
                avgSeverity: function(){
                    if (!this.get('numSeverity')){
                        return 0;
                    }
                    return (this.get('severityIndex')/this.get('numSeverity')).toFixed(2);
                }.property('severityIndex','numSeverity'),
                //I know... this looks ugly and should be in the controller... but e.g. on claim page how do you display the titles for evidences? Right...
                agreesText: function(){
                    return this.get('agreesCount') + ' people agreed';
                }.property('agreesCount'),
                disagreesText: function(){
                    return this.get('disagreesCount') + ' people disagreed';
                }.property('disagreesCount'),
                bookmarksText: function(){
                    return this.get('bookmarksCount') + " people think it's important";
                }.property('bookmarksCount'),
                claimsText: function(){
                    return this.get('claimsCount') + " claims";
                }.property('claimsCount'),
                evidencesText: function(){
                    return this.get('evidencesCount') + " evidences were supplied";
                }.property('evidencesCount'),
                viewsText: function(){
                    return this.get('viewsCount')+ " people saw this";
                }.property('viewsCount'),       
                activeText: function(){
                    var date = this.get('lastActive');

                    if (!date){
                        return "";
                    }
                    return Utils.formatTime(date) + ' [last active]';
                }.property('lastActive'),
                editedText: function(){
                    var date = this.get('lastEdit');

                    if (!date){
                        return "";
                    }
                    return Utils.formatTime(date) + ' [last edited]';
                }.property('lastEdit'),
                createdText: function(){
                    var date = this.get('creationTime');
                    if (!date){
                        return "";
                    }
                    return Utils.formatTime(date) + ' [created]';
                }.property('creationTime'),
                authorFullname: function(){
                    return this.get('author.name')+ ' ' +this.get('author.surname');
                },
                commentsText: function(){
                    return this.get('commentsCount') + ' people commented';
                }.property('commentsCount'),
                numSeverityText: function(){
                    return this.get('numSeverity') + ' people assessed the severity level';
                }.property('numSeverity'),
                avgSeverityText: function(){
                    return this.get('avgSeverity')+ ' average severity';
                }.property('avgSeverity'),
                //TODO remove this when image support is added
                randomImage: function(){
                    var rand = Math.floor(Math.random()*5);
                    return 'img/images/img'+rand+'.jpg';
                }.property('randomImage')

            });

            return StatsMixin;
        });