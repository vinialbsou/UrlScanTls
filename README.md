Project Url Scan


Introduction

Scan is a micro-service that execute a command in Script Shell of TestSsl, with objective to check the URL and server's service TLS/SSL ciphers, protocols as well as some cryptographic flaws.
Endpoints created

CreateScan: Allow user to input parameters (hostname,optionsSetting,protocols,priority,ignoreCache,protocol,port and scanningType) that will define how the hostname will be scanned.
Validate the requests due to not allow dangerous command or requests;
Save those requested selected by user, in a cache and database;
Check if the hostname already in the queu to scan;
Api not allowed scanning the same hostname twice in the same time;
For scan the same hostname again. Should wait to finish the current one;
User could request for scan with the same hostname. In that case the micro-service will check if the settings has already been selected. It'll run new scan or return the same information;
Prepare the command for TestSsl.sh script and dispatch the command to scan the URL, in a queue job;
Job use Process Symfony for run a script shell;
Return reportCode as parameters;
GetScan: Use reportCode parameter from CreateScan, in purpose to follow the scan and return the selected hostname vulnerabilities report.
Validate the reportCode and output format parameters is allowed;
Check if the reportCode request not started yet;
Get the data report from a html and json file and add a footer that will give a credits to a scan script owner;
Generate status and progress from scan data;
Return the status progress and the data report;
GetScanStatus: Use reportCode for check the status scan requested
Validate the request is allowed
Check and return scan status running;
GetScan already do the same. Right now the route is not using;
GetProperties: Properties for front that let user to choose for start the scan
Return default options for protocols;
Return default format file allowed;
