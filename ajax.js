jQuery(document).ready(function($){

	$('#submit').click(function(e){
		e.preventDefault();
		

		$.ajax({
			url: ajaxurl,
			dataType: 'json',
			data: {
				'action':'orbisius_test',
				'fullName' : $('#full-name').val()
			},
			success: function(data) {
				var output = '';
				for(var v in data){
					output += '<b>'+v+':</b> '+data[v]+'<br>';
				}

				$('#response-body').html(output);

			}
		});


	});

});