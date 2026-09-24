<?php

namespace Tests\Unit;

use App\Support\WarnaPalet;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class WarnaPaletTest extends TestCase
{
    public function test_stored_hex_has_priority_and_is_normalized(): void
    {
        $this->assertSame('#c62828', WarnaPalet::resolve('#C62828', 'Biru'));
    }

    public function test_palette_lookup_is_case_insensitive(): void
    {
        $this->assertSame('#c62828', WarnaPalet::resolve('', 'MERAH'));
        $this->assertSame('Merah', WarnaPalet::canonicalName('  merah  '));
    }

    public function test_unknown_color_returns_null_and_keeps_name(): void
    {
        $this->assertNull(WarnaPalet::resolve('', 'Tosca Elektrik'));
        $this->assertSame('Tosca Elektrik', WarnaPalet::canonicalName('Tosca Elektrik'));
    }

    public function test_submission_normalizes_official_names_and_hex(): void
    {
        $result = WarnaPalet::normalizeSubmissionOrFail([' merah '], ['']);

        $this->assertSame(['Merah'], $result['names']);
        $this->assertSame(['#c62828'], $result['hexes']);
    }

    public function test_submission_rejects_custom_color_without_hex(): void
    {
        try {
            WarnaPalet::normalizeSubmissionOrFail(['Tosca Elektrik'], ['']);
            $this->fail('Warna custom tanpa hex harus ditolak.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('warna.0', $e->errors());
        }
    }

    public function test_optional_submission_allows_missing_or_blank_colors(): void
    {
        $this->assertSame(['names' => [], 'hexes' => []], WarnaPalet::normalizeOptionalSubmissionOrFail(null, null));
        $this->assertSame(['names' => [], 'hexes' => []], WarnaPalet::normalizeOptionalSubmissionOrFail(['', '   '], []));
    }
}
