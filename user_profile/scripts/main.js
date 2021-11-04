
let ShareButton = document.getElementById("share_post_button");

$("#share_post_button").click(()=>{
    let content = $("#postText").prop("innerText");
    $.ajax({
        url: 'scripts/sendPost.php',
        method: 'post',
        dataType: 'html',
        data: {content: content},
        success: function(data){
        }
    });
})

let LikeButtons = document.getElementsByClassName("like_button");

for(let i=0; i<LikeButtons.length; i++){
    LikeButtons[i].onclick = function (){
        let id = LikeButtons[i].parentNode.getAttribute("name");
        id = id.match(/(\d+)\s(\d+)/i);
        let LikeInd = document.getElementById("like_" + id[2]);

        if(LikeButtons[i].getAttribute("src")==="resources/like.svg"){
            LikeButtons[i].setAttribute("src", "resources/default_like.svg");
            if(LikeInd.innerText>1){
                LikeInd.innerText -= 1;
            }
            else{
                LikeInd.innerText = "";
            }
        }
        else {
            LikeButtons[i].setAttribute("src", "resources/like.svg");
            LikeInd.innerText = +LikeInd.innerText + 1;

        }


        $.ajax({
            url: 'scripts/putLike.php',
            method: 'post',
            dataType: 'html',
            data: {owner_user: id[1], post_id: id[2]},
            success: function(data){

            }
        });

    }
}

let CommentButtons = document.getElementsByClassName("comment_button");
for (let i=0; i<CommentButtons.length; i++){
    CommentButtons[i].onclick = function (){
        let id = CommentButtons[i].parentNode.getAttribute("name");
        id = id.match(/(\d+)\s(\d+)/i);
        let CommentInd = document.getElementById("comment_" + id[2]);


        $.ajax({
            url: 'scripts/getComments.php',
            method: 'post',
            dataType: 'html',
            data: {post_id: id[2]},
            success: function(data){
                data = JSON.parse(data);
                let lastComment = data[data.length-1];
                let lastComName = CommentButtons[i].parentElement.lastElementChild.lastElementChild.lastElementChild.getAttribute("name");
                if(lastComName===null){
                    lastComName = "comment_id_0";
                }
                lastComName = lastComName.match(/(\w+)_(\d+)/i);
                if(lastComName[2] !== lastComment.comment_id){
                    for(let j=1; j<data.length; j++){
                        if(lastComName[2]>data[j].comment_id){
                            continue;
                        }
                        let html = '<hr><div class="comment_block" name="comment_id_' + data[j].comment_id + '">' +
                            '<a href="http://localhost:9092/user_profile/index.php?id=' + data[j].user_id +'">' +
                            data[j].firstname + ' ' + data[j].lastname + '</a>' +
                            '<img src="resources/points.svg" class="comment_points" height="20">' +
                            '<div class="comment_points_menu">';
                        if(data[0]==data[j].user_id){
                            html += '<div class="edit_comment_button" name="' + data[j].comment_id + " " + id[2] +'">Редактировать</div><hr><div class="delete_comment_button" name="' + data[j].comment_id + " " + id[2] +'">Удалить</div><hr>';
                        }
                        html +=
                            '<div class="answer_comment">Ответить</div></div><br><div id="comment_text_'+ data[j].comment_id + '">' +
                            '</div></div>';
                        let div = document.createElement("DIV");
                        let comment_id = "comment_text_" + data[j].comment_id;
                        div.innerHTML = html;

                        CommentButtons[i].parentNode.lastElementChild.appendChild(div);
                        document.getElementById(comment_id).innerText = data[j].content;



                        let Commentpoints = document.getElementsByClassName("comment_points");
                        for (let i=0; i<Commentpoints.length; i++){
                            Commentpoints[i].onclick = function (){
                                let menu = Commentpoints[i].nextElementSibling;
                                if(menu.style.display === "block"){
                                    menu.style.display ="none";
                                }
                                else{
                                    for(let j=0; j<Commentpoints.length; j++){
                                        Commentpoints[j].nextElementSibling.style.display="none";
                                    }
                                    menu.style.display ="block";
                                }
                            }
                        }



                    }
                }

                let EditCommentButton = document.getElementsByClassName("edit_comment_button");
                for (let i=0; i<EditCommentButton.length; i++){
                    EditCommentButton[i].onclick = function (){
                        let CommentEditor = document.getElementById("modal");
                        Id = EditCommentButton[i].getAttribute("name");
                        document.getElementById("edit_text").setAttribute("name", Id);

                        document.getElementById("editor").value = EditCommentButton[i].parentElement.parentElement.lastElementChild.innerText;
                        CommentEditor.style.display = "block";
                    }
                }

                let AnswerComment = $(".answer_comment");
                for(let i=0; i<AnswerComment.length; i++){
                    let buttonCounter = 0;
                    AnswerComment[i].onclick = function (){
                        html =
                            '<div class="send_comment_block">'+
                            '<div contentEditable="true" class="comment_area" ></div>' +
                            '<input type="submit" class="send_answer_comment nexus_button" value="Отправить">'+
                            '</div>';
                        let div = document.createElement("DIV");
                        div.innerHTML = html;
                        AnswerComment[i].parentElement.parentNode.appendChild(div);

                        let SendAnswerComment = document.getElementsByClassName("send_answer_comment");
                        //for (let i=0; i<SendAnswerComment.length; i++){
                            SendAnswerComment[buttonCounter].onclick = function (){

                                let commentBlock = SendAnswerComment[buttonCounter].parentNode;
                                let post_id = commentBlock.parentNode.parentElement.getAttribute("name");
                                post_id = post_id.match(/(\w+)_(\d+)/i);
                                buttonCounter++;
                                let content = commentBlock.firstElementChild.innerText;
                                alert(content);

                                /*$.ajax({
                                    url: 'scripts/sendAnswerComment.php',
                                    method: 'post',
                                    dataType: 'html',
                                    data: {content: content, post_id: post_id[1]},
                                    success: function(data){

                                    }
                                });*/

                           //    }
                        }

                    }

                }




                let DeleteCommentButton = document.getElementsByClassName("delete_comment_button");
                for (let i=0; i<DeleteCommentButton.length; i++){
                    DeleteCommentButton[i].onclick = function(){
                        id = DeleteCommentButton[i].getAttribute("name").match(/(\d+)\s(\d+)/i);
                        let post_id = id[2];
                        let comment_id = id[1];
                        $.ajax({
                            url: 'scripts/deleteComment.php',
                            method: 'post',
                            dataType: 'html',
                            data: {post_id: post_id, comment_id: comment_id},
                            success: function (data){

                            }
                        });
                    }
                }



            }
        });




        if(CommentInd.style.display == "block"){
            CommentInd.style.display="none";
        }
        else{
            CommentInd.style.display="block";
        }

    }
}


