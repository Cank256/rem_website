# Resend Email Integration Setup Guide

This guide explains how to set up and use Resend.com for sending emails in your Laravel application.

## What's Been Configured

The application has been configured to use Resend.com for:
1. **User Registration Emails** - Welcome emails sent when new users register
2. **Password Reset Emails** - Password reset links sent when users forget their password
3. **Email Verification** - Email verification links (if enabled)

## Setup Steps

### 1. Get Your Resend API Key

1. Go to [Resend.com](https://resend.com) and sign up for an account
2. Navigate to the API Keys section in your dashboard
3. Create a new API key
4. Copy the API key (it will only be shown once)

### 2. Configure Your Domain

For production use, you need to verify your domain with Resend:

1. In your Resend dashboard, go to **Domains**
2. Click **Add Domain**
3. Enter your domain: `ruralevangelicalministries.org`
4. Add the DNS records provided by Resend to your domain's DNS settings
5. Wait for verification (usually takes a few minutes)

**Note:** For testing, you can use Resend's test domain, but emails will only be sent to verified email addresses.

### 3. Update Environment Variables

#### For Local Development (.env)

```env
MAIL_MAILER=resend
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="Rural Evangelical Ministries"

RESEND_API_KEY=re_your_test_api_key_here
```

#### For Production (.env.production)

```env
MAIL_MAILER=resend
MAIL_FROM_ADDRESS="noreply@ruralevangelicalministries.org"
MAIL_FROM_NAME="Rural Evangelical Ministries"

RESEND_API_KEY=re_your_production_api_key_here
```

### 4. Clear Configuration Cache

After updating the environment variables, clear the configuration cache:

```bash
php artisan config:clear
php artisan cache:clear
```

## How It Works

### User Registration Flow

When a new user registers via `/register`:

1. User submits registration form
2. User account is created in the database
3. Laravel fires the `Registered` event
4. Email verification notification is sent via Resend (if email verification is enabled)
5. User is logged in and redirected to dashboard

The registration is handled in:
- `app/Http/Controllers/Auth/RegisteredUserController.php`

### Password Reset Flow

When a user requests a password reset via `/forgot-password`:

1. User enters their email address
2. Laravel generates a password reset token
3. Password reset email is sent via Resend with the reset link
4. User clicks the link in the email
5. User enters new password
6. Password is updated

The password reset is handled in:
- `app/Http/Controllers/Auth/PasswordResetLinkController.php`
- `app/Http/Controllers/Auth/NewPasswordController.php`

## Testing Email Sending

### Test User Registration Email

1. Make sure your `.env` file has the correct Resend API key
2. Register a new user at `/register`
3. Check the Resend dashboard for sent emails
4. Check your email inbox (if email verification is enabled)

### Test Password Reset Email

1. Go to `/forgot-password`
2. Enter a registered user's email address
3. Check the Resend dashboard for sent emails
4. Check your email inbox for the reset link

### Using Artisan Tinker for Testing

You can also test email sending directly:

```bash
php artisan tinker
```

Then run:

```php
// Test a simple email
Mail::raw('Test email from Laravel', function ($message) {
    $message->to('your-email@example.com')
            ->subject('Test Email');
});

// Test password reset email
use Illuminate\Support\Facades\Password;
Password::sendResetLink(['email' => 'user@example.com']);
```

## Email Templates

Laravel uses Blade templates for emails. The default templates are located in:
- `resources/views/vendor/notifications/email.blade.php` (if published)

To customize email templates:

```bash
php artisan vendor:publish --tag=laravel-notifications
```

This will create customizable templates in `resources/views/vendor/notifications/`.

## Queue Configuration

For better performance in production, configure email sending to use queues:

1. Update `.env`:
```env
QUEUE_CONNECTION=database
```

2. Make sure queue tables exist:
```bash
php artisan queue:table
php artisan migrate
```

3. Run the queue worker:
```bash
php artisan queue:work
```

Or use a process manager like Supervisor to keep the queue worker running.

## Monitoring and Debugging

### Check Resend Dashboard

- View sent emails, delivery status, and bounce rates
- Monitor API usage and limits
- View detailed logs for each email

### Laravel Logs

Check `storage/logs/laravel.log` for any email-related errors.

### Test Mode

To test without actually sending emails, you can temporarily switch back to log driver:

```env
MAIL_MAILER=log
```

Emails will be written to `storage/logs/laravel.log` instead of being sent.

## Troubleshooting

### Emails Not Sending

1. **Check API Key**: Ensure `RESEND_API_KEY` is set correctly in `.env`
2. **Clear Cache**: Run `php artisan config:clear`
3. **Check Domain**: Verify your domain is verified in Resend dashboard
4. **Check From Address**: Ensure `MAIL_FROM_ADDRESS` uses your verified domain
5. **Check Logs**: Look for errors in `storage/logs/laravel.log`

### "Domain not verified" Error

- You need to verify your domain in the Resend dashboard
- For testing, use a verified email address or Resend's test domain

### Rate Limits

- Free tier: 100 emails/day
- Check your Resend plan limits
- Consider upgrading if you need more capacity

## Security Best Practices

1. **Never commit API keys**: Keep `.env` files out of version control
2. **Use different keys**: Use separate API keys for development and production
3. **Rotate keys**: Regularly rotate your API keys
4. **Monitor usage**: Watch for unusual sending patterns in Resend dashboard
5. **Validate email addresses**: Always validate user input before sending emails

## Additional Resources

- [Resend Documentation](https://resend.com/docs)
- [Laravel Mail Documentation](https://laravel.com/docs/mail)
- [Resend PHP SDK](https://github.com/resendlabs/resend-php)

## Support

If you encounter issues:
1. Check the Resend status page
2. Review Laravel logs
3. Contact Resend support
4. Check Laravel documentation
