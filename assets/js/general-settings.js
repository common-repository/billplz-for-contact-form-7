document.addEventListener("DOMContentLoaded", () => {
	const copyText = document.getElementById("bcf7-shortcode");
	copyText.addEventListener("click", () => {
		const textToCopy = copyText.textContent;
		navigator.clipboard
			.writeText(textToCopy)
			.then(() => {
				copyText.textContent = "Shortcode Copied!";
				setTimeout(() => {
					copyText.textContent = textToCopy;
				}, 500);
			})
			.catch((error) => {
				console.error("Error copying text:", error);
			});
	});
});
