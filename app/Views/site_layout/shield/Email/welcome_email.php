<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مرحباً بك في MSARLink</title>
    <style>
        /* Reset styles for email clients */
        body, table, td, p, a, li, blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }
        
        /* Main styles */
        body {
            margin: 0 !important;
            padding: 0 !important;
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            direction: rtl;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        
        .header {
            background: linear-gradient(135deg, #136ad5 0%, #1573e8 100%);
            padding: 40px 30px;
            text-align: center;
        }
        
        .logo {
            color: #ffffff;
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
            text-decoration: none;
        }
        
        .header-subtitle {
            color: #ffffff;
            font-size: 18px;
            opacity: 0.9;
            margin: 0;
        }
        
        .welcome-badge {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50px;
            padding: 8px 20px;
            margin-top: 15px;
            display: inline-block;
            color: #ffffff;
            font-size: 14px;
            font-weight: bold;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .welcome-title {
            color: #333333;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .user-name {
            color: #136ad5;
            font-weight: bold;
        }
        
        .welcome-text {
            color: #666666;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .features-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 30px;
            margin: 30px 0;
        }
        
        .features-title {
            color: #136ad5;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 15px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .feature-icon {
            font-size: 24px;
            margin-left: 15px;
            width: 40px;
            text-align: center;
        }
        
        .feature-text {
            color: #333333;
            font-size: 16px;
            font-weight: 500;
        }
        
        .cta-section {
            text-align: center;
            margin: 40px 0;
        }
        
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #136ad5 0%, #1573e8 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 18px 45px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            margin: 10px;
            box-shadow: 0 4px 15px rgba(19, 106, 213, 0.3);
            transition: all 0.3s ease;
        }
        
        .cta-button:hover {
            background: linear-gradient(135deg, #0d5bba 0%, #1166d3 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(19, 106, 213, 0.4);
        }
        
        .secondary-button {
            display: inline-block;
            background: transparent;
            color: #136ad5 !important;
            text-decoration: none;
            padding: 18px 45px;
            border: 2px solid #136ad5;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            margin: 10px;
            transition: all 0.3s ease;
        }
        
        .secondary-button:hover {
            background: #136ad5;
            color: #ffffff !important;
        }
        
        .stats-section {
            display: flex;
            justify-content: space-around;
            margin: 30px 0;
            text-align: center;
        }
        
        .stat-item {
            flex: 1;
            padding: 20px;
        }
        
        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #136ad5;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 14px;
            color: #666666;
        }
        
        .tips-section {
            background-color: #e8f4fd;
            border: 1px solid #b3d9f7;
            border-radius: 8px;
            padding: 25px;
            margin: 30px 0;
        }
        
        .tips-title {
            color: #0c5aa6;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .tip-item {
            color: #0c5aa6;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 10px;
            padding-right: 20px;
            position: relative;
        }
        
        .tip-item:before {
            content: "💡";
            position: absolute;
            right: 0;
            top: 0;
        }
        
        .footer {
            background-color: #343a40;
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        
        .footer-text {
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        
        .social-links {
            margin: 20px 0;
        }
        
        .social-link {
            display: inline-block;
            margin: 0 10px;
            color: #ffffff;
            text-decoration: none;
            font-size: 18px;
        }
        
        .copyright {
            font-size: 12px;
            color: #adb5bd;
            margin-top: 20px;
        }
        
        /* Responsive styles */
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
            }
            
            .header, .content, .footer {
                padding: 20px !important;
            }
            
            .welcome-title {
                font-size: 24px !important;
            }
            
            .cta-button, .secondary-button {
                padding: 15px 30px !important;
                font-size: 16px !important;
                display: block !important;
                margin: 10px 0 !important;
            }
            
            .stats-section {
                flex-direction: column;
            }
            
            .feature-item {
                flex-direction: column;
                text-align: center;
            }
            
            .feature-icon {
                margin-left: 0 !important;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">MSARLink</div>
            <p class="header-subtitle">منصة التعلم الإلكتروني المتقدمة</p>
            <div class="welcome-badge">🎉 مرحباً بك معنا</div>
        </div>
        
        <!-- Main Content -->
        <div class="content">
            <h1 class="welcome-title">أهلاً وسهلاً <span class="user-name"><?= esc($user->username ?? $user->email) ?></span>!</h1>
            
            <p class="welcome-text">
                تهانينا! تم تفعيل حسابك بنجاح في منصة MSARLink. 
                نحن متحمسون لانضمامك إلى مجتمعنا التعليمي المتنامي.
            </p>
            
            <!-- Features Section -->
            <div class="features-section">
                <div class="features-title">🚀 ما يمكنك فعله الآن</div>
                
                <div class="feature-item">
                    <div class="feature-icon">📚</div>
                    <div class="feature-text">تصفح مكتبة الدورات التعليمية المتنوعة</div>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">🎯</div>
                    <div class="feature-text">تتبع تقدمك في التعلم والحصول على شهادات</div>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">👥</div>
                    <div class="feature-text">التفاعل مع المدربين والطلاب الآخرين</div>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">📱</div>
                    <div class="feature-text">الوصول للمحتوى من أي جهاز في أي وقت</div>
                </div>
            </div>
            
            <!-- Stats Section -->
            <div class="stats-section">
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">دورة تعليمية</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">10K+</div>
                    <div class="stat-label">طالب نشط</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">مدرب خبير</div>
                </div>
            </div>
            
            <!-- CTA Section -->
            <div class="cta-section">
                <a href="<?= site_url('/') ?>" class="cta-button">
                    ابدأ رحلتك التعليمية
                </a>
                <a href="<?= site_url('courses') ?>" class="secondary-button">
                    تصفح الدورات
                </a>
            </div>
            
            <!-- Tips Section -->
            <div class="tips-section">
                <div class="tips-title">💡 نصائح للبداية</div>
                <div class="tip-item">أكمل ملفك الشخصي لتحصل على توصيات مخصصة</div>
                <div class="tip-item">ابدأ بالدورات المجانية لتتعرف على المنصة</div>
                <div class="tip-item">انضم إلى مجتمعاتنا على وسائل التواصل الاجتماعي</div>
                <div class="tip-item">فعّل الإشعارات لتبقى على اطلاع بآخر التحديثات</div>
            </div>
            
            <p class="welcome-text">
                إذا كان لديك أي استفسار أو تحتاج إلى مساعدة، فريق الدعم متاح دائماً لمساعدتك.
                نتمنى لك تجربة تعليمية ممتعة ومثمرة!
            </p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p class="footer-text">
                شكراً لاختيارك منصة MSARLink<br>
                نحن هنا لدعمك في رحلتك التعليمية
            </p>
            
            <div class="social-links">
                <a href="#" class="social-link">📧</a>
                <a href="#" class="social-link">📱</a>
                <a href="#" class="social-link">🌐</a>
                <a href="#" class="social-link">📘</a>
            </div>
            
            <p class="copyright">
                © <?= date('Y') ?> MSARLink. جميع الحقوق محفوظة.
            </p>
        </div>
    </div>
</body>
</html>