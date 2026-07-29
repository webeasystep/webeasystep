# Debug Session: activation-direct-login
- **Status**: [OPEN]
- **Issue**: الضغط على رابط التفعيل المباشر من البريد يسبب استثناء من Shield بدل تفعيل الحساب وتسجيل الدخول مباشرة.

## Hypotheses
| ID | Hypothesis | Status |
|----|------------|--------|
| H1 | `auth_action` و`auth_action_message` ما زالا موجودين في الـ session وقت `login()` | Pending |
| H2 | توجد بيانات pending user قديمة في الجلسة تعرقل `login()` | Pending |
| H3 | مسار `activate-account` يجب أن يكون مستقلًا عن flow التفعيل المعتمد على الجلسة | Pending |
| H4 | تنظيف مفاتيح الـ action من الجلسة قبل `login()` سيحل المشكلة | Pending |
| H5 | سجل SMTP المذكور ليس سبب الانهيار الحالي في `activate-account` | Pending |

## Evidence
- User log shows `LogicException: The user has auth action in session, so cannot complete login.`
- Current `activateAccount()` logs user in directly after deleting `email_activate` identity only.

## Conclusion
- Pending
