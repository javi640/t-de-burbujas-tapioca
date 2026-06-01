<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panda Naicha — Tienda de Bubble Tea en La Paz</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,700;1,500&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --fog:   #D1DDDB;
            --sky:   #85B8CB;
            --ocean: #1D6A96;
            --deep:  #283B42;
            --cream: #F5F1EB;
            --white: #ffffff;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--deep);
            overflow-x: hidden;
        }

        /* ── Scrollbar ─────────────────────────── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--fog); }
        ::-webkit-scrollbar-thumb { background: var(--ocean); border-radius: 3px; }

        /* ── Navbar ────────────────────────────── */
        .navbar {
            background: rgba(40,59,66,.96);
            backdrop-filter: blur(12px);
            padding: .9rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: all .3s;
            border-bottom: 1px solid rgba(133,184,203,.15);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: .6rem;
            text-decoration: none;
        }

        .nav-logo {
            width: 38px; height: 38px;
            border-radius: 50%;
            background: var(--fog);
            padding: 4px;
            object-fit: contain;
        }

        .brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.15rem;
            color: #fff;
            font-weight: 700;
        }

        .navbar-nav .nav-link {
            color: rgba(255,255,255,.75) !important;
            font-size: .875rem;
            font-weight: 500;
            padding: .35rem .9rem !important;
            border-radius: 20px;
            transition: all .2s;
        }

        .navbar-nav .nav-link:hover {
            color: #fff !important;
            background: rgba(255,255,255,.1);
        }

        .btn-nav {
            background: var(--ocean);
            color: #fff !important;
            border-radius: 20px;
            padding: .38rem 1.1rem !important;
            font-weight: 600 !important;
        }

        .btn-nav:hover { background: var(--fog) !important; color: var(--deep) !important; }

        /* ── Hero ──────────────────────────────── */
        .hero {
            min-height: 92vh;
            background: var(--deep);
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero-bg-circle {
            position: absolute;
            border-radius: 50%;
            background: var(--ocean);
        }

        .hero-bg-circle.c1 { width: 600px; height: 600px; opacity: .1; top: -200px; right: -150px; }
        .hero-bg-circle.c2 { width: 300px; height: 300px; opacity: .07; bottom: -80px; left: -80px; background: var(--sky); }
        .hero-bg-circle.c3 { width: 180px; height: 180px; opacity: .12; top: 60%; left: 42%; background: var(--fog); }

        /* Floating pearls */
        .pearl {
            position: absolute;
            border-radius: 50%;
            background: var(--sky);
            animation: floatUp var(--dur) ease-in infinite;
            animation-delay: var(--delay);
            opacity: 0;
        }

        @keyframes floatUp {
            0%   { transform: translateY(0) scale(1); opacity: 0; }
            10%  { opacity: .4; }
            90%  { opacity: .15; }
            100% { transform: translateY(-100vh) scale(.5); opacity: 0; }
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: rgba(133,184,203,.12);
            border: 1px solid rgba(133,184,203,.25);
            color: var(--sky);
            font-size: .75rem;
            font-weight: 600;
            padding: .3rem .9rem;
            border-radius: 20px;
            letter-spacing: .06em;
            text-transform: uppercase;
            margin-bottom: 1.25rem;
            animation: fadeUp .8s ease both;
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.6rem, 6vw, 4.2rem);
            font-weight: 700;
            color: #fff;
            line-height: 1.1;
            margin-bottom: 1.25rem;
            animation: fadeUp .8s .15s ease both;
        }

        .hero-title span { color: var(--sky); }

        .hero-desc {
            font-size: 1.05rem;
            color: rgba(209,221,219,.7);
            line-height: 1.7;
            max-width: 500px;
            margin-bottom: 2rem;
            font-weight: 300;
            animation: fadeUp .8s .3s ease both;
        }

        .hero-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            animation: fadeUp .8s .45s ease both;
        }

        .btn-primary-hero {
            background: var(--ocean);
            color: #fff;
            border: none;
            padding: .8rem 1.8rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: .95rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            transition: all .2s;
        }

        .btn-primary-hero:hover {
            background: var(--sky);
            color: var(--deep);
            transform: translateY(-2px);
        }

        .btn-ghost-hero {
            background: transparent;
            color: rgba(255,255,255,.8);
            border: 1.5px solid rgba(255,255,255,.2);
            padding: .8rem 1.8rem;
            border-radius: 10px;
            font-weight: 500;
            font-size: .95rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            transition: all .2s;
        }

        .btn-ghost-hero:hover {
            border-color: rgba(255,255,255,.5);
            color: #fff;
        }

        /* Cup illustration */
        .hero-visual {
            display: flex;
            justify-content: center;
            align-items: center;
            animation: fadeUp .8s .3s ease both;
        }

        .hero-cup-wrap {
            position: relative;
            display: inline-block;
        }

        .hero-cup-wrap::before {
            content: '';
            position: absolute;
            width: 260px; height: 260px;
            border-radius: 50%;
            background: var(--ocean);
            opacity: .15;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
        }

        .hero-cup {
            width: 220px;
            filter: drop-shadow(0 24px 40px rgba(0,0,0,.4));
            position: relative;
            z-index: 2;
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-14px); }
        }

        .hero-stats {
            display: flex;
            gap: 2rem;
            margin-top: 2.5rem;
            animation: fadeUp .8s .6s ease both;
        }

        .hero-stat-val {
            font-family: 'DM Mono', monospace;
            font-size: 1.6rem;
            font-weight: 500;
            color: #fff;
        }

        .hero-stat-lbl {
            font-size: .75rem;
            color: rgba(209,221,219,.55);
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Section commons ───────────────────── */
        section { padding: 5rem 0; }

        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-size: .72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .09em;
            color: var(--ocean);
            background: rgba(29,106,150,.08);
            border: 1px solid rgba(29,106,150,.18);
            padding: .28rem .85rem;
            border-radius: 20px;
            margin-bottom: 1rem;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.8rem, 4vw, 2.8rem);
            font-weight: 700;
            color: var(--deep);
            line-height: 1.15;
            margin-bottom: .75rem;
        }

        .section-sub {
            font-size: .95rem;
            color: #5d7d84;
            line-height: 1.7;
            font-weight: 300;
            max-width: 560px;
        }

        /* ── About ─────────────────────────────── */
        .about { background: var(--white); }

        .about-img-wrap {
            position: relative;
        }

        .about-img-wrap .blob {
            width: 100%;
            max-width: 420px;
            border-radius: 40% 60% 55% 45% / 45% 45% 55% 55%;
            overflow: hidden;
            background: var(--fog);
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .about-svg { width: 75%; }

        .about-tag {
            position: absolute;
            background: var(--deep);
            color: #fff;
            padding: .75rem 1.2rem;
            border-radius: 12px;
            font-size: .82rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: .5rem;
            box-shadow: 0 8px 24px rgba(0,0,0,.18);
        }

        .about-tag.t1 { bottom: 10%; right: -5%; }
        .about-tag.t2 { top: 8%; left: -5%; background: var(--ocean); }

        .about-feature {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .about-feature-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            background: rgba(29,106,150,.08);
            color: var(--ocean);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .about-feature-title {
            font-weight: 600;
            font-size: .95rem;
            color: var(--deep);
            margin-bottom: .25rem;
        }

        .about-feature-desc {
            font-size: .85rem;
            color: #5d7d84;
            line-height: 1.6;
        }

        /* ── Menu ──────────────────────────────── */
        .menu { background: var(--fog); }

        .menu-filter {
            display: flex;
            gap: .5rem;
            flex-wrap: wrap;
            margin-bottom: 2.5rem;
        }

        .filter-btn {
            padding: .4rem 1.1rem;
            border-radius: 20px;
            border: 1.5px solid rgba(29,106,150,.25);
            background: transparent;
            color: var(--ocean);
            font-size: .82rem;
            font-weight: 500;
            cursor: pointer;
            transition: all .2s;
            font-family: inherit;
        }

        .filter-btn.active,
        .filter-btn:hover {
            background: var(--ocean);
            color: #fff;
            border-color: var(--ocean);
        }

        .menu-card {
            background: var(--white);
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(133,184,203,.2);
            transition: transform .25s, box-shadow .25s;
        }

        .menu-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(40,59,66,.12);
        }

        .menu-card-img {
            background: linear-gradient(135deg, var(--fog) 0%, var(--sky) 100%);
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .menu-card-img .cup-mini {
            width: 100px;
            filter: drop-shadow(0 8px 16px rgba(0,0,0,.2));
        }

        .menu-card-img .menu-badge {
            position: absolute;
            top: 12px; right: 12px;
            background: var(--deep);
            color: #fff;
            font-size: .65rem;
            font-weight: 600;
            padding: .2rem .65rem;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .menu-card-img .menu-badge.new { background: var(--ocean); }

        .menu-card-body {
            padding: 1.25rem;
        }

        .menu-card-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--deep);
            margin-bottom: .35rem;
        }

        .menu-card-desc {
            font-size: .8rem;
            color: #5d7d84;
            line-height: 1.55;
            margin-bottom: 1rem;
        }

        .menu-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .menu-price {
            font-family: 'DM Mono', monospace;
            font-size: 1.1rem;
            font-weight: 500;
            color: var(--ocean);
        }

        .btn-add {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: var(--ocean);
            color: #fff;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.1rem;
            transition: all .2s;
        }

        .btn-add:hover {
            background: var(--deep);
            transform: scale(1.1);
        }

        /* ── Why us ────────────────────────────── */
        .why { background: var(--deep); }

        .why .section-title { color: var(--fog); }
        .why .section-sub   { color: rgba(209,221,219,.6); }
        .why .section-badge { color: var(--sky); background: rgba(133,184,203,.1); border-color: rgba(133,184,203,.2); }

        .why-card {
            background: rgba(255,255,255,.05);
            border: 1px solid rgba(133,184,203,.15);
            border-radius: 16px;
            padding: 1.8rem 1.5rem;
            text-align: center;
            transition: all .25s;
        }

        .why-card:hover {
            background: rgba(255,255,255,.08);
            border-color: rgba(133,184,203,.3);
            transform: translateY(-4px);
        }

        .why-icon {
            width: 64px; height: 64px;
            border-radius: 16px;
            background: rgba(29,106,150,.25);
            color: var(--sky);
            font-size: 1.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
        }

        .why-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--fog);
            margin-bottom: .5rem;
        }

        .why-desc {
            font-size: .84rem;
            color: rgba(209,221,219,.55);
            line-height: 1.65;
            font-weight: 300;
        }

        /* ── Testimonials ──────────────────────── */
        .testimonials { background: var(--white); }

        .testi-card {
            background: var(--fog);
            border-radius: 16px;
            padding: 1.75rem;
            height: 100%;
            border: 1px solid rgba(133,184,203,.2);
            transition: transform .2s;
        }

        .testi-card:hover { transform: translateY(-4px); }

        .testi-stars {
            color: var(--ocean);
            font-size: .9rem;
            margin-bottom: .85rem;
        }

        .testi-text {
            font-size: .9rem;
            color: var(--deep);
            line-height: 1.7;
            font-style: italic;
            margin-bottom: 1.25rem;
        }

        .testi-author {
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .testi-avatar {
            width: 40px; height: 40px;
            border-radius: 50%;
            background: var(--ocean);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .78rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .testi-name  { font-weight: 600; font-size: .88rem; color: var(--deep); }
        .testi-role  { font-size: .75rem; color: #5d7d84; }

        /* ── Location ──────────────────────────── */
        .location { background: var(--fog); }

        .location-card {
            background: var(--white);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(40,59,66,.1);
        }

        .location-map {
            background: var(--deep);
            height: 280px;
            padding: 0;
            display: block;
            position: relative;
            overflow: hidden;
        }

        .location-map iframe {
            display: block;
            width: 100%;
            height: 100%;
            border: 0;
        }

        .btn-directions {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: var(--ocean);
            color: #fff;
            text-decoration: none;
            padding: .65rem 1.25rem;
            border-radius: 20px;
            font-size: .85rem;
            font-weight: 600;
            transition: all .2s;
            margin-top: .25rem;
            margin-bottom: 1.25rem;
        }

        .btn-directions:hover {
            background: var(--deep);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(40,59,66,.2);
        }

        .location-info { padding: 2rem 2.5rem; }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .info-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            background: rgba(29,106,150,.08);
            color: var(--ocean);
            font-size: 1.05rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .info-label { font-size: .72rem; font-weight: 600; color: #5d7d84; text-transform: uppercase; letter-spacing: .06em; margin-bottom: .2rem; }
        .info-val   { font-size: .92rem; color: var(--deep); font-weight: 500; }

        /* ── CTA ───────────────────────────────── */
        .cta-section {
            background: var(--ocean);
            padding: 5rem 0;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: rgba(255,255,255,.06);
            top: -150px; right: -100px;
        }

        .cta-section::after {
            content: '';
            position: absolute;
            width: 250px; height: 250px;
            border-radius: 50%;
            background: rgba(255,255,255,.04);
            bottom: -80px; left: -60px;
        }

        .cta-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.8rem, 4vw, 2.6rem);
            font-weight: 700;
            color: #fff;
            margin-bottom: .75rem;
        }

        .cta-sub {
            color: rgba(255,255,255,.75);
            font-size: .95rem;
            font-weight: 300;
            margin-bottom: 2rem;
            max-width: 500px;
        }

        .btn-cta {
            background: #fff;
            color: var(--ocean);
            border: none;
            padding: .85rem 2rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: .97rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            transition: all .2s;
        }

        .btn-cta:hover {
            background: var(--fog);
            color: var(--deep);
            transform: translateY(-2px);
        }

        /* ── Footer ────────────────────────────── */
        footer {
            background: var(--deep);
            padding: 4rem 0 2rem;
            color: rgba(209,221,219,.6);
        }

        .footer-logo-text {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            color: var(--fog);
            font-weight: 700;
            margin-bottom: .4rem;
        }

        .footer-tagline {
            font-size: .82rem;
            color: rgba(209,221,219,.45);
            margin-bottom: 1.5rem;
        }

        .footer-social {
            display: flex;
            gap: .6rem;
        }

        .social-btn {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.1);
            color: rgba(255,255,255,.6);
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 1rem;
            transition: all .2s;
        }

        .social-btn:hover { background: var(--ocean); color: #fff; border-color: var(--ocean); }

        .footer-heading {
            font-size: .72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .09em;
            color: var(--sky);
            margin-bottom: 1.1rem;
        }

        .footer-link {
            display: block;
            color: rgba(209,221,219,.5);
            text-decoration: none;
            font-size: .85rem;
            margin-bottom: .6rem;
            transition: color .2s;
        }

        .footer-link:hover { color: var(--fog); }

        .footer-divider {
            border: none;
            border-top: 1px solid rgba(133,184,203,.12);
            margin: 2.5rem 0 1.5rem;
        }

        .footer-copy {
            font-size: .78rem;
            color: rgba(209,221,219,.3);
        }

        /* ── Hours ─────────────────────────────── */
        .hours { background: var(--white); }

        .hours-card {
            background: var(--cream);
            border: 1px solid rgba(133,184,203,.25);
            border-radius: 20px;
            padding: 2.2rem 2rem;
            max-width: 480px;
            box-shadow: 0 12px 40px rgba(40,59,66,.06);
        }

        .hours-status {
            text-align: center;
            margin-bottom: 1.6rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-size: .95rem;
            font-weight: 700;
            padding: .55rem 1.4rem;
            border-radius: 24px;
            letter-spacing: .03em;
        }

        .status-badge.is-open {
            background: rgba(34,197,94,.12);
            color: #16a34a;
            border: 1px solid rgba(34,197,94,.35);
        }

        .status-badge.is-closed {
            background: rgba(239,68,68,.1);
            color: #dc2626;
            border: 1px solid rgba(239,68,68,.3);
        }

        .hours-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .hours-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .7rem 1rem;
            border-radius: 10px;
            font-size: .92rem;
            transition: background .15s;
        }

        .hours-row + .hours-row {
            border-top: 1px solid rgba(133,184,203,.18);
        }

        .hours-day {
            font-weight: 500;
            color: var(--deep);
        }

        .hours-time {
            font-family: 'DM Mono', monospace;
            font-weight: 500;
            color: var(--ocean);
        }

        .hours-row.is-today {
            background: rgba(29,106,150,.08);
            border-top-color: transparent;
        }

        .hours-row.is-today + .hours-row {
            border-top-color: transparent;
        }

        .hours-row.is-today .hours-day {
            color: var(--ocean);
            font-weight: 700;
        }

        .hours-row.is-today .hours-day::before {
            content: '★ ';
            color: var(--ocean);
            font-size: .85em;
        }

        /* ── WhatsApp float (HU-25) ───────────── */
        .whatsapp-float {
            position: fixed;
            bottom: 24px; right: 24px;
            width: 60px; height: 60px;
            border-radius: 50%;
            background: #25D366;
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.9rem;
            box-shadow: 0 8px 24px rgba(37,211,102,.4);
            z-index: 999;
            transition: all .25s;
            text-decoration: none;
            animation: wa-pulse 2.5s ease-in-out infinite;
        }

        .whatsapp-float:hover {
            transform: scale(1.1);
            color: #fff;
            box-shadow: 0 12px 32px rgba(37,211,102,.55);
        }

        @keyframes wa-pulse {
            0%, 100% { box-shadow: 0 8px 24px rgba(37,211,102,.4); }
            50%      { box-shadow: 0 8px 32px rgba(37,211,102,.7), 0 0 0 12px rgba(37,211,102,.1); }
        }

        /* ── Consult button (HU-26) ───────────── */
        .btn-consult {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: #25D366;
            color: #fff !important;
            border: none;
            padding: .45rem .9rem;
            border-radius: 20px;
            font-size: .78rem;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s;
        }

        .btn-consult:hover { background: #1da851; transform: translateY(-2px); }
        .btn-consult i { font-size: .95rem; }

        /* ── Responsive ────────────────────────── */
        @media (max-width: 991px) {
            .about-tag.t1, .about-tag.t2 { display: none; }
            .hero-stats { gap: 1.5rem; }
        }

        @media (max-width: 767px) {
            section { padding: 3.5rem 0; }
            .hero { min-height: auto; padding: 3rem 0; }
            .hero-visual { margin-top: 2.5rem; }
            .hero-cup { width: 160px; }
            .location-info { padding: 1.5rem; }
            .hours-card { padding: 1.6rem 1.2rem; }
        }

        @media (max-width: 576px) {
            .whatsapp-float {
                width: 54px; height: 54px;
                font-size: 1.7rem;
                bottom: 18px; right: 18px;
            }
        }
    </style>
</head>
<body>

    <!-- ── Navbar ──────────────────────────────── -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#hero-top">
                <img src="{{ asset('image/logo.png') }}" alt="Logo" class="nav-logo" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                <div style="display:none;width:38px;height:38px;border-radius:50%;background:var(--fog);align-items:center;justify-content:center;font-size:.8rem;font-weight:700;color:var(--deep);">PN</div>
                <span class="brand-name">Panda Naicha</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" style="color:rgba(255,255,255,.75);">
                <i class="bi bi-list" style="font-size:1.5rem;"></i>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navMenu">
                <ul class="navbar-nav align-items-center gap-1 mt-3 mt-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#about">Nosotros</a></li>
                    <li class="nav-item"><a class="nav-link" href="#menu">Menú</a></li>
                    <li class="nav-item"><a class="nav-link" href="#why">¿Por qué elegirnos?</a></li>
                    <li class="nav-item"><a class="nav-link" href="#hours">Horarios</a></li>
                    <li class="nav-item"><a class="nav-link" href="#location">Ubicación</a></li>
                    <li class="nav-item ms-2"><a class="nav-link btn-nav" href="{{ route('login') }}">Iniciar sesión</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ── Hero ────────────────────────────────── -->
    <section class="hero" id="hero-top">
        <!-- Decorative circles -->
        <div class="hero-bg-circle c1"></div>
        <div class="hero-bg-circle c2"></div>
        <div class="hero-bg-circle c3"></div>

        <!-- Floating pearls -->
        <div class="pearl" style="width:14px;height:14px;left:8%;bottom:0;--dur:6s;--delay:0s;"></div>
        <div class="pearl" style="width:10px;height:10px;left:18%;bottom:0;--dur:7s;--delay:1.2s;background:var(--fog);"></div>
        <div class="pearl" style="width:18px;height:18px;left:28%;bottom:0;--dur:5.5s;--delay:2.5s;"></div>
        <div class="pearl" style="width:8px; height:8px; left:55%;bottom:0;--dur:6.8s;--delay:.5s;background:var(--ocean);"></div>
        <div class="pearl" style="width:12px;height:12px;left:72%;bottom:0;--dur:5.8s;--delay:3.3s;"></div>
        <div class="pearl" style="width:16px;height:16px;left:85%;bottom:0;--dur:7.2s;--delay:1.8s;background:var(--fog);opacity:.15;"></div>

        <div class="container">
            <div class="row align-items-center gy-5">
                <div class="col-lg-6">
                    <div class="hero-badge">
                        <i class="bi bi-stars"></i>
                        Auténtica tapioca artesanal
                    </div>
                    <h1 class="hero-title">
                        Cada sorbo,<br>una <span>experiencia</span><br>única
                    </h1>
                    <p class="hero-desc">
                        Descubre nuestra selección de bebidas de tapioca elaboradas con ingredientes frescos, perlas cocinadas al momento y sabores únicos que te transportarán a otro mundo.
                    </p>
                    <div class="hero-actions">
                        <a href="#menu" class="btn-primary-hero">
                            <i class="bi bi-cup-straw"></i>
                            Ver menú
                        </a>
                        <a href="#about" class="btn-ghost-hero">
                            <i class="bi bi-play-circle"></i>
                            Conoce más
                        </a>
                    </div>
                    <div class="hero-stats">
                        <div>
                            <div class="hero-stat-val">20+</div>
                            <div class="hero-stat-lbl">Sabores únicos</div>
                        </div>
                        <div style="width:1px;background:rgba(209,221,219,.15);"></div>
                        <div>
                            <div class="hero-stat-val">500+</div>
                            <div class="hero-stat-lbl">Clientes felices</div>
                        </div>
                        <div style="width:1px;background:rgba(209,221,219,.15);"></div>
                        <div>
                            <div class="hero-stat-val">100%</div>
                            <div class="hero-stat-lbl">Ingredientes frescos</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-visual">
                        <div class="hero-cup-wrap">
                            <svg class="hero-cup" viewBox="0 0 200 280" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <!-- straw -->
                                <rect x="89" y="2" width="11" height="90" rx="5.5" fill="#85B8CB" opacity=".9"/>
                                <!-- cup body -->
                                <path d="M28 82 L42 258 Q42 268 52 268 H148 Q158 268 158 258 L172 82 Z" fill="#283B42"/>
                                <!-- shine -->
                                <path d="M42 95 L52 245 Q52 254 58 254 H70 L58 95 Z" fill="white" opacity=".07"/>
                                <!-- liquid -->
                                <path d="M31 108 L43 258 Q43 267 53 267 H147 Q157 267 157 258 L169 108 Z" fill="#1D6A96" opacity=".88"/>
                                <!-- liquid top -->
                                <ellipse cx="100" cy="108" rx="69" ry="10" fill="#85B8CB" opacity=".65"/>
                                <!-- pearls -->
                                <circle cx="72"  cy="228" r="13" fill="#1a2c32"/>
                                <circle cx="100" cy="238" r="14" fill="#1a2c32"/>
                                <circle cx="128" cy="227" r="12" fill="#1a2c32"/>
                                <circle cx="84"  cy="250" r="11" fill="#1a2c32"/>
                                <circle cx="116" cy="249" r="12" fill="#1a2c32"/>
                                <!-- pearl shines -->
                                <circle cx="68"  cy="223" r="4" fill="white" opacity=".2"/>
                                <circle cx="96"  cy="233" r="4" fill="white" opacity=".18"/>
                                <circle cx="124" cy="222" r="3.5" fill="white" opacity=".2"/>
                                <!-- lid -->
                                <ellipse cx="100" cy="82" rx="72" ry="14" fill="#D1DDDB"/>
                                <ellipse cx="100" cy="80" rx="65" ry="9"  fill="#85B8CB" opacity=".45"/>
                                <!-- band decoration -->
                                <path d="M36 148 L164 148" stroke="white" stroke-width="1.5" opacity=".06"/>
                                <!-- logo text on cup -->
                                <text x="100" y="180" text-anchor="middle" font-family="serif" font-size="11" fill="white" opacity=".35" font-style="italic">Panda Naicha</text>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── About ────────────────────────────────── -->
    <section class="about" id="about">
        <div class="container">
            <div class="row align-items-center gy-5">
                <div class="col-lg-5">
                    <div class="about-img-wrap d-flex justify-content-center">
                        <div class="blob">
                            <svg class="about-svg" viewBox="0 0 320 320" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <!-- background items -->
                                <circle cx="160" cy="160" r="130" fill="#85B8CB" opacity=".18"/>
                                <!-- large cup -->
                                <path d="M100 115 L113 255 Q113 262 120 262 H200 Q207 262 207 255 L220 115 Z" fill="#283B42"/>
                                <path d="M103 132 L114 255 Q114 261 121 261 H132 L120 132 Z" fill="white" opacity=".06"/>
                                <path d="M102 145 L113 255 Q113 261 120 261 H200 Q207 261 207 255 L218 145 Z" fill="#1D6A96" opacity=".85"/>
                                <ellipse cx="160" cy="145" rx="59" ry="9" fill="#85B8CB" opacity=".55"/>
                                <circle cx="141" cy="226" r="11" fill="#1a2c32"/>
                                <circle cx="160" cy="234" r="12" fill="#1a2c32"/>
                                <circle cx="179" cy="225" r="10" fill="#1a2c32"/>
                                <circle cx="149" cy="246" r="9"  fill="#1a2c32"/>
                                <circle cx="170" cy="245" r="10" fill="#1a2c32"/>
                                <circle cx="137" cy="221" r="3" fill="white" opacity=".2"/>
                                <circle cx="156" cy="229" r="3" fill="white" opacity=".18"/>
                                <ellipse cx="160" cy="115" rx="61" ry="12" fill="#D1DDDB"/>
                                <ellipse cx="160" cy="113" rx="55" ry="7.5" fill="#85B8CB" opacity=".4"/>
                                <rect x="118" y="78" width="8" height="55" rx="4" fill="#85B8CB" opacity=".8"/>
                                <!-- decorative elements -->
                                <circle cx="72"  cy="88"  r="14" fill="#1D6A96" opacity=".25"/>
                                <circle cx="258" cy="200" r="10" fill="#1D6A96" opacity=".2"/>
                                <circle cx="248" cy="90"  r="18" fill="#85B8CB" opacity=".2"/>
                                <circle cx="65"  cy="220" r="12" fill="#283B42" opacity=".2"/>
                                <!-- leaves -->
                                <ellipse cx="80" cy="155" rx="22" ry="9" fill="#2d8a5e" opacity=".3" transform="rotate(-25 80 155)"/>
                                <ellipse cx="245" cy="150" rx="18" ry="7" fill="#2d8a5e" opacity=".25" transform="rotate(20 245 150)"/>
                            </svg>
                        </div>
                        <div class="about-tag t1">
                            <i class="bi bi-award-fill" style="color:var(--sky);"></i>
                            Calidad premium
                        </div>
                        <div class="about-tag t2">
                            <i class="bi bi-heart-fill" style="color:#f87171;"></i>
                            Hecho con amor
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 offset-lg-1">
                    <div class="section-badge">
                        <i class="bi bi-info-circle"></i>
                        Nuestra historia
                    </div>
                    <h2 class="section-title">Pasión por la<br>tapioca auténtica</h2>
                    <p class="section-sub mb-4">
                        En Panda Naicha nacimos del amor por las bebidas asiáticas y la dedicación a ofrecer lo mejor. Cada bebida es preparada con perlas de tapioca frescas, tés de calidad y sabores que no encontrarás en ningún otro lugar.
                    </p>

                    <div class="about-feature">
                        <div class="about-feature-icon"><i class="bi bi-droplet-fill"></i></div>
                        <div>
                            <div class="about-feature-title">Ingredientes 100% naturales</div>
                            <div class="about-feature-desc">Seleccionamos cada ingrediente cuidadosamente. Sin colorantes artificiales, sin conservantes: solo sabor real.</div>
                        </div>
                    </div>
                    <div class="about-feature">
                        <div class="about-feature-icon"><i class="bi bi-clock-history"></i></div>
                        <div>
                            <div class="about-feature-title">Perlas cocinadas al momento</div>
                            <div class="about-feature-desc">Nuestras perlas de tapioca se cocinan frescos cada día para garantizar la textura perfecta en cada sorbo.</div>
                        </div>
                    </div>
                    <div class="about-feature">
                        <div class="about-feature-icon"><i class="bi bi-palette2"></i></div>
                        <div>
                            <div class="about-feature-title">Más de 20 sabores únicos</div>
                            <div class="about-feature-desc">Desde el clásico taro hasta combinaciones exóticas de frutas tropicales. Siempre habrá algo nuevo para descubrir.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── Menu ─────────────────────────────────── -->
    <section class="menu" id="menu">
        <div class="container">
            <div class="row justify-content-between align-items-end mb-5">
                <div class="col-lg-6">
                    <div class="section-badge"><i class="bi bi-cup-straw"></i> Nuestro menú</div>
                    <h2 class="section-title">Bebidas que enamoran</h2>
                    <p class="section-sub">Desde clásicos hasta creaciones originales, cada bebida es una experiencia.</p>
                </div>
                <div class="col-lg-auto mt-3 mt-lg-0">
                    <div class="menu-filter">
                        <button class="filter-btn active" data-filter="all">Todos</button>
                        <button class="filter-btn" data-filter="BEBIDA">Bebidas</button>
                        <button class="filter-btn" data-filter="TOPPING">Toppings</button>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                @forelse ($products as $product)
                    <div class="col-lg-4 col-md-6" data-category="{{ $product->category }}">
                        <div class="menu-card">
                            <div class="menu-card-img" style="background: linear-gradient(135deg,#e8d5c8,#c4a882);">
                                <svg class="cup-mini" viewBox="0 0 120 170" fill="none">
                                    <rect x="52" y="2" width="7" height="48" rx="3.5" fill="#85B8CB" opacity=".8"/>
                                    <path d="M16 50 L24 155 Q24 161 30 161 H90 Q96 161 96 155 L104 50 Z" fill="#6b4c38"/>
                                    <path d="M18 62 L24 155 Q24 160 30 160 H90 Q96 160 96 155 L102 62 Z" fill="#c4824a" opacity=".8"/>
                                    <ellipse cx="60" cy="62" rx="43" ry="7" fill="#e8a870" opacity=".6"/>
                                    <circle cx="44" cy="136" r="8" fill="#3d2a1a"/><circle cx="60" cy="143" r="9" fill="#3d2a1a"/><circle cx="76" cy="135" r="8" fill="#3d2a1a"/>
                                    <circle cx="41" cy="132" r="2.5" fill="white" opacity=".2"/>
                                    <ellipse cx="60" cy="50" rx="44" ry="8.5" fill="#D1DDDB"/>
                                </svg>
                                @if ($product->category === 'TOPPING')
                                    <span class="menu-badge">Topping</span>
                                @endif
                            </div>
                            <div class="menu-card-body">
                                <div class="menu-card-name">{{ $product->name }}</div>
                                <div class="menu-card-desc">Elaborado con ingredientes frescos. Consulta disponibilidad.</div>
                                <div class="menu-card-footer">
                                    <div class="menu-price">Bs {{ number_format($product->price, 0) }}</div>
                                    <a href="https://wa.me/59172088603?text={{ urlencode('Hola, quiero consultar por: ' . $product->name) }}"
                                       target="_blank" rel="noopener"
                                       class="btn-consult"
                                       aria-label="Consultar por {{ $product->name }} en WhatsApp">
                                        <i class="bi bi-whatsapp"></i> Consultar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center" style="color:#5d7d84;font-size:.95rem;padding:2rem 0;">
                        No hay productos disponibles por el momento.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ── Why us ────────────────────────────────── -->
    <section class="why" id="why">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-badge mx-auto"><i class="bi bi-patch-check"></i> Por qué elegirnos</div>
                <h2 class="section-title text-center">Lo que nos hace especiales</h2>
                <p class="section-sub mx-auto text-center" style="color:rgba(209,221,219,.55);">Cada detalle importa. Desde el primer sorbo hasta el último.</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="why-card">
                        <div class="why-icon"><i class="bi bi-droplet-fill"></i></div>
                        <div class="why-title">100% Natural</div>
                        <div class="why-desc">Ingredientes frescos y naturales sin aditivos artificiales. Tu salud es nuestra prioridad.</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="why-card">
                        <div class="why-icon"><i class="bi bi-lightning-charge"></i></div>
                        <div class="why-title">Preparación rápida</div>
                        <div class="why-desc">Tu bebida lista en menos de 3 minutos, sin sacrificar calidad ni sabor.</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="why-card">
                        <div class="why-icon"><i class="bi bi-sliders"></i></div>
                        <div class="why-title">Personalizable</div>
                        <div class="why-desc">Elige el nivel de azúcar, hielo, tipo de leche y toppings a tu gusto.</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="why-card">
                        <div class="why-icon"><i class="bi bi-heart"></i></div>
                        <div class="why-title">Hecho con amor</div>
                        <div class="why-desc">Cada bebida es preparada con dedicación y pasión por nuestro equipo.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── Testimonials ────────────────────────── -->
    <section class="testimonials">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-badge mx-auto"><i class="bi bi-chat-quote"></i> Testimonios</div>
                <h2 class="section-title text-center">Lo que dicen nuestros clientes</h2>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="testi-card">
                        <div class="testi-stars">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                        <p class="testi-text">"El Brown Sugar Boba es simplemente adictivo. Las perlas tienen la textura perfecta y el sabor es increíble. ¡Ya voy todos los fines de semana!"</p>
                        <div class="testi-author">
                            <div class="testi-avatar">ML</div>
                            <div>
                                <div class="testi-name">María López</div>
                                <div class="testi-role">Cliente frecuente</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="testi-card">
                        <div class="testi-stars">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                        <p class="testi-text">"Probé el Taro Latte por primera vez y me voló la cabeza. El ambiente es súper bonito y el servicio excelente. 100% recomendado."</p>
                        <div class="testi-author">
                            <div class="testi-avatar" style="background:var(--deep);">CR</div>
                            <div>
                                <div class="testi-name">Carlos Ríos</div>
                                <div class="testi-role">Cliente nuevo</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="testi-card">
                        <div class="testi-stars">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i>
                        </div>
                        <p class="testi-text">"Me encanta que puedo personalizar todo. El nivel de azúcar, el hielo... Siempre me hacen exactamente lo que quiero. Son los mejores."</p>
                        <div class="testi-author">
                            <div class="testi-avatar" style="background:#5d7d84;">AV</div>
                            <div>
                                <div class="testi-name">Ana Vargas</div>
                                <div class="testi-role">Cliente fiel</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── Hours ───────────────────────────────── -->
    <section class="hours" id="hours">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-badge mx-auto"><i class="bi bi-clock-history"></i> Horarios</div>
                <h2 class="section-title text-center">Horarios de Atención</h2>
                <p class="section-sub mx-auto text-center">Estamos abiertos todos los días para servirte el mejor bubble tea.</p>
            </div>

            <div class="hours-card mx-auto">
                <div class="hours-status">
                    <span id="open-status-badge" class="status-badge is-closed">● Cerrado</span>
                </div>

                <ul class="hours-list">
                    <li class="hours-row" data-day="1">
                        <span class="hours-day">Lunes</span>
                        <span class="hours-time">09:00 – 21:00</span>
                    </li>
                    <li class="hours-row" data-day="2">
                        <span class="hours-day">Martes</span>
                        <span class="hours-time">09:00 – 21:00</span>
                    </li>
                    <li class="hours-row" data-day="3">
                        <span class="hours-day">Miércoles</span>
                        <span class="hours-time">09:00 – 21:00</span>
                    </li>
                    <li class="hours-row" data-day="4">
                        <span class="hours-day">Jueves</span>
                        <span class="hours-time">09:00 – 21:00</span>
                    </li>
                    <li class="hours-row" data-day="5">
                        <span class="hours-day">Viernes</span>
                        <span class="hours-time">09:00 – 21:00</span>
                    </li>
                    <li class="hours-row" data-day="6">
                        <span class="hours-day">Sábado</span>
                        <span class="hours-time">09:00 – 21:00</span>
                    </li>
                    <li class="hours-row" data-day="0">
                        <span class="hours-day">Domingo</span>
                        <span class="hours-time">09:00 – 21:00</span>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <!-- ── Location ───────────────────────────── -->
    <section class="location" id="location">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-badge mx-auto"><i class="bi bi-geo-alt"></i> Encuéntranos</div>
                <h2 class="section-title text-center">¿Dónde estamos?</h2>
                <p class="section-sub mx-auto text-center">Visítanos y vive la experiencia Panda Naicha en persona.</p>
            </div>
            <div class="location-card">
                <div class="row g-0">
                    <div class="col-lg-6">
                        <div class="location-map">
                            <iframe
                                src="https://maps.google.com/maps?q=Av.%2020%20de%20Octubre%202038,%20La%20Paz,%20Bolivia&t=&z=16&ie=UTF8&iwloc=&output=embed"
                                width="100%" height="100%"
                                style="border:0;"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                title="Ubicación de Panda Naicha"></iframe>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="location-info">
                            <h3 style="font-family:'Playfair Display',serif;font-size:1.4rem;color:var(--deep);margin-bottom:1.5rem;">Panda Naicha</h3>

                            <div class="info-item">
                                <div class="info-icon"><i class="bi bi-geo-alt-fill"></i></div>
                                <div>
                                    <div class="info-label">Dirección</div>
                                    <div class="info-val">Av. 20 de Octubre 2038, La Paz, Bolivia</div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="bi bi-clock-fill"></i></div>
                                <div>
                                    <div class="info-label">Horario</div>
                                    <div class="info-val">Lunes a Domingo: 09:00 – 21:00</div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="bi bi-telephone-fill"></i></div>
                                <div>
                                    <div class="info-label">Teléfono / WhatsApp</div>
                                    <div class="info-val">+591 72088603</div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="bi bi-envelope-fill"></i></div>
                                <div>
                                    <div class="info-label">Correo</div>
                                    <div class="info-val">pandanaichalapaz@gmail.com</div>
                                </div>
                            </div>

                            <a href="https://www.google.com/maps/dir/?api=1&destination=Av.+20+de+Octubre+2038,+La+Paz,+Bolivia"
                               target="_blank" rel="noopener"
                               class="btn-directions">
                                <i class="bi bi-signpost-2-fill"></i> Cómo llegar
                            </a>

                            <div class="mt-3" style="display:flex;gap:.6rem;">
                                <a href="https://www.instagram.com/pandanaicha/" target="_blank" rel="noopener" class="social-btn" style="background:rgba(29,106,150,.1);border-color:rgba(29,106,150,.2);color:var(--ocean);" aria-label="Instagram">
                                    <i class="bi bi-instagram"></i>
                                </a>
                                <a href="https://www.facebook.com/Panda.Naicha.LP/directory_contact_info" target="_blank" rel="noopener" class="social-btn" style="background:rgba(29,106,150,.1);border-color:rgba(29,106,150,.2);color:var(--ocean);" aria-label="Facebook">
                                    <i class="bi bi-facebook"></i>
                                </a>
                                <a href="https://www.tiktok.com/@pandanaicha" target="_blank" rel="noopener" class="social-btn" style="background:rgba(29,106,150,.1);border-color:rgba(29,106,150,.2);color:var(--ocean);" aria-label="TikTok">
                                    <i class="bi bi-tiktok"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── CTA ───────────────────────────────── -->
    <section class="cta-section">
        <div class="container position-relative" style="z-index:2;">
            <div class="row align-items-center gy-4">
                <div class="col-lg-8">
                    <h2 class="cta-title">¿Listo para tu primer sorbo?</h2>
                    <p class="cta-sub">Visítanos hoy y descubre por qué somos la tienda de tapioca favorita de la ciudad. Tu bebida perfecta te espera.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="#menu" class="btn-cta">
                        <i class="bi bi-cup-straw"></i>
                        Ver nuestro menú
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ── Footer ────────────────────────────── -->
    <footer>
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-logo-text">Panda Naicha</div>
                    <div class="footer-tagline">Tapioca artesanal con amor</div>
                    <p style="font-size:.82rem;color:rgba(209,221,219,.4);line-height:1.7;margin-bottom:1.25rem;">
                        La mejor tienda de bebidas de tapioca de La Paz. Ingredientes frescos, sabores únicos y el mejor servicio.
                    </p>
                    <div class="footer-social">
                        <a href="https://www.instagram.com/pandanaicha/" target="_blank" rel="noopener" class="social-btn" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="https://www.facebook.com/Panda.Naicha.LP/directory_contact_info" target="_blank" rel="noopener" class="social-btn" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="https://www.tiktok.com/@pandanaicha" target="_blank" rel="noopener" class="social-btn" aria-label="TikTok"><i class="bi bi-tiktok"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 offset-lg-1">
                    <div class="footer-heading">Menú</div>
                    <a href="#" class="footer-link">Brown Sugar Boba</a>
                    <a href="#" class="footer-link">Taro Latte</a>
                    <a href="#" class="footer-link">Matcha con Perlas</a>
                    <a href="#" class="footer-link">Mango Tropical</a>
                    <a href="#" class="footer-link">Ver todos</a>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="footer-heading">Nosotros</div>
                    <a href="#about" class="footer-link">Nuestra historia</a>
                    <a href="#why" class="footer-link">¿Por qué elegirnos?</a>
                    <a href="#location" class="footer-link">Ubicación</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-heading">Contacto</div>
                    <div style="font-size:.82rem;color:rgba(209,221,219,.45);line-height:2;">
                        <div><i class="bi bi-geo-alt" style="color:var(--sky);margin-right:.4rem;"></i> Av. 20 de Octubre 2038, La Paz</div>
                        <div><i class="bi bi-clock" style="color:var(--sky);margin-right:.4rem;"></i> Lun – Dom 09:00 – 21:00</div>
                        <div><i class="bi bi-telephone" style="color:var(--sky);margin-right:.4rem;"></i> +591 72088603</div>
                        <div><i class="bi bi-envelope" style="color:var(--sky);margin-right:.4rem;"></i> pandanaichalapaz@gmail.com</div>
                    </div>
                </div>
            </div>
            <hr class="footer-divider">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="footer-copy">© {{ date('Y') }} Panda Naicha. Todos los derechos reservados.</div>
                <div class="footer-copy">Hecho con <i class="bi bi-heart-fill" style="color:var(--sky);font-size:.7rem;"></i> en Bolivia</div>
            </div>
        </div>
    </footer>

    <!-- ── Floating WhatsApp button ─────────────── -->
    <a href="https://wa.me/59172088603?text=Hola%2C%20me%20gustar%C3%ADa%20hacer%20una%20consulta%20sobre%20Panda%20Naicha"
       target="_blank" rel="noopener"
       class="whatsapp-float"
       aria-label="Contactar por WhatsApp">
        <i class="bi bi-whatsapp"></i>
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filter buttons (Todos / Bebidas / Toppings)
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const filter = this.dataset.filter || 'all';
                document.querySelectorAll('[data-category]').forEach(card => {
                    const matches = filter === 'all' || card.dataset.category === filter;
                    card.style.display = matches ? '' : 'none';
                });
            });
        });

        // Smooth scroll offset for sticky navbar
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', function (e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    const offset = 70;
                    window.scrollTo({ top: target.offsetTop - offset, behavior: 'smooth' });
                }
            });
        });

        // Open / closed badge + highlight current day (HU-24)
        function updateOpenStatus() {
            const now = new Date();
            const currentMinutes = now.getHours() * 60 + now.getMinutes();
            const openMinutes  = 9 * 60;   // 09:00
            const closeMinutes = 21 * 60;  // 21:00
            const isOpen = currentMinutes >= openMinutes && currentMinutes < closeMinutes;

            const badge = document.getElementById('open-status-badge');
            if (badge) {
                badge.textContent = isOpen ? '● Abierto ahora' : '● Cerrado';
                badge.classList.toggle('is-open',   isOpen);
                badge.classList.toggle('is-closed', !isOpen);
            }

            const today = now.getDay(); // 0 = Domingo
            document.querySelectorAll('[data-day]').forEach(el => {
                el.classList.toggle('is-today', Number(el.dataset.day) === today);
            });
        }

        updateOpenStatus();
        setInterval(updateOpenStatus, 60_000);
    </script>
</body>
</html>