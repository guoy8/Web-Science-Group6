
// Example of on-change event of slider
// $('#slider1').on('change.fndtn.slider', function(){
//   console.log($('#slider1').attr('data-slider'));
// });

var instanceHash = {};  //store instances as they are created

var audioInterval = null;
var intervalTime = 100;

function init() {
	createjs.Sound.registerPlugins([createjs.WebAudioPlugin, createjs.HTMLAudioPlugin]);

	if (!createjs.Sound.isReady()) {
		$("#error").css("display", "block");
		$("#interface").css("display", "none");
		return;
	}

	$("#library").change(selectItem);

	var assetsPath = "sounds/";
	var sounds = [
		{id: "test", src: "forgotten_sorrow.mp3"},
		{id: "test2", src: "song_of_moonlight.mp3"}
	];

	createjs.Sound.addEventListener("fileload", createjs.proxy(addSoundToList, this)); // add an event listener for when load is completed
	createjs.Sound.registerSounds(sounds, assetsPath);
}

// A library item was selected
function selectItem(event) {
	var list = $("#library").get(0);
}

function addSoundToList(event) {
	var list = $("#library").get(0);
	if (event.data.audioSprite) {
		for (var i = event.data.audioSprite.length; i--;) {
			list.options.add(new Option((event.data.audioSprite[i].id) + " (" + (event.data.channels || "unknown") + ")", event.data.audioSprite[i].id));  //event.data should never be undefined, because it is set to the max limit by SoundJS
		}
	} else {
		list.options.add(new Option((event.id || event.src) + " (" + (event.data || "unknown") + ")", event.id));  //event.data should never be undefined, because it is set to the max limit by SoundJS
	}
}

