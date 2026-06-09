<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SampleData20260609173831Seeder extends Seeder
{
    private const USER_ID = '019ea82b-9552-71b6-b3fb-ad027c76849e';

    public function run(): void
    {
        $now = '2026-06-09 17:38:31';

        DB::transaction(function () use ($now): void {
            $this->seedPlans($now);
            $userEmail = $this->seedUser($now);
            $this->seedPasswordResetToken($now, $userEmail);
            $this->seedWorkspaces($now, $userEmail);
            $this->seedFolders($now, $userEmail);
            $this->seedLabels($now, $userEmail);
            $this->seedNotes($now, $userEmail);
            $this->seedNoteLabels();
            $this->seedAttachments($now, $userEmail);
            $this->seedShares($now, $userEmail);
            $this->seedPayments($now, $userEmail);
            $this->seedReports($now, $userEmail);
            $this->seedActivityLogs($now);
        });
    }

    private function seedPlans(string $now): void
    {
        foreach ([
            [
                'Id' => '10000000-0000-7000-8000-000000000001',
                'name' => 'Free',
                'price' => 0,
                'max_notes' => 100,
                'max_workspaces' => 3,
                'max_attachment_size' => 5,
                'can_export' => false,
            ],
            [
                'Id' => '10000000-0000-7000-8000-000000000002',
                'name' => 'Premium',
                'price' => 99000,
                'max_notes' => null,
                'max_workspaces' => null,
                'max_attachment_size' => 25,
                'can_export' => true,
            ],
        ] as $plan) {
            DB::table('plans')->updateOrInsert(
                ['Id' => $plan['Id']],
                array_merge($plan, [
                    'CreatedBy' => 'Seeder',
                    'CreatedTime' => $now,
                    'UpdatedBy' => 'Seeder',
                    'UpdatedTime' => $now,
                    'DeleteFlag' => false,
                    'status' => true,
                ])
            );
        }
    }

    private function seedUser(string $now): string
    {
        $existingEmail = DB::table('users')
            ->where('Id', self::USER_ID)
            ->value('email');

        if (is_string($existingEmail) && $existingEmail !== '') {
            return $existingEmail;
        }

        $userEmail = 'sample-user@jotdown.local';

        DB::table('users')->insert([
            'Id' => self::USER_ID,
            'CreatedBy' => 'Seeder',
            'CreatedTime' => $now,
            'UpdatedBy' => 'Seeder',
            'UpdatedTime' => $now,
            'DeleteFlag' => false,
            'display_name' => 'Nguyen Van A',
            'email' => $userEmail,
            'password' => Hash::make('password123'),
            'role' => 'user',
            'status' => 'active',
            'avatar_url' => null,
            'theme' => 'dark',
            'font_size' => 'medium',
            'default_note_color' => '#ffffff',
            'plan_id' => '10000000-0000-7000-8000-000000000001',
            'email_verified_at' => $now,
            'last_login_at' => $now,
        ]);

        return $userEmail;
    }

    private function seedWorkspaces(string $now, string $userEmail): void
    {
        foreach ([
            ['Id' => '20000000-0000-7000-8000-000000000001', 'name' => 'Workspace ca nhan', 'description' => 'Khong gian ghi chu ca nhan'],
            ['Id' => '20000000-0000-7000-8000-000000000002', 'name' => 'Do an PHP', 'description' => 'Quan ly ghi chu cho do an'],
            ['Id' => '20000000-0000-7000-8000-000000000003', 'name' => 'Ke hoach hoc tap', 'description' => 'Theo doi cong viec hoc tap'],
        ] as $workspace) {
            DB::table('workspaces')->updateOrInsert(
                ['Id' => $workspace['Id']],
                array_merge($workspace, [
                    'CreatedBy' => $userEmail,
                    'CreatedTime' => $now,
                    'UpdatedBy' => $userEmail,
                    'UpdatedTime' => $now,
                    'DeleteFlag' => false,
                    'user_id' => self::USER_ID,
                ])
            );
        }
    }

    private function seedPasswordResetToken(string $now, string $userEmail): void
    {
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $userEmail],
            [
                'otp' => '123456',
                'token' => 'sample-reset-token-20260609173831',
                'created_at' => $now,
                'expires_at' => '2026-06-09 17:48:31',
                'is_used' => false,
            ]
        );
    }

    private function seedFolders(string $now, string $userEmail): void
    {
        foreach ([
            ['Id' => '30000000-0000-7000-8000-000000000001', 'workspace_id' => '20000000-0000-7000-8000-000000000001', 'name' => 'Y tuong'],
            ['Id' => '30000000-0000-7000-8000-000000000002', 'workspace_id' => '20000000-0000-7000-8000-000000000002', 'name' => 'Backend'],
            ['Id' => '30000000-0000-7000-8000-000000000003', 'workspace_id' => '20000000-0000-7000-8000-000000000003', 'name' => 'Deadline'],
        ] as $folder) {
            DB::table('folders')->updateOrInsert(
                ['Id' => $folder['Id']],
                array_merge($folder, [
                    'CreatedBy' => $userEmail,
                    'CreatedTime' => $now,
                    'UpdatedBy' => $userEmail,
                    'UpdatedTime' => $now,
                    'DeleteFlag' => false,
                    'user_id' => self::USER_ID,
                ])
            );
        }
    }

    private function seedLabels(string $now, string $userEmail): void
    {
        foreach ([
            ['Id' => '40000000-0000-7000-8000-000000000001', 'name' => 'Quan trong', 'color' => '#ef4444'],
            ['Id' => '40000000-0000-7000-8000-000000000002', 'name' => 'Y tuong', 'color' => '#3b82f6'],
            ['Id' => '40000000-0000-7000-8000-000000000003', 'name' => 'Can lam', 'color' => '#22c55e'],
        ] as $label) {
            DB::table('labels')->updateOrInsert(
                ['Id' => $label['Id']],
                array_merge($label, [
                    'CreatedBy' => $userEmail,
                    'CreatedTime' => $now,
                    'UpdatedBy' => $userEmail,
                    'UpdatedTime' => $now,
                    'DeleteFlag' => false,
                    'user_id' => self::USER_ID,
                ])
            );
        }
    }

    private function seedNotes(string $now, string $userEmail): void
    {
        foreach ([
            [
                'Id' => '50000000-0000-7000-8000-000000000001',
                'workspace_id' => '20000000-0000-7000-8000-000000000001',
                'folder_id' => '30000000-0000-7000-8000-000000000001',
                'title' => 'Y tuong ung dung JotDown',
                'content' => 'Them quick note, tag mau va tim kiem toan van.',
                'color' => '#ffffff',
                'is_pinned' => true,
                'pinned_at' => $now,
                'is_favorite' => true,
                'visibility' => 'private',
                'is_protected' => false,
                'archived_at' => null,
            ],
            [
                'Id' => '50000000-0000-7000-8000-000000000002',
                'workspace_id' => '20000000-0000-7000-8000-000000000002',
                'folder_id' => '30000000-0000-7000-8000-000000000002',
                'title' => 'Checklist API notes',
                'content' => 'Hoan thanh CRUD, pin, favorite, archive va filter.',
                'color' => '#FEF3C7',
                'is_pinned' => false,
                'pinned_at' => null,
                'is_favorite' => false,
                'visibility' => 'shared',
                'is_protected' => false,
                'archived_at' => null,
            ],
            [
                'Id' => '50000000-0000-7000-8000-000000000003',
                'workspace_id' => '20000000-0000-7000-8000-000000000003',
                'folder_id' => '30000000-0000-7000-8000-000000000003',
                'title' => 'Lich nop bai',
                'content' => 'Chuan bi demo va test Scribe docs.',
                'color' => '#DBEAFE',
                'is_pinned' => false,
                'pinned_at' => null,
                'is_favorite' => true,
                'visibility' => 'private',
                'is_protected' => false,
                'archived_at' => null,
            ],
        ] as $note) {
            DB::table('notes')->updateOrInsert(
                ['Id' => $note['Id']],
                array_merge($note, [
                    'CreatedBy' => $userEmail,
                    'CreatedTime' => $now,
                    'UpdatedBy' => $userEmail,
                    'UpdatedTime' => $now,
                    'DeleteFlag' => false,
                    'user_id' => self::USER_ID,
                    'note_password' => null,
                ])
            );
        }
    }

    private function seedNoteLabels(): void
    {
        foreach ([
            ['note_id' => '50000000-0000-7000-8000-000000000001', 'label_id' => '40000000-0000-7000-8000-000000000001'],
            ['note_id' => '50000000-0000-7000-8000-000000000001', 'label_id' => '40000000-0000-7000-8000-000000000002'],
            ['note_id' => '50000000-0000-7000-8000-000000000002', 'label_id' => '40000000-0000-7000-8000-000000000003'],
            ['note_id' => '50000000-0000-7000-8000-000000000003', 'label_id' => '40000000-0000-7000-8000-000000000001'],
        ] as $pivot) {
            DB::table('note_labels')->updateOrInsert($pivot, $pivot);
        }
    }

    private function seedAttachments(string $now, string $userEmail): void
    {
        foreach ([
            [
                'Id' => '60000000-0000-7000-8000-000000000001',
                'note_id' => '50000000-0000-7000-8000-000000000001',
                'file_name' => 'idea-board.png',
                'file_path' => 'https://res.cloudinary.com/dqicyabgq/image/upload/v1780000000/Jotdown/Attachment/idea-board.png',
                'file_size' => 120000,
                'file_type' => 'image/png',
                'attachment_kind' => 'image',
            ],
            [
                'Id' => '60000000-0000-7000-8000-000000000002',
                'note_id' => '50000000-0000-7000-8000-000000000002',
                'file_name' => 'api-checklist.pdf',
                'file_path' => 'https://example.com/files/api-checklist.pdf',
                'file_size' => 450000,
                'file_type' => 'application/pdf',
                'attachment_kind' => 'file',
            ],
        ] as $attachment) {
            DB::table('note_attachments')->updateOrInsert(
                ['Id' => $attachment['Id']],
                array_merge($attachment, [
                    'CreatedBy' => $userEmail,
                    'CreatedTime' => $now,
                    'UpdatedBy' => $userEmail,
                    'UpdatedTime' => $now,
                    'DeleteFlag' => false,
                ])
            );
        }
    }

    private function seedShares(string $now, string $userEmail): void
    {
        DB::table('note_shares')->updateOrInsert(
            ['Id' => '70000000-0000-7000-8000-000000000001'],
            [
                'CreatedBy' => $userEmail,
                'CreatedTime' => $now,
                'UpdatedBy' => $userEmail,
                'UpdatedTime' => $now,
                'DeleteFlag' => false,
                'note_id' => '50000000-0000-7000-8000-000000000002',
                'shared_by_user_id' => self::USER_ID,
                'shared_with_email' => 'reviewer@jotdown.local',
                'permission' => 'view',
                'share_token' => 'sample-share-token',
                'expires_at' => '2026-07-09 17:38:31',
                'revoked_at' => null,
            ]
        );
    }

    private function seedPayments(string $now, string $userEmail): void
    {
        DB::table('payments')->updateOrInsert(
            ['Id' => '80000000-0000-7000-8000-000000000001'],
            [
                'CreatedBy' => $userEmail,
                'CreatedTime' => $now,
                'UpdatedBy' => $userEmail,
                'UpdatedTime' => $now,
                'DeleteFlag' => false,
                'user_id' => self::USER_ID,
                'plan_id' => '10000000-0000-7000-8000-000000000002',
                'amount' => 99000,
                'status' => 'paid',
                'payment_method' => 'vnpay',
                'transaction_code' => 'VNPAY-SAMPLE-20260609',
                'paid_at' => $now,
            ]
        );
    }

    private function seedReports(string $now, string $userEmail): void
    {
        DB::table('reports')->updateOrInsert(
            ['Id' => '90000000-0000-7000-8000-000000000001'],
            [
                'CreatedBy' => $userEmail,
                'CreatedTime' => $now,
                'UpdatedBy' => $userEmail,
                'UpdatedTime' => $now,
                'DeleteFlag' => false,
                'note_id' => '50000000-0000-7000-8000-000000000002',
                'reporter_id' => self::USER_ID,
                'reason' => 'Du lieu mau cho chuc nang bao cao note.',
                'status' => 'pending',
                'admin_id' => null,
                'admin_notes' => null,
                'resolved_at' => null,
            ]
        );
    }

    private function seedActivityLogs(string $now): void
    {
        foreach ([
            ['Id' => 'a0000000-0000-7000-8000-000000000001', 'action' => 'create_note', 'target_type' => 'notes', 'target_id' => '50000000-0000-7000-8000-000000000001', 'description' => 'Tao note mau dau tien.'],
            ['Id' => 'a0000000-0000-7000-8000-000000000002', 'action' => 'share_note', 'target_type' => 'note_shares', 'target_id' => '70000000-0000-7000-8000-000000000001', 'description' => 'Chia se note mau cho reviewer.'],
        ] as $log) {
            DB::table('activity_logs')->updateOrInsert(
                ['Id' => $log['Id']],
                array_merge($log, [
                    'user_id' => self::USER_ID,
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'JotDown Seeder',
                    'created_at' => $now,
                ])
            );
        }
    }
}