let PostPoints = document.getElementsByClassName("post_points");
for (let i=0; i<PostPoints.length; i++) {
    PostPoints[i].onclick = function (){
        if(PostPoints[i].nextElementSibling.style.display === "block"){
            PostPoints[i].nextElementSibling.style.display = "none";
        }
        else{
            let Points = document.getElementsByClassName("points_menu");
            for (let j=0; j<Points.length; j++){
                Points[j].style.display = "none";
            }
            PostPoints[i].nextElementSibling.style.display = "block";
        }

    }
}

let PostEditButton = document.getElementsByClassName("post_edit_button");
for (let i=0; i<PostEditButton.length; i++){
    PostEditButton[i].onclick = function (){

        let PostEditor = document.getElementById("modal");
        let idValue = PostEditButton[i].parentElement.parentElement.parentElement.getAttribute("name");
        let id  = idValue.match(/(\d+)\s(\d+)/i);

        document.getElementById("edit_text").setAttribute("name", id[2]);
        document.getElementById("editor").value = PostEditButton[i].parentElement.parentElement.lastElementChild.innerHTML;
        PostEditor.style.display = "block";

    }
}

document.getElementById("cross_img").onclick = function (){
    document.getElementById("editor").value="";
    document.getElementById("modal").style.display="none";
}

let SubmitEditButton =  document.getElementById("submit_edit");
SubmitEditButton.onclick = function (){
    let content = document.getElementById("editor").value;
    let idValue = document.getElementById("edit_text").getAttribute("name");
    let id = idValue.match(/(\d+)\s(\d+)/i);
    if(id ===null){
        $.ajax({
            url: 'scripts/editPost.php',
            method: 'post',
            dataType: 'html',
            data: {content: content, post_id: idValue},
            success: function(data){

            }
        });
    }
    else{
        $.ajax({
            url: 'scripts/editComment.php',
            method: 'post',
            dataType: 'html',
            data: {content: content, post_id: id[2], comment_id: id[1]},
            success: function(data){

            }
        });
    }
    document.getElementById("editor").value="";
    document.getElementById("modal").style.display="none";
}

let SendComment = document.getElementsByClassName("send_comment");
for (let i=0; i<SendComment.length; i++){
    SendComment[i].onclick = function (){
        let comment = SendComment[i].parentNode;
        let post_id = comment.parentNode.parentElement.getAttribute("name");
        post_id = post_id.match(/(\d+)\s(\d+)/i);

        let content = comment.firstElementChild.innerText;

        $.ajax({
            url: 'scripts/sendComment.php',
            method: 'post',
            dataType: 'html',
            data: {content: content, post_id: post_id[2]},
            success: function(data){

            }
        });

    }
}

let DeletePostButton = document.getElementsByClassName("delete_post_button");
for (let i=0; i<DeletePostButton.length; i++){
    DeletePostButton[i].onclick = function(){
        let id=DeletePostButton[i].parentElement.parentElement.parentElement.getAttribute("name");
        id  = id.match(/(\d+)\s(\d+)/i);
        $.ajax({
           url: 'scripts/deletePost.php',
           method: 'post',
           dataType: 'html',
            data: {user_id: id[1], post_id: id[2]},
            success: function (data){

            }
        });
    }
}



