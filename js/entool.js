	function wordAnswerByNo(num) {
			var wq = document.getElementById('wordq'+num).textContent ;
			var wa = document.getElementById('worda'+num).textContent ;
			wordAnswer(wq,wa);
	}

  var answermodal = document.getElementById("answermodal");
  var dispmodal = false;
  var wordmodal = document.getElementById("wordmodal");
  var dispwmodal = false;

	function toggleWordModal(dispword,u) {
    answermodal.classList.remove("active");
    dispmodal = false;
    if(dispwmodal){
      wordmodal.classList.remove("active");
      dispwmodal = false;
      window.speechSynthesis.cancel()
    } else {
      wordmodal.classList.add("active");
      dispwmodal = true;
      var wordmodalcontent = document.getElementById("wordmodalcontent");
      wordmodalcontent.textContent = dispword;
      window.speechSynthesis.speak(u);
    }
  }

	function toggleAnswerModal() {
    wordmodal.classList.remove("active");
    dispwmodal = false;
    if(dispmodal){
      answermodal.classList.remove("active");
      dispmodal = false;
    } else {
      answermodal.classList.add("active");
      dispmodal = true;
    }
  }

	document.addEventListener('keydown', function(event) {
    window.speechSynthesis.cancel()

		if (event.key === '/' || event.key === 'Enter') {
      answermodal.classList.remove("active");
      wordmodal.classList.remove("active");
      dispmodal = false;
      dispwmodal = false;
    }


		if ( event.key == '1' || event.key == '2' || event.key == '3' || event.key == '4' || event.key == '5' || event.key == '6' || event.key == '7' || event.key == '8' || event.key == '9') {
			wordAnswerByNo(event.key);
		}

		if (event.key === 'Shift') {
			speech(que);
		}

		if (event.key === '0') {
			wordAnswerByNo('10');
		}
		if (event.key === '-') {
			wordAnswerByNo('11');
		}
		if (event.key === '^') {
			wordAnswerByNo('12');
		}
		if (event.key === "¥") {
			wordAnswerByNo('13');
		}
		if (event.key === "Backspace") {
			wordAnswerByNo('14');
		}

		if (event.key === 'ArrowRight') {
			document.getElementById('nextlink').click();
		}
		if (event.key === 'ArrowLeft') {
			document.getElementById('prevlink').click();
		}
		if (event.key === 'ArrowUp') {
      toggleAnswerModal();
		}
		if (event.key === '_') {
			ansspeech(ans);
		}
		if (event.key === 'ArrowDown') {
			document.getElementById('proglink').click();
		}
		if (event.key === 'y') {
			console.log('progress');
			document.getElementById('proglink').click();
		}
		if (event.key === 'n') {
			console.log('basic');
			document.getElementById('basiclink').click();
		}
		if (event.key === 'm') {
			console.log('practice');
			document.getElementById('practicelink').click();
		}
		if (event.key === ',') {
			console.log('review');
			document.getElementById('reviewlink').click();
		}
		if (event.key === 'h') {
			console.log('back');
			document.getElementById('prevlink').click();
		}
		if (event.key === 'j') {
      toggleAnswerModal();
		}
		if (event.key === 'k') {
			speech(que);
		}
		if (event.key === 'l') {
			console.log('next');
			document.getElementById('nextlink').click();
		}
		if (event.key === 'u') {
			console.log('review');
			document.getElementById('revlink').click();
		}
		if (event.key === 'i') {
			console.log('clear');
			document.getElementById('clearlink').click();
		}
		if (event.key === 'o') {
			console.log('master');
			document.getElementById('masterlink').click();
		}
		if (event.key === '.') {
			ansspeech(ans);
		}
	});

	function speech(word) {
		window.speechSynthesis.cancel()
		var u = new SpeechSynthesisUtterance();
		u.text = word;
		u.lang = target_lang_cord;
		u.rate = 0.8;
		window.speechSynthesis.speak(u);
	}
	function ansspeech(word) {
		window.speechSynthesis.cancel()
		var u = new SpeechSynthesisUtterance();
		u.text = word;
		u.lang = main_lang_cord;
		u.rate = 1.4;
		window.speechSynthesis.speak(u);
	}
	function wordAnswer(question,answer) {
		window.speechSynthesis.cancel()
		var u = new SpeechSynthesisUtterance();
		u.text = question;
		u.lang = target_lang_cord;
		u.rate = 0.8;
		toggleWordModal(question +' : '+ answer,u);
	}
