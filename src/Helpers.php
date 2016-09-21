<?php

namespace Ozanmuyes\Stubs;

use Illuminate\Support\Str;

class Helpers {
  /**
   * Normalizes the path string. Normalization means
   * removing trailing slashes, using forward
   * slashes (*nix style directory separator).
   *
   * @param string $path
   *
   * @return string
   */
  private static function normalizePath($path) {
    // Remove leading and trailing slashes (back and forward)
    $path = trim($path, '\\/');

    // If path is relative make it absolute
    if (!Str::startsWith($path, base_path())) {

      $path = base_path($path);
    }

    // Replace '\' with '/'
    $path = str_replace('\\', '/', $path);

    return $path;
  }

  /**
   * Returns stubs absolute path of the stubs
   * directory considering various settings.
   * Returning path string will not have trailing slashes.
   *
   * @return string
   */
  public static function getStubsDirectory() {
    // First try to get path information from `config/stubs.php`,
    // if somehow can not obtain the value check if the stubs were
    // published to `resources/stubs` directory, and if not published
    // fallback to package's stubs directory.
    $path = config('stubs.path', null);

    if (null === $path) {
      $path = file_exists(resource_path('stubs'))
        ? resource_path('stubs')
        : base_path('vendor/ozanmuyes/stubs/resources/stubs');
    }

    return self::normalizePath($path);
  }

  /**
   * Remove first characters from $haystack where there is corresponding $needle characters.
   * If they are equals take action depending on $preserveEndIfEqualsAfterLast;
   * - If set preserve $haystack characters after the last occurrence of
   *   $preserveEndIfEqualsAfterLast if found.
   *   If not found return $haystack.
   * - If not set return empty string
   *
   * @param        $haystack
   * @param        $needle
   * @param string $preserveEndIfEqualsAfterLast
   *
   * @return string
   */
  private static function truncateFromStart($haystack, $needle, $preserveEndIfEqualsAfterLast = '') {
    $haystack = trim($haystack, '\\');
    $needle = trim($needle, '\\');

    if ($haystack === $needle) {
      if ('' === $preserveEndIfEqualsAfterLast) {
        return '';
      } else {
        $lastPos = strrpos($haystack, $preserveEndIfEqualsAfterLast);

        if (false === $lastPos) {
          return $haystack;
        }

        return substr($haystack, $lastPos + 1);
      }
    }

    if (starts_with($haystack, $needle)) {
      return substr($haystack, strlen($needle) + 1);
    }

    return $haystack;
  }

  /**
   * Iterates each item in $haystack to truncate if there is corresponding item in $needle
   *
   * @param array|string $haystack          The item list of truncated values
   * @param array|string $needle            The unchanged list to check each item in $haystack
   * @param string       $trimCharList      Character list for trim
   * @param bool         $pickBestCandidate If just one value is wanted as result, pick the smallest and most likely to
   *                                        $haystack
   *
   * @return array|null|string
   */
  public static function arr_substr($haystack, $needle, $trimCharList = ' \t\n\r\0\x0B', $pickBestCandidate = true) {
    $result = [];

    if (is_string($haystack)) {
      $haystack = [$haystack];
    }

    if (is_string($needle)) {
      $needle = [$needle];
    }

    $checkCandidates = [];
    $temp = '';

    foreach ($haystack as $truncated) {
      foreach ($needle as $check) {
        $temp = trim(self::truncateFromStart($truncated, $check, '\\'), $trimCharList);

        if ('' === $temp) { // or if ('' === $temp || $temp == $truncated) {
          continue;
        }

        $result[] = $temp;
        $checkCandidates[$truncated][] = $temp;
      }
    }

    switch (count($result)) {
      case 0:
        return '';

      case 1:
        return $result[0];
    }

    if ($pickBestCandidate) {
      $bestCandidates = [];
      $bestCandidate = '';

      foreach ($checkCandidates as $check => $candidates) {
        foreach ($candidates as $candidate) {
          if ('' === $bestCandidate) {
            $bestCandidate = $candidate;

            continue;
          }

          if (
            strlen($candidate) < strlen($bestCandidate) &&
            str_contains($bestCandidate, $candidate)
          ) {
            $bestCandidate = $candidate;
          }
        }

        $bestCandidates[] = $bestCandidate;
        $bestCandidate = '';
      }

      $result = $bestCandidates;
    }

    switch (count($result)) {
      case 1:
        return $result[0];

      default:
        return $result;
    }
  }
}
