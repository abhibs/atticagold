

class Register{

	constructor(webcam){
		this.root = document.getElementById("root");
		this.webcam = webcam;
	}

	isLogged(){
		const branchInfo = localStorage.getItem("branchInfo");
		if(branchInfo){
			this.register();
		}
		else{
			localStorage.removeItem("branchInfo");
			this.login();
		}
	}

	getBranchInfo(){
		const branchInfo = localStorage.getItem("branchInfo");
		return {
			branchId : JSON.parse(branchInfo).branchId,
			branchName : JSON.parse(branchInfo).branchName
		}
	}

	login(){

		this.root.innerHTML = "";
		this.root.innerHTML = this.loginContent();

		const loginForm = this.root.querySelector("#loginForm");
		const submitButton = this.root.querySelector("button[type='submit']");

		function disableSubmit(){
			submitButton.disabled = true;
			submitButton.innerHTML = '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>';
		}
		function enableSubmit(){
			submitButton.removeAttribute('disabled');
			submitButton.innerHTML = 'Login';
		}

		loginForm.onsubmit = async (e)=>{
			e.preventDefault();

			const branchId = loginForm.branchId.value.toUpperCase();

			if(branchId !== ""){
				disableSubmit();

				const url = "src/login.php?branchId="+branchId;
				const response = await fetch(url);
				const result = await response.json();

				if(result.status == true){
					const data = {
						branchId: branchId,
						branchName: result.branchName
					}
					localStorage.setItem("branchInfo", JSON.stringify(data));
					this.register();
				}
				else{
					enableSubmit();
					localStorage.removeItem("branchInfo");
					alert("Please provide proper Branch ID");
				}
			}
			else{
				alert("Branch Id is required");
			}
		}
	}

	register(){

		this.root.innerHTML = "";
		this.root.innerHTML = this.registrationContent();

		const registerForm = this.root.querySelector("#registerForm");
		const formSubmitButton = this.root.querySelector("#register-submit-button");
		const submitButton = this.root.querySelector("#register-submit-button");

		const branchNameDisplay = this.root.querySelector(".branchNameDisplay")
		branchNameDisplay.textContent = this.getBranchInfo().branchName;
		this.logout(branchNameDisplay);

		this.webcam.attach(this.root.querySelector("#my_camera"));

		function isMobileValid(mobile){
			const sMobile = mobile.replace(/[^0-9]/g, "");
			let len = sMobile.length;
			return (len == 10);
		}
		function disableSubmit(){
			submitButton.disabled = true;
			submitButton.innerHTML = '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>';
		}
		function enableSubmit(){
			submitButton.removeAttribute('disabled');
			submitButton.innerHTML = 'Register';
		}

		registerForm.onsubmit = async (e) => {
			e.preventDefault();

			const formData = new FormData();
			formData.append("name", registerForm.customerName.value);
			formData.append("mobile", registerForm.customerMobile.value);
			formData.append("type", registerForm.customerType.value);
			formData.append("grossW", registerForm.customerGrossW.value);
			// formData.append("language",registerForm.language.value);
			formData.append("imageFile", registerForm.customerImage.value);
			formData.append("branchId", this.getBranchInfo().branchId);

			if(!isMobileValid(registerForm.customerMobile.value)){
				alert("Invalid Mobile Number provided");
			}
			// else if(registerForm.customerImage.value == ""){
				// alert("Image Not Captured");
			// }
			// else if(isMobileValid(registerForm.customerMobile.value) && registerForm.customerImage.value !== ""){
			else if(isMobileValid(registerForm.customerMobile.value)){
				disableSubmit();

				const response = await fetch("src/register.php", {
					method: "POST",
					body: formData
				})
				const result = await response.json();
				if(result.status == true){
					this.success();
				}
				else{
					enableSubmit();
					alert("Something went wrong please try again later");
				}
			}
		}
	}

	success(){
		this.root.innerHTML = "";
		this.root.innerHTML = this.successContent();

		this.root.querySelector(".branchNameDisplay").textContent = this.getBranchInfo().branchName;

		const timerDisplay = this.root.querySelector("#timerDisplay");
		let countDown = 11;

		const timer = setInterval(()=>{
			countDown -= 1;
			timerDisplay.textContent = countDown;
			if(countDown == 0){
				clearInterval(timer);
				this.register();
			}
		}, 1000);
	}

	logout(btn){
		btn.addEventListener("click", ()=>{
			const response = confirm("Do you want to logout?");
			if(response){
				localStorage.removeItem("branchInfo");
				this.login();
			}
		})
	}

	loginContent(){
		const content = '<div class="login-container">'
		+ '<div class="row">'
		+ '<div class="col-md-12">'
		+ '<div class="text-center m-b-md">'
		+ '<div class="panel-heading text-center" style="margin-bottom: 20px;">'
		+ '<h1 class="text-center heading-text font-extra-bold">'
		+ 'Attica Gold'
		+ '</h1>'
		+ '</div>'
		+ '</div>'
		+ '<div class="hpanel">'
		+ '<div class="panel-body">'
		+ '<form id="loginForm">'
		+ '<div class="form-group">'
		+ '<label for="branchId" class="form-label">Branch Id</label>'
		+ '<input type="text" class="form-control" id="branchId" name="branchId" placeholder="AGPL***" required style="text-transform: uppercase;">'
		+ '</div>'
		+ '<button type="submit" class="btn btn-design btn-block">Login</button>'
		+ '</form>'
		+ '</div>'
		+ '</div>'
		+ '</div>'
		+ '</div>'
		+ '</div>';
		return content;
	}

