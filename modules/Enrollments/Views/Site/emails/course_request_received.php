<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تم استلام طلب الاشتراك</title>
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
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
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
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .email-body {
            padding: 36px 30px;
            color: #333333;
        }
        .title {
            font-size: 24px;
            font-weight: 700;
            color: #136ad5;
            margin-top: 0;
            margin-bottom: 18px;
            text-align: center;
        }
        .email-text {
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 16px;
            color: #555555;
        }
        .details-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 18px;
            margin: 24px 0;
        }
        .details-box p {
            margin: 0 0 10px;
            color: #334155;
            font-size: 15px;
        }
        .details-box p:last-child {
            margin-bottom: 0;
        }
        .notice-box {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            padding: 16px;
            margin-top: 24px;
            font-size: 15px;
            line-height: 1.8;
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
        <div class="email-header">
            <img src="<?= base_url('site/images/logo.png') ?>" alt="WebEasyStep Logo" class="email-logo">
        </div>

        <div class="email-body">
            <h1 class="title">تم استلام طلب اشتراكك بنجاح</h1>

            <p class="email-text">
                أهلاً <strong><?= esc($full_name) ?></strong>،
            </p>

            <p class="email-text">
                تم استلام طلب اشتراكك في دورة <strong>"<?= esc($course_title) ?>"</strong> بنجاح، وطلبك الآن قيد المراجعة من الإدارة.
            </p>

            <div class="details-box">
                <p><strong>الدورة:</strong> <?= esc($course_title) ?></p>
                <p><strong>طريقة الدفع:</strong> <?= esc($payment_method) ?></p>
                <p><strong>المبلغ:</strong> <?= esc(number_format((float) $paid_amount, 2)) ?> $</p>
                <p><strong>تاريخ الطلب:</strong> <?= esc($submitted_at) ?></p>
            </div>

            <div class="notice-box">
                سيتم مراجعة طلبك وتفعيل الاشتراك خلال 24 ساعة كحد أقصى، وسيصلك بريد إلكتروني آخر فور اعتماد الاشتراك.
            </div>
        </div>

        <div class="email-footer">
            <p style="margin: 5px 0;">© <?= date('Y') ?> WebEasyStep. جميع الحقوق محفوظة.</p>
            <p style="margin: 5px 0;">هذا بريد تلقائي، يرجى عدم الرد عليه.</p>
        </div>
    </div>
</body>
</html>
