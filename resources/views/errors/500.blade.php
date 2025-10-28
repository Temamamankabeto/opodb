{{-- resources/views/errors/500.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Error - 500</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1f1c2c, #928dab);
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
            font-size: 9rem;
            margin: 0;
            font-weight: 900;
            background: linear-gradient(45deg, #ff512f, #dd2476);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shake 2s infinite;
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
            background: #ff512f;
            color: #fff;
            border-radius: 30px;
            transition: all 0.3s ease;
            animation: fadeInUp 2.5s ease-in-out;
        }

        a:hover {
            background: #dd2476;
            transform: scale(1.1);
        }

        /* Floating gears for animation */
        .gear {
            position: absolute;
            width: 120px;
            height: 120px;
            border: 12px solid rgba(255,255,255,0.15);
            border-radius: 50%;
            top: 15%;
            left: 20%;
            animation: spin 10s linear infinite;
        }

        .gear::before, .gear::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            background: rgba(255,255,255,0.2);
            border-radius: 3px;
        }

        .gear::before {
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
        }

        .gear::after {
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
        }

        .gear.small {
            width: 80px;
            height: 80px;
            top: 60%;
            right: 20%;
            animation-duration: 6s;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes shake {
            0%, 100% { transform: rotate(0); }
            25% { transform: rotate(2deg); }
            50% { transform: rotate(-2deg); }
            75% { transform: rotate(2deg); }
        }

        @keyframes spin {
            from { transform: rotate(0); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="gear"></div>
    <div class="gear small"></div>

    <div class="container">
        <h1>500</h1>
        <h2>Oops! Something went wrong</h2>
        <p>Our server encountered an error. Please try again later.</p>
        <a href="{{ url('/') }}">Go Back Home</a>
    </div>
</body>
</html>
