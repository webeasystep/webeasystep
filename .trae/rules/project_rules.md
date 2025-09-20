# ===============================
# Generic Project Rules for TRAI AI
# ===============================

# 1) Always use Invoke-WebRequest for all HTTP requests
# Example:
Invoke-WebRequest -Uri "http://localhost:8080/some/endpoint" -Method GET -UseBasicParsing
# - Always add headers and body when required (-Headers, -Body, -ContentType)
# - Always store output for analysis (HTML, JSON, etc.)

# 2) Always login before accessing protected routes
$session = New-Object Microsoft.PowerShell.Commands.WebRequestSession
$loginData = @{
    email = "spcialist@gmail.com"
    password = "123456"
}
Invoke-WebRequest -Uri "http://localhost:8080/login" -Method POST -Body $loginData -WebSession $session
# - Reuse $session for all subsequent requests
# - Treat login as the first step in any test cycle

# 3) After every request, always check logs
Get-Content D:\laragon\www\msarlink\debug.log
# - Inspect logs for errors, warnings, or SQL traces
# - Use logs to validate if the request worked correctly
# - Priority Rule: If any warnings or critical issues appear in the log,
#   they MUST be fixed and resolved before continuing with the next task.

# 4) When executing PHP scripts, always use the correct PHP binary
D:\laragon\bin\php\php-8.3.22-Win32-vs16-x64\php.exe

# 5) When executing MySQL queries, always use the correct MySQL binary
D:\laragon\bin\mysql\mysql-8.0.41-winx64\bin\mysql.exe

# 6) Generic Testing Workflow (Student Simulation)
# - Step 1: Login and save session
# - Step 2: Send request to a target endpoint (course, unit, item, etc.)
# - Step 3: Always capture and analyze the response as HTML/JSON
# - Step 4: Simulate next user action (navigate to next page, click, form submit, etc.)
# - Step 5: After each action, query the related database table (e.g., tb_user_item_progress) to confirm data is saved
# - Step 6: Review writable/logs after each action to cross-check system behavior
# - Step 7: Repeat the cycle until the full flow (course completion, form workflow, etc.) is tested

# 7) Cleanup Rule
# - After finishing any testing cycle, always clean up temporary test files (e.g., downloaded HTML, logs, debug outputs).
# Example:
Remove-Item -Path "D:\laragon\www\msarlink\*.html" -Force -ErrorAction SilentlyContinue
Remove-Item -Path "D:\laragon\www\msarlink\*.tmp" -Force -ErrorAction SilentlyContinue
# - Ensure no leftover test data, temporary files, or debug artifacts remain.
# - Keep only essential logs that are explicitly marked for long-term analysis.

# 8) Design Rules
# - All UI/UX designs MUST follow the same template stored at:
#   msarlink/public/site/
# - Rules include:
#   * Use the same primary and secondary colors as defined in the template
#   * Fonts must match the standard project font family
#   * All icons must be consistent with the template set (no mixing styles)
#   * Always use the same Bootstrap version as defined in the template
#   * Ensure spacing, padding, and responsive rules are consistent
# - Any new feature/page MUST be visually identical in design language
# - Deviation from template (custom fonts, random colors, mismatched icons, etc.) is NOT allowed
