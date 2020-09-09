<?php

namespace TrenchDevs\Csv;

use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Reader\CSV\Reader;
use Box\Spout\Reader\CSV\RowIterator;
use Box\Spout\Reader\CSV\Sheet;
use Box\Spout\Reader\CSV\SheetIterator;
use Box\Spout\Reader\Exception\ReaderNotOpenedException;
use Generator;
use InvalidArgumentException;

class CsvReader
{
    /**
     * Full file path
     * @var string|null
     */
    private $csvFilePath = null;
    /**
     * @var Reader|null
     */
    private $reader = null;
    /**
     * true - use the first row as the headers and keys of the associative array yielded
     * @var bool
     */
    private $firstRowAsHeaders = true;

    /**
     * Headers used for the associative array
     * @var array
     */
    private $headers;
    /**
     * @var SheetIterator
     */
    private $sheetIterator;
    /**
     * @var RowIterator
     */
    private $rowIterator;

    /**
     * @var bool
     */
    private $shouldTrimAllValues = true;

    /**
     * @return string|null
     */
    public function getCsvFilePath(): ?string
    {
        return $this->csvFilePath;
    }

    /**
     * CsvReader constructor.
     * @param string $csvFilePath
     * @throws IOException|ReaderNotOpenedException
     * @throws InvalidArgumentException
     */
    public function __construct(string $csvFilePath)
    {

        if (!file_exists($csvFilePath)) {
            throw new InvalidArgumentException("File not found");
        }

        // open file using spout
        $reader = ReaderEntityFactory::createCSVReader();
        $reader->open($csvFilePath);

        $this->csvFilePath = $csvFilePath;
        // initialize spout objects
        $this->reader = $reader;
        $this->sheetIterator = $this->reader->getSheetIterator();
        $this->rowIterator = $this->sheetIterator->current()->getRowIterator();
    }

    protected function seekToFirstSheet(): void
    {
        $this->sheetIterator->rewind();
    }

    /**
     * @return Sheet|mixed
     */
    protected function getFirstSheet(): Sheet
    {
        $this->seekToFirstSheet();
        return $this->sheetIterator->current();
    }

    protected function seekToFirstRow(): void
    {
        $this->rowIterator->rewind();
    }

    /**
     * @return Row
     */
    protected function getFirstRow(): Row
    {
        $this->seekToFirstRow();
        return $this->rowIterator->current();
    }

    /**
     * @return Generator
     * @throws InvalidArgumentException
     */
    public function iterator(): Generator
    {

        $this->seekToFirstSheet();
        $this->seekToFirstRow();

        foreach ($this->getFirstSheet()->getRowIterator() as $index => $row) {

            $rowArr = $row->toArray();

            if ($this->shouldTrimAllValues) {
                $this->trimAllValues($rowArr);
            }

            if ($index === 1 && $this->firstRowAsHeaders && empty($this->headers)) {
                // Set the first row as the headers
                $this->headers = $rowArr;
            } else {
                // format and yield row array to caller fn
                yield $this->formatArray($rowArr);
            }

            $rowArr = null;
            unset($rowArr);
            continue;
        }
    }

    /**
     * @param array $values
     */
    private function trimAllValues(array &$values): void
    {
        foreach ($values as $key => $value) {

            if (is_string($value)) {
                $values[$key] = trim($value);
            }
        }
    }

    /**
     * @return bool
     */
    public function isFirstRowAsHeaders(): bool
    {
        return $this->firstRowAsHeaders;
    }

    /**
     * @param bool $firstRowAsHeaders
     * @return CsvReader
     */
    public function setFirstRowAsHeaders(bool $firstRowAsHeaders): CsvReader
    {
        $this->firstRowAsHeaders = $firstRowAsHeaders;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param mixed $headers
     * @return CsvReader
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @param array $rowArr
     * @return array
     * @throws InvalidArgumentException
     */
    private function formatArray(array $rowArr): array
    {

        if (!empty($this->headers)) {
            return $this->asAssociativeArray($rowArr);
        }

        return $rowArr;
    }

    /**
     * @param array $rowArr
     * @return array
     * @throws InvalidArgumentException
     *
     */
    private function asAssociativeArray(array $rowArr): array
    {
        $headerCount = count($this->headers);

        if (count($this->headers) > count($rowArr)) {
            $rowArr = array_pad($rowArr, $headerCount, '');
        }

        if ($headerCount !== count($rowArr)) {
            throw new InvalidArgumentException("Malformed csv, number of columns for each row cannot be greater than the number of column headers");
        }

        // set the headers
        return array_combine($this->headers, $rowArr);
    }

}