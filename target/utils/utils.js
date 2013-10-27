define(["ember","utils/ajax","utils/ErrorInfo","utils/asserts","utils/constants","utils/validator","jquery"],function(e,t,n,r,i,s,o){var u={ajax:t,ERRORS:n,assert:r,CONSTANTS:i,validator:s,setModelProperties:function(t,n){r(!e.isNone(t),"Model is undefined"),r(!e.isNone(n),"jsonPayload is undefined");var i,s=n.stats,o=n.location,u=n.support,a=n.comments;s&&(t.setProperties(s),delete n.stats),o&&(t.setProperties(o),delete n.location),a&&(t.setProperties({commentsArray:a}),delete n.comments),typeof u=="number"&&(r(u===1||u===0,"wrong support property"),u?t.setProperties({support:!0}):t.setProperties({support:!1}),delete n.support),t.setProperties(n)},validatePayload:function(e){var t,r,i={login:s.validEmail,password:s.validPassword,title:s.validTitle,descr:s.validDescription,ID:s.validMongoID,crisisID:s.validMongoID,claimID:s.validMongoID,evidenceID:s.validMongoID,lat:s.validLat,lng:s.validLng,street:s.validStreet,cityID:s.validCity,countryID:s.validCountry,support:s.validSupport,markers:s.validMarkers,type:s.validType,tags:s.validTags,images:s.validImage};for(r in e){t=i[r];if(!t(e[r]))return{valid:!1,message:n.get(r)}}return{valid:!0,message:""}},timeAgo:function(t){r(!e.isNone(t),"Time is undefined"),t*=1e3;switch(typeof t){case"number":break;case"string":t=+(new Date(t));break;case"object":t.constructor===Date&&(t=t.getTime());break;default:t=+(new Date)}var n,i,s=[[60,"seconds",1],[120,"1 minute ago","1 minute from now"],[3600,"minutes",60],[7200,"1 hour ago","1 hour from now"],[86400,"hours",3600],[172800,"Yesterday","Tomorrow"],[604800,"days",86400],[1209600,"Last week","Next week"],[2419200,"weeks",604800],[4838400,"Last month","Next month"],[29030400,"months",2419200],[58060800,"Last year","Next year"],[290304e4,"years",29030400],[580608e4,"Last century","Next century"],[580608e5,"centuries",290304e4]],o=(+(new Date)-t)/1e3,u="ago",a=1;if(o===0)return"Just now";o<0&&(o=Math.abs(o),u="from now",a=2),n=0,i=s[n];while(i){i=s[n],n+=1;if(o<i[0])return typeof i[2]=="string"?i[a]:Math.floor(o/i[2])+" "+i[1]+" "+u}return t},formatTime:function(e){var t,n,r,i,s;return e*=1e3,t=new Date(e),n=t.getUTCFullYear(),r=t.getUTCMonth(),i=t.getUTCDate(),s=t.toLocaleTimeString(),n+"-"+r+"-"+i+" "+s},broadcastMinuteUpdates:function(){var e,t;t=function(){o.publish("minute")},e=setInterval(t,i.get("minute"))},getCityName:function(t,n){r(!e.isNone(t),"CityID is undefined"),r(!e.isNone(n),"CitiesArray is undefined");var i=0,s=n.length;for(;i<s;i++)if(n[i].id===t)return n[i].city;return""}};return u});