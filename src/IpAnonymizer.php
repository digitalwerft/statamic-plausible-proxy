<?php

namespace Digitalwerft\PlausibleProxy;

/**
 * Shortens visitor IPs before they are passed to Plausible:
 * IPv4 keeps the first three octets (/24), IPv6 the first 48 bits (/48).
 */
class IpAnonymizer
{
    public static function anonymize(?string $ip): ?string
    {
        $packed = $ip ? @inet_pton($ip) : false;

        if ($packed === false) {
            return null;
        }

        // IPv4-mapped IPv6 (::ffff:a.b.c.d) is treated as IPv4.
        if (strlen($packed) === 16 && str_starts_with($packed, str_repeat("\0", 10) . "\xff\xff")) {
            $packed = substr($packed, 12);
        }

        $keep = strlen($packed) === 4 ? 3 : 6;
        $masked = substr($packed, 0, $keep) . str_repeat("\0", strlen($packed) - $keep);

        return inet_ntop($masked);
    }
}
