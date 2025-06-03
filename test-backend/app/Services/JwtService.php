<?php

namespace App\Services;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use App\Models\User;

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

    public function getConfiguration(): Configuration
    {
        return $this->config;
    }
}
