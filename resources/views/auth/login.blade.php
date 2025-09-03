<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - AquaMonitor</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Minimalist UI CSS -->
    <link rel="stylesheet" href="{{ asset('css/minimalist-ui.css') }}">
    
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: var(--space-lg);
            background: linear-gradient(135deg, var(--background-color) 0%, #EAECEE 100%);
        }
        
        .login-card {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            animation: fadeIn 0.8s ease-out;
        }
        
        .login-logo {
            text-align: center;
            margin-bottom: var(--space-2xl);
        }
        
        .login-logo h1 {
            font-size: 2.5rem;
            font-weight: var(--font-weight-semibold);
            color: var(--primary-color);
            margin-bottom: var(--space-sm);
        }
        
        .login-logo p {
            color: var(--text-secondary);
            font-size: 1.1rem;
        }
        
        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: var(--space-lg);
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            gap: var(--space-sm);
        }
        
        .remember-me input {
            width: 16px;
            height: 16px;
        }
        
        .remember-me label {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }
        
        .forgot-password {
            font-size: 0.9rem;
            color: var(--accent-color);
            text-decoration: none;
        }
        
        .forgot-password:hover {
            text-decoration: underline;
        }
        
        .login-footer {
            text-align: center;
            margin-top: var(--space-2xl);
            padding-top: var(--space-lg);
            border-top: 1px solid var(--border-color);
        }
        
        .login-footer p {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }
        
        @media (max-width: 480px) {
            .login-container {
                padding: var(--space-md);
            }
            
            .login-card {
                padding: var(--space-lg);
            }
            
            .form-actions {
                flex-direction: column;
                gap: var(--space-md);
                align-items: stretch;
            }
            
            .remember-me {
                order: 2;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="card login-card">
            <div class="login-logo">
                <h1>AquaMonitor</h1>
                <p>Water Quality Monitoring System</p>
            </div>
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input 
                        id="email" 
                        type="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autocomplete="email" 
                        autofocus
                        placeholder="Enter your email address"
                    >
                    @error('email')
                        <div style="color: var(--danger-color); font-size: 0.9rem; margin-top: var(--space-xs);">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input 
                        id="password" 
                        type="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        name="password" 
                        required 
                        autocomplete="current-password"
                        placeholder="Enter your password"
                    >
                    @error('password')
                        <div style="color: var(--danger-color); font-size: 0.9rem; margin-top: var(--space-xs);">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="form-actions">
                    <div class="remember-me">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            id="remember" 
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        <label for="remember">Remember me</label>
                    </div>
                    
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-password">
                            Forgot password?
                        </a>
                    @endif
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: var(--space-xl);">
                    Sign In
                </button>
            </form>
            
            <div class="login-footer">
                <p>&copy; 2025 AquaMonitor. All rights reserved.</p>
            </div>
        </div>
    </div>
    
    <script>
        // Add subtle animations
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                });
            });
        });
    </script>
</body>
</html>
