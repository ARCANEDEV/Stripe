<?php namespace Arcanedev\Stripe\Tests;

use Arcanedev\Stripe\Webhook;
use Arcanedev\Stripe\WebhookSignature;

/**
 * Class     WebhookTest
 *
 * @package  Arcanedev\Stripe\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class WebhookTest extends StripeTestCase
{
    /* -----------------------------------------------------------------
     |  Constants
     | -----------------------------------------------------------------
     */

    const EVENT_PAYLOAD = "{
  \"id\": \"evt_test_webhook\",
  \"object\": \"event\"
}";

    const SECRET = 'whsec_test_secret';

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_process_with_valid_json_and_header()
    {
        $sigHeader = $this->generateHeader();
        $event     = Webhook::constructEvent(self::EVENT_PAYLOAD, $sigHeader, self::SECRET);

        $this->assertSame('evt_test_webhook', $event->id);
    }

    /**
     * @test
     *
     * @expectedException \UnexpectedValueException
     */
    public function it_must_fails_with_invalid_json()
    {
        $payload   = 'this is not valid JSON';
        $sigHeader = $this->generateHeader(['payload' => $payload]);

        Webhook::constructEvent($payload, $sigHeader, self::SECRET);
    }

    /**
     * @test
     *
     * @expectedException  \Arcanedev\Stripe\Exceptions\SignatureVerificationException
     */
    public function it_must_fails_with_valid_json_and_invalid_header()
    {
        $sigHeader = 'bad_header';

        Webhook::constructEvent(self::EVENT_PAYLOAD, $sigHeader, self::SECRET);
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\Stripe\Exceptions\SignatureVerificationException
     * @expectedExceptionMessage  Unable to extract timestamp and signatures from header
     */
    public function it_must_fail_with_malformed_header()
    {
        $sigHeader = "i'm not even a real signature header";

        WebhookSignature::verifyHeader(self::EVENT_PAYLOAD, $sigHeader, self::SECRET);
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\Stripe\Exceptions\SignatureVerificationException
     * @expectedExceptionMessage  No signatures found with expected scheme
     */
    public function it_must_fail_with_no_signatures_with_expected_scheme()
    {
        $sigHeader = $this->generateHeader(['scheme' => 'v0']);

        WebhookSignature::verifyHeader(self::EVENT_PAYLOAD, $sigHeader, self::SECRET);
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\Stripe\Exceptions\SignatureVerificationException
     * @expectedExceptionMessage  No signatures found matching the expected signature for payload
     */
    public function it_must_fail_with_no_valid_signature_for_payload()
    {
        $sigHeader = $this->generateHeader(['signature' => 'bad_signature']);

        WebhookSignature::verifyHeader(self::EVENT_PAYLOAD, $sigHeader, self::SECRET);
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\Stripe\Exceptions\SignatureVerificationException
     * @expectedExceptionMessage  Timestamp outside the tolerance zone
     */
    public function it_must_fail_with_timestamp_outside_tolerance()
    {
        $sigHeader = $this->generateHeader(['timestamp' => time() - 15]);

        WebhookSignature::verifyHeader(self::EVENT_PAYLOAD, $sigHeader, self::SECRET, 10);
    }

    /** @test */
    public function it_can_verify_with_valid_header_and_signature()
    {
        $sigHeader = $this->generateHeader();

        $this->assertTrue(WebhookSignature::verifyHeader(self::EVENT_PAYLOAD, $sigHeader, self::SECRET, 10));
    }

    /** @test */
    public function it_can_verify_header_contains_valid_signature()
    {
        $sigHeader = $this->generateHeader().',v1=bad_signature';

        $this->assertTrue(WebhookSignature::verifyHeader(self::EVENT_PAYLOAD, $sigHeader, self::SECRET, 10));
    }

    /** @test */
    public function it_can_verify_timestamp_off_but_no_tolerance()
    {
        $sigHeader = $this->generateHeader(['timestamp' => 12345]);

        $this->assertTrue(WebhookSignature::verifyHeader(self::EVENT_PAYLOAD, $sigHeader, self::SECRET));
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Generate a header.
     *
     * @param  array  $options
     *
     * @return string
     */
    private function generateHeader(array $options = [])
    {
        $timestamp = array_key_exists('timestamp', $options) ? $options['timestamp'] : time();
        $payload   = array_key_exists('payload', $options)   ? $options['payload']   : self::EVENT_PAYLOAD;
        $secret    = array_key_exists('secret', $options)    ? $options['secret']    : self::SECRET;
        $scheme    = array_key_exists('scheme', $options)    ? $options['scheme']    : WebhookSignature::EXPECTED_SCHEME;
        $signature = array_key_exists('signature', $options) ? $options['signature'] : null;

        if (is_null($signature)) {
            $signature = hash_hmac('sha256', "$timestamp.$payload", $secret);
        }

        return "t=$timestamp,$scheme=$signature";
    }
}
