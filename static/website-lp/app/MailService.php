<?php

declare(strict_types=1);

final class MailService
{
    private string $adminSubject;
    private string $autoReplySubject;

    public function __construct()
    {
        $this->adminSubject = (string) config('mail.admin_subject', '【DF CONNECT】お問い合わせがありました');
        $this->autoReplySubject = (string) config('mail.auto_reply_subject', '【DF CONNECT】お問い合わせありがとうございます');
    }

    public function sendAdminNotification(array $inquiry): void
    {
        $body = $this->buildAdminBody($inquiry);
        $to = (string) config('app.admin_email', 'your-admin@example.com');
        $toName = (string) config('app.admin_name', 'DF CONNECT');
        $from = (string) config('app.from_email', 'no-reply@example.com');
        $fromName = (string) config('app.from_name', 'DF CONNECT');

        if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
            $mailer = $this->createMailer();
            $mailer->setFrom($from, $fromName);
            $mailer->addAddress($to, $toName);
            $mailer->addReplyTo($inquiry['email'], $inquiry['name']);
            $mailer->isHTML(false);
            $mailer->Subject = $this->adminSubject;
            $mailer->Body = $body;
            $mailer->send();

            return;
        }

        $headers = [];
        $headers[] = 'From: ' . sprintf('%s <%s>', $fromName, $from);
        $headers[] = 'Reply-To: ' . sprintf('%s <%s>', $inquiry['name'], $inquiry['email']);
        $headers[] = 'Content-Type: text/plain; charset=UTF-8';

        $ok = mail($to, $this->adminSubject, $body, implode("\r\n", $headers));
        if (!$ok) {
            throw new RuntimeException('PHP mail() failed');
        }
    }

    public function sendAutoReply(array $inquiry): void
    {
        $body = $this->buildAutoReplyBody($inquiry);
        $to = $inquiry['email'];
        $toName = $inquiry['name'];
        $from = (string) config('app.from_email', 'no-reply@example.com');
        $fromName = (string) config('app.from_name', 'DF CONNECT');

        if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
            $mailer = $this->createMailer();
            $mailer->setFrom($from, $fromName);
            $mailer->addAddress($to, $toName);
            $mailer->isHTML(false);
            $mailer->Subject = $this->autoReplySubject;
            $mailer->Body = $body;
            $mailer->send();

            return;
        }

        $headers = [];
        $headers[] = 'From: ' . sprintf('%s <%s>', $fromName, $from);
        $headers[] = 'Content-Type: text/plain; charset=UTF-8';

        $ok = mail($to, $this->autoReplySubject, $body, implode("\r\n", $headers));
        if (!$ok) {
            throw new RuntimeException('PHP mail() failed');
        }
    }

    private function createMailer(): \PHPMailer\PHPMailer\PHPMailer
    {
        $mailer = new \PHPMailer\PHPMailer\PHPMailer(true);
        $mailer->isSMTP();
        $mailer->SMTPAuth = (bool) config('mail.smtp_auth', true);
        $mailer->Host = (string) config('mail.host', '');
        $mailer->Port = (int) config('mail.port', 465);
        $mailer->Username = (string) config('mail.username', '');
        $mailer->Password = (string) config('mail.password', '');
        $mailer->SMTPSecure = (string) config('mail.encryption', 'ssl');
        $mailer->CharSet = (string) config('mail.charset', 'UTF-8');
        $mailer->Encoding = 'base64';

        return $mailer;
    }

    private function buildAdminBody(array $inquiry): string
    {
        return <<<TEXT
DF CONNECTのフォームからお問い合わせがありました。

問い合わせID：{$inquiry['public_id']}
送信日時：{$inquiry['created_at']}

------------------------------
会社名：{$inquiry['company']}
お名前：{$inquiry['name']}
メールアドレス：{$inquiry['email']}
相談内容：{$inquiry['inquiry_type']}
------------------------------

メッセージ：
{$inquiry['message']}

------------------------------
IPアドレス：{$inquiry['ip_address']}
User-Agent：{$inquiry['user_agent']}
Referrer：{$inquiry['referrer']}
------------------------------

このメールに返信すると、問い合わせ者のメールアドレス宛に返信されます。
TEXT;
    }

    private function buildAutoReplyBody(array $inquiry): string
    {
        return <<<TEXT
{$inquiry['name']} 様

この度はDF CONNECTへお問い合わせいただきありがとうございます。
内容を確認のうえ、通常1〜2営業日以内にご返信いたします。

送信内容：
--------------------
会社名：{$inquiry['company']}
相談内容：{$inquiry['inquiry_type']}
メッセージ：
{$inquiry['message']}
--------------------

DF CONNECT
TEXT;
    }
}
