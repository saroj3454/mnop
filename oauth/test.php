<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://clever.com/oauth/tokens',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
"code": "d05229357cd53581105c",
"grant_type": "authorization_code",
"redirect_uri": "https://staging.coremathstandards.com/admin/oauth2callback.php"
}',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic '.base64_encode("94f269d8af3caf1e0ad6:ab64301982ee614ffc446c1b40db1a2937d97540"),
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
