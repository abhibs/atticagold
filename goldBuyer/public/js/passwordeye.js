(function() {
	"use strict";
	
	/**
		* Password Toggle (hide / show)
	*/
	const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('input[type=password]');
    
    togglePassword.addEventListener('click', () => {
        const type = password.getAttribute('type')
		if(type === "password"){
			togglePassword.classList.remove("fa-eye-slash");
			togglePassword.classList.add('fa-eye');
			password.setAttribute('type', "text");
		}
		else if(type === "text"){
			togglePassword.classList.remove("fa-eye");
			togglePassword.classList.add('fa-eye-slash');
			password.setAttribute('type', "password");
		}       
    });
	
})();