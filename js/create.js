$(document).foundation();
/* 
 * =========================================
 * VARIABLES
 * =========================================
 */
var instanceHash = {};  		//stores all sound instances
var previewInstance = null;		//stores the current preview sound

var sliders = {};				//stores the sliders 
var posIntervals = {};			//stores the position trackers
var sizes = [65, 100, 135, 170, 205];
var slidersId = [0, 1, 2, 3, 4];


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

	/* SLIDERS */

	// Initialize slider values
	$('#addLoop, #editLoop').foundation('slider', 'set_value', -1);

	// Bind add sound slider controls
	$('#addVolume').on('change.fndtn.slider', function() {
		handleAddChange('volume', $(this).attr('data-slider'));
	});	
	$("input[name='addloop']").change(function() {
		if ($(this).attr("checked")) {
			handleAddChange("loop", $(this).val());
		}
	});
	$("input[name='addpan']").change(function() {
		if ($(this).attr("checked")) {
			handleAddChange("pan", $(this).val());
		}
	});

	// Bind edit sound slider controls
	$('#editVolume').on('change.fndtn.slider', function(){
		handleEditChange("volume", $(this).attr('data-slider'));
	});	
	$('input[name="editloop"]').change(function() {
		if ($(this).attr("checked")) {
			handleEditChange("loop", $(this).val());
		}
	}); 
	$("input[name='editpan']").change(function() {
		if ($(this).attr("checked")) {
			handleEditChange("pan", $(this).val());
		}
	});

	/* SELECTION */

	// When user selects a new sound... 
	$("#library").change(selectNew);
	// When user selects a current playing sound...
	$("#nowPlaying").change(selectCurrent);


	/* BUTTONS */
	$("#playAll").click(masterPlayPause);
	$("#previewBtn").click(previewSound);
	$("#addBtn").click(addSound);
	$("#playPauseBtn").click(playPause);
	$("#stopBtn").click(stopCurrent);
	$("#removeBtn").click(removeOne);
	$("#removeAllBtn").click(removeAll);

	// Add sounds
	var assetsPath = "sounds/";
	var sounds = [
		{id: "Storm Rain", src: "188986_dammos_storm-rain.ogg"},
		{id: "Rain and Frogs", src: "93681_sithjawa_rain-and-frogs-2.ogg"},
		{id: "Thunderstorm", src: "2523_rhumphries_rbh-thunder-storm.ogg"},
		{id: "Evening in the Forest", src: "18765_reinsamba_evening-in-the-forest.ogg"},
		{id: "Wind and Soft Crickets", src: "163607_leandros-ntounis_wind-soft-crickets.ogg"}
	];

	// Using PreloadJS, add sound to library list
	createjs.Sound.addEventListener("fileload", createjs.proxy(addSoundToList, this)); // add an event listener for when load is completed
	createjs.Sound.registerSounds(sounds, assetsPath);

	// Set master volume to 50%
	// createjs.Sound.setVolume(50 / 100);
}


/* 
 * =========================================
 * HELPER FUNCTIONS
 * =========================================
 */

/*
 * General 
 */

// Adds sound to library
function addSoundToList(event) {
	var list = $("#library").get(0);
	console.log("addtolist: " + event.id);
	list.options.add(new Option((event.id || event.src), event.id)); 
}

// Play/pause all sounds 
var pause = false;
function masterPlayPause(event) {
	if (slidersId.length === 5) { return; }
	pause = !pause;
	console.log("Want to pause all songs: " + pause);
	if (pause) {
		$("#playAll i").removeClass("fa-pause").addClass("fa-play");
	} else {
		$("#playAll i").removeClass("fa-play").addClass("fa-pause");
	}
	for (var key in instanceHash) {
	   	if (instanceHash.hasOwnProperty(key)) {
	   		console.log("masterplaypause: " + key);
	    	var obj = instanceHash[key];
	    	// If user wishes to pause all sounds and sound is not finished
	    	if (pause && obj.playState != createjs.Sound.PLAY_FINISHED) {
	    		obj.pause();
	    	} 
	    	// If user wants to play all sounds
	    	else if (!pause) {
	    		if (obj.playState == createjs.Sound.PLAY_FINISHED) {
	    			obj.play({ loop: obj.loop, volume: obj.volume, pan: obj.pan });
	    			trackTime(key);
	    		} else {
	    			obj.resume();
	    		}
	    	}
	    }
	}
}

function trackTime(key) {
	clearInterval(posIntervals[key]);
	positionInterval = setInterval(function (event) {
		if (sliders.hasOwnProperty(key)) {
		    var obj = sliders[key];
			obj.setValue(Math.floor(instanceHash[key].getPosition()));
		}
	}, 50);
	posIntervals[key] = positionInterval;
}


