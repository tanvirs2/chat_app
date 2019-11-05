<?php
require_once 'config.php';
//$_SESSION['name'] = null;
if (!$_SESSION['name']) {
    header('location: login/index.php');
}


$sql = "SELECT * FROM chat JOIN user ON chat.user_id = user.id";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>

    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>

    <script src="https://js.pusher.com/5.0/pusher.min.js"></script>

    <script>
        $(document).ready(function () {
            document.querySelector("#msg-box").scrollTo(0,document.querySelector("#msg-box").scrollHeight);
        });
        var pusher = new Pusher('54b390630895bf04e224', {
            cluster: 'ap2'
        });

        var channel = pusher.subscribe('my-channel');

        channel.bind('my-event', function(data) {
            //document.querySelector('div').innerHTML = data.message;
            console.log(data);
            //alert('An event was triggered with message: ' + data.message);

            let loggedUser = "<?php echo $_SESSION['name'] ?>";

            let template = '';

            if ((loggedUser == data.name)) {
                template = `<div class="row msg_container base_sent">
                                <div class="col-xs-10 col-md-10">
                                    <div class="messages msg_sent">
                                        <p>${data.message}</p>
                                        <time datetime="2009-11-13T20:00">Timothy • 0 min</time>
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-2 avatar">
                                    <img src="http://www.bitrebels.com/wp-content/uploads/2011/02/Original-Facebook-Geek-Profile-Avatar-1.jpg" class=" img-responsive " alt="f">
                                </div>
                            </div>
                            `;
            } else {
                template = `${data.name}

                            <div class="row msg_container base_receive">
                                    <div class="col-md-2 col-xs-2 avatar">
                                        <img src="http://www.bitrebels.com/wp-content/uploads/2011/02/Original-Facebook-Geek-Profile-Avatar-1.jpg" class=" img-responsive " alt="f">
                                    </div>
                                    <div class="col-xs-10 col-md-10">
                                        <div class="messages msg_receive">
                                            <p>${data.message}</p>
                                            <time datetime="2009-11-13T20:00">Timothy • </time>
                                        </div>
                                    </div>
                                </div>
                            `;
            }



            $('#msg-box').append(template);
            document.querySelector("#msg-box").scrollTo(0,document.querySelector("#msg-box").scrollHeight);
        });


        function msgAppend(){

            let inputText = $('.chat_input').val();

            $.post("pusher.php",
                    {
                        name: inputText,
                    },
                    function(data, status){
                        $('.chat_input').val('');
                        $('.chat_input').focus();
                    }
                );
        }
    </script>

    <script>
        $(document).on('click', '.panel-heading span.icon_minim', function (e) {
            var $this = $(this);
            if (!$this.hasClass('panel-collapsed')) {
                $this.parents('.panel').find('.panel-body').slideUp();
                $this.addClass('panel-collapsed');
                $this.removeClass('glyphicon-minus').addClass('glyphicon-plus');
            } else {
                $this.parents('.panel').find('.panel-body').slideDown();
                $this.removeClass('panel-collapsed');
                $this.removeClass('glyphicon-plus').addClass('glyphicon-minus');
            }
        });
        $(document).on('focus', '.panel-footer input.chat_input', function (e) {
            var $this = $(this);
            if ($('#minim_chat_window').hasClass('panel-collapsed')) {
                $this.parents('.panel').find('.panel-body').slideDown();
                $('#minim_chat_window').removeClass('panel-collapsed');
                $('#minim_chat_window').removeClass('glyphicon-plus').addClass('glyphicon-minus');
            }
        });
        $(document).on('click', '#new_chat', function (e) {
            var size = $( ".chat-window:last-child" ).css("margin-left");
            size_total = parseInt(size) + 400;
            alert(size_total);
            var clone = $( "#chat_window_1" ).clone().appendTo( ".container" );
            clone.css("margin-left", size_total);
        });
        $(document).on('click', '.icon_close', function (e) {
            //$(this).parent().parent().parent().parent().remove();
            $( "#chat_window_1" ).remove();
        });

    </script>

    <style>
        body{
            height:400px;
            position: fixed;
            bottom: 0;
        }
        .col-md-2, .col-md-10{
            padding:0;
        }
        .panel{
            margin-bottom: 0;
        }
        .chat-window{
            bottom:0;
            position:fixed;
            float:right;
            margin-left:10px;
        }
        .chat-window > div > .panel{
            border-radius: 5px 5px 0 0;
        }
        .icon_minim{
            padding:2px 10px;
        }
        .msg_container_base{
            background: #e5e5e5;
            margin: 0;
            padding: 0 10px 10px;
            height:300px;
            overflow-x:hidden;
        }
        .top-bar {
            background: #666;
            color: white;
            padding: 10px;
            position: relative;
            overflow: hidden;
        }
        .msg_receive{
            padding-left:0;
            margin-left:0;
        }
        .msg_sent{
            padding-bottom:20px !important;
            margin-right:0;
        }
        .messages {
            background: white;
            padding: 10px;
            border-radius: 2px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            max-width:100%;
        }
        .messages > p {
            font-size: 13px;
            margin: 0 0 0.2rem 0;
        }
        .messages > time {
            font-size: 11px;
            color: #ccc;
        }
        .msg_container {
            padding: 10px;
            overflow: hidden;
            display: flex;
        }
        img {
            display: block;
            width: 100%;
        }
        .avatar {
            position: relative;
        }
        .base_receive > .avatar:after {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            width: 0;
            height: 0;
            border: 5px solid #FFF;
            border-left-color: rgba(0, 0, 0, 0);
            border-bottom-color: rgba(0, 0, 0, 0);
        }

        .base_sent {
            justify-content: flex-end;
            align-items: flex-end;
        }
        .base_sent > .avatar:after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 0;
            border: 5px solid white;
            border-right-color: transparent;
            border-top-color: transparent;
            box-shadow: 1px 1px 2px rgba(0 0 0 0.2);
        }

        .msg_sent > time{
            float: right;
        }



        .msg_container_base::-webkit-scrollbar-track
        {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
            background-color: #F5F5F5;
        }

        .msg_container_base::-webkit-scrollbar
        {
            width: 12px;
            background-color: #F5F5F5;
        }

        .msg_container_base::-webkit-scrollbar-thumb
        {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
            background-color: #555;
        }

        .btn-group.dropup{
            position:fixed;
            left:0;
            bottom:0;
        }
    </style>
