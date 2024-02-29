// Set body text to small
$('body').addClass('text-sm');

//Resolve conflict in jQuery UI tooltip with Bootstrap tooltip
$.widget.bridge('uibutton', $.ui.button);

// Enable the search box on jquery Select2 to be editable when using it in a bootstrap modal
// Solution from https://stackoverflow.com/questions/18487056/select2-doesnt-work-when-embedded-in-a-bootstrap-modal
$.fn.modal.Constructor.prototype._enforceFocus = function() {};

// App loading indicator
const loader = '<h6 class="text-center spinner"> <i class="fas fa-circle-notch fa-spin"></i> </h6>';

