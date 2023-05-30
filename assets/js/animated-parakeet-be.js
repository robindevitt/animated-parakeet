/**
 * Animated Parakeet Backend/admin Js.
 *
 * @package AnimatedParakeet
 */
var slider = document.getElementById("animatedParakeetClose");
var output = document.getElementById("closeDisplay");

output.innerHTML = slider.value + ' seconds'; // Display the default slider value.

// Update the current slider value (each time the user drags the slider handle).
slider.oninput = function () {
	output.innerHTML = this.value + ' seconds';
}

jQuery(document).ready(function ($) {
	// Add display condition
	$('#add-condition').on('click', function (e) {
		e.preventDefault();

		var condition = $('.display-condition:last').clone();
		condition.find('select').val('');
		$('#display-conditions').append(condition);
	});

	// Remove display condition
	$(document).on('click', '.remove-condition', function (e) {
		e.preventDefault();
		if (jQuery('div.display-condition').size() > 1) {
			$(this).closest('.display-condition').remove();
		}
	});
});
