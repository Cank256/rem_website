# Email Integration Setup Checklist

Use this checklist to ensure your Resend.com email integration is properly configured.

## 📋 Pre-Setup Checklist

- [ ] Have access to Resend.com account (or can create one)
- [ ] Have access to domain DNS settings (for production)
- [ ] Have access to `.env` file
- [ ] Can run artisan commands

## 🚀 Setup Steps

### Step 1: Get Resend API Key
- [ ] Go to [resend.com](https://resend.com)
- [ ] Sign up or log in
- [ ] Navigate to **API Keys** section
- [ ] Click **Create API Key**
- [ ] Copy the API key (starts with `re_`)
- [ ] Save it securely (you'll only see it once)

### Step 2: Update Environment File
- [ ] Open `.env` file in your project
- [ ] Find the mail configuration section
- [ ] Update the following values:
  ```env
  MAIL_MAILER=resend
  MAIL_FROM_ADDRESS="noreply@yourdomain.com"
  MAIL_FROM_NAME="Rural Evangelical Ministries"
  RESEND_API_KEY=re_your_actual_api_key_here
  ```
- [ ] Save the file

### Step 3: Clear Configuration Cache
- [ ] Open terminal in project directory
- [ ] Run: `php artisan config:clear`
- [ ] Run: `php artisan cache:clear`
- [ ] Verify no errors appear

### Step 4: Test Email Sending
- [ ] Run: `php artisan resend:test your-email@example.com`
- [ ] Check terminal for success message
- [ ] Check your email inbox
- [ ] Check Resend dashboard for sent email

### Step 5: Test User Registration Email
- [ ] Go to `/register` on your website
- [ ] Register a new test user
- [ ] Check email inbox for welcome email
- [ ] Verify email content looks correct
- [ ] Check Resend dashboard for delivery status

### Step 6: Test Password Reset Email
- [ ] Go to `/forgot-password` on your website
- [ ] Enter a registered user's email
- [ ] Check email inbox for password reset email
- [ ] Click the reset link
- [ ] Verify link works correctly
- [ ] Check Resend dashboard for delivery status

## 🌐 Production Setup (Additional Steps)

### Step 7: Verify Domain
- [ ] Log in to Resend dashboard
- [ ] Go to **Domains** section
- [ ] Click **Add Domain**
- [ ] Enter your domain: `ruralevangelicalministries.org`
- [ ] Copy the DNS records provided
- [ ] Add DNS records to your domain provider:
  - [ ] SPF record
  - [ ] DKIM record
  - [ ] DMARC record (optional but recommended)
- [ ] Wait for verification (usually 5-30 minutes)
- [ ] Verify domain shows as "Verified" in Resend

### Step 8: Update Production Configuration
- [ ] Open `.env.production` file
- [ ] Update with production values:
  ```env
  MAIL_MAILER=resend
  MAIL_FROM_ADDRESS="noreply@ruralevangelicalministries.org"
  RESEND_API_KEY=re_production_api_key_here
  ```
- [ ] Use your verified domain in `MAIL_FROM_ADDRESS`
- [ ] Use production API key (not test key)

### Step 9: Deploy to Production
- [ ] Upload updated `.env.production` to server
- [ ] SSH into production server
- [ ] Run: `php artisan config:clear`
- [ ] Run: `php artisan cache:clear`
- [ ] Test email sending on production

## ⚡ Optional: Queue Setup (Recommended)

### Step 10: Configure Queue
- [ ] Update `.env`:
  ```env
  QUEUE_CONNECTION=database
  ```
- [ ] Run: `php artisan queue:table`
- [ ] Run: `php artisan migrate`
- [ ] Start queue worker: `php artisan queue:work`
- [ ] Set up Supervisor or similar to keep queue running

## ✅ Verification Checklist

### Development Environment
- [ ] Test command works: `php artisan resend:test`
- [ ] Registration emails are sent
- [ ] Password reset emails are sent
- [ ] Emails appear in inbox (not spam)
- [ ] Emails visible in Resend dashboard

### Production Environment
- [ ] Domain is verified in Resend
- [ ] Production API key is configured
- [ ] From address uses verified domain
- [ ] Test email works on production
- [ ] Registration emails work on production
- [ ] Password reset emails work on production
- [ ] Queue is running (if configured)

## 🔍 Troubleshooting Checklist

If emails are not sending:
- [ ] Check `RESEND_API_KEY` is set in `.env`
- [ ] Verify API key is correct (no extra spaces)
- [ ] Run `php artisan config:clear`
- [ ] Check `storage/logs/laravel.log` for errors
- [ ] Verify `MAIL_MAILER=resend` in `.env`
- [ ] Check Resend dashboard for error messages
- [ ] Verify domain is verified (production only)
- [ ] Check `MAIL_FROM_ADDRESS` uses verified domain

If emails go to spam:
- [ ] Verify domain in Resend dashboard
- [ ] Add SPF record to DNS
- [ ] Add DKIM record to DNS
- [ ] Add DMARC record to DNS
- [ ] Check email content for spam triggers
- [ ] Warm up your sending domain gradually

## 📊 Monitoring Checklist

### Daily Monitoring
- [ ] Check Resend dashboard for delivery rates
- [ ] Monitor bounce rates
- [ ] Check for failed deliveries
- [ ] Review spam complaints (if any)

### Weekly Monitoring
- [ ] Review email sending volume
- [ ] Check API usage against plan limits
- [ ] Review Laravel logs for email errors
- [ ] Test email flows manually

### Monthly Monitoring
- [ ] Review Resend plan and usage
- [ ] Update API keys if needed
- [ ] Review and update email templates
- [ ] Check domain verification status

## 📚 Documentation Reference

Quick access to documentation:
- [ ] Read: `RESEND_QUICK_START.md` (5-minute guide)
- [ ] Read: `RESEND_SETUP.md` (complete guide)
- [ ] Read: `EMAIL_INTEGRATION_SUMMARY.md` (technical details)
- [ ] Bookmark: [Resend Documentation](https://resend.com/docs)
- [ ] Bookmark: [Laravel Mail Docs](https://laravel.com/docs/mail)

## 🎯 Success Criteria

Your email integration is successful when:
- [x] Resend package is installed
- [x] Configuration files are updated
- [x] Welcome email notification is created
- [x] Registration controller sends welcome emails
- [x] Password reset emails work
- [x] Test command is available
- [ ] API key is configured
- [ ] Test email is received
- [ ] Registration email is received
- [ ] Password reset email is received
- [ ] Emails appear in Resend dashboard

## 🎉 Completion

Once all checkboxes are marked:
- [ ] Email integration is complete
- [ ] All email flows are tested
- [ ] Production is configured (if applicable)
- [ ] Monitoring is set up
- [ ] Documentation is reviewed

---

**Need Help?**
- Check `storage/logs/laravel.log` for errors
- Review Resend dashboard for delivery status
- Run `php artisan resend:test` to diagnose issues
- Consult `RESEND_SETUP.md` for detailed troubleshooting

**Last Updated:** May 4, 2026
