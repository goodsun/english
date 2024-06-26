  var player = document.getElementById("player");
  let playing = false;
  let speed = 1.0;
  let interval = 1500;

	function setSpeed(val) {
    speed = val;
  }
	function setInterval(val) {
    interval = val;
  }

	function speech(word,lang) {
		window.speechSynthesis.cancel()
		var u = new SpeechSynthesisUtterance();
		u.text = word;
		u.lang = lang;
		u.rate = speed;
    u.onend = function(event) {
          setTimeout(function() {
            console.log('speech success:'+ word+'['+lang+']');
            if(playmode=='repeat'){
              proceedPage();
            }else{
              playing = false
              player.textContent = 'PLAY';
            }
          }, interval);
    };
		window.speechSynthesis.speak(u);
    playing = true;
    player.textContent = 'STOP';
	}

	function speechStop() {
    window.speechSynthesis.cancel()
    playing = false
    player.textContent = 'PLAY';
  }

	function proceedPage() {
    if(storageData[quiz_no + 1] == 'master'){
      console.log('次のページはマスターしています'+(quiz_no + 1));   
      console.log(storageData[quiz_no + 1]);   
      quiz_no += 1;
      proceedPage();
    }else{
      nextPage();
    }
  }
