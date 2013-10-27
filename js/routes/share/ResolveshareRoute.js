define(['ember', 'utils/utils'],
        function(Ember, Utils) {
            "use strict";

            var ResolveshareRoute = Ember.Route.extend({
            	model: function(params) {    
					    return Ember.Object.create({
					    	path: params.linkPath
					    });
				},
				afterModel: function(params, transition){
					//do the logic
					console.log('transition to path '+params.path);
					var type, len, payload, id,
						broken = params.path.split('/');

					//TODO Utils.validateTheLink(short);
					len = broken.length;
					type = Number(broken[len-1]);

					id = broken[len-2];

					switch(type){
						case 1: //crisis
								this.transitionTo('crisis',App.Crisis.find(id));
								break;
						case 2: //claim
								this.transitionTo('claim',App.Claim.find(id));
								break;
						case 3: //evidence
								this.transitionTo('evidence',App.Evidence.find(id));
								break;
						default:
							//log to server malicious attempt

					}

					//do the callback

				}
            });

        return ResolveshareRoute;
});