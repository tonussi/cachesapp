/**
 * @deprecated
 * @param str
 */
function mostraResultadoCache(str) {
  if (str.length == 0) {
    document.getElementById("resultadoTamanhoCache").innerHTML = "";
    return;
  } else {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        document.getElementById("resultadoTamanhoCache").innerHTML = xmlhttp.responseText;
      }
    }
    xmlhttp.open("GET", "gethint.php?q=" + str, true);
    xmlhttp.send();
  }
}

$(function() {
  // Get the form.
  var form = $('#ajax-cache-config');

  // Get the messages div.
  var formMessages = $('#form-messages');

  // Set up an event listener for the contact form.
  $(form)
    .submit(
      function(event) {
        // Stop the browser from submitting the form.
        event.preventDefault();

        // Serialize the form data.
        var formData = $(form).serialize();

        // Submit the form using AJAX.
        $
          .ajax({
            type: 'POST',
            url: $(form).attr('action'),
            data: formData
          })
          .done(function(response) {
            // Make sure that the formMessages div has
            // the 'success' class.
            $(formMessages).removeClass('error');
            $(formMessages).addClass('success');

            // Set the message text.
            $(formMessages).text(response);

            // // Clear the form.
            // $('#address_size_bits').val('');
            // $('#offset_size_bits').val('');
            // $('#index_size_bits').val('');
            // $('#tag_size_bits').val('');

          })
          .fail(
            function(data) {
              // Make sure that the formMessages
              // div has the 'error' class.
              $(formMessages).removeClass(
                'success');
              $(formMessages).addClass('error');

              // Set the message text.
              if (data.responseText !== '') {
                $(formMessages).text(
                  data.responseText);
              } else {
                $(formMessages)
                  .text(
                    'Desculpe, aconteceu um erro e sua mensagem falhou.');
              }
            });
      });
});