<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Luxury Auth') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    <style>
        :root {
            --dark-red: #5c0a0a;
            --light-red: #8b0000;
            --gold: #d4af37;
            --light-gold: #f1e5ac;
            --white: #ffffff;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }
        
        body {
            background-color: var(--dark-red);
            color: var(--white);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }
        
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }
        
        .particle {
            position: absolute;
            background-color: var(--gold);
            border-radius: 50%;
            opacity: 0.6;
            animation: float 15s infinite linear;
        }
        
        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 0.6;
            }
            50% {
                opacity: 0.9;
            }
            100% {
                transform: translateY(-1000px) rotate(720deg);
                opacity: 0;
            }
        }
       
       /* Background Styles */
body {
    background-image: url('{{ asset("images/heros.png") }}');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    margin: 0;
    padding: 0;
    min-height: 100vh;
    font-family: 'Montserrat', sans-serif;
    position: relative;
}

body::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
  
    backdrop-filter: blur(0px);
    z-index: -1;
}

/* Main Container */
.login-container {
    display: flex;
    max-width: 1000px;
    width: 90%;
    min-height: 600px;
    margin: 5vh auto;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.5);
}

/* Welcome Column */
.welcome-column {
    flex: 1;
    background: linear-gradient(135deg, rgba(139, 0, 0, 0.9), rgba(92, 10, 10, 0.9));
   
    padding: 50px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    position: relative;
}

.welcome-content {
    color: white;
    text-align: center;
}

.welcome-content h1 {
    font-size: 32px;
    margin-bottom: 15px;
    font-weight: 600;
}

.welcome-content h1 span {
    color: var(--gold);
}

.tagline {
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 40px;
    font-size: 16px;
}

/* Logo Animation */
.logo-animation {
    margin: 30px 0;
    animation: float 3s ease-in-out infinite;
}

.logo-img {
    width: 120px;
    height: auto;
    filter: drop-shadow(0 5px 15px rgba(212, 175, 55, 0.4));
}

@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
    100% { transform: translateY(0px); }
}

/* Features */
.features {
    margin-top: 30px;
}

.feature-item {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 15px 0;
}

.feature-item i {
    color: var(--gold);
    margin-right: 10px;
    font-size: 18px;
}

.feature-item span {
    color: white;
    font-size: 15px;
}

/* Form Column */
.form-column {
    flex: 1;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(3px);
    padding: 50px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.form-title {
    color: white;
    font-size: 28px;
    margin-bottom: 30px;
    text-align: center;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    color: var(--light-gold);
    margin-bottom: 8px;
    font-size: 14px;
}

.input-with-icon {
    position: relative;
}

.input-with-icon i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gold);
}

.input-with-icon input {
    width: 100%;
    padding: 12px 15px 12px 40px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 6px;
    color: white;
    font-size: 15px;
    transition: all 0.3s;
}

.input-with-icon input:focus {
    outline: none;
    border-color: var(--gold);
    box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
}

.form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 20px 0;
    font-size: 14px;
}

.remember-me {
    display: flex;
    align-items: center;
}

.remember-me input {
    margin-right: 8px;
    accent-color: var(--gold);
}

.forgot-password {
    color: var(--light-gold);
    text-decoration: none;
    transition: color 0.3s;
}

.forgot-password:hover {
    color: var(--gold);
    text-decoration: underline;
}

.login-btn {
    width: 100%;
    padding: 14px;
    background: linear-gradient(to right, var(--gold), var(--light-gold));
    color: var(--dark-red);
    border: none;
    border-radius: 6px;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s;
    margin-top: 10px;
}

.login-btn:hover {
    background: linear-gradient(to right, var(--light-gold), var(--gold));
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(212, 175, 55, 0.4);
}

/* Social Login */
.social-login {
    margin-top: 30px;
    text-align: center;
}

.divider {
    position: relative;
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 20px;
}

.divider::before,
.divider::after {
    content: '';
    position: absolute;
    top: 50%;
    width: 30%;
    height: 1px;
    background: rgba(255, 255, 255, 0.2);
}

.divider::before {
    left: 0;
}

.divider::after {
    right: 0;
}

.social-icons {
    display: flex;
    justify-content: center;
    gap: 15px;
}

.social-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    transition: all 0.3s;
}

.social-icon:hover {
    background: var(--gold);
    color: var(--dark-red);
    transform: translateY(-3px);
}

/* Responsive */
@media (max-width: 768px) {
    .login-container {
        flex-direction: column;
        min-height: auto;
    }
    
    .welcome-column, .form-column {
        padding: 30px;
    }
    
    .logo-img {
        width: 100px;
    }
}

@media (max-width: 480px) {
    .login-container {
        width: 95%;
    }
    
    .welcome-column, .form-column {
        padding: 25px 20px;
    }
    
    .welcome-content h1 {
        font-size: 26px;
    }
    
    .form-title {
        font-size: 24px;
    }
}
        /* Add these styles to your existing CSS */


    </style>
</head>
<body>
    <!-- Gold particles background -->
    <div class="particles" id="particles"></div>
    
    @yield('content')

    <script>
        // Create gold particles
        document.addEventListener('DOMContentLoaded', function() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 30;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                // Random size between 2px and 6px
                const size = Math.random() * 4 + 2;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                
                // Random position
                particle.style.left = `${Math.random() * 100}%`;
                particle.style.top = `${Math.random() * 100}%`;
                
                // Random animation duration
                const duration = Math.random() * 10 + 10;
                particle.style.animationDuration = `${duration}s`;
                
                // Random delay
                particle.style.animationDelay = `${Math.random() * 5}s`;
                
                particlesContainer.appendChild(particle);
            }
            
            // Add focus effect to input fields
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    const icon = this.parentElement.querySelector('i');
                    if (icon) {
                        icon.style.color = 'var(--light-gold)';
                        icon.style.transform = 'scale(1.2)';
                    }
                });
                
                input.addEventListener('blur', function() {
                    const icon = this.parentElement.querySelector('i');
                    if (icon) {
                        icon.style.color = 'var(--gold)';
                        icon.style.transform = 'scale(1)';
                    }
                });
            });
        });
    </script>
</body>
</html>