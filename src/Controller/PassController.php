<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PassController extends AbstractController
{
    #[Route('/encrypt', name: 'pass_encrypt', methods: ['POST'])]
    public function encrypt(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if ($data == null) {
            return new JsonResponse(['success' => false, 'message' => 'Aucune donnée.']);
        }

        $plainPass = $data['plainPass'];

        if ($plainPass == '') {
            return new JsonResponse(['success' => false, 'message' => 'Aucune donnée.']);
        }

        $encryptedPass = password_hash($plainPass, PASSWORD_BCRYPT);

        return new JsonResponse(['success' => true, 'hash' => $encryptedPass, 'original' => $plainPass]);
    }

    #[Route('/verify', name: 'pass_decrypt', methods: ['POST'])]
    public function decrypt(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if ($data == null) {
            return new JsonResponse(['success' => false, 'message' => 'Aucune donnée.']);
        }

        $plainPass = $data["plainPass"];
        $hash = $data["hash"];
        
        if ($plainPass == '' || $hash == '') {
            return new JsonResponse(['success' => false, 'message' => 'Aucune donnée.']);
        }

        $decryptedPass = password_verify($plainPass, $hash);

        if ($decryptedPass === false) {
            return new JsonResponse(['success' => false, 'message' => 'Le mot de passe ne correspond pas au hash donnée.']);
        }

        return new JsonResponse(['success' => true, 'message' => 'Le mot de passe correspond au hash donnée.']);
    }
}
