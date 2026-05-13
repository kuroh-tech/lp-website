<?php

declare(strict_types=1);

final class InquiryRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function insertInquiry(array $data, array $meta): array
    {
        $publicId = bin2hex(random_bytes(16));

        $stmt = $this->pdo->prepare(
            'INSERT INTO inquiries (
                public_id, company, name, email, company_type, inquiry_type,
                response_style, desired_timing, budget_range, nda,
                contact_method, message, privacy_agreed, ip_address, user_agent, referrer,
                status, admin_mail_status, auto_reply_status
            ) VALUES (
                :public_id, :company, :name, :email, :company_type, :inquiry_type,
                :response_style, :desired_timing, :budget_range, :nda,
                :contact_method, :message, :privacy_agreed, :ip_address, :user_agent, :referrer,
                :status, :admin_mail_status, :auto_reply_status
            )'
        );

        $status = 'new';
        $pending = 'pending';

        $stmt->execute([
            ':public_id' => $publicId,
            ':company' => $data['company'],
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':company_type' => $data['company_type'],
            ':inquiry_type' => $data['inquiry_type'],
            ':response_style' => $data['response_style'],
            ':desired_timing' => $data['desired_timing'],
            ':budget_range' => $data['budget_range'],
            ':nda' => $data['nda'],
            ':contact_method' => $data['contact_method'],
            ':message' => $data['message'],
            ':privacy_agreed' => 1,
            ':ip_address' => $meta['ip_address'],
            ':user_agent' => $meta['user_agent'],
            ':referrer' => $meta['referrer'],
            ':status' => $status,
            ':admin_mail_status' => $pending,
            ':auto_reply_status' => $pending,
        ]);

        $id = (int) $this->pdo->lastInsertId();

        return [
            'id' => $id,
            'public_id' => $publicId,
        ];
    }

    public function updateMailStatuses(int $id, string $adminStatus, string $autoStatus): void
    {
        $query = 'UPDATE inquiries SET admin_mail_status = :admin_mail_status, auto_reply_status = :auto_reply_status';
        if ($adminStatus === 'sent') {
            $query .= ', admin_mail_sent_at = CURRENT_TIMESTAMP';
        }
        if ($autoStatus === 'sent') {
            $query .= ', auto_reply_sent_at = CURRENT_TIMESTAMP';
        }

        $query .= ' WHERE id = :id';

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':admin_mail_status' => $adminStatus,
            ':auto_reply_status' => $autoStatus,
            ':id' => $id,
        ]);
    }

    public function logAttempt(string $ipAddress, ?string $email, string $result, ?string $reason = null): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO inquiry_attempts (ip_address, email, result, reason) VALUES (:ip_address, :email, :result, :reason)'
        );
        $stmt->execute([
            ':ip_address' => $ipAddress !== '' ? $ipAddress : null,
            ':email' => $email !== '' ? $email : null,
            ':result' => $result,
            ':reason' => $reason,
        ]);
    }

    public function countAttemptsByIp(string $ipAddress, int $windowSeconds): int
    {
        if ($ipAddress === '') {
            return 0;
        }

        $windowSince = (new DateTimeImmutable("-{$windowSeconds} seconds"))->format('Y-m-d H:i:s');

        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) AS count FROM inquiry_attempts WHERE ip_address = :ip AND created_at >= :window'
        );
        $stmt->bindValue(':ip', $ipAddress, PDO::PARAM_STR);
        $stmt->bindValue(':window', $windowSince, PDO::PARAM_STR);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        return (int) $count;
    }

    public function countAttemptsByEmail(string $email, int $windowSeconds): int
    {
        if ($email === '') {
            return 0;
        }

        $windowSince = (new DateTimeImmutable("-{$windowSeconds} seconds"))->format('Y-m-d H:i:s');

        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) AS count FROM inquiry_attempts WHERE email = :email AND created_at >= :window'
        );
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':window', $windowSince, PDO::PARAM_STR);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        return (int) $count;
    }
}
