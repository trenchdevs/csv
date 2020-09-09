<?php declare(strict_types=1);

use Box\Spout\Common\Exception\IOException;
use PHPUnit\Framework\TestCase;
use TrenchDevs\Csv\CsvReader;


/**
 * Class CsvReaderTest
 */
class CsvReaderTest extends TestCase
{

    private $validFilePath = __DIR__ . '/testfiles/valid.csv';
    private $validArrayColumns = ['id', 'name', 'age', 'tag line'];

    /** @test */
    public function constructorEmptyFilePathTest()
    {
        $this->expectException(InvalidArgumentException::class);
        new CsvReader('');
    }

    /** @test */
    public function constructorInvalidFilePathTest()
    {
        $this->expectException(InvalidArgumentException::class);
        new CsvReader(__DIR__. '/this/file/does/not/exists');
    }

    /** @test
     * @throws IOException
     */
    public function constructorValidFileTest()
    {
        $this->assertInstanceOf(CsvReader::class, new CsvReader($this->validFilePath));
    }

    /** @test
     * @throws IOException
     */
    public function canIterateItemsWithFirstRowAsHeaders()
    {

        $reader = new CsvReader($this->validFilePath);
        $reader->setFirstRowAsHeaders(true);
        $actualNumberOfLines = 0;
        $expectedNumberOfLines = 2;

        foreach ($reader->iterator() as $row) {
            $this->assertNotEmpty($row);
            foreach ($this->validArrayColumns as $column) {
                $this->assertArrayHasKey($column, $row);
            }
            $actualNumberOfLines++;
        }

        $this->assertEquals($expectedNumberOfLines, $actualNumberOfLines);
    }

    /** @test
     * @throws IOException
     */
    public function canIterateItems()
    {

        $reader = new CsvReader($this->validFilePath);
        $reader->setFirstRowAsHeaders(false);
        $actualNumberOfLines = 0;
        $expectedNumberOfLines = 3;

        foreach ($reader->iterator() as $row) {
            $this->assertNotEmpty($row);
            $actualNumberOfLines++;
        }

        $this->assertEquals($expectedNumberOfLines, $actualNumberOfLines);
    }

    /** @test
     * @throws IOException
     */
    public function canOverrideHeadersTest()
    {

        $headers = ['id', 'name', 'age', 'tagline', 'extraheader'];

        $reader = new CsvReader($this->validFilePath);
        $reader->setFirstRowAsHeaders(true)
            ->setHeaders($headers);
        $actualNumberOfLines = 0;
        $expectedNumberOfLines = 3;


        foreach ($reader->iterator() as $row) {
            $this->assertNotEmpty($row);
            foreach ($headers as $header) {
                $this->assertArrayHasKey($header, $row);
            }
            $actualNumberOfLines++;
        }

        $this->assertEquals($expectedNumberOfLines, $actualNumberOfLines);
    }

    /** @test
     * @throws IOException
     */
    public function canOverrideHeadersFirstRowAsHeadersFalseTest()
    {


        $reader = new CsvReader($this->validFilePath);
        $reader->setFirstRowAsHeaders(false);
        $actualNumberOfLines = 0;
        $expectedNumberOfLines = 3;


        foreach ($reader->iterator() as $index => $row) {
            $this->assertNotEmpty($row);
            $actualNumberOfLines++;
        }

        $this->assertEquals($expectedNumberOfLines, $actualNumberOfLines);
    }

}