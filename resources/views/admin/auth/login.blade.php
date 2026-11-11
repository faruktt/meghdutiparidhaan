<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign In &bull; {{ \App\Models\Setting::get('site_name', 'Meghduti Paridhaan') }}</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --brand-primary: #EA580C;
            --brand-primary-hover: #C2410C;
            --bg-body: #F8FAFC;
            --card-bg: #FFFFFF;
            --border-color: #E2E8F0;
            --text-dark: #0F172A;
            --text-muted: #64748B;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-font-smoothing: antialiased;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-body);
            background-image: 
                radial-gradient(at 0% 0%, rgba(234, 88, 12, 0.06) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(241, 245, 249, 1) 0px, transparent 50%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            color: var(--text-dark);
            position: relative;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 10;
        }

        .login-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 18px;
            padding: 38px 32px;
            box-shadow: 
                0 20px 40px -15px rgba(0, 0, 0, 0.06),
                0 0 1px 1px rgba(0, 0, 0, 0.02);
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #EA580C 0%, #FB923C 100%);
        }

        /* Brand Header */
        .brand-header {
            text-align: center;
            margin-bottom: 28px;
        }
        .brand-logo {
            font-size: 32px;
            font-weight: 900;
            letter-spacing: -0.5px;
            line-height: 1;
            margin-bottom: 6px;
            color: #0F172A;
        }
        .brand-logo span {
            color: var(--brand-primary);
        }
        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #FFF7ED;
            color: var(--brand-primary);
            border: 1px solid #FFEDD5;
            font-size: 10.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 3px 10px;
            border-radius: 99px;
            margin-bottom: 8px;
        }
        .brand-subtext {
            font-size: 13px;
            color: var(--text-muted);
        }

        /* Alerts */
        .alert-error {
            background: #FEF2F2;
            border: 1px solid #FCA5A5;
            color: #991B1B;
            padding: 11px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success {
            background: #F0FDF4;
            border: 1px solid #86EFAC;
            color: #166534;
            padding: 11px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Form Inputs */
        .form-group {
            margin-bottom: 18px;
        }
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 6px;
        }
        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-icon {
            position: absolute;
            left: 14px;
            color: #94A3B8;
            font-size: 14px;
            pointer-events: none;
            transition: color 0.2s;
        }
        .form-input {
            width: 100%;
            padding: 11px 40px 11px 38px;
            background: #FFFFFF;
            border: 1.5px solid #CBD5E1;
            border-radius: 8px;
            color: #0F172A;
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: all 0.2s ease;
        }
        .form-input::placeholder {
            color: #94A3B8;
        }
        .form-input:focus {
            border-color: var(--brand-primary);
            background: #FFFFFF;
            box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.12);
        }
        .form-input:focus ~ .input-icon {
            color: var(--brand-primary);
        }

        /* Password Eye Toggle */
        .btn-toggle-eye {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            color: #94A3B8;
            font-size: 14px;
            cursor: pointer;
            padding: 4px;
            transition: color 0.2s;
        }
        .btn-toggle-eye:hover {
            color: #334155;
        }

        /* Meta Row */
        .form-meta-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
            font-size: 13px;
            color: var(--text-muted);
        }
        .remember-checkbox {
            display: flex;
            align-items: center;
            gap: 7px;
            cursor: pointer;
            user-select: none;
        }
        .remember-checkbox input {
            accent-color: var(--brand-primary);
            width: 15px;
            height: 15px;
            cursor: pointer;
        }
        .link-store {
            color: #64748B;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-weight: 600;
            transition: color 0.2s;
        }
        .link-store:hover {
            color: var(--brand-primary);
        }

        /* Submit Button */
        .btn-signin {
            width: 100%;
            padding: 12px 20px;
            background: var(--brand-primary);
            color: #FFFFFF;
            border: none;
            border-radius: 8px;
            font-size: 14.5px;
            font-weight: 800;
            letter-spacing: 0.2px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(234, 88, 12, 0.25);
            transition: all 0.2s;
        }
        .btn-signin:hover {
            background: var(--brand-primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(234, 88, 12, 0.35);
        }
        .btn-signin:active {
            transform: translateY(1px);
        }

        .security-badge {
            margin-top: 24px;
            padding-top: 18px;
            border-top: 1px solid #F1F5F9;
            text-align: center;
            font-size: 11.5px;
            color: #94A3B8;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .security-badge i {
            color: #10B981;
            font-size: 12px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-card">
            
            <!-- Brand Header -->
            <div class="brand-header">
                @php
                    $siteName = \App\Models\Setting::get('site_name', 'Meghduti Paridhaan');
                    $siteLogo = \App\Models\Setting::get('site_logo');
                    $logoUrl = null;
                    if ($siteLogo) {
                        if (file_exists(public_path('images/' . $siteLogo))) {
                            $logoUrl = asset('images/' . $siteLogo);
                        } elseif (file_exists(public_path('storage/' . $siteLogo))) {
                            $logoUrl = asset('storage/' . $siteLogo);
                        }
                    }
                @endphp

                @if($logoUrl)
                    <div style="margin-bottom: 12px; display: flex; justify-content: center;">
                        <img src="{{ $logoUrl }}" alt="{{ $siteName }}" style="max-height: 52px; max-width: 220px; object-fit: contain;">
                    </div>
                @else
                    <div class="brand-logo" style="font-size: 24px; font-weight: 900; color: #5C141D; margin-bottom: 6px;">{{ $siteName }}</div>
                @endif
                <div class="brand-badge">
                    <i class="fa-solid fa-lock"></i> Admin Portal
                </div>
                <p class="brand-subtext">Sign in to manage {{ $siteName }}</p>
            </div>

            <!-- Error Alerts -->
            @if($errors->any())
                <div class="alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert-error">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Sign In Form -->
            <form action="{{ route('admin.login.post') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label class="form-label" for="adminEmail">Email</label>
                    <div class="input-wrap">
                        <i class="fa-regular fa-envelope input-icon"></i>
                        <input type="email" 
                               name="email" 
                               id="adminEmail" 
                               value="{{ old('email') }}" 
                               class="form-input" 
                               placeholder="Email" 
                               required 
                               autofocus 
                               autocomplete="username">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="adminPassword">Password</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-lock input-icon"></i>
                        <input type="password" 
                               name="password" 
                               id="adminPassword" 
                               class="form-input" 
                               placeholder="Password" 
                               required 
                               autocomplete="current-password">
                        <button type="button" class="btn-toggle-eye" id="togglePasswordBtn" title="Toggle password visibility">
                            <i class="fa-regular fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="form-meta-row">
                    <label class="remember-checkbox">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Remember me</span>
                    </label>
                    <a href="{{ route('home') }}" class="link-store" target="_blank">
                        <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 11px;"></i> Visit Store
                    </a>
                </div>

                <button type="submit" class="btn-signin">
                    <span>Sign In</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>

                <div class="security-badge">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span>256-Bit SSL Secured Connection</span>
                </div>
            </form>

        </div>
    </div>

    <script>
        // Toggle password show/hide
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('adminPassword');
        const eyeIcon = document.getElementById('eyeIcon');

        if (toggleBtn && passwordInput && eyeIcon) {
            toggleBtn.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                eyeIcon.classList.toggle('fa-eye', !isPassword);
                eyeIcon.classList.toggle('fa-eye-slash', isPassword);
            });
        }
    </script>
</body>
</html>
