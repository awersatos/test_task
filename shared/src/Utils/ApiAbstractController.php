<?php


namespace App\Utils;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class ApiAbstractController extends AbstractController
{
    private ?EntityManagerInterface $em = null;
    private ?array $data;
    private ?string $errorMessage;
    private ?int $statusCode;
    private ?array $headers;

    public function createResponse(): JsonResponse
    {
        $data = $this->data ?? null;
        $errorMessage = $this->errorMessage ?? null;
        $statusCode = $this->statusCode ?? JsonResponse::HTTP_OK;
        $headers = $this->headers ?? [];
        return new JsonResponse(['data' => $data, 'error' => $errorMessage], $statusCode, $headers);
    }

    public function addData(string $dataName, $newData): self
    {
        $this->data[$dataName] = $newData;
        return $this;
    }

    public function addRootData(array $newData): self
    {
        $this->data = $newData;
        return $this;
    }

    public function addStatusCode(int $code): self
    {
        $this->statusCode = $code;
        return $this;
    }

    public function addHeader(string $headerName, $headerValue): self
    {
        $this->headers[$headerName] = $headerValue;
        return $this;
    }

    public function addErrorMessage(string $message): self
    {
        $this->errorMessage = $message;
        return $this;
    }

    public function setEntityManager(EntityManagerInterface $em): void
    {
        $this->em = $em;
    }

    protected function em(): EntityManagerInterface
    {
        return $this->em;
    }
}