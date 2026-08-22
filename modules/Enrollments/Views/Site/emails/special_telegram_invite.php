<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دعوة خاصة للانضمام إلى مجتمع WebEasyStep</title>
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
        .telegram-section {
            background-color: #f0f7ff;
            border: 1px solid #d0e7ff;
            border-right: 4px solid #136ad5;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
            text-align: right;
        }
        .telegram-title {
            margin: 0 0 10px 0;
            font-size: 17px;
            font-weight: 700;
            color: #136ad5;
        }
        .telegram-desc {
            margin: 0 0 15px 0;
            font-size: 14px;
            color: #555555;
            line-height: 1.6;
        }
        .action-button-container {
            text-align: center;
            margin: 25px 0;
        }
        .action-button {
            display: inline-block;
            background-color: #24A1DE;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 35px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 15px;
            box-shadow: 0 4px 15px rgba(36, 161, 222, 0.3);
            transition: all 0.3s ease;
        }
        .action-button:hover {
            background-color: #1e8bbd;
            box-shadow: 0 6px 20px rgba(36, 161, 222, 0.4);
        }
        .link-help {
            font-size: 13px;
            color: #888888;
            margin-top: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 6px;
            word-break: break-all;
            direction: ltr;
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
            <h1 class="welcome-title">دعوة خاصة للانضمام إلى جروب الدعم</h1>
            
            <p class="email-text">
                مرحباً بك،
            </p>
            
            <p class="email-text">
                يسعدنا دعوتك للانضمام إلى مجتمعنا وجروب المتابعة والدعم الفني المخصص على تيليجرام لتقديم الدعم المباشر ومساعدتك في مسيرتك التعليمية.
            </p>

            <?php 
                $telegramGroupUrl = !empty($telegram_link) ? trim($telegram_link) : 'https://t.me/fakhrcs';
            ?>
            <!-- Telegram Section -->
            <div class="telegram-section">
                <h3 class="telegram-title">💬 جروب الدعم والمتابعة الفنية</h3>
                <p class="telegram-desc">
                    من خلال هذا الجروب، يمكنك طرح استفساراتك، مناقشة الدروس، والتواصل المباشر معنا ومع زملائك المشتركين لضمان تحقيق أقصى استفادة من المحتوى التعليمي.
                </p>
                
                <div class="action-button-container">
                    <a href="<?= esc($telegramGroupUrl) ?>" target="_blank" class="action-button">
                        انضم إلى جروب التيليجرام الآن
                    </a>
                </div>
            </div>

            <!-- Backup Link -->
            <div class="link-help">
                <p style="margin: 0 0 5px 0;">إذا لم يعمل الزر أعلاه، يمكنك نسخ الرابط التالي ولصقه في المتصفح الخاص بك:</p>
                <a href="<?= esc($telegramGroupUrl) ?>" style="color: #136ad5;"><?= esc($telegramGroupUrl) ?></a>
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p style="margin: 5px 0;">© <?= date('Y') ?> WebEasyStep. جميع الحقوق محفوظة.</p>
            <p style="margin: 5px 0;">إذا كنت بحاجة إلى مزيد من المساعدة، لا تتردد في التواصل معنا.</p>
        </div>
    </div>
</body>
</html>
