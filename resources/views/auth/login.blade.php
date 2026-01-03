<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dayflow</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #0f172a;
            overflow: hidden;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Animated Background */
        .bg-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            overflow: hidden;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(70px);
            opacity: 0.5;
            animation-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }

        .shape-1 {
            top: -15%;
            left: -15%;
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, #4f46e5 0%, rgba(79, 70, 229, 0) 70%);
            animation: moveOne 25s infinite alternate;
        }

        .shape-2 {
            bottom: -20%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, #ec4899 0%, rgba(236, 72, 153, 0) 70%);
            animation: moveTwo 20s infinite alternate-reverse;
        }

        .shape-3 {
            top: 40%;
            left: 40%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, #10b981 0%, rgba(16, 185, 129, 0) 70%);
            animation: moveThree 22s infinite alternate;
        }

        @keyframes moveOne {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(200px, 100px) rotate(45deg) scale(1.1); }
        }
        @keyframes moveTwo {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(-150px, -100px) scale(0.9); }
        }
        @keyframes moveThree {
            0% { transform: translate(0, 0) scale(0.8); }
            100% { transform: translate(-100px, 150px) scale(1.2); }
        }

        /* Enhanced Glass Login Card */
        .login-card {
            background: rgba(255, 255, 255, 0.07);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            border-left: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            position: relative;
            z-index: 10;
            box-shadow: 0 50px 100px -20px rgba(0, 0, 0, 0.5);
        }

        .form-control {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            padding: 0.8rem 1rem;
            border-radius: 12px;
            transition: all 0.3s;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
            color: white;
            outline: none;
        }

        .form-control::placeholder { color: rgba(255, 255, 255, 0.4); }

        .btn-login {
            background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
            border: none;
            padding: 1rem;
            border-radius: 12px;
            font-weight: 600;
            color: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            letter-spacing: 0.5px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.6);
            color: white;
            background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
        }

        .brand-logo {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #6366f1, #ec4899);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 15px 30px -10px rgba(99, 102, 241, 0.5);
            animation: floatLogo 6s ease-in-out infinite;
        }

        @keyframes floatLogo {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .input-group-text {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-right: none;
            color: rgba(255, 255, 255, 0.6);
            border-radius: 12px 0 0 12px;
        }

        .form-control { border-left: none; border-radius: 0 12px 12px 0; }
    </style>
</head>
<body>

    <!-- Background Shapes -->
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <!-- Login Container -->
    <div class="container d-flex justify-content-center">
        <div class="login-card animate__animated animate__zoomIn">
            <div class="text-center mb-5">
                <div class="brand-logo animate__animated animate__bounceIn delay-1s">
                    <i class="fas fa-layer-group fa-2x text-white"></i>
                </div>
                <h3 class="fw-bold text-white mb-1">Welcome Back</h3>
                <p class="text-white-50">Sign in to access your dashboard</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="mb-4">
                    <label class="form-label text-white-50 small fw-bold text-uppercase">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="name@dayflow.com" required autofocus>
                    </div>
                    @error('email')
                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="form-label text-white-50 small fw-bold text-uppercase">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-login w-100 mb-4">
                    Sign In <i class="fas fa-arrow-right ms-2"></i>
                </button>

            </form>
        </div>
    </div>

</body>
</html>