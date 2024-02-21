<?php

namespace App\Controllers\Frontend;

use App\Core\Controller;
use App\Core\Form;
use App\Core\Route;

class HomeController extends Controller
{
    #[Route('homepage', '/', ['GET'])]
    public function homepage(): void
    {
        $this->render('Frontend/home.php', [
            'meta' => [
                'title' => 'Homepage',
            ]
        ]);
    }
}
