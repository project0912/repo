define(["ember","utils/store","controllers/UserActionMixin"],function(e,t,n){var r=e.ObjectController.extend(n,{needs:["crisis","claim"],tags:function(){var n,r=this.get("controllers.claim.tags"),i=e.ArrayProxy.create({content:[]});return n=function(e){e&&i.pushObjects(e)},r&&t.getTags(r,n),i}.property("controllers.claim.tags"),parentCrisisName:function(){return this.get("controllers.crisis.title")}.property("controllers.crisis.title"),backToCrisis:function(){this.transitionToRoute("crisis")},createSupportingEvidence:function(){this.set("controllers.claim.evidSupport",1),this.transitionToRoute("claim.addevidence")},createOpposingEvidence:function(){this.set("controllers.claim.evidSupport",0),this.transitionToRoute("claim.addevidence")},editClaim:function(){this.set("controllers.claim.isEdit",!0),this.transitionToRoute("claim.edit")}});return r});