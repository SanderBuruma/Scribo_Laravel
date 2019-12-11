@extends('layouts.app')

@section('title', '| Home')


@section('header')
<link rel="stylesheet" href="{{ asset('/css/home.css') }}">
@stop


@section('content')
<div class="row" style="margin-right: 0;">
  <div class="col-md-8 offset-md-2 card mb-sm-2">
    <div class="card-body">
      <table>
        <tbody>

          <tr style="font-size: 24px;">
            <td><i class="fas fa-keyboard"></i></td>
            <td><a href="#" id="load-text-rnd" data-toggle="tooltip" title="Fetch ANY random text from the database">> Random <</a></td>
          </tr>

          <tr style="font-size: 24px;">
            <td><i class="fas fa-search" style="font-size: 24px;"></i></td>
            <td><a href="#" id="load-text-bible" data-toggle="tooltip" title="Fetch a specific text from the Bible">> Biblical <</a></td>
            <td>
              <select id="load-text-bible-book" value="2">
              @foreach($subcategories as $subcategory)
                <option value="{{$subcategory->id}}">{{$subcategory->name}}</option>
              @endforeach
              </select>
              <select id="load-text-bible-chapter"><option value="1"></option>1</select>
              <select id="load-text-bible-verse"><option value="1"></option>1</select>
            </td>
          </tr>

          <tr style="font-size: 24px;">
            <td><i class="fas fa-search" style="font-size: 24px;"></i></td>
            <td><a href="#" id="load-text-saint" data-toggle="tooltip" title="Fetch a specific text from a saint book">> Saint <</a></td>
            <td>
              <select id="load-text-saint-book" value="1">
              </select>
              <select id="load-text-saint-chapter"><option value="1"></option>1</select>
              <select id="load-text-saint-verse"><option value="1"></option>1</select>
            </td>
          </tr>

        </tbody>
      </table>
    </div>
  </div>

  <div class="col-md-8 offset-md-2 card card mb-sm-2" id="scribo-box">
    <div class="card-header">
      <h4 id="text-header">
        {{-- javascript inputs header info here --}}
      </h4>
    </div>
    <div class="card-body"><h2>
      <div id="text-to-type-display" class="inactive">
        <span id="text-correct"></span><span id="text-next-char"></span><span id="text-wrong"></span><span id="text-to-type"></span>
      </div>
      <input type="text" id="text-to-type-input" class="form-control" title="Empty me to reset the timer and mistakes counter. (Copy pasting into this box is not allowed...)" placeholder="Type here...">
    </h2></div>
  </div>

  <div class="col-md-8 offset-md-2 card mb-sm-2" id="completed-races-card">
    <div class="card-header">
      <h4>
        Completed Races:
      </h4>
    </div>
    <div>
      <table class="table">
        <thead>
          <th data-toggle="tooltip" title="Length of the typed text">Length</th>
          <th data-toggle="tooltip" title="WPM, characters per minute divided by five">WPM</th>
          <th data-toggle="tooltip" title="Mistakes divided by Length of text">Accuracy</th>
        </thead>
        <tbody id="completed-races-body">
          {{-- Javascript interacts here --}}
        </tbody>
      </table>
    </div>
  </div>

  <div class="col-md-8 offset-md-2 card leaderboard">
    <div class="card-header">
      <h4>
        Leaderboard
      </h4>
    </div>
    <div>
      <table class="table">
        <thead data-toggle="tooltip" title="To be recorded in the leaderboard the user must have completed at least 25 races">
          <th>Name</th>
          <th data-toggle="tooltip" title="Words per minute - characters typed per second times twelve">WPM</th>
          <th data-toggle="tooltip" title="1 minus number of mistakes divided by total characters correctly typed">Accuracy</th>
        </thead>
        <tbody id="leaderboard-body">
          {{-- Javascript interacts here --}}
        </tbody>
      </table>
    </div>
  </div>

</div>

<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">You're not logged in!</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <img src="/img/happynun.png"><br>
        Your scores and progress will not be saved. Register or log in so sister Gaudea can record your scores and calculate your statistics! When she isn't praying the rosary she's recording your scores and your WPM but she can't do that if you won't give her your name and email!
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">I don't want to be logged in!</button>
        <a type="button" class="btn btn-primary" href="/login">Log in!</a>
        <a type="button" class="btn btn-primary" href="/register">Register!</a>
      </div>
    </div>
  </div>
</div>
@endsection

@section('footer')

@guest
<script>
$('#modal').modal()
</script>
@endguest

