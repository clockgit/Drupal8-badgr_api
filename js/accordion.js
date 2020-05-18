jQuery(document).ready(function(){
  jQuery(".accordion > .form-group > fieldset > legend, .accordion > .form-group > fieldset > label").addClass('card-header');
  jQuery(".accordion").children().addClass('card');
  // jQuery(".accordion > .form-group > fieldset").addClass('card');
  jQuery(".accordion > .form-group > fieldset > .fieldset-wrapper, .accordion > .form-group > fieldset > input").addClass('card-body');
  jQuery(".accordion .card .card-body").each(function( index, element ){
    if(index > 0) {
      jQuery(element).hide();
    }
  });

  jQuery(".accordion .card .card-header").click(function() {
    jQuery(this).closest('.accordion').find('.card-body').hide();
    jQuery(this).closest('.card').find('.card-body').show();
  });
});