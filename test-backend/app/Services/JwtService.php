<?php

namespace App\Services;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use App\Models\User;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\UnencryptedToken;

class JwtService
{
    private Configuration $config;

    public function __construct()
    {
        $this->config = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText(env('JWT_SECRET', 'secret'))
        );
    }

    public function generateToken(User $user): string
    {
        $now = new \DateTimeImmutable();
        $expiresAt = $now->modify('+1 hour');

        return $this->config->builder()
            ->identifiedBy(bin2hex(random_bytes(16)))
            ->issuedAt($now)
            ->expiresAt($expiresAt)
            ->relatedTo((string) $user->id)
            ->withClaim('email', $user->email)
            ->getToken($this->config->signer(), $this->config->signingKey())
            ->toString();
    }

    private function getConfiguration(): Configuration
    {
        return $this->config;
    }

    private function getValidationConstraints(): array
    {
        $config = $this->getConfiguration();
        return [
            new SignedWith($config->signer(), $config->signingKey()),
            new LooseValidAt(SystemClock::fromUTC()),
        ];
    }

    public function validateToken(string $token): bool
    {
        try {
            $config = $this->getConfiguration();
            $parsedToken = $config->parser()->parse($token);
            $constraints = $this->getValidationConstraints();
            $validator = $this->config->validator();

            return $validator->validate($parsedToken, ...$constraints);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function parseToken(string $token): UnencryptedToken
    {
        $config = $this->getConfiguration();
        return $config->parser()->parse($token);
    }
}