/* 
 * Add Sound 
 */

// Selecting a new sound
function selectNew(event) {
	var list = $("#library").get(0);
	if (list.selectedIndex != -1) {
		$("#addBtns li button, #addVolume").removeClass("disabled");
		$("#addLoop input, #addPan input").prop("disabled", false);
	} else {
		$("#addBtns li button, #addVolume").addClass("disabled");
		$("#addLoop input, #addPan input").prop("disabled", true);
	}
}

// Previews the selected instance
function previewSound(event) {
	// Get selected element
	var selected = $("#library").find(":selected").text();
	if (previewInstance != null) {  console.log("The previous previewInstance id is " + previewInstance.name); }
	// If no preview instance is playing or a new instance is selected
	if (previewInstance === null || selected != previewInstance.name) {
		// Stop previous instance if it exists
		if (previewInstance != null) {
			previewInstance.stop();
		}
		// Get the new selected sound and play
		var instance = createjs.Sound.createInstance(selected);
		instance.name = selected;
		console.log("Previewing sound: " + instance.name);
		instance.volume = $("#addVolume").attr('data-slider') / 100;
		instance.loop = $("input[name='addloop']:checked").val();
		instance.pan = $("input[name='addpan']:checked").val();
		instance.play({ "volume": instance.volume, "pan": instance.pan });
		instance.addEventListener("complete", removePreview);
		previewInstance = instance;
		$("#previewBtn").html("<i class='fa fa-pause'></i> Pause");
	} else {
		// Pause and play preview
		if ($("#previewBtn i").hasClass("fa-pause")) {
			previewInstance.pause();
			$("#previewBtn").html("<i class='fa fa-play'></i> Preview");
		} else {
			previewInstance.play();
			$("#previewBtn").html("<i class='fa fa-pause'></i> Pause");
		}
	}
}

function removePreview(event) {
	var instance = event.target;
	previewInstance = null;
	$("#previewBtn").html("<i class='fa fa-play'></i> Play");
	instance.removeEventListener("complete", instance.removePreview);
	delete(instance.removePreview);
}

// Handles volume changes
function handleAddChange(type, val) {
	if (previewInstance === null) { return; }
	if (type === "volume") {
		previewInstance.setVolume(val/100);
	} else if (type=="loop") {
		previewInstance.loop = loop;
	} else if (type === "pan") {
		previewInstance.setPan(val);
	}
}

