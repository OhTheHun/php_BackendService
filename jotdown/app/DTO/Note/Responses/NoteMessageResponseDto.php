<?php

namespace App\DTO\Note\Responses;

class NoteMessageResponseDto
{
    public function __construct(
        public readonly string $message,
        public readonly ?NoteResponseDto $note = null,
    ) {}

    public function toArray(): array
    {
        $data = ['message' => $this->message];

        if ($this->note !== null) {
            $data['note'] = $this->note->toArray(false);
        }

        return $data;
    }
}
