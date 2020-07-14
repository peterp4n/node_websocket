<!DOCTYPE HTML>
<html>
<head><title></title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
<!-- Bootstrap -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://code.jquery.com/jquery-migrate-1.4.1.min.js"></script>
<script src="http://server_ip:server_port/socket.io/socket.io.js"></script>
<script>
	var socket = io.connect('http://server_ip:server_port/'); 

	socket.on('connect', function(){
		//name = 'test<?=rand(0, 9);?>';
		name = prompt('대화명을 입력해주세요.', '');
		socket.emit('addUser', name);
	});
	socket.on('updateChat', function (username, data) {
		$('#conversation').append('<b>'+username + ':</b> ' + data + '<br>');
		$('#conversation').scrollTop($('#conversation')[0].scrollHeight);
	});

	socket.on('updateUser', function(data) {
		$('#users').empty();
		$.each(data, function(key, value) {
			$('#users').append('<li><i class="icon-search icon-user"></i>&nbsp;' + key + '</li>');
		});
	});

</script>
</head>
<body style="margin:0">

<div class="span4">
	<div style="width:400px; height:400px; overflow-y:scroll;" id="conversation"></div>
	<div class="input-append">
		<input class="span4" id="data" type="text" />
		<button class="btn" id="datasend" type="button">Send</button>
	</div>
</div>
<script>

	$('#datasend').click( function() {
		var message = $('#data').val();
		$('#data').val('').focus();
		socket.emit('sendChat', message);
	});

	$('#data').keypress(function(e) {
		if(e.which == 13) {
			$(this).blur();
			$('#datasend').focus().click();
		}
	});
</script>
</body>
</html>