<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفعيل الحساب - WebEasyStep</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
            direction: rtl;
            text-align: right;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            overflow: hidden;
            border: 1px solid #eee;
        }
        .email-header {
            background-color: #136ad5;
            padding: 30px;
            text-align: center;
        }
        .email-logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
            background: white;
            border-radius: 50%;
            padding: 5px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .email-body {
            padding: 40px 30px;
            color: #333333;
        }
        .welcome-title {
            font-size: 24px;
            font-weight: 700;
            color: #136ad5;
            margin-top: 0;
            margin-bottom: 20px;
            text-align: center;
        }
        .email-text {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
            color: #555555;
        }
        .action-button-container {
            text-align: center;
            margin: 35px 0;
        }
        .action-button {
            display: inline-block;
            background-color: #136ad5;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 40px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 15px rgba(19, 106, 213, 0.3);
            transition: all 0.3s ease;
        }
        .action-button:hover {
            background-color: #0b5cbf;
            box-shadow: 0 6px 20px rgba(19, 106, 213, 0.4);
        }
        .link-help {
            font-size: 13px;
            color: #888888;
            margin-top: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 6px;
            word-break: break-all;
            direction: ltr; /* Ensure URL displays correctly */
            text-align: center;
        }
        .email-footer {
            background-color: #f1f3f5;
            padding: 20px;
            text-align: center;
            font-size: 13px;
            color: #888888;
            border-top: 1px solid #eeeeee;
        }
        .social-links {
            margin-bottom: 10px;
        }
        .social-links a {
            margin: 0 5px;
            text-decoration: none;
            color: #136ad5;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header with Logo -->
        <div class="email-header">
            <img src="<?= base_url('site/images/logo.png') ?>" alt="WebEasyStep Logo" class="email-logo">
        </div>

        <!-- Body Content -->
        <div class="email-body">
            <h1 class="welcome-title">أهلاً بك في WebEasyStep!</h1>
            
            <p class="email-text">
                عزيزي/عزيزتي <strong><?= esc($full_name) ?></strong>،
            </p>
            
            <p class="email-text">
                شكراً لتسجيلك معنا. نحن متحمسون جداً لانضمامك إلى مجتمعنا التعليمي. لقد قمت بالخطوة الأولى نحو تطوير مهاراتك.
            </p>
            
            <p class="email-text">
                لتأكيد حسابك والبدء في استخدام المنصة، يرجى الضغط على الزر أدناه:
            </p>

            <div class="action-button-container">
                <a href="<?= $activation_url ?>" class="action-button">تفعيل الحساب</a>
            </div>

            <p class="email-text" style="text-align: center; font-size: 14px; margin-top: 30px;">
                ستنتهي صلاحية هذا الرابط خلال 24 ساعة.
            </p>

            <!-- Backup Link -->
            <div class="link-help">
                <p style="margin: 0 0 5px 0;">إذا كنت تواجه مشكلة في الزر أعلاه، انسخ الرابط التالي:</p>
                <a href="<?= $activation_url ?>" style="color: #136ad5;"><?= $activation_url ?></a>
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p style="margin: 5px 0;">© <?= date('Y') ?> WebEasyStep. جميع الحقوق محفوظة.</p>
            <p style="margin: 5px 0;">إذا لم تقم بإنشاء هذا الحساب، يمكنك تجاهل هذه الرسالة بأمان.</p>
        </div>
    </div>
</body>
</html>
