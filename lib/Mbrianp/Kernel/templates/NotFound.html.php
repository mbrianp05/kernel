<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: arial, sans-serif;
        }

        .container {
            padding: 10%;
            padding-top: 70px;
        }

        .card-container {
            box-shadow: 0 0 2px 1px rgba(0, 0, 0, .2);
        }

        .info {
            color: #797979;
        }

        .heading {
            padding: 2rem;
            background-color: #2b9f9f;
            /*box-shadow: 0 3px 2px rgba(0, 0, 0, .5), 0 6px 2px rgba(0, 0, 0, .2);*/
            color: #FFF;
            font-size: 22px;
            font-weight: 500;
        }

        .reports {
            padding: 2rem;
            font-size: 15px;
        }
    </style>
    <title>Route was not found</title>
</head>
<body>
    <div class="container">
        <span class="info">Some errors were found</span>
        <div class="card-container">
            <div class="heading">
                <span>Route was not found</span>
            </div>
            <div class="reports">
                <span>It seems that you haven't configured a route for path: </span>
                <strong><?= $path ?></strong>
            </div>
        </div>
    </div>
</body>
</html>