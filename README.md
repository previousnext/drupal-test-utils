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

## EntityLoadTrait

A trait to assist with loading entities in tests.

### Usage

Load one node with the title "Hello, World":

```php
$node = $this->loadEntityByProperty('node', ['title' => 'Hello, World']);
```

Load all article nodes:

```php
$nodes = $this->loadEntityByProperty('node', ['type' => 'article'], FALSE);
```

Get the last created node. Useful for asserting on entities created via the UI:

```php
$node = $this->getLastCreatedEntity('node');
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

## CsvDownloadTrait

A trait to interact with Views CSV exports making it easy to make assertions on CSV output.

This trait requires the `league/csv` library which is included in the views_data_export module (via csv_serialization).

### Usage

Once your trait is added to your test base class, you can get CSV output into a variable and then make assertions on the contents.

```php
$this->drupalGet('admin/content');
// Pass TRUE if your CSV is batched, otherwise FALSE.
$csv = $this->assertViewsCsvExportRowCount(3, TRUE);
$csv->next();
$row = $csv->current();
$this->assertEquals([
  'Title' => 'Foo bar',
  'Content type' => 'Basic',
  'Status' => 'Published',
]);
```
