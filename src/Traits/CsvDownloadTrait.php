<?php

declare(strict_types=1);

namespace PNX\DrupalTestUtils\Traits;

use League\Csv\CharsetConverter;
use League\Csv\Reader;

/**
 * CSV download trait.
 */
trait CsvDownloadTrait {

  /**
   * Asserts that a CSV export has a certain number of rows.
   *
   * Clicks the orange 'CSV' button displayed in views, waits for batch to run,
   * then asserts contents of download.
   */
  protected function assertViewsCsvExportRowCount(int $count, bool $isBatch = TRUE): Reader {
    $this->clickLink('Download CSV');
    $csvReader = $this->waitForCsv($isBatch);
    $this->assertCount($count, $csvReader->getRecords());
    return $csvReader;
  }

  /**
   * Asserts that a CSV export has a certain number of rows.
   *
   * Clicks the orange 'CSV' button displayed in views, waits for batch to run,
   * then asserts contents of download.
   */
  protected function waitForCsv(bool $isBatch = TRUE): Reader {
    if ($isBatch) {
      $this->checkForMetaRefresh();
      $this->assertSession()->pageTextContains('Export complete. Download the file here.');
      $this->clickLink('here');
    }
    $csv = $this->getSession()
      ->getDriver()
      ->getContent();
    $csvReader = Reader::createFromString($csv);
    if ($csvReader->getInputBOM() === Reader::BOM_UTF16_LE) {
      CharsetConverter::addTo($csvReader, 'UTF-16', 'UTF-8');
    }
    $csvReader->setHeaderOffset(0);
    return $csvReader;
  }

}
