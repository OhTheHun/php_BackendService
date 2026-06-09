<?php

namespace App\Services\Interface;

use App\DTO\Label\Requests\CreateLabelRequestDto;
use App\DTO\Label\Requests\DeleteLabelRequestDto;
use App\DTO\Label\Requests\ListLabelsRequestDto;
use App\DTO\Label\Requests\UpdateLabelRequestDto;
use App\DTO\Label\Responses\LabelMessageResponseDto;
use App\DTO\Label\Responses\ListLabelsResponseDto;

interface ILabelService
{
    public function list(ListLabelsRequestDto $request): ListLabelsResponseDto;

    public function create(CreateLabelRequestDto $request): LabelMessageResponseDto;

    public function update(UpdateLabelRequestDto $request): LabelMessageResponseDto;

    public function delete(DeleteLabelRequestDto $request): LabelMessageResponseDto;
}
