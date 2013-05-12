$(document).ready(function(){
	
	//toggle subscribe div
	$('.subscribe').hide();
	$('.join_class').hide();

	$('.sub_btn').click(function(){
		var id = event.target.id;
		var lookup = '#' + id + '.subscribe';
		if($(lookup).is(':hidden')){
			$('.subscribe').hide("slow");
			$('.join_class').hide("slow");
		}
		$(lookup).toggle("slow");
	})

	$('.join_btn').click(function(){
		var id = event.target.id;
		var lookup = '#' + id + '.join_class';
		if($(lookup).is(':hidden')){
			$('.join_class').hide("slow");
			$('.subscribe').hide("slow");
		}
		$(lookup).toggle("slow");
	})

	$('.sub_submit').click(subscribeEmail);
	$('.join_submit').click(joinClass);
	
	function subscribeEmail(){
		var emailval = $('#email').val();
		var cidval = $(this).parent().attr("id");
		var mydata = {sub_email : emailval,
						cid: cidval};
		request = $.ajax({
			url: "add_email.php",
			type: "get",
			data: mydata
		})

		request.done(displaySubscribe);

	}

	function joinClass(){
		var pos = $('#position').val();
		var cidval = $(this).parent().attr("id");
		var mydata = {position: pos,
						cid: cidval};
		request = $.ajax({
			url: "add_email.php",
			type: "get",
			data:mydata
		})

		request.done(displayPosition);
	}

	function displaySubscribe(response){
		if(response == 'failed'){
			$('#email').val('Invalid Email!');
		}
		if(response == 'there'){
			$('#email').val('Already Signed Up!');
		}
		if(response == 'not there'){
			$('#email').hide("slow");
			$('.sub_submit').html('Done!');
		}
	}

	function displayPosition(response){
		if(response == 'failed'){
			$('#position').val('Invalid Position!');
		}
		if(response == 'there'){
			$('#position').val('Already signed up!');
		}
		if(response == 'not there'){
			$('#position').hide("slow");
			$('.join_submit').html('Done!');
		}
	}
})

