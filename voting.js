$(document).ready(function() {


	//####### on page load, retrive votes for each content
	$.each( $('.voting_wrapper'), function(){

		//retrive unique id from this voting_wrapper element
		var unique_id = $(this).attr("id");

		//retrive vote type
		if( $(this).hasClass("main_post") ) {
			var vote_type="post";
		}
		else{
			var vote_type="comment";
		}

		//prepare post content
		post_data = {'unique_id':unique_id, 'vote':'fetch', 'vote_type':vote_type};
		
		//send our data to "vote_process.php" using jQuery $.post()
		$.post('vote_process.php', post_data,  function(response) {
		
				//retrive votes from server, replace each vote count text
				$('#'+unique_id+' .vote_score').text(response.current_vote);
			},'json');
	});

		
	
	//####### on button click, get user vote and send it to vote_process.php using jQuery $.post().
	$(".voting_wrapper .voting_btn").click(function (e) {



		//get class name (down_button / up_button) of clicked element
		var clicked_button = $(this).children().attr('class');
		//get unique ID from voted parent element
		var unique_id 	= $(this).parent().attr("id"); 


		if ($(this).parent().hasClass("main_post"))
		{
			var vote_type="post";
	    }
	    else{
			var vote_type="comment";
		}

	if (Cookies.get('voted_'+unique_id)!=1) //create cookie and send ajax 
	{

		Cookies.set('voted_'+unique_id, '1', { expires: 3 }); //3 days
			if(clicked_button==='down_button') //user disliked the content
		{
	
			//prepare post content
			post_data = {'unique_id':unique_id, 'vote':'down', 'vote_type':vote_type, };
			
			//change color on vote
			$('#' + unique_id + " .down_button").css("background", "url(images/votes.png) no-repeat 0px -16px");	

			//send our data to "vote_process.php" using jQuery $.post()
			$.post('vote_process.php', post_data, function(data) {
				
				//replace vote down count text with new values
				$('#'+unique_id+' .down_votes').text(data);
				afterVote();
				//thank user for the dislike
				//alert("Thanks! Each Vote Counts, Even Dislikes!");
			}).fail(function(err) { 

				//change color to default
			$('#' + unique_id + " .down_button").css({"background": "url(images/votes.png) no-repeat","float": "left","height": "14px","width": "16px","cursor":"pointer"});
			//alert user about the HTTP server error
			//alert(err.statusText); 

			});
		}
		else if(clicked_button==='up_button') //user liked the content
		{
			//prepare post content
			post_data = {'unique_id':unique_id, 'vote':'up', 'vote_type':vote_type};

			//change color on vote
			$('#' + unique_id + " .up_button").css("background", "url(images/votes.png) no-repeat -16px -16px");

			//send our data to "vote_process.php" using jQuery $.post()
			$.post('vote_process.php', post_data, function(data) {

				//replace vote up count text with new values
				$('#'+unique_id+' .up_votes').text(data);
				afterVote();
				//thank user for liking the content
				//alert("Thanks! For Liking This Content.");
			}).fail(function(err) { 

			//change color to default
			$('#' + unique_id + " .up_button").css({"background": "url(images/votes.png) no-repeat -16px 0px","float": "left","height":"14px","width":"16px","cursor":"pointer"});
			//alert user about the HTTP server error
			//alert(err.statusText); 
			});


		}
	}


	
	});
	//end 

});

function afterVote() { //function that updates vote score after voting is done
		$.each( $('.voting_wrapper'), function(){
		
		//retrive unique id from this voting_wrapper element
		var unique_id = $(this).attr("id");
		if( $(this).hasClass("main_post") ) {
			var vote_type="post";
		}
		else{
			var vote_type="comment";
		}
		//prepare post content
		post_data = {'unique_id':unique_id, 'vote':'fetch', 'vote_type':vote_type};
		
		//send our data to "vote_process.php" using jQuery $.post()
		$.post('vote_process.php', post_data,  function(response) {
		
				//retrive votes from server, replace each vote count text
				$('#'+unique_id+' .vote_score').text(response.current_vote);
			},'json');
	});
};

