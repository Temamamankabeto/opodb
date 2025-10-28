{{-- resources/views/errors/404.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - 404</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #2b5876, #4e4376);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            overflow: hidden;
        }

        .container {
            text-align: center;
            animation: fadeIn 1.2s ease-in-out;
        }

        h1 {
            font-size: 10rem;
            margin: 0;
            font-weight: 900;
            background: linear-gradient(45deg, #ff4b1f, #ff9068);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: bounce 2s infinite;
        }

        h2 {
            margin: 10px 0;
            font-size: 2rem;
            animation: fadeInUp 1.5s ease-in-out;
        }

        p {
            margin: 15px 0;
            font-size: 1.2rem;
            animation: fadeInUp 2s ease-in-out;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            font-size: 1rem;
            text-decoration: none;
            background: #ff4b1f;
            color: #fff;
            border-radius: 30px;
            transition: all 0.3s ease;
            animation: fadeInUp 2.5s ease-in-out;
        }

        a:hover {
            background: #ff9068;
            transform: scale(1.1);
        }

        /* Floating animation for background elements */
        .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            animation: float 6s infinite ease-in-out;
        }

        .circle.small {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 20%;
            animation-delay: 0s;
        }

        .circle.medium {
            width: 150px;
            height: 150px;
            bottom: 15%;
            right: 15%;
            animation-delay: 2s;
        }

        .circle.large {
            width: 250px;
            height: 250px;
            top: 25%;
            right: 25%;
            animation-delay: 4s;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        @keyframes float {
            0% { transform: translateY(0) rotate(0); }
            50% { transform: translateY(-20px) rotate(45deg); }
            100% { transform: translateY(0) rotate(0); }
        }
    </style>
</head>
<body>
    <div class="circle small"></div>
    <div class="circle medium"></div>
    <div class="circle large"></div>

    <div class="container">
        <h1>404</h1>
        <h2>Oops! Page Not Found</h2>
        <p>The page you are looking for doesn’t exist or has been moved.</p>
        <a href="{{ url('/') }}">Go Back Home</a>
    </div>
</body>
</html>
