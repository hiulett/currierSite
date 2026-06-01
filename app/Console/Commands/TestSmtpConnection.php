<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestSmtpConnection extends Command
{
    protected $signature = 'mail:test-smtp';
    protected $description = 'Test SMTP connection';

    public function handle()
    {
        $host = config('mail.host');
        $port = config('mail.port');
        $timeout = 10;

        $this->info("Testing SMTP connection to $host:$port");
        $this->info("Timeout: {$timeout}s\n");

        // Test 1: DNS Resolution
        $this->line('1. DNS Resolution:');
        $ip = gethostbyname($host);
        if ($ip === $host) {
            $this->error("   ❌ FAILED: Could not resolve $host");
        } else {
            $this->info("   ✅ SUCCESS: $host resolves to $ip");
        }

        // Test 2: TCP Connection
        $this->line("\n2. TCP Connection:");
        $socket = @fsockopen($host, $port, $errno, $errstr, $timeout);
        if ($socket) {
            $this->info("   ✅ SUCCESS: Connected to $host:$port");
            
            // Test 3: SMTP Handshake
            $this->line("\n3. SMTP Handshake:");
            $response = fgets($socket, 1024);
            $this->info("   Server response: " . trim($response));
            
            // Send EHLO
            fputs($socket, "EHLO test\r\n");
            $response = fgets($socket, 1024);
            $this->info("   EHLO response: " . trim($response));
            
            // Close connection
            fputs($socket, "QUIT\r\n");
            fclose($socket);
        } else {
            $this->error("   ❌ FAILED: Could not connect to $host:$port");
            $this->error("   Error: $errstr (Code: $errno)");
        }

        $this->line("\n4. Mail Configuration:");
        $this->info("   MAIL_MAILER: " . config('mail.mailer'));
        $this->info("   MAIL_HOST: " . config('mail.host'));
        $this->info("   MAIL_PORT: " . config('mail.port'));
        $this->info("   MAIL_ENCRYPTION: " . config('mail.encryption'));
        $this->info("   MAIL_FROM_ADDRESS: " . config('mail.from.address'));
    }
}

