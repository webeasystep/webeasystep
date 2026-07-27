<?php
/**
 * Simple activation email optimized for readability and lower spam risk.
 *
 * - Uses table-based structure for better email client support.
 * - Keeps copy concise and action-oriented.
 * - Shows both activation button and activation code/link clearly.
 */
$displayName = trim((string) ($full_name ?? $user->full_name ?? ''));
$displayName = $displayName !== '' ? $displayName : 'WebEasyStep User';
$activationCode = (string) ($code ?? '');
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفعيل الحساب | Activate Your Account</title>
</head>
<body style="margin:0; padding:0; background-color:#f5f8fc; font-family:Arial, 'Segoe UI', Tahoma, sans-serif; color:#1f2937;">
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#f5f8fc; margin:0; padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:640px; background-color:#ffffff; border:1px solid #dbe7f3; border-radius:16px;">
                    <tr>
                        <td style="padding:28px 32px 12px; text-align:center; background-color:#136ad5; border-radius:16px 16px 0 0;">
                            <div style="font-size:26px; line-height:1.4; font-weight:700; color:#ffffff;">WebEasyStep</div>
                            <div style="margin-top:6px; font-size:14px; line-height:1.7; color:#dbeafe;">
                                تفعيل الحساب | Account Activation
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px 32px 8px;">
                            <p style="margin:0 0 16px; font-size:16px; line-height:1.8; color:#111827;">
                                مرحبا <strong><?= esc($displayName) ?></strong>
                            </p>
                            <p style="margin:0 0 16px; font-size:16px; line-height:1.9; color:#374151;">
                                شكرا لتسجيلك في <strong>WebEasyStep</strong>. لتأكيد بريدك الإلكتروني وتفعيل حسابك، اضغط على الزر التالي.
                            </p>
                            <p style="margin:0 0 20px; font-size:15px; line-height:1.9; color:#4b5563;">
                                Thank you for registering with <strong>WebEasyStep</strong>. Please confirm your email address and activate your account using the button below.
                            </p>
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:0 0 22px;">
                                <tr>
                                    <td align="center">
                                        <a href="<?= esc($activation_url) ?>" style="display:inline-block; background-color:#136ad5; color:#ffffff; text-decoration:none; font-size:16px; font-weight:700; padding:14px 28px; border-radius:10px;">
                                            تفعيل الحساب | Activate Account
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:0 0 18px; background-color:#f8fbff; border:1px solid #dbe7f3; border-radius:12px;">
                                <tr>
                                    <td style="padding:18px 20px;">
                                        <div style="font-size:14px; font-weight:700; color:#136ad5; margin-bottom:8px;">
                                            كود التفعيل | Activation Code
                                        </div>
                                        <div style="font-size:18px; line-height:1.8; font-weight:700; color:#111827; direction:ltr; text-align:center; word-break:break-all; padding:10px 12px; background-color:#ffffff; border:1px dashed #9dbfe8; border-radius:10px;">
                                            <?= esc($activationCode) ?>
                                        </div>
                                        <div style="margin-top:10px; font-size:13px; line-height:1.8; color:#6b7280;">
                                            يمكنك استخدام هذا الكود أو الرابط التالي إذا لم يعمل الزر. You can use this code or the link below if the button does not open.
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:0 0 18px; background-color:#ffffff; border:1px solid #e5e7eb; border-radius:12px;">
                                <tr>
                                    <td style="padding:16px 20px;">
                                        <div style="font-size:14px; font-weight:700; color:#136ad5; margin-bottom:8px;">
                                            رابط التفعيل | Activation Link
                                        </div>
                                        <div style="font-size:13px; line-height:1.9; color:#4b5563; direction:ltr; text-align:left; word-break:break-all;">
                                            <a href="<?= esc($activation_url) ?>" style="color:#136ad5; text-decoration:none;"><?= esc($activation_url) ?></a>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:0 0 18px; background-color:#fff8e8; border:1px solid #f2d58a; border-radius:12px;">
                                <tr>
                                    <td style="padding:16px 20px;">
                                        <div style="font-size:14px; font-weight:700; color:#8a5a00; margin-bottom:6px;">
                                            ملاحظة مهمة | Important Notice
                                        </div>
                                        <div style="font-size:14px; line-height:1.9; color:#6b4f12;">
                                            إذا لم تجد الرسالة في البريد الوارد، برجاء فحص <strong>Spam / Junk</strong> ثم أضف المرسل إلى القائمة الآمنة.<br>
                                            If you do not see this email in your inbox, please check your <strong>Spam / Junk</strong> folder and mark it as safe.
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin:0 0 8px; font-size:13px; line-height:1.8; color:#6b7280; text-align:center;">
                                صلاحية رابط التفعيل 24 ساعة. Activation link expires in 24 hours.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 32px 28px; text-align:center;">
                            <div style="padding-top:18px; border-top:1px solid #e5e7eb; font-size:12px; line-height:1.8; color:#6b7280;">
                                إذا لم تقم بإنشاء هذا الحساب، يمكنك تجاهل هذه الرسالة بأمان.<br>
                                If you did not create this account, you can safely ignore this email.
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
