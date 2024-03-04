<?php

namespace App\Tasks\TestSsl;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GenerateHeaderSummaryInformationFromScanDataTask
{
    public static function run(Request $request, $scanData): array
    {

        $scanTopicResultArray = config('scanHeader.topicResultArray');
        $severityPriorityArray = config('scanHeader.severityPriorityArray');


        // If we have valid json data
        if (!empty($scanData['data']['json']['scanResult'][0]) && is_array($scanData['data']['json']['scanResult'][0])) {

            // Generate the header summary information from the JSON scan data
            foreach ($scanData['data']['json']['scanResult'][0] as $scanResultTopicKey => $scanResultTopicArray) {
                // if the scan topic is to be ignored or the scan topic array is not array, skip it
                if (!empty($scanTopicResultArray[$scanResultTopicKey]['ignore']) || !is_array($scanResultTopicArray)) {
                    continue;
                }
                foreach ($scanResultTopicArray as $topicItemArray) {
                    if (!empty($topicItemArray['severity'])) {
                        // current topic severity is empty OR current topic severity is higher than the current topic item severity
                        if (
                            empty($scanTopicResultArray[$scanResultTopicKey]['severity'])
                            || $severityPriorityArray[$topicItemArray['severity']]['priority'] > $severityPriorityArray[$scanTopicResultArray[$scanResultTopicKey]['severity']]['priority']
                        ) {
                            $scanTopicResultArray[$scanResultTopicKey]['severity'] = $topicItemArray['severity'];
                            $scanTopicResultArray[$scanResultTopicKey]['findingString'] = $severityPriorityArray[$topicItemArray['severity']]['name'] ?? $topicItemArray['severity'];
                            $scanTopicResultArray[$scanResultTopicKey]['color'] = $severityPriorityArray[$topicItemArray['severity']]['color'];
                        }
                        if (!empty($topicItemArray['id']) && $topicItemArray['id'] == 'overall_grade') {
                            $scanTopicResultArray[$scanResultTopicKey]['findingString'] .= " (" . $topicItemArray['finding'] . ")";
                        }
                    }
                }

            }

        } // end if we have valid json data

        // Remove empty scan topics or topics with severity below defined threshold
        $scanTopicResultArray = self::removeEmptyAndBelowThresholdTopics($scanTopicResultArray, $severityPriorityArray);

        // add the scan topic results to the json
        $scanData['data']['json']['generatedHeaderSummary'] = $scanTopicResultArray;

        // add the scan results to the html
        if (!empty($scanData['data']['html'])) {
            $scanData['data']['html'] = self::addHeaderToHtml($scanData['data']['html'], $scanTopicResultArray);
        }

        return $scanData;
    }

    /**
     * @param string $html
     * @param array $scanTopicResultArray
     * @return string
     */
    private static function addHeaderToHtml(string $html, array $scanTopicResultArray): string
    {
        $reportColorArray = config('scanHeader.reportColorArray');

        $generatedHeaderSummaryHtml = "</span>\n\n<span style=\"text-decoration:underline;font-weight:bold;\"> Scanning summary </span>\n";

        foreach ($scanTopicResultArray as $scanTopicKey => $scanTopicArray) {
            $generatedHeaderSummaryHtml .=
                "\n<span style=\"font-weight:bold;\"> " . str_pad($scanTopicArray['name'], 23) . " </span>" .
                "<span style=\"color:" . ($reportColorArray[$scanTopicArray['color']] ?? $scanTopicArray['color']) . ";font-weight:bold;\">" . $scanTopicArray['findingString'] . "</span>";
        }


        // Add header to existing html result, just after the first span
        return Str::replaceFirst('</span>', $generatedHeaderSummaryHtml, $html);
    }

    /**
     * remove empty results or results with a severity below the ignoreBelowSeverityPriority
     * @param array $scanTopicResultArray
     * @param array $severityPriorityArray
     * @return array
     */
    private static function removeEmptyAndBelowThresholdTopics(array $scanTopicResultArray, array $severityPriorityArray): array
    {
        foreach ($scanTopicResultArray as $scanTopicKey => $scanTopicArray) {
            if (empty($scanTopicArray['severity'])
                || (
                    !empty($scanTopicArray['ignoreBelowSeverityPriority'])
                    && $severityPriorityArray[$scanTopicArray['severity']]['priority'] < $scanTopicArray['ignoreBelowSeverityPriority']
                )
            ) {
                unset($scanTopicResultArray[$scanTopicKey]);
            }
        }
        return $scanTopicResultArray;
    }
}
