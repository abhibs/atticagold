

class Register{
	constructor(){
		this.http = new XMLHttpRequest();
		
		this.name_input = document.querySelector("#regName");
		this.mobile_input = document.querySelector("#regMobile");
		this.mobile_number_feedback = document.getElementById("mobile-number-feedback");
		this.submit_div = document.querySelector("#submit-div");
		
		this.form = document.querySelector("#register-form");
		
		this.otp_button = document.querySelector("#regOTPButton");
		this.otp_input = document.querySelector("#regOTP");
		
		this.ten_digit_number_regex = /^\d{10}$/;
		this.six_digit_number_regex = /^[0-9]+$/;	
		
	}
	
	create_spinnin_button(){
		const span_spinning = document.createElement("span");
		span_spinning.classList.add("spinner-border");
		span_spinning.classList.add("spinner-border-sm");
		span_spinning.setAttribute("role", "status")
		span_spinning.setAttribute("aria-hidden", "true");
		return span_spinning;
	}
	create_spinning_neighbour_text(text){
		const span_sending = document.createElement("span");
		span_sending.textContent = text;
		return span_sending;
	}
	create_submit_button(){
		const submit_button = document.createElement("button");
		submit_button.setAttribute("type", "submit");		
		submit_button.classList.add("btn", "btn-primary", "form-submit-btn");		
		submit_button.innerHTML = "Submit";
		return submit_button;
	}
	
	check_mobile_number(mobile){
		return (this.ten_digit_number_regex.test(mobile));
	}
	
	resend_otp_button(){
		this.otp_button.removeAttribute("disabled");
		this.otp_button.innerHTML = "Resend OTP";
	}
	clicked_otp_button(){
		this.otp_button.disabled = "true";
		this.otp_button.innerHTML = "";
		this.otp_button.appendChild(this.create_spinnin_button());
		this.otp_button.appendChild(this.create_spinning_neighbour_text(" Sending OTP"));
	}
	
	send_otp(){
		const object = this;
		this.otp_button.addEventListener("click", (event)=>{
			let name = this.name_input.value;
			let mobile = this.mobile_input.value;
			if(this.check_mobile_number(mobile)){
				this.mobile_number_feedback.style.display = "none";
				
				this.otp_button.disabled = "true";
				this.otp_button.innerHTML = "";
				this.otp_button.appendChild(this.create_spinnin_button());
				this.otp_button.appendChild(this.create_spinning_neighbour_text());
				
				this.http.open("POST", "../ot.php");
				this.http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
				this.http.onload = function() {
					alert(`OTP is sent to your Mobile`);
					object.resend_otp_button();
					object.otp_input.removeAttribute("disabled");
					object.otp_input.value = "";
				};
				this.http.onerror = function() { 
					alert(`Network Error, Resend OTP`);
					object.resend_otp_button();
				};
				this.http.send(`name=${name}&data=${mobile}`);
			}
			else{
				alert("Enter 10 Digit Mobile Number");
				this.mobile_number_feedback.style.display = "block";
			}
		});
	}
	
	otp_sanitizer(){
		this.otp_input.addEventListener("keydown", (e)=>{
			let key = e.key;
			let regex = new RegExp(this.six_digit_number_regex);
			if(!regex.test(key)){
				if(key != "Backspace"){
					event.preventDefault();
					return false;
				}		
			}
		});
	}
	
	validate_otp(){
		const object = this;
		this.otp_input.addEventListener("input", (e)=>{
			let otp_value = this.otp_input.value;
			let count = otp_value.toString().length;
			if(count == 6){
				this.http.open("POST", "../otpValid.php");
				this.http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
				this.http.onload = function() {
					let response = object.http.response;
					if(response == 'OTP Validated'){
						object.otp_input.value = response
						object.otp_input.disabled = "true";
						
						object.submit_div.innerHTML = "";
						object.submit_div.append(object.create_submit_button());
						
						object.otp_button.disabled = "true";
					}
					else if(response == 'Invalid OTP'){
						object.otp_input.value = response
						object.otp_input.disabled = "true";
					}			
				};
				this.http.onerror = function() { 
					alert(`Network Error, Resend OTP`);
					object.resend_otp_button();
				};
				this.http.send(`data=${otp_value}`);
			}
		});
	}
	
	form_submit(){
		const object = this;
		this.form.onsubmit = async (e)=>{
			e.preventDefault();
			
			const input = object.form.querySelectorAll('input[name]');
			let flag = true;
			input.forEach((item)=>{
				if(item.value == ""){
					flag = false;
				}
			});
			if(!flag) return false;
			
			this.submit_div.innerHTML = "";
			this.submit_div.append(object.create_spinnin_button());
			
			let response = await fetch('src/signup.php', {
				method: 'POST',
				body: new FormData(object.form)
			});
			
			let result = await response.json();
			
			if(result.message == "Successfull"){
				alert("You are succesfully registered");
				window.location.href = "index.php";
			}
			else if(result.message == "Existing"){
				alert("Account already exists with this Mobile number, Refresh the page and try again with different Mobile number");
				this.submit_div.innerHTML = "";
				this.submit_div.append(object.create_submit_button());
			}
			else if(result.message == "Error"){				
				alert("Something went wrong, please try again later.");
				this.submit_div.innerHTML = "";
				this.submit_div.append(object.create_submit_button());
			}			
		};
	}
}

const obj = new Register();
obj.send_otp();
obj.otp_sanitizer();
obj.validate_otp();
obj.form_submit();
