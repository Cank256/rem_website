# Email Flow Diagrams

Visual representation of how emails are sent in your application.

## 📧 User Registration Email Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                     USER REGISTRATION FLOW                       │
└─────────────────────────────────────────────────────────────────┘

    User visits /register
           │
           ▼
    ┌──────────────────┐
    │  Registration    │
    │  Form Submitted  │
    └────────┬─────────┘
             │
             ▼
    ┌──────────────────────────────────────┐
    │  RegisteredUserController.php        │
    │  - Validates input                   │
    │  - Creates user in database          │
    │  - Fires Registered event            │
    └────────┬─────────────────────────────┘
             │
             ▼
    ┌──────────────────────────────────────┐
    │  Send Welcome Email                  │
    │  $user->notify(                      │
    │    new WelcomeEmailNotification()    │
    │  )                                   │
    └────────┬─────────────────────────────┘
             │
             ▼
    ┌──────────────────────────────────────┐
    │  Queue System (Optional)             │
    │  - Email added to queue              │
    │  - Processed by queue worker         │
    └────────┬─────────────────────────────┘
             │
             ▼
    ┌──────────────────────────────────────┐
    │  Laravel Mail System                 │
    │  - Builds email from notification    │
    │  - Uses configured mailer (resend)   │
    └────────┬─────────────────────────────┘
             │
             ▼
    ┌──────────────────────────────────────┐
    │  Resend API                          │
    │  - Receives email via API            │
    │  - Validates sender domain           │
    │  - Processes email                   │
    └────────┬─────────────────────────────┘
             │
             ▼
    ┌──────────────────────────────────────┐
    │  Email Delivery                      │
    │  - Sent to user's inbox              │
    │  - Delivery tracked in dashboard     │
    └──────────────────────────────────────┘
             │
             ▼
    User receives welcome email
```

## 🔐 Password Reset Email Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                   PASSWORD RESET EMAIL FLOW                      │
└─────────────────────────────────────────────────────────────────┘

    User visits /forgot-password
           │
           ▼
    ┌──────────────────┐
    │  Forgot Password │
    │  Form Submitted  │
    └────────┬─────────┘
             │
             ▼
    ┌──────────────────────────────────────┐
    │  PasswordResetLinkController.php     │
    │  - Validates email                   │
    │  - Checks if user exists             │
    └────────┬─────────────────────────────┘
             │
             ▼
    ┌──────────────────────────────────────┐
    │  Password Facade                     │
    │  Password::sendResetLink([           │
    │    'email' => $request->email        │
    │  ])                                  │
    └────────┬─────────────────────────────┘
             │
             ▼
    ┌──────────────────────────────────────┐
    │  Laravel Password Reset System       │
    │  - Generates secure token            │
    │  - Stores token in database          │
    │  - Creates reset URL with token      │
    └────────┬─────────────────────────────┘
             │
             ▼
    ┌──────────────────────────────────────┐
    │  Send Reset Email                    │
    │  - Uses ResetPassword notification   │
    │  - Includes reset link with token    │
    └────────┬─────────────────────────────┘
             │
             ▼
    ┌──────────────────────────────────────┐
    │  Queue System (Optional)             │
    │  - Email added to queue              │
    │  - Processed by queue worker         │
    └────────┬─────────────────────────────┘
             │
             ▼
    ┌──────────────────────────────────────┐
    │  Laravel Mail System                 │
    │  - Builds email with reset link      │
    │  - Uses configured mailer (resend)   │
    └────────┬─────────────────────────────┘
             │
             ▼
    ┌──────────────────────────────────────┐
    │  Resend API                          │
    │  - Receives email via API            │
    │  - Validates sender domain           │
    │  - Processes email                   │
    └────────┬─────────────────────────────┘
             │
             ▼
    ┌──────────────────────────────────────┐
    │  Email Delivery                      │
    │  - Sent to user's inbox              │
    │  - Delivery tracked in dashboard     │
    └──────────────────────────────────────┘
             │
             ▼
    User receives reset email
           │
           ▼
    User clicks reset link
           │
           ▼
    ┌──────────────────┐
    │  /reset-password │
    │  with token      │
    └────────┬─────────┘
             │
             ▼
    User enters new password
           │
           ▼
    Password updated in database
```

## 🔄 Email Processing Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                    EMAIL SYSTEM ARCHITECTURE                     │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                        APPLICATION LAYER                         │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │
│  │ Controllers  │  │ Notifications│  │   Events     │         │
│  │              │  │              │  │              │         │
│  │ - Register   │  │ - Welcome    │  │ - Registered │         │
│  │ - Password   │  │ - Reset      │  │              │         │
│  │   Reset      │  │              │  │              │         │
│  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘         │
│         │                 │                 │                  │
│         └─────────────────┼─────────────────┘                  │
│                           │                                     │
└───────────────────────────┼─────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│                      LARAVEL MAIL SYSTEM                         │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────────────────────────────────────────────┐      │
│  │              Mail Facade / Notification              │      │
│  │  - Builds email content                              │      │
│  │  - Applies templates                                 │      │
│  │  - Handles recipients                                │      │
│  └────────────────────┬─────────────────────────────────┘      │
│                       │                                         │
│                       ▼                                         │
│  ┌──────────────────────────────────────────────────────┐      │
│  │              Mail Manager                            │      │
│  │  - Selects mailer (resend)                           │      │
│  │  - Reads configuration                               │      │
│  └────────────────────┬─────────────────────────────────┘      │
│                       │                                         │
└───────────────────────┼─────────────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────────────┐
│                    QUEUE SYSTEM (Optional)                       │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────┐      ┌──────────────┐      ┌──────────────┐ │
│  │   Queue      │ ───▶ │    Queue     │ ───▶ │    Queue     │ │
│  │   Job        │      │   Worker     │      │  Processor   │ │
│  └──────────────┘      └──────────────┘      └──────┬───────┘ │
│                                                      │          │
└──────────────────────────────────────────────────────┼──────────┘
                                                       │
                                                       ▼
