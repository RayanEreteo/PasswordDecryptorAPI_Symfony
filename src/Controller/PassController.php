<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PassController extends AbstractController
{
    #[Route('/encryptPass', name: 'pass_encrypt', methods: ['POST'])]
    public function encrypt(Request $request): JsonResponse
    {
        $plainPass = $request->get('plainPass');

        if ($plainPass === null) {
            return new JsonResponse(null, JsonResponse::HTTP_BAD_REQUEST);
        }

        $encryptedPass = password_hash($plainPass, PASSWORD_BCRYPT);

        return new JsonResponse(['Hash'=> $encryptedPass, JsonResponse::HTTP_OK]);
    }

    #[Route('/decryptPass', name: 'pass_decrypt', methods: ['POST'])]
    public function decrypt(Request $request): JsonResponse
    {
        $plainPass = $request->get('plainPass');
        $hash = $request->get('hash');

        $decryptedPass = password_verify($plainPass, $hash);

        return new JsonResponse(['hashGood'=> $decryptedPass]);
    }
}