(function (factory) {
	var registeredInModuleLoader = false;
	if (typeof define === 'function' && define.amd) {
		define(factory);
		registeredInModuleLoader = true;
	}
	if (typeof exports === 'object') {
		module.exports = factory();
		registeredInModuleLoader = true;
	}
	if (!registeredInModuleLoader) {
		var OldCookies = window.Cookies;
		var api = window.Cookies = factory();
		api.noConflict = function () {
			window.Cookies = OldCookies;
			return api;
		};
	}
}(function () {
	function extend () {
		var i = 0;
		var result = {};
		for (; i < arguments.length; i++) {
			var attributes = arguments[ i ];
			for (var key in attributes) {
				result[key] = attributes[key];
			}
		}
		return result;
	}

	function init (converter) {
		function api (key, value, attributes) {
			var result;
			if (typeof document === 'undefined') {
				return;
			}

			// Write

			if (arguments.length > 1) {
				attributes = extend({
					path: '/'
				}, api.defaults, attributes);

				if (typeof attributes.expires === 'number') {
					var expires = new Date();
					expires.setMilliseconds(expires.getMilliseconds() + attributes.expires * 864e+5);
					attributes.expires = expires;
				}

				// We're using "expires" because "max-age" is not supported by IE
				attributes.expires = attributes.expires ? attributes.expires.toUTCString() : '';

				try {
					result = JSON.stringify(value);
					if (/^[\{\[]/.test(result)) {
						value = result;
					}
				} catch (e) {}

				if (!converter.write) {
					value = encodeURIComponent(String(value))
						.replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g, decodeURIComponent);
				} else {
					value = converter.write(value, key);
				}

				key = encodeURIComponent(String(key));
				key = key.replace(/%(23|24|26|2B|5E|60|7C)/g, decodeURIComponent);
				key = key.replace(/[\(\)]/g, escape);

				var stringifiedAttributes = '';

				for (var attributeName in attributes) {
					if (!attributes[attributeName]) {
						continue;
					}
					stringifiedAttributes += '; ' + attributeName;
					if (attributes[attributeName] === true) {
						continue;
					}
					stringifiedAttributes += '=' + attributes[attributeName];
				}
				return (document.cookie = key + '=' + value + stringifiedAttributes);
			}

			// Read

			if (!key) {
				result = {};
			}

			// To prevent the for loop in the first place assign an empty array
			// in case there are no cookies at all. Also prevents odd result when
			// calling "get()"
			var cookies = document.cookie ? document.cookie.split('; ') : [];
			var rdecode = /(%[0-9A-Z]{2})+/g;
			var i = 0;

			for (; i < cookies.length; i++) {
				var parts = cookies[i].split('=');
				var cookie = parts.slice(1).join('=');

				if (cookie.charAt(0) === '"') {
					cookie = cookie.slice(1, -1);
				}

				try {
					var name = parts[0].replace(rdecode, decodeURIComponent);
					cookie = converter.read ?
						converter.read(cookie, name) : converter(cookie, name) ||
						cookie.replace(rdecode, decodeURIComponent);

					if (this.json) {
						try {
							cookie = JSON.parse(cookie);
						} catch (e) {}
					}

					if (key === name) {
						result = cookie;
						break;
					}

					if (!key) {
						result[name] = cookie;
					}
				} catch (e) {}
			}

			return result;
		}

		api.set = api;
		api.get = function (key) {
			return api.call(api, key);
		};
		api.getJSON = function () {
			return api.apply({
				json: true
			}, [].slice.call(arguments));
		};
		api.defaults = {};

		api.remove = function (key, attributes) {
			api(key, '', extend(attributes, {
				expires: -1
			}));
		};

		api.withConverter = init;

		return api;
	}

	return init(function () {});
}));

