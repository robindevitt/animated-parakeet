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
