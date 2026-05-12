<?php

declare(strict_types=1);

final class RateLimiter
{
    public function __construct(
        private InquiryRepository $repository,
        private int $windowSeconds,
        private int $maxAttempts,
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
}
