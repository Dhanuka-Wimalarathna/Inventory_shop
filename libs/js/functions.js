function suggetion() {

    // Trigger on keyup in the suggestion input field
    $('#sug_input').keyup(function(e) {
  
      // Capture the input value for the product name
      var formData = {
        'product_name' : $('input[name=title]').val()
      };
  
      // Check if the input length is at least 1 character
      if(formData['product_name'].length >= 1){
  
        // Make an AJAX POST request to fetch suggestions
        $.ajax({
          type        : 'POST',
          url         : 'ajax.php',
          data        : formData,
          dataType    : 'json',
          encode      : true
        })
        .done(function(data) {
          // Display the suggestions in the result dropdown
          $('#result').html(data).fadeIn();
          
          // Handle click on a suggestion to fill the input
          $('#result li').click(function() {
            $('#sug_input').val($(this).text());
            $('#result').fadeOut(500);
          });
  
          // Hide the result dropdown when input loses focus
          $("#sug_input").blur(function(){
            $("#result").fadeOut(500);
          });
  
        });
  
      } else {
        // Hide the result dropdown if input length is less than 1
        $("#result").hide();
      };
  
      e.preventDefault();
    });
  }
  
  $('#sug-form').submit(function(e) {
    // Capture the input value for form submission
    var formData = {
      'p_name' : $('input[name=title]').val()
    };
  
    // Make an AJAX POST request to submit the form
    $.ajax({
      type        : 'POST',
      url         : 'ajax.php',
      data        : formData,
      dataType    : 'json',
      encode      : true
    })
    .done(function(data) {
      // Display product information and update total
      $('#product_info').html(data).show();
      total();
      $('.datePicker').datepicker('update', new Date());
  
    }).fail(function() {
      // Handle failure by displaying product information
      $('#product_info').html(data).show();
    });
  
    e.preventDefault();
  });
  
  function total(){
    // Calculate total based on price and quantity inputs
    $('#product_info input').change(function(e) {
      var price = +$('input[name=price]').val() || 0;
      var qty   = +$('input[name=quantity]').val() || 0;
      var total = qty * price ;
      $('input[name=total]').val(total.toFixed(2));
    });
  }
  
  $(document).ready(function() {
  
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
  
    // Toggle submenu visibility
    $('.submenu-toggle').click(function () {
      $(this).parent().children('ul.submenu').toggle(200);
    });
  
    // Initialize product name suggestion functionality
    suggetion();
  
    // Initialize total calculation functionality
    total();
  
    // Initialize datepicker
    $('.datepicker').datepicker({
      format: 'yyyy-mm-dd',
      todayHighlight: true,
      autoclose: true
    });
  });
  