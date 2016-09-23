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
        : base_path('vendor/ozanmuyes/laravel-stubs/resources/stubs');
    }

    return self::normalizePath($path);
  }

  /**
   * Truncates given subjects considering searches if any matches.
   *
   * @param string|array $searches Namespace, or Namespace and Imports
   * @param string|array $subjects Imports for Namespace,
   *                               Namespace and Imports for Extends
   * @param bool         $mustReturnArray
   *
   * @return array|string
   * @throws \Exception
   */
  public static function truncateFullyQualifiedNamespace($searches, $subjects, bool $mustReturnArray = false) {
    if (is_string($subjects)) {
      if ('' === $subjects) {
        return [];
      }

      $subjects = [$subjects];
    } elseif (is_array($subjects)) {
      if (count($subjects) === 0) {
        return [];
      }
    } else {
      throw new \Exception('Subjects MUST be either string or array, neither given.');
    }

    if (is_string($searches)) {
      $searches = [$searches];
    }

    $results = [];
    $searchesCount = count($searches);

    foreach ($subjects as $subject) {
      for ($i = 0; $i < $searchesCount; $i++) {
        $pos = strpos($subject, $searches[$i]);

        if (false === $pos) {
          if ($i + 1 === $searchesCount) {
            // We exhausted the `searches` list with `subject`, add `subject` to `result`
            // In order to not to lose it even though we are unable to truncate it.
            $results[] = $subject;
          } else {
            continue;
          }
        } else {
          $trimmed = trim(substr($subject, strlen($searches[$i])), '\\');

          // If the `subject` and the `search` are equal just get class name from `subject`
          if ('' === $trimmed) {
            $trimmed = trim(substr($subject, strrpos($subject, '\\')), '\\');
          }

          $results[] = $trimmed;

          // We have successfully truncate the `subject`, so there is no need to look further
          break;
        }
      }
    }

    if ($mustReturnArray) {
      return $results;
    }

    switch (count($results)) {
      case 1: {
        if ($mustReturnArray) {
          return $results;
        } else {
          return $results[0];
        }
      }

      default: // 2 or more
        return $results;
    }
  }
}
