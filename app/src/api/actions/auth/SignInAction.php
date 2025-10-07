<?php

namespace toubilib\api\actions\auth;

final class SignInAction
{
    public function __construct(
        private AuthnProviderInterface $authnProvider
    ) {}

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $body = json_decode($request->getBody()->getContents(), true);
        $credentials = new CredentialsDTO($body['email'], $body['password']);

        $authDTO = $this->authnProvider->signinCredentials($credentials);

        return ApiResponseBuilder::create()
            ->status(200)
            ->data(['jwt' => $authDTO->jwt])
            ->build($response);
    }
}