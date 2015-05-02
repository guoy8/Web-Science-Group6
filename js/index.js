$(document).foundation();
/* 
 * =========================================
 * VARIABLES
 * =========================================
 */
var instanceHash = {};  		//stores all sound instances
var mixHash = {};				//stores all mix instances

var sliders = {};				//stores the sliders 
var posIntervals = {};			//stores the position trackers
var sizes = [65, 100, 135, 170, 205];
var slidersId = [0, 1, 2, 3, 4];

var condition = "rainy";		// Weather type
var queue;						// Queue of sounds to load

/* 
 * =========================================
 * INIT
 * =========================================
 */

function init() {
	// Show error if SoundJS, HTMLAudioPlugin, and/or WebAudioPlugin are not supported
	if (!createjs.Sound.initializeDefaultPlugins()) {
		$("#error").style.display = "none";
		$("#interface").style.display = "block";
		return;
	}

	/* BUTTONS */
	$("#playAll").click(masterPlayPause);

	// Add 3 random mixes to page.
	$.ajax({
      	dataType: "JSON",
      	url: "fetchMixRand.php",
      	success: function(data) {
      		if (data[0]['name'] === null) { 
      			$("#random").remove(); 
      		else {
	      		for(var i = 0; i < data.length; i++) {
	      			if (data[i]['name'] === null) {
		      			$(".loading").remove();
		      			return;
		      		} 
	      			addRandomMix(data[i]['name'], JSON.stringify(data[i]['mixes']));
	      		}
	      	}
      	},
      	error: function (xhr, ajaxOptions, thrownError) {
	        console.log(xhr.status + ": " + thrownError);
	    }
    });

	// Add sounds
	queue = new createjs.LoadQueue();
	queue.installPlugin(createjs.Sound);
	queue.loadManifest([
	    {id: "Beach Waves", src: "sounds/beach/beach_waves_at_praia_grande.ogg"},
		{id: "Broken Top Creek", src: "sounds/brook/broken_top_creek.ogg"},
		{id: "Babbling Brook", src: "sounds/brook/babbling_brook.ogg"},
		{id: "Large Campfire", src: "sounds/campfire/large_campfire.ogg"},
		{id: "Quiet Autumn Campfire", src: "sounds/campfire/quiet_autumn_campfire.ogg"},
		{id: "Cedar Campfire", src: "sounds/campfire/cedar_campfire.ogg"},
		{id: "Wind Blowing in a Field", src: "sounds/wind/wind_blowing_in_a_field.ogg"},
		{id: "Northern Cold Wind Chimes", src: "sounds/windchimes/northern_cold_wind_chimes.ogg"},
		{id: "Five Rake Large Wind Chimes", src: "sounds/windchimes/five_rake_large_wind_chimes.ogg"},
		{id: "Nearby Wind Chimes", src: "sounds/windchimes/nearby_wind_chimes.ogg"}
	]);

	/* Weather API */
	$(document).ready(function(){
		$("#playAll").html('<i class="fa fa-circle-o-notch fa-spin"></i>');
		if (navigator.geolocation) {
			//Get the current location coordinates and change theme
	        navigator.geolocation.getCurrentPosition(showPosition, errorCallback, {timeout:2000});
	    } else {
	    	// console.log("else statement");
	    	queue.on("complete", addMainMix);
	    }
	});
	function errorCallback() {
		// console.log("Geolocation error");
		queue.on("complete", addMainMix);
	}

	// Set master volume to 50%
	// createjs.Sound.setVolume(50 / 100);
}

// Play/pause all sounds 
var pause = false;
function masterPlayPause(event) {
	if (slidersId.length === 5) { return; }
	pause = !pause;
	// console.log("Want to pause all songs: " + pause);
	if (pause) {
		$("#playAll i").removeClass("fa-pause").addClass("fa-play");
	} else {
		$("#playAll i").removeClass("fa-play").addClass("fa-pause");
	}
	for (var key in instanceHash) {
	   	if (instanceHash.hasOwnProperty(key)) {
	   		// console.log("masterplaypause: " + key);
	    	var obj = instanceHash[key];
	    	// If user wishes to pause all sounds and sound is not finished
	    	if (pause && obj.playState != createjs.Sound.PLAY_FINISHED) { obj.pause(); } 
	    	// If user wants to play all sounds
	    	else if (!pause) {
	    		if (obj.playState == createjs.Sound.PLAY_FINISHED) {
	    			obj.play({ loop: obj.loop, volume: obj.volume, pan: obj.pan });
	    			trackTime(key);
	    		} else { obj.resume(); }
	    	}
	    }
	}
}

var mixnum = 0;
var images = ["./img/rain_rainy.jpg", "./img/rain_birds.jpg", "./img/rain_stream.jpg"];
// Adds random mix to page
function addRandomMix(name, mix) {
	// Add to page
	var id = "mix" + (mixnum+1);
	var mix = JSON.parse(mix);
	var usermix = 	'<li id="' + id + '">' +
					'<img src="' + images[mixnum] + '"/>' +
					'<h4><button class="small button"><i class="fa fa-fw fa-play"></i></button>' + name + '</h4></li>';
	$("#random").append(usermix);
	mixnum++;

	$("#" + id).on('click', function() {
		if (!mixHash[id]) {
			mixHash[id] = [];
			for (var i=0; i<mix.length; i++) {
				var name = mix[i]['name'];
				var volume = mix[i]['volume'];
				var pan = mix[i]['pan'];
				var loop = mix[i]['loop'];
				var instance = createjs.Sound.createInstance(name);
				instance.play({loop: loop, volume: volume, pan: pan});
				(mixHash[id]).push(instance);
			}
			$("#" + id + " button").html('<i class="fa fa-fw fa-pause"></i></button>');
		} else {
			var pause = false;
			if (mixHash[id][0].paused) {
				pause = true;
				$("#" + id + " button").html('<i class="fa fa-fw fa-pause"></i></button>');
			} else {
				pause = false;
				$("#" + id + " button").html('<i class="fa fa-fw fa-play"></i></button>');
			}
			// console.log(mixHash[id]);
			for (var i=0; i<mixHash[id].length; i++) {
				pause ? mixHash[id][i].resume() : mixHash[id][i].pause();
			}
		}
	});
}

// Add main mix to page
function addMainMix(event) {
	$.ajax({
      	dataType: "JSON",
      	url: "fetchBycate.php",
      	data: {category: condition},
      	success: function(data) {
      		if (data.length === 0) {
      			$("#tracks").remove();
      		} else {
				var data = data[0]['mixes'];
				for (var i=0; i<data.length; i++) {
					createSound(data[i]['name'], data[i]['volume'], data[i]['pan'], data[i]['loop'])
				}
      		}
      		$(".loading").remove();
      		$("#playAll").html('<i class="fa fa-fw fa-pause"></i>');
      	}
    });
}

// Create sound and its slider 
function createSound(name, volume, pan, loop) {
	var instance = createjs.Sound.createInstance(name);

	// Get and save the values of id, volume, pan, loop
	instance.volume = volume;
	instance.pan = pan;
	instance.loop = loop;
	instance.name = name;
	instance.id = slidersId[0];
	// console.log("Adding sound: " + item.text + " with id " + instance.id);
	slidersId.splice(0, 1);
	// console.log("remaining ids: " + slidersId);

	// Save instance
	instanceHash[instance.id] = instance;

	// Create the slider
	var slider = $('#track' + instance.id).CircularSlider({
		min: 0,
		max: Math.floor(instance.getDuration()),
	    radius: sizes[instance.id],
	    innerCircleRatio: '0.1',
	    slide: function(ui, value) { instance.setPosition(value); }
	});
	slider.id = instance.id;
	sliders[instance.id] = slider;
	trackTime(instance.id);

	// Don't play instance if paused
	if (pause) { 
		instance.setVolume = instance.volume;
		instance.setPan = instance.pan;
	}
	// Play instance
	else { 
		// console.log("playing: " + instance.name);
		instance.play({ loop: instance.loop, pan: instance.pan, volume: instance.volume });
		$("#playAll i").removeClass("fa-play").addClass("fa-pause");
	}
}

// Track time of main mix
function trackTime(key) {
	clearInterval(posIntervals[key]);
	var positionInterval = setInterval(function (event) {
		if (sliders.hasOwnProperty(key)) {
		    var obj = sliders[key];
		    var duration = instanceHash[key].getDuration();
		    if (duration === 0) {duration = 1;}
			obj.setValue(Math.floor(instanceHash[key].getPosition() % duration));
		}
	}, 50);
	posIntervals[key] = positionInterval;
}

/* Weather API */
function showPosition(position){
	//Pass the coordinates to the API using the API key, and retrieve the weather data.
	$.get("http://api.openweathermap.org/data/2.5/weather?lat=" + position.coords.latitude + "&lon=" + position.coords.longitude + "&APPID=9a25400d6a728870af915c8c614e77d7", function(data){
		$.each(data.weather, function(i, obj){
			if(obj.id >= 800 && obj.id <= 803){  //weather ids of sunny/cloudy, not raining. Displays rain images
				condition = "sunny";
			}
			else{ //weather ids of rainy/stormy. Displays sunny images
				document.getElementById("Weather").src = "img/Sunny/weather_sunny.jpg";
				document.getElementById("sm_img0").src = "img/Sunny/sunny_chime.jpg";
				document.getElementById("sm_img1").src = "img/Sunny/sunny_bee.jpg";
				document.getElementById("sm_img2").src = "img/Sunny/sunny_fountain.jpg";
				document.getElementById("tracks").style.backgroundImage = "url('img/Sunny/sunny_treetops.jpg')";
				document.body.style.backgroundImage = "url('img/Sunny/sunny_background.jpg')";
				document.getElementById("weather_panel").style.backgroundColor = "#CCCC00";
				document.getElementById("contact").style.backgroundColor = "#CCCC00";
				condition = "rainy";
			}
		});
		if((data.main.temp-'273').toFixed(0) > 21){    //warm weather hotter than 70 F. Displays cold images
			document.getElementById("Weather").src = "img/Cold/weather_cold.jpg";
			document.getElementById("sm_img0").src = "img/Cold/cold_icicle.jpg";
			document.getElementById("sm_img1").src = "img/Cold/cold_owl.jpg";
			document.getElementById("sm_img2").src = "img/Cold/cold_bells.jpg";
			document.getElementById("tracks").style.backgroundImage = "url('img/Cold/cold_stream.jpg')";
			document.body.style.backgroundImage = "url('img/Cold/cold_background.jpg')";
			document.body.style.backgroundRepeat = "repeat";
			document.getElementById("weather_panel").style.backgroundColor = "#D8DFEB";
			document.getElementById("contact").style.backgroundColor = "#D8DFEB";
			condition = "warm";
		}
		if((data.main.temp-'273').toFixed(0) < 0){    //cold weather colder than 32 F. Displays hot images
			document.getElementById("Weather").src = "img/Warm/weather_warm.jpg";
			document.getElementById("sm_img0").src = "img/Warm/warm_seagulls.jpg";
			document.getElementById("sm_img1").src = "img/Warm/warm_beach.jpg";
			document.getElementById("sm_img2").src = "img/Warm/warm_underwater.jpg";
			document.getElementById("tracks").style.backgroundImage = "url('img/Warm/warm_bonfire.jpg')";
			document.body.style.backgroundImage = "url('img/Warm/warm_background.jpg')";
			document.getElementById("weather_panel").style.backgroundColor = "#EEEEF0";
			document.getElementById("contact").style.backgroundColor = "#EEEEF0";
			condition = "cold";
		}
	});
	// console.log("I was called in weatherapi");
	queue.on("complete", addMainMix);
}
