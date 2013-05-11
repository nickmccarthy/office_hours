$(document).ready(function(){

	$('#search_terms').keyup(findCompletedValue);

	function findCompletedValue(){
		var termval = $('#search_terms').val();
		var mydata = {terms: termval};
		request = $.ajax({
			url: "autocomplete.php",
			type: "get",
			data: mydata
		});

		request.done(suggestValues);
	}

	function suggestValues(availableSearches){
		$('.testing').html(availableSearches);
		/*$('#search_terms').autocomplete({
			source: availableSearches
		});*/
	}
});