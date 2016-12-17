<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="http://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
<script type="text/javascript" src="/mjohnh/js/api.js"></script>

<style type="text/css">
	#memo {
	}
	#daily_word {
		text-align:center;
		margin-top:10%;
		margin-left:25%;
		margin-right:25%;
	}
	#new_word {
		text-align:center;
		margin-left:25%;
		margin-right:25%;
		margin-bottom:10%;
	}
	label {
		font-size:2em;
	}
	span {
		font-size:1em;
	}
</style>
</head>
<body>
<title>english memo</title>
<div id="memo">
	<div id="daily_word">
			<h3>Daliy Word</h3>
			<label><?=($daily_word['word'])?></label><br>
			<?php foreach($daily_word['means'] as $mean):?>
				<span><?=($mean)?></span>
			<?php endforeach;?>
	</div>
	<hr>
	<div id="new_word">
		<?php if(isset($insert_rtn) && $insert_rtn):?>
			<div id="insert_status">
				<p><?=($insert_rtn)?></p>
			</div>
		<?php endif;?>
		<form class="main_form" id="main_form" method="post" action="//<?=($THISHOST)?>/mjohnh/engMemo/newWord" autocomplete="on">
			<h3>Create New Word</h3>
			<label>English Word</label>
			<input id="eng_word" name="input_eng_word" type="text"><br>
			<label>Chinese means</label>
			<textarea id="chi_means" name="input_chi_means" type="text"></textarea><br>
			<button id="btn_send" type="button">Submit</button>
		<form>
	</div>
</div>
<div id="fb" style="text-align:center;">
	<div id="fb">
		<fb:login-button scope="public_profile,email" onlogin="checkLoginState();" autologoutlink="true"></fb:login-button>
		<div id="status"></div>
	</div>
</div>

<script type="text/javascript">
(function($) {
  $(function() {

    $("#btn_send").click(function() {
      var eng_word = $("input[name=input_eng_word]").val();
      var chi_means = $("textarea[name=input_chi_means]").val();

      if (!eng_word || !chi_means) {
        alert('欄位不可為空');
      }
			else {
				$('#main_form').submit();
			}
    });

  });

  $("textarea[name=input_chi_means]").keydown(function (evt) {
    if (evt.keyCode === 13 && !evt.shiftKey) {
      event.preventDefault();
      $('#btn_send').trigger("click");
      return false;
    }
  }); 

  $("textarea[name=input_chi_means]").on('change keyup mousedown mouseup', function(event){
    this.value=this.value.replace(/\r?\n/g, ',');
  });
})(jQuery);
</script>

<script>
  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
		console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      testAPI();
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into Facebook.';
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

	function fbLogin() {
		FB.login(function(response) {
			if (response.authResponse) {
				console.log('Welcome!  Fetching your information.... ');
				FB.api('/me', function(response) {
					console.log('Good to see you, ' + response.name + '.');
				});
			} else {
				console.log('User cancelled login or did not fully authorize.');
			}
		});
	}

	function fbLogout() {
		FB.logout(function(response) {
			// user is now logged out
			alert('已成功登出!');
			window.location.reload();
		});
	}

  window.fbAsyncInit = function() {
  FB.init({
    appId      : '278715139167001',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.5' // use graph api version 2.5
  });

  // Now that we've initialized the JavaScript SDK, we call 
  // FB.getLoginStatus().  This function gets the state of the
  // person visiting this page and can return one of three states to
  // the callback you provide.  They can be:
  //
  // 1. Logged into your app ('connected')
  // 2. Logged into Facebook, but not your app ('not_authorized')
  // 3. Not logged into Facebook and can't tell if they are logged into
  //    your app or not.
  //
  // These three cases are handled in the callback function.

  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });

  };

  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me?fields=id,name,email,age_range,birthday', function(response) {
      console.log(response);
      console.log('Successful login for: ' + response.email);
      document.getElementById('status').innerHTML =
        'Thanks for logging in, ' + response.name + '!';
    });
  }
</script>

</body>
</html>
