<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestResendEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resend:test {email : The email address to send the test email to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test email using Resend to verify the integration';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');

        $this->info('Sending test email to: ' . $email);
        $this->info('Using mailer: ' . config('mail.default'));
        $this->info('From address: ' . config('mail.from.address'));

        try {
            Mail::raw('This is a test email from your Laravel application using Resend.com API. If you received this email, your integration is working correctly!', function ($message) use ($email) {
                $message->to($email)
                    ->subject('Test Email from ' . config('app.name'));
            });

            $this->info('✓ Test email sent successfully!');
            $this->info('Check your inbox at: ' . $email);
            $this->info('Also check the Resend dashboard for delivery status.');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('✗ Failed to send test email');
            $this->error('Error: ' . $e->getMessage());
            $this->newLine();
            $this->warn('Troubleshooting tips:');
            $this->warn('1. Check that RESEND_API_KEY is set in your .env file');
            $this->warn('2. Run: php artisan config:clear');
            $this->warn('3. Verify your domain in the Resend dashboard');
            $this->warn('4. Check that MAIL_FROM_ADDRESS uses your verified domain');

            return Command::FAILURE;
        }
    }
}
