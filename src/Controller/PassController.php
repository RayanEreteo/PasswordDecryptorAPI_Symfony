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
            return new JsonResponse(['success' => false, 'message' => 'Une erreur est survenu, merci de réessayer.']);
        }

        $encryptedPass = password_hash($plainPass, PASSWORD_BCRYPT);

        return new JsonResponse(['hash' => $encryptedPass, JsonResponse::HTTP_OK]);
    }

    #[Route('/decryptPass', name: 'pass_decrypt', methods: ['POST'])]
    public function decrypt(Request $request): JsonResponse
    {
        $plainPass = $request->get('plainPass');
        $hash = $request->get('hash');

        $decryptedPass = password_verify($plainPass, $hash);

        if ($decryptedPass === false) {
            return new JsonResponse(['success' => false, 'message' => 'Le mot de passe ne correspond pas au hash donnée.']);
        }

        return new JsonResponse(['success' => true, 'message' => 'Le mot de passe correspond au hash donnée.']);
    }
}
