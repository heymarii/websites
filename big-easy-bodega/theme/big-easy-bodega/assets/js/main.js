/**
 * Big Easy Bodega — main front-end script
 */
(function () {
	"use strict";

	function initNav() {
		var toggle = document.querySelector("[data-nav-toggle]");
		var nav = document.querySelector("[data-nav]");
		if (!toggle || !nav) return;

		toggle.addEventListener("click", function () {
			var open = nav.classList.toggle("is-open");
			toggle.setAttribute("aria-expanded", open ? "true" : "false");
		});
	}

	function initRequestForm() {
		var form = document.querySelector("[data-request-form]");
		if (!form || typeof bebTheme === "undefined") return;

		var status = form.querySelector("[data-request-status]");
		var submit = form.querySelector('[type="submit"]');

		form.addEventListener("submit", function (event) {
			event.preventDefault();
			if (!status || !submit) return;

			var original = submit.textContent;
			submit.disabled = true;
			submit.textContent = bebTheme.i18n.sending;
			status.hidden = true;
			status.classList.remove("is-success", "is-error");

			var data = new FormData(form);

			fetch(bebTheme.ajaxUrl, {
				method: "POST",
				credentials: "same-origin",
				body: data,
			})
				.then(function (res) {
					return res.json();
				})
				.then(function (json) {
					status.hidden = false;
					if (json && json.success) {
						status.classList.add("is-success");
						status.textContent =
							(json.data && json.data.message) ||
							"Thanks — we got your request.";
						form.reset();
					} else {
						status.classList.add("is-error");
						status.textContent =
							(json && json.data && json.data.message) ||
							bebTheme.i18n.error;
					}
				})
				.catch(function () {
					status.hidden = false;
					status.classList.add("is-error");
					status.textContent = bebTheme.i18n.error;
				})
				.finally(function () {
					submit.disabled = false;
					submit.textContent = original || bebTheme.i18n.submit;
				});
		});
	}

	document.addEventListener("DOMContentLoaded", function () {
		initNav();
		initRequestForm();
	});
})();
