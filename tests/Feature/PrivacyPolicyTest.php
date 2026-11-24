<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrivacyPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_privacy_policy_explains_data_deletion_and_is_linked_from_the_footer(): void
    {
        $this->get(route('privacy'))
            ->assertOk()
            ->assertSee('Privacy Policy')
            ->assertSee('Deleting your data')
            ->assertSee('mailto:info@afghanwomeninexile.org?subject=Data%20deletion', false);

        $this->get(route('home'))->assertSee(route('privacy'), false);
        $this->get(route('fa.privacy'))->assertOk();
    }
}