<script>
let inputLen = 0, startTime = +new Date(), raceStarted = false, currentText = '', nextText = '', fetchStatus = false, typingMistakes = 0, typingCorrect = true, allSaints = [], completedRaces = [], last25_wpm = 15;
let textInput = $('#text-to-type-input')[0],
textCorrect   = $('#text-correct')[0],
textNextChar  = $('#text-next-char')[0],
textWrong     = $('#text-wrong')[0],
textToType    = $('#text-to-type')[0],
scriboBox     = $('#scribo-box')[0];

$(document).ready(function(){
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    }
  });
  fetchLeaderboard();
  populateSaints();
  $('#text-to-type-input')[0].value = null;

  $('#load-text-rnd')[0].onclick = function(){
    fetchText();
  }
  $('#load-text-bible')[0].onclick = function(){
    let query = $('#load-text-bible-book')[0].value + " " + $('#load-text-bible-chapter')[0].value + " " + $('#load-text-bible-verse')[0].value;
    fetchText("?specific="+query);
  }
  $('#load-text-saint')[0].onclick = function(){
    let query = $('#load-text-saint-book')[0].value + " " + $('#load-text-saint-chapter')[0].value + " " + $('#load-text-saint-verse')[0].value;
    fetchText("?specific="+query);
  }

  $('#load-text-bible-book')[0].onchange = function(){
    bibleBookChange()
  }
  $('#load-text-saint-book')[0].onchange = function(){
    refreshSaintsChapters()
  }
  $('#load-text-saint-chapter')[0].onchange = function(){
    refreshSaintsVerses()
  }

  function bibleBookChange() {
    $.ajax({
      url: `/ajax/chapter`,
      method: 'post',
      data: {
        book: $('#load-text-bible-book')[0].value,
      },
      success: function(result){
        innerString = '';
        for (let i = 1; i-1<result ; i++) {
          innerString += `<option value="${i}">${i}</option>`
        }
        $('#load-text-bible-chapter')[0].innerHTML = innerString;
        $('#load-text-bible-chapter')[0].value = 1;
        bibleChapterChange();
      }
    });
  }

  $('#load-text-bible-chapter')[0].onchange = function(){
    bibleChapterChange();
  }

  function bibleChapterChange () {
    $.ajax({
      url: `/ajax/verse`,
      method: 'post',
      data: {
        book: $('#load-text-bible-book')[0].value,
        chapter: $('#load-text-bible-chapter')[0].value,
      },
      success: function(result){
        innerString = '';
        for (let i = 1; i-1<result ; i++) {
          innerString += `<option value="${i}">${i}</option>`
        }
        $('#load-text-bible-verse')[0].innerHTML = innerString;
        $('#load-text-bible-verse')[0].value = 1;
      }
    });
  }

	$('#text-to-type-input')[0].addEventListener('keydown', txtInputChange);
	$('#text-to-type-input')[0].addEventListener('keyup', txtInputChange);
  function txtInputChange(e) {
    if (e.target.value.length > 0 && !raceStarted) {
      //reset timer and mistake counter
      raceStarted = true;
      $('#text-to-type-display')[0].classList.remove('inactive');
      startTime = +new Date();
      typingMistakes = 0;
    }
    if (e.target.value.length > inputLen+2) {
      alert('no copy pasting, please...');
      e.target.value = '';
    }
    inputLen = e.target.value.length;

    refreshText(currentText.text);
	};
  bibleBookChange();
  fetchText();
});

function refreshText(text) {

  if (text.indexOf(textInput.value) === 0) { //input congruent with text

    typingCorrect = true;

    textInput.disabled = false;
    textInput.classList.remove('error')
    textCorrect.innerHTML = textInput.value;
    textNextChar.innerHTML = text.slice(textInput.value.length,textInput.value.length+1);
    textWrong.innerHTML = '';
    textToType.innerHTML = currentText.text.slice(textInput.value.length+1);
    if (textInput.value.length===currentText.text.length-1){//input equals text (ie. race complete)
      storeRace();
			scriboBox.classList.add('complete');
      textInput.disabled = true;
      if (!fetchStatus) {
        fetchStatus = true
        rollNextText();
      }
		} else {//input does not equal text
			scriboBox.classList.remove('complete');
		}
  } else { //input does not match text
    if (typingCorrect) {
      typingMistakes++;
      typingCorrect = false;
    };
		textNextChar.innerHTML = '';
		textInput.classList.add('error');
		for (let i = 0; i<currentText.text.length; i++){
			if (currentText.text.charAt(i)!==textInput.value.charAt(i)){//find the first character where text does not match input
				textCorrect.innerHTML = textInput.value.slice(0,i);
				textWrong.innerHTML = text.slice(i,textInput.value.length)
				textToType.innerHTML = text.slice(textInput.value.length)
				break;
			}
		}
	}
};


