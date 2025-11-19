(function ($) {
	
	"use strict";

	// Page loading animation
	$(window).on('load', function() {

        $('#js-preloader').addClass('loaded');

    });
	
	// input upload courrier
	const fileUpload = document.getElementById('fileUpload');
	const fileNameDisplay = document.getElementById('fileName');

	if(fileUpload && fileNameDisplay)
	{
		fileSvg.classList.add('text-secondary');

		fileUpload.addEventListener('change', function () 
		{
			if(fileUpload.files.length > 0) 
			{
				fileNameDisplay.textContent = fileUpload.files[0].name;
				fileSvg.classList.remove('text-secondary');
				fileSvg.classList.add('text-info');
			}
		});
	}

	//changed_state_inputs
	const categorySelect = document.getElementById('selectCategory');
	const typeElement = document.getElementById('typeElement');

	if(categorySelect && typeElement)
	{
		categorySelect.addEventListener('change', () => {
		
			if(categorySelect.value === 'sortant') 
			{
				typeElement.style.display = 'flex';
			}
			else
			{
				typeElement.style.display = 'none';
			}
		});
	}

	// redirection page couriste (1min)
	const path = window.location.pathname;
	const segments = path.split('/').filter(Boolean);

	const isCouristeTimer = segments.includes("couriste") && segments[segments.indexOf("couriste") + 1] === "show";
	const isSuperviseurTimer = window.location.pathname.endsWith('superviseur');
	const isLoginTimer = window.location.pathname.endsWith('login');
	const isIntrusionTimer = window.location.pathname.endsWith('intrusion');
	

	if(isCouristeTimer)
	{
		setTimeout(function() {
			window.location.href = "/courier/create";
		}, 60000);
	}
	if(isSuperviseurTimer)
	{
	setTimeout(function() {
			window.location.href = "logout";
		}, 1800000); 
	}
	if(isSuperviseurTimer === false && isCouristeTimer === false && isLoginTimer !== true && isIntrusionTimer !== true)
	{
		setTimeout(function() {
			window.location.href = "/logout";
		}, 300000);
	}
})(window.jQuery);