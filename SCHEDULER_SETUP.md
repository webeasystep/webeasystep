# Scheduled Tasks Setup Guide

This guide explains how to set up automated scheduled tasks for the MSARLink Learning Platform, including weekly progress email notifications and system maintenance.

## Overview

The platform includes several automated tasks:
- **Weekly Progress Emails**: Send learning progress reports to parents/guardians every Monday
- **System Cleanup**: Clean up old logs, temporary files, and expired data every Sunday
- **Daily Reminders** (Optional): Send daily reminders for incomplete units
- **Monthly Summaries** (Optional): Send monthly progress summary reports

## Prerequisites

1. **Email Configuration**: Ensure your email settings are configured in `.env`
2. **Database**: All required tables should be migrated
3. **Cron Access**: Access to set up cron jobs (Linux/macOS) or Task Scheduler (Windows)
4. **PHP CLI**: PHP command line interface should be available

## Email Configuration

Add these settings to your `.env` file:

```env
# Email Configuration
email.fromEmail = noreply@yourdomain.com
email.fromName = "MSARLink Learning Platform"
email.SMTPHost = your-smtp-host
email.SMTPUser = your-smtp-username
email.SMTPPass = your-smtp-password
email.SMTPPort = 587
email.SMTPCrypto = tls
email.protocol = smtp
```

## Setting Up Scheduled Tasks

### Option 1: Automatic Cron Setup (Recommended)

1. Generate cron entries:
```bash
cd /path/to/your/project
php spark scheduler:generate-cron
```

2. This will output cron entries that you can copy to your crontab:
```bash
crontab -e
```

3. Paste the generated cron entries and save.

### Option 2: Manual Cron Setup

Add these entries to your crontab (`crontab -e`):

```bash
# MSARLink Scheduled Tasks

# Weekly Progress Emails - Every Monday at 9:00 AM
0 9 * * 1 cd /path/to/your/project && php spark email:weekly-progress >> /path/to/logs/weekly_emails.log 2>&1

# System Cleanup - Every Sunday at 2:00 AM
0 2 * * 0 cd /path/to/your/project && php spark system:cleanup >> /path/to/logs/system_cleanup.log 2>&1
```

### Option 3: Windows Task Scheduler

1. Open Task Scheduler
2. Create Basic Task
3. Set trigger (e.g., Weekly on Monday at 9:00 AM)
4. Set action to start a program:
   - Program: `php`
   - Arguments: `spark email:weekly-progress`
   - Start in: `/path/to/your/project`

## Testing Scheduled Tasks

### Test Weekly Progress Emails

```bash
# Dry run (preview without sending)
php spark email:weekly-progress --dry-run

# Test with specific user
php spark email:weekly-progress --user-id=1 --dry-run

# Force send (ignore weekly limit)
php spark email:weekly-progress --force
```

### Test System Cleanup

```bash
# Dry run (preview without deleting)
php spark system:cleanup --dry-run --verbose

# Clean up files older than 7 days
php spark system:cleanup --days=7

# Clean up logs only
php spark system:cleanup --logs
```

## Command Options

### Weekly Progress Emails (`email:weekly-progress`)

- `--dry-run`: Preview emails without sending
- `--user-id=ID`: Send report for specific user only
- `--force`: Force send even if already sent this week

### System Cleanup (`system:cleanup`)

- `--days=N`: Number of days to keep files (default: 30)
- `--dry-run`: Preview cleanup without deleting
- `--verbose`: Show detailed output
- `--logs`: Clean up log files only
- `--temp`: Clean up temporary files only
- `--tokens`: Clean up expired tokens only

## Monitoring and Logs

### Log Files

Scheduled tasks create log files in `writable/logs/`:
- `scheduler_weekly_progress_emails.log`
- `scheduler_system_cleanup.log`
- Application logs in `log-YYYY-MM-DD.log`

### Monitoring Email Delivery

Check the `tb_email_logs` table for email delivery status:

```sql
SELECT 
    el.email_type,
    el.sent_at,
    el.status,
    u.username,
    u.email,
    u.parent_email
FROM tb_email_logs el
JOIN tb_users u ON u.id = el.user_id
WHERE el.email_type = 'weekly_progress'
ORDER BY el.sent_at DESC
LIMIT 50;
```

### System Health Check

Use the admin dashboard to monitor:
- Email delivery statistics
- System cleanup results
- Database health
- Storage usage

## Troubleshooting

### Common Issues

1. **Emails not sending**:
   - Check email configuration in `.env`
   - Verify SMTP credentials
   - Check email logs for errors
   - Test with `--dry-run` first

2. **Cron jobs not running**:
   - Verify cron service is running: `sudo service cron status`
   - Check cron logs: `grep CRON /var/log/syslog`
   - Ensure correct file paths in cron entries
   - Check PHP CLI path: `which php`

3. **Permission errors**:
   - Ensure web server user can write to `writable/` directory
   - Check file permissions: `chmod -R 755 writable/`
   - Verify database connection from CLI

4. **Memory or timeout issues**:
   - Increase PHP memory limit in CLI
   - Adjust timeout values in scheduler config
   - Process users in smaller batches

### Debug Commands

```bash
# Test database connection
php spark migrate:status

# Test email configuration
php spark email:test your-email@domain.com

# Check scheduler configuration
php spark scheduler:status

# View recent logs
tail -f writable/logs/log-$(date +%Y-%m-%d).log
```

## Configuration Options

### Scheduler Configuration

Edit `app/Config/Scheduler.php` to:
- Enable/disable specific tasks
- Modify schedules
- Adjust timeout values
- Add custom options

### Email Template Customization

The email templates are generated in:
- `app/Commands/SendWeeklyProgressEmails.php` (method: `generateEmailContent`)

Customize the HTML template to match your branding.

### Parent Email Setup

To enable parent notifications:
1. Add `parent_email` field to user registration
2. Update user profile forms to include parent email
3. Emails will be sent to parent email if available, otherwise to user email

## Security Considerations

1. **Email Privacy**: Ensure parent emails are collected with proper consent
2. **Log Security**: Protect log files from unauthorized access
3. **Database Cleanup**: Regularly clean up old data to maintain performance
4. **Error Handling**: Don't expose sensitive information in error messages

## Performance Optimization

1. **Batch Processing**: Process users in batches to avoid memory issues
2. **Database Indexing**: Ensure proper indexes on date fields
3. **Email Throttling**: Implement rate limiting for email sending
4. **Cleanup Scheduling**: Run cleanup during low-traffic hours

## Support

For issues with scheduled tasks:
1. Check the troubleshooting section above
2. Review log files for specific error messages
3. Test commands manually before scheduling
4. Monitor system resources during task execution

---

**Note**: Always test scheduled tasks in a development environment before deploying to production.