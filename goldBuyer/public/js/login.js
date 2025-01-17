
class Login{
	constructor(){
		this.login_form = document.getElementById("login-form");
		this.error_message = document.getElementById("error-message");
	}
	display_error_message(text){
		const div = document.createElement("div");
		div.classList.add("alert", "alert-danger", "alert-dismissible", "fade", "show")
		div.setAttribute("role", "alert")
		div.innerHTML = text;
		return div;
	}
	submit_form(){
		const object = this;
		this.login_form.onsubmit = async (e)=>{
			e.preventDefault();
			
			const input = object.login_form.querySelectorAll('input[name]');
			let flag = true;
			input.forEach((item)=>{
				if(item.value == ""){
					flag = false;
				}
			});
			if(!flag) return false;
			
			let response = await fetch('src/login.php', {
				method: 'POST',
				body: new FormData(object.login_form)
			});
			
			let result = await response.json();
			
			if(result.message == "Valid"){
				window.location.href = "dashboard.php";
			}
			else if(result.message == "Invalid"){
				this.error_message.innerHTML = "";
				this.error_message.append(this.display_error_message("Mobile number or password is Incorrect"));
			}
			else{
				this.error_message.innerHTML = "";
				this.error_message.append(this.display_error_message("Something went wrong, Please try again later"));
			}
			
		};
	}
}

const obj = new Login();
obj.submit_form();
