define(["ember","utils/utils","jquery"],function(e,t,n){var r=e.Mixin.create({bookmarkItem:function(){var e,n,r,i;i=this.itemType(),r=i.element,e=i.instanceType,n={type:"statisticsBookmark",instanceType:e,elementID:r.get("id"),undo:r.get("castedBookmark")},t.ajax.makeAJAXPostCall("router.php",n,function(e){if(!e.error){var t=r.get("bookmarksCount"),n=r.get("castedBookmark");n=(n+1)%2,n?t+=1:t-=1,r.set("castedBookmark",n),r.set("bookmarksCount",t)}})},upVoteItem:function(){var e,n,r,i;r=this.itemType(),e=r.instanceType,n=r.element;if(n.get("castedDisagree")===1){alert("You can't agree and disagree at the same time");return}i={type:"statisticsAgree",instanceType:e,elementID:n.get("id"),undo:n.get("castedAgree")},t.ajax.makeAJAXPostCall("router.php",i,function(e){if(!e.error){var t=n.get("agreesCount"),r=n.get("castedAgree");r=(r+1)%2,r?t+=1:t-=1,n.set("castedAgree",r),n.set("agreesCount",t)}})},downVoteItem:function(){var e,n,r,i;r=this.itemType(),e=r.instanceType,n=r.element;if(n.get("castedAgree")===1){alert("You can't agree and disagree at the same time");return}i={type:"statisticsDisagree",instanceType:e,elementID:n.get("id"),undo:n.get("castedDisagree")},t.ajax.makeAJAXPostCall("router.php",i,function(e){if(!e.error){var t=n.get("disagreesCount"),r=n.get("castedDisagree");r=(r+1)%2,r?t+=1:t-=1,n.set("castedDisagree",r),n.set("disagreesCount",t)}})},setSeverity:function(e){var t,n;n=this.itemType(),t=n.element,t.set("castedSeverity",1),t.set("castedSeverityVote",e)},itemType:function(){var e,t;return this.needs.contains("evidence")?(e="evidence",t=this.get("controllers.evidence")):this.needs.contains("claim")?(t=this.get("controllers.claim"),e="claim"):this.needs.contains("crisis")&&(e="crisis",t=this.get("controllers.crisis")),{instanceType:e,element:t}}});return r});