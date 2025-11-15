<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OPO Login</title>
    <link rel="shortcut icon" href="{{ asset('uploads/oromia.png') }}" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
                url("{{ asset('assets/uploads/oromia.png') }}") no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            display: flex;
            width: 100%;
            max-width: 1000px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }

        .text-content {
            flex: 1;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: rgba(0, 0, 0, 0.4);
        }

        .text-content h1 {
            font-size: 3.5rem;
            margin-bottom: 15px;
            color: #fff;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .text-content p {
            font-size: 1.3rem;
            margin-bottom: 10px;
            opacity: 0.9;
        }

        .form-container {
            flex: 1;
            padding: 50px 40px;
            background: rgba(255, 255, 255, 0.95);
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-header h2 {
            color: #0a58ca;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .form-header p {
            color: #666;
        }

        .input-group {
            margin-bottom: 20px;
            position: relative;
        }

        .input-group input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .input-group input:focus {
            outline: none;
            border-color: #0a58ca;
            box-shadow: 0 0 0 2px rgba(10, 88, 202, 0.2);
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #777;
        }

        .alert.alert-danger {
            background: #ffe6e6;
            color: #a94442;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #f5c6cb;
            font-size: 0.9rem;
        }

        .link {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .link .login {
            border: none;
            outline: none;
            cursor: pointer;
            background: #0a58ca;
            padding: 15px 0;
            border-radius: 8px;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(10, 88, 202, 0.3);
        }

        .link .login:hover {
            background: #0949a8;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(10, 88, 202, 0.4);
        }

        .link .forgot {
            color: #0a58ca;
            text-align: center;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .link .forgot:hover {
            text-decoration: underline;
        }

        footer {
            margin-top: 30px;
            text-align: center;
            padding: 15px;
            background: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            width: 100%;
            max-width: 1000px;
            font-size: 0.9rem;
        }

        /* Responsive Design */
        @media (max-width: 900px) {
            .container {
                flex-direction: column;
            }

            .text-content {
                padding: 30px 25px;
                text-align: center;
            }

            .form-container {
                padding: 30px 25px;
            }
        }

        @media (max-width: 460px) {
            .text-content h1 {
                font-size: 2.8rem;
            }

            .text-content p {
                font-size: 1.1rem;
            }

            .form-header h2 {
                font-size: 1.7rem;
            }
        }
    </style>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="container">
        <div class="text-content">
            <h1>OPO</h1>
            <p>Oromia President Office</p>
            <p>Plan Management System</p>
        </div>
        <div class="form-container">
            <div class="form-header">
                <h2>Welcome Back</h2>
                <p>Please sign in to your account</p>
            </div>
            <form action="{{ route('user.check') }}" method="post">
                @csrf
                <div class="input-group">
                    <i class="fas fa-phone"></i>
                    <input type="text" name="phone" placeholder="Phone number" value="{{ old('phone') }}"
                        required>
                </div>
                @if ($errors->has('phone'))
                    <div class="alert alert-danger">{{ $errors->first('phone') }}</div>
                @endif

                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                @if ($errors->has('password'))
                    <div class="alert alert-danger">{{ $errors->first('password') }}</div>
                @endif

                <div class="link">
                    <button type="submit" class="login">Login</button>
                    {{-- <a href="#" class="forgot">Forgot password?</a> --}}
                </div>
            </form>
        </div>
    </div>
    <footer>
        <p>&copy; 2025 Oromia President Office. All rights reserved.</p>
    </footer>
</body>

</html>