function populateSaints () {
  $.ajax({
    url: `/ajax/saints`,
    method: 'post',
    success: function(result){
      for (let i of result) {
        allSaints[i.id] = {id: i.id, name: i.name, text_count: i.text_count,};
      }
      let firstSaint, insideStr = ``;
      for (let i of result) {
        insideStr += `<option value="${i.id}">${i.name}</option>`;
        if (!firstSaint) {
          firstSaint = i;
        }
      };
      $('#load-text-saint-book').html(insideStr);
      $('#load-text-saint-book').val(firstSaint.id);
      refreshSaintsChapters();
    }
  });
}

function refreshSaintsChapters() {
  let saint = allSaints[$('#load-text-saint-book').val()];
  let chapterCount = Math.ceil(saint.text_count/50);
  let insideStr = ``;
  for (let i = 1; i<=chapterCount; i++) {
    insideStr += `<option value="${i}">${i}</option>`;
  }
  $('#load-text-saint-chapter').html(insideStr);
  $('#load-text-saint-chapter').val(1);
  refreshSaintsVerses();
}

function refreshSaintsVerses() {
  let saintId = $('#load-text-saint-book').val();
  let saintChapter = $('#load-text-saint-chapter').val();
  let insideStr = ``;
  if (Math.ceil(allSaints[saintId].text_count/50) != saintChapter) {
    for (let i = 1; i<=50 ; i++) {
      insideStr += `<option value="${i}">${i}</option>`;
    }
  } else {
    for (let i = 1; i<=allSaints[saintId].text_count%50 ; i++) {
      insideStr += `<option value="${i}">${i}</option>`;
    }
  }
  $('#load-text-saint-verse').html(insideStr);
  $('#load-text-saint-verse').val(1);
}

function fetchText(getVariables = '') {
  //should be run only if a race has NOT been finished
  $.ajax({
    url: `/ajax/text${getVariables}`,
    method: 'get',
    success: function(result){

      nextText = result;
      if (!nextText) {
        //happens onload of the page
        fetchText(`?textId=${nextText.id-2}`);

      } else {

        rollNextText();

      }
    }
  });

}

function fetchNextText () {
  $.ajax({
    url: `/ajax/text?textId=${currentText.id}`,
    method: 'get',
    success: function(result){
      nextText = result;
    }
  });
}

function rollNextText () {
  currentText = nextText;
  fetchStatus = false;
  $('#text-to-type-input')[0].value = null;
  $('#text-header')[0].innerHTML = `${currentText.title} ${currentText.chapter}:${currentText.verse}`;
  refreshText(currentText.text);
  raceStarted = false;
  $('#text-to-type-display')[0].classList.add('inactive');
  fetchNextText();
}

function fetchLeaderboard() {
  $.ajax({
    url: `/ajax/leaderboard`,
    method: 'get',
    success: function(result){
      let insideStr = ``;
      for (let i of result) {
        let WPM = Math.round(i.WPM*1e2)/1e2;
        let accuracy = `${((1-i.mistakes/i.races_len)*100)}`.substr(0,5);
        accuracy += `%`;
        insideStr += `
        <tr>
          <td><img src="https://www.gravatar.com/avatar/${i.md5}?s=25" alt=""> <a href="/user/${i.name}" target="_blank" rel="noopener noreferrer">${i.name}</a></td>
          <td>${WPM}</td>
          <td>${accuracy}</td>
        </tr>`;
      }
      $('#leaderboard-body').html(insideStr);
    }
  });
}

function storeRace(){
  let textInput = $('#text-to-type-input')[0];
  $.ajax({
    url: `/race`,
    method: 'post',
    data: {
      text_id: currentText.id,
      speed: textInput.value.length * 12 / (((+new Date())-startTime)/1e3),
      mistakes: typingMistakes,
      time_taken: (((+new Date())-startTime)/1e3),
    }
  });
  updateCompletedRaces()
}

function updateCompletedRaces () {

  let textInput = $('#text-to-type-input')[0];

  if (completedRaces.unshift(`
  <tr>
    <td>${currentText.length}</td>
    <td>${Math.round(textInput.value.length * 120 / (((+new Date())-startTime)/1e3))/10}</td>
    <td>${Math.round(1e3-typingMistakes/currentText.length*1e3)/10}%</td>
  </tr>
  `) > 5) {
    completedRaces.pop();
  }

  $('#completed-races-body').html(completedRaces.join(''))

}

</script>
@stop
