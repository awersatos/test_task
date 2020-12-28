<?php


namespace App\RequestData;

use App\Utils\DataTransferInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;

class BookData implements DataTransferInterface
{
    /**
     * @Assert\Type(type="integer")
     * @Assert\NotBlank()
     * @Assert\GreaterThan(0)
     */
    private int $authorId;

    /**
     * @Assert\Length(max="255", min="3")
     * @Assert\Type(type="string")
     * @Assert\NotBlank()
     */
    private string $ruName;

    /**
     * @Assert\Length(max="255", min="3")
     * @Assert\Type(type="string")
     * @Assert\NotBlank()
     */
    private string $enName;

    public function __construct(Request $request)
    {
        $data = json_decode($request->getContent(), true, 3, JSON_THROW_ON_ERROR);
        $this->authorId = $data['authorId'] ?? 0;
        $this->ruName = $data['ruName'] ?? '';
        $this->enName = $data['enName'] ?? '';
    }

    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    public function getRuName(): string
    {
        return $this->ruName;
    }

    public function getEnName(): string
    {
        return $this->enName;
    }
}