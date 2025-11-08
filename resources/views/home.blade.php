<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Readify - Modern Library Management</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      height: 100%;
    }
    html {
      height: 100%;
    }
    .gradient-bg {
      background: linear-gradient(135deg, #000000 0%, #1a1a1a 50%, #000000 100%);
    }
    .glass-effect {
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .glow-effect { box-shadow: 0 0 30px rgba(255, 255, 255, 0.1); }
    .hover-glow:hover {
      box-shadow: 0 0 40px rgba(255, 255, 255, 0.2);
      transform: translateY(-2px);
      transition: all 0.3s ease;
    }
    .text-glow { text-shadow: 0 0 20px rgba(255, 255, 255, 0.3); }
    .hero-gradient {
      background: radial-gradient(circle at center, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
    }
    .feature-card {
      background: linear-gradient(145deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.02));
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      transition: all 0.3s ease;
    }
    .feature-card:hover {
      background: linear-gradient(145deg, rgba(255, 255, 255, 0.08), rgba(255, 255, 255, 0.04));
      box-shadow: 0 10px 40px rgba(255, 255, 255, 0.1);
      transform: translateY(-5px);
    }
    .nav-link { position: relative; color: #ffffff; text-decoration: none; font-weight: 500; transition: color 0.3s ease; }
    .nav-link::after { content: ""; position: absolute; left: 0; bottom: -4px; height: 2px; width: 0%; background: linear-gradient(90deg, rgba(255,255,255,0.1), rgba(255,255,255,0.8), rgba(255,255,255,0.1)); transition: width 0.3s ease; }
    .nav-link:hover { color: rgba(255,255,255,0.85); }
    .nav-link:hover::after { width: 100%; }
    .cta-button {
      background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 100%);
      color: #000000;
      border: 1px solid rgba(255, 255, 255, 0.3);
      box-shadow: 0 0 30px rgba(255, 255, 255, 0.2);
      transition: all 0.3s ease;
    }
    .cta-button:hover {
      background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%);
      box-shadow: 0 0 50px rgba(255, 255, 255, 0.4);
      transform: translateY(-2px);
    }
    .stats-number {
      background: linear-gradient(135deg, #ffffff 0%, #cccccc 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }
    .floating { animation: float 6s ease-in-out infinite; }
    .mobile-menu { transform: translateX(100%); transition: transform 0.3s ease; }
    .mobile-menu.active { transform: translateX(0); }
    .footer-link { position: relative; overflow: hidden; }
    .footer-link::before {
      content: '';
      position: absolute;
      bottom: 0; left: -100%; width: 100%; height: 2px;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.6), transparent);
      transition: left 0.5s ease;
    }
    .footer-link:hover::before { left: 100%; }
    .footer-link:hover {
      color: rgba(255, 255, 255, 0.9) !important;
      text-shadow: 0 0 8px rgba(255, 255, 255, 0.3);
      transform: translateX(8px);
    }
    /* Navbar: static and transparent (matches portal) */
    nav.navbar { position: static; z-index: 50; background: transparent; }
  </style>
  <style>@view-transition { navigation: auto; }</style>
 </head>
 <body>
  <div class="min-h-full gradient-bg">
    @include('partials.navbar')
    <!-- Hero Section -->
    <section id="home" class="hero-gradient" style="padding: 120px 24px 80px; text-align: center; position: relative; overflow: hidden;">
      <div style="max-width: 1200px; margin: 0 auto; position: relative; z-index: 2;">
        <div class="floating" style="margin-bottom: 32px;">
          <h1 class="text-glow" style="font-size: 56px; font-weight: 800; color: #ffffff; margin: 0 0 24px 0; line-height: 1.1; letter-spacing: -0.02em;">
            Readify: Your Favourite Library
          </h1>
          <p style="font-size: 20px; color: #888888; margin: 0 0 40px 0; max-width: 600px; margin-left: auto; margin-right: auto; line-height: 1.6;">
            Modern digital library management system with advanced features for seamless book tracking and user management
          </p>
        </div>
        <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap; margin-bottom: 60px;">
          <a href="{{ route('borrow.portal') }}" class="cta-button hover-glow" style="padding: 16px 32px; border-radius: 12px; font-weight: 600; font-size: 18px; border: none; cursor: pointer; text-decoration:none; display:inline-flex; align-items:center; justify-content:center;">Get Started</a>
          <button class="glass-effect hover-glow" style="padding: 16px 32px; border-radius: 12px; font-weight: 600; font-size: 18px; background: transparent; color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.3); cursor: pointer;">Watch Demo</button>
        </div>
        <!-- Stats -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; margin-top: 80px;">
          <div style="text-align: center;">
            <div class="stats-number" style="font-size: 48px; font-weight: 800; margin-bottom: 8px;">100+</div>
            <div style="color: #888888; font-size: 16px;">Books Managed</div>
          </div>
          <div style="text-align: center;">
            <div class="stats-number" style="font-size: 48px; font-weight: 800; margin-bottom: 8px;">10</div>
            <div style="color: #888888; font-size: 16px;">categories</div>
          </div>
          <div style="text-align: center;">
            <div class="stats-number" style="font-size: 48px; font-weight: 800; margin-bottom: 8px;">99.9%</div>
            <div style="color: #888888; font-size: 16px;">Uptime</div>
          </div>
          <div style="text-align: center;">
            <div class="stats-number" style="font-size: 48px; font-weight: 800; margin-bottom: 8px;">24/7</div>
            <div style="color: #888888; font-size: 16px;">Support</div>
          </div>
        </div>
      </div>
      <!-- Background Effects -->
      <div style="position: absolute; top: 20%; left: 10%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>
      <div style="position: absolute; bottom: 20%; right: 10%; width: 200px; height: 200px; background: radial-gradient(circle, rgba(255, 255, 255, 0.03) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>
    </section>

    <!-- Features Section -->
    <section id="features" style="padding: 80px 24px; background: linear-gradient(180deg, transparent 0%, rgba(255, 255, 255, 0.02) 50%, transparent 100%);">
      <div style="max-width: 1200px; margin: 0 auto;">
        <div style="text-align: center; margin-bottom: 60px;">
          <h2 style="font-size: 40px; font-weight: 700; color: #ffffff; margin: 0 0 20px 0; text-shadow: 0 0 20px rgba(255, 255, 255, 0.2);">Powerful Features</h2>
          <p style="font-size: 18px; color: #888888; max-width: 600px; margin: 0 auto;">Everything you need to manage your library efficiently and effectively</p>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 32px;">
          <div class="feature-card hover-glow" style="padding: 40px; border-radius: 16px;">
            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #ffffff, rgba(255, 255, 255, 0.7)); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 24px;">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 19.5C4 18.1193 5.11929 17 6.5 17H20" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M6.5 2H20V22H6.5C5.11929 22 4 20.8807 4 19.5V4.5C4 3.11929 5.11929 2 6.5 2Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M9 7H17" stroke="#000000" stroke-width="2" stroke-linecap="round"/>
                <path d="M9 11H17" stroke="#000000" stroke-width="2" stroke-linecap="round"/>
              </svg>
            </div>
            <h3 style="font-size: 24px; font-weight: 600; color: #ffffff; margin: 0 0 16px 0;">Smart Book Management</h3>
            <p style="color: #888888; line-height: 1.6; font-size: 16px;">Efficiently organize and categorize your entire book collection with advanced search and filtering capabilities.</p>
          </div>
          <div class="feature-card hover-glow" style="padding: 40px; border-radius: 16px;">
            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #ffffff, rgba(255, 255, 255, 0.7)); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 24px;">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 3V21H21" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M9 9L12 6L16 10L21 5" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M21 5H17V9" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <h3 style="font-size: 24px; font-weight: 600; color: #ffffff; margin: 0 0 16px 0;">Real-time Tracking</h3>
            <p style="color: #888888; line-height: 1.6; font-size: 16px;">Monitor book loans, returns, and user activity in real-time with comprehensive tracking systems.</p>
          </div>
          <div class="feature-card hover-glow" style="padding: 40px; border-radius: 16px;">
            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #ffffff, rgba(255, 255, 255, 0.7)); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 24px;">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M22 12H18L15 21L9 3L6 12H2" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <h3 style="font-size: 24px; font-weight: 600; color: #ffffff; margin: 0 0 16px 0;">Advanced Analytics</h3>
            <p style="color: #888888; line-height: 1.6; font-size: 16px;">Gain insights into library usage patterns and make data-driven decisions with detailed analytics.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- About Section -->
    <section id="about" style="padding: 80px 24px;">
      <div style="max-width: 1200px; margin: 0 auto;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center;" class="lg:grid-cols-2 grid-cols-1">
          <div>
            <h2 style="font-size: 40px; font-weight: 700; color: #ffffff; margin: 0 0 24px 0; text-shadow: 0 0 20px rgba(255, 255, 255, 0.2);">About Readify</h2>
            <p style="color: #888888; line-height: 1.8; font-size: 18px; margin-bottom: 24px;">We're revolutionizing library management with cutting-edge technology that makes book tracking, user management, and analytics seamless and intuitive.</p>
            <p style="color: #888888; line-height: 1.8; font-size: 18px; margin-bottom: 32px;">Our platform combines modern design with powerful functionality to create the ultimate library management experience.</p>
            <button class="cta-button hover-glow" style="padding: 16px 32px; border-radius: 12px; font-weight: 600; font-size: 18px; border: none; cursor: pointer;">Learn More</button>
          </div>
          <div class="glass-effect" style="padding: 40px; border-radius: 20px; text-align: center;">
            <div style="margin-bottom: 20px; display: flex; justify-content: center;">
              <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M2 20H22V22H2V20Z" fill="rgba(255, 255, 255, 0.8)"/>
                <path d="M4 20V10L12 4L20 10V20" stroke="rgba(255, 255, 255, 0.8)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M6 20V12H18V20" stroke="rgba(255, 255, 255, 0.8)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M8 16H10" stroke="rgba(255, 255, 255, 0.8)" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M14 16H16" stroke="rgba(255, 255, 255, 0.8)" stroke-width="1.5" stroke-linecap="round"/>
              </svg>
            </div>
            <h3 style="font-size: 24px; font-weight: 600; color: #ffffff; margin: 0 0 16px 0;">Modern Library Solutions</h3>
            <p style="color: #888888; line-height: 1.6;">Built for the future of library management</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="glass-effect" style="margin-top: 80px; padding: 60px 24px 40px; border-top: 1px solid rgba(255, 255, 255, 0.1);">
      <div style="max-width: 1200px; margin: 0 auto;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; margin-bottom: 40px;">
          <!-- Company Info -->
          <div>
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
              <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #ffffff, rgba(255, 255, 255, 0.7)); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #000000; font-size: 20px;">R</div>
              <span style="font-size: 24px; font-weight: 700; color: #ffffff;">Readify</span>
            </div>
            <p style="color: #888888; line-height: 1.6; font-size: 16px;">Revolutionizing library management with cutting-edge technology</p>
          </div>
          <!-- Quick Links -->
          <div>
            <h4 style="font-size: 18px; font-weight: 600; color: #ffffff; margin: 0 0 20px 0;">Quick Links</h4>
            <div style="display: flex; flex-direction: column; gap: 12px;">
              <a href="#home" class="footer-link" style="color: #888888; text-decoration: none; transition: all 0.3s ease; position: relative; padding: 8px 0; display: inline-block;">Home</a>
              <a href="#features" class="footer-link" style="color: #888888; text-decoration: none; transition: all 0.3s ease; position: relative; padding: 8px 0; display: inline-block;">Features</a>
              <a href="#about" class="footer-link" style="color: #888888; text-decoration: none; transition: all 0.3s ease; position: relative; padding: 8px 0; display: inline-block;">About</a>
              <a href="#contact" class="footer-link" style="color: #888888; text-decoration: none; transition: all 0.3s ease; position: relative; padding: 8px 0; display: inline-block;">Contact</a>
            </div>
          </div>
          <!-- Contact Info -->
          <div>
            <h4 style="font-size: 18px; font-weight: 600; color: #ffffff; margin: 0 0 20px 0;">Contact</h4>
            <div style="display: flex; flex-direction: column; gap: 12px; color: #888888;">
              <div style="display: flex; align-items: center; gap: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <polyline points="22,6 12,13 2,6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                hello@readify.com
              </div>
              <div style="display: flex; align-items: center; gap: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M22 16.92V19.92C22 20.52 21.39 21 20.92 21C9.11 21 1 12.89 1 1.08C1 0.61 1.48 0 2.08 0H5.08C5.68 0 6.08 0.4 6.08 1C6.08 3.25 6.5 5.45 7.34 7.47C7.48 7.76 7.4 8.11 7.14 8.37L5.9 9.61C7.07 12.02 9.98 14.93 12.39 16.1L13.63 14.86C13.89 14.6 14.24 14.52 14.53 14.66C16.55 15.5 18.75 15.92 21 15.92C21.6 15.92 22 16.32 22 16.92Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                +1 (555) 123-4567
              </div>
              <div style="display: flex; align-items: center; gap: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M21 10C21 17 12 23 12 23S3 17 3 10C3 5.03 7.03 1 12 1S21 5.03 21 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <circle cx="12" cy="10" r="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                123 Library St, Book City
              </div>
            </div>
          </div>
          <!-- Social Links -->
          <div>
            <h4 style="font-size: 18px; font-weight: 600; color: #ffffff; margin: 0 0 20px 0;">Follow Us</h4>
            <div style="display: flex; gap: 16px;">
              <a href="#" style="width: 40px; height: 40px; background: rgba(255, 255, 255, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #ffffff; text-decoration: none; transition: all 0.3s ease; border: 1px solid rgba(255, 255, 255, 0.2);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M18 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V4C20 2.9 19.1 2 18 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M8 6H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  <path d="M8 10H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  <path d="M8 14H12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
              </a>
              <a href="#" style="width: 40px; height: 40px; background: rgba(255, 255, 255, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #ffffff; text-decoration: none; transition: all 0.3s ease; border: 1px solid rgba(255, 255, 255, 0.2);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M23 3C22.0424 3.67548 20.9821 4.19211 19.86 4.53C19.2577 3.83751 18.4573 3.34669 17.567 3.12393C16.6767 2.90116 15.7395 2.95718 14.8821 3.28445C14.0247 3.61173 13.2884 4.19445 12.773 4.95371C12.2575 5.71297 11.9877 6.61435 12 7.53V8.53C10.2426 8.57557 8.50127 8.18581 6.93101 7.39624C5.36074 6.60667 4.01032 5.43666 3 4C3 4 -1 13 8 17C5.94053 18.398 3.48716 19.099 1 19C10 24 21 19 21 7.5C20.9991 7.22145 20.9723 6.94359 20.92 6.67C21.9406 5.66349 22.6608 4.39271 23 3V3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </a>
              <a href="#" style="width: 40px; height: 40px; background: rgba(255, 255, 255, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #ffffff; text-decoration: none; transition: all 0.3s ease; border: 1px solid rgba(255, 255, 255, 0.2);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M16 8C16 10.21 14.21 12 12 12C9.79 12 8 10.21 8 8C8 5.79 9.79 4 12 4C14.21 4 16 5.79 16 8Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M12 14C16.42 14 20 15.79 20 18V20H4V18C4 15.79 7.58 14 12 14Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </a>
              <a href="#" style="width: 40px; height: 40px; background: rgba(255, 255, 255, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #ffffff; text-decoration: none; transition: all 0.3s ease; border: 1px solid rgba(255, 255, 255, 0.2);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2" stroke="currentColor" stroke-width="2"/>
                  <circle cx="8.5" cy="8.5" r="1.5" stroke="currentColor" stroke-width="2"/>
                  <polyline points="21,15 16,10 5,21" stroke="currentColor" stroke-width="2"/>
                </svg>
              </a>
            </div>
          </div>
        </div>
        <!-- Copyright -->
        <div style="border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 30px; text-align: center;">
          <p style="color: #888888; font-size: 14px; margin: 0;">© 2024 Readify. All rights reserved. | Privacy Policy | Terms of Service</p>
        </div>
      </div>
    </footer>
  </div>

  <script>
    function toggleMobileMenu() {
      const mobileMenu = document.getElementById('mobileMenu');
      mobileMenu.classList.toggle('active');
    }
  </script>
 </body>
 </html>
