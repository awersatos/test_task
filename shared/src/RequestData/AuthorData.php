<?php


namespace App\RequestData;

use App\Utils\DataTransferInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;

class AuthorData implements DataTransferInterface
{
    /**
     * @Assert\Length(max="255", min="3")
     * @Assert\Type(type="string")
     * @Assert\NotBlank()
     */
    private string $name;

    public function __construct(Request $request)
    {
        $data = json_decode($request->getContent(), true, 3, JSON_THROW_ON_ERROR);
        $this->name = $data['name'] ?? '';
    }

    public function getName(): string
    {
        return $this->name;
    }
}