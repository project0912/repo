/**
 * 
 * The main entrypoint to the applicaiton. Here gets everything assembled
 * @name Main
 * @author Abdul
 * @constructor
 */
(function(root) {
    "use strict";
    require(["config"], function(config) {

        require.config(config);

        require(["templates/precompiled/templatesModule"]);

        require(["App", "ember", "utils/utils"],
                function(App, Ember, Utils) {

                    var AppName = config.app_name || "App";
                    root[AppName] = App = Ember.Application.create(App);
                    App.Utils = Utils;

                    App.Crisis.reopenClass({
                        find: function(crisis_id) {
                            App.Utils.assert(!Ember.isNone(crisis_id), "Crisis_id is not defined");

                            var crisis = App.Crisis.create({
                                crisisID: crisis_id
                            }),
                            payload = {
                                type: 'crisisGetInfo',
                                crisisID: crisis_id
                            },
                            callback = function(data) {
                                if (data.error) {
                                    Utils.assert(false, 'callback Crisis failed.');
                                } else {
                                    Utils.setModelProperties(crisis, data.element);
                                    crisis.loadClaims();
                                }
                            };

                            Utils.ajax.makeAJAXPostCall('router.php', payload, callback);

                            return crisis;
                        }
                    });

                    App.Claim.reopenClass({
                        find: function(claim_id) {

                            App.Utils.assert(!Ember.isNone(claim_id), "Claim_id is not defined");

                            var claim = App.Claim.create({
                                claimID: claim_id
                            }),
                            payload = {
                                type: 'claimGetInfo',
                                claimID: claim_id
                            },
                            callback = function(data) {
                                if (data.error) {
                                    Utils.assert(false, 'callbackInfo failed');
                                } else {
                                    Utils.setModelProperties(claim, data.element);
                                    claim.loadEvidences();
                                }
                            };

                            Utils.ajax.makeAJAXPostCall('router.php', payload, callback);

                            return claim;
                        }
                    });

                    App.Evidence.reopenClass({
                        find: function(evidence_id) {

                            App.Utils.assert(!Ember.isNone(evidence_id), "Evidence_id is not defined");

                            var evidence = App.Evidence.create({
                                evidenceID: evidence_id
                            }),
                            payload = {
                                type: 'evidenceGetInfo',
                                evidenceID: evidence_id
                            },
                            callback = function(data) {
                                if (data.error) {
                                    Utils.assert(false, 'evidenceGetInfo failed');
                                } else {
                                    Utils.setModelProperties(evidence, data.element);
                                    evidence.loadAuthors();
                                }
                            };

                            Utils.ajax.makeAJAXPostCall('router.php', payload, callback);

                            return evidence;
                        }
                    });

                    Utils.broadcastMinuteUpdates();

                    //Allow support for readonly HTML attribute
                    Ember.TextSupport.reopen({
                        attributeBindings: ["readonly"]
                    });

                    // Handlebars helper to generate lorem ipsum text
                    // Usage: {{lorem type=paragraph amount=4}}
                    Ember.Handlebars.registerHelper('coverImagePath', function(context, options) {
                        debugger;
//                        var opts = {
//                            ptags: true
//                        };
//                        if (options.hash.type) {
//                            opts.type = options.hash.type;
//                        }
//                        if (options.hash.amount) {
//                            opts.amount = options.hash.amount;
//                        }
                        return new Handlebars.SafeString($('<div></div>').html());
                    });

                });
    });
})(this);
