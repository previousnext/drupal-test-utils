# Drupal Test Utils

Utilities for testing Drupal!

## ConfigTrait

A trait to facilitate overriding config for the duration of a test.

### Usage

Add the trait to your base class and override the tearDown method.

```php
use PNX\DrupalTestUtils\Traits\ConfigTrait;
use weitzman\DrupalTestTraits\ExistingSiteBase;

abstract class MyBaseClass extends ExistingSiteBase {

  /**
   * {@inheritdoc}
   */
  protected function tearDown(): void {
    $this->setConfigValues($this->originalConfiguration, FALSE);
    parent::tearDown();
  }

}
```  

In a test case, call `$this->setConfigValues`:

```php
$this->setConfigValues([
  'system.logging' => [
    'error_level' => \ERROR_REPORTING_DISPLAY_VERBOSE,
  ],
]);
```


## ExpectsCacheableResponseTrait

A trait to add Dynamic Page Cache cacheability testing to every request in your Functional tests.

### Usage

Once your trait is added to your test base class, you can check cachability by overriding the `drupalGet` function.

```php
/**
 * {@inheritdoc}
 */
protected function drupalGet($path, array $options = [], array $headers = []): string {
  $response = parent::drupalGet($path, $options, $headers);
  $this->detectUncacheableResponse($path, $options);
  return $response;
}
```

### Marking paths as uncacheable

There are some paths that are always uncacheable (i.e pages with forms like node/add). These paths can marked as uncacheable
by adding the `$uncacheableDynamicPagePatterns` property to your tests. You can add a common set of these to your test base
and add more specific paths in indivdual tests as these patterns will be gathered up the class hierarchy at run time.

Patterns are regular expressions matched with `preg_match`

E.g
```php
 protected static array $uncacheableDynamicPagePatterns = [
   '/user/login',
   '/big_pipe/no-js',
   '/batch',
   '/node/add/.*',
 ];
```
