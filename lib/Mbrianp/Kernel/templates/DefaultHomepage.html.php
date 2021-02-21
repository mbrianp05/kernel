<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="vendor/kernel/templates/assets/css/all.min.css">
    <style>
        body, html {
            width: 100%;
            height: 100%;
            font-family: arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            /*background-image: url("vendor/kernel/templates/assets/img/banner.jpg");*/
            /*background-position: center center;*/
            /*background-repeat: no-repeat;*/
            /*background-attachment: fixed;*/
            /*background-size: cover;*/
        }

        .container {
            padding: 60px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-content: center;
            height: 100%;
            background-color: rgba(250, 250, 250, .8);
            position: relative;
            z-index: 1;
        }

        p {
            padding: 20px 20px 0 20px;
            height: 200px;
            flex-basis: 70%;
            line-height: 1.5;
            margin-bottom: 0;
            color: #222;
        }

        h1, h3 {
            flex-grow: 1;
            flex-basis: 100%;
            text-align: center;
            color: #111;
        }

        p strong {
            display: block;
            color: #222;
        }

        .container div {
            flex-basis: 70%;
            display: flex;
            justify-content: space-between;
        }

        .container div a {
            text-decoration: none;
            color: #222222;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .container div a svg {
            display: block;
            margin-bottom: 10px;
            font-size: 58px;
        }

        .space-top {
            margin-top: 40px;
        }

        h1 {
            margin: 0 0 0 5px;
            font-size: 33px;
        }

        h3 {
            font-style: italic;
            font-size: 23px;
        }

        .bar {
            position: relative;
            padding: 20px;
            width: 100%;
            box-sizing: border-box;
            font-weight: 400;
            font-size: 15px;
            box-shadow: 0 2px 2px rgba(0, 0, 0, .3);
            color: #fafafa;
            background-color: rgba(0, 0, 0, .8);
            z-index: 1;
            border-radius: 7px;
        }

        .back::before {
            position: absolute;
            width: 0;
            height: 0;
            border-right: 0 solid transparent;
            border-bottom: 100vh solid #ccc;
            border-left: 100vw solid transparent;
            content: "";
            z-index: 1;
            right: 0;
        }
    </style>
    <title>Framework's homepage</title>
</head>
<body>
<div class="back"></div>
<div class="container">
    <h1>Welcome to Laton Web Framework</h1>
    <h3>Create your own web sites easily</h3>
    <div class="bar">
        You are seeing this because no route has been configured yet.
    </div>
    <p>
        Don't care about common things anymore, this swift light framework provides you some useful functionalities that
        will help you make all websites you want.

        <strong class="space-top">Don't waste more time, and create your first controller!</strong>
        <strong>Don't forget register it in map.php</strong>
    </p>
    <div>
        <a href="https://github.com/mbrianp05/Laton">
            <span class="fa fab fa-4x fa-github"></span>
            GitHub
        </a>
        <a href="https://twitter.com/BrMgPr">
            <span class="fa fab fa-twitter fa-4x"></span>
            Twitter
        </a>
        <a href="readme.md">
            <span class="fa fa-book fa-4x"></span>
            Documentation
        </a>
    </div>
</div>
<script src="vendor/kernel/templates/assets/js/all.min.js"></script>
</body>
</html>