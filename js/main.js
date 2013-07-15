function likeRepo(owner, repo_name, opinion){
    $(document).ready(function(){

        var ip = $("input[type='hidden']").val();
        var reg_obj = new RegExp("([0-9]{1,3}\.){3}[0-9]{1,3}");
        var host = window.location.host;

        //if liker IP is correct
        if(reg_obj.test(ip)){
            $.ajax({
                type: 'POST',
                url: 'http://'+host+'/likerepo',
                data: {'owner' : owner, 'repo_name' : repo_name, 'liker_ip' : ip, 'opinion' : opinion},
                success: function(data){

                    if (data.status == "added"){
                        change_status(owner, repo_name, opinion, data.likes, data.unlikes);
                    }
                    if (data.status == "changed"){
                        change_status(owner, repo_name, opinion, data.likes, data.unlikes);
                    }
                },
                error : function(data){alert("Can't send Like request to server!");},
                dataType: "json"
            });
        }
    });
}

function change_status(owner, repo_name, opinion, likes, unlikes){
    var conv_op;
    var _class;
    var _text;
    if(opinion == 1) {conv_op = 0; _class = "likes unlike"; _text = "Unlike"}
    else {conv_op = 1; _class = "likes like"; _text = "Like"}
    var attributes = {"onclick" : "likeRepo('"+owner+"', '"+repo_name+"', "+conv_op+")", "class" : _class};
    $("div[onclick='likeRepo('"+owner+"', '"+repo_name+"', "+opinion+")']").attr(attributes).text(_text);
    $("div[onclick='likeRepo('"+owner+"', '"+repo_name+"', "+conv_op+")']").siblings("span:contains(Likes:)").text("Likes: "+likes);
    $("div[onclick='likeRepo('"+owner+"', '"+repo_name+"', "+conv_op+")']").siblings("span:contains(Unlikes:)").text("Unlikes: "+unlikes);
}

function likeUser(user_login, opinion){


    $(document).ready(function(){

        var ip = $("input[type='hidden']").val();
        var reg_obj = new RegExp("([0-9]{1,3}\.){3}[0-9]{1,3}");
        var host = window.location.host;

        //if liker IP is correct
        if(reg_obj.test(ip)){

            $.ajax({
                type: 'POST',
                url: 'http://'+host+'/likeuser',
                data: {'user_login' : user_login, 'liker_ip' : ip, 'opinion' : opinion},
                success: function(data){

                    if (data.status == "added"){
                        change_user_status(user_login, opinion, data.likes, data.unlikes);
                    }
                    if (data.status == "changed"){
                        change_user_status(user_login, opinion, data.likes, data.unlikes);
                    }
                },
                error : function(data){alert("Can't send Like request to server!");},
                dataType: "json"
            });

        }
    });
}

function change_user_status(user, opinion, likes, unlikes){
    var conv_op;
    var _class;
    var _text;
    if(opinion == 1) {conv_op = 0; _class = "likes unlike"; _text = "Unlike"}
    else {conv_op = 1; _class = "likes like"; _text = "Like"}
    var attributes = {"onclick" : "likeUser('"+user+"', "+conv_op+")", "class" : _class};
    $("div[onclick='likeUser('"+user+"', "+opinion+")']").attr(attributes).text(_text);
    $("div[onclick='likeUser('"+user+"', "+conv_op+")']").siblings("span:contains(Likes:)").text("Likes: "+likes);
    $("div[onclick='likeUser('"+user+"', "+conv_op+")']").siblings("span:contains(Unlikes:)").text("Unlikes: "+unlikes);
}
