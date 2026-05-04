# Resend Email Integration - Quick Start

## 🚀 Quick Setup (5 minutes)

### 1. Get Your API Key
1. Sign up at [resend.com](https://resend.com)
2. Go to **API Keys** → **Create API Key**
3. Copy the key (starts with `re_`)

### 2. Update .env File
```env
MAIL_MAILER=resend
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
RESEND_API_KEY=re_your_api_key_here
```

### 3. Clear Cache
```bash
php artisan config:clear
```

### 4. Test It
```bash
php artisan resend:test your-email@example.com
```

## ✅ What's Already Working

### User Registration Email
When a user registers at `/register`, they automatically receive a welcome email.

**Location:** `app/Http/Controllers/Auth/RegisteredUserController.php`

### Password Reset Email
When a user requests password reset at `/forgot-password`, they receive a reset link.

**Location:** `app/Http/Controllers/Auth/PasswordResetLinkController.php`

## 📧 Email Templates

### Welcome Email
- **File:** `app/Notifications/WelcomeEmailNotification.php`
- **Sent:** When user registers
- **Customize:** Edit the `toMail()` method

### Password Reset Email
- **Built-in:** Laravel's default password reset notification
- **Customize:** Publish with `php artisan vendor:publish --tag=laravel-notifications`

## 🔧 Testing Commands

### Send Test Email
```bash
php artisan resend:test your-email@example.com
```

### Test in Tinker
```bash
php artisan tinker
```
```php
// Send test email
Mail::raw('Test', fn($m) => $m->to('test@example.com')->subject('Test'));

// Test password reset
Password::sendResetLink(['email' => 'user@example.com']);
```

## 🌐 Domain Setup (Production)

### For Testing (Development)
- Use any email address
- Resend will send to any address in test mode

### For Production
1. Go to Resend Dashboard → **Domains**
2. Add your domain: `ruralevangelicalministries.org`
3. Add DNS records provided by Resend
4. Wait for verification
5. Update `.env.production`:
```env
MAIL_FROM_ADDRESS="noreply@ruralevangelicalministries.org"
```

## 🐛 Troubleshooting

### Emails not sending?
```bash
# 1. Check configuration
php artisan config:show mail

# 2. Clear all caches
php artisan config:clear
php artisan cache:clear

# 3. Check logs
tail -f storage/logs/laravel.log
```

### Common Issues

| Issue | Solution |
|-------|----------|
| "Invalid API key" | Check `RESEND_API_KEY` in `.env` |
| "Domain not verified" | Verify domain in Resend dashboard |
| "From address invalid" | Use verified domain in `MAIL_FROM_ADDRESS` |
| Emails in spam | Set up SPF, DKIM, DMARC records |

## 📊 Monitoring

### Resend Dashboard
- View sent emails
- Check delivery status
- Monitor bounce rates
- Track API usage

### Laravel Logs
```bash
tail -f storage/logs/laravel.log
```

## ⚡ Performance (Optional)

### Use Queues for Better Performance
```env
QUEUE_CONNECTION=database
```

```bash
# Run migrations
php artisan queue:table
php artisan migrate

# Start queue worker
php artisan queue:work
```

## 📝 Customization Examples

### Customize Welcome Email
Edit `app/Notifications/WelcomeEmailNotification.php`:

```php
public function toMail(object $notifiable): MailMessage
{
    return (new MailMessage)
        ->subject('Welcome!')
        ->greeting('Hello ' . $notifiable->name)
        ->line('Your custom message here')
        ->action('Get Started', url('/dashboard'))
        ->line('Thank you!');
}
```

### Add More Email Notifications

```bash
php artisan make:notification YourNotification
```

Then send it:
```php
$user->notify(new YourNotification());
```

## 🔐 Security Checklist

- [ ] Never commit `.env` file
- [ ] Use different API keys for dev/production
- [ ] Verify domain in production
- [ ] Monitor sending patterns
- [ ] Set up rate limiting if needed

## 📚 Resources

- [Full Setup Guide](./RESEND_SETUP.md)
- [Resend Docs](https://resend.com/docs)
- [Laravel Mail Docs](https://laravel.com/docs/mail)

## 🆘 Need Help?

1. Check `storage/logs/laravel.log`
2. Review Resend dashboard
3. Run test command: `php artisan resend:test`
4. Check [Resend Status](https://status.resend.com)
