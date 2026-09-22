/**
 * Horizontal product carousel controls
 */
(function () {
	"use strict";

	function initCarousel(root) {
		var track = root.querySelector("[data-carousel-track]");
		var prev = root.querySelector("[data-carousel-prev]");
		var next = root.querySelector("[data-carousel-next]");
		if (!track) return;

		function scrollByCard(dir) {
			var card = track.querySelector(".product-card");
			var amount = card ? card.getBoundingClientRect().width + 16 : 280;
			track.scrollBy({ left: dir * amount, behavior: "smooth" });
		}

		if (prev) prev.addEventListener("click", function () { scrollByCard(-1); });
		if (next) next.addEventListener("click", function () { scrollByCard(1); });
	}

	document.addEventListener("DOMContentLoaded", function () {
		document.querySelectorAll("[data-carousel]").forEach(initCarousel);
	});
})();
