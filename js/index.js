
var instances = [];
var positionInterval;
var sliders = [];
var i = 0;
var sizes = [75, 125, 175, 225, 275];

function init() {
	if (!createjs.Sound.initializeDefaultPlugins()) {
		$("#error").style.display = "block";
		$("#example").style.display = "none";
		return;
	}

	$("#tracks").css("display", "none");

	var assetsPath = "sounds/";
	var sounds = [
		{id: "evening-forest", src: "188986__dammos__storm-rain.mp3"},
		{id: "rain-n-frogs", src: "93681__sithjawa__rain-and-frogs-2.mp3"}
	];

	createjs.Sound.addEventListener("fileload", createjs.proxy(handleLoadComplete, this)); // add an event listener for when load is completed
	createjs.Sound.registerSounds(sounds, assetsPath);
	createjs.Sound.setVolume(50 / 100);
}

function handleLoadComplete(event) {

	$("#tracks").css("display", "block");

	var instance = createjs.Sound.createInstance(event.id);
	console.log(event.id);
	instance.play({loop: -1});
	instance.addEventListener("complete", function () {
		clearInterval(positionInterval);
		$("#playSound i").removeClass("fa-pause").addClass("fa-play");
	});

	instances.push(instance);

	var slider = $('#track' + i).CircularSlider({
		min: 0,
		max: Math.floor(instance.getDuration()),
    radius: sizes[i],
    innerCircleRatio: '0.1',
    slide: function(ui, value) {
    	instance.setPosition(value);
    }
  });

  sliders.push(slider);

  i += 1;
	
	$("#playSound i").removeClass("fa-play").addClass("fa-pause");

	trackTime();
}

$("#playSound").click(function(event) {
	for (var j = 0; j < instances.length; j++) {
		if (instances[j].playState == createjs.Sound.PLAY_FINISHED) {
			instances[j].play({loop: -1});
			$("#playSound i").removeClass("fa-play").addClass("fa-pause");
			trackTime();
			return;
		} else {
			instances[j].paused ? instances[j].resume() : instances[j].pause();
		}

		if (instances[j].paused) {
			$("#playSound i").removeClass("fa-pause").addClass("fa-play");
		} else {
			$("#playSound i").removeClass("fa-play").addClass("fa-pause");
		}
	}

});

var dragOffset;
function trackTime() {
	positionInterval = setInterval(function (event) {
		for (var j = 0; j < instances.length; j++) {
			sliders[j].setValue(Math.floor(instances[j].getPosition()));
		}
	}, 25);
}
