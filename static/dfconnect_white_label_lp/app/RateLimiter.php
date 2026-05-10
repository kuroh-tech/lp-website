<?php

declare(strict_types=1);

final class RateLimiter
{
    public function __construct(
        private readonly InquiryRepository $repository,
        private readonly int $windowSeconds,
        private readonly int $maxAttempts,
    ) {
    }

    public function isLimited(string $ipAddress, string $email): bool
    {
        $ipCount = $this->repository->countAttemptsByIp($ipAddress, $this->windowSeconds);
        if ($ipCount >= $this->maxAttempts) {
            return true;
        }

        if ($email !== '') {
            $emailCount = $this->repository->countAttemptsByEmail($email, $this->windowSeconds);
            if ($emailCount >= $this->maxAttempts) {
                return true;
            }
        }

        return false;
    }

    public function limitReason(string $ipAddress, string $email): string
    {
        if ($this->repository->countAttemptsByIp($ipAddress, $this->windowSeconds) >= $this->maxAttempts) {
            return 'ip';
        }

        if ($email !== '' && $this->repository->countAttemptsByEmail($email, $this->windowSeconds) >= $this->maxAttempts) {
            return 'email';
        }

        return '';
    }
}
