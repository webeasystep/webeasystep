<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفعيل الاشتراك - WebEasyStep</title>
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
            <h1 class="welcome-title">مرحباً بك في دورتك الجديدة!</h1>
            
            <p class="email-text">
                عزيزي/عزيزتي <strong><?= esc($full_name) ?></strong>،
            </p>
            
            <p class="email-text">
                يسعدنا إخبارك بأنه تم تفعيل اشتراكك بنجاح في دورة <strong>"<?= esc($course_title) ?>"</strong>.
            </p>
            
            <p class="email-text">
                يمكنك الآن الوصول إلى جميع الدروس، الاختبارات، والمرفقات الخاصة بالدورة وبدء رحلتك التعليمية معنا.
            </p>

            <!-- Telegram Support Group Section -->
            <div style="background-color: #f0f7ff; border: 1px solid #d0e7ff; border-right: 4px solid #136ad5; border-radius: 8px; padding: 18px; margin: 25px 0; direction: rtl; text-align: right;">
                <h3 style="margin: 0 0 10px 0; font-size: 16px; font-weight: 700; color: #136ad5;">
                    💬 جروب الدعم الفني والمتابعة على تيليجرام
                </h3>
                <p style="margin: 0 0 15px 0; font-size: 14px; color: #555555; line-height: 1.5;">
                    للحصول على الدعم الفني المباشر، الإجابة على استفساراتك، والتواصل مع المدرب وزملائك في الدورة، يرجى الانضمام إلى جروب تيليجرام المخصص لتقديم الدعم للمشتركين.
                </p>
                <div style="text-align: center;">
                    <a href="https://t.me/+5ZmUqh_I981hYWY0" target="_blank" style="display: inline-block; background-color: #24A1DE; color: #ffffff !important; text-decoration: none; padding: 10px 24px; border-radius: 8px; font-weight: bold; font-size: 14px; box-shadow: 0 3px 10px rgba(36, 161, 222, 0.2);">
                        انضم للجروب عبر تيليجرام
                    </a>
                </div>
            </div>

            <div class="action-button-container">
                <a href="<?= $course_url ?>" class="action-button">ابدأ التعلم الآن</a>
            </div>

            <!-- Backup Link -->
            <div class="link-help">
                <p style="margin: 0 0 5px 0;">إذا كنت تواجه مشكلة في الزر أعلاه، انسخ الرابط التالي وافتحه في المتصفح:</p>
                <a href="<?= $course_url ?>" style="color: #136ad5;"><?= $course_url ?></a>
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p style="margin: 5px 0;">© <?= date('Y') ?> WebEasyStep. جميع الحقوق محفوظة.</p>
            <p style="margin: 5px 0;">هذا بريد تلقائي، يرجى عدم الرد عليه.</p>
        </div>
    </div>
</body>
</html>