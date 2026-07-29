<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعادة تعيين كلمة المرور - FakhrCS</title>
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
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
            text-decoration: none;
        }
        
        .header-subtitle {
            color: #ffffff;
            font-size: 16px;
            opacity: 0.9;
            margin: 0;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .welcome-title {
            color: #333333;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .welcome-text {
            color: #666666;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .activation-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px solid #136ad5;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        
        .activation-title {
            color: #136ad5;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .code-section {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .code-label {
            color: #666666;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .activation-code {
            background-color: #ffffff;
            border: 2px dashed #136ad5;
            border-radius: 6px;
            padding: 15px;
            font-family: 'Courier New', monospace;
            font-size: 24px;
            font-weight: bold;
            color: #136ad5;
            text-align: center;
            letter-spacing: 5px;
        }
        
        .security-notice {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
        }
        
        .security-icon {
            color: #856404;
            font-size: 20px;
            margin-bottom: 10px;
        }
        
        .security-text {
            color: #856404;
            font-size: 14px;
            line-height: 1.5;
            margin: 0;
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
                font-size: 20px !important;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">FakhrCS</div>
            <p class="header-subtitle">المنصة الأولى المخصصة لطلبة الجامعة السعودية الإلكترونية</p>
        </div>
        
        <!-- Main Content -->
        <div class="content">
            <h1 class="welcome-title">طلب إعادة تعيين كلمة المرور</h1>
            
            <p class="welcome-text">
                لقد تلقينا طلباً لإعادة تعيين كلمة المرور الخاصة بحسابك.
                الرجاء استخدام الرمز أدناه لإكمال العملية.
            </p>
            
            <!-- Activation Section -->
            <div class="activation-box">
                <div class="activation-title">🔑 رمز التفعيل</div>
                
                <div class="code-section">
                    <div class="code-label">رمز التحقق الخاص بك هو:</div>
                    <div class="activation-code"><?= esc($code) ?></div>
                </div>
            </div>
            
            <!-- Security Notice -->
            <div class="security-notice">
                <div class="security-icon">🔒</div>
                <p class="security-text">
                    <strong>ملاحظة أمنية:</strong> هذا الرمز صالح لمدة ساعة واحدة فقط. 
                    إذا لم تقم بطلب إعادة تعيين كلمة المرور، يرجى تجاهل هذه الرسالة ولا تشارك هذا الرمز مع أحد.
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p class="footer-text">
                هذه رسالة تلقائية من منصة FakhrCS<br>
                إذا كان لديك أي استفسار، لا تتردد في التواصل معنا
            </p>
            
            <div class="social-links">
                <a href="<?= site_url('/') ?>" class="social-link" title="موقع المنصة" target="_blank">🌐</a>
                <a href="https://wa.me/201032863861" class="social-link" title="الدعم عبر واتساب" target="_blank">💬</a>
                <a href="mailto:fakhrcshub@gmail.com" class="social-link" title="البريد الإلكتروني">📧</a>
            </div>
            
            <p class="copyright">
                © <?= date('Y') ?> FakhrCS. جميع الحقوق محفوظة.
            </p>
        </div>
    </div>
</body>
</html>
