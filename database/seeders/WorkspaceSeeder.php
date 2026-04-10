<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Workspace;
use App\Models\Apitoken;
use App\Models\Quota;
use Illuminate\Support\Str;

class WorkspaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $workspaces = [
                [
                    'name' => "My First Project",
                    'description' => fake()->paragraph(),
                ],
                [
                    'name' => fake()->sentence(),
                    'description' => fake()->paragraph(),
                ],
                [
                    'name' => fake()->sentence(),
                    'description' => null,
                ],
                [
                    'name' => fake()->sentence(),
                    'description' => fake()->paragraph(),
                ],
                [
                    'name' => fake()->sentence(),
                    'description' => null,
                ],
            ];

            foreach ($workspaces as $data) {
                $workspace = Workspace::create([
                    'user_id' => $user->id,
                    'name' => $data['name'],
                    'description' => $data['description'],
                ]);

                Apitoken::create([
                    'workspace_id' => $workspace->id,
                    'name' => fake()->word(),
                    'token' => bin2hex(Str::random(40)),
                ]);

                Apitoken::create([
                    'workspace_id' => $workspace->id,
                    'name' => fake()->word(),
                    'token' => bin2hex(Str::random(40)),
                    'revoked_at' => now(),
                ]);

                if ($data['name'] === "My First Project") {
                    Quota::create([
                        'workspace_id' => $workspace->id,
                        'monthly_limit' => 50.00,
                    ]);
                }
            }
        }
    }
}
