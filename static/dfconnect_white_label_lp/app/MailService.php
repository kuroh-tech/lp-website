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
        $mailer = $this->createMailer();
        $mailer->setFrom(config('app.from_email', 'no-reply@dfconnect.jp'), config('app.from_name', 'DF CONNECT'));
        $mailer->addAddress(config('app.admin_email', 'your-admin@example.com'), config('app.admin_name', 'DF CONNECT'));
        $mailer->addReplyTo($inquiry['email'], $inquiry['name']);
        $mailer->isHTML(false);
        $mailer->Subject = $this->adminSubject;
        $mailer->Body = $this->buildAdminBody($inquiry);
        $mailer->send();
    }

    public function sendAutoReply(array $inquiry): void
    {
        $mailer = $this->createMailer();
        $mailer->setFrom(config('app.from_email', 'no-reply@dfconnect.jp'), config('app.from_name', 'DF CONNECT'));
        $mailer->addAddress($inquiry['email'], $inquiry['name']);
        $mailer->isHTML(false);
        $mailer->Subject = $this->autoReplySubject;
        $mailer->Body = $this->buildAutoReplyBody($inquiry);
        $mailer->send();
    }

    private function createMailer(): \PHPMailer\PHPMailer\PHPMailer
    {
        if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            throw new RuntimeException('PHPMailer is not installed. Run composer install --no-dev.');
        }

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
会社区分：{$inquiry['company_type']}
相談内容：{$inquiry['inquiry_type']}
希望の対応スタイル：{$inquiry['response_style']}
希望時期：{$inquiry['desired_timing']}
予算感：{$inquiry['budget_range']}
NDA希望：{$inquiry['nda']}
希望の連絡方法：{$inquiry['contact_method']}
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

なお、クライアント名・案件詳細などの機密情報は、NDA締結後の共有でも問題ありません。
まずは概要ベースで確認させていただきます。

送信内容：
--------------------
会社名：{$inquiry['company']}
相談内容：{$inquiry['inquiry_type']}
希望時期：{$inquiry['desired_timing']}
メッセージ：
{$inquiry['message']}
--------------------

DF CONNECT
TEXT;
    }
}
