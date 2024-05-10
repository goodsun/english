  // SETQUIZ
  let quiz_no = 0;
  var understand = document.getElementById("understand");
  var headSnum = document.getElementById("HeadSnum");
  var headQnum = document.getElementById("HeadQnum");
  var headPM = document.getElementById("HeadPM");
  var textName = document.getElementById("textName");
  var answermodal = document.getElementById("answermodal");
  var dispmodal = false;
  var wordmodal = document.getElementById("wordmodal");
  var dispwmodal = false;
  var menumodal = document.getElementById("menumodal");
  var dispmenu = false;
  var sectionlist = document.getElementById("sectionlist");
  var playmode = "oneshot";
  var playModeContainer = document.getElementById("playmode");
  var modeCont = document.getElementById("modeCont");
  
	function togglePlay() {
    if(quiz_no == 0){
      nextPage();
      togglePlay();
    }
    if(playing){
      speechStop();
    }else{
      speech(textdata.text[quiz_no].question, textdata.qlang);
    }
  }
	function togglePlayMode() {
    if(playmode == "oneshot"){
      playmode = "repeat";
      headPM.textContent = '🔄';
      modeCont.textContent= 'AUTO';
    }else{
      playmode = "oneshot";
      headPM.textContent = '🔉';
      modeCont.textContent= 'ONESHOT';
    }
    playModeContainer.textContent = playmode;
  }
	function toggleWordModal(dispword) {
    if(dispwmodal){
      wordmodal.classList.remove("active");
      dispwmodal = false;
    } else {
      wordmodal.classList.add("active");
      dispwmodal = true;
    }
  }

	function toggleAnswerModal() {
    if(dispmodal){
      answermodal.classList.remove("active");
      dispmodal = false;
    } else {
      answermodal.classList.add("active");
      dispmodal = true;
    }
  }

	function toggleMenuModal() {
    if(dispmenu){
      menumodal.classList.remove("active");
      dispmenu = false;
    } else {
      menumodal.classList.add("active");
      dispmenu = true;
    }

    var classList = document.getElementById("classList");
    classList.innerHTML = '<h1 class="menutitle">Text List</h1>';
    for (const key in textdata.class) {
      let linktag = '&nbsp;<span class="success">' + textdata.class[key] + '</span>&nbsp;';
      if (textdata.textname != textdata.class[key]){
        linktag = '<a class="sectionlink" href="/?text='+ textdata.class[key] +'"> ' + textdata.class[key] + '</a>';
      }
      classList.insertAdjacentHTML('beforeend', linktag);
    }

    var sectionList = document.getElementById("sectionList");
    sectionList.innerHTML = '<h1 class="menutitle">Section List</h1>';
    for (const key in textdata.section) {
      const linktag = '<span class="sectionlink" onClick="setSection('+ key +')"> section.' + key + '</span>';
      sectionList.insertAdjacentHTML('beforeend', linktag);
    }

  }

	function setSection(seq) {
    speechStop();
    quiz_no = textdata.section[seq];
    setQuizData();
    menumodal.classList.add("active");
    dispmenu = true;
    speech(textdata.text[quiz_no].question, textdata.qlang);
  }

	function dispWord(q,a) {
    wordmodal.classList.add("active");
    dispwmodal = true;
    var wordmodalcontent = document.getElementById("wordmodalcontent");
    wordmodalcontent.textContent = q + ' : ' + a;
  }

	function setQuizData() {
      
      answermodal.classList.remove("active");
      dispmodal = false;
      wordmodal.classList.remove("active");
      dispwmodal = false;

      var question = document.getElementById("question");
      var answer = document.getElementById("answer");
      const quizdata = textdata.text[quiz_no];
      const wordlist = quizdata.words.wordlist;

      question.innerHTML = quizdata.words.output;
      answer.textContent = quizdata.answer;
      headQnum.textContent = quiz_no;

      for (const key in textdata.section) {
        if(textdata.section[key] > quiz_no){
          break;
        }
        headSnum.textContent = key;
      }
      console.log('SetQuiz:' + quiz_no + " (Sec." + headSnum.textContent+")");
      understandCheck();
  }

  function understandCheck(){
      if(storageData[quiz_no] == 'master'){
        console.log('this is master');
        understand.textContent = 'master';
        understand.classList.remove(...understand.classList);
        understand.classList.add('master');
      }else if(storageData[quiz_no] == 'check'){
        console.log('this is check');
        understand.textContent = 'check'
        understand.classList.remove(...understand.classList);
        understand.classList.add('check');
      } else {
        console.log('this is none');
        understand.textContent = ''
        understand.classList.remove(...understand.classList);
      }
  }

   function nextPage() {
      speechStop();
      quiz_no = quiz_no + 1;
      setQuizData();
      speech(textdata.text[quiz_no].question, textdata.qlang);
   }

   function prevPage() {
      speechStop();
      quiz_no = quiz_no - 1;
      if(quiz_no < 1){
        quiz_no = 1;
      }
      setQuizData();
      speech(textdata.text[quiz_no].question, textdata.qlang);
   }

	document.addEventListener('keydown', function(event) {
    console.log(event.key);
		if (event.key === '/') {
      saveData('master');
    }
		if (event.key === '.') {
      removeData();
    }
		if (event.key === ',') {
      saveData('check');
    }
		if (event.key === 'Backspace') {
      toggleMenuModal();
    }
		if (event.key === '¥') {
      togglePlayMode();
    }
		if (event.key === 'Enter') {
      togglePlay();
      answermodal.classList.remove("active");
      wordmodal.classList.remove("active");
      menumodal.classList.remove("active");
      dispmodal = false;
      dispwmodal = false;
      dispmenu = false;
    }
		if (event.key === '_') {
      togglePlayMode();
    }
		if (event.key === 'Shift') {
      toggleMenuModal();
    }
		if (event.key === 'ArrowUp') {
      togglePlay();
    }
		if (event.key === 'ArrowDown') {
      toggleAnswerModal();
    }
		if (event.key === 'ArrowRight') {
      speechStop();
      nextPage();
		}
		if (event.key === 'ArrowLeft') {
      speechStop();
      prevPage();
		}

    const regex = /^[a-z]+$/;
		if (regex.test(event.key)) {
      let dispWords = '';
      const wordsList = textdata.text[quiz_no].words.wordlist;
      for (const key in wordsList) {
        if(event.key == wordsList[key][0].charAt(0).toLowerCase()){
           dispWords = dispWords + wordsList[key][0] +' : '+ wordsList[key][1] + "\n";
        }
      }
      if(dispWords != ''){
        wordmodal.classList.add("active");
        dispwmodal = true;
        var wordmodalcontent = document.getElementById("wordmodalcontent");
        wordmodalcontent.textContent = dispWords;
      }
    }
    
  });

