$(document).ready(function(){
	var condition = "rainy";
	if (navigator.geolocation) {
		//Get the current location coordinates.
        navigator.geolocation.getCurrentPosition(showPosition);
    } 
	function showPosition(position){
		//Pass the coordinates to the API using the API key, and retrieve the weather data.
		$.get("http://api.openweathermap.org/data/2.5/weather?lat=" + position.coords.latitude + "&lon=" + position.coords.longitude + "&APPID=9a25400d6a728870af915c8c614e77d7", function(data){
			$.each(data.weather, function(i, obj){
				if(obj.id >= 800 && obj.id <= 803){  //weather ids of sunny/cloudy, not raining. Displays rain images
					condition = "sunny";
				}
				else{ //weather ids of rainy/stormy. Displays sunny images
					document.getElementById("Weather").src = "img/Sunny/weather_sunny.jpg";
					document.getElementById("sm_img1").src = "img/Sunny/sunny_chime.jpg";
					document.getElementById("sm_img2").src = "img/Sunny/sunny_bee.jpg";
					document.getElementById("sm_img3").src = "img/Sunny/sunny_fountain.jpg";
					document.getElementById("tracks").style.backgroundImage = "url('img/Sunny/sunny_treetops.jpg')";
					document.body.style.backgroundImage = "url('img/Sunny/sunny_background.jpg')";
					document.getElementById("weather_panel").style.backgroundColor = "#CCCC00";
					document.getElementById("contact").style.backgroundColor = "#CCCC00";
					condition = "rainy";
				}
			});
			if((data.main.temp-'273').toFixed(0) > 21){    //warm weather hotter than 70 F. Displays cold images
				document.getElementById("Weather").src = "img/Cold/weather_cold.jpg";
				document.getElementById("sm_img1").src = "img/Cold/cold_icicle.jpg";
				document.getElementById("sm_img2").src = "img/Cold/cold_owl.jpg";
				document.getElementById("sm_img3").src = "img/Cold/cold_bells.jpg";
				document.getElementById("tracks").style.backgroundImage = "url('img/Cold/cold_stream.jpg')";
				document.body.style.backgroundImage = "url('img/Cold/cold_background.jpg')";
				document.body.style.backgroundRepeat = "repeat";
				document.getElementById("weather_panel").style.backgroundColor = "#D8DFEB";
				document.getElementById("contact").style.backgroundColor = "#D8DFEB";
				condition = "warm";
			}
			if((data.main.temp-'273').toFixed(0) < 0){    //cold weather colder than 32 F. Displays hot images
				document.getElementById("Weather").src = "img/Warm/weather_warm.jpg";
				document.getElementById("sm_img1").src = "img/Warm/warm_seagulls.jpg";
				document.getElementById("sm_img2").src = "img/Warm/warm_beach.jpg";
				document.getElementById("sm_img3").src = "img/Warm/warm_underwater.jpg";
				document.getElementById("tracks").style.backgroundImage = "url('img/Warm/warm_bonfire.jpg')";
				document.body.style.backgroundImage = "url('img/Warm/warm_background.jpg')";
				document.getElementById("weather_panel").style.backgroundColor = "#EEEEF0";
				document.getElementById("contact").style.backgroundColor = "#EEEEF0";
				condition = "cold";
			}
		});
	}
	return condition;
});
