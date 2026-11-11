<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coming Soon | {{ \App\Models\Setting::get('site_name', 'Meghduti Paridhaan') }} - {{ \App\Models\Setting::get('site_tagline', 'Own The Fashion Wave') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #000000;
            --brand-tan: #B5876D;
            --text-dark: #18181B;
            --text-muted: #71717A;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Montserrat', sans-serif;
            min-height: 100vh;
            background: #FAFAFA;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            color: var(--text-dark);
            position: relative;
        }
        .card {
            background: #FFFFFF;
            border: 1px solid #E4E4E7;
            border-radius: 4px;
            padding: 60px 40px;
            max-width: 640px;
            width: 100%;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
        }
        .brand-badge {
            display: inline-block;
            background: #000000;
            color: #FFFFFF;
            padding: 6px 16px;
            border-radius: 2px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 24px;
        }
        .brand-logo {
            font-size: 38px;
            font-weight: 900;
            letter-spacing: 4px;
            color: #000000;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .subtitle {
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 28px;
        }
        .headline {
            font-size: 26px;
            font-weight: 800;
            line-height: 1.3;
            margin-bottom: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .desc {
            font-size: 14px;
            line-height: 1.6;
            color: var(--text-muted);
            max-width: 500px;
            margin: 0 auto 36px;
        }
        .countdown {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            max-width: 440px;
            margin: 0 auto 36px;
        }
        .time-box {
            background: #FAFAFA;
            border: 1px solid #E4E4E7;
            border-radius: 2px;
            padding: 16px 8px;
        }
        .time-val {
            font-size: 26px;
            font-weight: 800;
            color: #18181B;
            line-height: 1;
        }
        .time-lbl {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-top: 6px;
            letter-spacing: 1px;
        }
        .action-group {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            border-radius: 2px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
        }
        .btn-primary {
            background: #B5876D;
            color: #FFFFFF;
            border: none;
        }
        .btn-primary:hover {
            background: #A0725A;
        }
        .btn-outline {
            background: #FFFFFF;
            color: #000000;
            border: 1px solid #000000;
        }
        .btn-outline:hover {
            background: #000000;
            color: #FFFFFF;
        }
        .footer-note {
            margin-top: 40px;
            font-size: 12px;
            color: #A1A1AA;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="brand-badge">Official Launch</div>
        <h1 class="brand-logo">{{ \App\Models\Setting::get('site_name', 'Meghduti Paridhaan') }}</h1>
        <div class="subtitle">{{ \App\Models\Setting::get('site_tagline', 'Own The Fashion Wave') }}</div>

        <h2 class="headline">New Collection Dropping Soon</h2>
        <p class="desc">
            Experience bespoke tailoring, 100% combed Egyptian cotton shirts, and modern everyday essentials crafted with timeless elegance.
        </p>

        <div class="countdown">
            <div class="time-box">
                <div class="time-val" id="days">03</div>
                <div class="time-lbl">Days</div>
            </div>
            <div class="time-box">
                <div class="time-val" id="hours">14</div>
                <div class="time-lbl">Hours</div>
            </div>
            <div class="time-box">
                <div class="time-val" id="minutes">32</div>
                <div class="time-lbl">Minutes</div>
            </div>
            <div class="time-box">
                <div class="time-val" id="seconds">48</div>
                <div class="time-lbl">Seconds</div>
            </div>
        </div>

        <div class="action-group">
            <a href="{{ route('home', ['preview' => 1]) }}" class="btn btn-primary">
                Preview Storefront
            </a>
            <a href="{{ route('admin.login') }}" class="btn btn-outline">
                Admin Access
            </a>
        </div>

        <div class="footer-note">
            Hotline: +8809611900371 | Dhaka, Bangladesh
        </div>
    </div>

    <script>
        let countDate = new Date().getTime() + (3 * 24 * 60 * 60 * 1000);
        setInterval(function() {
            let now = new Date().getTime();
            let gap = countDate - now;

            let second = 1000, minute = second * 60, hour = minute * 60, day = hour * 24;
            let d = Math.floor(gap / day);
            let h = Math.floor((gap % day) / hour);
            let m = Math.floor((gap % hour) / minute);
            let s = Math.floor((gap % minute) / second);

            document.getElementById('days').innerText = String(d).padStart(2, '0');
            document.getElementById('hours').innerText = String(h).padStart(2, '0');
            document.getElementById('minutes').innerText = String(m).padStart(2, '0');
            document.getElementById('seconds').innerText = String(s).padStart(2, '0');
        }, 1000);
    </script>
</body>
</html>
