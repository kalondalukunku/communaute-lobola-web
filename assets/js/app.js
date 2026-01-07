function copyToClipboard(text) {
	const el = document.createElement('textarea');
	el.value = text;
	document.body.appendChild(el);
	el.select();
	document.execCommand('copy');
	document.body.removeChild(el);
	
	// Notification visuelle simple
	const btn = event.currentTarget;
	const originalText = btn.innerHTML;
	btn.innerHTML = '<i class="fas fa-check"></i> Copié !';
	setTimeout(() => {
		btn.innerHTML = originalText;
	}, 2000);
}