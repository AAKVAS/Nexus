let pencilButtons = document.getElementsByClassName("pencil_button");
for (let i=0; i< pencilButtons.length; i++){
    pencilButtons[i].onclick = function(){
        let buttonType = pencilButtons[i].id;
        if(document.getElementById(buttonType + '_block')){
           return;
        }
        let isAccess = true;

        if(buttonType==='edit_email' || buttonType==='edit_password'){
            isAccess=false;
            let modal = document.getElementById("modal");
            modal.style.display = "block";
            let crossButton = document.getElementById("cross_img");
            crossButton.onclick = function (){
                let Modal = document.getElementById("modal");
                Modal.style.display = "none";
            }

            let confirmPassword = document.getElementById("submit_password");
            confirmPassword.onclick = function (){
                let editor = document.getElementById("editor");
                let password = editor.value;
                $.ajax({
                    url: 'scripts/checkPassword.php',
                    method: 'post',
                    dataType: 'html',
                    data: {password: password},
                    success: function(data){
                        isAccess=data;
                        if(data){
                            document.getElementById("modal").style.display = "none";
                            let inputBlock = "<div contentEditable='true' id='" + buttonType +"_block' class='comment_area'></div>" +
                                "<input type='submit' class='nexus_button submit_button' id='" + buttonType +"_button' value='Изменить'>";
                            let divBlock = document.createElement("DIV");
                            divBlock.innerHTML = inputBlock;
                            document.getElementById(buttonType).parentElement.appendChild(divBlock);

                            let submitButton = document.getElementById(buttonType + "_button");
                            submitButton.onclick = function (){
                                let newValue = document.getElementById(buttonType + "_block").innerText;

                                $.ajax({
                                    url: 'scripts/editProfile.php',
                                    method: 'post',
                                    dataType: 'html',
                                    data: {newValue: newValue, buttonType: buttonType},
                                    success: function(data){
                                    }
                                });
                            };
                        }
                    }
                });
            }
        }
        if(!isAccess){
            return;
        }

        let inputBlock = "<div contentEditable='true' id='" + buttonType +"_block' class='comment_area'></div>" +
            "<input type='submit' class='nexus_button submit_button' id='" + buttonType +"_button' value='Изменить'>";
        let divBlock = document.createElement("DIV");
        divBlock.innerHTML = inputBlock;
        document.getElementById(buttonType).parentElement.appendChild(divBlock);
        let submitButton = document.getElementById(buttonType + "_button");
        submitButton.onclick = function (){
            let newValue = document.getElementById(buttonType + "_block").innerText;
            $.ajax({
                url: 'scripts/editProfile.php',
                method: 'post',
                dataType: 'html',
                data: {newValue: newValue, buttonType: buttonType},
                success: function(data){

                }
            });
        };
    }
}