// Adds sound to current playing
var i = 0;	
var positionInterval;
function addSound(event) {
	// Verify an item exists in list.
	var list = $("#library").get(0);
	if (list.selectedIndex == -1) { return; }

	// Stop the preview
	if (previewInstance != null) {
		console.log("Stopping preview of " + previewInstance.name);
		previewInstance.stop();
		previewInstance = null;
		$("#previewBtn").html("<i class='fa fa-play'></i> Preview");
	}

	// Make sure the max number of tracks isn't exceeded
	if (slidersId.length < 1) { 
		$("#maxError").html(
			'<div data-alert class="alert-box warning"> Sorry, you cannot add any more tracks to this mix. <a href="#" class="close">&times;</a></div>'
		);
		$(document).foundation('alert', 'reflow');
		return;
	}

	// Get selected instance(s)
	for (var j = 0, l = list.options.length; j < l; j++) {
		if (!list.options[j].selected) { continue; }

		var item = list.options[j];
		// Create the sound instance
		var instance = createjs.Sound.createInstance(item.value);
		instance.handleSuccessProxy = createjs.proxy(handlePlaySuccess, instance);	// OJR kind of hacky
		instance.addEventListener("succeeded", instance.handleSuccessProxy);
		instance.addEventListener("interrupted", createjs.proxy(handlePlayFailed,instance));
		instance.addEventListener("failed", createjs.proxy(handlePlayFailed,instance));
		
		// Get and save the values of id, volume, pan, loop
		instance.volume = $("#addVolume").attr('data-slider') / 100;
		instance.pan = $("input[name='addpan']:checked").val();
		instance.loop = $("input[name='addloop']:checked").val();
		instance.name = item.text;
		instance.id = slidersId[0];
		console.log("Adding sound: " + item.text + " with id " + instance.id);
		slidersId.splice(0, 1);
		console.log("remaining ids: " + slidersId);

		// Save instance
		instanceHash[instance.id] = instance;

		// Create the slider
		var slider = $('#track' + instance.id).CircularSlider({
			min: 0,
			max: Math.floor(instance.getDuration()),
		    radius: sizes[instance.id],
		    innerCircleRatio: '0.1',
		    slide: function(ui, value) {
		    	instance.setPosition(value);
		    }
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
			instance.play({ loop: instance.loop, pan: instance.pan, volume: instance.volume });
			$("#playAll i").removeClass("fa-play").addClass("fa-pause");
		}
	}
}

function handlePlaySuccess(event) {
	var instance = event.target;
	console.log("handleplaysuccess: " + instance.id);
	instance.removeEventListener("succeeded", instance.handleSuccessProxy);
	delete(instance.handleSuccessProxy);

	var nowPlaying = $("#nowPlaying").get(0);

	// Add to Now Playing
	if (nowPlaying.options[0] !== null) {
		if (nowPlaying.options[0].value == "-1") {
			nowPlaying.remove(0);
			nowPlaying.disabled = false;
		} else {
			for (var j = nowPlaying.options.length - 1; j >= 0; j--) {
				if (nowPlaying.options[j].value == instance.id) {
					if (nowPlaying.options.remove) {
						nowPlaying.options.remove(j);
					} else if (nowPlaying.remove) {
						nowPlaying.remove(j);
					}
				}
			}
		}
	}
	nowPlaying.options.add(new Option(instance.name + "(" + instance.id + ")", instance.id));
}

// Playback failed (usually interrupt failed)
function handlePlayFailed(event) {
	removeSound(event.target);
}
/*
 * Edit Sound 
 */

// Selecting a currently playing sound
function selectCurrent(event) {
	var list = $("#nowPlaying").get(0);
	if (list.selectedIndex > -1) {
		// Enable options
		$("#editBtns li button, #editVolume").removeClass("disabled");
		$("input[name='editpan'], input[name='editloop']").prop("disabled", false);
		
		// Get the selected sound
		var instance = getCurrentSound();
		if (instance === null) { return; }

		// Get and set volume
		var value = instance.getVolume() * 100 | 0;
		$("#editVolume").foundation('slider', 'set_value', value);

		// Get and set loop
		value = instance.loop;
		if (value === "-1") {
			$("#yesELoop").prop("checked", true);
			$("#noELoop").prop("checked", false);
		} else {
			$("#yesELoop").prop("checked", false);
			$("#noELoop").prop("checked", true);
		}

		// Get and set pan
		value = instance.getPan();
		if (value === -1) {
			$("#editCenter, #editRight").prop("checked", false);
			$("#editLeft").prop("checked", true);
		} else if (value === 0) {
			$("#editLeft, #editRight").prop("checked", false);
			$("#editCenter").prop("checked", true);
		} else {
			$("#editCenter, #editLeft").prop("checked", false);
			$("#editRight").prop("checked", true);
		}

		// Set play/pause button
		if (soundStatus(instance) === "paused") {
			$("#playPauseBtn").html('<i class="fa fa-play"></i> Play</button>');
		} else {
			$("#playPauseBtn").html('<i class="fa fa-pause"></i> Pause</button>');
		}

		// Highlight corresponding position slider
		console.log("Selected sliderId: " + sliders[instance.id].attr('id'));
		var sid = sliders[instance.id].attr('id');
		highlight(sid);

	} else {
		// disable options
		$("#editBtns li button, #editVolume, #editLoop").addClass("disabled");
		$("#removeAllBtn").removeClass("disabled");
		$("#editLeft, #editCenter, #editRight").prop("disabled", true);
	}
}

function highlight(wantedID) {
	var highlighted = "#43DB73";
	var normal = "#45A7C8"
	for (var key in sliders) {
		if (sliders.hasOwnProperty(key)) {
		    var sid = sliders[key].attr('id');
		    if (sid === wantedID) {
		    	$("#" + sid + " .jcs, #" + sid + " .jcs-indicator").css("border-color", highlighted);
		    	$("#" + sid + " .jcs-indicator").css("background-color", highlighted);
		    } else {
		    	$("#" + sid + " .jcs, #" + sid + " .jcs-indicator").css("border-color", normal);
		    	$("#" + sid + " .jcs-indicator").css("background-color", normal);
		    }
		}
	}
}

// Get currently selected sound from Now Playing
function getCurrentSound() {
	var list = $("#nowPlaying").get(0);
	if (list.selectedIndex > -1) {
		var item = list.options[list.selectedIndex];
		if (item == null) { return null; } 
		var instance = instanceHash[item.value];
		return instance;
	}
	return null;
}

// Checks if sound is finished, currently playing, or paused
function soundStatus(instance) {
	if (instance.playState != createjs.Sound.PLAY_FINISHED) {
		if (instance.paused) { return "paused"; }
		else { return "playing"; }
	} else { return "finished"; }
}

// Handles play/pause the selected sound
function playPause(event) {
	var instance = getCurrentSound();
	if (instance === null) { return; }
	console.log("Want to play currentSound: " + instance.name + " with id: " + instance.id);
	var status = soundStatus(instance);
	console.log("Sound status: " + status);
	if (status === "paused") {
		instance.resume();
		$("#playPauseBtn").html('<i class="fa fa-pause"></i> Pause</button>');
	} else if (status === "playing") {
		instance.pause();
		$("#playPauseBtn").html('<i class="fa fa-play"></i> Play</button>');
	} else {
		instance.play({ volume: instance.getVolume(), loop: instance.loop, pan: instance.getPan() });
		$("#playPauseBtn").html('<i class="fa fa-pause"></i> Pause</button>');
	}
}

// Handles stopping the selected sound
function stopCurrent(event) {
	var instance = getCurrentSound();
	if (instance != null) { 
		console.log("Want to stop: " + instance.name);
		instance.stop(); 
		if ($("#playPauseBtn i").hasClass("fa-pause")) {
			$("#playPauseBtn").html('<i class="fa fa-play"></i> Play</button>');
		}
	}
}

// Handles removing the selected sound
function removeOne(event) {
	var instance = getCurrentSound();
	if (instance == null) { return; }
	instance.stop();
	console.log("Want to remove: " + instance.name);
	removeSound(instance);
}

// Handles removing all sounds
function removeAll(event) {
	createjs.Sound.stop();
	removeAllSound();
}

// Removes a given instance
function removeSound(instance) {
    var list = $("#nowPlaying").get(0);
    for (var j = list.options.length - 1; j >= 0; j--) {
		if (list.options[j].value == instance.id) {

			// Disable edit buttons if selected element was removed
			if (list.selectedIndex == j) {
				$("#editBtns li button, #editVolume, #editLoop").addClass("disabled");
				$("#removeAllBtn").removeClass("disabled");
				$("#editLeft, #editCenter, #editRight").prop("disabled", true);
			}

			// Remove from list
			if (list.options.remove) { list.options.remove(j); }
			else if (list.remove) { list.remove(j); }

			// Remove position tracker
			clearInterval(posIntervals[instance.id]);

    		// Delete the instance and its slider
		    delete(instanceHash[instance.id]);
		    $("#" + sliders[instance.id].attr('id') + " div").remove();
		    delete(sliders[instance.id]);

		    // Return sliderId
			slidersId.push(instance.id);
    		slidersId.sort();
    		console.log("available slidersId: " + slidersId);

			break;
		}
	}
	// Add default --no sounds playing--
	if (list.options.length == 0) {
		list.options.add(new Option("-- No Sounds Playing --", -1));
		list.disabled = true;
		$("#editBtns li button, #editVolume, #editLoop").addClass("disabled");
		$("#removeAllBtn").removeClass("disabled");
		$("#editLeft, #editCenter, #editRight").prop("disabled", true);
		$("#playPauseBtn").html('<i class="fa fa-play"></i> Play</button>');
		$("#playAll i").removeClass("fa-pause").addClass("fa-play");
	} else if (list.selectedIndex > -1) {
		var item = list.options[list.selectedIndex];
	}
}

// Removes all sounds
function removeAllSound(instance) {
	var list = $("#nowPlaying").get(0);
	for (var j = list.options.length - 1; j >= 0; j--) {
		// Instance id
		var iid = list.options[j].value;
		console.log("Removing song with id: " + iid);

		// Remove from list
		if (list.options.remove) { list.options.remove(j); }
		else if (list.remove) { list.remove(j); }

		// Remove position tracker
		clearInterval(posIntervals[iid]);

		// Delete the instance and its slider
	    delete(instanceHash[iid]);
	    $("#" + sliders[iid].attr('id') + " div").remove();
	    delete(sliders[iid]);

	    // Return sliderId
		slidersId.push(iid);
		slidersId.sort();
		console.log("available slidersId: " + slidersId);
	}

	// Disable edit buttons 
	$("#editBtns li button, #editVolume, #editLoop").addClass("disabled");
	$("#removeAllBtn").removeClass("disabled");
	$("#editLeft, #editCenter, #editRight").prop("disabled", true);

	// Add default --no sounds playing--
	list.options.add(new Option("-- No Sounds Playing --", -1));
	list.disabled = true;
	$("#editBtns li button, #editVolume, #editLoop").addClass("disabled");
	$("#removeAllBtn").removeClass("disabled");
	$("#editLeft, #editCenter, #editRight").prop("disabled", true);
	$("#playPauseBtn").html('<i class="fa fa-play"></i> Play</button>');
	$("#playAll i").removeClass("fa-pause").addClass("fa-play");
}

// Handles edit sound option changes
function handleEditChange(type, val) {
	var instance = getCurrentSound();
	if (instance === null) { return; }
	if (type === "volume") {
		instance.setVolume(val/100);
	} else if (type === "pan") {
		instance.setPan(val);
	} else {
		instance.loop = val;
	}
}

// Show modals

