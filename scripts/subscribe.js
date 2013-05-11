$(document).ready(function(){
	
	//toggle subscribe div
	$('.subscribe').hide();

	$('.sub_btn').click(function(){
		var id = event.target.id;
		var lookup = '#' + id + '.subscribe';
		$(lookup).toggle("slow");
	})

	$('.sub_submit').click(subscribeEmail);
	
	function subscribeEmail(){
		var emailval = $('#email').val();
		var cidval = $(this).parent().parent().attr("id");
		var mydata = {sub_email : emailval,
						cid: cidval};
		request = $.ajax({
			url: "add_email.php",
			type: "get",
			data: mydata
		})

		request.done(displaySubscribe);

	}

	function displaySubscribe(response){
		if(response == 'failed'){
			$('#email').val('Invalid Email!');
		}
		if(response == 'there'){
			$('#email').val('Already Signed Up!');
		}
		if(response == 'not there'){
			$('#email').hide();
			$('.sub_submit').html('Signed Up!');
		}
	}
})

