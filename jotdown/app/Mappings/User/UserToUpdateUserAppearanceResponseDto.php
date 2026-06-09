<?php

namespace App\Mappings\User;

use App\DTO\User\Responses\UpdateUserAppearanceResponseDto;
use App\DTO\User\Responses\UpdateUserAppearanceSettingsResponseDto;
use App\Models\User;

class UserToUpdateUserAppearanceResponseDto
{
    public function transform(User $user): UpdateUserAppearanceResponseDto
    {
        return new UpdateUserAppearanceResponseDto(
            message: 'Appearance settings updated successfully.',
            settings: new UpdateUserAppearanceSettingsResponseDto(
                theme: $user->theme,
                fontSize: $user->font_size,
                defaultNoteColor: $user->default_note_color,
            ),
        );
    }
}
