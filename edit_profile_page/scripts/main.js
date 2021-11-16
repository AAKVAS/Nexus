let pencilButtons = document.getElementsByClassName("pencil_button");
for (let i=0; i< pencilButtons.length; i++){
    pencilButtons[i].onclick = function(){
        let buttonType = pencilButtons[i].id;
        if(document.getElementById(buttonType + '_block')){
           return;
        }
        let inputBlock = "<div contentEditable='true' id='" + buttonType +"_block' class='comment_area'></div><input type='submit' class='nexus_button' id='" + buttonType +"_button' value='Изменить'>";
        let divBlock = document.createElement("DIV");
        divBlock.innerHTML = inputBlock;
        document.getElementById(buttonType).parentElement.appendChild(divBlock);
    }
}