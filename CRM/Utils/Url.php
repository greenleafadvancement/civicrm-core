<?php
/*
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC. All rights reserved.                        |
 |                                                                    |
 | This work is published under the GNU AGPLv3 license with some      |
 | permitted exceptions and without any warranty. For full license    |
 | and copyright information, see https://civicrm.org/licensing       |
 +--------------------------------------------------------------------+
 */

use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\UriInterface;

class CRM_Utils_Url {

  /**
   * Parse url to a UriInterface.
   *
   * @param string $url
   *
   * @return \Psr\Http\Message\UriInterface
   */
  public static function parseUrl($url) {
    return new Uri($url);
  }

  /**
   * Unparse url back to a string.
   *
   * @param \Psr\Http\Message\UriInterface $parsed
   *
   * @return string
   */
  public static function unparseUrl(UriInterface $parsed) {
    return $parsed->__toString();
  }

  /**
   * Convert to a relative URL (if host/port matches).
   *
   * @param string $value
   * @param string|null $currentHostPort
   *   The value of HTTP_HOST. (NULL means "lookup HTTP_HOST")
   * @return string
   *   Either the relative version of $value (if on the same HTTP_HOST), or else
   *   the absolute version.
   */
  public static function toRelative(string $value, ?string $currentHostPort = NULL): string {
    $currentHostPort = $currentHostPort ?: $_SERVER['HTTP_HOST'] ?? NULL;

    if (preg_match(';^(//|http://|https://)([^/]*)(.*);', $value, $m)) {
      if ($m[2] === $currentHostPort) {
        return $m[3];
      }
    }

    return $value;
  }

  /**
   * Determine if $child is a descendent of $parent.
   *
   * Relative URLs mean that multiple strings may not
   *
   * @param string|\Psr\Http\Message\UriInterface|\Civi\Core\Url $child
   * @param string|\Psr\Http\Message\UriInterface|\Civi\Core\Url $parent
   * @return bool
   */
  public static function isChildOf($child, $parent): bool {
    $childRel = static::toRelative((string) $child);
    $parentRel = static::toRelative((string) $parent);
    return str_starts_with($childRel, $parentRel);
  }

}
