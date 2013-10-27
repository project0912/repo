define(function(Utils){
	"use strict";

	var Generator;
	Generator = {


		/**
		 * Generates a random length string from the characters of the English alphabet and numbers and space.
		 * @param  {[int]} maxLen [maximum length of the string to be returned]
		 * @return {[string]}        [random length string of valid characters]
		 */
		randomValidCharString : function(maxLen){
			var randomLength,possible,text,i,valid;

			randomLength=Math.floor(Math.random()*maxLen);
			possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789 ';
			text = '';
			
			for( i=0; i < randomLength; i=i+1 ){
	    		text += possible.charAt(Math.floor(Math.random() * possible.length));
	    	}
	    	return text;
		},
		/**
		 * Generate random stirng from the Basic Multilingual Plane range
		 * @param  {[int]} maxLen [maximum length of the string to be returned]
		 * @return {[string]}        [random length string]
		 */
		randomMixedString : function(maxLen){
			var randomLength,i,possible,randomChar,valid,text;

			randomLength = Math.floor(Math.random()*maxLen);
			text = '';

			for (i=0;i<randomLength;i = i + 1){
				
				randomChar = String.fromCharCode(Math.random()*0xFFFF);//Basic Multilingual Plane range

				text += randomChar;
			}
			return text;
		}
	};

	return Generator;
});