</head>
<body>

<!------ Include the above in your HEAD tag ---------->

<div class="container">
    <a href="session.php" class="btn btn-danger">Logout</a>
    <div class="row chat-window col-xs-5 col-md-3" id="chat_window_1" style="margin-left:10px; right: 200px">
        <div class="col-xs-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading top-bar">
                    <div class="col-md-8 col-xs-8">
                        <h3 class="panel-title"><span class="glyphicon glyphicon-comment"></span> Chat </h3>
                    </div>
                    <div class="col-md-4 col-xs-4" style="text-align: right;">
                        <a href="#"><span id="minim_chat_window" class="glyphicon glyphicon-minus icon_minim"></span></a>
                        <a href="#"><span class="glyphicon glyphicon-remove icon_close" data-id="chat_window_1"></span></a>
                    </div>
                </div>
                <div class="panel-body msg_container_base" id="msg-box">

                    <?php
                    if ($result->num_rows > 0) {
                        // output data of each row
                        while($row = $result->fetch_assoc()) {

                            if ($_SESSION['id'] != $row['user_id']) {
                                echo $row['name'];
                                ?>
                                <div class="row msg_container base_receive">
                                    <div class="col-md-2 col-xs-2 avatar">
                                        <img src="http://www.bitrebels.com/wp-content/uploads/2011/02/Original-Facebook-Geek-Profile-Avatar-1.jpg" class=" img-responsive " alt="f">
                                    </div>
                                    <div class="col-xs-10 col-md-10">
                                        <div class="messages msg_receive">
                                            <p><?php echo $row['msg'] ?></p>
                                            <time datetime="2009-11-13T20:00">Timothy • <?php echo $row['time'] ?></time>
                                        </div>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="row msg_container base_sent">
                                    <div class="col-md-10 col-xs-10 ">
                                        <div class="messages msg_sent">
                                            <p><?php echo $row['msg'] ?></p>
                                            <time datetime="2009-11-13T20:00">Timothy • <?php echo $row['time'] ?></time>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-2 avatar">
                                        <img src="http://www.bitrebels.com/wp-content/uploads/2011/02/Original-Facebook-Geek-Profile-Avatar-1.jpg" class=" img-responsive " alt="f">
                                    </div>
                                </div>

                            <?php }
                        }
                    }
                    ?>

                </div>
                <div class="panel-footer">
                    <div class="input-group">
                        <label for="btn-input"></label>
                        <input id="btn-input" type="text" class="form-control input-sm chat_input" placeholder="Write your message here..." />
                        <span class="input-group-btn">
                        <button class="btn btn-primary btn-sm" onclick="msgAppend()" id="btn-chat">Send</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
</body>
</html>