define(['QUnit','utils/utils','tests/stringGenerator'],function(qunit,Utils,Generator) {
		"use strict";
		var title,
			description,
			coutryId,
			cityId,
			latlng,
			markers,
			javaScriptTypes,
			mixedString,
			mongoId,
			mongoIdObjects,
			randomNumber,
			supportDynamic,
			supportObjects,
			testInRange,
			validCharString,
			zoomLevel,
			run;//return value

			javaScriptTypes = [
				[new Object(), 'new Object()'],
				[NaN,'NaN'],
				[true,'boolean (true)'],
				[false,'boolean (false)'],
				[[new Array()],'new Array()'],
				[null,'null']
			];

			/**
			 * returns a random length string verifying if from the length point of view satisfies the conditions for the title
			 * @return {[type]} [description]
			 */
			validCharString = function(floor,ceil,max){
				var valid,text;

				text = Generator.randomValidCharString(max);

				valid = text.length<=ceil && text.length>=floor;

				//check if ends with space
				valid = valid && text[text.length-1]!==' ';

				return [text,valid];
			};

			mixedString = function(floor,ceil, max){
				var valid,text,validCharacters,i;
				//regular expressions are not used in order to identify possible errors
				validCharacters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789 ';

				text = Generator.randomMixedString(max);

				//check length
				valid = text.length<=ceil && text.length>=floor;

				//check if ends with space
				valid = valid && text[text.length-1]!==' ';
				
				for (i=0;i<text.length;i = i + 1){
					if (validCharacters.indexOf(text.charAt(i)) === -1){
						valid = false;
						break;
					}
				}

				return [text,valid];
			};

			randomNumber = function(range){
				var sign,aux,value;
				
				//generate randomly the sign of the number
				aux = Math.random()*10;

				if (aux >= 5 ){
					sign = -1;
				}else{
					sign = 1;
				}

				value = sign*Math.random()*range;

				return value;
			};

			title = function(nr_tests){
				var i,minLength,maxLength,validTitle,aux,text,validator;

				validator = Utils.validator;
				minLength = 10;
				maxLength = 60;
				for (i = 0;i<nr_tests;i = i + 1){
					aux = validCharString(minLength,maxLength,120);
					text = aux[0];
					validTitle = aux[1];
					test('Title',function(){
						if (validTitle){
							ok(validator.validTitle(text),'Valid: '+text);
						}else{
							ok(!validator.validTitle(text),'Invalid: '+text);
						}
					});
				}
			};

			description = function(nr_tests){
				var i,minLength,maxLength,validDescription,aux,text,validator;

				validator = Utils.validator;
				minLength = 30;
				maxLength = 300;
				for (i = 0;i<nr_tests;i = i + 1){
					aux = validCharString(minLength,maxLength,400);
					text = aux[0];
					validDescription = aux[1];
					test('Description',function(){
						if (validDescription){
							ok(validator.validDescription(text),'Valid: '+text);
						}else{
							ok(!validator.validDescription(text),'Invalid: '+text);
						}
					});
				}
			};

			testInRange = function(nr_tests,fct,desc,floor,ceil){
				var i,aux;

				for (i=0;i<nr_tests;i = i + 1){
					//generate with 20% string, with 70% number
					test(desc,function(){
						if ( (Math.random()*100) < 15){
							aux = Generator.randomMixedString(20);
							ok(!fct(aux[0]),'String '+aux[0]);
						}else{
							aux = randomNumber(Number.MAX_VALUE);
								if (aux > ceil || aux < floor){
									ok(!fct(aux),'Invalid :'+aux);
								}else{
									ok(fct(aux),'Valid :'+aux);
								}
						}
					});
				}
			};

			zoomLevel = function(nr_tests){

				testInRange(nr_tests,Utils.validator.validZoom,'Zoomlevel',1,20);
				
			};

			supportDynamic = function(nr_tests){
				var i,aux,action,validator;

				validator = Utils.validator;

				for (i = 0;i<nr_tests; i = i + 1){
					action = Math.random()*100;
					test('Support dynamic',function(){
						if ( action < 15){//15% prob do this
							aux = Generator.randomMixedString(5);
							ok(!validator.validSupport(aux[0],'String '+aux[0]));
						}else{//85% prob do this
							aux = randomNumber(Number.MAX_VALUE);
							if (aux === 1 || aux === 0){
								ok(validator.validSupport(aux),'Valid '+aux);
							}else{
								ok(!validator.validSupport(aux),'Invalid '+aux);
							}
						}
					});
				}
			};

			supportObjects = function(){
				test('Support Javascript types',function(){
					ok(!Utils.validator.validSupport.apply(javaScriptTypes));
				});
			};

			mongoId = function(nr_tests){
				var i,text,valid,validator,aux;

				validator = Utils.validator;

				for (i=0;i<nr_tests;i = i+1){
					aux = validCharString(50);
					text = aux[0];
					valid = aux[1] && (text.indexOf(' ') === -1) && (text.length === 24);
					test('MongoID',function(){
						if (valid){
							ok(validator.validMongoID(text),'Valid ');
						}else{
							ok(!validator.validMongoID(text),'Invalid ');
						}
					});
				}
			};

			mongoIdObjects = function(){
				test('MongoID javaScript types',function(){
					ok(!Utils.validator.validMongoID.apply(javaScriptTypes));
				});
			};

			cityId = function(nr_tests){
				testInRange(nr_tests,Utils.validator.validCity,'City ID',1,36254);
			};

			coutryId = function(nr_tests){
				testInRange(nr_tests,Utils.validator.validCountry,'Coutry ID',1,254);
			};
			


        run = function() {
        	module('Crisis');
        	// title(50);
        	// description(50);
        	// zoomLevel(50);
        	// supportDynamic(50);
        	// supportObjects();
        	// mongoId(20);
        	// mongoIdObjects();
        	cityId(20);
        	coutryId(20);
        };
        return {run: run};
    }
);