<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
        <script>
            function submit() {
                let url = document.getElementById("twitter_url").value;
                let isTwitterUrl = new RegExp('^https:\\/\\/twitter.com\\/.*\\/status\\/(\\d*)$');
                let matches = url.match(isTwitterUrl);
                if (matches) {
                    let id = matches[1];
                    let xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function () {
                        let resultContainer = document.getElementById('result');
                        if (this.readyState === 4 && this.status === 200) {
                            let response = JSON.parse(this.responseText);
                            let responseItems = response.items;
                            var followers = 0;
                            responseItems.forEach(function (item) {
                                followers += item.user.followers_count;
                            });
                            resultContainer.innerText = "Potential reach is: " + followers;
                        } else if (this.status === 404) {
                            resultContainer.innerText = "Tweet not found";
                        } else if (this.status === 400 || this.status === 500) {
                            resultContainer.innerText = "Something went wrong";
                        } else {
                            resultContainer.innerText = "Loading";
                        }
                    };
                    xmlhttp.open('GET', 'http://localhost:3000/api/tweet/'+id+'/retweets');
                    xmlhttp.send();
                }
                else {
                    document.getElementById('result').innerText = "This does not look like a valid tweet url";
                }
            }
        </script>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Tweet Reach
                </div>

                <div class="links">
                    <input id="twitter_url">
                    <button onclick="submit()">Find reach</button>
                </div>
                <div id="result">
                </div>
            </div>
        </div>
    </body>
</html>
