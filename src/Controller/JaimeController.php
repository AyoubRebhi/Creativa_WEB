<?php

namespace App\Twig;

use App\Repository\JaimeRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class JaimeExtension extends AbstractExtension
{
    private $jaimeRepository;

    public function __construct(JaimeRepository $jaimeRepository)
    {
        $this->jaimeRepository = $jaimeRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getJaimeCountForProject', [$this, 'getJaimeCountForProject']),
        ];
    }

    public function getJaimeCountForProject($projectId)
    {
        return $this->jaimeRepository->count(['idProjet' => $projectId]);
    }
}
