@extends('layouts.app')

@section('title', '| Home')


@section('header')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@stop


@section('content')
<div class="row">
  <div class="col-md-8 offset-md-2 card">
    <div class="card-body">
      <table>
        <tbody>
          <tr style="font-size: 24px;" title="ANY random text from the database">
            <td><i class="fas fa-keyboard"></i></td>
            <td><a href="#" id="load-text-rnd">>> Random <<</a></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
   
  <div class="col-md-8 offset-md-2 card" id="scribo-box">
    <div class="card-body"><h2>
      <div>
        <span id="text-correct"></span><span id="text-next-char"></span><span id="text-wrong"></span><span id="text-to-type"></span>
      </div>
      <input type="text" id="text-to-type-input" class="form-control" title="Empty me to reset the timer.">
    </h2></div>
  </div>
</div>
@endsection

@section('footer')
<script>
let resultLength, inputLen = 0, startTime = +new Date(), currentText = 'test', fetchStatus = false, typingMistakes = 0, typingCorrect = true;
$(document).ready(function(){
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
		}
	});
  $('#text-to-type-input')[0].value = null;

	$('#load-text-rnd')[0].onclick = function(){
    fetchText();
	}
  
	$('#text-to-type-input')[0].addEventListener('keydown', txtInputChange);
	$('#text-to-type-input')[0].addEventListener('keyup', txtInputChange);
  function txtInputChange(e) {
    if (e.target.value.length < 1) {
      //reset timer and mistake counter
      startTime = +new Date();
      typingMistakes = 0;
    } else  if (e.target.value.length > inputLen+2) {
      alert('no copy pasting, please');
      e.target.value = '';
    }
    inputLen = e.target.value.length;

    refreshText(currentText.text);
	};

});

function refreshText(text) {
  
  let textInput     = $('#text-to-type-input')[0];
  let textCorrect   = $('#text-correct')[0];
  let textNextChar  = $('#text-next-char')[0];
  let textWrong     = $('#text-wrong')[0];
  let textToType    = $('#text-to-type')[0];
  let scriboBox     = $('#scribo-box')[0];

  if (text.indexOf(textInput.value) === 0) { //imput congruent with text
    
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
        setTimeout(function(){
          fetchText();
        },1e3)
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

function fetchText() {
  $.ajax({
    url: `/ajax/text`,
    method: 'get',
    success: function(result){
      fetchStatus = false;
      console.log(result);
      resultLength = result.length;
      currentText = result;
      $('#text-to-type-input')[0].value = null;
      refreshText(result.text);
    },
    error: function(jqxhr, status, exception) {
      console.log(jqxhr);
      console.log('Exception:', exception);
      console.log(status);
    }
  });
}

function storeRace(){
  let textInput     = $('#text-to-type-input')[0];
  $.ajax({
    url: `/race`,
    method: 'post',
    data: {
      text_id: currentText.id,
      speed: textInput.value.length * 12 / (((+new Date())-startTime)/1e3),
      accuracy: Math.min(1 - (0,typingMistakes / currentText.text.length)),
      time_taken: (((+new Date())-startTime)/1e3),
    },
    success: function(result){
      console.log(result);
    },
    error: function(jqxhr, status, exception) {
      console.log(jqxhr);
      console.log('Exception:', exception);
      console.log(status);
    }
  });
}

</script>
@stop