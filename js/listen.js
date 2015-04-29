$(document).foundation();

/*
 * DEFAULT SOUND IMAGES
 */
var soundImgs = {
	"Beach Waves at Praia Grande": "http://i61.tinypic.com/wsn1ja.jpg",
	"Broken Top Creek": "http://images.forwallpaper.com/files/thumbs/preview/21/212749__rain-droplets-water-mood-photo-macro-black-and-white-wallpaper_p.jpg",
	"Babbling Brook": "https://naturewallpaperhd.files.wordpress.com/2013/11/black-and-white-river-hd-wallpaper-1024x640.jpg",
	"Large Campfire": "http://i58.tinypic.com/if5rmv.jpg",
	"Quiet Autumn Campfire": "http://i59.tinypic.com/2lig5ub.jpg",
	"Cedar Campfire": "http://i59.tinypic.com/2lig5ub.jpg",
	"Wind Blowing in a Field": "http://cdn.animals-pics.com/pictures/favim.com/orig/201102/23/Favim.com-634.jpg",
	"Northern Cold Wind Chimes": "http://i62.tinypic.com/2vi5xxw.jpg",
	"Five Rake Large Wind Chimes": "http://i59.tinypic.com/1el9og.jpg",
	"Nearby Wind Chimes": "http://s28.postimg.org/9k5s6e4gd/countrysidecidadas.jpg"
};

var instanceHash = {};
var mixHash = {};

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

	// Add sounds
	var queue = new createjs.LoadQueue();
	queue.installPlugin(createjs.Sound);
	queue.addEventListener("fileload", createjs.proxy(addSoundToList, this));
	queue.on("complete", removeLoad);
	queue.loadManifest([
	    {id: "Beach Waves at Praia Grande", src: "sounds/beach/beach_waves_at_praia_grande.ogg"},
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

	// Set master volume to 50%
	// createjs.Sound.setVolume(50 / 100);
}

function removeLoad() {
	$(".loading").remove();
}

function addSoundToList(event) {
	// Add to page
	var id = event.item.id.replace(/ /g,'');
	var newSound = 	'<div class="small-12 medium-6 large-4" id="' + id + '">' +
					'<img src="' + soundImgs[event.item.id] + '"/>' +
					'<h4><button class="small button"><i class="fa fa-fw fa-play"></i></button>' + event.item.id + '</h4></div>';
	$("#defaultLibrary").append(newSound);
	$("#" + id).on('click', function() {
		var instance = instanceHash[event.item.id];
		// if instance does not exist yet
		if (!instance) {
			// Create sound instance
			instance = createjs.Sound.createInstance(event.item.id);
			instance.play({loop: -1});
			$("#" + id + " button").html('<i class="fa fa-fw fa-pause"></i></button>');
			instanceHash[event.item.id] = instance;
		} else {
			if (instance.paused) {
				instance.resume();
				$("#" + id + " button").html('<i class="fa fa-fw fa-pause"></i></button>');
			} else {
				instance.pause();
				$("#" + id + " button").html('<i class="fa fa-fw fa-play"></i></button>');
			} 
		}
	});
}

// Load user mixes
if (document.getElementById("userLibrary")) {
	$.ajax({
      	dataType: "JSON",
      	url: "fetchMix.php",
      	success: function(data) {
      		// console.log(data);
      		if (data.length === 0) {
      			$("#userLibrary").html("<p>You haven't saved any mixes! Visit <a href='create.php'>the create page</a> to start mixing now.</p>");
      		} else {
      			for(var i = 0; i < data.length; i++) {
      				addUserMix(data[i]['name'], JSON.stringify(data[i]['mixes']));
      			}

      		}
      	}
    });
}

var mixnum = 0;

function addUserMix(name, mix) {
	// Add to page
	var id = "mix" + (mixnum+1);
	mixnum++;
	var mix = JSON.parse(mix);
	var usermix = 	'<div class="small-12 medium-6 large-4" id="' + id + '">' +
					'<img src="' + 'http://i59.tinypic.com/1el9og.jpg' + '"/>' +
					'<h4><button class="small button"><i class="fa fa-fw fa-play"></i></button>' + name + '</h4></div>';
	$("#userLibrary").append(usermix);

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
			console.log(mixHash[id]);
			for (var i=0; i<mixHash[id].length; i++) {
				pause ? mixHash[id][i].resume() : mixHash[id][i].pause();
			}
		}
	});
}

