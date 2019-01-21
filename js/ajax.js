jQuery(document).ready(function($) {
  $('#submit').click(function(e) {
    e.preventDefault();

    var fullName = $('#full-name').val();

    if ( ! fullName ) {
      alert('Full Name must not be empty');
      return;
    }

    $.ajax({
      url: orbisius.ajax_url,
      data: {
        'action': 'orbisius_test',
        'fullName': $('#full-name').val(),
        'nonce': orbisius.nonce,
      },
      success: function(data) {
        var output = '';
        var json_data = JSON.parse(data);
        for (var v in json_data) {
          output += '<b>' + v+ ':</b> ' + json_data[v] + '<br>';
        }

        $('#response-body').html(output);
      },
      error: function(error) {
        console.log(error);
      },
    });
  });
});
