<?php

namespace toubilib\api\providers\auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use toubilib\core\application\ports\api\providersInterfaces\JwtManagerInterface;
use toubilib\core\domain\exceptions\JwtManagerExpiredTokenException;
use toubilib\core\domain\exceptions\JwtManagerInvalidTokenException;

class JwtManager implements JwtManagerInterface {
    private string $secret;
    private int $access_expiration_time;
    private int $refresh_expiration_time;
    private string $issuer;

    public function __construct(string $secret, int $expirationTime, int $refreshExpirationTime) {
        $this->secret = $secret;
        $this->access_expiration_time = $expirationTime;
        $this->refresh_expiration_time = $refreshExpirationTime;
    }

    public function setIssuer(string $issuer): void {
        $this->issuer = $issuer;
    }

    public function create(array $payload, int $type): string {
        if ($type === JwtManagerInterface::ACCESS_TOKEN) {
            $expirationTime = time() + $this->access_expiration_time;
        } else {
            $expirationTime = time() + $this->refresh_expiration_time;
        }

        $token = JWT::encode([
            'iss' => $this->issuer,
            'sub' => $payload['id'],
            'iat' => time(),
            'exp' => $expirationTime,
            'upr' => $payload
        ], $this->secret, 'HS512');

        return $token;
    }

    public function validate(string $jwtToken): array {
        try {
            $jwtToken = JWT::decode($jwtToken, new Key($this->secret, 'HS512'));
        } catch (ExpiredException $e) {
            throw new JwtManagerExpiredTokenException("expired jwt token");
        } catch (SignatureInvalidException | \UnexpectedValueException | \DomainException $e) {
            throw new JwtManagerInvalidTokenException("invalid jwt token");
        }

        return (array) $jwtToken->upr;
    }
}