	registrationContent(){
		const content = '<div id="wrapper" style="margin: 0px;">'
		+ '<div class="content">'
		+ '<div class="row">'
		+ '<div class="col-lg-12">'
		+ '<div class="hpanel">'
		+ '<div class="panel-heading" style="margin-bottom: 5px;">'
		+ '<h1 class="heading-text font-extra-bold">'
		+ '<span>Attica Gold</span>'
		+ '<small class="branchNameDisplay" style="float: right;"></small>'
		+ '</h1>'
		+ '</div>'
		+ '<div class="panel-heading bg-color text-center" style="color: #ffc107; border-bottom: 3px solid #fff; font-size: 15px;">'
		+ 'CUSTOMER REGISTRATION FORM'
		+ '</div>'
		+ '<div class="panel-body">'
		+ '<form id="registerForm">'
		+ '<div class="col-lg-3 text-center">'
		+ '<div id="results" style="position:absolute;"></div>'
		+ '<div id="my_camera" style="margin: auto;"></div>'
		+ '<p class="small m-t-md">'
		+ '<a onClick="take_snapshot()" class="btn btn-default btn-design"><i class="fa fa-camera"></i> Capture Image</a>'
		+ '<input type="hidden" name="customerImage" id="customerImage" class="image-tag" required>'
		+ '</p>'
		+ '</div>'
		+ '<div class="col-lg-9">'
		+ '<div class="row">'
		+ '<div class="form-group col-lg-12">'
		+ '<label for="customerName" class="form-label">Name</label>'
		+ '<input type="text" class="form-control" id="customerName" name="customerName" placeholder="Your Name" required>'
		+ '</div>'
		+ '<div class="form-group col-lg-6">'
		+ '<label for="customerMobile" class="form-label">Mobile Number</label>'
		+ '<input type="number" class="form-control" id="customerMobile" name="customerMobile" placeholder="" required maxlength="10" pattern="[0-9]{10}"'
		+ 'oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">'
		+ '</div>'
		+ '<div class="form-group col-lg-6">'
		+ '<label for="customerType" class="form-label">Type</label>'
		+ '<select name="customerType" id="customerType" class="form-control" aria-label="Default select example" required>'
		+ '<option selected="true" disabled="disabled" value="">Type</option>'
		+ '<option value="physical">Physical</option>'
		+ '<option value="release">Release</option>'
		+ '</select>'
		+ '</div>'
		+ '<div class="form-group col-lg-6">'
		+ '<label for="customerGrossW" class="form-label">Gross Weight</label>'
		+ '<input type="number" class="form-control" id="customerGrossW" name="customerGrossW" placeholder="Gross Weight" required min="0" max="10000" step="0.001">'
		+ '</div>'
		// + '<div class="form-group col-lg-6">'
		// + '<label for="language" class="form-label">Preferred Language</label>'
		// + '<select name="language" id="language" class="form-control" aria-label="Default select example" required>'
		// + '<option selected="true" disabled="disabled" value="">Language</option>'
		// + '<option value="English">English</option>'
		// + '<option value="Hindi">Hindi</option>'
		// + '<option value="Kannada">Kannada</option>'
		// + '<option value="Tamil">Tamil</option>'
		// + '<option value="Telugu">Telugu</option>'
		// + '</select>'
		// + '</div>'
		+ '</div>'
		+ '</div>'
		+ '<div class="text-right m-t-xs">'
		+ '<button type="submit" class="btn btn-design" id="register-submit-button" style="padding-right: 50px; padding-left: 50px; margin-top: 40px">Register</button>'
		+ '</div>'
		+ '</form>'
		+ '</div>'
		+ '</div>'
		+ '</div>'
		+ '</div>'
		+ '</div>'
		+ '</div>';
		return content;
	}

	successContent(){
		const content = '<div id="wrapper" style="margin: 0px;">'
		+ '<div class="content">'
		+ '<div class="row">'
		+ '<div class="col-lg-12">'
		+ '<div class="hpanel">'
		+ '<div class="panel-heading " style="margin-bottom: 5px;">'
		+ '<h1 class="heading-text font-extra-bold">'
		+ 'Attica Gold'
		+ '<small class="branchNameDisplay" style="float: right;"></small>'
		+ '</h1>'
		+ '</div>'
		+ '<div class="panel-body">'
		+ '<div class="row text-center m-t-lg m-b-lg">'
		+ '<div class="col-lg-12">'
		+ '<i class="pe-7s-check fa-5x text-muted" style="color: #ffc107;"></i>'
		+ '<h1>Successfully Registered</h1>'
		+ '<br>'
		+ '<p class="m-t-md text-center">'
		+ '<a class="text-warning" href="./"><i class="fn pe-7s-back"></i> Go back to Registration</a>'
		+ '</p>'
		+ '</div>'
		+ '<div class="checkbox col-lg-12">'
		+ '<p class="text-center">You will be automatically redirected to registration page in <span id="timerDisplay"></span> sec</p>'
		+ '</div>'
		+ '</div>'
		+ '</div>'
		+ '</div>'
		+ '</div>'
		+ '</div>'
		+ '</div>'
		+ '</div>';
		return content;
	}
}

const reg = new Register(Webcam);
reg.isLogged();