┌─────────────────────────────────────────────────────────────────┐
│                      RESEND TRANSPORT                            │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────────────────────────────────────────────┐      │
│  │           Resend PHP SDK                             │      │
│  │  - Formats email for Resend API                      │      │
│  │  - Adds authentication (API key)                     │      │
│  │  - Handles API communication                         │      │
│  └────────────────────┬─────────────────────────────────┘      │
│                       │                                         │
└───────────────────────┼─────────────────────────────────────────┘
                        │
                        ▼ HTTPS
┌─────────────────────────────────────────────────────────────────┐
│                        RESEND API                                │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │
│  │   Validate   │  │   Process    │  │   Deliver    │         │
│  │   - API Key  │  │   - Queue    │  │   - SMTP     │         │
│  │   - Domain   │  │   - Format   │  │   - Track    │         │
│  │   - Content  │  │   - Route    │  │   - Log      │         │
│  └──────────────┘  └──────────────┘  └──────┬───────┘         │
│                                              │                  │
└──────────────────────────────────────────────┼──────────────────┘
                                               │
                                               ▼
                                    ┌──────────────────┐
                                    │  User's Inbox    │
                                    └──────────────────┘
```

## 📊 Configuration Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                    CONFIGURATION HIERARCHY                       │
└─────────────────────────────────────────────────────────────────┘

    .env file
    ├── MAIL_MAILER=resend
    ├── MAIL_FROM_ADDRESS=noreply@domain.com
    ├── MAIL_FROM_NAME=Church Name
    └── RESEND_API_KEY=re_xxxxx
           │
           ▼
    config/mail.php
    ├── default: env('MAIL_MAILER')
    ├── from: [
    │     'address' => env('MAIL_FROM_ADDRESS'),
    │     'name' => env('MAIL_FROM_NAME')
    │   ]
    └── mailers: [
          'resend' => [
            'transport' => 'resend',
            'key' => env('RESEND_API_KEY')
          ]
        ]
           │
           ▼
    Laravel Mail System
    ├── Reads configuration
    ├── Selects resend transport
    └── Uses API key for authentication
           │
           ▼
    Resend API
    └── Sends email
```

## 🔍 Debugging Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                      DEBUGGING WORKFLOW                          │
└─────────────────────────────────────────────────────────────────┘

    Email not sending?
           │
           ▼
    ┌──────────────────────────────────────┐
    │  Step 1: Check Configuration         │
    │  php artisan config:show mail        │
    │  - Verify MAIL_MAILER=resend         │
    │  - Check RESEND_API_KEY is set       │
    └────────┬─────────────────────────────┘
             │
             ▼
    ┌──────────────────────────────────────┐
    │  Step 2: Clear Caches                │
    │  php artisan config:clear            │
    │  php artisan cache:clear             │
    └────────┬─────────────────────────────┘
             │
             ▼
    ┌──────────────────────────────────────┐
    │  Step 3: Check Laravel Logs          │
    │  tail -f storage/logs/laravel.log    │
    │  - Look for mail errors              │
    │  - Check for API errors              │
    └────────┬─────────────────────────────┘
             │
             ▼
    ┌──────────────────────────────────────┐
    │  Step 4: Test with Command           │
    │  php artisan resend:test email       │
    │  - Sends test email                  │
    │  - Shows configuration               │
    │  - Displays errors                   │
    └────────┬─────────────────────────────┘
             │
             ▼
    ┌──────────────────────────────────────┐
    │  Step 5: Check Resend Dashboard      │
    │  - View sent emails                  │
    │  - Check delivery status             │
    │  - Review error messages             │
    └────────┬─────────────────────────────┘
             │
             ▼
    ┌──────────────────────────────────────┐
    │  Step 6: Verify Domain (Production)  │
    │  - Check domain verification         │
    │  - Verify DNS records                │
    │  - Check from address                │
    └──────────────────────────────────────┘
```

## 📈 Monitoring Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                      MONITORING WORKFLOW                         │
└─────────────────────────────────────────────────────────────────┘

    Daily Monitoring
           │
           ├─▶ Resend Dashboard
           │   ├── Delivery rates
           │   ├── Bounce rates
           │   ├── Failed deliveries
           │   └── Spam complaints
           │
           ├─▶ Laravel Logs
           │   ├── storage/logs/laravel.log
           │   ├── Mail errors
           │   └── Queue failures
           │
           └─▶ Application Metrics
               ├── User registrations
               ├── Password resets
               └── Email queue length

    Weekly Review
           │
           ├─▶ Email Volume
           ├─▶ API Usage
           ├─▶ Error Trends
           └─▶ Performance Metrics

    Monthly Review
           │
           ├─▶ Plan Usage
           ├─▶ Cost Analysis
           ├─▶ Template Updates
           └─▶ Domain Health
```

---

**Note:** These diagrams provide a visual overview of the email system. For detailed implementation, refer to the code files and documentation.
