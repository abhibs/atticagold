
class Quot{
	constructor(){
		this.form = document.getElementById("quot-form");
		this.display_message = document.getElementById("submit-message");
	}
	display_message_div(text, type){
		this.display_message.innerHTML = "";
		const div = document.createElement("div");
		div.setAttribute("role", "alert");
		if(type == "success"){
			div.classList.add("alert", "alert-success", "alert-dismissible", "fade", "show");
		}
		else if(type == "error"){
			div.classList.add("alert", "alert-danger", "alert-dismissible", "fade", "show");
		}	
		div.textContent = text;
		this.display_message.append(div);
	}
	submit_form(){
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
			
			let response = await fetch('src/quot.php', {
				method: 'POST',
				body: new FormData(object.form)
			});
			
			let result = await response.json();
			
			if(result.message == "Success"){
				window.location.href = "submission.php";
			}
			else{
				this.display_message_div("Something went wrong, Please try again later", "error");
			}
			
		};
	}
}

const obj = new Quot();
obj.submit_form();